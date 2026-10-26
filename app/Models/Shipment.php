<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipment extends Model
{
    protected $fillable = [
        'order_id',
        'ekspedisi',
        'nomor_resi',
        'tanggal_kirim',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
