<?php

use Illuminate\Support\Facades\Route;
use Modules\Inventory\Http\Controllers\API\V1\SectionController;
use Modules\Inventory\Http\Controllers\API\V1\CategoryController;
use Modules\Inventory\Http\Controllers\API\V1\SupplierController;


Route::middleware(['auth:admin,admin_token', 'verified'])->group(function () {
    Route::apiResource('suppliers', SupplierController::class)->names('suppliers');
    
    Route::apiResource('categories', CategoryController::class)->names('categories');
    Route::get('/categories/sections/all', [CategoryController::class, 'sections'])->name('categories.sections');
    
    Route::apiResource('sections', SectionController::class)->names('sections');
});
