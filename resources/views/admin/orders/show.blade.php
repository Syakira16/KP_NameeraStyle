{{--
    HALAMAN DETAIL PESANAN

    Susunan halaman (dari atas ke bawah):
      1. Kepala        : kode pesanan, status, dan tombol aksi
      2. Pelanggan & Pengiriman : dua kotak informasi berdampingan
      3. Ubah Status   : satu baris penuh
      4. Detail Produk : tabel produk selebar halaman
      5. Pembayaran    : ringkasan pembayaran (kiri) dan riwayat pembayaran (kanan)
      6. Riwayat       : riwayat status (kiri) dan riwayat perubahan pesanan (kanan)

    Aturan tampilan supaya mudah dibaca:
      - Setiap kotak punya judul, lalu isi berupa baris "label ... isi" (label abu-abu, isi lebih tebal).
      - Kotak yang berisi tabel dibuat lebar penuh, tidak dipaksa muat di setengah halaman.
      - Judul kolom tabel otomatis di tengah (diatur di layouts/admin.blade.php).
--}}

@extends('layouts.admin')


@section('title')
Detail Pesanan
@endsection


@section('content')


{{-- ============ PESAN BERHASIL / GAGAL (dari controller, hanya tampil kalau ada) ============ --}}
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



{{-- ============ 1. KEPALA PESANAN + TOMBOL AKSI ============ --}}
<div class="rounded-2xl border border-[#F0E3DC] bg-white p-6">

    <div class="flex flex-wrap items-start justify-between gap-4">

        {{-- Kiri: kode dan tanggal pesanan --}}
        <div>
            <p class="text-xs uppercase tracking-widest text-[#7A6258]">Kode Pesanan</p>
            <h2 class="text-2xl font-semibold text-[#4A3228]">{{ $order->kode_pesanan }}</h2>
            <p class="mt-1 text-[#7A6258]">Dipesan pada {{ \Carbon\Carbon::parse($order->tanggal_pesan)->format('d M Y') }}</p>
        </div>

        {{-- Kanan: dua label status --}}
        <div class="flex flex-wrap items-center gap-2">

            <span class="rounded-full bg-[#F8DDE2] px-4 py-1.5 text-sm font-medium text-[#8A3E56]">
                {{ $order->status_pesanan }}
            </span>

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


    {{-- Tombol aksi: dipisah garis tipis dari kode pesanan --}}
    <div class="mt-5 flex flex-wrap items-center gap-3 border-t border-[#F5EBE5] pt-5">
        <a href="{{ route('admin.orders.index') }}"
       class="rounded-full border-2 border-[#6B4A3C] px-6 py-2 font-medium text-[#6B4A3C] hover:bg-white">
        Kembali
    </a>
        
        <a href="{{ route('admin.orders.invoice', $order->id) }}"
            target="_blank"
            class="inline-flex items-center gap-2 rounded-full bg-[#6B4A3C] px-6 py-2.5 font-medium text-white hover:bg-[#5A3E32]">

                <svg class="h-[18px] w-[18px]"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.75"
                    stroke-linecap="round"
                    stroke-linejoin="round">

                    <path d="M6 9V3h12v6"/>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                    <path d="M6 14h12v7H6z"/>

                </svg>

                Cetak Invoice

            </a>

    {{-- Tombol Kembali (selalu tampil) --}}
    
    


    {{-- Edit pesanan: hanya tampil kalau masih Menunggu Produksi --}}
    @if($order->status_pesanan == 'Menunggu Produksi')

        <a href="{{ route('admin.orders.edit', $order->id) }}"
           class="rounded-full bg-[#6B4A3C] px-6 py-2.5 font-medium text-white hover:bg-[#5A3E32]">
            Ubah Pesanan
        </a>

    @endif


    {{-- Tambah pembayaran: hanya tampil kalau belum lunas --}}
    @if($order->statusPembayaran() == 'Belum Lunas')

        <a href="{{ route('admin.payments.create', $order->id) }}"
           class="rounded-full bg-[#F8DDE2] px-6 py-2.5 font-medium text-[#8A3E56] hover:bg-[#F3C9D2]">
            Tambah Pembayaran
        </a>

    @endif


    {{--
        Tambah pengiriman.
        Syaratnya: status pesanan Packing dan belum ada data pengiriman.
        Kalau sudah lunas, tombol tampil. Kalau belum lunas, muncul keterangan.
    --}}
    @if($order->status_pesanan == 'Packing' && !$order->shipment)

        @if($order->statusPembayaran() == 'Lunas')

            <a href="{{ route('admin.shipments.create', $order->id) }}"
               class="rounded-full bg-[#E3EBF5] px-6 py-2.5 font-medium text-[#2A5080] hover:bg-[#D3E0F0]">
                Tambah Pengiriman
            </a>

        @else

            <span class="rounded-full bg-red-50 px-6 py-2.5 font-medium text-red-700">
                Pengiriman menunggu pelunasan pembayaran
            </span>

        @endif

    @endif


    {{-- Selesaikan pesanan: hanya tampil kalau pesanan sedang Dikirim --}}
    @if($order->status_pesanan == 'Dikirim')

        <form action="{{ route('admin.orders.selesai', $order->id) }}" method="POST">

            @csrf
            @method('PUT')

            <button type="submit"
                    onclick="return confirm('Apakah pesanan sudah diterima pelanggan?')"
                    class="rounded-full bg-green-600 px-6 py-2.5 font-medium text-white hover:bg-green-700">
                Selesaikan Pesanan
            </button>

        </form>

    @endif

    </div>

