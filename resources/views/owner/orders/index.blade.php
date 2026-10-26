@extends('layouts.owner')


@section('title')

Monitoring Pesanan

@endsection




@section('content')


<div class="rounded-2xl border border-[#F0E3DC] bg-white p-6">


<h2 class="mb-5 text-lg font-semibold text-[#4A3228]">

Daftar Monitoring Pesanan

</h2>




<div class="overflow-x-auto">


<table class="w-full text-left">


<thead>


<tr class="bg-[#FBF6F3] text-xs uppercase text-[#7A6258]">


<th class="px-4 py-3">
Kode Pesanan
</th>


<th class="px-4 py-3">
Pelanggan
</th>


<th class="px-4 py-3">
Tanggal
</th>


<th class="px-4 py-3">
Status
</th>


<th class="px-4 py-3">
Pembayaran
</th>


<th class="px-4 py-3">
Pengiriman
</th>


</tr>


</thead>





<tbody>


@foreach($orders as $order)


<tr class="border-b border-[#F5EBE5]">


<td class="px-4 py-3 font-semibold">

{{ $order->kode_pesanan }}

</td>




<td class="px-4 py-3">

{{ $order->customer->nama }}

</td>




<td class="px-4 py-3">

{{ $order->tanggal_pesan }}

</td>




<td class="px-4 py-3">


<span class="rounded-full bg-[#F5EBE5] px-3 py-1 text-xs">

{{ $order->status_pesanan }}

</span>


</td>




<td class="px-4 py-3">


@if($order->statusPembayaran() == 'Lunas')


<span class="text-green-700">

Lunas

</span>


@else


<span class="text-red-700">

Belum Lunas

</span>


@endif


</td>





<td class="px-4 py-3">


@if($order->shipment)


{{ $order->shipment->ekspedisi }}

<br>

<span class="text-xs text-[#7A6258]">

{{ $order->shipment->nomor_resi }}

</span>


@else


<span class="text-[#A18A80]">

Belum dikirim

</span>


@endif


</td>




</tr>


@endforeach


</tbody>


</table>


</div>


</div>


@endsection