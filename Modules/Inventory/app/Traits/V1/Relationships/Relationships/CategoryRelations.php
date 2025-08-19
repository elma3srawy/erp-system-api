<?php

namespace Modules\Inventory\Traits\V1\Relationships\Relationships;

use Modules\Inventory\Models\Section;

trait CategoryRelations
{
    public function sections()
    {
        return $this->hasMany(Section::class);
    }
}
