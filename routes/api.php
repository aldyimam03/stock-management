<?php

use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\StockApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('products', [ProductApiController::class, 'index']);
    Route::get('products/{product}', [ProductApiController::class, 'show']);

    Route::get('stock/balances', [StockApiController::class, 'balances']);
    Route::get('stock/movements', [StockApiController::class, 'movements']);
});
