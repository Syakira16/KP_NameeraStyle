<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;


class OrderMonitoringController extends Controller
{


    public function index()
    {


        $orders = Order::with([

            'customer',

            'payments',

            'shipment'

        ])

        ->latest()

        ->get();



        return view(

            'owner.orders.index',

            compact('orders')

        );


    }


}