<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosBill extends Model
{
    protected $fillable = [
        'table_no',
        'source'
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
