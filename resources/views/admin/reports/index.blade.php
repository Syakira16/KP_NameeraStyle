{{--
    HALAMAN LAPORAN PESANAN

    Susunan halaman (dari atas ke bawah):
      1. Kartu ringkasan : jumlah pesanan, nilai pesanan, uang masuk, piutang
      2. Daftar pesanan  : pilihan status (saring), tombol Unduh PDF, dan tabel laporan

    Tabel dibuat ringkas (6 kolom, sebelumnya 9) supaya mudah dibaca:
      - Kode pesanan dan tanggal digabung dalam satu kolom
      - Total, dibayar, dan sisa digabung dalam satu kolom "Pembayaran"
      - Status pembayaran dan status pesanan digabung dalam satu kolom "Status"

    Data yang dikirim dari controller (nama jangan diubah):
      $jumlahPesanan, $totalPesanan, $totalPembayaran, $totalPiutang  -> angka ringkasan
      $status  -> status yang sedang dipilih (kosong kalau menampilkan semua)
      $orders  -> daftar pesanan yang ditampilkan di tabel

    Arti class Tailwind yang sering muncul:
      mt-5 / mb-4       = jarak di atas / di bawah
      p-6 / px-4        = jarak di dalam kotak
      rounded-2xl       = sudut membulat
      grid sm:grid-cols-2 xl:grid-cols-4 = kartu 1 kolom di HP, 2 kolom di tablet, 4 kolom di layar lebar
      flex justify-between = isi berjajar, satu di kiri dan satu di kanan
--}}

@extends('layouts.admin')


@section('title')
Laporan Pesanan
@endsection


@section('content')


{{-- ============ 1. KARTU RINGKASAN ============ --}}
<div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">


    <div class="rounded-2xl border border-[#F0E3DC] bg-white p-5">
        <p class="font-medium text-[#7A6258]">Jumlah Pesanan</p>
        <h3 class="mt-3 text-3xl font-semibold text-[#4A3228]">{{ $jumlahPesanan }}</h3>
    </div>


    <div class="rounded-2xl border border-[#F0E3DC] bg-white p-5">
        <p class="font-medium text-[#7A6258]">Total Nilai Pesanan</p>
        <h3 class="mt-3 text-2xl font-semibold text-[#4A3228]">
            Rp {{ number_format($totalPesanan, 0, ',', '.') }}
        </h3>
    </div>


    <div class="rounded-2xl border border-[#F0E3DC] bg-white p-5">
        <p class="font-medium text-[#7A6258]">Uang Masuk</p>
        <h3 class="mt-3 text-2xl font-semibold text-green-700">
            Rp {{ number_format($totalPembayaran, 0, ',', '.') }}
        </h3>
    </div>


    <div class="rounded-2xl border border-[#F0E3DC] bg-white p-5">
        <p class="font-medium text-[#7A6258]">Piutang</p>
        <h3 class="mt-3 text-2xl font-semibold text-red-700">
            Rp {{ number_format($totalPiutang, 0, ',', '.') }}
        </h3>
    </div>


</div>


