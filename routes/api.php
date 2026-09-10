<?php


Route::group(['prefix' => 'v1'], function () {

    Route::get('helth_check', function () {
        return response()->json([
            'data' => [],
            'success' => true,
            'status' => 200,
            'message' => 'API is healthy'
        ]);
    });


    Route::post('coupon-apply', [\App\Http\Controllers\Api\ApiCheckoutController::class, 'apply_coupon_code']);
    Route::post('coupon-remove', [\App\Http\Controllers\Api\ApiCheckoutController::class, 'remove_coupon_code']);

    Route::get('home', [\App\Http\Controllers\Api\ApiHomeController::class, 'index']);
    Route::get('collections', [\App\Http\Controllers\Api\ApiHomeController::class, 'collections']);
    Route::post('newsletter', [\App\Http\Controllers\Api\ApiHomeController::class, 'newsletter']);
    Route::get('products', [\App\Http\Controllers\Api\ApiProductController::class, 'index']);
    Route::get('products/search', [\App\Http\Controllers\Api\ApiProductController::class, 'search']);
    Route::get('products/{identifier}', [\App\Http\Controllers\Api\ApiProductController::class, 'productDetails']);
    Route::get('categories/{slug}', [\App\Http\Controllers\Api\ApiProductController::class, 'categoryProducts']);
    Route::get('search', [\App\Http\Controllers\Api\ApiHomeController::class, 'searchSuggestions']);



    // in progress

    Route::get('blogs', [\App\Http\Controllers\Api\ApiFrontendController::class, 'blogs']);
    Route::get('blogs/{slug}', [\App\Http\Controllers\Api\ApiFrontendController::class, 'singleBlog']);
    Route::get('about-us', [\App\Http\Controllers\Api\ApiFrontendController::class, 'aboutUs']);
    Route::get('contact-us', [\App\Http\Controllers\Api\ApiFrontendController::class, 'contactUs']);
    Route::get('integration', [\App\Http\Controllers\Api\ApiFrontendController::class, 'integration']);
    Route::post('contact-store', [\App\Http\Controllers\Api\ApiFrontendController::class, 'contactStore']);
    Route::get('settings', [\App\Http\Controllers\Api\ApiFrontendController::class, 'settings']);
    Route::get('product/{slug}/show', [\App\Http\Controllers\Api\ApiFrontendController::class, 'productShow']);




    // in progress
    Route::prefix('wishlist')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\ApiWishlistController::class, 'index']);
        Route::post('/toggle', [\App\Http\Controllers\Api\ApiWishlistController::class, 'toggle']);
        Route::delete('/{productId}', [\App\Http\Controllers\Api\ApiWishlistController::class, 'destroy']);
    });


    // done
    Route::prefix('cart')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\ApiCartController::class, 'index']);
        Route::post('/shipping', [\App\Http\Controllers\Api\ApiCartController::class, 'shipping_areas']);
        Route::post('/add', [\App\Http\Controllers\Api\ApiCartController::class, 'add']);
        Route::put('/update/{id}', [App\Http\Controllers\Api\ApiCartController::class, 'update']);
        Route::delete('/remove/{id}', [\App\Http\Controllers\Api\ApiCartController::class, 'remove']);
        Route::delete('/clear', [\App\Http\Controllers\Api\ApiCartController::class, 'clear']);
        Route::get('/related-products', [\App\Http\Controllers\Api\ApiCartController::class, 'cartRelated']);
    });


    // done
    Route::prefix('orders')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\ApiOrderController::class, 'index']);
        Route::get('/order-tracking/{code}', [\App\Http\Controllers\Api\ApiOrderController::class, 'orderTracking']);
        Route::get('/{code}', [\App\Http\Controllers\Api\ApiOrderController::class, 'show']);
        Route::post('/place', [\App\Http\Controllers\Api\ApiOrderController::class, 'place']);
        Route::post('incomplete-order', [App\Http\Controllers\Api\ApiOrderController::class, 'incompleteOrder']);
        Route::get('invoice/{id}', [App\Http\Controllers\Api\ApiOrderController::class, 'invoice']);
    });

    Route::post('review-store', [\App\Http\Controllers\Api\ApiReviewController::class, 'reviewStore']);

    //done
    Route::get('business-settings', [\App\Http\Controllers\Api\BusinessSettingController::class, 'businessSettings']);
    Route::get('pages', [\App\Http\Controllers\Api\BusinessSettingController::class, 'policy']);

    Route::prefix('auth')->group(function () {
        Route::post('register', [\App\Http\Controllers\Api\AuthenticationController::class, 'register']);
        Route::post('login', [\App\Http\Controllers\Api\AuthenticationController::class, 'login']);
        Route::get('oauth', [\App\Http\Controllers\Api\AuthenticationController::class, 'oauth']);
        Route::middleware(['auth:sanctum'])->group(function () {
            Route::post('logout', [\App\Http\Controllers\Api\AuthenticationController::class, 'logout']);
            Route::get('profile', [\App\Http\Controllers\Api\AuthenticationController::class, 'profile']);
            Route::get('dashboard', [\App\Http\Controllers\Api\AuthenticationController::class, 'dashboard']);
            Route::put('change-password', [\App\Http\Controllers\Api\AuthenticationController::class, 'changePassword']);
            Route::put('profile-update', [\App\Http\Controllers\Api\AuthenticationController::class, 'profileUpdate']);

            // Profile Order
            Route::post('review-store', [\App\Http\Controllers\Api\ApiReviewController::class, 'reviewStore']);
            Route::get('purchase-history', [\App\Http\Controllers\Api\ApiReviewController::class, 'purchaseHistory']);
        });
    });


    // Dropshipper API routes


    Route::prefix('campaigns')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\ApiCampaignController::class, 'list']);
        Route::get('/{slug}', [\App\Http\Controllers\Api\ApiCampaignController::class, 'details']);
    });

    Route::prefix('about')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\ApiAboutController::class, 'about']);
    });

    Route::prefix('faqs')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\ApiFaqController::class, 'faqs']);
    });


    Route::prefix('landing-page')->group(function () {
        Route::get('/{slug}', [\App\Http\Controllers\Api\ApiLandingPageController::class, 'landingpage']);
    });

});
