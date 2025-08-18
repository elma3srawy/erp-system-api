<?php

namespace Modules\Inventory\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    protected static function newFactory()
    {
        return \Modules\Inventory\Database\Factories\CategoryFactory::new();
    }
    protected static function booted()
    {
        foreach (['created', 'updated', 'deleted'] as $event) {
            static::$event(fn () => Cache::tags('categories')->flush());
        }
    }
}
