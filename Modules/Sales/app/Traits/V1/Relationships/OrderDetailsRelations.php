<?php

namespace Modules\Sales\Traits\V1\Relationships;

use Modules\Sales\Models\Order;
use Modules\Inventory\Models\Product;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait OrderDetailsRelations 
{
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
