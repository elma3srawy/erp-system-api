<?php

namespace Modules\Inventory\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Inventory\Traits\V1\Relationships\Relationships\SectionRelations;

class Section extends Model
{
    use HasFactory;
    use SectionRelations;

    protected $fillable = [
        'name',
        'category_id',
    ];

    protected static function newFactory()
    {
        return \Modules\Inventory\Database\Factories\SectionFactory::new();
    }
    protected static function booted()
    {
        foreach (['created', 'updated', 'deleted'] as $event) {
            static::$event(fn () => Cache::tags(['sections', 'categories-with-sections' , 'products'])->flush());
        }
    }
}
