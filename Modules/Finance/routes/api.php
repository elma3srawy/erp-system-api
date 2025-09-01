<?php

use Illuminate\Support\Facades\Route;
use Modules\Finance\Http\Controllers\V1\InvoiceController;

Route::middleware(['auth:admin,admin_token'])->group(function () {
    Route::apiResource('invoices', InvoiceController::class)->names('invoices');
});
