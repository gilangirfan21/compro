<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SeoController;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/tentang', [PageController::class, 'about'])->name('about');
Route::get('/layanan', [PageController::class, 'services'])->name('services');

Route::get('/portofolio', [PortfolioController::class, 'index'])->name('portfolios.index');
Route::get('/portofolio/{portfolio:slug}', [PortfolioController::class, 'show'])->name('portfolios.show');

Route::get('/berita', [PostController::class, 'index'])->name('posts.index');
Route::get('/berita/{post:slug}', [PostController::class, 'show'])->name('posts.show');

Route::get('/kontak', [ContactController::class, 'show'])->name('contact');
Route::post('/kontak', [ContactController::class, 'store'])
    ->middleware('throttle:contact')
    ->name('contact.store');
