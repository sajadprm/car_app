<?php

use App\Http\Controllers\api\BaseController;
use App\Http\Controllers\api\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});
Route::get('test', [RegisterController::class, 'test']);
// Route::get('test', [BaseController::class, 'test']);

         
// Route::middleware('auth:sanctum')->group( function () {
//     Route::resource('cars', CarController::class);
//     Route::resource('requests', RequestController::class);
// });
