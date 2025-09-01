<?php

namespace Modules\Finance\Traits\V1\Relationships;

use Modules\Finance\Models\Invoice;
use Modules\Inventory\Models\Product;

trait InvoiceItemsRelations
{
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
