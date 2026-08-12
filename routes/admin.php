<?php

use App\Http\Controllers\Vendor\VendorLoginController;

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {
    //Update Routes
    Route::get('/dashboard', [\App\Http\Controllers\Dashboard\AdminController::class, 'admin_dashboard'])
        ->name('admin.dashboard')->middleware(['auth', 'admin']);


    Route::get('/cache-cache', '\App\Http\Controllers\Dashboard\AdminController@clearCache')->name('cache.clear');
    Route::resource('profile', 'ProfileController');

    // uploaded files
    Route::any('/uploaded-files/file-info', 'AizUploadController@file_info')->name('uploaded-files.info');
    Route::resource('/uploaded-files', 'AizUploadController')->except(['destroy']);
    Route::delete('/uploaded-files/destroy/{id}', 'AizUploadController@destroy')->name('uploaded-files.destroy');

    // Aiz Uploader
    Route::post('/aiz-uploader/upload', 'AizUploadController@upload')->name('admin.aiz-uploader.upload');
    Route::get('/aiz-uploader/get_uploaded_files', 'AizUploadController@get_uploaded_files')->name('admin.aiz-uploader.get_uploaded_files');
    Route::post('/aiz-uploader/get_file_by_ids', 'AizUploadController@get_file_by_ids')->name('admin.aiz-uploader.get_file_by_ids');
    Route::delete('/aiz-uploader/destroy/{id}', 'AizUploadController@destroy')->name('admin.aiz-uploader.destroy');



    Route::resource('shipping_costs', '\App\Http\Controllers\Admin\ShippingCostController')->except(['show']);
    Route::get('/fraud_checker', '\App\Http\Controllers\Admin\FraudCheckerController@index')->name('fraud_checker');



    // Business Settings
    Route::post('/business-settings/update', '\App\Http\Controllers\Admin\BusinessSettingsController@update')->name('business_settings.update');
    Route::post('/business-settings/save-setting', '\App\Http\Controllers\Admin\BusinessSettingsController@saveSetting')->name('business_settings.update_setting');
    Route::post('/business-settings/update/activation', '\App\Http\Controllers\Admin\BusinessSettingsController@updateActivationSettings')->name('business_settings.update.activation');
    Route::get('/general-setting', '\App\Http\Controllers\Admin\BusinessSettingsController@general_setting')->name('general_setting.index');
    Route::get('/credentials', '\App\Http\Controllers\Admin\BusinessSettingsController@credentials')->name('credentials.index');
    Route::get('/activation', '\App\Http\Controllers\Admin\BusinessSettingsController@activation')->name('activation.index');
    Route::get('/social-login', '\App\Http\Controllers\Admin\BusinessSettingsController@social_login')->name('social_login.index');
    Route::get('/google-analytics', '\App\Http\Controllers\Admin\BusinessSettingsController@google_analytics')->name('google_analytics.index');
    Route::post('/google_analytics', '\App\Http\Controllers\Admin\BusinessSettingsController@google_analytics_update')->name('google_analytics.update');
    Route::post('/facebook_pixel', '\App\Http\Controllers\Admin\BusinessSettingsController@facebook_pixel_update')->name('facebook_pixel.update');
    Route::post('/env_key_update', '\App\Http\Controllers\Admin\BusinessSettingsController@env_key_update')->name('env_key_update.update');


    // website setting
    Route::group(['prefix' => 'website'], function () {
        Route::get('/footer', 'WebsiteController@footer')->name('website.footer');
        Route::get('/appearance', 'WebsiteController@appearance')->name('website.appearance');
        Route::get('/header', 'WebsiteController@header')->name('website.header');
        Route::get('/pages', 'WebsiteController@pages')->name('website.pages');
        Route::get('/price-fix', 'WebsiteController@priceFix')->name('website.price.fix');
        Route::post('/pages/sort', 'WebsiteController@sort')->name('website.pages.sort');



        Route::put('/pages/{id}', '\App\Http\Controllers\Admin\PageController@update_section')->name('website.pages.section.update');
        Route::resource('custom-pages', '\App\Http\Controllers\Admin\PageController');
        Route::get('/about_page', '\App\Http\Controllers\Admin\PageController@aboutEditPage')->name('website.about.page');
    });

    // Staff Management
    Route::resource('roles', '\App\Http\Controllers\Admin\RoleController');
    Route::resource('staffs', '\App\Http\Controllers\Admin\StaffController');

    // Visitor Log
    Route::get('/visitor-logs', '\App\Http\Controllers\Admin\VisitorLogController@index')->name('admin.visitor_log');
    Route::delete('/visitor-logs/{id}', '\App\Http\Controllers\Admin\VisitorLogController@destroy')->name('admin.visitor_log.destroy');
    Route::post('/visitor-logs/{id}/block', '\App\Http\Controllers\Admin\VisitorLogController@block')->name('admin.visitor_log.block');
    Route::post('/visitor-logs/{id}/unblock', '\App\Http\Controllers\Admin\VisitorLogController@unblock')->name('admin.visitor_log.unblock');

    require __DIR__ . '/droploo.php';
    require __DIR__ . '/ecommerce.php';
});
