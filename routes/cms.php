<?php

use Illuminate\Support\Facades\Route;


// Show all blogs
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\BlogController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\Admin\BlogController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\Admin\BlogController::class, 'store'])->name('store');
    Route::get('/{blog}/edit', [\App\Http\Controllers\Admin\BlogController::class, 'edit'])->name('edit');
    Route::put('/{blog}', [\App\Http\Controllers\Admin\BlogController::class, 'update'])->name('update');
    Route::get('/{blog}', [\App\Http\Controllers\Admin\BlogController::class, 'show'])->name('show');
    Route::delete('/blog/{id}', [\App\Http\Controllers\Admin\BlogController::class, 'destroy'])->name('destroy');
});


// Show all sliders
Route::prefix('slider')->name('slider.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\SliderController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\Admin\SliderController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\Admin\SliderController::class, 'store'])->name('store');
    Route::get('/{slider}/edit', [\App\Http\Controllers\Admin\SliderController::class, 'edit'])->name('edit');
    Route::put('/{slider}', [\App\Http\Controllers\Admin\SliderController::class, 'update'])->name('update');
    Route::delete('/slider/{id}', [\App\Http\Controllers\Admin\SliderController::class, 'destroy'])->name('destroy');
});



Route::prefix('faq')->name('faq.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\FaqController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\Admin\FaqController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\Admin\FaqController::class, 'store'])->name('store');
    Route::get('/{faq}/edit', [\App\Http\Controllers\Admin\FaqController::class, 'edit'])->name('edit');
    Route::put('/{faq}', [\App\Http\Controllers\Admin\FaqController::class, 'update'])->name('update');
    Route::delete('/{id}', [\App\Http\Controllers\Admin\FaqController::class, 'destroy'])->name('destroy');
    Route::post('/faq/sort', [\App\Http\Controllers\Admin\FaqController::class, 'sort'])->name('sort');
});




Route::prefix('contact-message')->name('contact-message.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\ContactMessageController::class, 'index'])->name('index');
    Route::get('/{contact_message}/show', [\App\Http\Controllers\Admin\ContactMessageController::class, 'show'])->name('show');
    Route::delete('/{id}', [\App\Http\Controllers\Admin\ContactMessageController::class, 'destroy'])->name('destroy');
});


Route::prefix('about')->name('about.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\AboutController::class, 'index'])->name('index');
    Route::put('/update', [\App\Http\Controllers\Admin\AboutController::class, 'update'])->name('update');
    Route::put('/counter', [\App\Http\Controllers\Admin\AboutController::class, 'counter'])->name('counter');
});

Route::prefix('newsletter')->name('newsletter.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\NewsletterController::class, 'index'])->name('index');
    Route::post('/newsletter/status', [\App\Http\Controllers\Admin\NewsletterController::class, 'updateStatus'])->name('status');
    Route::delete('/{id}', [\App\Http\Controllers\Admin\NewsletterController::class, 'destroy'])->name('destroy');
});




Route::post('/store-attribute-value', [\App\Http\Controllers\Admin\AttributeController::class, 'store_attribute_value'])->name('store-attribute-value');
Route::get('/edit-attribute-value/{id}', [\App\Http\Controllers\Admin\AttributeController::class, 'edit_attribute_value'])->name('edit-attribute-value');
Route::post('/update-attribute-value/{id}', [\App\Http\Controllers\Admin\AttributeController::class, 'update_attribute_value'])->name('update-attribute-value');
Route::delete('/destroy-attribute-value/{id}', [\App\Http\Controllers\Admin\AttributeController::class, 'destroy_attribute_value'])->name('destroy-attribute-value');


Route::get('home-section', [\App\Http\Controllers\Admin\HomeSectionController::class, 'home'])->name('home.section');
Route::get('main-page', [\App\Http\Controllers\Admin\HomeSectionController::class, 'mainPage'])->name('main.page');
Route::put('main-page-update', [\App\Http\Controllers\Admin\HomeSectionController::class, 'mainPageUpdate'])->name('main.page.update');
