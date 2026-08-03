<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AdminDashboardController;
use App\Http\Controllers\admin\AdminProductController;
use App\Http\Controllers\admin\AdminOrderController;
use App\Http\Controllers\admin\AdminUserController;
use App\Http\Controllers\admin\AdminRequestController;
use App\Http\Controllers\admin\AdminCampaignController;
use App\Http\Controllers\admin\AdminSupplierController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\AdminAnalyticsController;
use App\Http\Controllers\admin\AdminFaqController;
use App\Http\Controllers\admin\AdminTermsController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\SettingsController;
use App\Http\Controllers\admin\ContactController;
use App\Http\Controllers\admin\SliderController;
use App\Http\Controllers\admin\PromoCodeController;
use App\Http\Controllers\admin\AdminProductImportController;
use App\Http\Controllers\admin\AdminInstallmentPlanController;
use App\Http\Controllers\admin\AdminWalletController;

Route::prefix('admin')->middleware('admin')->group(function () {

    // ===== DASHBOARD =====
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/refresh', [AdminDashboardController::class, 'refreshData'])->name('admin.dashboard.refresh');

    // ===== PRODUCTS =====
    Route::prefix('products')->controller(AdminProductController::class)->group(function () {
        Route::get('/', 'index')->name('admin.products.index');
        Route::get('/create', 'create')->name('admin.products.create');
        Route::post('/store', 'store')->name('admin.products.store');
        Route::get('/edit/{product}', 'edit')->name('admin.products.edit');
        Route::post('/update/{product}', 'update')->name('admin.products.update');
        Route::get('/delete/{product}', 'destroy')->name('admin.products.delete');
        Route::get('/delete-image/{id}', 'deleteImage')->name('admin.products.deleteImage');
    });

    // ===== PRODUCT IMPORT (CSV) =====
    Route::prefix('import')->controller(AdminProductImportController::class)->group(function () {
        Route::get('/', 'index')->name('admin.products.import');
        Route::post('/', 'store')->name('admin.products.import.store');
        Route::get('/{import}/report', 'report')->name('admin.products.import.report');
    });

    // ===== INSTALLMENT PLANS =====
    Route::prefix('plans')->controller(AdminInstallmentPlanController::class)->group(function () {
        Route::get('/', 'index')->name('admin.plans.index');
        Route::post('/', 'store')->name('admin.plans.store');
        Route::get('/{plan}/edit', 'edit')->name('admin.plans.edit');
        Route::post('/{plan}', 'update')->name('admin.plans.update');
        Route::post('/{plan}/delete', 'destroy')->name('admin.plans.delete');
    });

    // ===== ORDERS =====
    Route::prefix('orders')->controller(AdminOrderController::class)->group(function () {
        Route::get('/', 'index')->name('admin.orders.index');
        Route::get('/fees', 'fees')->name('admin.orders.fees');
        Route::post('/fees/{fee}', 'updateFee')->name('admin.orders.fees.update');
        Route::post('/fees/override/store', 'storeFeeOverride')->name('admin.orders.fees.override.store');
        Route::post('/fees/override/{override}/delete', 'destroyFeeOverride')->name('admin.orders.fees.override.delete');
        Route::get('/export/csv', 'export')->name('admin.orders.export');
        Route::get('/{order}', 'show')->name('admin.orders.show');
        Route::post('/{order}/status', 'updateStatus')->name('admin.orders.status');
        Route::post('/{order}/delivery', 'updateDeliveryStatus')->name('admin.orders.delivery');
    });

    // ===== USERS =====
    Route::prefix('users')->controller(AdminUserController::class)->group(function () {
        Route::get('/', 'index')->name('admin.users.index');
        Route::get('/{user}', 'show')->name('admin.users.show');
        Route::post('/{user}/role', 'updateRole')->name('admin.users.role');
        Route::post('/{user}/suspend', 'suspend')->name('admin.users.suspend');
        Route::post('/{user}/unsuspend', 'unsuspend')->name('admin.users.unsuspend');
        Route::get('/{user}/delete', 'destroy')->name('admin.users.delete');
    });

    // ===== REQUESTS =====
    Route::prefix('requests')->controller(AdminRequestController::class)->group(function () {
        Route::get('/plan-changes', 'planChanges')->name('admin.requests.plan-changes');
        Route::post('/plan-changes/{planChangeRequest}/approve', 'approvePlanChange')->name('admin.requests.plan-changes.approve');
        Route::post('/plan-changes/{planChangeRequest}/reject', 'rejectPlanChange')->name('admin.requests.plan-changes.reject');
        Route::get('/product-requests', 'productRequests')->name('admin.requests.product-requests');
        Route::post('/product-requests/{productRequest}/update', 'updateProductRequest')->name('admin.requests.product-requests.update');
        Route::get('/exchange-requests', 'exchangeRequests')->name('admin.requests.exchange-requests');
        Route::post('/exchange-requests/{exchangeRequest}/update', 'updateExchangeRequest')->name('admin.requests.exchange-requests.update');
        Route::get('/deletion-requests', 'deletionRequests')->name('admin.requests.deletion-requests');
        Route::post('/deletion-requests/{deletionRequest}/approve', 'approveDeletion')->name('admin.requests.deletion-requests.approve');
        Route::post('/deletion-requests/{deletionRequest}/reject', 'rejectDeletion')->name('admin.requests.deletion-requests.reject');
    });

    // ===== CAMPAIGNS =====
    Route::prefix('campaigns')->controller(AdminCampaignController::class)->group(function () {
        Route::get('/', 'index')->name('admin.campaigns.index');
        Route::get('/create', 'create')->name('admin.campaigns.create');
        Route::post('/store', 'store')->name('admin.campaigns.store');
        Route::post('/{campaign}/send', 'send')->name('admin.campaigns.send');
        Route::get('/{campaign}/delete', 'destroy')->name('admin.campaigns.delete');
    });

    // ===== SUPPLIERS =====
    Route::prefix('suppliers')->controller(AdminSupplierController::class)->group(function () {
        Route::get('/', 'index')->name('admin.suppliers.index');
        Route::get('/create', 'create')->name('admin.suppliers.create');
        Route::post('/store', 'store')->name('admin.suppliers.store');
        Route::get('/edit/{supplier}', 'edit')->name('admin.suppliers.edit');
        Route::post('/update/{supplier}', 'update')->name('admin.suppliers.update');
        Route::get('/delete/{supplier}', 'destroy')->name('admin.suppliers.delete');
    });

    // ===== CATEGORIES =====
    Route::prefix('category')->controller(CategoryController::class)->group(function () {
        Route::get('/', 'index')->name('admin.category.index');
        Route::get('/create', 'create')->name('admin.category.create');
        Route::post('/store', 'store')->name('admin.category.store');
        Route::get('/edit/{category}', 'edit')->name('admin.category.edit');
        Route::post('/update/{category}', 'update')->name('admin.category.update');
        Route::get('/delete/{id}', 'delete')->name('admin.category.delete');
    });

    // ===== BRANDS =====
    Route::prefix('brands')->controller(BrandController::class)->group(function () {
        Route::get('/edit/{brand}', 'edit')->name('admin.brands.edit');
        Route::get('/', 'index')->name('admin.brands.index');
        Route::get('/create', 'create')->name('admin.brands.create');
        Route::post('/store', 'store')->name('admin.brands.store');
        Route::post('/update/{brand}', 'update')->name('admin.brands.update');
        Route::get('/delete/{brand}', 'destroy')->name('admin.brands.delete');
    });

    // ===== WALLET =====
    Route::prefix('wallet')->controller(AdminWalletController::class)->group(function () {
        Route::get('/', 'index')->name('admin.wallet.index');
        Route::get('/credit/{user}', 'creditForm')->name('admin.wallet.credit-form');
        Route::post('/credit/{user}', 'credit')->name('admin.wallet.credit');
        Route::get('/withdrawals', 'withdrawals')->name('admin.wallet.withdrawals');
        Route::post('/withdrawals/{withdrawal}', 'updateWithdrawal')->name('admin.wallet.withdrawals.update');
    });

    // ===== SETTINGS =====
    Route::get('/settings', [SettingsController::class, 'index'])->name('admin.settings');
    Route::post('/settings', [SettingsController::class, 'update'])->name('admin.settings.update');

    // ===== SLIDERS =====
    Route::prefix('sliders')->controller(SliderController::class)->group(function () {
        Route::get('/', 'index')->name('admin.sliders.index');
        Route::post('/store', 'store')->name('admin.sliders.store');
        Route::get('/delete/{id}', 'delete')->name('admin.sliders.delete');
    });

    // ===== CONTACTS =====
    Route::prefix('contacts')->controller(ContactController::class)->group(function () {
        Route::get('/', 'index')->name('admin.contacts.index');
    });

    // ===== ANALYTICS =====
    Route::prefix('analytics')->controller(AdminAnalyticsController::class)->group(function () {
        Route::get('/', 'index')->name('admin.analytics');
        Route::get('/export', 'export')->name('admin.analytics.export');
    });

    // ===== PROMO CODES =====
    Route::prefix('promo-codes')->controller(PromoCodeController::class)->group(function () {
        Route::get('/', 'index')->name('admin.promo-codes.index');
        Route::get('/create', 'create')->name('admin.promo-codes.create');
        Route::post('/store', 'store')->name('admin.promo-codes.store');
        Route::get('/edit/{promoCode}', 'edit')->name('admin.promo-codes.edit');
        Route::post('/update/{promoCode}', 'update')->name('admin.promo-codes.update');
        Route::get('/delete/{promoCode}', 'destroy')->name('admin.promo-codes.delete');
    });

    // ===== VALUE CHAMPIONS =====
    Route::get('/value-champions', function () {
        return view('backend.value-champions');
    })->name('admin.value-champions');

    // ===== FAQS =====
    Route::prefix('faqs')->controller(AdminFaqController::class)->group(function () {
        Route::get('/', 'index')->name('admin.faqs.index');
        Route::post('/store', 'store')->name('admin.faqs.store');
        Route::post('/update/{faq}', 'update')->name('admin.faqs.update');
        Route::get('/delete/{faq}', 'destroy')->name('admin.faqs.delete');
    });

    // ===== TERMS =====
    Route::prefix('terms')->controller(AdminTermsController::class)->group(function () {
        Route::get('/', 'index')->name('admin.terms.index');
        Route::post('/update/{term}', 'update')->name('admin.terms.update');
    });
});