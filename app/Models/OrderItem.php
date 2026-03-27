<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $casts = [
        'price' => 'float',
        'quantity' => 'int',
    ];

    public function product()
    {
        return $this::belongsTo(Product::class);
    }

    public function orders()
    {
        return $this::belongsTo(Orders::class);
    }
}
