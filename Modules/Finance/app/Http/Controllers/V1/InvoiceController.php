<?php

namespace Modules\Finance\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Modules\Finance\Models\Invoice;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\Finance\Http\Requests\V1\StoreInvoiceRequest;
use Modules\Finance\Http\Requests\V1\UpdateInvoiceRequest;
use Modules\Finance\Interfaces\Services\V1\InvoiceInterface;
use Modules\Finance\Transformers\V1\InvoiceResource;

class InvoiceController extends Controller
{
    use ResponseTrait;

    public function __construct(protected InvoiceInterface $invoice) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Cache::tags('invoices')->remember('invoice-page-'.request('page', 1), 60*60, function () {
            return Invoice::with('items')->latest()->paginate(PAGINATION)->toResourceCollection(InvoiceResource::class)->resource;
        });
        return $this->success($invoices , 'Invoices retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoiceRequest $request) 
    {
        DB::beginTransaction();
        try {
            $invoice = $this->invoice->create($request->getData());
            DB::commit();
            return $this->created($invoice , "Invoice Created successfully");
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->serverError();
        }
    }

    /**
     * Show the specified resource.
     */
    public function show(Invoice $invoice)
    {
        return $this->success($invoice->toResource(InvoiceResource::class) ,"Invoice retrieved successfully.");
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        DB::beginTransaction();
        try {
            $invoice = $this->invoice->update($invoice, $request->getData())->refresh();
            DB::commit();
            return $this->updated($invoice , "Invoice Updated successfully");
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->serverError();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        DB::beginTransaction();
        try {
            $invoice = $this->invoice->delete($invoice);
            DB::commit();
            return $this->deleted("Invoice Deleted successfully");
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->serverError();
        }
    }
}
