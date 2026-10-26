@extends('layouts.admin')


@section('title')
Ubah Pelanggan
@endsection


@section('content')


<form action="{{ route('admin.customers.update', $customer->id) }}"
      method="POST"
      class="rounded-2xl border border-[#F0E3DC] bg-white p-6 sm:p-8">

    @csrf

    @method('PUT')

    <h3 class="text-lg font-semibold text-[#4A3228]">Ubah Informasi Pelanggan</h3>
    <p class="mb-6 text-xs text-[#7A6258]">Perbarui data pelanggan, lalu klik Update untuk menyimpan</p>


    <div class="space-y-5">


        {{-- Nama Pelanggan --}}
        {{-- old('nama', $customer->nama) artinya: pakai isian terakhir kalau form gagal disimpan, kalau tidak ada pakai data pelanggan --}}
        <div>
            <label for="nama" class="mb-1.5 block font-medium text-[#4A3228]">
                Nama Pelanggan
            </label>

            <input id="nama"
                   name="nama"
                   type="text"
                   value="{{ old('nama', $customer->nama) }}"
                   placeholder="Masukkan nama pelanggan"
                   class="w-full rounded-xl border border-[#EBDDD5] px-4 py-2.5 placeholder:text-[#B5A39A] focus:border-[#D4708A] focus:outline-none focus:ring-2 focus:ring-[#F8DDE2]">

            @error('nama')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>


        {{-- Nomor HP --}}
        <div>
            <label for="no_hp" class="mb-1.5 block font-medium text-[#4A3228]">
                Nomor HP
            </label>

            <input id="no_hp"
                   name="no_hp"
                   type="text"
                   inputmode="numeric"
                   minlength="10"
                   maxlength="13"
                   value="{{ old('no_hp', $customer->no_hp) }}"
                   placeholder="08xxxx"
                   class="w-full rounded-xl border border-[#EBDDD5] px-4 py-2.5 placeholder:text-[#B5A39A] focus:border-[#D4708A] focus:outline-none focus:ring-2 focus:ring-[#F8DDE2]">

            @error('no_hp')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>


        {{-- Alamat --}}
        {{-- Tag textarea sengaja ditulis rapat tanpa spasi di dalamnya, supaya isinya tidak diawali spasi kosong --}}
        <div>
            <label for="alamat" class="mb-1.5 block font-medium text-[#4A3228]">
                Alamat
            </label>

            <textarea id="alamat"
                      name="alamat"
                      rows="4"
                      placeholder="Masukkan alamat pelanggan"
                      class="w-full rounded-xl border border-[#EBDDD5] px-4 py-2.5 placeholder:text-[#B5A39A] focus:border-[#D4708A] focus:outline-none focus:ring-2 focus:ring-[#F8DDE2]">{{ old('alamat', $customer->alamat) }}</textarea>

            @error('alamat')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>


    </div>


    {{-- Tombol --}}
    <div class="mt-8 flex flex-wrap items-center gap-3">

        <button type="submit"
                class="rounded-full bg-[#6B4A3C] px-8 py-2.5 font-medium text-white hover:bg-[#5A3E32]">
            Ubah
        </button>

        <a href="{{ route('admin.customers.index') }}"
           class="rounded-full border-2 border-[#6B4A3C] px-8 py-2 font-medium text-[#6B4A3C] hover:bg-[#FBF6F3]">
            Batal
        </a>

    </div>


</form>


@endsection