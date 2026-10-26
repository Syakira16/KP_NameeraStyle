<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'kode_pesanan',
        'tanggal_pesan',
        'total_harga',
        'diskon',
        'status_pesanan',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function shipment(): HasOne
    {
        return $this->hasOne(Shipment::class);
    }

    //perhitungan : 
    public function totalDibayar()
    {
        return $this->payments->sum('jumlah_bayar');
    }


    public function sisaPembayaran()
    {
        return $this->total_harga - $this->totalDibayar();
    }


    public function statusPembayaran()
    {
        if($this->sisaPembayaran() <= 0){
            return 'Lunas';
        }
        return 'Belum Lunas';
    }

    public function siapDikirim()
    {
        return 
        $this->statusPembayaran() == 'Lunas'
        &&
        $this->status_pesanan == 'Packing';
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class);
    }
}
