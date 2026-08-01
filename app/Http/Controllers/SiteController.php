<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\Faq;
use App\Models\TermsAndCondition;

class SiteController extends Controller
{
    // Homepage
    public function index()
    {
        $settings = Setting::first();
        $sliders = Slider::latest()->get();
        $featuredProducts = Product::where('featured', true)
            ->where('status', 'active')
            ->with(['category', 'primaryImage', 'images', 'installmentPlans'])
            ->latest()
            ->take(12)
            ->get()
            ->each(function ($product) {
                $product->installment_plans_count = $product->installmentPlans->count();
                return $product;
            });
        $categories = Category::all();
        $brands = Brand::where('is_active', true)->get();
        $newArrivals = Product::where('status', 'active')
            ->with(['category', 'primaryImage', 'images'])
            ->latest()
            ->take(8)
            ->get();

        return view('frontend.index', compact(
            'settings', 'sliders', 'featuredProducts',
            'categories', 'brands', 'newArrivals'
        ));
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

    // Terms & Conditions
    public function terms($type = 'general')
    {
        $terms = TermsAndCondition::where('type', $type)->where('is_active', true)->first();
        return view('frontend.terms', compact('terms', 'type'));
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
