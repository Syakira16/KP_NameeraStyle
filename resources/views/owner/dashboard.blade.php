@extends('layouts.owner')

@section('title')
Dashboard Owner
@endsection


@section('content')


{{-- ================= CARD RINGKASAN ================= --}}

<div class="grid gap-5 md:grid-cols-4">


    {{-- Total Pesanan --}}

    <div class="rounded-2xl border border-[#F0E3DC] bg-white p-5">

        <p class="text-sm text-[#7A6258]">
            Total Pesanan
        </p>

        <h2 class="mt-3 text-3xl font-semibold text-[#4A3228]">
            {{ $jumlahPesanan }}
        </h2>

        <p class="mt-2 text-xs text-[#A18A80]">
            Seluruh pesanan masuk
        </p>

    </div>





    {{-- Total Nilai Pesanan --}}

    <div class="rounded-2xl border border-[#F0E3DC] bg-white p-5">

        <p class="text-sm text-[#7A6258]">
            Nilai Transaksi
        </p>


        <h2 class="mt-3 text-2xl font-semibold text-[#4A3228]">

            Rp {{ number_format($totalPesanan,0,',','.') }}

        </h2>


        <p class="mt-2 text-xs text-[#A18A80]">
            Total seluruh pesanan
        </p>

    </div>





    {{-- Pembayaran Masuk --}}

    <div class="rounded-2xl border border-[#F0E3DC] bg-white p-5">


        <p class="text-sm text-[#7A6258]">
            Pembayaran Masuk
        </p>


        <h2 class="mt-3 text-2xl font-semibold text-green-700">

            Rp {{ number_format($totalPendapatan,0,',','.') }}

        </h2>


        <p class="mt-2 text-xs text-[#A18A80]">
            Total pembayaran pelanggan
        </p>


    </div>






    {{-- Piutang --}}

    <div class="rounded-2xl border border-[#F0E3DC] bg-white p-5">


        <p class="text-sm text-[#7A6258]">
            Total Piutang
        </p>


        <h2 class="mt-3 text-2xl font-semibold text-red-700">

            Rp {{ number_format($totalPiutang,0,',','.') }}

        </h2>


        <p class="mt-2 text-xs text-[#A18A80]">

            {{ $belumLunas }} transaksi belum lunas

        </p>


    </div>



</div>








{{-- ================= STATUS PESANAN ================= --}}


<div class="mt-8 rounded-2xl border border-[#F0E3DC] bg-white p-6">


    <h2 class="mb-5 text-lg font-semibold text-[#4A3228]">

        Monitoring Status Pesanan

    </h2>



    <div class="grid gap-4 md:grid-cols-5">



        @foreach($statusPesanan as $status=>$jumlah)


        <div class="rounded-xl bg-[#FBF6F3] p-4">


            <p class="text-xs text-[#7A6258]">

                {{ $status }}

            </p>


            <p class="mt-2 text-2xl font-semibold text-[#4A3228]">

                {{ $jumlah }}

            </p>



        </div>


        @endforeach



    </div>


</div>









{{-- ================= SOP PENGIRIMAN ================= --}}


<div class="mt-8 grid gap-5 md:grid-cols-2">


    {{-- Sesuai SOP --}}

    <div class="rounded-2xl border border-[#F0E3DC] bg-white p-6">


        <p class="text-sm text-[#7A6258]">

            Pengiriman Sesuai SOP

        </p>


        <h2 class="mt-3 text-3xl font-semibold text-green-700">

            {{ $sesuaiSOP }}

        </h2>


        <p class="mt-2 text-xs text-[#A18A80]">

            Diproses sebelum pukul 20.00

        </p>


    </div>





    {{-- Melewati SOP --}}

    <div class="rounded-2xl border border-[#F0E3DC] bg-white p-6">


        <p class="text-sm text-[#7A6258]">

            Pengiriman Melewati SOP

        </p>


        <h2 class="mt-3 text-3xl font-semibold text-red-700">

            {{ $melewatiSOP }}

        </h2>


        <p class="mt-2 text-xs text-[#A18A80]">

            Diproses setelah pukul 20.00

        </p>


    </div>



</div>






@endsection