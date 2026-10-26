@extends('layouts.admin')


@section('title')
Tambah Produk
@endsection


@section('content')


<form action="{{ route('admin.products.store') }}"
      method="POST"
      class="rounded-2xl border border-[#F0E3DC] bg-white p-6 sm:p-8">

    @csrf

    <h3 class="text-lg font-semibold text-[#4A3228]">Informasi Produk</h3>
    <p class="mb-6 text-xs text-[#7A6258]">Isi data produk yang akan dijual di Nameera Style</p>


    <div class="space-y-5">


        {{-- Nama Produk --}}
        <div>
            <label for="nama_produk" class="mb-1.5 block font-medium text-[#4A3228]">
                Nama Produk
            </label>

            <input id="nama_produk"
                   name="nama_produk"
                   type="text"
                   value="{{ old('nama_produk') }}"
                   placeholder="Contoh: Gamis Satin Dusty Rose"
                   class="w-full rounded-xl border border-[#EBDDD5] px-4 py-2.5 placeholder:text-[#B5A39A] focus:border-[#D4708A] focus:outline-none focus:ring-2 focus:ring-[#F8DDE2]">

            @error('nama_produk')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>


        {{-- Harga --}}
        <div>
            <label for="harga" class="mb-1.5 block font-medium text-[#4A3228]">
                Harga
            </label>

            <div class="flex">
                <span class="flex items-center rounded-l-xl border border-r-0 border-[#EBDDD5] bg-[#FBF6F3] px-4 text-[#7A6258]">
                    Rp
                </span>

                <input id="harga"
                       name="harga"
                       type="number"
                       min="0"
                       value="{{ old('harga') }}"
                       placeholder="179000"
                       class="w-full rounded-r-xl border border-[#EBDDD5] px-4 py-2.5 placeholder:text-[#B5A39A] focus:border-[#D4708A] focus:outline-none focus:ring-2 focus:ring-[#F8DDE2]">
            </div>

            @error('harga')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>


        {{-- Ukuran dan Warna (sejajar di layar lebar) --}}

        <div class="grid gap-5 sm:grid-cols-2">


            {{-- Ukuran (pilihan) --}}
            <div>
                <label for="ukuran" class="mb-1.5 block font-medium text-[#4A3228]">
                    Ukuran
                </label>

                <select id="ukuran"
                        name="ukuran"
                        class="w-full rounded-xl border border-[#EBDDD5] px-4 py-2.5 focus:border-[#D4708A] focus:outline-none focus:ring-2 focus:ring-[#F8DDE2]">

                    <option value="">Pilih Ukuran</option>

                    <option value="S" {{ old('ukuran') == 'S' ? 'selected' : '' }}>S</option>
                    <option value="M" {{ old('ukuran') == 'M' ? 'selected' : '' }}>M</option>
                    <option value="L" {{ old('ukuran') == 'L' ? 'selected' : '' }}>L</option>
                    <option value="XL" {{ old('ukuran') == 'XL' ? 'selected' : '' }}>XL</option>
                    <option value="XXL" {{ old('ukuran') == 'XXL' ? 'selected' : '' }}>XXL</option>

                </select>

                @error('ukuran')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <div class="mb-1.5 flex items-center justify-between">

                    <label for="color_id" class="font-medium text-[#4A3228]">
                        Warna
                    </label>

                    <a href="{{ route('admin.colors.index') }}"
                    class="text-xs font-medium text-[#8A3E56] hover:underline">
                        + Kelola Warna
                    </a>

                </div>

                <select id="color_id"
                        name="color_id"
                        class="w-full rounded-xl border border-[#EBDDD5] bg-white px-4 py-2.5 focus:border-[#D4708A] focus:outline-none focus:ring-2 focus:ring-[#F8DDE2]">

                    <option value="">Pilih Warna</option>

                    @foreach($colors as $color)

                        <option value="{{ $color->id }}"
                            {{ old('color_id') == $color->id ? 'selected' : '' }}>

                            {{ $color->nama_warna }}

                        </option>

                    @endforeach

                </select>

                @error('color_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror

            </div>

        </div>


    </div>



    {{-- Tombol --}}
    <div class="mt-8 flex flex-wrap items-center gap-3">

        <button type="submit"
                class="rounded-full bg-[#6B4A3C] px-8 py-2.5 font-medium text-white hover:bg-[#5A3E32]">
            Simpan
        </button>

        <a href="{{ route('admin.products.index') }}"
           class="rounded-full border-2 border-[#6B4A3C] px-8 py-2 font-medium text-[#6B4A3C] hover:bg-[#FBF6F3]">
            Batal
        </a>

    </div>


</form>


@endsection