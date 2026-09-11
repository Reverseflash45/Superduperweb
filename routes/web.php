<?php

use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\TermsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/portofolio', [PortfolioController::class, 'index'])->name('portfolio.index');
Route::get('/portofolio/{portfolio:slug}', [PortfolioController::class, 'show'])->name('portfolio.show');

Route::get('/konsultasi', [ConsultationController::class, 'create'])->name('consultation.create');
Route::post('/konsultasi', [ConsultationController::class, 'store'])
    ->middleware('throttle:konsultasi')
    ->name('consultation.store');

Route::get('/syarat-ketentuan', [TermsController::class, 'show'])->name('terms');

// Halaman invoice klien: diakses lewat kode acak 40 karakter, tanpa login.
Route::prefix('invoice/{project:invoice_code}')->name('invoice.')->group(function () {
    Route::get('/', [InvoiceController::class, 'show'])->name('show');
    Route::get('/pdf', [InvoiceController::class, 'pdf'])->name('pdf');
    Route::post('/bukti-pembayaran', [InvoiceController::class, 'storeProof'])
        ->middleware('throttle:bukti-pembayaran')
        ->name('proof');
});

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
