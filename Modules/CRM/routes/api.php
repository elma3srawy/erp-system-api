<?php

use App\Http\Middleware\MustBeGuest;
use Illuminate\Support\Facades\Route;
use Modules\CRM\Http\Controllers\V1\CustomerController;
use Modules\CRM\Http\Controllers\V1\CustomerAuthController;

Route::middleware(['auth:admin,admin_token'])->group(function () {
    Route::apiResource('customers', CustomerController::class);
});
Route::middleware([MustBeGuest::class])->group(function () {
    Route::post('customer/login' , [CustomerAuthController::class , 'login']);      
    Route::post('customer/create-token' , [CustomerAuthController::class , 'createToken']);      
});