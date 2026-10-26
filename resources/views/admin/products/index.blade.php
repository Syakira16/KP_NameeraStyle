@extends('layouts.admin')


@section('title')
Data Produk
@endsection


@section('content')


{{-- ============ TOMBOL TAMBAH PRODUK ============ --}}
<a href="{{ route('admin.products.create') }}"
   class="inline-flex items-center gap-2 rounded-full bg-[#6B4A3C] px-6 py-2.5 font-medium text-white hover:bg-[#5A3E32]">

    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>

    Tambah Produk

</a>


{{-- ============ PESAN BERHASIL ============ --}}
@if(session('success'))

<div class="mt-5 flex items-center gap-3 rounded-2xl border border-green-200 bg-green-50 px-5 py-3 text-green-800">

    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m8 12 3 3 5-6"/></svg>

    {{ session('success') }}

</div>

@endif


{{-- ============ TABEL PRODUK ============ --}}
<div class="mt-5 rounded-2xl border border-[#F0E3DC] bg-white p-6">

    <div class="mb-4 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-[#4A3228]">
            Daftar Produk
        </h3>

        <span class="text-sm text-[#7A6258]">
            {{ count($products) }} produk
        </span>
    </div>


    <div class="overflow-x-auto">

        <table class="w-full min-w-[800px] table-fixed border-separate border-spacing-0 overflow-hidden rounded-2xl border border-[#EBDDD5] text-left">

            {{-- Lebar masing-masing kolom --}}
            <colgroup>
                <col class="w-[28%]">
                <col class="w-[20%]">
                <col class="w-[12%]">
                <col class="w-[18%]">
                <col class="w-[22%]">
            </colgroup>


           <thead>
                <tr class="bg-[#FBF6F3] text-xs uppercase tracking-wide text-[#7A6258]">

                    <th class="border-b border-r border-[#EBDDD5] px-5 py-3.5 text-center">
                        Nama Produk
                    </th>

                    <th class="border-b border-r border-[#EBDDD5] px-5 py-3.5 text-center">
                        Harga
                    </th>

                    <th class="border-b border-r border-[#EBDDD5] px-5 py-3.5 text-center">
                        Ukuran
                    </th>

                    <th class="border-b border-r border-[#EBDDD5] px-5 py-3.5 text-center">
                        Warna
                    </th>

                    <th class="border-b border-[#EBDDD5] px-5 py-3.5 text-center">
                        Aksi
                    </th>

                </tr>

            </thead>


            {{-- DATA --}}
            <tbody>

                @forelse($products as $product)

                <tr class="text-[#4A3228]">

                    {{-- Nama Produk --}}
                    <td class="border-b border-r border-[#EBDDD5] px-5 py-4 align-middle">

                        <span class="font-semibold">
                            {{ $product->nama_produk }}
                        </span>

                    </td>


                    {{-- Harga --}}
                    <td class="border border-[#EBDDD5] px-5 py-4 align-middle whitespace-nowrap">

                        Rp {{ number_format($product->harga, 0, ',', '.') }}

                    </td>


                    {{-- Ukuran --}}
                    <td class="border border-[#EBDDD5] px-5 py-4 text-center align-middle">

                        <span class="inline-flex min-w-[45px] justify-center rounded-full bg-[#F5EBE5] px-3 py-1 text-xs font-medium text-[#5A4238]">

                            {{ $product->ukuran }}

                        </span>

                    </td>


                    {{-- Warna --}}
                    <td class="border border-[#EBDDD5] px-5 py-4 align-middle">

                        {{ $product->color->nama_warna ?? '-' }}

                    </td>


                    {{-- Aksi --}}
                    <td class="border-b border-[#EBDDD5] px-5 py-4 align-middle">

                        <div class="flex items-center justify-center gap-2">

                            {{-- Tombol Ubah --}}
                            <a href="{{ route('admin.products.edit', $product->id) }}"
                               class="inline-flex items-center justify-center rounded-full bg-[#EFE0D6] px-4 py-1.5 text-xs font-medium text-[#6B4A3C] transition hover:bg-[#E6D2C4]">

                                Ubah

                            </a>


                            {{-- Tombol Hapus --}}
                            <form action="{{ route('admin.products.destroy', $product->id) }}"
                                  method="POST">

                                @csrf

                                @method('DELETE')

                                <button type="submit"
                                        class="inline-flex items-center justify-center rounded-full bg-red-50 px-4 py-1.5 text-xs font-medium text-red-700 transition hover:bg-red-100"
                                        onclick="return confirm('Hapus produk?')">

                                    Hapus

                                </button>

                            </form>

                        </div>

                    </td>

                </tr>


                @empty

                <tr>

                    <td colspan="5"
                        class="border border-[#EBDDD5] px-5 py-10 text-center text-[#7A6258]">

                        Belum ada produk.
                        Klik "Tambah Produk" untuk menambahkan.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>


@endsection