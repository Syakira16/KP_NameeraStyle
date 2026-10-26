@extends('layouts.owner')


@section('title')

Laporan Piutang

@endsection



@section('content')


<div class="rounded-2xl border border-[#F0E3DC] bg-white p-6">


<h2 class="mb-5 font-semibold">

Total Piutang:

Rp {{ number_format($totalPiutang,0,',','.') }}

</h2>




<table class="w-full">


<thead>

<tr class="bg-[#FBF6F3]">


<th class="px-4 py-3 text-left">
Pelanggan
</th>


<th class="px-4 py-3 text-left">
Total Pesanan
</th>


<th class="px-4 py-3 text-left">
Sisa Pembayaran
</th>


</tr>


</thead>




<tbody>


@foreach($orders as $order)


<tr class="border-b">


<td class="px-4 py-3">

{{ $order->customer->nama }}

</td>


<td class="px-4 py-3">

Rp {{ number_format($order->total_harga,0,',','.') }}

</td>


<td class="px-4 py-3 text-red-700">

Rp {{ number_format($order->sisaPembayaran(),0,',','.') }}

</td>


</tr>


@endforeach


</tbody>


</table>


</div>


@endsection