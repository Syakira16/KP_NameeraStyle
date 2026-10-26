<!DOCTYPE html>
<html>

<head>

<title>Laporan Piutang</title>


<style>

body{
font-family:Arial;
font-size:12px;
}


table{
width:100%;
border-collapse:collapse;
}


th,td{
border:1px solid #333;
padding:8px;
}

</style>


</head>


<body>


<h2 align="center">
Nameera Style
</h2>


<h3 align="center">
Laporan Piutang
</h3>



<p>
Total Piutang:
Rp {{ number_format($totalPiutang,0,',','.') }}
</p>




<table>


<tr>

<th>Pelanggan</th>

<th>Total Pesanan</th>

<th>Dibayar</th>

<th>Sisa</th>

</tr>




@foreach($orders as $order)


<tr>


<td>
{{ $order->customer->nama }}
</td>


<td>
Rp {{ number_format($order->total_harga,0,',','.') }}
</td>


<td>
Rp {{ number_format($order->totalDibayar(),0,',','.') }}
</td>


<td>
Rp {{ number_format($order->sisaPembayaran(),0,',','.') }}
</td>


</tr>


@endforeach



</table>


</body>

</html>