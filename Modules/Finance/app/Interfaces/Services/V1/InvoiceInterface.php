<?php

namespace Modules\Finance\Interfaces\Services\V1;

use Modules\Finance\Models\Invoice;

interface InvoiceInterface 
{
    public function create(array $data): Invoice;
    public function update(Invoice $invoice,  array $data): Invoice;
    public function delete(Invoice $invoice): bool;
}
