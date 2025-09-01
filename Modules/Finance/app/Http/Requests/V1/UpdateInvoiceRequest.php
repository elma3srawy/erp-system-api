<?php

namespace Modules\Finance\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Sales\Models\Order;

class UpdateInvoiceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $invoice = $this->route('invoice');

        return [
            "order_id" => ["required","integer","exists:orders,id"],
            "due_date" => ["required", "date"],
            "status" => ["required", "in:draft,sent,paid,overdue"],
            "paid_amount" => ["required","numeric","min:0","max:".$invoice->total_amount],
        ];
    }

    /** 
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function getData()
    {
        $validated = $this->validated();
        $order = Order::with('details.product')->find($validated['order_id']);
        return [
            "invoice" => [
                "order_id" => $validated['order_id'],
                "due_date" => $validated['due_date'],
                "status" => $validated['status'],
                "subtotal" => $order->total_amount,
                "paid_amount" => $validated['paid_amount'],
            ],
            "invoice_details" =>    
               array_map(function ($detail) {
                   return [
                       "product_id" => $detail['product_id'],
                       "quantity" => $detail['quantity'],
                       "price" => $detail['unit_price']
                   ];
               }, $order->details->toArray())
        ];
    }
}
