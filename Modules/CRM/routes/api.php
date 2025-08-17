<?php

use Illuminate\Support\Facades\Route;
use Modules\CRM\Http\Controllers\V1\CustomerController;
use Modules\CRM\Http\Controllers\V1\CustomerAuthController;

Route::middleware(['auth:admin,admin_token'])->group(function () {
    Route::apiResource('customers', CustomerController::class);
});

Route::post('customer/login' , [CustomerAuthController::class , 'login']);      