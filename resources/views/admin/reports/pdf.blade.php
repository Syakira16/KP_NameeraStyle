{{--
    HALAMAN PDF LAPORAN

    PENTING: halaman ini diubah menjadi PDF oleh library PDF (biasanya DomPDF), BUKAN oleh browser.
    Karena itu:
      - Tailwind TIDAK dipakai (tidak akan terbaca), semua gaya ditulis di tag <style> di bawah.
      - Tidak boleh memakai flex atau grid. Tata letak memakai <table> saja.
      - Font Poppins tidak tersedia, jadi dipakai Helvetica (bawaan PDF).

    Data yang dikirim dari controller (nama jangan diubah):
      $totalPesanan, $totalPembayaran, $totalPiutang -> angka ringkasan
      $orders                                        -> daftar pesanan di tabel

    Susunan halaman:
      1. Kepala     : logo, judul, dan tanggal cetak
      2. Ringkasan  : total nilai pesanan, uang masuk, piutang
      3. Tabel      : daftar pesanan
--}}

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Laporan Nameera Style</title>

    <style>

        /* Halaman: font dan ukuran tulisan dasar */
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #3F2A22;
        }

        /* Kepala laporan */
        .kepala {
            width: 100%;
            border-bottom: 3px solid #6B4A3C;
            margin-bottom: 18px;
        }

        .kepala td {
            border: none;
            padding: 0 0 10px 0;
        }

        .judul {
            font-size: 18px;
            font-weight: bold;
            color: #4A3228;
        }

        .subjudul {
            font-size: 11px;
            color: #7A6258;
            margin-top: 3px;
        }

        /* Tiga kotak ringkasan */
        .ringkasan {
            width: 100%;
            margin-bottom: 18px;
            border-collapse: separate;
            border-spacing: 8px 0;
            margin-left: -8px;
        }

        .ringkasan td {
            width: 33%;
            border: 1px solid #F0E3DC;
            background-color: #FBF6F3;
            padding: 10px 12px;
        }

        .ringkasan .nama {
            font-size: 10px;
            color: #7A6258;
        }

        .ringkasan .nilai {
            font-size: 15px;
            font-weight: bold;
            margin-top: 4px;
        }

        /* Tabel daftar pesanan */
        .tabel {
            width: 100%;
            border-collapse: collapse;
        }

        .tabel th {
            background-color: #6B4A3C;
            color: #FFFFFF;
            text-align: left;
            font-size: 10px;
            padding: 8px 6px;
            border: 1px solid #6B4A3C;
        }

        .tabel td {
            padding: 7px 6px;
            border: 1px solid #EBDDD5;
        }

        /* Baris genap diberi warna pink muda supaya mudah dibaca */
        .tabel tr.genap td {
            background-color: #FDF8F5;
        }

        .kanan {
            text-align: right;
        }

        .tengah {
            text-align: center;
        }

        .hijau {
            color: #15803D;
        }

        .merah {
            color: #B91C1C;
        }

        .catatan {
            margin-top: 16px;
            font-size: 10px;
            color: #7A6258;
        }

    </style>

</head>


<body>


    {{-- ============ 1. KEPALA ============ --}}
    <table class="kepala">
        <tr>

            {{-- Kiri: judul dan tanggal cetak --}}
            <td>
                <div class="judul">Laporan Administrasi</div>
                <div class="subjudul">Nameera Style &nbsp;|&nbsp; Dicetak pada {{ date('d-m-Y') }}</div>
            </td>

            {{-- Kanan: logo. public_path() mengambil file langsung dari folder public/images --}}
            <td class="kanan">
                <img src="{{ public_path('images/logo.png') }}" alt="Nameera Style" height="40">
            </td>

        </tr>
    </table>


    {{-- ============ 2. RINGKASAN ============ --}}
    <table class="ringkasan">
        <tr>

            <td>
                <div class="nama">Total Nilai Pesanan</div>
                <div class="nilai">Rp {{ number_format($totalPesanan, 0, ',', '.') }}</div>
            </td>

            <td>
                <div class="nama">Uang Masuk</div>
                <div class="nilai hijau">Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</div>
            </td>

            <td>
                <div class="nama">Piutang</div>
                <div class="nilai merah">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</div>
            </td>

        </tr>
    </table>


    {{-- ============ 3. TABEL PESANAN ============ --}}
    <table class="tabel">

        <thead>
            <tr>
                <th class="tengah">No</th>
                <th>Kode Pesanan</th>
                <th>Pelanggan</th>
                <th>Tanggal</th>
                <th class="kanan">Total</th>
                <th class="kanan">Dibayar</th>
                <th class="kanan">Sisa</th>
                <th>Pembayaran</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>

            {{-- $loop->iteration = nomor urut (1, 2, 3, ...) yang disediakan Laravel --}}
            @forelse($orders as $order)

            {{-- Baris genap diberi class "genap" supaya warnanya berbeda --}}
            <tr class="{{ $loop->iteration % 2 == 0 ? 'genap' : '' }}">

                <td class="tengah">{{ $loop->iteration }}</td>

                <td>{{ $order->kode_pesanan }}</td>

                <td>{{ $order->customer->nama }}</td>

                <td>{{ $order->tanggal_pesan }}</td>

                <td class="kanan">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>

                <td class="kanan hijau">Rp {{ number_format($order->totalDibayar(), 0, ',', '.') }}</td>

                <td class="kanan merah">Rp {{ number_format($order->sisaPembayaran(), 0, ',', '.') }}</td>

                {{-- Status pembayaran: hijau kalau Lunas, merah kalau Belum Lunas --}}
                <td>
                    @if($order->statusPembayaran() == 'Lunas')
                        <b class="hijau">Lunas</b>
                    @else
                        <b class="merah">Belum Lunas</b>
                    @endif
                </td>

                <td>{{ $order->status_pesanan }}</td>

            </tr>

            @empty

            <tr>
                <td colspan="9" class="tengah">Belum ada data laporan</td>
            </tr>

            @endforelse

        </tbody>

    </table>


    <p class="catatan">
        Laporan ini dibuat otomatis oleh sistem Nameera Style
    </p>


</body>

</html>