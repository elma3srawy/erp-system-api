<?php

use Illuminate\Support\Facades\Route;
use Modules\Inventory\Http\Controllers\API\V1\SupplierController;





Route::middleware(['auth:admin,admin_token' , 'verified'])->group(function () {
    Route::apiResource('suppliers', SupplierController::class)->names('suppliers');
});
