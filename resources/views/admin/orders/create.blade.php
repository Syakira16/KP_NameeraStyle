{{--
    FORM TAMBAH PESANAN PRE-ORDER

    Susunan form (dari atas ke bawah):
      1. Pelanggan dan tanggal pesan
      2. Produk yang dipesan (mulai dari 1 baris, bisa ditambah)

    Cara menambah baris produk (tanpa JavaScript):
      - Isi angka di kotak "Tambah baris", misalnya 3, lalu klik tombol "+ Tambah Produk".
      - Halaman dimuat ulang dengan 3 baris produk baru. Isian yang sudah diketik tidak hilang.
      - Angka dibiarkan 1 kalau hanya ingin menambah satu baris.

    Penjelasan nama input:
      product_id[] dan jumlah[] memakai tanda [] artinya "daftar".
      Laravel menerima semua baris sekaligus, lalu OrderController memprosesnya satu per satu.

    old('product_id.0') = isian terakhir untuk baris pertama (nomor 0), supaya
    pilihan tidak hilang kalau form dimuat ulang atau gagal disimpan.
--}}

@extends('layouts.admin')


@section('title')
Tambah Pesanan Pre-Order
@endsection


@section('content')


{{-- Pesan gagal dari controller --}}
@if(session('error'))

<div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-5 py-3 text-red-800">
    {{ session('error') }}
</div>

@endif


