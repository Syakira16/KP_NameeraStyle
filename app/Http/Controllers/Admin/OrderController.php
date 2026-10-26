<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


class OrderController extends Controller
{


   public function index(Request $request)
{

    $query = Order::with([
        'customer',
        'payments'
    ]);



    // ================= SEARCH =================

    if($request->search){


        $search = $request->search;


        $query->where(function($q) use ($search){


            $q->where(
                'kode_pesanan',
                'like',
                "%$search%"
            )


            ->orWhereHas(
                'customer',
                function($customer) use ($search){

                    $customer->where(
                        'nama',
                        'like',
                        "%$search%"
                    );

                }
            );


        });


    }





    // ================= FILTER STATUS =================

    if($request->status){


        $query->where(
            'status_pesanan',
            $request->status
        );


    }





    $orders = $query

    ->latest()

    ->get();



    // ================= FILTER DARI KARTU DASHBOARD =================
    // ?filter=belum_lunas  : hanya pesanan yang masih punya sisa pembayaran
    // ?filter=siap_kirim   : hanya pesanan berstatus Packing yang sudah lunas
    // Aturannya sama persis dengan penghitungan di dashboard.

    $filter = $request->filter;

    if($filter == 'belum_lunas'){

        $orders = $orders->filter(function($order){
            return $order->statusPembayaran() == 'Belum Lunas';
        });

    }

    if($filter == 'siap_kirim'){

        $orders = $orders->filter(function($order){
            return $order->siapDikirim();
        });

    }






    return view(
        'admin.orders.index',
        compact('orders', 'filter')
    );


}





    /**
     * Form tambah pesanan
     */
    public function create()
    {

        // Pelanggan yang dipilih: dari isian terakhir (old) atau dari alamat halaman (?customer_id=5)
        $customerId = old('customer_id', request('customer_id'));

        // Kalau ada id-nya, ambil satu pelanggan itu saja (bukan semua pelanggan)
        $customerTerpilih = null;

        if ($customerId) {
            $customerTerpilih = Customer::find($customerId);
        }

        // Kata yang diketik di kolom cari pelanggan
        $cariPelanggan = old('cari_pelanggan');

        // Hasil pencarian: kosong dulu, diisi kalau ada kata yang dicari. Paling banyak 20 data.
        $hasilCari = collect();

        if ($cariPelanggan) {

            $hasilCari = Customer::where('nama', 'like', '%' . $cariPelanggan . '%')
                ->orWhere('no_hp', 'like', '%' . $cariPelanggan . '%')
                ->orderBy('nama')
                ->limit(20)
                ->get();

        }

        $products = Product::all();

        // Jumlah baris produk di form.
        // old('jumlah_baris', 1) artinya: pakai isian terakhir kalau ada, kalau tidak ada mulai dari 1 baris.
        $jumlahBaris = old('jumlah_baris', 1);


        return view(
            'admin.orders.create',
            compact(
                'customerTerpilih',
                'cariPelanggan',
                'hasilCari',
                'products',
                'jumlahBaris'
            )
        );

    }







    /**
     * Tombol Cari pelanggan: halaman dimuat ulang, isian form tetap ada.
     */
    public function cariPelanggan(Request $request)
    {

        // Pelanggan lama dilepas supaya hasil pencarian yang tampil
        return redirect()
            ->route('admin.orders.create')
            ->withInput($request->except('customer_id'));

    }


    /**
     * Tombol Pilih Pelanggan Ini: pelanggan dari hasil cari dimasukkan ke form.
     */
    public function pilihPelanggan(Request $request)
    {

        // Kata pencarian dilepas, customer_id tetap dibawa
        return redirect()
            ->route('admin.orders.create')
            ->withInput($request->except('cari_pelanggan'));

    }


    /**
     * Tombol Ganti pelanggan: pelanggan dilepas, kolom cari muncul lagi.
     */
    public function gantiPelanggan(Request $request)
    {

        return redirect()
            ->route('admin.orders.create')
            ->withInput($request->except('customer_id', 'cari_pelanggan'));

    }


    /**
     * Menambah baris produk di form tambah pesanan.
     * Halaman dimuat ulang, tapi semua isian form tetap ada (withInput).
     */
    public function tambahBaris(Request $request)
    {

        // Jumlah baris sekarang + jumlah baris yang ingin ditambah
        $jumlahBaris = (int) $request->jumlah_baris + (int) $request->tambah_baris;

        // Paling sedikit 1 baris dan paling banyak 20 baris
        if ($jumlahBaris < 1) {
            $jumlahBaris = 1;
        }

        if ($jumlahBaris > 20) {
            $jumlahBaris = 20;
        }

        // Simpan jumlah baris yang baru ke dalam isian form
        $request->merge(['jumlah_baris' => $jumlahBaris]);

        return redirect()
            ->route('admin.orders.create')
            ->withInput();

    }







