<?php

namespace Modules\Inventory\Traits\V1\Relationships\Relationships;

use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\PurchaseOrder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait PurchaseOrderDetailRelations
{
    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
