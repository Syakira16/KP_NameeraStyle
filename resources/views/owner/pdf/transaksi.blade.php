{{--
    LAPORAN TRANSAKSI (PDF)

    Halaman ini diubah menjadi PDF, jadi aturannya berbeda dengan halaman web biasa:
      - Tidak memakai Tailwind. Semua gaya ditulis di <style> di bawah.
      - Tidak memakai flex / grid. Susunan berdampingan dibuat dengan <table>.
      - Kertas dibuat mendatar (landscape) supaya 9 kolom muat dan terbaca.

    Data dari controller (nama jangan diubah):
      $orders          -> daftar pesanan
      $totalTransaksi  -> total nilai semua pesanan
--}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi - Nameera Style</title>

    <style>
        /* Kertas A4 mendatar dengan tepi 28px */
        @page {
            size: A4 landscape;
            margin: 28px;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: #4A3228;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }


        /* ===== KEPALA LAPORAN ===== */
        .kepala {
            background: #6B4A3C;
            color: #FFFFFF;
        }

        .kepala td {
            padding: 14px 18px;
            vertical-align: middle;
        }

        .kepala .merek {
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .kepala .judul {
            font-size: 12px;
            font-weight: bold;
            text-align: right;
        }

        .kepala .periode {
            font-size: 9px;
            text-align: right;
            color: #F3DDE2;
        }

        .garis-pink {
            height: 4px;
            background: #D4708A;
        }


        /* ===== TIGA KOTAK RINGKASAN ===== */
        /* border-spacing membuat jarak antar kotak */
        .ringkasan {
            border-collapse: separate;
            border-spacing: 8px 0;
            margin: 10px 0;
            width: 100%;
        }

        .ringkasan td {
            width: 33%;
            padding: 11px 14px;
            border: 1px solid #EBDDD5;
            background: #FDF8F5;
        }

        .ringkasan .label {
            font-size: 9px;
            color: #7A6258;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .ringkasan .nilai {
            margin-top: 4px;
            font-size: 14px;
            font-weight: bold;
        }

        .hijau { color: #15803D; }
        .merah { color: #B91C1C; }


        /* ===== TABEL DETAIL ===== */
        .detail {
            margin-top: 6px;
        }

        /* Judul kolom diulang di setiap halaman kalau tabel lebih dari satu halaman */
        .detail thead {
            display: table-header-group;
        }

        .detail th {
            background: #F5EBE5;
            color: #6B4A3C;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-align: center;          /* judul kolom di tengah */
            padding: 8px 6px;
            border-top: 1px solid #D8C8BF;
            border-bottom: 2px solid #D4708A;
        }

        .detail td {
            padding: 7px 6px;
            border-bottom: 1px solid #EFE3DC;
            text-align: left;            /* isi tabel rata kiri */
            vertical-align: middle;
        }

        /* Baris genap diberi warna tipis supaya mata tidak "loncat" antar baris */
        .detail tr.genap td {
            background: #FDF8F5;
        }

        /* Kolom nomor sempit dan di tengah */
        .detail td.no {
            width: 24px;
            text-align: center;
            color: #7A6258;
        }

        /* Kolom uang rata kanan supaya angka sejajar */
        .detail td.uang {
            text-align: right;
            white-space: nowrap;
        }

        .detail .kode {
            font-weight: bold;
        }

        /* Label status: teks berwarna, tanpa kotak, agar aman di PDF */
        .status-lunas       { color: #15803D; font-weight: bold; }
        .status-belum       { color: #B91C1C; font-weight: bold; }
        .status-pesanan     { font-weight: bold; color: #6B4A3C; }

        .kosong {
            text-align: center;
            padding: 24px;
            color: #7A6258;
        }


        /* ===== BARIS TOTAL DI BAWAH TABEL ===== */
        .detail tr.total td {
            background: #F5EBE5;
            font-weight: bold;
            border-top: 2px solid #D8C8BF;
        }


        /* ===== KAKI LAPORAN ===== */
        .kaki {
            margin-top: 14px;
            font-size: 9px;
            color: #7A6258;
        }

        .kaki td {
            padding-top: 6px;
            border-top: 1px solid #EBDDD5;
        }

        .kaki .kanan {
            text-align: right;
        }
    </style>
</head>

<body>


    {{-- ============ KEPALA ============ --}}
    <table class="kepala">
        <tr>
            <td class="merek">NAMEERA STYLE</td>
            <td>
                <div class="judul">LAPORAN TRANSAKSI</div>

                {{-- Periode: dari filter ?mulai=...&sampai=... kalau ada --}}
                <div class="periode">
                    @if(request('mulai') || request('sampai'))
                        Periode:
                        {{ request('mulai') ? \Carbon\Carbon::parse(request('mulai'))->format('d-m-Y') : 'Awal' }}
                        s/d
                        {{ request('sampai') ? \Carbon\Carbon::parse(request('sampai'))->format('d-m-Y') : 'Akhir' }}
                    @else
                        Periode: Semua Data
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <div class="garis-pink"></div>


    {{-- ============ RINGKASAN (3 kotak) ============ --}}
    <table class="ringkasan">
        <tr>
            <td>
                <div class="label">Total Nilai Pesanan</div>
                <div class="nilai">Rp {{ number_format($totalTransaksi, 0, ',', '.') }}</div>
            </td>

            <td>
                <div class="label">Pembayaran Diterima</div>
                <div class="nilai hijau">
                    Rp {{ number_format($orders->sum(function($order) {
                        return $order->totalDibayar();
                    }), 0, ',', '.') }}
                </div>
            </td>

            <td>
                <div class="label">Sisa Tagihan</div>
                <div class="nilai merah">
                    Rp {{ number_format($orders->sum(function($order) {
                        return $order->sisaPembayaran();
                    }), 0, ',', '.') }}
                </div>
            </td>
        </tr>
    </table>


    {{-- ============ TABEL DETAIL ============ --}}
    <table class="detail">

        <thead>
            <tr>
                <th>No</th>
                <th>Kode Pesanan</th>
                <th>Pelanggan</th>
                <th>Tanggal</th>
                <th>Total Pesanan</th>
                <th>Dibayar</th>
                <th>Sisa</th>
                <th>Pembayaran</th>
                <th>Status Pesanan</th>
            </tr>
        </thead>

        <tbody>

            @forelse($orders as $index => $order)

                {{-- Baris genap (nomor 2, 4, 6, ...) diberi class "genap" untuk warna tipis --}}
                <tr class="{{ $index % 2 == 1 ? 'genap' : '' }}">

                    <td class="no">{{ $index + 1 }}</td>

                    <td class="kode">{{ $order->kode_pesanan }}</td>

                    <td>{{ $order->customer->nama ?? '-' }}</td>

                    <td>
                        {{ $order->tanggal_pesan
                            ? \Carbon\Carbon::parse($order->tanggal_pesan)->format('d-m-Y')
                            : '-' }}
                    </td>

                    <td class="uang">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>

                    <td class="uang hijau">Rp {{ number_format($order->totalDibayar(), 0, ',', '.') }}</td>

                    <td class="uang merah">Rp {{ number_format($order->sisaPembayaran(), 0, ',', '.') }}</td>

                    {{-- Hijau kalau Lunas, merah kalau Belum Lunas --}}
                    <td>
                        @if($order->statusPembayaran() == 'Lunas')
                            <span class="status-lunas">Lunas</span>
                        @else
                            <span class="status-belum">Belum Lunas</span>
                        @endif
                    </td>

                    <td class="status-pesanan">{{ $order->status_pesanan }}</td>

                </tr>

            @empty

                <tr>
                    <td colspan="9" class="kosong">
                        Belum ada transaksi pada periode ini.
                    </td>
                </tr>

            @endforelse

            {{-- Baris jumlah di paling bawah (hanya kalau ada data) --}}
            @if(count($orders) > 0)

                <tr class="total">
                    <td colspan="4" style="text-align: right;">JUMLAH ({{ count($orders) }} pesanan)</td>
                    <td class="uang">Rp {{ number_format($totalTransaksi, 0, ',', '.') }}</td>
                    <td class="uang">
                        Rp {{ number_format($orders->sum(function($order) { return $order->totalDibayar(); }), 0, ',', '.') }}
                    </td>
                    <td class="uang">
                        Rp {{ number_format($orders->sum(function($order) { return $order->sisaPembayaran(); }), 0, ',', '.') }}
                    </td>
                    <td colspan="2"></td>
                </tr>

            @endif

        </tbody>

    </table>


    {{-- ============ KAKI ============ --}}
    <table class="kaki">
        <tr>
            <td>Nameera Style - Pre-Order Busana Muslimah</td>
            <td class="kanan">Dicetak pada {{ now()->format('d-m-Y H:i') }}</td>
        </tr>
    </table>


</body>
</html>