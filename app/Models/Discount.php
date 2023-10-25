<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'discountCode',
        'price',
        'used',
    ];

    public function order() {
        return $this->hasOne(Order::class);
    }
}
