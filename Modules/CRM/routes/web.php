<?php

use Illuminate\Support\Facades\Route;
use Modules\CRM\Http\Controllers\CRMController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('crms', CRMController::class)->names('crm');
});
