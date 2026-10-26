@extends('layouts.owner')

@section('title', 'Laporan Transaksi')

@section('content')

@php
    $jumlahPesanan = $orders->count();

    $totalDibayar = $orders->sum(function ($order) {
        return $order->totalDibayar();
    });

    $totalPiutang = $orders->sum(function ($order) {
        return $order->sisaPembayaran();
    });

    $formatTanggal = function ($tanggal) {
        if (!$tanggal) {
            return '-';
        }

        try {
            return \Carbon\Carbon::parse($tanggal)->format('d-m-Y');
        } catch (\Exception $e) {
            return $tanggal;
        }
    };

    $periodeMulai = request('mulai');
    $periodeSampai = request('sampai');
@endphp

<div class="space-y-6">

    {{-- HEADER --}}
    <div>
        <p class="mt-1 text-sm text-[#7A6258]">
            Pantau nilai pesanan, pembayaran diterima, dan sisa tagihan Nameera Style.
        </p>
    </div>

    {{-- FILTER PERIODE --}}
    <div class="rounded-2xl border border-[#F0E3DC] bg-white p-6 shadow-sm">

        <h2 class="mb-5 text-lg font-semibold text-[#4A3228]">
            Filter Periode Transaksi
        </h2>

        <form
            method="GET"
            action="{{ route('owner.reports.transaksi') }}"
            class="flex flex-wrap items-end gap-4"
        >

            <div>
                <label
                    for="mulai"
                    class="mb-2 block text-sm font-medium text-[#7A6258]"
                >
                    Tanggal Mulai
                </label>

                <input
                    type="date"
                    id="mulai"
                    name="mulai"
                    value="{{ $periodeMulai }}"
                    max="{{ $periodeSampai ?: now()->format('Y-m-d') }}"
                    class="rounded-xl border border-[#EBDDD5] px-4 py-2 text-sm focus:border-[#6B4A3C] focus:outline-none focus:ring-2 focus:ring-[#EBDDD5]"
                >
            </div>

            <div>
                <label
                    for="sampai"
                    class="mb-2 block text-sm font-medium text-[#7A6258]"
                >
                    Tanggal Sampai
                </label>

                <input
                    type="date"
                    id="sampai"
                    name="sampai"
                    value="{{ $periodeSampai }}"
                    min="{{ $periodeMulai }}"
                    max="{{ now()->format('Y-m-d') }}"
                    class="rounded-xl border border-[#EBDDD5] px-4 py-2 text-sm focus:border-[#6B4A3C] focus:outline-none focus:ring-2 focus:ring-[#EBDDD5]"
                >
            </div>

            <button
                type="submit"
                class="rounded-xl bg-[#6B4A3C] px-5 py-2.5 text-sm font-medium text-white transition hover:bg-[#5A3E32]"
            >
                <span class="inline-flex items-center gap-2">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L15 12.414V19l-6 3v-9.586L3.293 6.707A1 1 0 013 6V4z"
                        />
                    </svg>
                    Tampilkan
                </span>
            </button>

            <a
                href="{{ route('owner.reports.transaksi.pdf', array_filter([
                    'mulai' => $periodeMulai,
                    'sampai' => $periodeSampai,
                ], function ($value) {
                    return $value !== null && $value !== '';
                })) }}"
                class="rounded-xl bg-green-700 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-green-800"
            >
                <span class="inline-flex items-center gap-2">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2h-4l-2-2H9L7 4H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                        />
                    </svg>
                    Cetak PDF
                </span>
            </a>

            @if($periodeMulai || $periodeSampai)
                <a
                    href="{{ route('owner.reports.transaksi') }}"
                    class="rounded-xl border border-[#EBDDD5] px-5 py-2.5 text-sm font-medium text-[#6B4A3C] transition hover:bg-[#FBF6F3]"
                >
                    Reset Filter
                </a>
            @endif

        </form>

        @if($periodeMulai && $periodeSampai && $periodeMulai > $periodeSampai)
            <p class="mt-3 text-sm text-red-600">
                Tanggal mulai tidak boleh melebihi tanggal sampai.
                Silakan periksa kembali periode yang dipilih.
            </p>
        @endif

    </div>

    {{-- RINGKASAN --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        {{-- Total Pesanan --}}
        <div class="rounded-2xl border border-[#F0E3DC] bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-sm text-[#7A6258]">
                        Jumlah Pesanan
                    </p>

                    <h2 class="mt-2 text-2xl font-bold text-[#4A3228]">
                        {{ number_format($jumlahPesanan, 0, ',', '.') }}
                    </h2>

                    <p class="mt-1 text-xs text-[#7A6258]">
                        Pesanan tercatat
                    </p>
                </div>

                <div class="rounded-xl bg-[#F8DDE2] p-3 text-[#6B4A3C]">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M3 3h2l2.4 12.4a2 2 0 002 1.6h8.8a2 2 0 002-1.6L22 7H6M10 21h.01M18 21h.01"
                        />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Nilai Pesanan --}}
        <div class="rounded-2xl border border-[#F0E3DC] bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-sm text-[#7A6258]">
                        Total Nilai Pesanan
                    </p>

                    <h2 class="mt-2 break-words text-xl font-bold text-[#4A3228]">
                        Rp {{ number_format($totalTransaksi, 0, ',', '.') }}
                    </h2>

                    <p class="mt-1 text-xs text-[#7A6258]">
                        Setelah diskon
                    </p>
                </div>

                <div class="rounded-xl bg-[#F5EBE5] p-3 text-[#6B4A3C]">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M12 8c-2.2 0-4 1-4 2.5S9.8 13 12 13s4 1 4 2.5S14.2 18 12 18m0-10V6m0 12v-2m9-4a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Pembayaran Diterima --}}
        <div class="rounded-2xl border border-[#F0E3DC] bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-sm text-[#7A6258]">
                        Pembayaran Diterima
                    </p>

                    <h2 class="mt-2 break-words text-xl font-bold text-green-700">
                        Rp {{ number_format($totalDibayar, 0, ',', '.') }}
                    </h2>

                    <p class="mt-1 text-xs text-[#7A6258]">
                        Total pembayaran tercatat
                    </p>
                </div>

                <div class="rounded-xl bg-green-100 p-3 text-green-700">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M5 13l4 4L19 7"
                        />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Sisa Tagihan --}}
        <div class="rounded-2xl border border-[#F0E3DC] bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-sm text-[#7A6258]">
                        Sisa Tagihan
                    </p>

                    <h2 class="mt-2 break-words text-xl font-bold text-red-700">
                        Rp {{ number_format($totalPiutang, 0, ',', '.') }}
                    </h2>

                    <p class="mt-1 text-xs text-[#7A6258]">
                        Belum dibayar
                    </p>
                </div>

                <div class="rounded-xl bg-red-100 p-3 text-red-700">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M12 9v4m0 4h.01M10.3 3.9L1.8 18.5A1.7 1.7 0 003.3 21h17.4a1.7 1.7 0 001.5-2.5L13.7 3.9a2 2 0 00-3.4 0z"
                        />
                    </svg>
                </div>
            </div>
        </div>

    </div>

    {{-- KETERANGAN PERIODE --}}
    <div class="flex flex-col gap-1 rounded-xl bg-[#FBF6F3] px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-[#6B4A3C]">
            <span class="font-semibold">Periode laporan:</span>

            @if($periodeMulai || $periodeSampai)
                {{ $periodeMulai ? $formatTanggal($periodeMulai) : 'Awal data' }}
                s/d
                {{ $periodeSampai ? $formatTanggal($periodeSampai) : 'Data terbaru' }}
            @else
                Semua Data
            @endif
        </p>

        <p class="text-xs text-[#7A6258]">
            Data berdasarkan tanggal pemesanan
        </p>
    </div>



    {{-- DETAIL TRANSAKSI --}}

    <div class="overflow-hidden rounded-2xl border border-[#E8D8CE] bg-white shadow-sm">

    {{-- Header tabel --}}
    <div class="flex flex-col gap-3 border-b border-[#E8D8CE] bg-[#FFFCFA] px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
        <div>
            <h2 class="text-lg font-bold text-[#4A3228]">
                Detail Transaksi
            </h2>
            <p class="mt-1 text-sm text-[#8A7469]">
                Rincian pesanan dan pembayaran pelanggan.
            </p>
        </div>

        <span class="inline-flex w-fit items-center rounded-full bg-[#F8DDE2] px-4 py-2 text-sm font-semibold text-[#6B4A3C]">
            {{ number_format($orders->count(), 0, ',', '.') }} Pesanan
        </span>
    </div>

    <div class="overflow-x-auto rounded-b-2xl">

        <table class="w-full min-w-[1150px] border-collapse text-sm">

            {{-- JUDUL KOLOM --}}
            <thead>
                <tr class="bg-[#F5EBE5] text-xs font-bold uppercase tracking-wide text-[#65483B]">

                    <th class="border border-[#D8C5B9] px-4 py-4 text-center">
                        No.
                    </th>

                    <th class="border border-[#D8C5B9] px-4 py-4 text-center">
                        Kode Pesanan
                    </th>

                    <th class="border border-[#D8C5B9] px-4 py-4 text-center">
                        Pelanggan
                    </th>

                    <th class="border border-[#D8C5B9] px-4 py-4 text-center">
                        Tanggal
                    </th>

                    <th class="border border-[#D8C5B9] px-4 py-4 text-center">
                        Total Pesanan
                    </th>

                    <th class="border border-[#D8C5B9] px-4 py-4 text-center">
                        Dibayar
                    </th>

                    <th class="border border-[#D8C5B9] px-4 py-4 text-center">
                        Sisa Tagihan
                    </th>

                    <th class="border border-[#D8C5B9] px-4 py-4 text-center">
                        Pembayaran
                    </th>

                    <th class="border border-[#D8C5B9] px-4 py-4 text-center">
                        Status Pesanan
                    </th>

                </tr>
            </thead>

            {{-- ISI TABEL --}}
            <tbody>

                @forelse($orders as $index => $order)

                    @php
                        $statusPembayaran = $order->statusPembayaran();
                        $statusLunas = $statusPembayaran === 'Lunas';
                    @endphp

                    <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-[#FFFCFA]' }} hover:bg-[#FFF1F2]">

                        <td class="border border-[#E3D5CC] px-4 py-4 text-left text-[#5A4238]">
                            {{ $index + 1 }}
                        </td>

                        <td class="border border-[#E3D5CC] px-4 py-4 text-left font-semibold text-[#4A3228]">
                            {{ $order->kode_pesanan ?? '-' }}
                        </td>

                        <td class="border border-[#E3D5CC] px-4 py-4 text-left text-[#5A4238]">
                            {{ $order->customer->nama ?? '-' }}
                        </td>

                        <td class="border border-[#E3D5CC] px-4 py-4 text-left whitespace-nowrap text-[#5A4238]">
                            {{ $order->tanggal_pesan
                                ? \Carbon\Carbon::parse($order->tanggal_pesan)->format('d-m-Y')
                                : '-' }}
                        </td>

                        <td class="border border-[#E3D5CC] px-4 py-4 text-left whitespace-nowrap font-medium text-[#4A3228]">
                            Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                        </td>

                        <td class="border border-[#E3D5CC] px-4 py-4 text-left whitespace-nowrap font-medium text-green-700">
                            Rp {{ number_format($order->totalDibayar(), 0, ',', '.') }}
                        </td>

                        <td class="border border-[#E3D5CC] px-4 py-4 text-left whitespace-nowrap font-medium {{ $order->sisaPembayaran() > 0 ? 'text-red-700' : 'text-[#5A4238]' }}">
                            Rp {{ number_format($order->sisaPembayaran(), 0, ',', '.') }}
                        </td>

                        <td class="border border-[#E3D5CC] px-4 py-4 text-left whitespace-nowrap">
                            @if($statusLunas)
                                <span class="inline-flex rounded-full bg-green-100 px-3 py-1.5 text-xs font-semibold text-green-800">
                                    Lunas
                                </span>
                            @else
                                <span class="inline-flex rounded-full bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-800">
                                    Belum Lunas
                                </span>
                            @endif
                        </td>

                        <td class="border border-[#E3D5CC] px-4 py-4 text-left">
                            <span class="inline-flex rounded-lg bg-[#F5EBE5] px-3 py-1.5 text-xs font-medium text-[#65483B]">
                                {{ $order->status_pesanan ?? '-' }}
                            </span>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td
                            colspan="9"
                            class="border border-[#E3D5CC] px-4 py-12 text-center text-[#7A6258]"
                        >
                            Belum ada transaksi pada periode ini.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- Footer tabel --}}
    @if($orders->count() > 0)
        <div class="flex flex-col gap-1 border-t border-[#E8D8CE] bg-[#FFFCFA] px-5 py-4 text-sm sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <p class="text-[#7A6258]">
                Menampilkan
                <span class="font-semibold text-[#4A3228]">
                    {{ number_format($orders->count(), 0, ',', '.') }}
                </span>
                pesanan.
            </p>

            <p class="text-xs text-[#8A7469]">
                Nominal dalam Rupiah (IDR)
            </p>
        </div>
    @endif

</div>


</div>

@endsection
