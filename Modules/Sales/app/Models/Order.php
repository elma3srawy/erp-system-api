<?php

namespace Modules\Sales\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Modules\Sales\Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Sales\Traits\V1\Relationships\OrderRelations;

class Order extends Model
{
    use HasFactory,
        OrderRelations;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'customer_id',
        'order_date',
        'total_amount',
        'status',
    ];

    protected static function newFactory(): OrderFactory
    {
        return OrderFactory::new();
    }

    protected static function booted()
    {
        foreach (['created', 'updated', 'deleted'] as $event) {
            static::$event(fn () => Cache::tags(['orders'])->flush());
        }
    }
}
