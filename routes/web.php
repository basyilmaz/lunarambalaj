<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\ReferenceController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SolutionsController;
use App\Http\Controllers\TrackingEventController;
use Illuminate\Support\Facades\Route;

Route::get('/robots.txt', [SeoController::class, 'robots']);
Route::get('/sitemap.xml', [SeoController::class, 'sitemap']);
Route::get('/llms.txt', [SeoController::class, 'llms']);
Route::post('/track/event', [TrackingEventController::class, 'store'])
    ->middleware('throttle:120,1')
    ->name('track.event');

Route::middleware('site-locale:tr')->group(function (): void {
    Route::get('/', [HomeController::class, 'index'])->name('tr.home');
    Route::get('/hakkimizda', [PageController::class, 'about'])->name('tr.about');
    Route::get('/hizmetler', [ServiceController::class, 'index'])->name('tr.services');
    Route::get('/urunler', [ProductController::class, 'index'])->name('tr.products');
    Route::get('/urunler/{slug}', [ProductController::class, 'show'])->name('tr.products.show');
    Route::get('/cozumler', [SolutionsController::class, 'index'])->name('tr.solutions');
    Route::get('/galeri', [GalleryController::class, 'index'])->name('tr.gallery');
    Route::get('/referanslar', [ReferenceController::class, 'index'])->name('tr.references');
    Route::get('/sss', [FaqController::class, 'index'])->name('tr.faq');
    Route::get('/blog', [BlogController::class, 'index'])->name('tr.blog');
    Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('tr.blog.show');
    Route::get('/iletisim', [ContactController::class, 'index'])->name('tr.contact');
    Route::post('/iletisim', [ContactController::class, 'store'])->name('tr.contact.store');
    Route::get('/teklif-al', [QuoteController::class, 'index'])->name('tr.quote');
    Route::post('/teklif-al', [QuoteController::class, 'store'])->name('tr.quote.store');
    Route::get('/teklif-al/tesekkurler', [QuoteController::class, 'thankyou'])->name('tr.quote.thankyou');
    Route::get('/kvkk', [PageController::class, 'kvkk'])->name('tr.kvkk');
    Route::get('/cerez-politikasi', [PageController::class, 'cookie'])->name('tr.cookie');
    Route::get('/gizlilik-politikasi', [PageController::class, 'privacy'])->name('tr.privacy');
});

Route::prefix('en')->middleware('site-locale:en')->group(function (): void {
    Route::get('/', [HomeController::class, 'index'])->name('en.home');
    Route::get('/about', [PageController::class, 'about'])->name('en.about');
    Route::get('/services', [ServiceController::class, 'index'])->name('en.services');
    Route::get('/products', [ProductController::class, 'index'])->name('en.products');
    Route::get('/products/{slug}', [ProductController::class, 'show'])->name('en.products.show');
    Route::get('/solutions', [SolutionsController::class, 'index'])->name('en.solutions');
    Route::get('/gallery', [GalleryController::class, 'index'])->name('en.gallery');
    Route::get('/references', [ReferenceController::class, 'index'])->name('en.references');
    Route::get('/faq', [FaqController::class, 'index'])->name('en.faq');
    Route::get('/blog', [BlogController::class, 'index'])->name('en.blog');
    Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('en.blog.show');
    Route::get('/contact', [ContactController::class, 'index'])->name('en.contact');
    Route::post('/contact', [ContactController::class, 'store'])->name('en.contact.store');
    Route::get('/get-quote', [QuoteController::class, 'index'])->name('en.quote');
    Route::post('/get-quote', [QuoteController::class, 'store'])->name('en.quote.store');
    Route::get('/get-quote/thank-you', [QuoteController::class, 'thankyou'])->name('en.quote.thankyou');
    Route::get('/kvkk', [PageController::class, 'kvkk'])->name('en.kvkk');
    Route::get('/cookie-policy', [PageController::class, 'cookie'])->name('en.cookie');
    Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('en.privacy');
});

Route::prefix('ru')->middleware('site-locale:ru')->group(function (): void {
    Route::get('/', [HomeController::class, 'index'])->name('ru.home');
    Route::get('/about', [PageController::class, 'about'])->name('ru.about');
    Route::get('/services', [ServiceController::class, 'index'])->name('ru.services');
    Route::get('/products', [ProductController::class, 'index'])->name('ru.products');
    Route::get('/products/{slug}', [ProductController::class, 'show'])->name('ru.products.show');
    Route::get('/solutions', [SolutionsController::class, 'index'])->name('ru.solutions');
    Route::get('/gallery', [GalleryController::class, 'index'])->name('ru.gallery');
    Route::get('/references', [ReferenceController::class, 'index'])->name('ru.references');
    Route::get('/faq', [FaqController::class, 'index'])->name('ru.faq');
    Route::get('/blog', [BlogController::class, 'index'])->name('ru.blog');
    Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('ru.blog.show');
    Route::get('/contact', [ContactController::class, 'index'])->name('ru.contact');
    Route::post('/contact', [ContactController::class, 'store'])->name('ru.contact.store');
    Route::get('/get-quote', [QuoteController::class, 'index'])->name('ru.quote');
    Route::post('/get-quote', [QuoteController::class, 'store'])->name('ru.quote.store');
    Route::get('/get-quote/thank-you', [QuoteController::class, 'thankyou'])->name('ru.quote.thankyou');
    Route::get('/kvkk', [PageController::class, 'kvkk'])->name('ru.kvkk');
    Route::get('/cookie-policy', [PageController::class, 'cookie'])->name('ru.cookie');
    Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('ru.privacy');
});

Route::prefix('ar')->middleware('site-locale:ar')->group(function (): void {
    Route::get('/', [HomeController::class, 'index'])->name('ar.home');
    Route::get('/about', [PageController::class, 'about'])->name('ar.about');
    Route::get('/services', [ServiceController::class, 'index'])->name('ar.services');
    Route::get('/products', [ProductController::class, 'index'])->name('ar.products');
    Route::get('/products/{slug}', [ProductController::class, 'show'])->name('ar.products.show');
    Route::get('/solutions', [SolutionsController::class, 'index'])->name('ar.solutions');
    Route::get('/gallery', [GalleryController::class, 'index'])->name('ar.gallery');
    Route::get('/references', [ReferenceController::class, 'index'])->name('ar.references');
    Route::get('/faq', [FaqController::class, 'index'])->name('ar.faq');
    Route::get('/blog', [BlogController::class, 'index'])->name('ar.blog');
    Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('ar.blog.show');
    Route::get('/contact', [ContactController::class, 'index'])->name('ar.contact');
    Route::post('/contact', [ContactController::class, 'store'])->name('ar.contact.store');
    Route::get('/get-quote', [QuoteController::class, 'index'])->name('ar.quote');
    Route::post('/get-quote', [QuoteController::class, 'store'])->name('ar.quote.store');
    Route::get('/get-quote/thank-you', [QuoteController::class, 'thankyou'])->name('ar.quote.thankyou');
    Route::get('/kvkk', [PageController::class, 'kvkk'])->name('ar.kvkk');
    Route::get('/cookie-policy', [PageController::class, 'cookie'])->name('ar.cookie');
    Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('ar.privacy');
});
