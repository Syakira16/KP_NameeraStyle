<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{


    public function index(Request $request)
    {


        // Filter status

        $status = $request->status;



        $orders = Order::with([

            'customer',
            'payments'

        ]);





        if($status){


            $orders->where(
                'status_pesanan',
                $status
            );


        }




        $orders = $orders->latest()->get();







        // Total nilai seluruh pesanan

        $totalPesanan = Order::sum('total_harga');





        // Total uang masuk

        $totalPembayaran = Payment::sum('jumlah_bayar');






        // Total piutang

        $totalPiutang = $totalPesanan - $totalPembayaran;







        // Jumlah pesanan

        $jumlahPesanan = Order::count();







        return view(
            'admin.reports.index',
            compact(

                'orders',

                'totalPesanan',

                'totalPembayaran',

                'totalPiutang',

                'jumlahPesanan',

                'status'

            )
        );


    }
public function show(Order $order)
{

    $order->load([

        'customer',

        'orderDetails.product',

        'payments',

        'shipment'

    ]);



    return view(
        'admin.reports.show',
        compact('order')
    );

}

public function pdf()
{


    $orders = Order::with([

        'customer',
        'payments'

    ])

    ->latest()

    ->get();




    $totalPesanan = Order::sum('total_harga');


    $totalPembayaran = Payment::sum('jumlah_bayar');


    $totalPiutang = 
    $totalPesanan - $totalPembayaran;





    $pdf = Pdf::loadView(
        'admin.reports.pdf',
        compact(
            'orders',
            'totalPesanan',
            'totalPembayaran',
            'totalPiutang'
        )
    );




    return $pdf->download(
        'laporan-nameera-style.pdf'
    );


}
}