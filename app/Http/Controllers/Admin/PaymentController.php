<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Menampilkan daftar pembayaran.
     */
    public function index(Request $request)
    {
        $query = Order::with([
            'customer',
            'payments'
        ]);

        // ================= SEARCH =================

        if ($request->search) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'kode_pesanan',
                    'like',
                    "%$search%"
                )

                ->orWhereHas('customer', function ($customer) use ($search) {

                    $customer->where(
                        'nama',
                        'like',
                        "%$search%"
                    );

                });

            });
        }

        // ================= AMBIL DATA PESANAN =================

        $orders = $query
            ->latest()
            ->get();

        // ================= FILTER STATUS PEMBAYARAN =================

        if ($request->status) {

            $orders = $orders->filter(function ($order) use ($request) {

                return $order->statusPembayaran()
                    == $request->status;

            });
        }

        // ================= TOTAL =================

        $totalTransaksi = $orders->sum('total_harga');

        $totalDibayar = $orders->sum(function ($order) {

            return $order->totalDibayar();

        });

        $totalPiutang = $totalTransaksi - $totalDibayar;

        // ================= VIEW =================

        return view(
            'admin.payments.index',
            compact(
                'orders',
                'totalTransaksi',
                'totalDibayar',
                'totalPiutang'
            )
        );
    }


    /**
     * Menampilkan form tambah pembayaran.
     */
    public function create(Order $order)
    {
        // Jika pesanan sudah lunas,
        // pembayaran baru tidak boleh ditambahkan.
        if ($order->statusPembayaran() == 'Lunas') {

            return redirect()
                ->route(
                    'admin.orders.show',
                    $order->id
                )
                ->with(
                    'error',
                    'Pesanan sudah lunas, pembayaran tidak dapat ditambahkan lagi'
                );
        }

        return view(
            'admin.payments.create',
            compact('order')
        );
    }


    /**
     * Menyimpan pembayaran baru.
     */

/**
 * Menyimpan pembayaran baru.
 */
    public function store(Request $request, Order $order)
    {
        // ================= VALIDASI INPUT =================

        $request->validate([
            'tanggal_bayar' => [
                'required',
                'date',
                'before_or_equal:today'
            ],

            'jumlah_bayar' => [
                'required',
                'numeric',
                'min:1'
            ],

            'metode_bayar' => [
                'required',
                'string'
            ],

            'bukti_bayar' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048'
            ],
        ]);

        // ================= CEK STATUS PESANAN =================

        if ($order->statusPembayaran() == 'Lunas') {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Pesanan sudah lunas, pembayaran tidak dapat ditambahkan lagi.'
                );
        }

        // ================= HITUNG PEMBAYARAN =================

        $totalPesanan = (float) $order->total_harga;

        // DP minimal 50% dari total setelah diskon.
        $minimalDp = (int) ceil($totalPesanan * 0.50);

        $totalDibayar = (float) $order->totalDibayar();

        $jumlahBayar = (float) $request->jumlah_bayar;

        $sisaPembayaran = (float) $order->sisaPembayaran();

        // ================= CEK JUMLAH BAYAR =================

        if ($jumlahBayar > $sisaPembayaran) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Jumlah pembayaran melebihi sisa tagihan.'
                );
        }

        // ================= CEK DP MINIMAL =================

        // Pembayaran pertama wajib mencapai DP minimal 50%.
        if ($totalDibayar <= 0 && $jumlahBayar < $minimalDp) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Pembayaran pertama minimal Rp '
                    . number_format($minimalDp, 0, ',', '.')
                    . ' (50% dari total pesanan setelah diskon).'
                );
        }

        // ================= UPLOAD BUKTI =================

        $namaBukti = null;

        if ($request->hasFile('bukti_bayar')) {
            $namaBukti = $request
                ->file('bukti_bayar')
                ->store('bukti_pembayaran', 'public');
        }

        // ================= SIMPAN PEMBAYARAN =================

        Payment::create([
            'order_id' => $order->id,
            'tanggal_bayar' => $request->tanggal_bayar,
            'jumlah_bayar' => $jumlahBayar,
            'metode_bayar' => $request->metode_bayar,
            'bukti_bayar' => $namaBukti,
        ]);

        // ================= KEMBALI =================

        return redirect()
            ->route('admin.orders.show', $order->id)
            ->with(
                'success',
                'Pembayaran berhasil ditambahkan.'
            );
    }

}