    /**
     * Simpan pesanan baru (satu pesanan bisa berisi banyak produk)
     */
    public function store(Request $request)
    {

        // 1. Cek isian form.
        //    product_id dan jumlah berbentuk DAFTAR (array) karena di form namanya product_id[] dan jumlah[]
        $request->validate([
            'customer_id'     => 'required',
            'tanggal_pesan'   => 'required|date',
            'product_id'      => 'required|array',
            'product_id.*'    => 'nullable|exists:products,id',
            'jumlah.*'        => 'nullable|integer|min:1',
        ]);


        // 2. Hitung isi pesanan: daftar produk, diskon, dan total
        $hasil = $this->hitungPesanan($request);


        // 3. Kalau tidak ada satu pun produk yang dipilih, kembali ke form
        if (count($hasil['item']) == 0) {

            return back()
                ->withInput()
                ->withErrors(['product_id' => 'Pilih minimal 1 produk.']);

        }


        // 4. Simpan semuanya sekaligus.
        //    DB::transaction artinya: kalau ada satu langkah yang gagal, semua langkah dibatalkan,
        //    jadi tidak ada pesanan yang tersimpan setengah-setengah.
        $order = DB::transaction(function () use ($request, $hasil) {

            // Data pesanan
            $order = Order::create([
                'kode_pesanan'   => 'NS-' . date('Ymd') . '-' . rand(1000, 9999),
                'customer_id'    => $request->customer_id,
                'tanggal_pesan'  => $request->tanggal_pesan,
                'total_harga'    => $hasil['total'],
                'diskon'         => $hasil['diskon'],
                'status_pesanan' => 'Menunggu Produksi',
            ]);

            // Satu baris rincian untuk setiap produk
            foreach ($hasil['item'] as $baris) {
                $order->orderDetails()->create($baris);
            }

            // Riwayat awal pesanan
            $order->statusHistories()->create([
                'status'     => 'Menunggu Produksi',
                'keterangan' => 'Pesanan dibuat dan masuk proses produksi',
                'user_id'    => auth()->id(),
            ]);

            return $order;

        });


        return redirect()
            ->route('admin.orders.show', $order->id)
            ->with('success', 'Pesanan berhasil dibuat');

    }




    /**
     * Detail pesanan
     */
    public function show(Order $order)
    {

        $order->load([

            'customer',

            'orderDetails.product',

            'payments',

            'shipment',

            'statusHistories'

        ]);



        return view(
            'admin.orders.show',
            compact('order')
        );

    }









    /**
     * Form edit pesanan
     */
    public function edit(Order $order)
    {


        if($order->status_pesanan != 'Menunggu Produksi'){


            return redirect()

            ->route(
                'admin.orders.show',
                $order->id
            )

            ->with(
                'error',
                'Pesanan tidak dapat diedit'
            );

        }




        // Pelanggan yang tampil di form: isian terakhir (old) atau pelanggan pesanan ini.
        // Kalau isian terakhir kosong (habis klik "Ganti pelanggan"), kolom cari yang tampil.
        $customerId = old('customer_id', $order->customer_id);

        $customerTerpilih = null;

        if ($customerId) {
            $customerTerpilih = Customer::find($customerId);
        }

        // Hasil pencarian pelanggan (paling banyak 20 data)
        $cariPelanggan = old('cari_pelanggan');

        $hasilCari = collect();

        if ($cariPelanggan) {

            $hasilCari = Customer::where('nama', 'like', '%' . $cariPelanggan . '%')
                ->orWhere('no_hp', 'like', '%' . $cariPelanggan . '%')
                ->orderBy('nama')
                ->limit(20)
                ->get();

        }

        $products = Product::all();

        // Jumlah baris produk: isian terakhir, atau sebanyak produk di pesanan ini (paling sedikit 1)
        $jumlahBaris = old('jumlah_baris', max(1, count($order->orderDetails)));


        return view(
            'admin.orders.edit',
            compact(
                'order',
                'customerTerpilih',
                'cariPelanggan',
                'hasilCari',
                'products',
                'jumlahBaris'
            )
        );

    }









