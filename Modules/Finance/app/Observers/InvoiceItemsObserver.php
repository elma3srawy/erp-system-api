<?php

namespace Modules\Finance\Observers;

use Modules\Finance\Models\InvoiceItems;

class InvoiceItemsObserver
{
    /**
     * Handle the InvoiceItems "created" event.
     */
    public function created(InvoiceItems $invoiceitems): void {}

    /**
     * Handle the InvoiceItems "updated" event.
     */
    public function updated(InvoiceItems $invoiceitems): void {}

    /**
     * Handle the InvoiceItems "deleted" event.
     */
    public function deleted(InvoiceItems $invoiceitems): void {}

    /**
     * Handle the InvoiceItems "restored" event.
     */
    public function restored(InvoiceItems $invoiceitems): void {}

    /**
     * Handle the InvoiceItems "force deleted" event.
     */
    public function forceDeleted(InvoiceItems $invoiceitems): void {}
}
