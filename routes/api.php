<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FoodApiController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::get('/foods', [FoodApiController::class, 'index']);
Route::get('/foods/{id}', [FoodApiController::class, 'show']);
Route::post('/foods', [FoodApiController::class, 'store']);
Route::put('/foods/{id}', [FoodApiController::class, 'update']);
Route::delete('/foods/{id}', [FoodApiController::class, 'destroy']);
