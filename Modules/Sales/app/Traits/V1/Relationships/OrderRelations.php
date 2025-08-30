<?php

namespace Modules\Sales\Traits\V1\Relationships;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\CRM\Models\Customer;
use Modules\Sales\Models\OrderDetails;

trait OrderRelations 
{
    public function details(): HasMany
    {
        return $this->hasMany(OrderDetails::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
