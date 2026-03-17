<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\XpubController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ApiKeyController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\DeveloperOverviewController;
use App\Http\Controllers\DeveloperToolsController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/pricing', function () {
    return Inertia::render('Pricing');
})->name('pricing');


// DASHBOARD ROUTES
Route::middleware(['auth', 'verified'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    
    Route::get('/wallets', [WalletController::class, 'index'])->name('wallets');
    Route::post('/wallets', [WalletController::class, 'store'])->name('wallets.store');
    Route::patch('/wallets/{wallet}/status', [WalletController::class, 'update'])->name('wallets.update');
    Route::delete('/wallets/{wallet}', [WalletController::class, 'destroy'])->name('wallets.destroy');

    Route::get('/live-monitor', [PaymentController::class, 'index'])->name('live-monitor');
    
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
    
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices');
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
});

// DEVELOPER PORTAL ROUTES
Route::middleware(['auth', 'verified'])->prefix('developer')->name('developer.')->group(function () {
    Route::get('/', [DeveloperOverviewController::class, 'index'])->name('index');
    
    Route::get('/api-keys', [ApiKeyController::class, 'index'])->name('api-keys');
    Route::post('/api-keys', [ApiKeyController::class, 'store'])->name('api-keys.store');
    Route::delete('/api-keys/{api_key}', [ApiKeyController::class, 'destroy'])->name('api-keys.destroy');
    
    Route::get('/webhooks', [WebhookController::class, 'index'])->name('webhooks');
    Route::post('/webhooks', [WebhookController::class, 'store'])->name('webhooks.store');
    
    Route::get('/sandbox', [DeveloperToolsController::class, 'sandbox'])->name('sandbox');
    Route::get('/playground', [DeveloperToolsController::class, 'playground'])->name('playground');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
