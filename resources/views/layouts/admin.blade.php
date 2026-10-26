<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title') - Nameera Style</title>

    {{-- Font Poppins dari Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#FBF6F3] font-['Poppins',sans-serif] text-sm text-[#3F2A22]">

<div class="flex">

    {{-- ============ SIDEBAR ============ --}}
    <aside class="min-h-screen w-64 shrink-0 bg-[#D8BCA9] p-5 text-[#3F2A22]">

        {{-- Logo --}}
        <div class="mb-8 flex items-center gap-3 px-2">
            <img src="{{ asset('images/logo-nameera.jpeg') }}" alt="Nameera Style" class="h-10 w-10 rounded-xl bg-white object-contain p-1">
            <div>
                <p class="text-lg font-semibold leading-tight">Nameera Style</p>
                <p class="text-[10px] uppercase tracking-widest text-[#5A3E32]">Busana Muslimah</p>
            </div>
        </div>

        {{-- Menu --}}
        {{-- Menu yang sedang dibuka otomatis diberi latar putih (lihat bagian "request()->...") --}}
        <ul class="space-y-1">

            <li>
                <a href="/admin/dashboard"
                   class="flex items-center gap-3 rounded-xl px-3 py-2.5 {{ request()->is('admin/dashboard') ? 'bg-white font-semibold' : 'font-medium hover:bg-white/50' }}">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
                    Dashboard
                </a>
            </li>

            <li>
                <a href="{{ route('admin.products.index') }}"
                   class="flex items-center gap-3 rounded-xl px-3 py-2.5 {{ request()->routeIs('admin.products.*') ? 'bg-white font-semibold' : 'font-medium hover:bg-white/50' }}">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"/></svg>
                    Produk
                </a>
            </li>

            <li>
                <a href="{{ route('admin.colors.index') }}"
                class="flex items-center gap-3 rounded-xl px-3 py-2.5 {{ request()->routeIs('admin.colors.*') ? 'bg-white font-semibold' : 'font-medium hover:bg-white/50' }}">

                    <svg class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.75"
                        stroke-linecap="round"
                        stroke-linejoin="round">

                        <circle cx="12" cy="12" r="9"/>
                        <circle cx="8.5" cy="9" r="1"/>
                        <circle cx="15.5" cy="8" r="1"/>
                        <circle cx="16" cy="15" r="1"/>
                        <circle cx="9" cy="15" r="1"/>

                    </svg>

                    Data Warna

                </a>
            </li>

            <li>
                <a href="{{ route('admin.customers.index') }}"
                   class="flex items-center gap-3 rounded-xl px-3 py-2.5 {{ request()->routeIs('admin.customers.*') ? 'bg-white font-semibold' : 'font-medium hover:bg-white/50' }}">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3.5"/><path d="M2.5 20c0-3.6 2.9-6 6.5-6s6.5 2.4 6.5 6"/><circle cx="17.5" cy="9" r="2.5"/><path d="M17 14.2c2.6.2 4.5 2 4.5 5"/></svg>
                    Pelanggan
                </a>
            </li>

            <li>
                <a href="{{ route('admin.orders.index') }}"
                   class="flex items-center gap-3 rounded-xl px-3 py-2.5 {{ request()->routeIs('admin.orders.*') ? 'bg-white font-semibold' : 'font-medium hover:bg-white/50' }}">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4V3h6v1M9 11h6M9 15h6"/></svg>
                    Pesanan PO
                </a>
            </li>

            <li>
                <a href="{{ route('admin.payments.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 font-medium hover:bg-white/50">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h14a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h11"/><circle cx="16" cy="13.5" r="1.2"/></svg>
                    Pembayaran
                </a>
            </li>

            <li>
                <a href="{{ route('admin.shipments.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 font-medium hover:bg-white/50">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M2 6h11v10H2zM13 9h4l4 4v3h-8"/><circle cx="6.5" cy="17.5" r="1.8"/><circle cx="17" cy="17.5" r="1.8"/></svg>
                    Pengiriman
                </a>
            </li>

            <li>
                <a href="{{ route('admin.reports.index') }}"
                   class="flex items-center gap-3 rounded-xl px-3 py-2.5 {{ request()->routeIs('admin.reports.*') ? 'bg-white font-semibold' : 'font-medium hover:bg-white/50' }}">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20V10M10 20V4M16 20v-7M22 20H2"/></svg>
                    Laporan
                </a>
            </li>

        </ul>

    </aside>


    {{-- ============ ISI HALAMAN ============ --}}
    <main class="min-w-0 flex-1 p-8">

        {{-- Bagian atas: judul halaman dan nama admin --}}
        <div class="mb-8 flex items-center justify-between">

            <div>
                <p class="text-sm text-[#7A6258]">
                    {{ now()->locale('id')->translatedFormat('l, j F Y') }}
                </p>
                <h1 class="text-3xl font-semibold text-[#4A3228]">
                    @yield('title')
                </h1>
            </div>

            {{-- Tombol Customer Services. Kalau diklik, muncul pilihan Logout (memakai tag details, tanpa JavaScript) --}}
            <details class="relative">

                <summary class="flex cursor-pointer list-none items-center gap-3 rounded-full border border-[#EBDDD5] bg-white py-1.5 pl-1.5 pr-4 hover:bg-[#FDF8F5] [&::-webkit-details-marker]:hidden">

                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#6B4A3C] text-xs font-semibold text-white">
                        C
                    </span>

                    <span class="font-medium">Customer Services</span>

                    <svg class="h-4 w-4 text-[#7A6258]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>

                </summary>

                {{-- Pilihan yang muncul --}}
                <div class="absolute right-0 z-10 mt-2 w-48 rounded-2xl border border-[#F0E3DC] bg-white p-2 shadow-lg">

                    {{-- Logout harus lewat form POST. Kalau route 'logout' belum ada di proyekmu, tombol ini tidak melakukan apa-apa --}}
                    <form action="{{ Route::has('logout') ? route('logout') : '#' }}" method="POST">

                        @csrf

                        <button type="submit"
                                class="flex w-full items-center gap-2 rounded-xl px-3 py-2.5 text-left font-medium text-red-700 hover:bg-red-50">

                            <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>

                            Keluar

                        </button>

                    </form>

                </div>

            </details>

        </div>

        @yield('content')

    </main>

</div>

</body>
</html>