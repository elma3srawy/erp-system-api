<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\V1\Authentication\AdminAuthTokenController;
use Modules\Core\Http\Controllers\V1\Authentication\AdminVerificationController;
use Modules\Core\Http\Controllers\V1\Authentication\AdminAuthenticationController;


Route::controller(AdminAuthenticationController::class)->group(function(){
    Route::post('sign-in' , 'login')->name('login');
    Route::post('sign-up' , 'register')->name('register');
});
Route::controller(AdminAuthTokenController::class)->group(function(){
    Route::post('/delete-token', 'delete');
});

Route::middleware(['auth:admin,admin_token'])->group(function(){

    Route::controller(AdminVerificationController::class)->group(function(){

        Route::post('/email/verification-notification', 'sendVerificationEmail')
        ->middleware('throttle:6,1')->name('verification.send');

        Route::get('/email/verify/{id}/{hash}', 'verifyEmail')
        ->middleware('signed')->name('verification.verify');
    });

    Route::controller(AdminAuthenticationController::class)->group(function(){
        Route::post('logout' , 'logout')->name('logout');
        Route::get('auth-admin' , 'me')->name('me');
    });

    Route::controller(AdminAuthTokenController::class)->group(function(){
        Route::post('/delete-token', 'delete');
    });

    Route::middleware(['verified'])->group(function(){

    });

});
