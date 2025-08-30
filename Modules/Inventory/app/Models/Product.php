<?php

namespace Modules\Inventory\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Inventory\Traits\V1\Relationships\ProductRelations;


class Product extends Model
{
    use HasFactory;
    use ProductRelations;

    protected $fillable = [
        'name',
        'section_id',
        'sku',
        'quantity',
        'price',
    ];

    protected static function newFactory()
    {
        return \Modules\Inventory\Database\Factories\ProductFactory::new();
    }
    protected static function booted()
    {
        foreach (['created', 'updated', 'deleted'] as $event) {
            static::$event(fn () => Cache::tags(['products' , "orders"])->flush());
        }
    }
}
