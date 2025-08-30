<?php

namespace Modules\Inventory\Traits\V1\Relationships;

use Modules\Inventory\Models\Category;

trait SectionRelations
{
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
