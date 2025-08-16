<?php

use Illuminate\Support\Facades\Route;
use Modules\CRM\Http\Controllers\V1\CustomerController;

Route::middleware(['auth:admin,admin_token'])->group(function () {
    Route::apiResource('customers', CustomerController::class);
});