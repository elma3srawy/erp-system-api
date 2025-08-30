<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;
use Modules\Inventory\Traits\V1\Relationships\PurchaseOrderRelations;

class PurchaseOrder extends Model
{
    use HasFactory, PurchaseOrderRelations;

    protected $fillable = [
        'supplier_id',
        'order_date',
        'status',
    ];

    protected $casts = [
        'order_date' => 'datetime',
    ];

    protected static function booted()
    {
        foreach (['created', 'updated', 'deleted'] as $event) {
            static::$event(fn () => Cache::tags(['purchase-orders'])->flush());
        }
    }

    protected static function newFactory()
    {
        return \Modules\Inventory\Database\Factories\PurchaseOrderFactory::new();
    }
}
