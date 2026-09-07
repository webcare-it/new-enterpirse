<?php


use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\SitemapController;

// Sitemap - dynamic, generated from DB
Route::get('/sitemap.xml', [SitemapController::class, 'index']);

Route::get('/', function () {
    return serveReact('home');
})->name('home');

// Promotions
Route::view('/promotions/ghi', 'frontend.landing_pages.ghi');
Route::view('/promotions/turbo-fan', 'frontend.landing_pages.turbo-fan');
Route::view('/promotions/livo-fan', 'frontend.landing_pages.livo-fan');
Route::view('/promotions/khimar', 'frontend.landing_pages.khimar');
Route::view('/promotions/borkha', 'frontend.landing_pages.borkha');
Route::view('/promotions/panjabi', 'frontend.landing_pages.panjabi');

Auth::routes([
    'verify' => true,
    'login' => false,
]);

// Admin Login Routes - Use admin.web middleware for separate session cookie
Route::middleware(['guest'])->group(function () {
    Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/admin/login', [LoginController::class, 'login']);
});


// Admin Logout Route - Use admin.web middleware for separate session cookie
Route::middleware(['auth', 'admin'])->group(function () {
    Route::match(['get', 'post'], '/admin/logout', '\App\Http\Controllers\Auth\LoginController@admin_logout')->name('admin.logout');
});


// Aiz Uploader routes for frontend (used by aiz-core.js)
Route::middleware(['auth'])->group(function () {
    Route::post('/aiz-uploader', 'AizUploadController@show_uploader')->name('aiz-uploader');
    Route::post('/aiz-uploader/upload', 'AizUploadController@upload')->name('aiz-uploader.upload');
    Route::get('/aiz-uploader/get_uploaded_files', 'AizUploadController@get_uploaded_files')->name('aiz-uploader.get_uploaded_files');
    Route::post('/aiz-uploader/get_file_by_ids', 'AizUploadController@get_file_by_ids')->name('aiz-uploader.get_file_by_ids');
    Route::delete('/aiz-uploader/destroy/{id}', 'AizUploadController@destroy')->name('aiz-uploader.destroy');
});




// Final catch-all — NEVER intercept admin/* routes
Route::fallback(function () {
    // Let API 404s return JSON
    if (request()->is('api/*')) {
        return response()->json([
            'success' => false,
            'message' => 'API endpoint not found'
        ], 404);
    }

    // For admin/seller/customer routes that don't match — redirect to login or dashboard
    // Don't abort(404) here as it triggers error pages that may crash with null Auth::user()
    if (request()->is('admin/*')) {
        return auth()->check()
            ? redirect('/admin')
            : redirect('/admin/login');
    }

    // For all other routes (frontend), return the React app view to handle client-side routing
    return serveReact('frontend');
});
