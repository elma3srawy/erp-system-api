<?php

namespace Modules\Inventory\Traits\V1\Relationships;

use Modules\Inventory\Models\Supplier;
use Modules\Inventory\Models\PurchaseOrderDetail;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait PurchaseOrderRelations
{
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(PurchaseOrderDetail::class);
    }
}
