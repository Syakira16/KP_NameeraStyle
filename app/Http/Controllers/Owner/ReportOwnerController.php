<?php

namespace App\Http\Controllers\Owner;


use App\Http\Controllers\Controller;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;



class ReportOwnerController extends Controller
{


    /**
     * Halaman utama laporan owner
     */
    public function index()
    {

        return view('owner.reports.index');

    }







    /**
     * Laporan transaksi
     */
    public function transaksi(Request $request)
    {


        $orders = Order::with([

            'customer',
            'payments'

        ])


        ->when($request->mulai, function($query) use ($request){


            $query->whereDate(

                'tanggal_pesan',

                '>=',

                $request->mulai

            );


        })



        ->when($request->sampai, function($query) use ($request){


            $query->whereDate(

                'tanggal_pesan',

                '<=',

                $request->sampai

            );


        })



        ->latest()

        ->get();






        $totalTransaksi = $orders->sum(

            'total_harga'

        );






        return view(

            'owner.reports.transaksi',

            compact(

                'orders',

                'totalTransaksi'

            )

        );


    }









    /**
     * Export PDF transaksi
     */
    public function transaksiPdf(Request $request)
    {


        $orders = Order::with([

            'customer',
            'payments'

        ])

        ->when($request->mulai, function($query) use ($request){


            $query->whereDate(

                'tanggal_pesan',

                '>=',

                $request->mulai

            );


        })



        ->when($request->sampai, function($query) use ($request){


            $query->whereDate(

                'tanggal_pesan',

                '<=',

                $request->sampai

            );


        })


        ->latest()

        ->get();






        $totalTransaksi = $orders->sum(

            'total_harga'

        );







        $pdf = Pdf::loadView(

            'owner.pdf.transaksi',

            compact(

                'orders',

                'totalTransaksi'

            )

        );






        return $pdf->download(

            'laporan-transaksi-nameera-style.pdf'

        );


    }









    /**
     * Laporan piutang
     */
    public function piutang()
    {


        $orders = Order::with([

            'customer',
            'payments'

        ])

        ->get()

        ->filter(function($order){


            return $order->sisaPembayaran() > 0;


        });







        $totalPiutang = $orders->sum(function($order){


            return $order->sisaPembayaran();


        });








        return view(

            'owner.reports.piutang',

            compact(

                'orders',

                'totalPiutang'

            )

        );


    }









    /**
     * Export PDF piutang
     */
    public function piutangPdf()
    {


        $orders = Order::with([

            'customer',
            'payments'

        ])

        ->get()

        ->filter(function($order){


            return $order->sisaPembayaran() > 0;


        });








        $totalPiutang = $orders->sum(function($order){


            return $order->sisaPembayaran();


        });








        $pdf = Pdf::loadView(

            'owner.pdf.piutang',

            compact(

                'orders',

                'totalPiutang'

            )

        );







        return $pdf->download(

            'laporan-piutang-nameera-style.pdf'

        );


    }





}