<?php

use Illuminate\Support\Facades\Route;
use Modules\Inventory\Http\Controllers\API\V1\SectionController;
use Modules\Inventory\Http\Controllers\API\V1\CategoryController;
use Modules\Inventory\Http\Controllers\API\V1\SupplierController;
use Modules\Inventory\Http\Controllers\API\V1\ProductController;

Route::middleware(['auth:admin,admin_token', 'verified'])->group(function () {
    Route::apiResource('suppliers', SupplierController::class)->names('suppliers');
    
    Route::apiResource('categories', CategoryController::class)->names('categories');
    Route::get('/categories/sections/all', [CategoryController::class, 'sections'])->name('categories.sections');
    
    Route::apiResource('sections', SectionController::class)->names('sections');
    
    Route::apiResource('products', ProductController::class)->names('products');
    Route::controller(ProductController::class)->group(function () {
        Route::put('/product/{product}/suppliers/update', 'updateSuppliers')->name('products.suppliers.update');
    });
});
