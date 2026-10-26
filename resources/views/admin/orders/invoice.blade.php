<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <title>Invoice {{ $order->kode_pesanan }}</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #3F2A22;
            margin: 0;
            padding: 30px;
            background: white;
        }

        .header {
            width: 100%;
            margin-bottom: 30px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo {
            width: 60px;
            height: 60px;
        }

        .brand {
            font-size: 20px;
            font-weight: bold;
            color: #4A3228;
        }

        .subtitle {
            font-size: 10px;
            color: #7A6258;
            margin-top: 4px;
        }

        .invoice-title {
            text-align: right;
            font-size: 24px;
            font-weight: bold;
            color: #6B4A3C;
        }

        .invoice-code {
            text-align: right;
            margin-top: 5px;
            color: #7A6258;
        }

        .info {
            width: 100%;
            margin-bottom: 25px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-title {
            font-weight: bold;
            color: #6B4A3C;
            margin-bottom: 6px;
        }

        .label {
            width: 100px;
            color: #7A6258;
        }

        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .product-table th {
            background: #FBF6F3;
            color: #6B4A3C;
            font-weight: bold;
            padding: 10px;
            border: 1px solid #EBDDD5;
            text-align: left;
        }

        .product-table td {
            padding: 10px;
            border: 1px solid #EBDDD5;
            vertical-align: top;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .summary {
            width: 100%;
            margin-top: 20px;
        }

        .summary-table {
            width: 45%;
            margin-left: auto;
            border-collapse: collapse;
        }

        .summary-table td {
            padding: 7px;
        }

        .summary-label {
            color: #7A6258;
        }

        .total {
            border-top: 2px solid #6B4A3C;
            font-size: 14px;
            font-weight: bold;
            color: #4A3228;
        }

        .payment-box {
            margin-top: 25px;
            padding: 15px;
            border: 1px solid #EBDDD5;
            background: #FBF6F3;
        }

        .status-lunas {
            color: #15803D;
            font-weight: bold;
        }

        .status-belum {
            color: #B91C1C;
            font-weight: bold;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            color: #7A6258;
            font-size: 10px;
        }
    </style>
</head>

<body>

    {{-- ================= HEADER ================= --}}

    <div class="header">

        <table class="header-table">

            <tr>

                <td>

                    <img
                        src="{{ public_path('images/logo-nameera.jpeg') }}"
                        class="logo"
                    >

                    <div class="brand">
                        Nameera Style
                    </div>

                    <div class="subtitle">
                        Busana Muslimah
                    </div>

                </td>

                <td>

                    <div class="invoice-title">
                        INVOICE
                    </div>

                    <div class="invoice-code">
                        {{ $order->kode_pesanan }}
                    </div>

                </td>

            </tr>

        </table>

    </div>


    {{-- ================= INFORMASI PESANAN ================= --}}

    <div class="info">

        <table class="info-table">

            <tr>

                <td width="50%">

                    <div class="info-title">
                        Kepada
                    </div>

                    <strong>
                        {{ $order->customer->nama }}
                    </strong>

                    <br>

                    {{ $order->customer->no_hp }}

                    <br>

                    {{ $order->customer->alamat }}

                </td>

                <td width="50%">

                    <div class="info-title">
                        Informasi Pesanan
                    </div>

                    <table>

                        <tr>
                            <td class="label">
                                Kode Pesanan
                            </td>

                            <td>
                                : {{ $order->kode_pesanan }}
                            </td>
                        </tr>

                        <tr>
                            <td class="label">
                                Tanggal
                            </td>

                            <td>
                                : {{ \Carbon\Carbon::parse($order->tanggal_pesan)->translatedFormat('d F Y') }}
                            </td>
                        </tr>

                        <tr>
                            <td class="label">
                                Status
                            </td>

                            <td>
                                : {{ $order->status_pesanan }}
                            </td>
                        </tr>

                    </table>

                </td>

            </tr>

        </table>

    </div>


    {{-- ================= DAFTAR PRODUK ================= --}}

    <table class="product-table">

        <thead>

            <tr>

                <th width="5%" class="center">
                    No
                </th>

                <th width="30%">
                    Produk
                </th>

                <th width="15%">
                    Ukuran
                </th>

                <th width="15%">
                    Warna
                </th>

                <th width="10%" class="center">
                    Qty
                </th>

                <th width="25%" class="right">
                    Subtotal
                </th>

            </tr>

        </thead>

        <tbody>

            @foreach($order->orderDetails as $detail)

                <tr>

                    <td class="center">
                        {{ $loop->iteration }}
                    </td>

                    <td>
                        {{ $detail->product->nama_produk }}
                    </td>

                    <td>
                        {{ $detail->product->ukuran }}
                    </td>

                    <td>
                        {{ $detail->product->color->nama_warna ?? '-' }}
                    </td>

                    <td class="center">
                        {{ $detail->jumlah }}
                    </td>

                    <td class="right">

                        Rp
                        {{ number_format(
                            $detail->harga * $detail->jumlah,
                            0,
                            ',',
                            '.'
                        ) }}

                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>


{{-- ================= RINGKASAN HARGA ================= --}}

@php
    $subtotalProduk = $order->orderDetails->sum(function ($detail) {
        return $detail->harga * $detail->jumlah;
    });

    // total_harga sudah merupakan total setelah diskon
    $totalPesanan = $order->total_harga;

    // DP minimal 50% dari total akhir setelah diskon
    $minimalDp = ceil($totalPesanan * 0.50);
@endphp

<div class="summary">
    <table class="summary-table">
        <tr>
            <td class="summary-label">Subtotal Produk</td>
            <td class="right">
                Rp {{ number_format($subtotalProduk, 0, ',', '.') }}
            </td>
        </tr>

        @if($order->diskon > 0)
            <tr>
                <td class="summary-label">Diskon</td>
                <td class="right">
                    - Rp {{ number_format($order->diskon, 0, ',', '.') }}
                </td>
            </tr>
        @endif

        <tr class="total">
            <td>TOTAL PESANAN</td>
            <td class="right">
                Rp {{ number_format($totalPesanan, 0, ',', '.') }}
            </td>
        </tr>
    </table>
</div>



{{-- ================= PEMBAYARAN ================= --}}

<div class="payment-box">
    <strong>Informasi Pembayaran</strong>

    <br><br>

    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="padding: 5px 0;">
                Total Pesanan
            </td>
            <td class="right" style="padding: 5px 0;">
                Rp {{ number_format($totalPesanan, 0, ',', '.') }}
            </td>
        </tr>

        <tr>
            <td style="padding: 5px 0;">
                DP Minimal (50%)
            </td>
            <td class="right" style="padding: 5px 0;">
                <strong>
                    Rp {{ number_format($minimalDp, 0, ',', '.') }}
                </strong>
            </td>
        </tr>

        <tr>
            <td style="padding: 5px 0;">
                Total Dibayar
            </td>
            <td class="right" style="padding: 5px 0;">
                Rp {{ number_format($order->totalDibayar(), 0, ',', '.') }}
            </td>
        </tr>

        <tr>
            <td style="padding: 5px 0;">
                Sisa Pembayaran
            </td>
            <td class="right" style="padding: 5px 0;">
                <strong>
                    Rp {{ number_format($order->sisaPembayaran(), 0, ',', '.') }}
                </strong>
            </td>
        </tr>
    </table>

    <br>

    Status Pembayaran:
    @if($order->statusPembayaran() == 'Lunas')
        <span class="status-lunas">LUNAS</span>
    @else
        <span class="status-belum">BELUM LUNAS</span>
    @endif

    @if($order->totalDibayar() < $minimalDp)
        <br><br>
        <span class="status-belum">
            Pembayaran belum mencapai DP minimal 50%.
        </span>
    @endif
</div>



    {{-- ================= FOOTER ================= --}}

    <div class="footer">

        Terima kasih telah berbelanja di Nameera Style.

        <br>

        Invoice ini dibuat secara otomatis oleh sistem.

    </div>

</body>

</html>