<?php

use App\Http\Controllers\Api\V1\DeliveryBoyController;
use App\Http\Controllers\Api\V1\EventsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\HomepageController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Backend\DeliveryController;
use App\Http\Controllers\Api\V1\AuthOtpApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ✅ Public route (homepage data without login)
Route::get('/v1/homepage/data', [HomepageController::class, 'getHomepageData']);


// ✅ Protected routes (require login)
Route::middleware('auth:api')->group(function () {

    Route::prefix('v1/products')->group(function () {
        Route::get('/', [ProductController::class, 'getAllProducts']);
        Route::get('/search', [ProductController::class, 'searchProducts']);
        Route::get('/{slug}', [ProductController::class, 'getProductDetails']);
        Route::get('/category/{categoryId}', [ProductController::class, 'getProductsByCategory']);
        Route::get('/subcategory/{subcategoryId}', [ProductController::class, 'getProductsBySubcategory']);
    });

    Route::prefix('v1/categories')->group(function () {
        Route::get('/', [HomepageController::class, 'getAllCategoriesWithSubcategories']);
        Route::get('/{id}/subcategories', [HomepageController::class, 'getSubcategoriesByCategoryId']);
    });

    Route::prefix('v1/events')->group(function () {
        Route::get('/', [EventsController::class, 'getAllEvents']);
        Route::get('/categories', [EventsController::class, 'getAllCategories']);
        Route::get('/{categorySlug}', [EventsController::class, 'getEventsByCategorySlug']);
        Route::get('/{categorySlug}/{eventSlug}', [EventsController::class, 'getEventDetails']);
        Route::get('/{categorySlug}/{eventSlug}/comments', [EventsController::class, 'getCommentsByEventSlug']);
    });

    Route::prefix('v1/delivery-boy')->group(function () {
        Route::post('order-status', [DeliveryBoyController::class, 'changeOrderStatus']);
        Route::post('orders', [DeliveryBoyController::class, 'getOrders']);
    });

    Route::prefix('v1/delivery')->group(function () {
        Route::get('locations', [HomepageController::class, 'getDeliveryLocations']);
    });

    // ✅ Authenticated user APIs
    Route::prefix('v1/auth')->group(function () {
        Route::get('/me', [AuthOtpApiController::class, 'me']);
        Route::post('/logout', [AuthOtpApiController::class, 'logout']);
    });
});

// ✅ Auth routes (public, no middleware)
Route::prefix('v1/auth')->group(function () {
    Route::post('/generate-otp', [AuthOtpApiController::class, 'generateOtp']);
    Route::post('/verify-otp', [AuthOtpApiController::class, 'verifyOtp']);
});
