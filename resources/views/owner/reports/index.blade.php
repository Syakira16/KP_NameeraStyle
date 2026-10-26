@extends('layouts.owner')


@section('title')

Laporan Owner

@endsection



@section('content')


<div class="grid gap-6 md:grid-cols-2">



<div class="rounded-2xl border border-[#F0E3DC] bg-white p-6">


<h2 class="text-lg font-semibold text-[#4A3228]">

Laporan Transaksi

</h2>


<p class="mt-2 text-sm text-[#7A6258]">

Melihat seluruh transaksi berdasarkan periode.

</p>


<a href="{{ route('owner.reports.transaksi') }}"

class="mt-5 inline-block rounded-full bg-[#6B4A3C] px-5 py-2 text-white">

Lihat Laporan

</a>


</div>





<div class="rounded-2xl border border-[#F0E3DC] bg-white p-6">


<h2 class="text-lg font-semibold text-[#4A3228]">

Laporan Piutang

</h2>


<p class="mt-2 text-sm text-[#7A6258]">

Melihat daftar pelanggan yang belum lunas.

</p>


<a href="{{ route('owner.reports.piutang') }}"

class="mt-5 inline-block rounded-full bg-[#6B4A3C] px-5 py-2 text-white">

Lihat Laporan

</a>


</div>



</div>



@endsection