<form action="{{ route('admin.orders.store') }}"
      method="POST"
      class="rounded-2xl border border-[#F0E3DC] bg-white p-6 sm:p-8">

    @csrf

    {{--
        Tombol Simpan "tak terlihat" yang sengaja diletakkan paling atas.
        Alasannya: kalau Enter ditekan di kolom isian, browser menekan tombol submit PERTAMA di form.
        Tanpa ini, yang tertekan malah tombol Cari / Ganti pelanggan.
    --}}
    <button type="submit" class="sr-only" tabindex="-1" aria-hidden="true">Simpan</button>

    {{-- Menyimpan jumlah baris produk yang sedang tampil (dipakai tombol Tambah Produk) --}}
    <input type="hidden" name="jumlah_baris" value="{{ $jumlahBaris }}">

    <h3 class="text-lg font-semibold text-[#4A3228]">Informasi Pesanan</h3>
    <p class="mb-6 text-xs text-[#7A6258]">Pilih pelanggan, lalu isi produk yang dipesan</p>


    <div class="space-y-5">


        {{-- ============ 1. PELANGGAN DAN TANGGAL PESAN ============ --}}
        <div class="grid gap-5 sm:grid-cols-2">

            {{-- ===== PILIH PELANGGAN (pakai pencarian, bukan daftar panjang) ===== --}}
            <div>

                <label class="mb-1.5 block font-medium text-[#4A3228]">
                    Pelanggan
                </label>

                @if($customerTerpilih)

                    {{-- Pelanggan sudah dipilih: tampilkan kartu kecil. customer_id dikirim lewat input tersembunyi. --}}
                    <input type="hidden" name="customer_id" value="{{ $customerTerpilih->id }}">

                    <div class="rounded-xl border border-[#EBDDD5] bg-[#FBF6F3] px-4 py-3">
                        <p class="font-semibold text-[#4A3228]">{{ $customerTerpilih->nama }}</p>
                        <p class="text-sm text-[#7A6258]">{{ $customerTerpilih->no_hp }}</p>
                        <p class="text-sm text-[#7A6258]">{{ $customerTerpilih->alamat }}</p>
                    </div>

                    {{-- Tombol ganti pelanggan. Isian form lain tidak hilang. --}}
                    <button type="submit"
                            formaction="{{ route('admin.orders.ganti_pelanggan') }}"
                            class="mt-2 text-sm font-medium text-[#D4708A] hover:underline">
                        Ganti pelanggan
                    </button>

                @else

                    {{-- Belum ada pelanggan terpilih: tampilkan kolom cari --}}
                    <div class="flex gap-2">

                        <input type="text"
                               name="cari_pelanggan"
                               value="{{ old('cari_pelanggan') }}"
                               placeholder="Ketik nama atau no HP"
                               class="w-full rounded-xl border border-[#EBDDD5] bg-white px-4 py-2.5 placeholder:text-[#B5A39A] focus:border-[#D4708A] focus:outline-none focus:ring-2 focus:ring-[#F8DDE2]">

                        <button type="submit"
                                formaction="{{ route('admin.orders.cari_pelanggan') }}"
                                class="shrink-0 rounded-xl bg-[#EFE0D6] px-5 py-2.5 font-medium text-[#6B4A3C] hover:bg-[#E6D2C4]">
                            Cari
                        </button>

                    </div>

                    {{-- Hasil pencarian (paling banyak 20 pelanggan) --}}
                    @if($cariPelanggan)

                        @if(count($hasilCari) > 0)

                            <select name="customer_id"
                                    class="mt-2 w-full rounded-xl border border-[#EBDDD5] bg-white px-4 py-2.5 focus:border-[#D4708A] focus:outline-none focus:ring-2 focus:ring-[#F8DDE2]">

                                <option value="">-- Pilih dari hasil pencarian --</option>

                                @foreach($hasilCari as $customer)
                                    <option value="{{ $customer->id }}">
                                        {{ $customer->nama }} - {{ $customer->no_hp }} - {{ Str::limit($customer->alamat, 40) }}
                                    </option>
                                @endforeach

                            </select>

                            {{-- Tombol Pilih: memasukkan pelanggan yang dipilih ke form --}}
                            <button type="submit"
                                    formaction="{{ route('admin.orders.pilih_pelanggan') }}"
                                    class="mt-2 rounded-xl bg-[#6B4A3C] px-5 py-2 text-sm font-medium text-white hover:bg-[#5A3E32]">
                                Pilih Pelanggan Ini
                            </button>

                            @if(count($hasilCari) == 20)
                                <p class="mt-1 text-xs text-[#7A6258]">Menampilkan 20 pelanggan pertama. Ketik lebih lengkap untuk mempersempit.</p>
                            @endif

                        @else

                            <p class="mt-2 text-sm text-red-600">
                                Pelanggan "{{ $cariPelanggan }}" tidak ditemukan.
                                <a href="{{ route('admin.customers.create') }}" class="font-medium underline">Tambah pelanggan baru</a>
                            </p>

                        @endif

                    @endif

                @endif

                @error('customer_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror

            </div>

            <div>
                <label for="tanggal_pesan" class="mb-1.5 block font-medium text-[#4A3228]">
                    Tanggal Pesan
                </label>

                <input id="tanggal_pesan"
                       name="tanggal_pesan"
                       type="date"
                       value="{{ old('tanggal_pesan', date('Y-m-d')) }}"
                       class="w-full rounded-xl border border-[#EBDDD5] px-4 py-2.5 focus:border-[#D4708A] focus:outline-none focus:ring-2 focus:ring-[#F8DDE2]">

                @error('tanggal_pesan')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

        </div>


        {{-- ============ 2. PRODUK YANG DIPESAN ============ --}}
        <div>

            <p class="mb-1 font-medium text-[#4A3228]">Produk yang Dipesan</p>

            <p class="mb-3 text-xs text-[#7A6258]">
                Diskon Rp 50.000 otomatis diberikan kalau total jumlah 4 pcs atau lebih.
                Produk yang sama dipilih dua kali akan dijumlahkan.
            </p>


            <div class="space-y-3">

                {{-- Mengulang sebanyak $jumlahBaris kali: $i bernilai 0, 1, 2, ... --}}
                @for($i = 0; $i < $jumlahBaris; $i++)

                <div class="grid gap-3 rounded-2xl bg-[#FDF8F5] p-4 sm:grid-cols-4">

                    {{-- Pilihan produk --}}
                    <div class="sm:col-span-3">

                        <label class="mb-1 block text-xs font-medium text-[#7A6258]">
                            Produk {{ $i + 1 }}
                        </label>

                        <select name="product_id[]"
                                class="w-full rounded-xl border border-[#EBDDD5] bg-white px-4 py-2.5 focus:border-[#D4708A] focus:outline-none focus:ring-2 focus:ring-[#F8DDE2]">

                            <option value="">-- Pilih Produk --</option>

                            @foreach($products as $product)

                                <option value="{{ $product->id }}" {{ old('product_id.' . $i) == $product->id ? 'selected' : '' }}>
                                    {{ $product->nama_produk }}
                                    | Warna: {{ $product->warna }}
                                    | Ukuran: {{ $product->ukuran }}
                                    | Rp {{ number_format($product->harga, 0, ',', '.') }}
                                </option>

                            @endforeach

                        </select>

                        @error('product_id.' . $i)
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror

                    </div>

                    {{-- Jumlah --}}
                    <div>

                        <label class="mb-1 block text-xs font-medium text-[#7A6258]">
                            Jumlah
                        </label>

                        <input name="jumlah[]"
                               type="number"
                               min="1"
                               value="{{ old('jumlah.' . $i, 1) }}"
                               class="w-full rounded-xl border border-[#EBDDD5] bg-white px-4 py-2.5 focus:border-[#D4708A] focus:outline-none focus:ring-2 focus:ring-[#F8DDE2]">

                        @error('jumlah.' . $i)
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror

                    </div>

                </div>

                @endfor

            </div>


            {{-- Pesan kalau semua baris produk kosong --}}
            @error('product_id')
                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
            @enderror


            {{-- Tambah baris produk.
                 formaction = tombol ini mengirim form ke route lain (tambahBaris), bukan ke Simpan Pesanan. --}}
            <div class="mt-4 flex flex-wrap items-center gap-3">

                <label for="tambah_baris" class="text-sm text-[#7A6258]">Tambah baris sebanyak</label>

                <input id="tambah_baris"
                       name="tambah_baris"
                       type="number"
                       min="1"
                       max="20"
                       value="1"
                       class="w-20 rounded-xl border border-[#EBDDD5] px-3 py-2 text-center focus:border-[#D4708A] focus:outline-none focus:ring-2 focus:ring-[#F8DDE2]">

                <button type="submit"
                        formaction="{{ route('admin.orders.tambah_baris') }}"
                        class="rounded-full bg-[#F8DDE2] px-5 py-2 text-sm font-medium text-[#8A3E56] hover:bg-[#F2CBD3]">
                    + Tambah Produk
                </button>

            </div>

        </div>




    </div>


    {{-- Tombol --}}
    <div class="mt-8 flex flex-wrap items-center gap-3">

        <button type="submit"
                class="rounded-full bg-[#6B4A3C] px-8 py-2.5 font-medium text-white hover:bg-[#5A3E32]">
            Simpan Pesanan
        </button>

        <a href="{{ route('admin.orders.index') }}"
           class="rounded-full border-2 border-[#6B4A3C] px-8 py-2 font-medium text-[#6B4A3C] hover:bg-[#FBF6F3]">
            Batal
        </a>

    </div>


</form>


@endsection