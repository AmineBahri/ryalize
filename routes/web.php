<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Illuminate\Contracts\View\View;

Route::get('/', function ():View {
    return view('welcome');
});

Route::controller(UserController::class)->group(function () {
    Route::get('users/{id}',  'show');
    Route::get('users/{id}/transactions', 'fetchTransactions')->name('users.transactions');
});



