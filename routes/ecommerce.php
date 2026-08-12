<?php

use App\Http\Controllers\Admin\IncompleteOrderController;
use App\Http\Controllers\Admin\LandingpageController;
use App\Http\Controllers\Admin\ManualOrderController;
use App\Http\Controllers\Admin\OrderController;
use Illuminate\Support\Facades\Route;


Route::prefix('category')->name('dropshipping-category.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DropshippingCategoryController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\Admin\DropshippingCategoryController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\Admin\DropshippingCategoryController::class, 'store'])->name('store');
    Route::get('/{category}/edit', [\App\Http\Controllers\Admin\DropshippingCategoryController::class, 'edit'])->name('edit');
    Route::put('/{category}', [\App\Http\Controllers\Admin\DropshippingCategoryController::class, 'update'])->name('update');
    Route::delete('/{id}', [\App\Http\Controllers\Admin\DropshippingCategoryController::class, 'destroy'])->name('destroy');
    Route::post('/team/sort', [\App\Http\Controllers\Admin\DropshippingCategoryController::class, 'sort'])->name('sort');
});


Route::prefix('subcategory')->name('subcategory.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\SubCategoryController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\Admin\SubCategoryController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\Admin\SubCategoryController::class, 'store'])->name('store');
    Route::get('/{category}/edit', [\App\Http\Controllers\Admin\SubCategoryController::class, 'edit'])->name('edit');
    Route::put('/{category}', [\App\Http\Controllers\Admin\SubCategoryController::class, 'update'])->name('update');
    Route::delete('/{id}', [\App\Http\Controllers\Admin\SubCategoryController::class, 'destroy'])->name('destroy');
});



Route::prefix('brand')->name('brand.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\BrandController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\Admin\BrandController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\Admin\BrandController::class, 'store'])->name('store');
    Route::get('/{category}/edit', [\App\Http\Controllers\Admin\BrandController::class, 'edit'])->name('edit');
    Route::put('/{category}', [\App\Http\Controllers\Admin\BrandController::class, 'update'])->name('update');
    Route::delete('/{id}', [\App\Http\Controllers\Admin\BrandController::class, 'destroy'])->name('destroy');
    Route::post('/team/sort', [\App\Http\Controllers\Admin\BrandController::class, 'sort'])->name('sort');
});



Route::prefix('sectionconfig')->name('sectionconfig.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\SectionConfigController::class, 'index'])->name('index');
    Route::post('/team/sort', [\App\Http\Controllers\Admin\SectionConfigController::class, 'sort'])->name('sort');
    Route::post('/sectionconfig/toggle-status', [\App\Http\Controllers\Admin\SectionConfigController::class, 'toggleStatus'])->name('toggleStatus');
    Route::post('/title-update', [\App\Http\Controllers\Admin\SectionConfigController::class, 'updateTitle'])->name('updateTitle');
});


Route::prefix('payment-system')->name('paymentsystem.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\PaymentSystemController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\Admin\PaymentSystemController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\Admin\PaymentSystemController::class, 'store'])->name('store');
    Route::get('/{category}/edit', [\App\Http\Controllers\Admin\PaymentSystemController::class, 'edit'])->name('edit');
    Route::put('/{category}', [\App\Http\Controllers\Admin\PaymentSystemController::class, 'update'])->name('update');
    Route::delete('/{id}', [\App\Http\Controllers\Admin\PaymentSystemController::class, 'destroy'])->name('destroy');
});






Route::prefix('color')->name('colors.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\ColorController::class, 'index'])->name('index');
    Route::post('/store', [\App\Http\Controllers\Admin\ColorController::class, 'store'])->name('store');
    Route::get('/edit/{color}', [\App\Http\Controllers\Admin\ColorController::class, 'edit'])->name('edit');
    Route::post('/update/{color}', [\App\Http\Controllers\Admin\ColorController::class, 'update'])->name('update');
    Route::delete('/destroy/{id}', [\App\Http\Controllers\Admin\ColorController::class, 'destroy'])->name('destroy');
});