</div>


{{-- ============ 2. PELANGGAN & PENGIRIMAN ============ --}}
<div class="mt-5 grid gap-5 lg:grid-cols-2">


    {{-- Informasi pelanggan --}}
    <div class="rounded-2xl border border-[#F0E3DC] bg-white p-6">

        <h3 class="mb-4 text-lg font-semibold text-[#4A3228]">Informasi Pelanggan</h3>

        {{-- Nama pelanggan dengan huruf awal di lingkaran pink --}}
        <div class="mb-4 flex items-center gap-3 rounded-2xl bg-[#FDF8F5] p-4">

            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#F8DDE2] font-semibold text-[#8A3E56]">
                {{ strtoupper(substr($order->customer->nama, 0, 1)) }}
            </span>

            <span class="text-base font-semibold text-[#4A3228]">{{ $order->customer->nama }}</span>

        </div>

        <dl class="divide-y divide-[#F5EBE5]">

            <div class="flex justify-between gap-4 py-3">
                <dt class="text-[#7A6258]">No HP</dt>
                <dd class="font-medium text-[#4A3228]">{{ $order->customer->no_hp }}</dd>
            </div>

            <div class="flex justify-between gap-4 py-3">
                <dt class="shrink-0 text-[#7A6258]">Alamat</dt>
                <dd class="text-right font-medium text-[#4A3228]">{{ $order->customer->alamat }}</dd>
            </div>

        </dl>

    </div>


    {{-- Informasi pengiriman --}}
    <div class="rounded-2xl border border-[#F0E3DC] bg-white p-6">

        <h3 class="mb-4 text-lg font-semibold text-[#4A3228]">Informasi Pengiriman</h3>

        @if($order->shipment)

            <dl class="divide-y divide-[#F5EBE5]">

                <div class="flex justify-between gap-4 py-3">
                    <dt class="text-[#7A6258]">Ekspedisi</dt>
                    <dd class="font-medium text-[#4A3228]">{{ $order->shipment->ekspedisi ?? '-' }}</dd>
                </div>

                <div class="flex justify-between gap-4 py-3">
                    <dt class="text-[#7A6258]">Nomor Resi</dt>
                    <dd class="font-medium text-[#4A3228]">{{ $order->shipment->nomor_resi }}</dd>
                </div>

                <div class="flex justify-between gap-4 py-3">
                    <dt class="text-[#7A6258]">Tanggal Kirim</dt>
                    <dd class="font-medium text-[#4A3228]">{{ \Carbon\Carbon::parse($order->shipment->tanggal_kirim)->format('d M Y') }}</dd>
                </div>

            </dl>

        @else

            <p class="rounded-2xl bg-[#FDF8F5] px-4 py-10 text-center text-[#7A6258]">
                Belum ada informasi pengiriman
            </p>

        @endif

    </div>


</div>


{{-- ============ 3. UBAH STATUS PESANAN ============ --}}
<div class="mt-5 rounded-2xl border border-[#F0E3DC] bg-white p-6">

    <form action="{{ route('admin.orders.updateStatus', $order->id) }}"
          method="POST"
          class="flex flex-wrap items-center gap-4">

        @csrf
        @method('PUT')

        <h3 class="text-lg font-semibold text-[#4A3228]">Ubah Status Pesanan</h3>

        <select id="status_pesanan"
                name="status_pesanan"
                class="min-w-[200px] flex-1 rounded-xl border border-[#EBDDD5] bg-white px-4 py-2.5 focus:border-[#D4708A] focus:outline-none focus:ring-2 focus:ring-[#F8DDE2]">

            {{-- Daftar pilihan status. Status yang sedang dipakai otomatis terpilih. --}}
            @foreach(['Menunggu Produksi', 'Ready', 'Packing', 'Dikirim', 'Selesai'] as $status)

                <option value="{{ $status }}" {{ $order->status_pesanan == $status ? 'selected' : '' }}>
                    {{ $status }}
                </option>

            @endforeach

        </select>

        <button type="submit"
                class="rounded-full bg-[#6B4A3C] px-6 py-2.5 font-medium text-white hover:bg-[#5A3E32]">
            Ubah Status
        </button>

    </form>

