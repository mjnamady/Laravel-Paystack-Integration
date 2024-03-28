<?php

use App\Http\Controllers\PaystackController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('callback', [PaystackController::class, 'CallBack'])->name('callback');
Route::get('success', [PaystackController::class, 'Success'])->name('success');
Route::get('cancel', [PaystackController::class, 'Cancel'])->name('cancel');