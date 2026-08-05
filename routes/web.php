<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\user\CartController;
use App\Http\Controllers\user\CheckoutController;
use App\Http\Controllers\user\OrderController;
use App\Http\Controllers\user\WalletController;
use App\Http\Controllers\user\WishlistController;
use App\Http\Controllers\user\ProfileController;
use App\Http\Controllers\api\PaymentController;
use App\Http\Controllers\Auth\ForgotPasswordController;

// ===== LOCAL ASSET SERVING =====
// Custom docroot deployment (see app/helper.php::servePublicFile): stream
// Vite bundles (/build/*) and public storage files (/storage/*) through the
// framework when the webserver cannot serve them as static files.
Route::get('/build/{path}', fn (string $path) => servePublicFile('build/'.$path))->where('path', '.*');
Route::get('/storage/{path}', fn (string $path) => servePublicFile('storage/'.$path))->where('path', '.*');

// ===== FRONTEND / PUBLIC ROUTES =====
Route::controller(SiteController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    // Shop renders the Livewire ShopCatalog component inside the store layout.
    Route::get('/shop', 'shop')->name('shop');
    Route::get('/product/{slug}', 'product')->name('product.show');
    Route::get('/contact', 'contact')->name('contact');
    Route::get('/faq', 'faq')->name('faq');
    Route::get('/legal', 'legal')->name('legal');
    Route::get('/terms/{type?}', 'terms')->name('terms');
    Route::get('/about', 'about')->name('about');
});

// ===== DEMO PAGE =====
Route::get('/demo', function () {
    return view('frontend.demo');
});

// ===== CART (Session-based) =====
Route::prefix('cart')->controller(CartController::class)->group(function () {
    Route::get('/', 'index')->name('cart.index');
    Route::post('/add', 'add')->name('cart.add');
    Route::post('/update', 'update')->name('cart.update');
    Route::get('/remove/{productId}', 'remove')->name('cart.remove');
    Route::get('/clear', 'clear')->name('cart.clear');
    Route::get('/count', 'count')->name('cart.count');
});

// ===== CHECKOUT (Authenticated) =====
Route::middleware(['auth'])->prefix('checkout')->controller(CheckoutController::class)->group(function () {
    Route::get('/', 'index')->name('checkout.index');
    Route::post('/process', 'process')->name('checkout.process');
    Route::get('/payment/{order}', 'paymentGateway')->name('payment.gateway');
    Route::post('/payment/{order}/process', 'processPayment')->name('payment.process');
    Route::post('/apply-promo', 'applyPromoCode')->name('checkout.apply-promo');
    Route::get('/remove-promo', 'removePromoCode')->name('checkout.remove-promo');
    Route::get('/proxy/search', 'searchProxy')->name('checkout.proxy.search');
    Route::get('/confirmation/{order}', 'confirmation')->name('order.confirmation');
});

// ===== ORDERS =====
Route::middleware(['auth'])->prefix('orders')->controller(OrderController::class)->group(function () {
    Route::get('/', 'index')->name('orders.index');
    Route::get('/{order}', 'show')->name('orders.show');
    Route::post('/{order}/pay-installment', 'payInstallment')->name('orders.pay.installment');
    Route::post('/{order}/pay-partial', 'payPartial')->name('orders.pay.partial');
    Route::post('/{order}/pay-full', 'payFull')->name('orders.pay.full');
    Route::post('/{order}/pay-wallet', 'payWallet')->name('orders.pay.wallet');
    Route::get('/{order}/change-plan', 'changePlanForm')->name('orders.change.plan.form');
    Route::post('/{order}/change-plan', 'requestPlanChange')->name('orders.change.plan');
    Route::get('/{order}/exchange', 'exchangeForm')->name('orders.exchange.form');
    Route::post('/{order}/exchange', 'requestExchange')->name('orders.exchange.request');
    Route::post('/{order}/cancel', 'cancelOrder')->name('orders.cancel');
    Route::get('/{order}/tracking', 'tracking')->name('orders.tracking');
    Route::post('/{order}/review', 'submitReview')->name('orders.review');
});