{{-- ============ 2. DAFTAR PESANAN ============ --}}
<div class="mt-5 rounded-2xl border border-[#F0E3DC] bg-white p-6">


    {{-- Baris atas: judul (kiri), pilihan status dan tombol Unduh PDF (kanan) --}}
    <div class="mb-5 flex flex-wrap items-end justify-between gap-4">

        <div>
            <h3 class="text-lg font-semibold text-[#4A3228]">Daftar Pesanan</h3>
            <p class="text-xs text-[#7A6258]">{{ count($orders) }} pesanan ditampilkan</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">

            {{--
                Pilihan status.
                method="GET" : status yang dipilih ikut di alamat halaman, contoh: /admin/reports?status=Selesai
                name="status": nama data yang dibaca controller
            --}}
            <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-wrap items-center gap-2">

                <select name="status"
                        class="min-w-[180px] rounded-xl border border-[#EBDDD5] bg-white px-4 py-2.5 focus:border-[#D4708A] focus:outline-none focus:ring-2 focus:ring-[#F8DDE2]">

                    <option value="">Semua Status</option>

                    @foreach(['Menunggu Produksi', 'Ready', 'Packing', 'Dikirim', 'Selesai'] as $namaStatus)

                        <option value="{{ $namaStatus }}" {{ $status == $namaStatus ? 'selected' : '' }}>
                            {{ $namaStatus }}
                        </option>

                    @endforeach

                </select>

                <button type="submit"
                        class="rounded-full bg-[#6B4A3C] px-5 py-2.5 font-medium text-white hover:bg-[#5A3E32]">
                    Tampilkan
                </button>

                {{-- Hanya muncul kalau sedang memilih status --}}
                @if($status)

                    <a href="{{ route('admin.reports.index') }}"
                       class="rounded-full border-2 border-[#6B4A3C] px-5 py-2 font-medium text-[#6B4A3C] hover:bg-[#FBF6F3]">
                        Semua
                    </a>

                @endif

            </form>

            <a href="{{ route('admin.reports.pdf') }}"
               class="rounded-full bg-[#F8DDE2] px-5 py-2.5 font-medium text-[#8A3E56] hover:bg-[#F3C9D2]">
                Unduh PDF
            </a>

        </div>

    </div>


    {{-- Tabel laporan (6 kolom) --}}
    <div class="overflow-x-auto">

        <table class="w-full min-w-[850px] overflow-hidden rounded-2xl border border-[#EBDDD5] text-left">

            {{-- ================= HEADER ================= --}}
            <thead>
                <tr class="bg-[#FBF6F3] text-xs uppercase tracking-wide text-[#7A6258]">

                    <th class="border-b border-r border-[#EBDDD5] px-4 py-3 text-center">
                        Pesanan
                    </th>

                    <th class="border-b border-r border-[#EBDDD5] px-4 py-3 text-center">
                        Pelanggan
                    </th>

                    <th class="border-b border-r border-[#EBDDD5] px-4 py-3 text-center">
                        Total
                    </th>

                    <th class="border-b border-r border-[#EBDDD5] px-4 py-3 text-center">
                        Pembayaran
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

                    {{-- Pesanan --}}
                    <td class="border-b border-r border-[#EBDDD5] px-4 py-4 align-middle">

                        <p class="font-semibold text-[#4A3228]">
                            {{ $order->kode_pesanan }}
                        </p>

                        <p class="mt-0.5 text-xs text-[#7A6258]">
                            {{ \Carbon\Carbon::parse($order->tanggal_pesan)->format('d M Y') }}
                        </p>

                    </td>


                    {{-- Pelanggan --}}
                    <td class="border-b border-r border-[#EBDDD5] px-4 py-4 align-middle text-[#4A3228]">
                        {{ $order->customer->nama }}
                    </td>


                    {{-- Total --}}
                    <td class="whitespace-nowrap border-b border-r border-[#EBDDD5] px-4 py-4 align-middle font-semibold text-[#4A3228]">
                        Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                    </td>


                    {{-- Pembayaran --}}
                    <td class="border-b border-r border-[#EBDDD5] px-4 py-4 align-middle">

                        <div class="space-y-1">

                            <p class="whitespace-nowrap text-sm text-green-700">
                                <span class="font-medium">Dibayar:</span>
                                Rp {{ number_format($order->totalDibayar(), 0, ',', '.') }}
                            </p>

                            <p class="whitespace-nowrap text-sm text-red-700">
                                <span class="font-medium">Sisa:</span>
                                Rp {{ number_format($order->sisaPembayaran(), 0, ',', '.') }}
                            </p>

                        </div>

                    </td>


                    {{-- Status --}}
                    <td class="border-b border-r border-[#EBDDD5] px-4 py-4 align-middle">

                        <div class="flex flex-col items-start gap-1.5">

                            {{-- Status Pesanan --}}
                            @if($order->status_pesanan == 'Selesai')

                                <span class="whitespace-nowrap rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                    {{ $order->status_pesanan }}
                                </span>

                            @elseif($order->status_pesanan == 'Dikirim')

                                <span class="whitespace-nowrap rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700">
                                    {{ $order->status_pesanan }}
                                </span>

                            @elseif($order->status_pesanan == 'Menunggu Produksi')

                                <span class="whitespace-nowrap rounded-full bg-pink-100 px-3 py-1 text-xs font-medium text-pink-700">
                                    {{ $order->status_pesanan }}
                                </span>

                            @else

                                <span class="whitespace-nowrap rounded-full bg-stone-100 px-3 py-1 text-xs font-medium text-stone-700">
                                    {{ $order->status_pesanan }}
                                </span>

                            @endif


                            {{-- Status Pembayaran --}}
                            @if($order->statusPembayaran() == 'Lunas')

                                <span class="whitespace-nowrap text-xs font-medium text-green-700">
                                    Lunas
                                </span>

                            @else

                                <span class="whitespace-nowrap text-xs font-medium text-red-700">
                                    Belum Lunas
                                </span>

                            @endif

                        </div>

                    </td>


                    {{-- Aksi --}}
                    <td class="border-b border-[#EBDDD5] px-4 py-4 align-middle">

                        <div class="flex items-center justify-center">

                            <a href="{{ route('admin.reports.show', $order->id) }}"
                            class="whitespace-nowrap rounded-full bg-[#EFE0D6] px-4 py-2 text-xs font-medium text-[#6B4A3C] transition hover:bg-[#E6D2C4]">
                                Detail
                            </a>

                        </div>

                    </td>

                </tr>


                @empty

                <tr>

                    <td colspan="6"
                        class="px-4 py-12 text-center text-[#7A6258]">

                        Belum ada data laporan.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>


</div>


@endsection