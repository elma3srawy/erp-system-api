<?php

use Illuminate\Support\Facades\Route;
use Modules\Sales\Http\Controllers\V1\OrderController;


Route::middleware(["auth:customer,customer_token"])->group(function()
{
    Route::apiResource('orders', OrderController::class)->only(['store', 'update'])->names('orders');
});

Route::middleware(["auth:customer,customer_token,admin,admin_token"])->group(function()
{
    Route::apiResource('orders', OrderController::class)->only(['destroy'])->names('orders');
});

Route::middleware(["auth:admin,admin_token"])->group(function()
{

    Route::apiResource('orders', OrderController::class)->only(['index', 'show'])->names('orders');

    Route::controller(OrderController::class)->group(function(){
        Route::put('order/change-status/{order}' , 'changeStatus')->name('change-status');
    });
});