Route::prefix('product')->name('products.')->group(function () {
    // Resource routes
    Route::get('/', [\App\Http\Controllers\Admin\ProductController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\Admin\ProductController::class, 'create'])->name('create');
    Route::post('/store', [\App\Http\Controllers\Admin\ProductController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [\App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('edit');
    Route::put('/{id}', [\App\Http\Controllers\Admin\ProductController::class, 'update'])->name('update');
    Route::delete('/{id}', [\App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('destroy');
    Route::delete('/delete-all/fasjgdfasfhrwer/werwerwe', [\App\Http\Controllers\Admin\ProductController::class, 'deleteAll'])->name('delete-all');
    Route::get('/{id}', [\App\Http\Controllers\Admin\ProductController::class, 'show'])->name('show');
    Route::post('/update-status', [\App\Http\Controllers\Admin\ProductController::class, 'updateStatus'])->name('update-status');

    // Bulk Actions
    Route::post('/bulk-delete', [\App\Http\Controllers\Admin\ProductController::class, 'bulkDelete'])->name('bulk-delete');
    Route::post('/bulk-update-status', [\App\Http\Controllers\Admin\ProductController::class, 'bulkUpdateStatus'])->name('bulk-update-status');
    Route::post('/bulk-update-featured', [\App\Http\Controllers\Admin\ProductController::class, 'bulkUpdateFeatured'])->name('bulk-update-featured');

    // AJAX routes for product creation
    Route::post('/subcategories/get', [\App\Http\Controllers\Admin\ProductController::class, 'getSubcategories'])->name('subcategories.get.json');
    Route::post('/add-more-choice-option', [\App\Http\Controllers\Admin\ProductController::class, 'addMoreChoiceOption'])->name('add-more-choice-option');
    Route::post('/sku-combination', [\App\Http\Controllers\Admin\ProductController::class, 'skuCombination'])->name('sku_combination');


    Route::get('/csv/import', [\App\Http\Controllers\Admin\ProductCsvController::class, 'showImportForm'])->name('import.form');
    Route::post('/csv/import', [\App\Http\Controllers\Admin\ProductCsvController::class, 'import'])->name('import');
    Route::get('/csv/import/template', [\App\Http\Controllers\Admin\ProductCsvController::class, 'downloadTemplate'])->name('import.template');
    Route::get('/csv/import/preview', [\App\Http\Controllers\Admin\ProductCsvController::class, 'preview'])->name('import.preview');

    // Routes for import progress

    Route::get('products/import-progress', [\App\Http\Controllers\Admin\ProductCsvController::class, 'getImportProgress'])->name('import.progress');
    Route::post('products/import-cancel', [\App\Http\Controllers\Admin\ProductCsvController::class, 'cancelImport'])->name('import.cancel');
});


Route::prefix('brand')->name('brand.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\BrandController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\Admin\BrandController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\Admin\BrandController::class, 'store'])->name('store');
    Route::get('/{category}/edit', [\App\Http\Controllers\Admin\BrandController::class, 'edit'])->name('edit');
    Route::put('/{category}', [\App\Http\Controllers\Admin\BrandController::class, 'update'])->name('update');
    Route::delete('/{id}', [\App\Http\Controllers\Admin\BrandController::class, 'destroy'])->name('destroy');
    Route::post('/team/sort', [\App\Http\Controllers\Admin\BrandController::class, 'sort'])->name('sort');
});

Route::resource('coupon', '\App\Http\Controllers\Admin\CouponController');
Route::delete('/coupon/destroy/{id}', [\App\Http\Controllers\Admin\CouponController::class, 'destroy'])->name('coupon.destroy');
Route::post('/coupon/get_coupon_form', [\App\Http\Controllers\Admin\CouponController::class, 'get_coupon_form'])->name('coupon.get_coupon_form');
Route::post('/coupon/get_coupon_form_edit', [\App\Http\Controllers\Admin\CouponController::class, 'get_coupon_form_edit'])->name('coupon.get_coupon_form_edit');


Route::prefix('reviews')->name('reviews.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('index');
    Route::post('/update-status', [\App\Http\Controllers\Admin\ReviewController::class, 'updateStatus'])->name('update-status');
    Route::get('/show/{id}', [\App\Http\Controllers\Admin\ReviewController::class, 'show'])->name('show');


    Route::delete('/{id}', [\App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('destroy');
    Route::post('/mark-as-read', [\App\Http\Controllers\Admin\ReviewController::class, 'markAsRead'])->name('mark-as-read');
    Route::post('/bulk-update-status', [\App\Http\Controllers\Admin\ReviewController::class, 'bulkUpdateStatus'])->name('bulk-update-status');
    Route::post('/bulk-delete', [\App\Http\Controllers\Admin\ReviewController::class, 'bulkDelete'])->name('bulk-delete');
});


Route::prefix('wishlists')->name('wishlists.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\WishlistController::class, 'index'])->name('index');
    Route::delete('/{id}', [\App\Http\Controllers\Admin\WishlistController::class, 'destroy'])->name('destroy');
    Route::post('/bulk-delete', [\App\Http\Controllers\Admin\WishlistController::class, 'bulkDelete'])->name('bulkDelete');
});

Route::prefix('carts')->name('carts.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\CartController::class, 'index'])->name('index');
    Route::delete('/{id}', [\App\Http\Controllers\Admin\CartController::class, 'destroy'])->name('destroy');
    Route::post('/bulk-delete', [\App\Http\Controllers\Admin\CartController::class, 'bulkDelete'])->name('bulkDelete');
});


// In web.php - Add to your admin routes group
Route::prefix('customers')->name('customers.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('index');
    Route::get('show/{id}', [\App\Http\Controllers\Admin\CustomerController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [\App\Http\Controllers\Admin\CustomerController::class, 'edit'])->name('edit');
    Route::put('/{id}', [\App\Http\Controllers\Admin\CustomerController::class, 'update'])->name('update');
    Route::delete('/{id}', [\App\Http\Controllers\Admin\CustomerController::class, 'destroy'])->name('destroy');
    Route::post('/toggle-ban/{id}', [\App\Http\Controllers\Admin\CustomerController::class, 'toggleBan'])->name('toggle-ban');
    Route::post('/bulk-action', [\App\Http\Controllers\Admin\CustomerController::class, 'bulkAction'])->name('bulk-action');
    Route::get('/export', [\App\Http\Controllers\Admin\CustomerController::class, 'export'])->name('export');
    Route::post('/{id}/update-balance', [\App\Http\Controllers\Admin\CustomerController::class, 'updateBalance'])->name('update-balance');
    Route::post('/bulk-sms', [\App\Http\Controllers\Admin\CustomerController::class, 'bulkSms'])->name('bulk-sms');
    Route::post('/get-customer-names', [\App\Http\Controllers\Admin\CustomerController::class, 'getCustomerNames'])->name('get-customer-names');
});


Route::prefix('searches')->name('searches.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\SearchController::class, 'index'])->name('index');
    Route::delete('/{id}', [\App\Http\Controllers\Admin\SearchController::class, 'destroy'])->name('destroy');
    Route::post('/bulk-delete', [\App\Http\Controllers\Admin\SearchController::class, 'bulkDelete'])->name('bulk-delete');
    Route::post('/clear-all', [\App\Http\Controllers\Admin\SearchController::class, 'clearAll'])->name('clear-all');
    Route::get('/export', [\App\Http\Controllers\Admin\SearchController::class, 'export'])->name('export');
});


// In routes/web.php - Add to your admin routes group

Route::prefix('compares')->name('compares.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\CompareController::class, 'index'])->name('index');
    Route::delete('show/{id}', [\App\Http\Controllers\Admin\CompareController::class, 'destroy'])->name('destroy');
    Route::post('/bulk-delete', [\App\Http\Controllers\Admin\CompareController::class, 'bulkDelete'])->name('bulk-delete');
    Route::post('/clear-all', [\App\Http\Controllers\Admin\CompareController::class, 'clearAll'])->name('clear-all');
    Route::get('/export', [\App\Http\Controllers\Admin\CompareController::class, 'export'])->name('export');
});

Route::prefix('campaigns')->name('campaigns.')->group(function () {
    // Campaign CRUD import
    Route::get('/', [App\Http\Controllers\Admin\CampaignController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\Admin\CampaignController::class, 'create'])->name('create');
    Route::post('/store', [App\Http\Controllers\Admin\CampaignController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [App\Http\Controllers\Admin\CampaignController::class, 'edit'])->name('edit');
    Route::put('/{id}', [App\Http\Controllers\Admin\CampaignController::class, 'update'])->name('update');
    Route::delete('/{id}', [App\Http\Controllers\Admin\CampaignController::class, 'destroy'])->name('destroy');
    Route::get('/{id}', [App\Http\Controllers\Admin\CampaignController::class, 'show'])->name('show');

    // Campaign status update
    Route::post('/{id}/toggle-status', [App\Http\Controllers\Admin\CampaignController::class, 'toggleStatus'])->name('toggle-status');

    Route::post('/update-status', [\App\Http\Controllers\Admin\CampaignController::class, 'updateStatus'])->name('update-status');

    // Bulk actions
    Route::post('/bulk-delete', [App\Http\Controllers\Admin\CampaignController::class, 'bulkDelete'])->name('bulk-delete');
    Route::post('/bulk-status', [App\Http\Controllers\Admin\CampaignController::class, 'bulkStatus'])->name('bulk-status');

    // Product management for campaign
    Route::post('/{id}/add-product', [App\Http\Controllers\Admin\CampaignController::class, 'addProduct'])->name('add-product');
    Route::delete('/{campaignId}/remove-product/{productId}', [App\Http\Controllers\Admin\CampaignController::class, 'removeProduct'])->name('remove-product');
    Route::post('/{id}/update-product-priority', [App\Http\Controllers\Admin\CampaignController::class, 'updateProductPriority'])->name('update-product-priority');

    // AJAX routes
    Route::get('/products/search', [App\Http\Controllers\Admin\CampaignController::class, 'searchProducts'])->name('search-products');

    // Export
    Route::get('/export', [App\Http\Controllers\Admin\CampaignController::class, 'export'])->name('export');

    Route::delete('/campaigns/{campaignId}/remove-product/{productId}', [App\Http\Controllers\Admin\CampaignController::class, 'removeProduct'])->name('remove-product');
});


// Manual Order 
Route::prefix('manual-orders')->name('manual_orders.')->group(function () {
    Route::get('/create-manual-order', [ManualOrderController::class, 'create_manual_order'])->name('index');
    Route::post('/get-products', [ManualOrderController::class, 'getProducts'])->name('get-products');

    Route::post('/add-to-cart', [ManualOrderController::class, 'addToCart'])->name('add-to-cart');
    Route::post('/update-cart', [ManualOrderController::class, 'updateCart'])->name('update-cart');
    Route::post('/add-variant-to-cart', [ManualOrderController::class, 'addVariantToCart'])->name('add-variant-to-cart');
    Route::post('/remove-from-cart', [ManualOrderController::class, 'removeFromCart'])->name('remove-from-cart');
    Route::post('/get-cart', [ManualOrderController::class, 'getCart'])->name('get-cart');
    Route::post('/clear-cart', [ManualOrderController::class, 'clearCart'])->name('clear-cart');
    Route::post('/place-order', [ManualOrderController::class, 'placeOrder'])->name('place-order');
    Route::get('/search-customer', [ManualOrderController::class, 'searchCustomer'])->name('search-customer');
    Route::get('/{id}', [ManualOrderController::class, 'show'])->name('show');
    Route::post('/update-status', [ManualOrderController::class, 'updateStatus'])->name('update-status');

    Route::post('/apply-coupon', [ManualOrderController::class, 'applyCoupon'])->name('apply-coupon');
    Route::post('/remove-coupon', [ManualOrderController::class, 'removeCoupon'])->name('remove-coupon');
    Route::post('/apply-manual-discount', [ManualOrderController::class, 'applyManualDiscount'])->name('apply-manual-discount');
    Route::post('/remove-manual-discount', [ManualOrderController::class, 'removeManualDiscount'])->name('remove-manual-discount');
    Route::post('/search-products', [ManualOrderController::class, 'searchProducts'])->name('search-products');
});

Route::prefix('orders')->name('orders.')->group(function () {
    // Main routes
    Route::get('/', [OrderController::class, 'orders'])->name('index');
    Route::get('/manual-orders', [OrderController::class, 'manualOrders'])->name('all.manual.orders');
    Route::get('/delivered-orders', [OrderController::class, 'deliveredOrders'])->name('all.delivered.orders');
    Route::get('/shipped-orders', [OrderController::class, 'shippedOrders'])->name('all.shipped.orders');
    Route::get('/canceled-orders', [OrderController::class, 'canceledOrders'])->name('all.canceled.orders');
    Route::get('/{id}', [OrderController::class, 'show'])->name('show');
    Route::get('download-invoice/{id}', [OrderController::class, 'downloadInvoice'])->name('download.invoice');
    Route::get('/{id}/edit', [OrderController::class, 'edit'])->name('edit');

    // Search products searchProducts
    Route::get('/search-products', [OrderController::class, 'searchProducts'])->name('search.product');

    // Edit routes
    Route::post('/{order}/add-item', [OrderController::class, 'addItem'])->name('add-item');
    Route::post('/{order}/update-item', [OrderController::class, 'updateItem'])->name('update-item');
    Route::post('/{order}/remove-item', [OrderController::class, 'removeItem'])->name('remove-item');
    Route::post('/{order}/update-notes', [OrderController::class, 'updateNotes'])->name('update-notes');
    Route::post('/{order}/update-address', [OrderController::class, 'updateAddress'])->name('update-address');
    Route::post('/{order}/cancel', [OrderController::class, 'cancelOrder'])->name('cancel');
    Route::post('/{order}/update-order', [OrderController::class, 'updateOrder'])->name('update-order');
    Route::get('/{order}/print', [OrderController::class, 'printOrder'])->name('print');
    Route::delete('/{id}', [OrderController::class, 'destroy'])->name('destroy');

    // Status update routes
    Route::post('/update-status', [OrderController::class, 'updateStatus'])->name('update-status');
    Route::post('/update-delivery-status', [OrderController::class, 'updateDeliveryStatus'])->name('update-delivery-status');
    Route::post('/update-payment-status', [OrderController::class, 'updatePaymentStatus'])->name('update-payment-status');

    // Bulk action routes
    Route::post('/bulk-update-delivery', [OrderController::class, 'bulkUpdateDeliveryStatus'])->name('bulk-update-delivery');
    Route::post('/bulk-update-payment', [OrderController::class, 'bulkUpdatePaymentStatus'])->name('bulk-update-payment');
    Route::post('/bulk-delete', [OrderController::class, 'bulkDelete'])->name('bulk-delete');
    // Export route
    Route::get('/export', [OrderController::class, 'export'])->name('export');

    Route::get('/csv/import', [\App\Http\Controllers\Admin\OrderCsvController::class, 'showImportForm'])->name('import.form');
    Route::post('/csv/import', [\App\Http\Controllers\Admin\OrderCsvController::class, 'import'])->name('import');
    Route::get('/csv/import/template', [\App\Http\Controllers\Admin\OrderCsvController::class, 'downloadTemplate'])->name('import.template');
});

Route::prefix('landing-pages')->name('landingpages.')->group(function () {
    // Main routes
    Route::get('/', [LandingpageController::class, 'index'])->name('index');
    Route::get('/create', [LandingPageController::class, 'create'])->name('create');
    Route::post('/store', [LandingPageController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [LandingPageController::class, 'edit'])->name('edit');
    Route::put('/{id}', [LandingPageController::class, 'update'])->name('update');
    Route::delete('/{id}', [LandingPageController::class, 'destroy'])->name('destroy');
    Route::get('/{slug}', [LandingPageController::class, 'show'])->name('show');

    // Status toggle
    Route::post('/{id}/toggle-status', [LandingPageController::class, 'toggleStatus'])->name('toggle-status');

    // Bulk actions
    Route::post('/bulk-delete', [LandingPageController::class, 'bulkDelete'])->name('bulk-delete');
    Route::post('/bulk-status', [LandingPageController::class, 'bulkStatus'])->name('bulk-status');

    // Export
    Route::get('/export', [LandingPageController::class, 'export'])->name('export');

    // Frontend view
    Route::get('/preview/{id}', [LandingPageController::class, 'preview'])->name('preview');
});


Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/product-stocks', [\App\Http\Controllers\Admin\ReportController::class, 'productStocksReport'])->name('product-stocks');
    Route::get('/product-stocks/export', [\App\Http\Controllers\Admin\ReportController::class, 'exportProductStocks'])->name('export-product-stocks');

    Route::get('/product-wishlist', [\App\Http\Controllers\Admin\ReportController::class, 'productWishlistReport'])->name('product-wishlist');
    Route::get('/product-wishlist/export', [\App\Http\Controllers\Admin\ReportController::class, 'exportProductWishlist'])->name('export-product-wishlist');

    Route::get('/user-searches', [\App\Http\Controllers\Admin\ReportController::class, 'userSearchesReport'])->name('user-searches');
    Route::get('/user-searches/export', [\App\Http\Controllers\Admin\ReportController::class, 'exportUserSearches'])->name('export-user-searches');
    Route::post('/user-searches/clear', [\App\Http\Controllers\Admin\ReportController::class, 'clearSearchRecords'])->name('clear-search-records');

    Route::get('/sales', [\App\Http\Controllers\Admin\ReportController::class, 'salesReport'])->name('sales');
    Route::get('/sales-trend-data', [\App\Http\Controllers\Admin\ReportController::class, 'getSalesTrendData'])->name('sales-trend-data');
    Route::get('/sales/export', [\App\Http\Controllers\Admin\ReportController::class, 'exportSalesReport'])->name('export-sales');

    Route::get('/products', [\App\Http\Controllers\Admin\ReportController::class, 'productReport'])->name('products');
    Route::get('/products/export', [\App\Http\Controllers\Admin\ReportController::class, 'exportProductReport'])->name('export-products');
    Route::get('/customers', [\App\Http\Controllers\Admin\ReportController::class, 'customerReport'])->name('customers');
    Route::get('/customers/export', [\App\Http\Controllers\Admin\ReportController::class, 'exportCustomerReport'])->name('export-customers');
    Route::get('/orders', [\App\Http\Controllers\Admin\ReportController::class, 'orderReport'])->name('orders');
    Route::get('/orders/export', [\App\Http\Controllers\Admin\ReportController::class, 'exportOrderReport'])->name('export-orders');

    // Route::get('/reviews', [\App\Http\Controllers\Admin\ReportController::class, 'reviewReport'])->name('reviews');

    // Route::get('/coupons', [\App\Http\Controllers\Admin\ReportController::class, 'couponReport'])->name('coupons');
});


// Incomplete Order routes
Route::prefix('incomplete-orders')->name('incomplete-orders.')->group(function () {
    Route::get('/', [IncompleteOrderController::class, 'index'])->name('index');
    Route::get('/create', [IncompleteOrderController::class, 'create'])->name('create');
    Route::post('/', [IncompleteOrderController::class, 'store'])->name('store');
    Route::get('/{incompleteOrder}', [IncompleteOrderController::class, 'show'])->name('show');
    Route::get('/{incompleteOrder}/edit', [IncompleteOrderController::class, 'edit'])->name('edit');
    Route::put('/{incompleteOrder}', [IncompleteOrderController::class, 'update'])->name('update');
    Route::delete('/{incompleteOrder}', [IncompleteOrderController::class, 'destroy'])->name('destroy');

    // Custom routes
    Route::post('/{incompleteOrder}/convert', [IncompleteOrderController::class, 'convertToOrder'])->name('convert');
    Route::post('/{incompleteOrder}/reminder', [IncompleteOrderController::class, 'sendReminder'])->name('send-reminder');
    Route::post('/bulk-action', [IncompleteOrderController::class, 'bulkAction'])->name('bulk-action');
    Route::get('/stats', [IncompleteOrderController::class, 'getStats'])->name('stats');
});

// Also add a link in the main orders menu
Route::get('/orders/incomplete', [OrderController::class, 'incompleteOrders'])->name('orders.all.incomplete');



Route::resource('attributes', '\App\Http\Controllers\Admin\AttributeController');

Route::get('/attributes/edit/{id}', [\App\Http\Controllers\Admin\AttributeController::class, 'edit'])->name('attributes.edit');
Route::delete('/attributes/destroy/{id}', [\App\Http\Controllers\Admin\AttributeController::class, 'destroy'])->name('attributes.destroy');
