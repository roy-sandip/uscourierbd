<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes([
    'register'  => false,
    'reset'     => false,
    'verify'    => false,
]);


//Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function(){
    
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('/shipments', ShipmentController::class);
    
    //Only Admin Routes
    Route::middleware(['can:admin'])->group(function(){
        Route::resource('/agents', AgentController::class);
        Route::resource('/services', ServiceController::class);
        Route::resource('/users', UserController::class);

    });

});


//pdf print routes
Route::prefix('print')->name('print.')->middleware(['auth'])->group(function(){
    Route::get('/shipments/booking-receipt/{id}', [PrintController::class, 'pdfController'])->name('shipments.receipt');
    Route::get('/shipments/transport-copy/{id}', [PrintController::class, 'pdfController'])->name('shipments.transportCopy');
    

});