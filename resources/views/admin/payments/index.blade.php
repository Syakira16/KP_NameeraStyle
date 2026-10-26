{{--
    HALAMAN DATA PEMBAYARAN

    Susunan halaman (dari atas ke bawah):
      1. Pesan       : pesan berhasil / gagal (hanya tampil kalau ada)
      2. Ringkasan   : total transaksi, total sudah dibayar, total piutang
      3. Daftar      : kolom cari, pilihan status pembayaran, dan tabel pesanan

    Data yang dikirim dari controller (nama jangan diubah):
      $totalTransaksi, $totalDibayar, $totalPiutang -> angka ringkasan
      $orders                                       -> daftar pesanan di tabel
      request('search') dan request('status')       -> kata dan status yang sedang dicari

    Arti class Tailwind yang sering muncul:
      mt-5 / mb-4     = jarak di atas / di bawah
      p-5 / px-4      = jarak di dalam kotak
      rounded-2xl     = sudut membulat
      grid md:grid-cols-3 = kartu menumpuk di HP, 3 kolom di layar lebar
      flex-wrap       = isi turun ke baris baru kalau layar sempit
      overflow-x-auto = tabel bisa digeser ke samping kalau layar sempit
--}}

@extends('layouts.admin')


@section('title')
Pembayaran
@endsection


@section('content')


{{-- ============ 1. PESAN BERHASIL / GAGAL ============ --}}
@if(session('success'))

<div class="mb-5 rounded-2xl border border-green-200 bg-green-50 px-5 py-3 text-green-800">
    {{ session('success') }}
</div>

@endif


@if(session('error'))

<div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-5 py-3 text-red-800">
    {{ session('error') }}
</div>

@endif


{{-- ============ 2. KARTU RINGKASAN ============ --}}
<div class="grid gap-5 md:grid-cols-3">


    <div class="rounded-2xl border border-[#F0E3DC] bg-white p-5">
        <p class="font-medium text-[#7A6258]">Total Transaksi</p>
        <h3 class="mt-3 text-2xl font-semibold text-[#4A3228]">
            Rp {{ number_format($totalTransaksi, 0, ',', '.') }}
        </h3>
    </div>


    <div class="rounded-2xl border border-[#F0E3DC] bg-white p-5">
        <p class="font-medium text-[#7A6258]">Total Sudah Dibayar</p>
        <h3 class="mt-3 text-2xl font-semibold text-green-700">
            Rp {{ number_format($totalDibayar, 0, ',', '.') }}
        </h3>
    </div>


    <div class="rounded-2xl border border-[#F0E3DC] bg-white p-5">
        <p class="font-medium text-[#7A6258]">Total Piutang</p>
        <h3 class="mt-3 text-2xl font-semibold text-red-700">
            Rp {{ number_format($totalPiutang, 0, ',', '.') }}
        </h3>
    </div>


</div>


