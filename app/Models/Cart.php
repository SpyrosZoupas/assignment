<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = null;

    public function user() {
        return $this->hasOne(User::class);
    }

    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }
}
