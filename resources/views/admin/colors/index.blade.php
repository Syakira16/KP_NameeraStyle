@extends('layouts.admin')


@section('title')
Data Warna
@endsection


@section('content')


{{-- ============ TOMBOL TAMBAH WARNA ============ --}}
<a href="{{ route('admin.colors.create') }}"
   class="inline-flex items-center gap-2 rounded-full bg-[#6B4A3C] px-6 py-2.5 font-medium text-white hover:bg-[#5A3E32]">

    <svg class="h-[18px] w-[18px]"
         viewBox="0 0 24 24"
         fill="none"
         stroke="currentColor"
         stroke-width="2"
         stroke-linecap="round">

        <path d="M12 5v14M5 12h14"/>

    </svg>

    Tambah Warna

</a>


{{-- ============ PESAN BERHASIL ============ --}}
@if(session('success'))

<div class="mt-5 flex items-center gap-3 rounded-2xl border border-green-200 bg-green-50 px-5 py-3 text-green-800">

    <svg class="h-5 w-5 shrink-0"
         viewBox="0 0 24 24"
         fill="none"
         stroke="currentColor"
         stroke-width="2"
         stroke-linecap="round"
         stroke-linejoin="round">

        <circle cx="12" cy="12" r="9"/>
        <path d="m8 12 3 3 5-6"/>

    </svg>

    {{ session('success') }}

</div>

@endif


{{-- ============ PESAN ERROR ============ --}}
@if(session('error'))

<div class="mt-5 flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 px-5 py-3 text-red-800">

    <svg class="h-5 w-5 shrink-0"
         viewBox="0 0 24 24"
         fill="none"
         stroke="currentColor"
         stroke-width="2"
         stroke-linecap="round"
         stroke-linejoin="round">

        <circle cx="12" cy="12" r="9"/>
        <path d="M12 8v5"/>
        <path d="M12 16h.01"/>

    </svg>

    {{ session('error') }}

</div>

@endif


{{-- ============ TABEL WARNA ============ --}}
<div class="mt-5 rounded-2xl border border-[#F0E3DC] bg-white p-6">

    <div class="mb-4 flex items-center justify-between">

        <h3 class="text-lg font-semibold text-[#4A3228]">
            Daftar Warna
        </h3>

        <span class="text-sm text-[#7A6258]">
            {{ count($colors) }} warna
        </span>

    </div>


    <div class="overflow-x-auto">

        <table class="w-full min-w-[750px] table-fixed border-separate border-spacing-0 overflow-hidden rounded-2xl border border-[#EBDDD5] text-left">

            <colgroup>
                <col class="w-[12%]">
                <col class="w-[43%]">
                <col class="w-[20%]">
                <col class="w-[25%]">
            </colgroup>


            {{-- HEADER --}}
            <thead>

                <tr class="bg-[#FBF6F3] text-xs uppercase tracking-wide text-[#7A6258]">

                    <th class="border-b border-r border-[#EBDDD5] px-5 py-3.5 text-center">
                        No
                    </th>

                    <th class="border-b border-r border-[#EBDDD5] px-5 py-3.5 text-center">
                        Nama Warna
                    </th>

                    <th class="border-b border-r border-[#EBDDD5] px-5 py-3.5 text-center">
                        Jumlah Produk
                    </th>

                    <th class="border-b border-[#EBDDD5] px-5 py-3.5 text-center">
                        Aksi
                    </th>

                </tr>

            </thead>


            {{-- DATA --}}
            <tbody>

                @forelse($colors as $color)

                <tr class="text-[#4A3228]">

                    {{-- No --}}
                    <td class="border-b border-r border-[#EBDDD5] px-5 py-4 text-center align-middle">

                        {{ $loop->iteration }}

                    </td>


                    {{-- Nama Warna --}}
                    <td class="border-b border-r border-[#EBDDD5] px-5 py-4 align-middle">

                        <span class="font-semibold">
                            {{ $color->nama_warna }}
                        </span>

                    </td>


                    {{-- Jumlah Produk --}}
                    <td class="border-b border-r border-[#EBDDD5] px-5 py-4 text-center align-middle">

                        <span class="inline-flex rounded-full bg-[#F5EBE5] px-3 py-1 text-xs font-medium text-[#5A4238]">

                            {{ $color->products_count }} produk

                        </span>

                    </td>


                    {{-- Aksi --}}
                    <td class="border-b border-[#EBDDD5] px-5 py-4 align-middle">

                        <div class="flex items-center justify-center gap-2">

                            {{-- Ubah --}}
                            <a href="{{ route('admin.colors.edit', $color->id) }}"
                               class="inline-flex items-center justify-center rounded-full bg-[#EFE0D6] px-4 py-1.5 text-xs font-medium text-[#6B4A3C] transition hover:bg-[#E6D2C4]">

                                Ubah

                            </a>


                            {{-- Hapus --}}
                            <form action="{{ route('admin.colors.destroy', $color->id) }}"
                                  method="POST">

                                @csrf

                                @method('DELETE')

                                <button type="submit"
                                        class="inline-flex items-center justify-center rounded-full bg-red-50 px-4 py-1.5 text-xs font-medium text-red-700 transition hover:bg-red-100"
                                        onclick="return confirm('Hapus warna ini?')">

                                    Hapus

                                </button>

                            </form>

                        </div>

                    </td>

                </tr>


                @empty

                <tr>

                    <td colspan="4"
                        class="border-[#EBDDD5] px-5 py-10 text-center text-[#7A6258]">

                        Belum ada warna.
                        Klik "Tambah Warna" untuk menambahkan.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>


@endsection