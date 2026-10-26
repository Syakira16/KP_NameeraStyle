<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Status Pesanan - Nameera Style</title>

    {{-- Font Poppins dari Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

</head>


<body class="min-h-screen bg-[#FBF6F3] font-['Poppins',sans-serif] text-sm text-[#3F2A22]">


<div class="mx-auto max-w-3xl space-y-5 px-4 py-10">
    <div class="flex items-center justify-center gap-3">

        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#6B4A3C] text-xl font-semibold text-white">
            N
        </div>

        <div>
            <p class="text-lg font-semibold leading-tight text-[#4A3228]">Nameera Style</p>
            <p class="text-[10px] uppercase tracking-widest text-[#7A6258]">Busana Muslimah</p>
        </div>

    </div>

    <div class="relative overflow-hidden rounded-3xl bg-[#F8DDE2] p-6 sm:p-8">

        <div class="pointer-events-none absolute -right-14 -top-24 h-64 w-64 rounded-full border-2 border-[#EDBFCA]"></div>
        <div class="pointer-events-none absolute right-5 -top-8 h-36 w-36 rounded-full border-2 border-[#EDBFCA]"></div>

        <div class="relative">

            <p class="text-xs font-semibold uppercase tracking-widest text-[#8A3E56]">Status Pesanan</p>

            <p class="mt-1 text-[#664B42]">
                Kode Pesanan: <span class="font-semibold text-[#4A3228]">{{ $order->kode_pesanan }}</span>
            </p>

            <div class="mt-4">

                @if (str_contains(strtolower($order->status_pesanan), 'selesai'))

                    <span class="inline-block rounded-full bg-green-100 px-5 py-2 text-lg font-semibold text-green-700">
                        {{ $order->status_pesanan }}
                    </span>

                @elseif (str_contains(strtolower($order->status_pesanan), 'kirim'))

                    <span class="inline-block rounded-full bg-blue-100 px-5 py-2 text-lg font-semibold text-blue-700">
                        {{ $order->status_pesanan }}
                    </span>

                @elseif (str_contains(strtolower($order->status_pesanan), 'produksi'))

                    <span class="inline-block rounded-full bg-white px-5 py-2 text-lg font-semibold text-[#8A3E56]">
                        {{ $order->status_pesanan }}
                    </span>

                @else

                    <span class="inline-block rounded-full bg-white px-5 py-2 text-lg font-semibold text-[#4A3228]">
                        {{ $order->status_pesanan }}
                    </span>

                @endif

            </div>

        </div>

    </div>


    {{-- ============ 2. INFORMASI PESANAN ============ --}}
    <div class="rounded-2xl border border-[#F0E3DC] bg-white p-6">

        <h2 class="mb-4 text-lg font-semibold text-[#4A3228]">Informasi Pesanan</h2>

        <div class="space-y-3">

            <div class="flex justify-between gap-4">
                <span class="text-[#7A6258]">Pelanggan</span>
                <span class="font-medium">{{ $order->customer->nama }}</span>
            </div>

            <div class="flex justify-between gap-4">
                <span class="text-[#7A6258]">Tanggal Pesan</span>
                <span class="font-medium">{{ $order->tanggal_pesan }}</span>
            </div>

            <div class="flex justify-between gap-4">
                <span class="text-[#7A6258]">Diskon</span>
                <span class="font-medium">Rp {{ number_format($order->diskon, 0, ',', '.') }}</span>
            </div>

            <div class="flex justify-between gap-4 border-t border-[#F5EBE5] pt-3">
                <span class="font-semibold text-[#4A3228]">Total Pembayaran</span>
                <span class="font-semibold text-[#4A3228]">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
            </div>

        </div>

    </div>


    {{-- ============ 3. DETAIL PRODUK ============ --}}
    <div class="rounded-2xl border border-[#F0E3DC] bg-white p-6">

        <h2 class="mb-4 text-lg font-semibold text-[#4A3228]">Detail Produk</h2>

        <div class="overflow-x-auto">

            <table class="w-full min-w-[480px] text-left">

                <thead>
                    <tr class="bg-[#FBF6F3] text-xs uppercase tracking-wide text-[#7A6258]">
                        <th class="rounded-l-xl px-4 py-3">Produk</th>
                        <th class="px-4 py-3">Warna</th>
                        <th class="px-4 py-3">Ukuran</th>
                        <th class="px-4 py-3">Jumlah</th>
                        <th class="rounded-r-xl px-4 py-3">Harga</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($order->orderDetails as $detail)

                    <tr class="border-b border-[#F5EBE5]">

                        <td class="px-4 py-3.5 font-semibold text-[#4A3228]">
                            {{ $detail->product->nama_produk }}
                        </td>

                        <td class="px-4 py-3.5">
                            {{ $detail->product->warna }}
                        </td>

                        <td class="px-4 py-3.5">
                            <span class="rounded-full bg-[#F5EBE5] px-3 py-1 text-xs font-medium text-[#5A4238]">
                                {{ $detail->product->ukuran }}
                            </span>
                        </td>

                        <td class="px-4 py-3.5">
                            {{ $detail->jumlah }} pcs
                        </td>

                        <td class="px-4 py-3.5 font-medium">
                            Rp {{ number_format($detail->harga, 0, ',', '.') }}
                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>


    {{-- ============ 4. INFORMASI PENGIRIMAN ============ --}}
    <div class="rounded-2xl border border-[#F0E3DC] bg-white p-6">

        <h2 class="mb-4 text-lg font-semibold text-[#4A3228]">Informasi Pengiriman</h2>

        @if($order->shipment)

        <div class="grid gap-4 sm:grid-cols-3">

            <div class="rounded-2xl bg-[#FDF8F5] p-4">
                <p class="text-xs text-[#7A6258]">Ekspedisi</p>
                <p class="mt-1 font-semibold text-[#4A3228]">{{ $order->shipment->ekspedisi ?? '-' }}</p>
            </div>

            <div class="rounded-2xl bg-[#FDF8F5] p-4">
                <p class="text-xs text-[#7A6258]">Nomor Resi</p>
                <p class="mt-1 font-semibold text-[#4A3228]">{{ $order->shipment->nomor_resi }}</p>
            </div>

            <div class="rounded-2xl bg-[#FDF8F5] p-4">
                <p class="text-xs text-[#7A6258]">Tanggal Kirim</p>
                <p class="mt-1 font-semibold text-[#4A3228]">{{ $order->shipment->tanggal_kirim }}</p>
            </div>

        </div>

        @else

        <p class="rounded-2xl bg-[#FDF8F5] px-4 py-8 text-center text-[#7A6258]">
            Pesanan belum dikirim
        </p>

        @endif

    </div>


    {{-- ============ TOMBOL ============ --}}
    <div class="flex flex-wrap gap-3">

        <a href="{{ route('tracking.detail', $order->kode_pesanan) }}"
           class="rounded-full bg-[#6B4A3C] px-8 py-2.5 font-medium text-white hover:bg-[#5A3E32]">
            Lihat Detail Pesanan
        </a>

        <a href="{{ route('tracking.index') }}"
           class="rounded-full border-2 border-[#6B4A3C] px-8 py-2 font-medium text-[#6B4A3C] hover:bg-white">
            Kembali
        </a>

    </div>


</div>


</body>

</html>