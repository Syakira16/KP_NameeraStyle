
@extends('layouts.admin')

@section('title')
Tambah Pembayaran
@endsection

@section('content')

@php
    $totalPesanan = (float) $order->total_harga;
    $totalDibayar = (float) $order->totalDibayar();
    $sisaPembayaran = (float) $order->sisaPembayaran();

    // DP minimal 50% dari total pesanan setelah diskon.
    $minimalDp = (int) ceil($totalPesanan * 0.50);

    // DP hanya berlaku pada pembayaran pertama.
    $pembayaranPertama = $totalDibayar <= 0;
@endphp

{{-- ============ PESAN ERROR ============ --}}
@if(session('error'))
    <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-800">
        <p class="font-semibold">Pembayaran gagal disimpan</p>
        <p class="mt-1">{{ session('error') }}</p>
    </div>
@endif

@if(session('success'))
    <div class="mb-5 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-green-800">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-800">
        <p class="font-semibold">Periksa kembali data pembayaran:</p>
        <ul class="mt-2 list-inside list-disc">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


{{-- ============ 1. RINGKASAN PESANAN ============ --}}
<div class="rounded-2xl border border-[#F0E3DC] bg-white p-6">

    <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
        <h3 class="text-lg font-semibold text-[#4A3228]">
            Ringkasan Pesanan
        </h3>

        <span class="text-sm text-[#7A6258]">
            Kode Pesanan:
            <span class="font-semibold text-[#4A3228]">
                {{ $order->kode_pesanan }}
            </span>
        </span>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

        {{-- Total Pesanan --}}
        <div class="rounded-2xl bg-[#FDF8F5] p-4">
            <p class="text-xs text-[#7A6258]">Total Pesanan</p>
            <p class="mt-1 text-lg font-semibold text-[#4A3228]">
                Rp {{ number_format($totalPesanan, 0, ',', '.') }}
            </p>
        </div>

        {{-- Sudah Dibayar --}}
        <div class="rounded-2xl bg-green-50 p-4">
            <p class="text-xs text-[#7A6258]">Sudah Dibayar</p>
            <p class="mt-1 text-lg font-semibold text-green-700">
                Rp {{ number_format($totalDibayar, 0, ',', '.') }}
            </p>
        </div>

        {{-- Sisa Pembayaran --}}
        <div class="rounded-2xl bg-red-50 p-4">
            <p class="text-xs text-[#7A6258]">Sisa Pembayaran</p>
            <p class="mt-1 text-lg font-semibold text-red-700">
                Rp {{ number_format($sisaPembayaran, 0, ',', '.') }}
            </p>
        </div>

        {{-- DP Minimal --}}
        <div class="rounded-2xl bg-amber-50 p-4">
            <p class="text-xs text-amber-800">
                DP Minimal 50%
            </p>
            <p class="mt-1 text-lg font-semibold text-amber-900">
                Rp {{ number_format($minimalDp, 0, ',', '.') }}
            </p>
            <p class="mt-1 text-xs text-amber-800">
                Dari total setelah diskon
            </p>
        </div>

    </div>

    {{-- Petunjuk pembayaran --}}
    @if($pembayaranPertama)
        <div class="mt-5 rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-900">
            <p class="font-semibold">
                Pembayaran pertama: wajib DP minimal 50%
            </p>
            <p class="mt-1 text-sm">
                CS harus memasukkan minimal
                <strong>Rp {{ number_format($minimalDp, 0, ',', '.') }}</strong>.
                Jika nominal pembayaran di bawah angka tersebut, sistem akan menolak
                pembayaran dan menampilkan pesan alasannya.
            </p>
        </div>
    @else
        <div class="mt-5 rounded-xl border border-green-200 bg-green-50 p-4 text-green-800">
            <p class="font-semibold">
                Pembayaran cicilan berikutnya
            </p>
            <p class="mt-1 text-sm">
                Tidak ada nominal minimum khusus untuk cicilan berikutnya.
                CS boleh memasukkan nominal berapa pun di atas Rp0, selama
                tidak melebihi sisa tagihan sebesar
                <strong>Rp {{ number_format($sisaPembayaran, 0, ',', '.') }}</strong>.
            </p>
        </div>
    @endif

</div>


