<?php

namespace Modules\Finance\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Finance\Database\Factories\InvoiceDetailFactory;
use Modules\Finance\Observers\InvoiceItemsObserver;
use Modules\Finance\Traits\V1\Relationships\InvoiceItemsRelations;

#[ObservedBy(InvoiceItemsObserver::class)]
class InvoiceItems extends Model
{
    use HasFactory, InvoiceItemsRelations;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        "product_id",
        "quantity",
        "price",
        "tax",
        "discount",
    ];

    protected static function newFactory(): InvoiceDetailFactory
    {
        return InvoiceDetailFactory::new();
    }
}
