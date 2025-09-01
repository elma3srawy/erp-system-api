<?php

namespace Modules\Finance\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Finance\Database\Factories\InvoiceFactory;
use Modules\Finance\Observers\InvoiceObserver;
use Modules\Finance\Traits\V1\Relationships\InvoiceRelations;

#[ObservedBy(InvoiceObserver::class)]
class Invoice extends Model
{
    use HasFactory, InvoiceRelations;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'order_id',
        'due_date',
        'status',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'total_amount',
        'paid_amount',
        'remaining_amount',
    ];

    protected static function newFactory(): InvoiceFactory
    {
        return InvoiceFactory::new();
    }

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }
}
