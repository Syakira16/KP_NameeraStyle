{{--
    HALAMAN DATA PENGIRIMAN

    Susunan halaman (dari atas ke bawah):
      1. Kartu ringkasan : total pengiriman, sudah dikirim, belum dikirim
      2. Tabel           : daftar pengiriman beserta ekspedisi, nomor resi, dan tanggal kirim

    Data yang dikirim dari controller (nama jangan diubah):
      $totalPengiriman, $sudahDikirim, $belumDikirim -> angka ringkasan
      $shipments                                     -> daftar pengiriman di tabel

    Arti class Tailwind yang sering muncul:
      mt-5 / mb-4     = jarak di atas / di bawah
      p-5 / px-4      = jarak di dalam kotak
      rounded-2xl     = sudut membulat
      grid md:grid-cols-3 = kartu menumpuk di HP, 3 kolom di layar lebar
      overflow-x-auto = tabel bisa digeser ke samping kalau layar sempit
--}}

@extends('layouts.admin')


@section('title')
Pengiriman
@endsection


@section('content')


{{-- ============ 1. KARTU RINGKASAN ============ --}}
<div class="grid gap-5 md:grid-cols-3">


    <div class="rounded-2xl border border-[#F0E3DC] bg-white p-5">
        <p class="font-medium text-[#7A6258]">Total Pengiriman</p>
        <h3 class="mt-3 text-3xl font-semibold text-[#4A3228]">{{ $totalPengiriman }}</h3>
    </div>


    <div class="rounded-2xl border border-[#F0E3DC] bg-white p-5">
        <p class="font-medium text-[#7A6258]">Sudah Dikirim</p>
        <h3 class="mt-3 text-3xl font-semibold text-green-700">{{ $sudahDikirim }}</h3>
    </div>


    <div class="rounded-2xl border border-[#F0E3DC] bg-white p-5">
        <p class="font-medium text-[#7A6258]">Belum Dikirim</p>
        <h3 class="mt-3 text-3xl font-semibold text-amber-700">{{ $belumDikirim }}</h3>
    </div>


</div>


{{-- ============ 2. TABEL PENGIRIMAN ============ --}}
<div class="mt-5 rounded-2xl border border-[#F0E3DC] bg-white p-6">


    <div class="mb-4 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-[#4A3228]">Data Pengiriman</h3>
        <span class="text-sm text-[#7A6258]">{{ count($shipments) }} pengiriman</span>
    </div>


    <div class="overflow-x-auto">

        <div class="overflow-x-auto">

    <table class="w-full min-w-[950px] overflow-hidden rounded-2xl border border-[#EBDDD5] text-left">

        {{-- ================= HEADER ================= --}}
        <thead>
            <tr class="bg-[#FBF6F3] text-xs uppercase tracking-wide text-[#7A6258]">

                <th class="border-b border-r border-[#EBDDD5] px-4 py-3 text-center">
                    Kode Pesanan
                </th>

                <th class="border-b border-r border-[#EBDDD5] px-4 py-3 text-center">
                    Pelanggan
                </th>

                <th class="border-b border-r border-[#EBDDD5] px-4 py-3 text-center">
                    Ekspedisi
                </th>

                <th class="border-b border-r border-[#EBDDD5] px-4 py-3 text-center">
                    Nomor Resi
                </th>

                <th class="border-b border-r border-[#EBDDD5] px-4 py-3 text-center">
                    Tanggal Kirim
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

            @forelse($shipments as $shipment)

            <tr class="transition hover:bg-[#FDF8F5]">

                {{-- Kode Pesanan --}}
                <td class="whitespace-nowrap border-b border-r border-[#EBDDD5] px-4 py-4 align-middle">

                    <span class="font-semibold text-[#4A3228]">
                        {{ $shipment->order->kode_pesanan }}
                    </span>

                </td>


                {{-- Pelanggan --}}
                <td class="border-b border-r border-[#EBDDD5] px-4 py-4 align-middle text-[#4A3228]">

                    {{ $shipment->order->customer->nama }}

                </td>


                {{-- Ekspedisi --}}
                <td class="border-b border-r border-[#EBDDD5] px-4 py-4 align-middle text-[#5A4238]">

                    {{ $shipment->ekspedisi ?? '-' }}

                </td>


                {{-- Nomor Resi --}}
                <td class="border-b border-r border-[#EBDDD5] px-4 py-4 align-middle">

                    @if($shipment->nomor_resi)

                        <span class="font-medium text-[#4A3228]">
                            {{ $shipment->nomor_resi }}
                        </span>

                    @else

                        <span class="text-[#A8958B]">
                            Belum tersedia
                        </span>

                    @endif

                </td>


                {{-- Tanggal Kirim --}}
                <td class="whitespace-nowrap border-b border-r border-[#EBDDD5] px-4 py-4 align-middle text-[#5A4238]">

                    {{ $shipment->tanggal_kirim ?? '-' }}

                </td>


                {{-- Status --}}
                <td class="border-b border-r border-[#EBDDD5] px-4 py-4 align-middle">

                    @if($shipment->nomor_resi)

                        <span class="whitespace-nowrap rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                            Dikirim
                        </span>

                    @else

                        <span class="whitespace-nowrap rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-700">
                            Belum Dikirim
                        </span>

                    @endif

                </td>


                {{-- Aksi --}}
                <td class="border-b border-[#EBDDD5] px-4 py-4 align-middle">

                    <div class="flex items-center justify-center">

                        <a href="{{ route('admin.orders.show', $shipment->order->id) }}"
                           class="whitespace-nowrap rounded-full bg-[#EFE0D6] px-4 py-2 text-xs font-medium text-[#6B4A3C] transition hover:bg-[#E6D2C4]">
                            Detail
                        </a>

                    </div>

                </td>

            </tr>


            @empty

            <tr>

                <td colspan="7"
                    class="px-4 py-12 text-center text-[#7A6258]">

                    Belum ada data pengiriman.

                </td>

            </tr>

            @endforelse

        </tbody>

    </table>

</div>

    </div>


</div>


@endsection