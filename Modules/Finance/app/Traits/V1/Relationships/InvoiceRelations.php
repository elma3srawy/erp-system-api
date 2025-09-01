<?php

namespace Modules\Finance\Traits\V1\Relationships;

use Modules\Finance\Models\InvoiceItems;

trait InvoiceRelations
{
    public function items()
    {
        return $this->hasMany(InvoiceItems::class);
    }
}
