<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdmsController;

Route::get('/', function () {
    return view('welcome');
});

// ADMS Server Routes
Route::prefix('iclock')->group(function () {
    Route::any('cdata', [AdmsController::class, 'cdata']);
    Route::any('getrequest', [AdmsController::class, 'getrequest']);
    Route::any('devicecmd', [AdmsController::class, 'devicecmd']);
    Route::any('registry', [AdmsController::class, 'registry']);
    Route::any('ping', [AdmsController::class, 'ping']);
});
