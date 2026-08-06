<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\Faq;
use App\Models\Review;
use App\Models\InstallmentPlan;
use App\Models\TermsAndCondition;
use App\Services\InstallmentCalculatorService;

class SiteController extends Controller
{
    // Homepage
    public function index()
    {
        $settings = Setting::first();
        $sliders = Slider::latest()->get();

        // Product lists are cached briefly — the home page is the most-hit
        // route and these are the three heaviest queries on it.
        $featuredProducts = $this->withInstallmentData(
            Cache::remember('home.products.featured', 300, fn () =>
                Product::where('featured', true)
                    ->where('status', 'active')
                    ->with(['category', 'primaryImage', 'images', 'installmentPlans'])
                    ->latest()
                    ->take(12)
                    ->get()
            )
        );

        // Hot deals — products currently on sale (base_price > price),
        // biggest discount first.
        $deals = $this->withInstallmentData(
            Cache::remember('home.products.deals', 300, fn () =>
                Product::where('status', 'active')
                    ->whereColumn('base_price', '>', 'price')
                    ->with(['category', 'primaryImage', 'images', 'installmentPlans'])
                    ->orderByRaw('(base_price - price) / base_price DESC')
                    ->take(8)
                    ->get()
            )
        );

        $newArrivals = $this->withInstallmentData(
            Cache::remember('home.products.new', 300, fn () =>
                Product::where('status', 'active')
                    ->with(['category', 'primaryImage', 'images', 'installmentPlans'])
                    ->latest()
                    ->take(8)
                    ->get()
            )
        );

        $categories = Category::withCount(['products' => fn ($q) => $q->where('status', 'active')])->get();
        $brands = Brand::where('is_active', true)->get();

        $stats = Cache::remember('home.stats', 300, function () use ($categories, $brands) {
            return [
                'products' => Product::where('status', 'active')->count(),
                'categories' => $categories->count(),
                'brands' => $brands->count(),
                'plans' => InstallmentPlan::where('is_active', true)->count(),
            ];
        });

        $wishlistIds = auth()->check()
            ? auth()->user()->wishlist()->pluck('product_id')->all()
            : [];

        $faqs = Faq::where('is_active', true)->orderBy('sort_order')->take(4)->get();

        // Testimonials — real customer reviews when available, the view falls
        // back to a curated set when the store is still young.
        $homeTestimonials = Review::query()
            ->whereNotNull('comment')
            ->where('comment', '!=', '')
            ->with('user')
            ->latest()
            ->take(6)
            ->get()
            ->map(fn (Review $review) => [
                'name' => $review->user?->name ?: 'Verified customer',
                'city' => 'Verified buyer',
                'text' => $review->comment,
                'rating' => max(1, min(5, (int) $review->rating)),
            ])
            ->all();

        return view('frontend.index', compact(
            'settings', 'sliders', 'featuredProducts', 'deals',
            'categories', 'brands', 'newArrivals', 'stats',
            'wishlistIds', 'faqs', 'homeTestimonials'
        ));
    }

    /**
     * Attach the per-product card data the storefront needs: how many plans
     * a product offers and the lowest per-payment installment ("from ₦X/wk").
     */
    private function withInstallmentData($products)
    {
        return $products->each(function ($product) {
            $product->installment_plans_count = $product->installmentPlans->count();

            $lowest = $product->installmentPlans
                ->where('is_active', true)
                ->sortBy('duration')
                ->first();

            if ($lowest) {
                $breakdown = InstallmentCalculatorService::breakdown((float) $product->price, $lowest);
                $product->installment_from = $breakdown['per_installment'];
                $product->installment_type = $lowest->type;
            } else {
                $product->installment_from = null;
                $product->installment_type = null;
            }
        });
    }

    // Shop page now lives in the Livewire ShopCatalog component (see
    // app/Livewire/ShopCatalog.php + routes/web.php) — debounced search,
    // URL-synced multi-select filters and infinite scroll. Kept only as the
    // canonical catalog entry for any legacy references.
    public function shop(Request $request)
    {
        return view('frontend.shop');
    }

    // Single Product page
    public function product($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('status', 'active')
            ->with(['category', 'brand', 'images', 'installmentPlans', 'reviews.user'])
            ->firstOrFail();

        // Check if in wishlist
        $inWishlist = auth()->check() && auth()->user()->wishlist()
            ->where('product_id', $product->id)->exists();

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->with(['primaryImage', 'images'])
            ->latest()
            ->take(8)
            ->get();

        // Gallery: primary image first, then the rest.
        $gallery = collect()
            ->merge($product->images->where('is_primary', true))
            ->merge($product->images->where('is_primary', false))
            ->map(fn ($img) => imageUrl($img->image_path))
            ->values()
            ->all();

        return view('frontend.product', compact('product', 'relatedProducts', 'inWishlist', 'gallery'));
    }

    // Checkout page
    public function checkout()
    {
        return view('frontend.checkout');
    }

    // FAQ page
    public function faq()
    {
        $faqs = Faq::where('is_active', true)->orderBy('sort_order')->get()->groupBy('category');
        return view('frontend.faq', compact('faqs'));
    }

    // Terms & Conditions — ?plan=ID renders that plan's scoped terms
    // (used by the checkout page's dynamic "plan terms" link).
    public function terms($type = 'general')
    {
        $terms = TermsAndCondition::where('type', $type)->where('is_active', true)->first();

        if (request()->has('plan')) {
            $scoped = TermsAndCondition::where('installment_plan_id', (int) request('plan'))
                ->where('is_active', true)
                ->first();
            if ($scoped) {
                $terms = $scoped;
                $type = $scoped->type;
            }
        }

        return view('frontend.terms', compact('terms', 'type'));
    }

    // Legal & Policies hub — index of every policy document.
    public function legal()
    {
        return view('frontend.legal');
    }

    // Contact page
    public function contact()
    {
        return view('frontend.contact');
    }

    // About page
    public function about()
    {
        return view('frontend.about');
    }
}
