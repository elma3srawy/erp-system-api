<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;
use Modules\Inventory\Traits\V1\Relationships\PurchaseOrderDetailRelations;

class PurchaseOrderDetail extends Model
{
    use HasFactory, PurchaseOrderDetailRelations;

    protected $fillable = [
        'purchase_order_id',
        'product_id',
        'quantity',
        'unit_price',
        'total',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::saving(function ($model) {
            $model->total = $model->quantity * $model->unit_price;
        });

        foreach (['created', 'updated', 'deleted' , 'saved'] as $event) {
            static::$event(fn() => Cache::tags(['purchase-orders'])->flush());
        }
    }

    protected static function newFactory()
    {
        return \Modules\Inventory\Database\Factories\PurchaseOrderDetailFactory::new();
    }
}
