<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Nameera Style - Busana Muslimah</title>

    {{-- Font Poppins dari Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{--
        HEADER MUNCUL SAAT SCROLL (tanpa JavaScript, murni CSS)

        Di paling atas halaman, header tersembunyi.
        Setelah halaman digulir sekitar 160px, header muncul perlahan.
        Mau muncul lebih cepat/lambat: ubah angka 160px pada animation-range.

        @supports = aturan ini hanya dipakai browser yang mendukung (Chrome, Edge, Safari baru).
        Di browser yang belum mendukung (misalnya Firefox), header tetap tampil seperti biasa.
    --}}
    <style>
        @supports (animation-timeline: scroll()) {

            .header-scroll {
                opacity: 0;
                visibility: hidden;
                transform: translateY(-100%);
                animation: header-muncul linear forwards;
                animation-timeline: scroll();
                animation-range: 0 160px;
            }

            @keyframes header-muncul {
                to {
                    opacity: 1;
                    visibility: visible;
                    transform: translateY(0);
                }
            }

        }
    </style>

</head>


<body class="min-h-screen bg-[#FBF6F3] font-['Poppins',sans-serif] text-sm text-[#3F2A22]">


{{-- ============ 1. MENU ATAS ============ --}}
{{-- fixed = menu menempel di atas layar saat digulir. class header-scroll = muncul setelah halaman digulir (lihat <style> di atas). --}}
<header class="header-scroll fixed inset-x-0 top-0 z-50 border-b border-[#F0E3DC] bg-white/95 backdrop-blur">

<div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-6 py-3">

    {{-- Logo (file logo.png ada di folder public/images) --}}
    <a href="/">
        <img src="{{ asset('images/logo-nameera.jpeg') }}" alt="Nameera Style" class="h-14 w-auto">
    </a>

    <nav class="flex items-center gap-3">

        <a href="{{ route('tracking.index') }}"
           class="hidden px-3 py-2 font-medium text-[#6B4A3C] hover:underline sm:block">
            Cek Status Pesanan
        </a>

        {{-- Kalau sudah login tampil "Buka Dashboard", kalau belum tampil "Masuk" --}}
        @auth

        <a href="{{ url('/admin/dashboard') }}"
           class="rounded-full bg-[#6B4A3C] px-6 py-2.5 font-medium text-white hover:bg-[#5A3E32]">
            Buka Dashboard
        </a>

        @else

        <a href="{{ route('login') }}"
           class="rounded-full bg-[#6B4A3C] px-6 py-2.5 font-medium text-white hover:bg-[#5A3E32]">
            Masuk
        </a>

        @endauth

    </nav>

</div>

</header>


{{-- ============ 2. BAGIAN UTAMA ============ --}}
<main class="mx-auto max-w-6xl px-6">

    <section class="grid items-center gap-10 py-10 lg:grid-cols-2 lg:py-16">

        {{-- Teks sambutan --}}
        <div>

            <p class="text-xs font-semibold uppercase tracking-widest text-[#8A3E56]">
                Assalamu'alaikum
            </p>

            <h1 class="mt-3 text-4xl font-semibold leading-tight text-[#4A3228]">
                Busana muslimah elegan, dipesan dengan mudah lewat pre-order.
            </h1>

            <p class="mt-4 max-w-lg text-base text-[#664B42]">
                Pantau status pesanan Anda kapan saja, cukup dengan memasukkan kode pesanan.
            </p>

            {{-- Dua pintu masuk: satu untuk Pelanggan, satu untuk Customer Services dan Owner --}}
            <div class="mt-8 grid gap-4 sm:grid-cols-2">

                {{-- Untuk Pelanggan (tanpa login) --}}
                <div class="rounded-2xl border border-[#F0E3DC] bg-white p-5">

                    <p class="text-xs font-semibold uppercase tracking-widest text-[#8A3E56]">Untuk Pelanggan</p>

                    <p class="mt-2 text-[#664B42]">
                        Punya kode pesanan? Cek status dan nomor resi pesanan Anda tanpa perlu login.
                    </p>

                    <a href="{{ route('tracking.index') }}"
                       class="mt-4 inline-block rounded-full border-2 border-[#6B4A3C] px-6 py-2 font-medium text-[#6B4A3C] hover:bg-[#FBF6F3]">
                        Cek Status Pesanan
                    </a>

                </div>

                {{-- Untuk Customer Services dan Owner (perlu login) --}}
                <div class="rounded-2xl border border-[#F0E3DC] bg-white p-5">

                    <p class="text-xs font-semibold uppercase tracking-widest text-[#8A3E56]">Untuk Customer Services &amp; Owner</p>

                    <p class="mt-2 text-[#664B42]">
                        Masuk untuk mengelola pesanan, pembayaran, dan pengiriman.
                    </p>

                    @auth

                    <a href="{{ url('/admin/dashboard') }}"
                       class="mt-4 inline-block rounded-full bg-[#6B4A3C] px-6 py-2.5 font-medium text-white hover:bg-[#5A3E32]">
                        Buka Dashboard
                    </a>

                    @else

                    <a href="{{ route('login') }}"
                       class="mt-4 inline-block rounded-full bg-[#6B4A3C] px-6 py-2.5 font-medium text-white hover:bg-[#5A3E32]">
                        Masuk
                    </a>

                    @endauth

                </div>

            </div>

        </div>


        {{-- Kartu logo dengan hiasan lingkaran pink --}}
        <div class="relative overflow-hidden rounded-3xl border border-[#F0E3DC] bg-white px-8 py-14 sm:px-14">
            {{-- Lebar logo dibatasi max-w-md (sekitar 448px). Mau lebih kecil: max-w-sm. Mau lebih besar: max-w-lg. --}}
            <img src="{{ asset('images/logo-nameera.jpeg') }}"
                 alt="Logo Nameera Style"
                 class="relative mx-auto h-auto w-full max-w-md">

        </div>

    </section>


    {{-- ============ 3. KEUNGGULAN ============ --}}
    <section class="grid gap-5 pb-16 md:grid-cols-3">

        <div class="rounded-2xl border border-[#F0E3DC] bg-white p-6">

            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#F8DDE2] text-[#8A3E56]">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4V3h6v1M9 11h6M9 15h6"/></svg>
            </span>

            <h2 class="mt-4 text-lg font-semibold text-[#4A3228]">Pre-Order Rapi</h2>

            <p class="mt-1 text-[#7A6258]">
                Setiap pesanan tercatat lengkap, mulai dari produk, ukuran, warna, sampai jumlahnya.
            </p>

        </div>

        <div class="rounded-2xl border border-[#F0E3DC] bg-white p-6">

            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#EFE0D6] text-[#6B4A3C]">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h14a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h11"/><circle cx="16" cy="13.5" r="1.2"/></svg>
            </span>

            <h2 class="mt-4 text-lg font-semibold text-[#4A3228]">Pembayaran Tercatat</h2>

            <p class="mt-1 text-[#7A6258]">
                Riwayat pembayaran dan sisa tagihan setiap pesanan bisa dilihat dengan jelas.
            </p>

        </div>

        <div class="rounded-2xl border border-[#F0E3DC] bg-white p-6">

            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#E3EBF5] text-[#2A5080]">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M2 6h11v10H2zM13 9h4l4 4v3h-8"/><circle cx="6.5" cy="17.5" r="1.8"/><circle cx="17" cy="17.5" r="1.8"/></svg>
            </span>

            <h2 class="mt-4 text-lg font-semibold text-[#4A3228]">Lacak Pesanan</h2>

            <p class="mt-1 text-[#7A6258]">
                Pelanggan cukup memasukkan kode pesanan untuk melihat status dan nomor resi.
            </p>

        </div>

    </section>

</main>


{{-- ============ 4. BAGIAN BAWAH ============ --}}
<footer class="border-t border-[#F0E3DC] py-6 text-center text-xs text-[#7A6258]">
    &copy; {{ date('Y') }} Nameera Style
</footer>


</body>

</html>