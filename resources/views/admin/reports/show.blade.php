{{--
    HALAMAN DETAIL LAPORAN PESANAN

    Susunan halaman (dari atas ke bawah):
      1. Kepala     : kode pesanan, tanggal, status pesanan, status pembayaran
      2. Pelanggan  : nama, no HP, alamat
      3. Produk     : tabel produk yang dipesan
      4. Keuangan   : subtotal, diskon, total, sudah dibayar, sisa

    Arti class Tailwind yang sering muncul:
      mt-5 / mb-4     = jarak di atas / di bawah
      p-6 / px-4      = jarak di dalam kotak
      rounded-2xl     = sudut membulat
      flex            = isi kotak berjajar ke samping
      justify-between = isi dipisah ke kiri dan kanan
      space-y-3       = jarak antar isi di dalam kotak
--}}

@extends('layouts.admin')


@section('title')
Detail Laporan Pesanan
@endsection


@section('content')


{{-- ============ 1. KEPALA PESANAN ============ --}}
<div class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-[#F0E3DC] bg-white p-6">

    {{-- Kiri: kode dan tanggal pesanan --}}
    <div>
        <p class="text-xs uppercase tracking-widest text-[#7A6258]">Kode Pesanan</p>
        <h2 class="text-2xl font-semibold text-[#4A3228]">{{ $order->kode_pesanan }}</h2>
        <p class="mt-1 text-[#7A6258]">Dipesan pada {{ $order->tanggal_pesan }}</p>
    </div>

    {{-- Kanan: dua label status --}}
    <div class="flex flex-wrap items-center gap-2">

        <span class="rounded-full bg-[#F8DDE2] px-4 py-1.5 text-sm font-medium text-[#8A3E56]">
            {{ $order->status_pesanan }}
        </span>

        {{-- Hijau kalau lunas, merah kalau belum --}}
        @if($order->statusPembayaran() == 'Lunas')

            <span class="rounded-full bg-green-100 px-4 py-1.5 text-sm font-medium text-green-700">
                Lunas
            </span>

        @else

            <span class="rounded-full bg-red-100 px-4 py-1.5 text-sm font-medium text-red-700">
                Belum Lunas
            </span>

        @endif

    </div>

</div>


{{-- ============ 2. INFORMASI PELANGGAN ============ --}}
<div class="mt-5 rounded-2xl border border-[#F0E3DC] bg-white p-6">

    <h3 class="mb-4 text-lg font-semibold text-[#4A3228]">Informasi Pelanggan</h3>

    {{-- Nama pelanggan dengan huruf awal di lingkaran pink --}}
    <div class="mb-4 flex items-center gap-3">

        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#F8DDE2] font-semibold text-[#8A3E56]">
            {{ strtoupper(substr($order->customer->nama, 0, 1)) }}
        </span>

        <span class="text-base font-semibold text-[#4A3228]">{{ $order->customer->nama }}</span>

    </div>

    <div class="space-y-3">

        <div class="flex justify-between gap-4">
            <span class="text-[#7A6258]">No HP</span>
            <span class="font-medium">{{ $order->customer->no_hp }}</span>
        </div>

        <div class="flex justify-between gap-4">
            <span class="text-[#7A6258]">Alamat</span>
            <span class="text-right font-medium">{{ $order->customer->alamat }}</span>
        </div>

    </div>

</div>


{{-- ============ 3. DETAIL PRODUK ============ --}}
<div class="mt-5 rounded-2xl border border-[#F0E3DC] bg-white p-6">

    <h3 class="mb-4 text-lg font-semibold text-[#4A3228]">Detail Produk</h3>

    <div class="overflow-x-auto">

        <table class="w-full min-w-[600px] text-left">

            <thead>
                <tr class="bg-[#FBF6F3] text-xs uppercase tracking-wide text-[#7A6258]">
                    <th class="rounded-l-xl px-4 py-3">Produk</th>
                    <th class="px-4 py-3">Warna</th>
                    <th class="px-4 py-3">Ukuran</th>
                    <th class="px-4 py-3">Jumlah</th>
                    <th class="px-4 py-3">Harga</th>
                    <th class="rounded-r-xl px-4 py-3 text-right">Subtotal</th>
                </tr>
            </thead>

            <tbody>

                @foreach($order->orderDetails as $detail)

                <tr class="border-b border-[#F5EBE5]">

                    <td class="px-4 py-3.5 font-semibold text-[#4A3228]">
                        {{ $detail->product->nama_produk }}
                    </td>

                    <td class="px-4 py-3.5">
                        {{ $detail->product->warna }}
                    </td>

                    <td class="px-4 py-3.5">
                        <span class="rounded-full bg-[#F5EBE5] px-3 py-1 text-xs font-medium text-[#5A4238]">
                            {{ $detail->product->ukuran }}
                        </span>
                    </td>

                    <td class="px-4 py-3.5">
                        {{ $detail->jumlah }}
                    </td>

                    <td class="px-4 py-3.5">
                        Rp {{ number_format($detail->harga, 0, ',', '.') }}
                    </td>

                    {{-- Subtotal = jumlah x harga --}}
                    <td class="px-4 py-3.5 text-right font-semibold">
                        Rp {{ number_format($detail->jumlah * $detail->harga, 0, ',', '.') }}
                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>


{{-- ============ 4. KEUANGAN ============ --}}
<div class="mt-5 rounded-2xl border border-[#F0E3DC] bg-white p-6">

    <h3 class="mb-4 text-lg font-semibold text-[#4A3228]">Keuangan</h3>

    <div class="space-y-3">

        <div class="flex justify-between gap-4">
            <span class="text-[#7A6258]">Subtotal</span>
            <span class="font-medium">Rp {{ number_format($order->total_harga + $order->diskon, 0, ',', '.') }}</span>
        </div>

        <div class="flex justify-between gap-4">
            <span class="text-[#7A6258]">Diskon</span>
            <span class="font-medium">Rp {{ number_format($order->diskon, 0, ',', '.') }}</span>
        </div>

        <div class="flex justify-between gap-4 border-t border-[#F5EBE5] pt-3">
            <span class="font-semibold text-[#4A3228]">Total</span>
            <span class="font-semibold text-[#4A3228]">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
        </div>

        <div class="flex justify-between gap-4">
            <span class="text-[#7A6258]">Sudah Dibayar</span>
            <span class="font-medium text-green-700">Rp {{ number_format($order->totalDibayar(), 0, ',', '.') }}</span>
        </div>

        <div class="flex justify-between gap-4">
            <span class="text-[#7A6258]">Sisa Pembayaran</span>
            <span class="font-semibold text-red-700">Rp {{ number_format($order->sisaPembayaran(), 0, ',', '.') }}</span>
        </div>

    </div>

</div>


{{-- ============ TOMBOL KEMBALI ============ --}}
<div class="mt-5">

    <a href="{{ route('admin.reports.index') }}"
       class="inline-block rounded-full border-2 border-[#6B4A3C] px-6 py-2 font-medium text-[#6B4A3C] hover:bg-white">
        Kembali
    </a>

</div>


@endsection