// ===== REQUESTS (plan change / exchange / product / closure) =====
Route::middleware(['auth'])->prefix('requests')->controller(\App\Http\Controllers\user\RequestController::class)->group(function () {
    Route::get('/', 'index')->name('requests.index');
    Route::get('/product/create', 'productForm')->name('requests.product.create');
    Route::post('/product', 'storeProduct')->name('requests.product.store');
});

// ===== WALLET =====
Route::middleware(['auth'])->prefix('wallet')->controller(WalletController::class)->group(function () {
    Route::get('/', 'index')->name('wallet.index');
    Route::get('/fund', 'showFundForm')->name('wallet.fund');
    Route::post('/fund', 'fund')->name('wallet.fund.process');
    Route::get('/withdraw', 'withdraw')->name('wallet.withdraw');
    Route::post('/withdraw', 'requestWithdrawal')->name('wallet.withdraw.process');
    Route::get('/history', 'history')->name('wallet.history');
});

// ===== WISHLIST =====
Route::middleware(['auth'])->prefix('wishlist')->controller(WishlistController::class)->group(function () {
    Route::get('/', 'index')->name('wishlist.index');
    Route::post('/toggle', 'toggle')->name('wishlist.toggle');
    Route::post('/exchange', 'requestExchange')->name('wishlist.exchange');
    Route::get('/remove/{id}', 'remove')->name('wishlist.remove');
});

// ===== PROFILE =====
Route::middleware(['auth'])->prefix('profile')->controller(ProfileController::class)->group(function () {
    Route::get('/', 'index')->name('profile.index');
    Route::get('/edit', 'edit')->name('profile.edit');
    Route::post('/update', 'update')->name('profile.update');
    Route::post('/update-password', 'updatePassword')->name('profile.password');
    Route::get('/addresses', 'addresses')->name('profile.addresses');
    Route::post('/addresses/store', 'storeAddress')->name('profile.addresses.store');
    Route::post('/addresses/{address}/update', 'updateAddress')->name('profile.addresses.update');
    Route::get('/addresses/{address}/delete', 'deleteAddress')->name('profile.addresses.delete');
    Route::get('/cards', 'cards')->name('profile.cards');
    Route::get('/cards/{card}/delete', 'deleteCard')->name('profile.cards.delete');
    Route::get('/banks', 'banks')->name('profile.banks');
    Route::post('/banks/store', 'storeBank')->name('profile.banks.store');
    Route::get('/banks/{bank}/delete', 'deleteBank')->name('profile.banks.delete');
    Route::get('/verification', 'verification')->name('profile.verification');
    Route::post('/verification/submit', 'submitVerification')->name('profile.verification.submit');
    Route::post('/deletion-request', 'requestDeletion')->name('profile.deletion.request');
});

// ===== PAYMENT GATEWAY CALLBACKS & WEBHOOKS =====
Route::prefix('payment')->controller(PaymentController::class)->group(function () {
    Route::post('/initialize', 'initialize')->name('payment.initialize');
    Route::get('/paystack/callback', 'paystackCallback')->name('payment.paystack.callback');
    Route::get('/flutterwave/callback', 'flutterwaveCallback')->name('payment.flutterwave.callback');
    Route::get('/korapay/callback', 'korapayCallback')->name('payment.korapay.callback');
    Route::post('/webhook/{gateway}', 'handleWebhook')->name('payment.webhook');
});

// ===== CAMPAIGN TRACKING (opens / clicks — public pixels & redirects) =====
Route::prefix('c')->controller(\App\Http\Controllers\CampaignTrackingController::class)->group(function () {
    Route::get('/t/{log}', 'open')->name('campaign.track.open');
    Route::get('/l/{log}', 'click')->name('campaign.track.click');
});

// ===== CONTACT =====
Route::middleware('auth')->post('/contactus', [App\Http\Controllers\user\UserController::class, 'contactus'])->name('contact.send');

// ===== AUTH =====
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Auth::routes();

// ===== INCLUDE ADMIN ROUTES =====
include('admin.php');
