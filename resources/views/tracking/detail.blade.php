<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Detail Pesanan - Nameera Style</title>

    {{-- Font Poppins dari Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

</head>


<body class="min-h-screen bg-[#FBF6F3] font-['Poppins',sans-serif] text-sm text-[#3F2A22]">


<div class="mx-auto max-w-3xl space-y-5 px-4 py-10">


    {{-- ============ LOGO ============ --}}
    <div class="flex items-center justify-center gap-3">

        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#6B4A3C] text-xl font-semibold text-white">
            N
        </div>

        <div>
            <p class="text-lg font-semibold leading-tight text-[#4A3228]">Nameera Style</p>
            <p class="text-[10px] uppercase tracking-widest text-[#7A6258]">Busana Muslimah</p>
        </div>

    </div>


    {{-- ============ 1. INFORMASI PESANAN ============ --}}
    <div class="relative overflow-hidden rounded-3xl border border-[#F0E3DC] bg-white p-6 sm:p-8">

        {{-- Hiasan lingkaran --}}
        <div class="pointer-events-none absolute -right-12 -top-16 h-40 w-40 rounded-full bg-[#F8DDE2]"></div>

        <div class="relative">

            <h1 class="mb-5 text-2xl font-semibold text-[#4A3228]">Detail Pesanan</h1>

            <div class="space-y-3">

                <div class="flex justify-between gap-4">
                    <span class="text-[#7A6258]">Kode Pesanan</span>
                    <span class="font-semibold text-[#4A3228]">{{ $order->kode_pesanan }}</span>
                </div>

                <div class="flex justify-between gap-4">
                    <span class="text-[#7A6258]">Nama Pelanggan</span>
                    <span class="font-medium">{{ $order->customer->nama }}</span>
                </div>

                <div class="flex justify-between gap-4">
                    <span class="text-[#7A6258]">Tanggal Pesan</span>
                    <span class="font-medium">{{ $order->tanggal_pesan }}</span>
                </div>

                {{-- Status pesanan: warna label berbeda-beda, tergantung kata di dalam status --}}
                <div class="flex items-center justify-between gap-4">

                    <span class="text-[#7A6258]">Status Pesanan</span>

                    @if (str_contains(strtolower($order->status_pesanan), 'selesai'))

                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                            {{ $order->status_pesanan }}
                        </span>

                    @elseif (str_contains(strtolower($order->status_pesanan), 'kirim'))

                        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700">
                            {{ $order->status_pesanan }}
                        </span>

                    @elseif (str_contains(strtolower($order->status_pesanan), 'produksi'))

                        <span class="rounded-full bg-pink-100 px-3 py-1 text-xs font-medium text-pink-700">
                            {{ $order->status_pesanan }}
                        </span>

                    @else

                        <span class="rounded-full bg-stone-100 px-3 py-1 text-xs font-medium text-stone-700">
                            {{ $order->status_pesanan }}
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- ============ 2. DETAIL PRODUK ============ --}}
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

        {{-- ============ 3. RIWAYAT STATUS PESANAN ============ --}}
    <div class="rounded-2xl border border-[#F0E3DC] bg-white p-6">

        <h2 class="mb-5 text-lg font-semibold text-[#4A3228]">
            Riwayat Status Pesanan
        </h2>


        <div class="space-y-5">

            @forelse($order->statusHistories as $history)

                <div class="flex gap-4">


                    {{-- titik timeline --}}
                    <div class="relative flex flex-col items-center">

                        <div class="h-4 w-4 rounded-full bg-[#6B4A3C]"></div>

                        @if(!$loop->last)

                            <div class="h-full w-0.5 bg-[#EBDDD5]"></div>

                        @endif

                    </div>



                    {{-- isi status --}}
                    <div class="pb-5">

                        <p class="font-semibold text-[#4A3228]">

                            {{ $history->status }}

                        </p>


                        <p class="mt-1 text-sm text-[#7A6258]">

                            {{ $history->keterangan }}

                        </p>


                        <p class="mt-1 text-xs text-[#A18A80]">

                            {{ $history->created_at->format('d M Y, H:i') }}

                        </p>


                    </div>


                </div>


            @empty


                <p class="text-center text-[#7A6258] py-5">

                    Belum ada riwayat status pesanan

                </p>


            @endforelse


        </div>


    </div>


    {{-- ============ 3. INFORMASI PEMBAYARAN ============ --}}
    <div class="rounded-2xl border border-[#F0E3DC] bg-white p-6">

        <h2 class="mb-4 text-lg font-semibold text-[#4A3228]">Informasi Pembayaran</h2>

        <div class="space-y-3">

            <div class="flex justify-between gap-4">
                <span class="text-[#7A6258]">Total Pesanan</span>
                <span class="font-semibold text-[#4A3228]">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
            </div>

            <div class="flex justify-between gap-4">
                <span class="text-[#7A6258]">Total Dibayar</span>
                <span class="font-medium text-green-700">Rp {{ number_format($order->totalDibayar(), 0, ',', '.') }}</span>
            </div>

            <div class="flex justify-between gap-4">
                <span class="text-[#7A6258]">Sisa Pembayaran</span>
                <span class="font-medium text-red-700">Rp {{ number_format($order->sisaPembayaran(), 0, ',', '.') }}</span>
            </div>

            <div class="flex items-center justify-between gap-4 border-t border-[#F5EBE5] pt-3">

                <span class="text-[#7A6258]">Status Pembayaran</span>

                @if($order->statusPembayaran() == 'Lunas')

                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                        Lunas
                    </span>

                @else

                    <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">
                        Belum Lunas
                    </span>

                @endif

            </div>

        </div>


        <h3 class="mb-3 mt-6 font-semibold text-[#4A3228]">Riwayat Pembayaran</h3>

        <div class="overflow-x-auto">

            <table class="w-full min-w-[320px] text-left">

                <thead>
                    <tr class="bg-[#FBF6F3] text-xs uppercase tracking-wide text-[#7A6258]">
                        <th class="rounded-l-xl px-4 py-3">No</th>
                        <th class="px-4 py-3">Tanggal Bayar</th>
                        <th class="rounded-r-xl px-4 py-3">Jumlah</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($order->payments as $index => $payment)

                    <tr class="border-b border-[#F5EBE5]">

                        <td class="px-4 py-3.5">
                            {{ $index + 1 }}
                        </td>

                        <td class="px-4 py-3.5">
                            {{ $payment->tanggal_bayar }}
                        </td>

                        <td class="px-4 py-3.5 font-medium">
                            Rp {{ number_format($payment->jumlah_bayar, 0, ',', '.') }}
                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="3" class="px-4 py-10 text-center text-[#7A6258]">
                            Belum ada pembayaran
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- ============ TOMBOL KEMBALI ============ --}}
    <a href="{{ route('tracking.index') }}"
       class="inline-block rounded-full border-2 border-[#6B4A3C] px-8 py-2 font-medium text-[#6B4A3C] hover:bg-white">
        Kembali
    </a>


</div>


</body>

</html>