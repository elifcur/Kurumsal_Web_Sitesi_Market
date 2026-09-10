<?php

use Illuminate\Support\Facades\Route;
use Templates\Business\Http\Controllers\Admin\GalleryItemController as AdminGalleryItemController;
use Templates\Business\Http\Controllers\Admin\PriceItemController as AdminPriceItemController;
use Templates\Business\Http\Controllers\Public\GalleryController;
use Templates\Business\Http\Controllers\Public\PriceListController;
use Templates\Business\Http\Controllers\Public\QuoteController;

Route::get('/fiyat-listesi', [PriceListController::class, 'index'])->name('prices.index');
Route::get('/galeri', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/teklif', [QuoteController::class, 'show'])->name('quote.show');
Route::post('/teklif', [QuoteController::class, 'store'])->middleware('throttle:contact')->name('quote.store');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin', 'can:manage-sector'])->group(function (): void {
    Route::post('price-items/reorder', [AdminPriceItemController::class, 'reorder'])->name('price-items.reorder');
    Route::resource('price-items', AdminPriceItemController::class)->except('show');
    Route::resource('gallery', AdminGalleryItemController::class)->except('show');
});
