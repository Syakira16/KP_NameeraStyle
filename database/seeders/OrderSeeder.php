<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * 48 pesanan dummy lengkap dengan detail, pembayaran, pengiriman,
     * dan riwayat status. Aturan bisnis yang diikuti:
     *  - diskon Rp50.000 jika total jumlah barang >= 4 (sama seperti OrderController)
     *  - pesanan Dikirim/Selesai pasti sudah Lunas dan punya data pengiriman
     *  - pesanan Packing: sebagian Lunas (siap dikirim), sebagian Belum Lunas
     *  - kode pesanan berformat NS-YYYYMMDD-XXXX
     *
     * Jalankan dengan: php artisan migrate:fresh --seed
     */
    private const JUMLAH_PESANAN = 48;

    public function run(): void
    {
        // Angka acak tetap supaya hasil seeder selalu sama
        mt_srand(2026);

        $csId        = DB::table('users')->where('email', 'cs@nameerastyle.test')->value('id');
        $customerIds = DB::table('customers')->orderBy('id')->pluck('id')->all();
        $products    = DB::table('products')->orderBy('id')->get(['id', 'harga'])->all();

        $jumlahPelanggan = count($customerIds);
        $jumlahProduk    = count($products);

        for ($i = 0; $i < self::JUMLAH_PESANAN; $i++) {
            $tanggalPesan = Carbon::create(2026, 4, 6)->addDays($i * 4);
            $status       = $this->statusUntuk($i);

            // ---------- 1) Barang yang dipesan ----------
            $banyakBaris = mt_rand(1, 3);
            $dipilih = [];
            while (count($dipilih) < $banyakBaris) {
                $p = mt_rand(0, $jumlahProduk - 1);
                if (! in_array($p, $dipilih, true)) {
                    $dipilih[] = $p;
                }
            }

            $baris = [];
            $subtotal = 0;
            $totalJumlah = 0;
            foreach ($dipilih as $urutan => $p) {
                $jumlah = mt_rand(1, 3);
                // Setiap pesanan ke-6 dibuat 4 pcs supaya ada contoh diskon
                if ($i % 6 === 0 && $urutan === 0) {
                    $jumlah = 4;
                }
                $harga = (int) $products[$p]->harga;
                $baris[] = ['product_id' => $products[$p]->id, 'jumlah' => $jumlah, 'harga' => $harga];
                $subtotal += $jumlah * $harga;
                $totalJumlah += $jumlah;
            }

            $diskon = $totalJumlah >= 4 ? 50000 : 0;
            $total  = $subtotal - $diskon;

            // ---------- 2) Rencana pembayaran ----------
            $pembayaran = $this->rencanaBayar($status, $i, $total, $tanggalPesan);

            // ---------- 3) Rencana pengiriman ----------
            $pengiriman = null;
            if (in_array($status, ['Dikirim', 'Selesai'], true)) {
                $ekspedisiList = ['J&T Express', 'J&T Express', 'J&T Express', 'JNE', 'SiCepat'];
                $pengiriman = [
                    'ekspedisi'     => $ekspedisiList[$i % count($ekspedisiList)],
                    'nomor_resi'    => 'JP' . str_pad((string) (3100000000 + $i * 48271), 10, '0', STR_PAD_LEFT),
                    'tanggal_kirim' => $tanggalPesan->copy()->addDays(10 + ($i % 8)),
                ];
            }

            // ---------- 4) Simpan pesanan ----------
            $kode = 'NS-' . $tanggalPesan->format('Ymd') . '-' . str_pad((string) (1000 + ($i * 37) % 9000), 4, '0', STR_PAD_LEFT);
            $waktuBuat = $tanggalPesan->copy()->setTime(10, 0);

            $orderId = DB::table('orders')->insertGetId([
                'kode_pesanan'   => $kode,
                'customer_id'    => $customerIds[$i % $jumlahPelanggan],
                'tanggal_pesan'  => $tanggalPesan->toDateString(),
                'total_harga'    => $total,
                'diskon'         => $diskon,
                'status_pesanan' => $status,
                'created_at'     => $waktuBuat,
                'updated_at'     => $waktuBuat,
            ]);

            foreach ($baris as $b) {
                DB::table('order_details')->insert($b + [
                    'order_id'   => $orderId,
                    'created_at' => $waktuBuat,
                    'updated_at' => $waktuBuat,
                ]);
            }

            foreach ($pembayaran as $k => $bayar) {
                $metode = ['Transfer Bank', 'E-Wallet', 'Tunai'][($i + $k) % 3];
                DB::table('payments')->insert([
                    'order_id'      => $orderId,
                    'tanggal_bayar' => $bayar['tanggal']->toDateString(),
                    'jumlah_bayar'  => $bayar['jumlah'],
                    'metode_bayar'  => $metode,
                    'bukti_bayar'   => null,
                    'created_at'    => $bayar['tanggal']->copy()->setTime(14, 0),
                    'updated_at'    => $bayar['tanggal']->copy()->setTime(14, 0),
                ]);
            }

            if ($pengiriman) {
                DB::table('shipments')->insert([
                    'order_id'      => $orderId,
                    'ekspedisi'     => $pengiriman['ekspedisi'],
                    'nomor_resi'    => $pengiriman['nomor_resi'],
                    'tanggal_kirim' => $pengiriman['tanggal_kirim']->toDateString(),
                    'created_at'    => $pengiriman['tanggal_kirim']->copy()->setTime(16, 0),
                    'updated_at'    => $pengiriman['tanggal_kirim']->copy()->setTime(16, 0),
                ]);
            }

            // ---------- 5) Riwayat status ----------
            $riwayat = $this->riwayatStatus($status, $tanggalPesan, $pengiriman);
            foreach ($riwayat as $r) {
                DB::table('order_status_histories')->insert([
                    'order_id'   => $orderId,
                    'status'     => $r['status'],
                    'keterangan' => $r['keterangan'],
                    'user_id'    => $csId,
                    'created_at' => $r['waktu'],
                    'updated_at' => $r['waktu'],
                ]);
            }

            // updated_at pesanan = waktu riwayat terakhir
            DB::table('orders')->where('id', $orderId)->update([
                'updated_at' => end($riwayat)['waktu'],
            ]);
        }
    }

    /**
     * Pesanan lama -> Selesai, pesanan baru -> masih di awal proses.
     */
    private function statusUntuk(int $i): string
    {
        if ($i < 24) return 'Selesai';             // 24 pesanan
        if ($i < 32) return 'Dikirim';             //  8 pesanan
        if ($i < 38) return 'Packing';             //  6 pesanan
        if ($i < 44) return 'Ready';               //  6 pesanan
        return 'Menunggu Produksi';                //  4 pesanan
    }

    /**
     * Mengembalikan daftar pembayaran: [['tanggal' => Carbon, 'jumlah' => int], ...]
     * Jumlah semua pembayaran = total (Lunas) atau kurang dari total (Belum Lunas).
     */
    private function rencanaBayar(string $status, int $i, int $total, Carbon $tanggal): array
    {
        $lunas = false;
        $bagian = [1.0];

        switch ($status) {
            case 'Selesai':
            case 'Dikirim':
                $lunas = true;
                $pola = [[1.0], [0.5, 0.5], [0.4, 0.3, 0.3]];
                $bagian = $pola[$i % 3];
                break;

            case 'Packing':
                if ($i < 35) {            // 3 pesanan Lunas -> siap dikirim
                    $lunas = true;
                    $bagian = [0.5, 0.5];
                } else {                  // 3 pesanan masih Belum Lunas
                    $bagian = [0.6];
                }
                break;

            case 'Ready':
                if ($i % 2 === 0) {
                    $lunas = true;
                    $bagian = [0.5, 0.5];
                } else {
                    $bagian = [0.5];
                }
                break;

            default: // Menunggu Produksi
                if ($i >= 46) {
                    return [];            // belum ada pembayaran sama sekali
                }
                $bagian = [0.4];          // baru bayar DP
        }

        $hasil = [];
        $terbayar = 0;
        $jarakHari = [0, 5, 10];

        foreach ($bagian as $k => $persen) {
            $terakhir = ($k === count($bagian) - 1);

            if ($lunas && $terakhir) {
                $jumlah = $total - $terbayar;          // sisa tepat sampai Lunas
            } else {
                $jumlah = (int) (round($total * $persen / 1000) * 1000);
            }

            $terbayar += $jumlah;
            $hasil[] = [
                'tanggal' => $tanggal->copy()->addDays($jarakHari[$k]),
                'jumlah'  => $jumlah,
            ];
        }

        return $hasil;
    }

    /**
     * Riwayat status berurutan sampai status pesanan saat ini.
     */
    private function riwayatStatus(string $statusAkhir, Carbon $tanggalPesan, ?array $pengiriman): array
    {
        $urutan = ['Menunggu Produksi', 'Ready', 'Packing', 'Dikirim', 'Selesai'];
        $sampai = array_search($statusAkhir, $urutan, true);

        $kirim = $pengiriman['tanggal_kirim'] ?? null;

        $semua = [
            'Menunggu Produksi' => [
                $tanggalPesan->copy()->setTime(10, 5),
                'Pesanan dibuat dan masuk proses produksi',
            ],
            'Ready' => [
                $tanggalPesan->copy()->addDays(7)->setTime(11, 0),
                'Produksi selesai, barang siap',
            ],
            'Packing' => [
                $tanggalPesan->copy()->addDays(9)->setTime(13, 0),
                'Pesanan sedang dikemas',
            ],
            'Dikirim' => [
                $kirim ? $kirim->copy()->setTime(16, 0) : $tanggalPesan->copy()->addDays(11),
                $pengiriman
                    ? 'Pesanan dikirim lewat ' . $pengiriman['ekspedisi'] . ', resi ' . $pengiriman['nomor_resi']
                    : 'Pesanan dikirim',
            ],
            'Selesai' => [
                $kirim ? $kirim->copy()->addDays(4)->setTime(10, 0) : $tanggalPesan->copy()->addDays(15),
                'Pesanan diterima pelanggan',
            ],
        ];

        $hasil = [];
        foreach ($urutan as $n => $nama) {
            if ($n > $sampai) {
                break;
            }
            $hasil[] = [
                'status'     => $nama,
                'keterangan' => $semua[$nama][1],
                'waktu'      => $semua[$nama][0],
            ];
        }

        return $hasil;
    }
}