    /**
     * Tombol-tombol di form ubah pesanan yang memuat ulang halaman
     * (tambah produk, cari / pilih / ganti pelanggan). Isian form tetap ada.
     *
     * Tombolnya mengirim name="aksi" dengan isi berbeda: tambah_baris, cari, pilih, atau ganti.
     */
    public function segarkanFormEdit(Request $request, Order $order)
    {

        $aksi = $request->aksi;


        if ($aksi == 'tambah_baris') {

            // Jumlah baris sekarang + jumlah baris yang ingin ditambah (1 sampai 20)
            $jumlahBaris = (int) $request->jumlah_baris + (int) $request->tambah_baris;

            $jumlahBaris = max(1, min(20, $jumlahBaris));

            $request->merge(['jumlah_baris' => $jumlahBaris]);

        }


        if ($aksi == 'cari' || $aksi == 'ganti') {

            // Pelanggan dilepas. Diisi kosong (bukan dihapus) supaya tidak kembali ke pelanggan lama.
            $request->merge(['customer_id' => '']);

        }


        if ($aksi == 'pilih' || $aksi == 'ganti') {

            // Kata pencarian tidak diperlukan lagi
            $request->merge(['cari_pelanggan' => '']);

        }


        return redirect()
            ->route('admin.orders.edit', $order->id)
            ->withInput();

    }







    /**
     * Update pesanan (isi pesanan diganti dengan isian form yang baru)
     */
    public function update(Request $request, Order $order)
    {

        // Pesanan hanya boleh diubah selama masih Menunggu Produksi
        if ($order->status_pesanan != 'Menunggu Produksi') {

            return redirect()->route('admin.orders.show', $order->id);

        }


        // 1. Cek isian form (sama seperti saat menambah pesanan)
        $request->validate([
            'customer_id'     => 'required',
            'tanggal_pesan'   => 'required|date',
            'product_id'      => 'required|array',
            'product_id.*'    => 'nullable|exists:products,id',
            'jumlah.*'        => 'nullable|integer|min:1',
        ]);


        // 2. Hitung isi pesanan yang baru
        $hasil = $this->hitungPesanan($request);


        // 3. Minimal harus ada 1 produk
        if (count($hasil['item']) == 0) {

            return back()
                ->withInput()
                ->withErrors(['product_id' => 'Pilih minimal 1 produk.']);

        }


        // 3b. Catat apa saja yang berubah (data lama dibandingkan dengan isian baru).
        //     Ini harus dilakukan SEBELUM data pesanan diubah.
        $perubahan = $this->catatPerubahan($order, $request, $hasil);

        // Kalau tidak ada yang berubah, tidak perlu menyimpan apa-apa
        if (count($perubahan) == 0) {

            return redirect()
                ->route('admin.orders.show', $order->id)
                ->with('success', 'Tidak ada data yang diubah');

        }


        // 4. Simpan perubahan (semua berhasil atau semua dibatalkan)
        DB::transaction(function () use ($request, $order, $hasil, $perubahan) {

            // Ubah data pesanan. Diskon ikut dihitung ulang.
            $order->update([
                'customer_id'   => $request->customer_id,
                'tanggal_pesan' => $request->tanggal_pesan,
                'total_harga'   => $hasil['total'],
                'diskon'        => $hasil['diskon'],
            ]);

            // Hapus rincian lama, lalu buat ulang sesuai isian form
            $order->orderDetails()->delete();

            foreach ($hasil['item'] as $baris) {
                $order->orderDetails()->create($baris);
            }


            // Simpan riwayat edit. Setiap perubahan ditulis di baris baru.
            $order->statusHistories()->create([
                'status'     => 'Pesanan Diedit',
                'keterangan' => implode("\n", $perubahan),
                'user_id'    => auth()->id(),
            ]);

        });


        return redirect()
            ->route('admin.orders.show', $order->id)
            ->with('success', 'Pesanan berhasil diperbarui');

    }




