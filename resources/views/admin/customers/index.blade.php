@extends('layouts.admin')


@section('title')
Data Pelanggan
@endsection


@section('content')


{{-- ============ TOMBOL TAMBAH (kiri) DAN KOLOM CARI (kanan) ============ --}}
<div class="flex flex-wrap items-center justify-between gap-3">


    <a href="{{ route('admin.customers.create') }}"
       class="inline-flex items-center gap-2 rounded-full bg-[#6B4A3C] px-6 py-2.5 font-medium text-white hover:bg-[#5A3E32]">

        <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>

        Tambah Pelanggan

    </a>


    {{--
        Form pencarian.
        method="GET" : kata yang diketik ikut di alamat halaman, contoh: /admin/customers?cari=siti
        name="cari"  : nama data yang dibaca controller lewat $request->cari
        value        : mengisi kembali kolom dengan kata yang tadi dicari
    --}}
    <form action="{{ route('admin.customers.index') }}" method="GET" class="flex items-center gap-2">

        <input type="text"
               name="cari"
               value="{{ $cari }}"
               placeholder="Cari nama, no HP, atau alamat"
               class="w-64 rounded-full border border-[#EBDDD5] bg-white px-5 py-2.5 placeholder:text-[#B5A39A] focus:border-[#D4708A] focus:outline-none focus:ring-2 focus:ring-[#F8DDE2]">

        <button type="submit"
                class="rounded-full bg-[#EFE0D6] px-5 py-2.5 font-medium text-[#6B4A3C] hover:bg-[#E6D2C4]">
            Cari
        </button>

        {{-- Tombol Reset hanya muncul kalau sedang mencari. Klik untuk menampilkan semua pelanggan lagi. --}}
        @if($cari)
        <a href="{{ route('admin.customers.index') }}"
           class="rounded-full border-2 border-[#6B4A3C] px-5 py-2 font-medium text-[#6B4A3C] hover:bg-[#FBF6F3]">
            Tampilkan Semua
        </a>
        @endif

    </form>


</div>


{{-- ============ PESAN BERHASIL ============ --}}
@if(session('success'))

<div class="mt-5 flex items-center gap-3 rounded-2xl border border-green-200 bg-green-50 px-5 py-3 text-green-800">

    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m8 12 3 3 5-6"/></svg>

    {{ session('success') }}

</div>

@endif


{{-- ============ PESAN GAGAL (contoh: pelanggan tidak bisa dihapus karena punya pesanan) ============ --}}
@if(session('error'))

<div class="mt-5 rounded-2xl border border-red-200 bg-red-50 px-5 py-3 text-red-800">
    {{ session('error') }}
</div>

@endif


{{-- ============ TABEL PELANGGAN ============ --}}
<div class="mt-5 rounded-2xl border border-[#F0E3DC] bg-white p-6">

    <div class="mb-4 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-[#4A3228]">Daftar Pelanggan</h3>
        <span class="text-sm text-[#7A6258]">{{ $customers->total() }} pelanggan</span>
    </div>

<div class="overflow-x-auto">

    <table class="w-full min-w-[850px] table-fixed border-separate border-spacing-0 overflow-hidden rounded-2xl border border-[#EBDDD5] text-left">

        {{-- ================= LEBAR KOLOM ================= --}}
        <colgroup>
            <col class="w-[23%]">
            <col class="w-[15%]">
            <col class="w-[32%]">
            <col class="w-[30%]">
        </colgroup>


        {{-- ================= HEADER ================= --}}
        <thead>

            <tr class="bg-[#FBF6F3] text-xs uppercase tracking-wide text-[#7A6258]">

                <th class="border-b border-r border-[#EBDDD5] px-5 py-3.5 text-center">
                    Nama
                </th>

                <th class="border-b border-r border-[#EBDDD5] px-5 py-3.5 text-center">
                    No HP
                </th>

                <th class="border-b border-r border-[#EBDDD5] px-5 py-3.5 text-center">
                    Alamat
                </th>

                <th class="border-b border-[#EBDDD5] px-5 py-3.5 text-center">
                    Aksi
                </th>

            </tr>

        </thead>


        {{-- ================= DATA ================= --}}
        <tbody>

            @forelse($customers as $customer)

            <tr class="text-[#4A3228] transition hover:bg-[#FDF8F5]">


                {{-- ================= NAMA ================= --}}
                <td class="border-b border-r border-[#EBDDD5] px-5 py-4 align-middle">

                    <span class="font-semibold">
                        {{ $customer->nama }}
                    </span>

                </td>


                {{-- ================= NO HP ================= --}}
                <td class="whitespace-nowrap border-b border-r border-[#EBDDD5] px-5 py-4 align-middle">

                    {{ $customer->no_hp }}

                </td>


                {{-- ================= ALAMAT ================= --}}
                <td class="border-b border-r border-[#EBDDD5] px-5 py-4 align-middle">

                    <div class="leading-relaxed text-[#5A4238]">
                        {{ $customer->alamat }}
                    </div>

                </td>


                {{-- ================= AKSI ================= --}}
                <td class="border-b border-[#EBDDD5] px-5 py-4 align-middle">

                    <div class="flex items-center justify-center gap-2">

                        {{-- Buat Pesanan --}}
                        <a href="{{ route('admin.orders.create', ['customer_id' => $customer->id]) }}"
                           class="inline-flex items-center justify-center whitespace-nowrap rounded-full bg-[#F8DDE2] px-4 py-1.5 text-xs font-medium text-[#B84B6A] transition hover:bg-[#F3CBD3]">

                            Buat Pesanan

                        </a>


                        {{-- Ubah --}}
                        <a href="{{ route('admin.customers.edit', $customer->id) }}"
                           class="inline-flex items-center justify-center whitespace-nowrap rounded-full bg-[#EFE0D6] px-4 py-1.5 text-xs font-medium text-[#6B4A3C] transition hover:bg-[#E6D2C4]">

                            Ubah

                        </a>


                        {{-- Hapus --}}
                        <form action="{{ route('admin.customers.destroy', $customer->id) }}"
                              method="POST">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="inline-flex items-center justify-center whitespace-nowrap rounded-full bg-red-50 px-4 py-1.5 text-xs font-medium text-red-700 transition hover:bg-red-100"
                                    onclick="return confirm('Hapus pelanggan?')">

                                Hapus

                            </button>

                        </form>

                    </div>

                </td>

            </tr>


            @empty

            <tr>

                <td colspan="4"
                    class="px-5 py-10 text-center text-[#7A6258]">

                    @if($cari)

                        Pelanggan dengan kata
                        <span class="font-semibold text-[#4A3228]">
                            "{{ $cari }}"
                        </span>
                        tidak ditemukan.

                    @else

                        Belum ada pelanggan.
                        Klik "Tambah Pelanggan" untuk menambahkan.

                    @endif

                </td>

            </tr>

            @endforelse

        </tbody>

    </table>

</div>

    {{-- Nomor halaman --}}
    <div class="mt-4">
        {{ $customers->links() }}
    </div>

</div>


@endsection