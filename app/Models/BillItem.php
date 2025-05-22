<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillItem extends Model
{
    protected $fillable = [
        'bill_id',
        'description',
        'price',
        'quantity',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'bill_id'
    ];

    protected $appends = ['amount']; 

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    public function getAmountAttribute()
    {
        return $this->price * $this->quantity;
    }
}