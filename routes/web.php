<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;

Route::get('/', function () {
    return view('welcome');
});

// Route::post('/cart', [CartController::class, 'createCart']);
// Route::post('/cart/items', [CartController::class, 'addToCart']);
// Route::get('/cart', [CartController::class, 'getCart']);
// Route::put('/cart/items/{itemId}', [CartController::class, 'updateCartItem']);
// Route::delete('/cart/items/{itemId}', [CartController::class, 'removeCartItem']);

Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::post('/users', [UserController::class, 'store']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);
Route::post('login', [UserController::class, 'login']);
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/cart', [CartController::class, 'createCart']);
    Route::post('/cart/items', [CartController::class, 'addToCart']);
    Route::get('/cart', [CartController::class, 'getCart']);
    Route::post('/cart/items/{itemId}', [CartController::class, 'updateCartItem']);
    Route::delete('/cart/items/{itemId}', [CartController::class, 'removeCartItem']);
    Route::get('logout', [UserController::class, 'logout']);
    Route::post('/checkout', [OrderController::class, 'checkout']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{orderId}', [OrderController::class, 'show']);
});