</div>


{{-- ============ 4. DETAIL PRODUK (selebar halaman) ============ --}}
<div class="mt-5 rounded-2xl border border-[#F0E3DC] bg-white p-6">

    <div class="mb-4 flex items-center justify-between gap-3">
        <h3 class="text-lg font-semibold text-[#4A3228]">Detail Produk</h3>
        <span class="text-sm text-[#7A6258]">{{ count($order->orderDetails) }} produk</span>
    </div>

    <div class="overflow-x-auto">

        <table class="w-full min-w-[600px] text-left">

            <thead>
                <tr class="bg-[#FBF6F3] text-xs uppercase tracking-wide text-[#7A6258]">
                    <th class="rounded-l-xl px-4 py-3">Produk</th>
                    <th class="px-4 py-3">Ukuran</th>
                    <th class="px-4 py-3">Jumlah</th>
                    <th class="px-4 py-3">Harga</th>
                    <th class="rounded-r-xl px-4 py-3">Subtotal</th>
                </tr>
            </thead>

            <tbody>

                @foreach($order->orderDetails as $detail)

                <tr class="border-b border-[#F5EBE5]">

                    {{-- Nama produk, warna ditulis kecil di bawahnya --}}
                    <td class="px-4 py-4">
                        <p class="font-semibold text-[#4A3228]">{{ $detail->product->nama_produk }}</p>
                        <p class="text-sm text-[#7A6258]">{{ $detail->product->warna }}</p>
                    </td>

                    <td class="px-4 py-4">
                        <span class="rounded-full bg-[#F5EBE5] px-3 py-1 text-xs font-medium text-[#5A4238]">
                            {{ $detail->product->ukuran }}
                        </span>
                    </td>

                    <td class="px-4 py-4">{{ $detail->jumlah }} pcs</td>

                    <td class="px-4 py-4">
                        Rp {{ number_format($detail->harga, 0, ',', '.') }}
                    </td>

                    <td class="px-4 py-4 font-semibold text-[#4A3228]">
                        Rp {{ number_format($detail->jumlah * $detail->harga, 0, ',', '.') }}
                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>


{{-- ============ 5. RINGKASAN & RIWAYAT PEMBAYARAN ============ --}}
<div class="mt-5 grid gap-5 lg:grid-cols-5">


    {{-- Ringkasan pembayaran (kiri, 2 dari 5 bagian) --}}
    <div class="rounded-2xl border border-[#F0E3DC] bg-white p-6 lg:col-span-2">

        <h3 class="mb-4 text-lg font-semibold text-[#4A3228]">Ringkasan Pembayaran</h3>

        <dl class="divide-y divide-[#F5EBE5]">

            <div class="flex justify-between gap-4 py-3">
                <dt class="text-[#7A6258]">Subtotal</dt>
                <dd class="font-medium text-[#4A3228]">Rp {{ number_format($order->total_harga + $order->diskon, 0, ',', '.') }}</dd>
            </div>

            <div class="flex justify-between gap-4 py-3">
                <dt class="text-[#7A6258]">Diskon</dt>
                <dd class="font-medium text-[#4A3228]">- Rp {{ number_format($order->diskon, 0, ',', '.') }}</dd>
            </div>

            <div class="flex justify-between gap-4 py-3">
                <dt class="font-semibold text-[#4A3228]">Total Bayar</dt>
                <dd class="text-lg font-semibold text-[#4A3228]">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</dd>
            </div>

            <div class="flex justify-between gap-4 py-3">
                <dt class="text-[#7A6258]">Sudah Dibayar</dt>
                <dd class="font-medium text-green-700">Rp {{ number_format($order->totalDibayar(), 0, ',', '.') }}</dd>
            </div>

        </dl>

        {{-- Sisa pembayaran dibuat menonjol: merah kalau masih ada sisa, hijau kalau lunas --}}
        @if($order->statusPembayaran() == 'Lunas')

            <div class="mt-3 flex items-center justify-between gap-4 rounded-2xl bg-green-50 px-4 py-3">
                <span class="font-medium text-green-700">Sisa Pembayaran</span>
                <span class="text-lg font-semibold text-green-700">Lunas</span>
            </div>

        @else

            <div class="mt-3 flex items-center justify-between gap-4 rounded-2xl bg-red-50 px-4 py-3">
                <span class="font-medium text-red-700">Sisa Pembayaran</span>
                <span class="text-lg font-semibold text-red-700">Rp {{ number_format($order->sisaPembayaran(), 0, ',', '.') }}</span>
            </div>

        @endif

    </div>


    {{-- Riwayat pembayaran (kanan, 3 dari 5 bagian) --}}
    <div class="rounded-2xl border border-[#F0E3DC] bg-white p-6 lg:col-span-3">

        <div class="mb-4 flex items-center justify-between gap-3">
            <h3 class="text-lg font-semibold text-[#4A3228]">Riwayat Pembayaran</h3>
            <span class="text-sm text-[#7A6258]">{{ $order->payments->count() }} pembayaran</span>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full min-w-[460px] text-left">

                <thead>
                    <tr class="bg-[#FBF6F3] text-xs uppercase tracking-wide text-[#7A6258]">
                        <th class="rounded-l-xl px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Jumlah</th>
                        <th class="px-4 py-3">Metode</th>
                        <th class="rounded-r-xl px-4 py-3">Bukti</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($order->payments as $payment)

                    <tr class="border-b border-[#F5EBE5]">

                        <td class="px-4 py-4 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($payment->tanggal_bayar)->format('d M Y') }}
                        </td>

                        <td class="px-4 py-4 whitespace-nowrap font-semibold text-[#4A3228]">
                            Rp {{ number_format($payment->jumlah_bayar, 0, ',', '.') }}
                        </td>

                        <td class="px-4 py-4">
                            <span class="inline-flex whitespace-nowrap rounded-full bg-[#F5EBE5] px-3 py-1 text-xs font-medium text-[#5A4238]">
                                {{ $payment->metode_bayar ?? '-' }}
                            </span>
                        </td>

                        <td class="px-4 py-4">

                            @if($payment->bukti_bayar)

                                <a href="{{ asset('storage/' . $payment->bukti_bayar) }}"
                                   target="_blank"
                                   class="inline-flex whitespace-nowrap rounded-full bg-[#E3EBF5] px-4 py-1.5 text-xs font-medium text-[#2A5080] hover:bg-[#D3E0F0]">
                                    Lihat Bukti
                                </a>

                            @else

                                <span class="text-sm text-[#9A8880]">Tidak ada</span>

                            @endif

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="4" class="px-4 py-10 text-center text-[#7A6258]">
                            Belum ada pembayaran
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


