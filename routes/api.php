<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\HomepageController;
use App\Http\Controllers\Api\V1\ProductController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/v1/homepage/data', [HomepageController::class, 'getHomepageData']);

Route::prefix('v1/products')->group(function () {
    Route::get('/', [ProductController::class, 'getAllProducts']);
    Route::get('/search', [ProductController::class, 'searchProducts']);
    Route::get('/{slug}', [ProductController::class, 'getProductDetails']);
});
