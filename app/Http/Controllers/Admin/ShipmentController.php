<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Http\Request;



class ShipmentController extends Controller
{


    /**
     * Form input pengiriman
     */

    /**
 * Daftar pengiriman
 */
public function index()
{

    $shipments = Shipment::with([
        'order.customer'
    ])
    ->latest()
    ->get();



    $totalPengiriman = Shipment::count();



    $sudahDikirim = Shipment::whereNotNull(
        'nomor_resi'
    )
    ->count();



    $belumDikirim = Order::where(
        'status_pesanan',
        'Packing'
    )
    ->whereDoesntHave('shipment')
    ->count();



    return view(
        'admin.shipments.index',
        compact(
            'shipments',
            'totalPengiriman',
            'sudahDikirim',
            'belumDikirim'
        )
    );

}

    public function create(Order $order)
    {

        // Pastikan status pesanan Packing

        if($order->status_pesanan != 'Packing'){

            return redirect()
            ->route('admin.orders.show',$order->id)
            ->with(
                'error',
                'Pengiriman hanya dapat dilakukan setelah pesanan masuk tahap Packing'
            );

        }



        // Cek pembayaran dan kesiapan kirim

        if(!$order->siapDikirim()){

            return redirect()
            ->route('admin.orders.show',$order->id)
            ->with(
                'error',
                'Pesanan belum dapat dikirim. Pastikan pembayaran sudah lunas.'
            );

        }




        // Cek apakah sudah ada pengiriman

        if($order->shipment){


            return redirect()
            ->route(
                'admin.orders.show',
                $order->id
            )
            ->with(
                'error',
                'Pesanan ini sudah memiliki data pengiriman'
            );


        }




        return view(
            'admin.shipments.create',
            compact('order')
        );


    }







    /**
     * Simpan data pengiriman
     */
    public function store(Request $request, Order $order)
    {



        /*
        |--------------------------------------------------------------------------
        | Validasi SOP sebelum pengiriman
        |--------------------------------------------------------------------------
        */


        // Status harus Packing

        if($order->status_pesanan != 'Packing'){


            return redirect()
            ->route('admin.orders.show',$order->id)
            ->with(
                'error',
                'Pengiriman hanya dapat dilakukan ketika status pesanan Packing'
            );


        }



        // Pembayaran harus lunas

        if(!$order->siapDikirim()){


            return redirect()
            ->route('admin.orders.show',$order->id)
            ->with(
                'error',
                'Pesanan belum dapat dikirim karena pembayaran belum lunas'
            );


        }




        // Cegah pengiriman ganda

        if($order->shipment){


            return redirect()
            ->route(
                'admin.orders.show',
                $order->id
            )
            ->with(
                'error',
                'Data pengiriman sudah tersedia'
            );


        }






        /*
        |--------------------------------------------------------------------------
        | Validasi input
        |--------------------------------------------------------------------------
        */


        $request->validate([

            'ekspedisi'=>'required',

            'nomor_resi'=>'required',

            'tanggal_kirim'=>'required|date'

        ]);







        /*
        |--------------------------------------------------------------------------
        | Simpan data shipment
        |--------------------------------------------------------------------------
        */


        Shipment::create([


            'order_id'=>$order->id,


            'ekspedisi'=>$request->ekspedisi,


            'nomor_resi'=>$request->nomor_resi,


            'tanggal_kirim'=>$request->tanggal_kirim


        ]);







        /*
        |--------------------------------------------------------------------------
        | Cek SOP batas waktu 20.00
        |--------------------------------------------------------------------------
        */


        $jamSekarang = now()->format('H:i');



        if($jamSekarang <= '20:00'){


            $keterangan =
            'Pengiriman diproses sesuai SOP sebelum pukul 20.00';


        }else{


            $keterangan =
            'Pengiriman diproses melewati batas SOP pukul 20.00';


        }








        /*
        |--------------------------------------------------------------------------
        | Update status pesanan
        |--------------------------------------------------------------------------
        */


        $order->update([

            'status_pesanan'=>'Dikirim'

        ]);








        /*
        |--------------------------------------------------------------------------
        | Simpan histori status
        |--------------------------------------------------------------------------
        */


        $order->statusHistories()->create([


            'status'=>'Dikirim',


            'keterangan'=>$keterangan,


            'user_id'=>auth()->id()


        ]);









        return redirect()

        ->route(
            'admin.orders.show',
            $order->id
        )

        ->with(
            'success',
            'Data pengiriman berhasil disimpan'
        );


    }



}