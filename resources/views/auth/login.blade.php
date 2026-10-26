{{--
    HALAMAN LOGIN NAMEERA STYLE

    Susunan halaman:
      1. Panel kiri  : logo dan kalimat sambutan (hanya tampil di layar lebar)
      2. Panel kanan : form login

    Warna yang dipakai:
      #6B4A3C = coklat tua        #F8DDE2 = pink muda
      #FBF6F3 = putih hangat      #D4708A = pink tua (garis saat kolom diklik)
      #4A3228 = coklat untuk judul
      #7A6258 = coklat pudar untuk teks pendukung

    Arti class Tailwind yang sering muncul:
      p-6 / px-6 / py-6 = jarak di dalam kotak
      mb-5 / mt-5       = jarak di bawah / di atas
      rounded-xl        = sudut membulat
      flex              = isi kotak berjajar
      hidden lg:flex    = disembunyikan, tapi tampil di layar lebar
      w-full            = lebar penuh
--}}

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Masuk - Nameera Style</title>

    {{-- Font Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Memuat Tailwind (jalankan "npm run dev" supaya tampilannya aktif) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>


<body class="min-h-screen bg-[#FBF6F3] font-['Poppins',sans-serif] text-sm text-[#3F2A22]">


<div class="flex min-h-screen">


    {{-- ============ 1. PANEL KIRI ============ --}}
    <div class="relative hidden w-1/2 flex-col justify-between overflow-hidden bg-[#6B4A3C] p-12 text-white lg:flex">

        {{-- Tiga lingkaran hiasan --}}
        <div class="absolute -right-24 -top-24 h-96 w-96 rounded-full border-2 border-white/15"></div>
        <div class="absolute -right-4 top-16 h-56 w-56 rounded-full border-2 border-white/15"></div>
        <div class="absolute -bottom-32 -left-20 h-96 w-96 rounded-full bg-white/5"></div>

        {{-- Logo --}}
        {{-- Logo ditaruh di kotak putih supaya warnanya jelas di atas latar coklat. w-auto menjaga bentuk logo tetap asli. Mau lebih kecil/besar: ganti h-20. --}}
        <div class="relative w-fit rounded-2xl bg-white px-6 py-4">
            <img src="{{ asset('images/logo-nameera.jpeg') }}" alt="Nameera Style" class="h-20 w-auto">
        </div>

        {{-- Kalimat sambutan (silakan ubah tulisannya) --}}
        <div class="relative max-w-md">

            <p class="text-xs font-semibold uppercase tracking-widest text-[#F8DDE2]">Assalamu'alaikum</p>

            <h2 class="mt-3 text-3xl font-semibold leading-snug">
                Kelola pesanan pre-order Nameera Style dengan lebih rapi
            </h2>

            <p class="mt-4 text-[#EFE0D6]">
                Pantau pesanan, pembayaran, dan pengiriman busana muslimah dalam satu tempat
            </p>

        </div>

        {{-- date('Y') otomatis menampilkan tahun sekarang --}}
        <p class="relative text-xs text-[#EFE0D6]">
            &copy; {{ date('Y') }} Nameera Style
        </p>

    </div>


    {{-- ============ 2. PANEL KANAN (FORM LOGIN) ============ --}}
    <div class="flex w-full items-center justify-center px-6 py-10 lg:w-1/2">

        <div class="w-full max-w-md">

            {{-- Logo kecil, hanya tampil di HP (di layar lebar sudah ada di panel kiri) --}}
            <div class="mb-8 lg:hidden">
                <img src="{{ asset('images/logo.png') }}" alt="Nameera Style" class="h-16 w-auto">
            </div>


            <h1 class="text-3xl font-semibold text-[#4A3228]">Masuk</h1>

            <p class="mb-8 mt-1 text-[#7A6258]">
                Silakan masuk menggunakan akun Customer Services atau Owner
            </p>


            {{-- Pesan dari sistem, misalnya "password berhasil diubah". Hanya tampil kalau ada. --}}
            @if (session('status'))

            <div class="mb-5 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                {{ session('status') }}
            </div>

            @endif


            {{-- Form dikirim ke route "login" dengan cara POST. @csrf wajib ada (pengaman bawaan Laravel). --}}
            <form action="{{ route('login') }}" method="POST" class="space-y-5">

                @csrf


                {{-- ----- Kolom Email -----

                     Penjelasan atribut:
                       name         = nama data yang dikirim ke Laravel (jangan diubah)
                       value        = isi awal. old('email') mengembalikan email yang tadi diketik kalau login gagal
                       required     = wajib diisi
                       autofocus    = kursor langsung ada di kolom ini
                       placeholder  = tulisan contoh yang pudar
                --}}
                <div>

                    <label for="email" class="mb-1.5 block font-medium text-[#4A3228]">
                        Email
                    </label>

                    <input id="email"
                           name="email"
                           type="email"
                           value="{{ old('email') }}"
                           required
                           autofocus
                           placeholder="nama@email.com"
                           class="w-full rounded-xl border border-[#EBDDD5] px-4 py-2.5 focus:border-[#D4708A] focus:outline-none focus:ring-2 focus:ring-[#F8DDE2]">

                    {{-- Kalau email salah, pesan error muncul di sini --}}
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror

                </div>


                {{-- ----- Kolom Kata Sandi (type="password" membuat isinya tersamarkan) ----- --}}
                <div>

                    <label for="password" class="mb-1.5 block font-medium text-[#4A3228]">
                        Kata Sandi
                    </label>

                    <input id="password"
                           name="password"
                           type="password"
                           required
                           placeholder="Masukkan kata sandi"
                           class="w-full rounded-xl border border-[#EBDDD5] px-4 py-2.5 focus:border-[#D4708A] focus:outline-none focus:ring-2 focus:ring-[#F8DDE2]">

                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror

                </div>

                {{-- ----- Tombol Masuk ----- --}}
                <button type="submit"
                        class="w-full rounded-full bg-[#6B4A3C] px-6 py-3 font-medium text-white hover:bg-[#5A3E32]">
                    Masuk
                </button>


            </form>


        </div>

    </div>


</div>


</body>

</html>