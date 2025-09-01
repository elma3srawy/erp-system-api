<?php

namespace Modules\Finance\Services\V1;

use Modules\Finance\Models\Invoice;
use Modules\Finance\Interfaces\Services\V1\InvoiceInterface;

class InvoiceService implements InvoiceInterface
{
    public function create(array $data): Invoice {
        $invoice = Invoice::create($data['invoice']);
        $this->storeItems($invoice, $data['invoice_details']);
        return $invoice;
    }
    public function update(Invoice $invoice ,array $data): Invoice {
        $invoice->update($data['invoice']);
        $invoice->items()->delete();
        $this->storeItems($invoice, $data['invoice_details']);
        return $invoice;
    }
    public function delete(Invoice $invoice): bool {
        return $invoice->delete();
    }

    private function storeItems(Invoice $invoice, $data)
    {
        $invoice->items()->createMany($data);
    }
}
