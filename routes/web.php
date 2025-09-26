<?php

use App\Http\Controllers\PrintController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShipmentController;


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

});


//pdf print routes
Route::prefix('print')->name('print.')->middleware(['auth'])->group(function(){
    Route::get('/shipments/booking-receipt/{id}', [PrintController::class, 'pdfController'])->name('shipments.receipt');
    Route::get('/shipments/transport-copy/{id}', [PrintController::class, 'pdfController'])->name('shipments.transportCopy');
    

});