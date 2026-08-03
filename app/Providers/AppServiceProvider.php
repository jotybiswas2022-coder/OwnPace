<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

// Policies — authorization is spatie-permission driven (see each Policy).
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Order;
use App\Models\User;
use App\Models\Campaign;
use App\Models\PromoCode;
use App\Models\ProductRequest;
use App\Models\ExchangeRequest;
use App\Models\PlanChangeRequest;
use App\Models\AccountDeletionRequest;
use App\Models\Faq;
use App\Models\TermsAndCondition;
use App\Models\Post;
use App\Models\Slider;
use App\Models\Setting;
use App\Models\ProductFee;
use App\Models\InstallmentPlan;
use App\Models\Wallet;
use App\Models\WalletWithdrawalRequest;
use App\Policies\InstallmentPlanPolicy;
use App\Policies\WalletPolicy;
use App\Policies\ProductPolicy;
use App\Policies\BrandPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\SupplierPolicy;
use App\Policies\OrderPolicy;
use App\Policies\UserPolicy;
use App\Policies\CampaignPolicy;
use App\Policies\PromoCodePolicy;
use App\Policies\ProductRequestPolicy;
use App\Policies\ExchangeRequestPolicy;
use App\Policies\PlanChangeRequestPolicy;
use App\Policies\AccountDeletionRequestPolicy;
use App\Policies\FaqPolicy;
use App\Policies\TermsAndConditionPolicy;
use App\Policies\PostPolicy;
use App\Policies\SliderPolicy;
use App\Policies\SettingPolicy;
use App\Policies\ProductFeePolicy;
use App\Policies\DashboardPolicy;
use App\Policies\AnalyticsPolicy;
use App\Policies\ContactPolicy;
use App\Services\Payments\PaymentGatewayManager;
use App\Services\Payments\PaystackGateway;
use App\Services\Payments\FlutterwaveGateway;
use App\Services\Payments\KorapayGateway;

use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Payment gateways are resolved by key through the manager — swapping a
        // provider means touching this one registration, nowhere else.
        $this->app->singleton(PaystackGateway::class);
        $this->app->singleton(FlutterwaveGateway::class);
        $this->app->singleton(KorapayGateway::class);

        $this->app->singleton(PaymentGatewayManager::class, function ($app) {
            return new PaymentGatewayManager([
                'paystack' => $app->make(PaystackGateway::class),
                'flutterwave' => $app->make(FlutterwaveGateway::class),
                'korapay' => $app->make(KorapayGateway::class),
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register policies for the resources admins manage.
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(Brand::class, BrandPolicy::class);
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(Supplier::class, SupplierPolicy::class);
        Gate::policy(Order::class, OrderPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Campaign::class, CampaignPolicy::class);
        Gate::policy(PromoCode::class, PromoCodePolicy::class);
        Gate::policy(ProductRequest::class, ProductRequestPolicy::class);
        Gate::policy(ExchangeRequest::class, ExchangeRequestPolicy::class);
        Gate::policy(PlanChangeRequest::class, PlanChangeRequestPolicy::class);
        Gate::policy(AccountDeletionRequest::class, AccountDeletionRequestPolicy::class);
        Gate::policy(Faq::class, FaqPolicy::class);
        Gate::policy(TermsAndCondition::class, TermsAndConditionPolicy::class);
        Gate::policy(Post::class, PostPolicy::class);
        Gate::policy(Slider::class, SliderPolicy::class);
        Gate::policy(Setting::class, SettingPolicy::class);
        Gate::policy(ProductFee::class, ProductFeePolicy::class);
        Gate::policy(InstallmentPlan::class, InstallmentPlanPolicy::class);
        Gate::policy(Wallet::class, WalletPolicy::class);
        Gate::policy(WalletWithdrawalRequest::class, WalletPolicy::class);

        // Non-model view abilities (dashboard / analytics / contacts pages).
        // Delegated to dedicated policies so they get the same schema-guarded
        // permission check as the CRUD policies above.
        Gate::define('view dashboard', [DashboardPolicy::class, 'view']);
        Gate::define('view analytics', [AnalyticsPolicy::class, 'view']);
        Gate::define('view contacts', [ContactPolicy::class, 'view']);

        // Super Admins can do anything — short-circuit every authorization check.
        // The legacy isSuperAdmin() flag is honored too, so admins created before
        // spatie roles were seeded keep full access. Both the capitalized seed
        // name ('Super Admin') and the lowercase slug are accepted.
        // The Schema::hasTable guard avoids querying acl_* tables before the
        // spatie migration has run (would otherwise throw a QueryException).
        Gate::before(function ($user, $ability) {
            if ($user->isSuperAdmin()) {
                return true;
            }

            if (Schema::hasTable('acl_roles') && $user->hasAnyRole(['super_admin', 'Super Admin'])) {
                return true;
            }
        });

        view()->composer('*', function ($view) {
            $carts = collect();

            $view->with('carts', $carts);
        });
    }
}
