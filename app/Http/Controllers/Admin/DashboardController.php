<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        // ===============================
        // 1. AMBIL SEMUA PESANAN (sekali saja)
        // with(...) = ikut mengambil data pelanggan, pembayaran, dan produknya
        // supaya website tidak lambat.
        // latest() = pesanan terbaru ada di urutan paling atas.
        // ===============================
        $semuaPesanan = Order::with(['customer', 'payments', 'orderDetails.product'])
            ->latest()
            ->get();


        // ===============================
        // 2. SIAPKAN WADAH ANGKA (awalnya 0 / kosong)
        // ===============================
        $jumlahPesanan   = $semuaPesanan->count();   // jumlah semua pesanan
        $totalPesanan    = 0;                        // total nilai semua pesanan
        $totalPendapatan = 0;                        // total uang yang sudah masuk
        $totalPiutang    = 0;                        // total sisa pembayaran
        $belumLunas      = 0;                        // jumlah pesanan belum lunas
        $siapKirim       = 0;                        // jumlah pesanan siap dikirim
        $perluDitagih    = [];                       // daftar pesanan yang masih punya tagihan
        $produkTerlaris  = [];                       // nama produk => jumlah terjual

        // Jumlah pesanan di setiap tahap (nama tahap jangan diubah)
        $statusPesanan = [
            'Menunggu Produksi' => 0,
            'Ready'             => 0,
            'Packing'           => 0,
            'Dikirim'           => 0,
            'Selesai'           => 0,
        ];


        // ===============================
        // 3. HITUNG SEMUANYA DALAM SATU PERULANGAN
        // Setiap pesanan dicek satu per satu.
        // ===============================
        foreach ($semuaPesanan as $order) {

            // Tambah ke total nilai pesanan dan uang yang sudah masuk
            $totalPesanan    += $order->total_harga;
            $totalPendapatan += $order->totalDibayar();

            // Kalau masih ada sisa pembayaran, berarti belum lunas
            $sisa = $order->sisaPembayaran();

            if ($sisa > 0) {
                $totalPiutang += $sisa;
                $belumLunas++;
                $perluDitagih[] = $order;
            }

            // Pesanan yang sudah Packing dan lunas siap dikirim
            if ($order->siapDikirim()) {
                $siapKirim++;
            }

            // Tambah 1 pada tahap pesanan ini
            $statusPesanan[$order->status_pesanan]++;

            // Hitung jumlah potong yang terjual untuk setiap produk
            foreach ($order->orderDetails as $detail) {

                $namaProduk = $detail->product->nama_produk;

                // Kalau produk ini belum pernah dihitung, mulai dari 0
                if (!isset($produkTerlaris[$namaProduk])) {
                    $produkTerlaris[$namaProduk] = 0;
                }

                $produkTerlaris[$namaProduk] += $detail->jumlah;
            }
        }


        // ===============================
        // 4. URUTKAN DAN BATASI DAFTAR
        // ===============================

        // Produk terlaris: urutkan dari yang paling banyak, ambil 5 teratas
        arsort($produkTerlaris);
        $produkTerlaris = array_slice($produkTerlaris, 0, 5, true);

        // Perlu ditagih: ambil 5 pesanan terbaru yang masih punya tagihan
        $perluDitagih = array_slice($perluDitagih, 0, 5);

        // 5 pesanan terbaru (dari $semuaPesanan yang sudah urut terbaru)
        $pesananTerbaru = $semuaPesanan->take(5);

        // 5 pesanan yang masih menunggu produksi
        $menungguProduksi = $semuaPesanan
            ->where('status_pesanan', 'Menunggu Produksi')
            ->take(5);


        // ===============================
        // 5. PERSEN KEUANGAN (untuk batang di kartu Keuangan)
        // Dicek dulu supaya tidak dibagi 0.
        // ===============================
        $persenMasuk   = 0;
        $persenPiutang = 0;

        if ($totalPesanan > 0) {
            $persenMasuk   = round($totalPendapatan / $totalPesanan * 100);
            $persenPiutang = round($totalPiutang / $totalPesanan * 100);
        }


        // ===============================
        // 6. KIRIM SEMUA DATA KE HALAMAN DASHBOARD
        // ===============================
        return view('admin.dashboard', compact(
            'jumlahPesanan',
            'totalPesanan',
            'totalPendapatan',
            'totalPiutang',
            'belumLunas',
            'siapKirim',
            'perluDitagih',
            'produkTerlaris',
            'pesananTerbaru',
            'menungguProduksi',
            'statusPesanan',
            'persenMasuk',
            'persenPiutang'
        ));
    }
}