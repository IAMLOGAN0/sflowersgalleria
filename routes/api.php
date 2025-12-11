<?php

use App\Http\Controllers\Api\V1\DeliveryBoyController;
use App\Http\Controllers\Api\V1\EventsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\HomepageController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\AuthOtpApiController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\OrderController;
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

Route::get('/v1/flowers/random', [HomepageController::class, 'getRandomFlowers']);


Route::prefix('v1/products')->group(function () {
    Route::get('/', [ProductController::class, 'getAllProducts']);
    Route::get('/search', [ProductController::class, 'searchProducts']);
    Route::get('/{slug}', [ProductController::class, 'getProductDetails']);
    Route::get('/category/{categoryId}', [ProductController::class, 'getProductsByCategory']);
    Route::get('/subcategory/{subcategoryId}', [ProductController::class, 'getProductsBySubcategory']);

    Route::get('/random/all', [ProductController::class, 'getRandomProducts']);
    Route::get('/category/{categoryId}/random', [ProductController::class, 'getRandomProductsByCategory']);
    Route::get('/global-search', [ProductController::class, 'globalSearch']);
});

Route::prefix('v1/categories')->group(function () {
    Route::get('/', [HomepageController::class, 'getAllCategoriesWithSubcategories']);
    Route::get('/{id}/subcategories', [HomepageController::class, 'getSubcategoriesByCategoryId']);
});

Route::prefix('v1/delivery')->group(function () {
    Route::get('locations', [HomepageController::class, 'getDeliveryLocations']);
});

Route::prefix('v1/events')->group(function () {
    Route::get('/', [EventsController::class, 'getAllEvents']);
    Route::get('/categories', [EventsController::class, 'getAllCategories']);
    Route::get('/{categorySlug}', [EventsController::class, 'getEventsByCategorySlug']);
    Route::get('/{categorySlug}/{eventSlug}', [EventsController::class, 'getEventDetails']);
    Route::get('/{categorySlug}/{eventSlug}/comments', [EventsController::class, 'getCommentsByEventSlug']);
});


// ✅ Protected routes (require login)
Route::middleware('auth:api')->group(function () {

    Route::prefix('v1/cart')->group(function () {
        Route::post('/add', [CartController::class, 'addToCart']);
        Route::get('/', [CartController::class, 'getCart']);
        Route::post('/update/{id}', [CartController::class, 'updateQuantity']);
        Route::delete('/remove/{id}', [CartController::class, 'removeItem']);
        Route::delete('/clear', [CartController::class, 'clearCart']);
    });

    Route::prefix('v1/profile')->group(function () {
        Route::get('/', [ProfileController::class, 'getProfile']); // fetch profile
        Route::post('/update', [ProfileController::class, 'updateProfile']); // update profile
    });

    Route::prefix('v1/user')->group(function () {
        Route::get('/addresses', [ProfileController::class, 'getAddresses']);
        Route::post('/addresses', [ProfileController::class, 'addAddress']);
        Route::get('/address/{id}', [ProfileController::class, 'getAddress']);
        Route::post('/addresses/{id}', [ProfileController::class, 'updateAddress']);
        Route::delete('/addresses/{id}', [ProfileController::class, 'deleteAddress']);
    });

     // 🛒 Order Routes
    Route::prefix('v1/order')->group(function () {
        Route::post('/create-temp', [OrderController::class, 'createTempOrder']);
        Route::post('/confirm', [OrderController::class, 'confirmOrder']);

        // Route::post('/store', [OrderController::class, 'storeOrder']);
        Route::get('/list', [OrderController::class, 'orderList']);
        Route::get('/detail/{id}', [OrderController::class, 'orderDetail']);
    });
    
    
    
Route::get('v1/all-coupon', [CartController::class, 'allCoupon'])->name('all-coupon');
Route::post('v1/apply-coupon', [CartController::class, 'couponCalculation'])->name('coupon-calculation');




    Route::prefix('v1/delivery-boy')->group(function () {
        Route::post('order-status', [DeliveryBoyController::class, 'changeOrderStatus']);
        Route::post('orders', [DeliveryBoyController::class, 'getOrders']);
    });

    // ✅ Authenticated user APIs
    Route::prefix('v1/auth')->group(function () {
        Route::post('/refresh', [AuthOtpApiController::class, 'refresh']);
        Route::get('/me', [AuthOtpApiController::class, 'me']);
        Route::post('/logout', [AuthOtpApiController::class, 'logout']);
    });
});

// ✅ Auth routes (public, no middleware)
Route::prefix('v1/auth')->group(function () {
    Route::post('/generate-otp', [AuthOtpApiController::class, 'generateOtp']);
    Route::post('/resend-otp', [AuthOtpApiController::class, 'resendOtp']);
    Route::post('/verify-otp', [AuthOtpApiController::class, 'verifyOtp']);
});
