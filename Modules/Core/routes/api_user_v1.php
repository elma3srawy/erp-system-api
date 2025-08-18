<?php

use App\Http\Middleware\MustBeGuest;
use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\V1\Authentication\UserVerificationController;
use Modules\Core\Http\Controllers\V1\Authentication\UserAuthenticationController;


Route::middleware([MustBeGuest::class])->group(function(){
    
    Route::controller(UserAuthenticationController::class)->group(function(){
        Route::post('sign-in' , 'login')->name('login');
        Route::post('sign-up' , 'register')->name('register');
    });

});

Route::middleware(['auth:user'])->group(function(){

    Route::controller(UserVerificationController::class)->group(function(){

        Route::post('/email/verification-notification', 'sendVerificationEmail')
        ->middleware('throttle:6,1')->name('verification.send');

        Route::get('/email/verify/{id}/{hash}', 'verifyEmail')
        ->middleware('signed')->name('verification.verify');

    });

    Route::controller(UserAuthenticationController::class)->group(function(){
        Route::post('logout' , 'logout')->name('logout');
        Route::get('auth-user' , 'me')->name('me');
    });
    
    Route::middleware(['verified'])->group(function(){


    });
});
