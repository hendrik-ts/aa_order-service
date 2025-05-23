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
        'created_at',
        'updated_at'
    ];
}
