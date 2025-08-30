<?php

namespace Modules\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Sales\Database\Factories\OrderDetailsFactory;
use Modules\Sales\Traits\V1\Relationships\OrderDetailsRelations;

class OrderDetails extends Model
{
    use HasFactory,
        OrderDetailsRelations;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'total',
    ];

    protected static function newFactory(): OrderDetailsFactory
    {
        return OrderDetailsFactory::new();
    }

    protected static function booted()
    {
        static::saving(function (self $model) {
            $model->total = $model->quantity * $model->unit_price;
        });
    }

}
