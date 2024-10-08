<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PayMobController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register',[ AuthController::class,'register']);
Route::post('/login',[ AuthController::class,'login']);
Route::post('/logout',[ AuthController::class,'logout'])->middleware('auth:sanctum');

Route::prefix('books')->group(function () {
    Route::get('/', [BookController::class, 'index']);
    Route::post('/', [BookController::class, 'store'])->middleware('auth:sanctum');
    Route::get('/{slug}', [BookController::class, 'show']);
    Route::put('/{id}', [BookController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/{id}', [BookController::class, 'destroy'])->middleware('auth:sanctum');
});

Route::post('/cart/add/{bookId}', [CartController::class, 'add'])->middleware('auth:sanctum');
Route::get('/cart', [CartController::class, 'index'])->middleware('auth:sanctum');
Route::delete('/cart/{bookId}', [CartController::class, 'remove'])->middleware('auth:sanctum');

Route::post('/orders/place', [OrderController::class, 'placeOrder'])->middleware('auth:sanctum');

Route::post('payment/',[PayMobController::class,'pay'])->middleware('auth:sanctum');

Route::get('callback/',[PayMobController::class,'callback']);