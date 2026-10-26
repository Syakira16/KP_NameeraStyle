{{--
    FORM TAMBAH PENGIRIMAN

    Halaman ini dibuka dari tombol "Tambah Pengiriman" di halaman Detail Pesanan
    (hanya muncul kalau pesanan berstatus Packing dan sudah lunas).

    Data dari controller (nama jangan diubah):
      $order -> pesanan yang akan dikirim
--}}

@extends('layouts.admin')


@section('title')
Tambah Pengiriman
@endsection


@section('content')


{{-- Pesan gagal dari controller --}}
@if(session('error'))

<div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-5 py-3 text-red-800">
    {{ session('error') }}
</div>

@endif


{{-- ============ RINGKASAN PESANAN ============ --}}
<div class="mb-5 grid gap-4 rounded-2xl border border-[#F0E3DC] bg-white p-6 sm:grid-cols-3">

    <div>
        <p class="text-xs uppercase tracking-widest text-[#7A6258]">Kode Pesanan</p>
        <p class="mt-1 text-lg font-semibold text-[#4A3228]">{{ $order->kode_pesanan }}</p>
    </div>

    <div>
        <p class="text-xs uppercase tracking-widest text-[#7A6258]">Pelanggan</p>
        <p class="mt-1 text-lg font-semibold text-[#4A3228]">{{ $order->customer->nama }}</p>
    </div>

    <div>
        <p class="text-xs uppercase tracking-widest text-[#7A6258]">Alamat Tujuan</p>
        <p class="mt-1 text-[#5A4238]">{{ $order->customer->alamat }}</p>
    </div>

</div>


{{-- ============ FORM PENGIRIMAN ============ --}}
<form action="{{ route('admin.shipments.store', $order->id) }}"
      method="POST"
      class="rounded-2xl border border-[#F0E3DC] bg-white p-6 sm:p-8">

    @csrf

    <h3 class="text-lg font-semibold text-[#4A3228]">Informasi Pengiriman</h3>
    <p class="mb-6 text-xs text-[#7A6258]">Isi ekspedisi dan nomor resi. Pelanggan bisa melihat nomor resi lewat Cek Status Pesanan.</p>


    <div class="space-y-5">


        {{-- Ekspedisi --}}
        <div>
            <label for="ekspedisi" class="mb-1.5 block font-medium text-[#4A3228]">
                Ekspedisi
            </label>

            <select id="ekspedisi"
                    name="ekspedisi"
                    required
                    class="w-full rounded-xl border border-[#EBDDD5] bg-white px-4 py-2.5 focus:border-[#D4708A] focus:outline-none focus:ring-2 focus:ring-[#F8DDE2]">

                <option value="">-- Pilih Ekspedisi --</option>

                {{-- Daftar ekspedisi. Mau menambah ekspedisi? Cukup tambahkan namanya di dalam tanda [ ] --}}
                @foreach(['J&T', 'SPX', 'AnterAja', 'JNE', 'SiCepat'] as $ekspedisi)

                    <option value="{{ $ekspedisi }}" {{ old('ekspedisi') == $ekspedisi ? 'selected' : '' }}>
                        {{ $ekspedisi }}
                    </option>

                @endforeach

            </select>

            @error('ekspedisi')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>


        {{-- Nomor Resi --}}
        <div>
            <label for="nomor_resi" class="mb-1.5 block font-medium text-[#4A3228]">
                Nomor Resi
            </label>

            <input id="nomor_resi"
                   name="nomor_resi"
                   type="text"
                   required
                   value="{{ old('nomor_resi') }}"
                   placeholder="Masukkan nomor resi"
                   class="w-full rounded-xl border border-[#EBDDD5] px-4 py-2.5 placeholder:text-[#B5A39A] focus:border-[#D4708A] focus:outline-none focus:ring-2 focus:ring-[#F8DDE2]">

            @error('nomor_resi')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>


        {{-- Tanggal Kirim --}}
        <div>
            <label for="tanggal_kirim" class="mb-1.5 block font-medium text-[#4A3228]">
                Tanggal Kirim
            </label>

            <input id="tanggal_kirim"
                   name="tanggal_kirim"
                   type="date"
                   value="{{ old('tanggal_kirim', date('Y-m-d')) }}"
                   class="w-full rounded-xl border border-[#EBDDD5] px-4 py-2.5 focus:border-[#D4708A] focus:outline-none focus:ring-2 focus:ring-[#F8DDE2]">

            @error('tanggal_kirim')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>


    </div>


    {{-- Tombol --}}
    <div class="mt-8 flex flex-wrap items-center gap-3">

        <button type="submit"
                class="rounded-full bg-[#6B4A3C] px-8 py-2.5 font-medium text-white hover:bg-[#5A3E32]">
            Simpan Pengiriman
        </button>

        <a href="{{ route('admin.orders.show', $order->id) }}"
           class="rounded-full border-2 border-[#6B4A3C] px-8 py-2 font-medium text-[#6B4A3C] hover:bg-[#FBF6F3]">
            Kembali
        </a>

    </div>


</form>


@endsection