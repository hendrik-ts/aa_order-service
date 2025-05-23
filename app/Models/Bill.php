<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $fillable = [
        'code',
        'table_no',
        'sub_total',
        'total',
        'discount',
        'tax_rate',
        'paid',
        'paid_at',
    ];

    protected $hidden = [
        'outlet_name'
    ];

    protected $casts = [
        'paid' => 'boolean',
        'paid_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(BillItem::class);
    }

    public function payment()
    {
        return $this->hasOne(BillPayment::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
