@extends('layouts.admin')

@section('title')
Dashboard
@endsection

@section('content')

<div class="space-y-6">
    <div class="relative overflow-hidden rounded-3xl bg-[#F8DDE2] p-8">
        <div class="pointer-events-none absolute -right-14 -top-24 h-64 w-64 rounded-full border-2 border-[#EDBFCA]"></div>
        <div class="pointer-events-none absolute right-5 -top-8 h-36 w-36 rounded-full border-2 border-[#EDBFCA]"></div>

        <p class="text-xs font-semibold uppercase tracking-widest text-[#8A3E56]">
            Assalamu'alaikum
        </p>

        <h2 class="mt-1 text-2xl font-semibold text-[#4A3228]">
            Selamat datang kembali, @if(auth()->user()->role == 'owner') Owner @else Customer Services @endif
        </h2>

        <p class="mt-1 max-w-xl text-[#664B42]">
            Pantau pesanan PO, pembayaran, dan pengiriman gamis serta hijab pelangganmu dalam satu tempat.
        </p>

        <div class="relative mt-5 flex flex-wrap gap-3">
            {{-- Tombol ini hanya tampil untuk Customer Services --}}
            @if(auth()->user()->role == 'admin')
            <a href="{{ route('admin.orders.index') }}"
               class="rounded-full bg-[#6B4A3C] px-6 py-2.5 font-medium text-white hover:bg-[#5A3E32]">
                Lihat Pesanan
            </a>
            @endif
            <a href="{{ route('admin.reports.index') }}"
               class="rounded-full border-2 border-[#6B4A3C] px-6 py-2.5 font-medium text-[#6B4A3C] hover:bg-white/60">
                Lihat Laporan
            </div>
        </a>

    </div>


    {{-- ============ 2. KARTU RINGKASAN (4 kartu) ============ --}}
    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">

        {{-- Total Pesanan --}}
        <div class="rounded-2xl border border-[#F0E3DC] bg-white p-5">
            <div class="flex items-center justify-between">
                <p class="font-medium text-[#7A6258]">Total Pesanan</p>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#F8DDE2] text-[#8A3E56]">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4V3h6v1M9 11h6M9 15h6"/></svg>
                </span>
            </div>
            <h3 class="mt-3 text-3xl font-semibold">{{ $jumlahPesanan }}</h3>
        </div>

        {{-- Total Nilai Pesanan --}}
        <div class="rounded-2xl border border-[#F0E3DC] bg-white p-5">
            <div class="flex items-center justify-between">
                <p class="font-medium text-[#7A6258]">Total Nilai Pesanan</p>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#EFE0D6] text-[#6B4A3C]">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"/></svg>
                </span>
            </div>
            <h3 class="mt-3 text-2xl font-semibold">
                Rp {{ number_format($totalPesanan, 0, ',', '.') }}
            </h3>
        </div>

        {{-- Uang Masuk --}}
        <div class="rounded-2xl border border-[#F0E3DC] bg-white p-5">
            <div class="flex items-center justify-between">
                <p class="font-medium text-[#7A6258]">Pembayaran Masuk</p>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-100 text-green-700">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h14a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h11"/><circle cx="16" cy="13.5" r="1.2"/></svg>
                </span>
            </div>
            <h3 class="mt-3 text-2xl font-semibold text-green-700">
                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
            </h3>
        </div>

        {{-- Piutang --}}
        <div class="rounded-2xl border border-[#F0E3DC] bg-white p-5">
            <div class="flex items-center justify-between">
                <p class="font-medium text-[#7A6258]">Piutang</p>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-100 text-red-700">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                </span>
            </div>
            <h3 class="mt-3 text-2xl font-semibold text-red-700">
                Rp {{ number_format($totalPiutang, 0, ',', '.') }}
            </h3>
        </div>

    </div>


    {{-- ============ 3. BELUM LUNAS & SIAP DIKIRIM (2 kartu) ============ --}}
    <div class="grid gap-5 md:grid-cols-2">

        <a href="{{ route('admin.orders.index', ['filter' => 'belum_lunas']) }}"
           class="flex items-center gap-4 rounded-2xl border border-[#F0E3DC] bg-white p-5 hover:border-[#D4708A]">
            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-100 text-red-700">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7.5v5M12 16.2v.1"/></svg>
            </span>
            <div>
                <p class="font-medium text-[#7A6258]">Belum Lunas</p>
                <h3 class="text-2xl font-semibold text-red-700">{{ $belumLunas }} pesanan</h3>
            </div>
        </a>

        <a href="{{ route('admin.orders.index', ['filter' => 'siap_kirim']) }}"
           class="flex items-center gap-4 rounded-2xl border border-[#F0E3DC] bg-white p-5 hover:border-[#D4708A]">
            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-100 text-blue-700">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M2 6h11v10H2zM13 9h4l4 4v3h-8"/><circle cx="6.5" cy="17.5" r="1.8"/><circle cx="17" cy="17.5" r="1.8"/></svg>
            </span>
            <div>
                <p class="font-medium text-[#7A6258]">Siap Dikirim</p>
                <h3 class="text-2xl font-semibold text-blue-700">{{ $siapKirim }} pesanan</h3>
            </div>
        </a>

    </div>


    {{-- ============ 4. PESANAN TERBARU ============ --}}
    <div class="rounded-2xl border border-[#F0E3DC] bg-white p-6">

        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-[#4A3228]">Pesanan Terbaru</h3>
            @if(auth()->user()->role == 'admin')
            <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-[#9A4A5F] hover:underline">
                Lihat semua
            </a>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[500px] text-left">

                <thead>
                    <tr class="bg-[#FBF6F3] text-xs uppercase tracking-wide text-[#7A6258]">
                        <th class="rounded-l-xl px-4 py-3">Kode</th>
                        <th class="px-4 py-3">Pelanggan</th>
                        <th class="px-4 py-3">Total</th>
                        <th class="rounded-r-xl px-4 py-3">Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($pesananTerbaru as $order)

                        <tr class="border-b border-[#F5EBE5]">
                            <td class="px-4 py-3 font-semibold text-[#4A3228]">
                                {{ $order->kode_pesanan }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $order->customer->nama }}
                            </td>
                            <td class="px-4 py-3">
                                Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3">

                                {{-- Warna label mengikuti status pesanan --}}
                                @if($order->status_pesanan == 'Menunggu Produksi')
                                    <span class="rounded-full bg-pink-100 px-3 py-1 text-xs font-medium text-pink-700">{{ $order->status_pesanan }}</span>
                                @elseif($order->status_pesanan == 'Ready')
                                    <span class="rounded-full bg-[#EFE0D6] px-3 py-1 text-xs font-medium text-[#6B4A3C]">{{ $order->status_pesanan }}</span>
                                @elseif($order->status_pesanan == 'Packing')
                                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-700">{{ $order->status_pesanan }}</span>
                                @elseif($order->status_pesanan == 'Dikirim')
                                    <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700">{{ $order->status_pesanan }}</span>
                                @else
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">{{ $order->status_pesanan }}</span>
                                @endif

                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="px-4 py-10 text-center text-[#7A6258]">
                                Belum ada pesanan
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>
        </div>

    </div>


    {{-- ============ 5. MENUNGGU PRODUKSI ============ --}}
    <div class="rounded-2xl border border-[#F0E3DC] bg-white p-6">

        <h3 class="mb-4 text-lg font-semibold text-[#4A3228]">Menunggu Produksi</h3>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[400px] text-left">

                <thead>
                    <tr class="bg-[#FBF6F3] text-xs uppercase tracking-wide text-[#7A6258]">
                        <th class="rounded-l-xl px-4 py-3">Kode</th>
                        <th class="px-4 py-3">Pelanggan</th>
                        <th class="rounded-r-xl px-4 py-3">Tanggal</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($menungguProduksi as $order)

                        <tr class="border-b border-[#F5EBE5]">
                            <td class="px-4 py-3 font-semibold text-[#4A3228]">
                                {{ $order->kode_pesanan }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $order->customer->nama }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $order->tanggal_pesan }}
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="3" class="px-4 py-10 text-center text-[#7A6258]">
                                Tidak ada pesanan
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>
        </div>

    </div>


    {{-- ============ 6. PERLU DITAGIH & PRODUK TERLARIS ============ --}}
    {{-- Dua kartu ini diisi dari DashboardController: $perluDitagih (pesanan yang masih punya sisa pembayaran) dan $produkTerlaris (nama produk => jumlah terjual). --}}
    <div class="grid gap-5 lg:grid-cols-2">

        {{-- Perlu ditagih --}}
        <div class="rounded-2xl border border-[#F0E3DC] bg-white p-6">

            <h3 class="text-lg font-semibold text-[#4A3228]">Pesanan yang masih memiliki tagihan</h3>
            <p class="mb-4 text-xs text-[#7A6258]">Pelanggan yang masih punya sisa pembayaran</p>

            <div class="space-y-3">

                {{-- Klik salah satu pesanan untuk membuka Detail Pesanan (hanya Customer Services). Owner hanya melihat daftarnya. --}}
                @forelse($perluDitagih as $order)

                    <a href="@if(auth()->user()->role == 'admin'){{ route('admin.orders.show', $order->id) }}@else#@endif"
                       class="flex items-center justify-between rounded-xl bg-[#FDF8F5] p-3 hover:bg-[#F8EDE7]">
                        <div>
                            <p class="font-medium">{{ $order->customer->nama }}</p>
                            <p class="text-xs text-[#7A6258]">
                                {{ $order->kode_pesanan }} · dipesan {{ $order->tanggal_pesan }}
                            </p>
                        </div>
                        <p class="font-semibold text-red-700">
                            Rp {{ number_format($order->sisaPembayaran(), 0, ',', '.') }}
                        </p>
                    </a>

                @empty

                    <p class="py-8 text-center text-[#7A6258]">Tidak ada tagihan yang perlu ditindaklanjuti</p>

                @endforelse

            </div>

        </div>

        
        {{-- Status pesanan --}}
        <div class="rounded-2xl border border-[#F0E3DC] bg-white p-6">

            <h3 class="text-lg font-semibold text-[#4A3228]">Status Pesanan</h3>
            <p class="mb-5 text-xs text-[#7A6258]">Jumlah pesanan per tahap</p>

            <div class="space-y-4">

                @foreach($statusPesanan as $nama => $jumlah)

                    <div class="flex items-center gap-3">
                        <span class="w-28 shrink-0">{{ $nama }}</span>
                        <div class="h-3.5 flex-1 overflow-hidden rounded-full bg-[#F5EBE5]">
                            <div class="h-full rounded-full bg-[#D4708A]"
                                 style="width: {{ round($jumlah / (max($statusPesanan) ?: 1) * 100) }}%"></div>
                        </div>
                        <span class="w-8 text-right font-semibold">{{ $jumlah }}</span>
                    </div>

                @endforeach

            </div>

        </div>
       

    </div>

</div>

@endsection