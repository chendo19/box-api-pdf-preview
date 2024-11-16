<?php

use App\Http\Controllers\TokenController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('pdf');
});


Route::get('/get-code', [TokenController::class, 'getCode']);
Route::get('/get-token', [TokenController::class, 'getToken']);
Route::get('/get-new-box-api-token', [TokenController::class, 'getNewBoxApiToken']);
