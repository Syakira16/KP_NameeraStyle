@extends('layouts.admin')

@section('title')
Tambah Warna
@endsection

@section('content')

<form action="{{ route('admin.colors.store') }}"
      method="POST"
      class="rounded-2xl border border-[#F0E3DC] bg-white p-6 sm:p-8">

    @csrf

    <h3 class="text-lg font-semibold text-[#4A3228]">
        Informasi Warna
    </h3>

    <p class="mb-6 text-xs text-[#7A6258]">
        Tambahkan warna yang dapat digunakan pada produk
    </p>


    <div>

        <label for="nama_warna"
               class="mb-1.5 block font-medium text-[#4A3228]">
            Nama Warna
        </label>

        <input id="nama_warna"
               name="nama_warna"
               type="text"
               value="{{ old('nama_warna') }}"
               placeholder="Contoh: Dusty Pink"
               class="w-full rounded-xl border border-[#EBDDD5] px-4 py-2.5 placeholder:text-[#B5A39A] focus:border-[#D4708A] focus:outline-none focus:ring-2 focus:ring-[#F8DDE2]">

        @error('nama_warna')
            <p class="mt-1 text-xs text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>


    <div class="mt-8 flex flex-wrap items-center gap-3">

        <button type="submit"
                class="rounded-full bg-[#6B4A3C] px-8 py-2.5 font-medium text-white hover:bg-[#5A3E32]">
            Simpan
        </button>

        <a href="{{ route('admin.colors.index') }}"
           class="rounded-full border-2 border-[#6B4A3C] px-8 py-2 font-medium text-[#6B4A3C] hover:bg-[#FBF6F3]">
            Batal
        </a>

    </div>

</form>

@endsection