<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'nama',
        'no_hp',
        'alamat',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
