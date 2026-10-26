<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Cek Status Pesanan - Nameera Style</title>

    {{-- Font Poppins dari Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

</head>


<body class="min-h-screen bg-[#FBF6F3] font-['Poppins',sans-serif] text-sm text-[#3F2A22]">


<div class="mx-auto flex min-h-screen max-w-md flex-col justify-center px-4 py-10">


    {{-- ============ LOGO ============ --}}
    <div class="mb-6 flex items-center justify-center gap-3">

        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#6B4A3C] text-xl font-semibold text-white">
            N
        </div>

        <div>
            <p class="text-lg font-semibold leading-tight text-[#4A3228]">Nameera Style</p>
            <p class="text-[10px] uppercase tracking-widest text-[#7A6258]">Busana Muslimah</p>
        </div>

    </div>


    {{-- ============ KARTU CEK STATUS ============ --}}
    <div class="relative overflow-hidden rounded-3xl border border-[#F0E3DC] bg-white p-6 sm:p-8">

        {{-- Hiasan lingkaran --}}
        <div class="pointer-events-none absolute -right-12 -top-16 h-40 w-40 rounded-full bg-[#F8DDE2]"></div>

        <div class="relative">

            <h1 class="text-2xl font-semibold text-[#4A3228]">
                Cek Status Pesanan
            </h1>

            <p class="mt-1 mb-6 text-[#7A6258]">
                Masukkan kode pesananmu untuk melihat perkembangan pesanan.
            </p>


            {{-- Pesan error (misalnya kode pesanan tidak ditemukan) --}}
            @if(session('error'))

            <div class="mb-5 flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">

                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7.5v5M12 16.2v.1"/></svg>

                {{ session('error') }}

            </div>

            @endif


            <form action="{{ route('tracking.search') }}"
                  method="POST">

                @csrf

                <label for="kode_pesanan" class="mb-1.5 block font-medium text-[#4A3228]">
                    Kode Pesanan
                </label>

                <input id="kode_pesanan"
                       name="kode_pesanan"
                       type="text"
                       value="{{ old('kode_pesanan') }}"
                       placeholder="Contoh: NS-20261004-1234"
                       class="w-full rounded-xl border border-[#EBDDD5] px-4 py-2.5 placeholder:text-[#B5A39A] focus:border-[#D4708A] focus:outline-none focus:ring-2 focus:ring-[#F8DDE2]">

                <button type="submit"
                        class="mt-5 w-full rounded-full bg-[#6B4A3C] px-6 py-3 font-medium text-white hover:bg-[#5A3E32]">
                    Cek Status
                </button>

            </form>

        </div>

    </div>


    <p class="mt-6 text-center text-xs text-[#7A6258]">
        Kode pesanan dikirim oleh admin Nameera Style saat pesananmu dibuat.
    </p>


</div>


</body>

</html>