    /**
     * Update status pesanan sesuai SOP
     */
    public function updateStatus(
        Request $request,
        Order $order
    )
    {


        $request->validate([

            'status_pesanan'=>'required'

        ]);




        // Status selesai hanya melalui tombol selesai

        if($request->status_pesanan == 'Selesai'){


            return back()

            ->with(
                'error',
                'Status Selesai hanya dapat dilakukan melalui konfirmasi penerimaan pesanan'
            );

        }




        $alurStatus = [

            'Menunggu Produksi',

            'Ready',

            'Packing',

            'Dikirim'

        ];





        $statusLama = $order->status_pesanan;

        $statusBaru = $request->status_pesanan;





        $posisiLama = array_search(
            $statusLama,
            $alurStatus
        );


        $posisiBaru = array_search(
            $statusBaru,
            $alurStatus
        );





        if($statusLama == $statusBaru){


            return back()

            ->with(
                'error',
                'Status pesanan tidak mengalami perubahan'
            );

        }





        if($posisiBaru < $posisiLama){


            return back()

            ->with(
                'error',
                'Status pesanan tidak dapat kembali'
            );

        }






        if($posisiBaru > $posisiLama + 1){


            return back()

            ->with(
                'error',
                'Perubahan status harus mengikuti SOP'
            );

        }





        // ===== ATURAN TAMBAHAN (dicek di server, jadi tidak bisa dilewati) =====

        // Aturan 1: pesanan baru boleh masuk Packing kalau pembayarannya sudah lunas
        if ($statusBaru == 'Packing' && $order->statusPembayaran() != 'Lunas') {

            return back()->with(
                'error',
                'Pesanan belum lunas. Selesaikan pembayaran dulu sebelum status diubah ke Packing'
            );

        }


        // Aturan 2: pesanan baru boleh berstatus Dikirim kalau data pengiriman dan nomor resi sudah diisi
        if ($statusBaru == 'Dikirim') {

            if (!$order->shipment || !$order->shipment->nomor_resi) {

                return back()->with(
                    'error',
                    'Isi data pengiriman dan nomor resi dulu sebelum status diubah ke Dikirim'
                );

            }

        }


        $order->update([

            'status_pesanan'=>$statusBaru

        ]);






        $order->statusHistories()->create([

            'status'=>$statusBaru,

            'keterangan'=>'Status diubah dari ' . $statusLama . ' menjadi ' . $statusBaru,

            'user_id'=>auth()->id()

        ]);






        return redirect()

        ->route(
            'admin.orders.show',
            $order->id
        )

        ->with(
            'success',
            'Status pesanan berhasil diperbarui'
        );


    }









    /**
     * Selesaikan pesanan
     */
    public function selesai(Order $order)
    {


        if($order->status_pesanan != 'Dikirim'){


            return redirect()

            ->route(
                'admin.orders.show',
                $order->id
            )

            ->with(
                'error',
                'Pesanan hanya dapat diselesaikan setelah status Dikirim'
            );

        }





        $order->update([

            'status_pesanan'=>'Selesai'

        ]);





        $order->statusHistories()->create([

            'status'=>'Selesai',

            'keterangan'=>'Pesanan telah diterima pelanggan',

            'user_id'=>auth()->id()

        ]);






        return redirect()

        ->route(
            'admin.orders.show',
            $order->id
        )

        ->with(
            'success',
            'Pesanan berhasil diselesaikan'
        );


    }


    /**
     * Menghitung isi pesanan dari form (dipakai oleh store dan update).
     *
     * Hasilnya berupa:
     *   'item'   = daftar produk yang dipesan (product_id, jumlah, harga)
     *   'diskon' = potongan harga
     *   'total'  = total yang harus dibayar
     */
    private function hitungPesanan(Request $request)
    {

        // Contoh isi: $daftarProduk = [3, 5, null, null, null]  dan  $daftarJumlah = [2, 1, 1, 1, 1]
        $daftarProduk = $request->product_id;
        $daftarJumlah = $request->jumlah;


        // Kumpulkan jumlah per produk.
        // Kalau produk yang sama dipilih dua kali, jumlahnya digabung.
        $jumlahPerProduk = [];

        foreach ($daftarProduk as $urutan => $productId) {

            // Baris yang produknya tidak dipilih dilewati
            if ($productId == null) {
                continue;
            }

            // Kalau jumlah dikosongkan, dianggap 1
            $jumlah = $daftarJumlah[$urutan] ?? 1;

            if ($jumlah == null) {
                $jumlah = 1;
            }

            if (isset($jumlahPerProduk[$productId])) {
                $jumlahPerProduk[$productId] = $jumlahPerProduk[$productId] + $jumlah;
            } else {
                $jumlahPerProduk[$productId] = $jumlah;
            }

        }


        // Ambil harga setiap produk dan hitung subtotal
        $item = [];
        $subtotal = 0;
        $jumlahPotong = 0;

        foreach ($jumlahPerProduk as $productId => $jumlah) {

            $product = Product::findOrFail($productId);

            $item[] = [
                'product_id' => $product->id,
                'jumlah'     => $jumlah,
                'harga'      => $product->harga,
            ];

            $subtotal = $subtotal + ($product->harga * $jumlah);
            $jumlahPotong = $jumlahPotong + $jumlah;

        }


        // Diskon Rp 50.000 kalau total semua produk 4 potong atau lebih
        $diskon = 0;

        if ($jumlahPotong >= 4) {
            $diskon = 50000;
        }

        $total = $subtotal - $diskon;

        // Total tidak boleh minus
        if ($total < 0) {
            $total = 0;
        }


        return [
            'item'   => $item,
            'diskon' => $diskon,
            'total'  => $total,
        ];

    }


