<?php

namespace App\Http\Controllers;


use App\Models\Order;
use Illuminate\Http\Request;


class TrackingController extends Controller
{


    public function index()
    {

        return view('tracking.index');

    }




    public function search(Request $request)
    {


        $request->validate([

            'kode_pesanan'=>'required'

        ]);



        $order = Order::with([
            'customer',
            'orderDetails.product',
            'shipment',
            'statusHistories'
        ])

        ->where(
            'kode_pesanan',
            $request->kode_pesanan
        )

        ->first();

        if(!$order){
            return back()

            ->with(
                'error',
                'Kode pesanan tidak ditemukan'
            );


        }

        return view(
            'tracking.result',
            compact('order')
        );
    }

    public function detail($kode_pesanan)
{

    $order = Order::with([
        'customer',
        'orderDetails.product',
        'payments',
        'shipment',
        'statusHistories'
    ])
    ->where(
        'kode_pesanan',
        $kode_pesanan
    )
    ->first();


    if(!$order){

        return redirect()
        ->route('tracking.index')
        ->with(
            'error',
            'Kode pesanan tidak ditemukan'
        );

    }


    return view(
        'tracking.detail',
        compact('order')
    );

}


}