{{-- ============ 3. DAFTAR PEMBAYARAN ============ --}}
<div class="mt-5 rounded-2xl border border-[#F0E3DC] bg-white p-6">


    <div class="mb-4 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-[#4A3228]">Rekap Pembayaran</h3>
        <span class="text-sm text-[#7A6258]">{{ count($orders) }} pesanan</span>
    </div>


    {{--
        Form pencarian dan pilihan status.
        method="GET" : kata dan status ikut di alamat halaman, contoh: /admin/payments?search=siti&status=Lunas
        name="search" dan name="status" : nama data yang dibaca controller
        value : mengisi kembali kolom dengan kata yang tadi dicari
    --}}
    <form action="{{ route('admin.payments.index') }}" method="GET" class="mb-5 flex flex-wrap items-center gap-3">

        {{-- Semua kolom di baris ini setinggi sama (h-11) dan sama-sama bulat (rounded-full) supaya rapi --}}
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="Cari kode pesanan atau nama pelanggan"
               class="h-11 min-w-[240px] flex-1 rounded-full border border-[#EBDDD5] px-5 placeholder:text-[#B5A39A] focus:border-[#D4708A] focus:outline-none focus:ring-2 focus:ring-[#F8DDE2]">

        <select name="status"
                class="h-11 w-48 rounded-full border border-[#EBDDD5] bg-white pl-5 pr-10 focus:border-[#D4708A] focus:outline-none focus:ring-2 focus:ring-[#F8DDE2]">

            <option value="">Semua Status</option>

            <option value="Lunas" {{ request('status') == 'Lunas' ? 'selected' : '' }}>Lunas</option>

            <option value="Belum Lunas" {{ request('status') == 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas</option>

        </select>

        <button type="submit"
                class="h-11 rounded-full bg-[#6B4A3C] px-8 font-medium text-white hover:bg-[#5A3E32]">
            Cari
        </button>

        {{-- Tombol ini hanya muncul kalau sedang mencari atau memilih status --}}
        @if(request('search') || request('status'))

            <a href="{{ route('admin.payments.index') }}"
               class="flex h-11 items-center rounded-full border-2 border-[#6B4A3C] px-6 font-medium text-[#6B4A3C] hover:bg-[#FBF6F3]">
                Tampilkan Semua
            </a>

        @endif

    </form>


    {{-- Tabel pembayaran --}}
    <div class="overflow-x-auto">

        <table class="w-full min-w-[1100px] overflow-hidden rounded-2xl border border-[#EBDDD5] text-left">

            {{-- ================= HEADER ================= --}}
            <thead>
                <tr class="bg-[#FBF6F3] text-xs uppercase tracking-wide text-[#7A6258]">

                    <th class="border-b border-r border-[#EBDDD5] px-4 py-3 text-center">
                        No
                    </th>

                    <th class="border-b border-r border-[#EBDDD5] px-4 py-3 text-center">
                        Kode Pesanan
                    </th>

                    <th class="border-b border-r border-[#EBDDD5] px-4 py-3 text-center">
                        Pelanggan
                    </th>

                    <th class="border-b border-r border-[#EBDDD5] px-4 py-3 text-center whitespace-nowrap">
                        Total Pesanan
                    </th>

                    <th class="border-b border-r border-[#EBDDD5] px-4 py-3 text-center">
                        Dibayar
                    </th>

                    <th class="border-b border-r border-[#EBDDD5] px-4 py-3 text-center">
                        Sisa
                    </th>

                    <th class="border-b border-r border-[#EBDDD5] px-4 py-3 text-center">
                        Status
                    </th>

                    <th class="border-b border-[#EBDDD5] px-4 py-3 text-center">
                        Aksi
                    </th>

                </tr>
            </thead>


            {{-- ================= ISI ================= --}}
            <tbody class="bg-white">

                @forelse($orders as $order)

                <tr class="transition hover:bg-[#FDF8F5]">

                    {{-- No --}}
                    <td class="border-b border-r border-[#EBDDD5] px-4 py-4 align-middle text-center text-[#7A6258]">
                        {{ $loop->iteration }}
                    </td>


                    {{-- Kode Pesanan --}}
                    <td class="whitespace-nowrap border-b border-r border-[#EBDDD5] px-4 py-4 align-middle">
                        <span class="font-semibold text-[#4A3228]">
                            {{ $order->kode_pesanan }}
                        </span>
                    </td>


                    {{-- Pelanggan --}}
                    <td class="border-b border-r border-[#EBDDD5] px-4 py-4 align-middle">
                        {{ $order->customer->nama }}
                    </td>


                    {{-- Total Pesanan --}}
                    <td class="whitespace-nowrap border-b border-r border-[#EBDDD5] px-4 py-4 align-middle font-medium text-[#4A3228]">
                        Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                    </td>


                    {{-- Dibayar --}}
                    <td class="whitespace-nowrap border-b border-r border-[#EBDDD5] px-4 py-4 align-middle font-medium text-green-700">
                        Rp {{ number_format($order->totalDibayar(), 0, ',', '.') }}
                    </td>


                    {{-- Sisa --}}
                    <td class="whitespace-nowrap border-b border-r border-[#EBDDD5] px-4 py-4 align-middle font-medium text-red-700">
                        Rp {{ number_format($order->sisaPembayaran(), 0, ',', '.') }}
                    </td>


                    {{-- Status --}}
                    <td class="border-b border-r border-[#EBDDD5] px-4 py-4 align-middle">

                        <div class="flex items-center justify-center">

                            @if($order->statusPembayaran() == 'Lunas')

                                <span class="whitespace-nowrap rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                    Lunas
                                </span>

                            @else

                                <span class="whitespace-nowrap rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">
                                    Belum Lunas
                                </span>

                            @endif

                        </div>

                    </td>


                    {{-- Aksi --}}
                    <td class="border-b border-[#EBDDD5] px-4 py-4 align-middle">

                        <div class="flex items-center justify-center gap-2">

                            {{-- Tambah Pembayaran --}}
                            @if($order->statusPembayaran() != 'Lunas')

                                <a href="{{ route('admin.payments.create', $order->id) }}"
                                class="whitespace-nowrap rounded-full bg-[#F8DDE2] px-4 py-2 text-xs font-medium text-[#8A3E56] transition hover:bg-[#F3C9D2]">
                                    Tambah
                                </a>

                            @endif


                            {{-- Detail --}}
                            <a href="{{ route('admin.orders.show', $order->id) }}"
                            class="whitespace-nowrap rounded-full bg-[#EFE0D6] px-4 py-2 text-xs font-medium text-[#6B4A3C] transition hover:bg-[#E6D2C4]">
                                Detail
                            </a>

                        </div>

                    </td>

                </tr>


                @empty

                <tr>

                    <td colspan="8"
                        class="px-4 py-12 text-center text-[#7A6258]">

                        Belum ada data pembayaran

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>


</div>


@endsection