<?php

namespace Modules\Inventory\Traits\V1\Relationships;

use Modules\Inventory\Models\Section;
use Modules\Inventory\Models\Supplier;

trait ProductRelations
{
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class,"products_suppliers");
    }
}