</div>


{{-- ============ 6. RIWAYAT STATUS & RIWAYAT PERUBAHAN ============ --}}
<div class="mt-5 grid gap-5 lg:grid-cols-2">


    {{-- Perjalanan status (tanpa catatan perubahan pesanan) --}}
    <div class="rounded-2xl border border-[#F0E3DC] bg-white p-6">

        <h3 class="mb-4 text-lg font-semibold text-[#4A3228]">Riwayat Status Pesanan</h3>

        <div class="space-y-5">

            @forelse($order->statusHistories->where('status', '!=', 'Pesanan Diedit') as $history)

                <div class="border-l-4 border-[#D4708A] pl-4">

                    <p class="font-semibold text-[#4A3228]">{{ $history->status }}</p>

                    <p class="whitespace-pre-line text-[#5A4238]">{{ $history->keterangan }}</p>

                    <p class="mt-1 text-sm text-[#7A6258]">
                        {{ $history->created_at->format('d M Y, H:i') }}
                    </p>

                </div>

            @empty

                <p class="py-6 text-center text-[#7A6258]">Belum ada riwayat status</p>

            @endforelse

        </div>

    </div>


    {{-- Perubahan (edit) pesanan: tanggal, jam, lalu satu poin untuk tiap perubahan --}}
    <div class="rounded-2xl border border-[#F0E3DC] bg-white p-6">

        <h3 class="mb-4 text-lg font-semibold text-[#4A3228]">Riwayat Perubahan Pesanan</h3>

        <div class="space-y-4">

            @forelse($order->statusHistories->where('status', 'Pesanan Diedit') as $history)

                <div class="rounded-2xl bg-[#FDF8F5] p-4">

                    <p class="font-semibold text-[#4A3228]">
                        {{ $history->created_at->format('d M Y') }}
                        <span class="font-normal text-[#7A6258]">pukul {{ $history->created_at->format('H:i') }}</span>
                    </p>

                    {{-- Keterangan dipecah per baris, tiap perubahan menjadi satu poin --}}
                    <ul class="mt-2 space-y-1.5">

                        @foreach(explode("\n", $history->keterangan) as $baris)

                            <li class="flex gap-2 text-[#5A4238]">
                                <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-[#D4708A]"></span>
                                <span>{{ $baris }}</span>
                            </li>

                        @endforeach

                    </ul>

                </div>

            @empty

                <p class="py-6 text-center text-[#7A6258]">Pesanan ini belum pernah diubah</p>

            @endforelse

        </div>

    </div>


</div>


@endsection