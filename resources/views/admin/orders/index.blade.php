@extends('layouts.admin')


@section('title')
Data Pesanan
@endsection


@section('content')


{{-- ============ TOMBOL TAMBAH PESANAN ============ --}}
<a href="{{ route('admin.orders.create') }}"
   class="inline-flex items-center gap-2 rounded-full bg-[#6B4A3C] px-6 py-2.5 font-medium text-white hover:bg-[#5A3E32]">

    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>

    Tambah Pesanan

</a>


{{-- ============ PESAN BERHASIL ============ --}}
@if(session('success'))

<div class="mt-5 flex items-center gap-3 rounded-2xl border border-green-200 bg-green-50 px-5 py-3 text-green-800">

    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m8 12 3 3 5-6"/></svg>

    {{ session('success') }}

</div>

@endif


{{-- ============ INFO FILTER (muncul kalau datang dari kartu dashboard) ============ --}}
@if($filter == 'belum_lunas' || $filter == 'siap_kirim')

<div class="mt-5 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-[#F8DDE2] bg-[#FDF3F5] px-5 py-3 text-[#8A3E56]">

    <span>
        Menampilkan pesanan
        <strong>{{ $filter == 'belum_lunas' ? 'yang belum lunas' : 'siap dikirim (Packing dan sudah lunas)' }}</strong>
    </span>

    <a href="{{ route('admin.orders.index') }}" class="font-medium underline">Tampilkan semua</a>

</div>

@endif


{{-- ============ TABEL PESANAN ============ --}}
<div class="mt-5 rounded-2xl border border-[#F0E3DC] bg-white p-6">

    <div class="mb-4 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-[#4A3228]">Data Pesanan Pre-Order</h3>
        <span class="text-sm text-[#7A6258]">{{ count($orders) }} pesanan</span>
    </div>


<div class="overflow-x-auto">

    <table class="w-full min-w-[1000px] overflow-hidden rounded-2xl border border-[#EBDDD5] text-left">

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
                    Total
                </th>

                <th class="border-b border-r border-[#EBDDD5] px-4 py-3 text-center">
                    Diskon
                </th>

                <th class="border-b border-r border-[#EBDDD5] px-4 py-3 text-center">
                    Pembayaran
                </th>

                <th class="border-b border-r border-[#EBDDD5] px-4 py-3 text-center">
                    Status Pesanan
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

                {{-- Kode Pesanan --}}
                <td class="border-b border-r border-[#EBDDD5] px-4 py-4 align-middle">
                    <span class="font-semibold text-[#4A3228]">
                        {{ $order->kode_pesanan }}
                    </span>
                </td>


                {{-- Pelanggan --}}
                <td class="border-b border-r border-[#EBDDD5] px-4 py-4 align-middle">
                    {{ $order->customer->nama }}
                </td>


                {{-- Total --}}
                <td class="border-b border-r border-[#EBDDD5] px-4 py-4 align-middle font-medium text-[#4A3228]">
                    Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                </td>


                {{-- Diskon --}}
                <td class="border-b border-r border-[#EBDDD5] px-4 py-4 align-middle text-[#5A4238]">
                    Rp {{ number_format($order->diskon, 0, ',', '.') }}
                </td>


                {{-- Pembayaran --}}
                <td class="border-b border-r border-[#EBDDD5] px-4 py-4 align-middle">

                    @if($order->statusPembayaran() == 'Lunas')

                        <span class="whitespace-nowrap rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                            Lunas
                        </span>

                    @else

                        <span class="whitespace-nowrap rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">
                            Belum Lunas
                        </span>

                    @endif

                </td>


                {{-- Status Pesanan --}}
                <td class="border-b border-r border-[#EBDDD5] px-4 py-4 align-middle">

                    @if (str_contains(strtolower($order->status_pesanan), 'selesai'))

                        <span class="whitespace-nowrap rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                            {{ $order->status_pesanan }}
                        </span>

                    @elseif (str_contains(strtolower($order->status_pesanan), 'kirim'))

                        <span class="whitespace-nowrap rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700">
                            {{ $order->status_pesanan }}
                        </span>

                    @elseif (str_contains(strtolower($order->status_pesanan), 'produksi'))

                        <span class="whitespace-nowrap rounded-full bg-pink-100 px-3 py-1 text-xs font-medium text-pink-700">
                            {{ $order->status_pesanan }}
                        </span>

                    @else

                        <span class="whitespace-nowrap rounded-full bg-stone-100 px-3 py-1 text-xs font-medium text-stone-700">
                            {{ $order->status_pesanan }}
                        </span>

                    @endif

                </td>


                {{-- Aksi --}}
                <td class="border-b border-[#EBDDD5] px-4 py-4 align-middle">

                    <div class="flex items-center justify-center">

                        <a href="{{ route('admin.orders.show', $order->id) }}"
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

                    Belum ada pesanan.
                    Klik "Tambah Pesanan" untuk menambahkan.

                </td>

            </tr>

            @endforelse

        </tbody>

    </table>

</div>

</div>


@endsection