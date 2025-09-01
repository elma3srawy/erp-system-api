<?php

namespace Modules\Finance\Observers;

use Illuminate\Support\Facades\Cache;
use Modules\Finance\Models\Invoice;

class InvoiceObserver
{
    /**
     * Handle the Invoice "created" event.
     */
    public function created(Invoice $invoice): void {}

    /**
     * Handle the Invoice "updated" event.
     */
    public function updated(Invoice $invoice): void {}

    /**
     * Handle the Invoice "deleted" event.
     */
    public function deleted(Invoice $invoice): void 
    {
        Cache::tags('invoices')->flush();
    }

    /**
     * Handle the Invoice "restored" event.
     */
    public function restored(Invoice $invoice): void {}

    /**
     * Handle the Invoice "force deleted" event.
     */
    public function forceDeleted(Invoice $invoice): void {}

    public function saving(Invoice $invoice)
    {
        $invoice->total_amount = $invoice->subtotal + $invoice->tax_amount - $invoice->discount_amount;
        $invoice->remaining_amount = $invoice->total_amount - $invoice->paid_amount;
    }
    public function saved(Invoice $invoice)
    {
        Cache::tags('invoices')->flush();
    }
}