{{-- ============ 2. FORM PEMBAYARAN ============ --}}
<form action="{{ route('admin.payments.store', $order->id) }}"
      method="POST"
      enctype="multipart/form-data"
      class="mt-5 rounded-2xl border border-[#F0E3DC] bg-white p-6 sm:p-8">

    @csrf

    <h3 class="text-lg font-semibold text-[#4A3228]">
        Data Pembayaran
    </h3>

    <p class="mb-6 text-xs text-[#7A6258]">
        Lengkapi informasi pembayaran pelanggan
    </p>

    <div class="grid gap-5 sm:grid-cols-2">

        {{-- Tanggal Bayar --}}
        <div>
            <label for="tanggal_bayar"
                   class="mb-1.5 block font-medium text-[#4A3228]">
                Tanggal Bayar
            </label>

            <input id="tanggal_bayar"
                   name="tanggal_bayar"
                   type="date"
                   value="{{ old('tanggal_bayar', date('Y-m-d')) }}"
                   required
                   max="{{ date('Y-m-d') }}"
                   class="w-full rounded-xl border border-[#EBDDD5] px-4 py-2.5 focus:border-[#D4708A] focus:outline-none focus:ring-2 focus:ring-[#F8DDE2]">

            @error('tanggal_bayar')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>


        {{-- Jumlah Bayar --}}
        <div>
            <label for="jumlah_bayar"
                   class="mb-1.5 block font-medium text-[#4A3228]">
                Jumlah Bayar (Rp)
            </label>

            <input id="jumlah_bayar"
                   name="jumlah_bayar"
                   type="number"
                   min="1"
                   max="{{ $sisaPembayaran }}"
                   step="1"
                   value="{{ old('jumlah_bayar') }}"
                   placeholder="{{ $pembayaranPertama ? 'Minimal ' . $minimalDp : 'Masukkan nominal cicilan' }}"
                   required
                   class="w-full rounded-xl border border-[#EBDDD5] px-4 py-2.5 placeholder:text-[#B5A39A] focus:border-[#D4708A] focus:outline-none focus:ring-2 focus:ring-[#F8DDE2]">

            @if($pembayaranPertama)
                <p class="mt-1 text-xs text-amber-800">
                    Minimal DP: Rp {{ number_format($minimalDp, 0, ',', '.') }}
                </p>
            @else
                <p class="mt-1 text-xs text-[#7A6258]">
                    Bebas di atas Rp0, maksimal Rp {{ number_format($sisaPembayaran, 0, ',', '.') }}.
                </p>
            @endif

            @error('jumlah_bayar')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>


        {{-- Metode Bayar --}}
        <div>
            <label for="metode_bayar"
                   class="mb-1.5 block font-medium text-[#4A3228]">
                Metode Pembayaran
            </label>

            <select id="metode_bayar"
                    name="metode_bayar"
                    required
                    class="w-full rounded-xl border border-[#EBDDD5] bg-white px-4 py-2.5 focus:border-[#D4708A] focus:outline-none focus:ring-2 focus:ring-[#F8DDE2]">

                <option value="">Pilih Metode Pembayaran</option>

                <option value="Transfer Bank"
                    {{ old('metode_bayar') == 'Transfer Bank' ? 'selected' : '' }}>
                    Transfer Bank
                </option>

                <option value="E-Wallet"
                    {{ old('metode_bayar') == 'E-Wallet' ? 'selected' : '' }}>
                    E-Wallet
                </option>

                <option value="Tunai"
                    {{ old('metode_bayar') == 'Tunai' ? 'selected' : '' }}>
                    Tunai
                </option>
            </select>

            @error('metode_bayar')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>


        {{-- Bukti Bayar --}}
        <div>
            <label for="bukti_bayar"
                   class="mb-1.5 block font-medium text-[#4A3228]">
                Bukti Pembayaran
            </label>

            <input id="bukti_bayar"
                   name="bukti_bayar"
                   type="file"
                   accept=".jpg,.jpeg,.png"
                   class="w-full rounded-xl border border-[#EBDDD5] bg-white px-4 py-2.5 text-sm text-[#7A6258] file:mr-4 file:rounded-full file:border-0 file:bg-[#F8DDE2] file:px-4 file:py-2 file:text-xs file:font-medium file:text-[#8A3E56] hover:file:bg-[#F3C9D2]">

            <p class="mt-1 text-xs text-[#9A857B]">
                Format JPG, JPEG, atau PNG. Maksimal 2 MB.
            </p>

            @error('bukti_bayar')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

    </div>


    {{-- Tombol --}}
    <div class="mt-8 flex flex-wrap items-center gap-3">

        <button type="submit"
                class="rounded-full bg-[#6B4A3C] px-8 py-2.5 font-medium text-white hover:bg-[#5A3E32]">
            Simpan Pembayaran
        </button>

        <a href="{{ route('admin.orders.show', $order->id) }}"
           class="rounded-full border-2 border-[#6B4A3C] px-8 py-2 font-medium text-[#6B4A3C] hover:bg-[#FBF6F3]">
            Batal
        </a>

    </div>

</form>

@endsection