    /**
     * Membandingkan pesanan lama dengan isian form yang baru.
     *
     * Hasilnya berupa daftar kalimat, contohnya:
     *   'Pelanggan: Tasya menjadi Aulia'
     *   'Jumlah Sienna (pink, M): 2 pcs menjadi 3 pcs'
     * Kalau tidak ada yang berubah, daftarnya kosong.
     */
    private function catatPerubahan(Order $order, Request $request, array $hasil)
    {

        $perubahan = [];


        // 1. Pelanggan berubah?
        if ($order->customer_id != $request->customer_id) {

            $pelangganBaru = Customer::find($request->customer_id);

            $perubahan[] = 'Pelanggan: ' . $order->customer->nama . ' menjadi ' . $pelangganBaru->nama;

        }


        // 2. Tanggal pesan berubah? (diambil 10 huruf pertama: tahun-bulan-tanggal)
        $tanggalLama = substr((string) $order->tanggal_pesan, 0, 10);

        if ($tanggalLama != $request->tanggal_pesan) {

            $perubahan[] = 'Tanggal pesan: ' . $tanggalLama . ' menjadi ' . $request->tanggal_pesan;

        }


        // 3. Produk berubah?
        //    Susun dulu daftar lama dan baru dalam bentuk: product_id => jumlah
        $produkLama = [];

        foreach ($order->orderDetails as $detail) {
            $produkLama[$detail->product_id] = $detail->jumlah;
        }

        $produkBaru = [];

        foreach ($hasil['item'] as $baris) {
            $produkBaru[$baris['product_id']] = $baris['jumlah'];
        }

        // Gabungkan semua product_id dari daftar lama dan baru, tanpa duplikat
        $semuaId = array_unique(array_merge(array_keys($produkLama), array_keys($produkBaru)));

        foreach ($semuaId as $id) {

            $jumlahLama = $produkLama[$id] ?? 0;
            $jumlahBaru = $produkBaru[$id] ?? 0;

            // Jumlahnya sama berarti tidak ada perubahan untuk produk ini
            if ($jumlahLama == $jumlahBaru) {
                continue;
            }

            $product = Product::find($id);

            $nama = 'Produk #' . $id;

            if ($product) {
                $nama = $product->nama_produk . ' (' . $product->warna . ', ' . $product->ukuran . ')';
            }

            if ($jumlahLama == 0) {

                $perubahan[] = 'Produk ditambahkan: ' . $nama . ' sebanyak ' . $jumlahBaru . ' pcs';

            } elseif ($jumlahBaru == 0) {

                $perubahan[] = 'Produk dihapus: ' . $nama . ' sebanyak ' . $jumlahLama . ' pcs';

            } else {

                $perubahan[] = 'Jumlah ' . $nama . ': ' . $jumlahLama . ' pcs menjadi ' . $jumlahBaru . ' pcs';

            }

        }


        // 4. Diskon dan total berubah?
        if ($order->diskon != $hasil['diskon']) {

            $perubahan[] = 'Diskon: Rp ' . number_format($order->diskon, 0, ',', '.')
                         . ' menjadi Rp ' . number_format($hasil['diskon'], 0, ',', '.');

        }

        if ($order->total_harga != $hasil['total']) {

            $perubahan[] = 'Total: Rp ' . number_format($order->total_harga, 0, ',', '.')
                         . ' menjadi Rp ' . number_format($hasil['total'], 0, ',', '.');

        }


        return $perubahan;

    }

    public function invoice(Order $order)
    {
        $order->load([
            'customer',
            'orderDetails.product.color',
            'payments',
        ]);

        $pdf = Pdf::loadView(
            'admin.orders.invoice',
            compact('order')
        );

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream(
            'Invoice-' . $order->kode_pesanan . '.pdf'
        );
    }

}