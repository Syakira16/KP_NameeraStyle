<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\OrderStatusHistory;

class DashboardOwnerController extends Controller
{

    public function index()
    {
        // Jumlah seluruh pesanan
        $jumlahPesanan = Order::count();

        // Total nilai transaksi
        $totalPesanan = Order::sum('total_harga');

        // Total pembayaran masuk
        $totalPendapatan = Payment::sum('jumlah_bayar');

        // Total piutang
        $totalPiutang = 
        $totalPesanan - $totalPendapatan;

        $statusPesanan = [
            'Menunggu Produksi' => Order::where(
                'status_pesanan',
                'Menunggu Produksi'
            )->count(),



            'Ready' => Order::where(
                'status_pesanan',
                'Ready'
            )->count(),



            'Packing' => Order::where(
                'status_pesanan',
                'Packing'
            )->count(),



            'Dikirim' => Order::where(
                'status_pesanan',
                'Dikirim'
            )->count(),



            'Selesai' => Order::where(
                'status_pesanan',
                'Selesai'
            )->count(),


        ];

        $belumLunas = Order::get()

        ->filter(function($order){

            return $order->sisaPembayaran() > 0;

        })

        ->count();

$sesuaiSOP = OrderStatusHistory::where(
    'status',
    'Dikirim'
)
->whereTime(
    'created_at',
    '<=',
    '20:00:00'
)
->count();



$melewatiSOP = OrderStatusHistory::where(
    'status',
    'Dikirim'
)
->whereTime(
    'created_at',
    '>',
    '20:00:00'
)
->count();







        return view(

            'owner.dashboard',

            compact(

                'jumlahPesanan',

                'totalPesanan',

                'totalPendapatan',

                'totalPiutang',

                'statusPesanan',

                'belumLunas',

                'sesuaiSOP',

                'melewatiSOP'

            )

        );


    }

}