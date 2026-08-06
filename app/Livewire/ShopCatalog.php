<?php

namespace App\Livewire;

use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

/**
 * Customer-facing catalog: debounced search, multi-select brand/category
 * filters (synced to the URL query string), several sort modes and an
 * infinite-scroll loadMore pattern (no pagination links).
 */
class ShopCatalog extends Component
{
    #[Url]
    public string $search = '';

    #[Url]
    public string $sort = 'newest';

    /** @var array<int> */
    #[Url(as: 'categories')]
    public array $selectedCategories = [];

    /** @var array<int> */
    #[Url(as: 'brands')]
    public array $selectedBrands = [];

    public int $perPage = 12;

    public function updatedSearch(): void
    {
        $this->perPage = 12;
    }

    public function updatedSelectedCategories(): void
    {
        $this->perPage = 12;
    }

    public function updatedSelectedBrands(): void
    {
        $this->perPage = 12;
    }

    public function updatedSort(): void
    {
        $this->perPage = 12;
    }

    /**
     * Clear all filters and search, back to a fresh catalog.
     */
    public function resetFilters(): void
    {
        $this->search = '';
        $this->selectedCategories = [];
        $this->selectedBrands = [];
        $this->sort = 'newest';
        $this->perPage = 12;
    }

    /**
     * Remove a single filter (used by the active-filter chips).
     */
    public function removeFilter(string $key, int $id): void
    {
        if ($key === 'categories') {
            $this->selectedCategories = array_values(array_diff($this->selectedCategories, [$id]));
        } elseif ($key === 'brands') {
            $this->selectedBrands = array_values(array_diff($this->selectedBrands, [$id]));
        }

        $this->perPage = 12;
    }

    /**
     * Clear just the search term, keeping category/brand filters.
     */
    public function clearSearch(): void
    {
        $this->search = '';
        $this->perPage = 12;
    }

    /**
     * Infinite scroll: called by wire:intersect when the sentinel scrolls
     * into view, and by the fallback "Load more" button.
     */
    public function loadMore(): void
    {
        $this->perPage += 12;
    }

    #[Computed]
    public function categories()
    {
        return Category::withCount(['products' => fn ($q) => $q->where('status', 'active')])
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function brands()
    {
        return Brand::where('is_active', true)->orderBy('name')->get();
    }

    #[Computed]
    public function sortOptions()
    {
        return [
            'newest' => 'Newest',
            'updated' => 'Recently Updated',
            'popular' => 'Most Popular',
            'price_asc' => 'Price: Low to High',
            'price_desc' => 'Price: High to Low',
            'name' => 'Name A–Z',
            'brand' => 'Brand',
            'category' => 'Category',
        ];
    }

    public function render()
    {
        $query = Product::query()
            ->where('status', 'active')
            ->with(['category', 'brand', 'primaryImage', 'images', 'installmentPlans']);

        if (trim($this->search) !== '') {
            $query->where(function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                  ->orWhere('description', 'like', '%'.$this->search.'%')
                  ->orWhereHas('brand', fn ($b) => $b->where('name', 'like', '%'.$this->search.'%'));
            });
        }

        if (! empty($this->selectedCategories)) {
            $query->whereIn('category_id', $this->selectedCategories);
        }

        if (! empty($this->selectedBrands)) {
            $query->whereIn('brand_id', $this->selectedBrands);
        }

        switch ($this->sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'brand':
                $query->orderBy(Brand::select('name')->whereColumn('brands.id', 'products.brand_id'));
                break;
            case 'category':
                $query->orderBy(Category::select('name')->whereColumn('categories.id', 'products.category_id'));
                break;
            case 'popular':
                $query->withCount('orders')->orderByDesc('orders_count');
                break;
            case 'updated':
                $query->orderByDesc('updated_at');
                break;
            default:
                $query->orderByDesc('id');
                break;
        }

        $total = (clone $query)->count();
        $products = attachInstallmentData($query->limit($this->perPage)->get());

        $wishlistIds = auth()->check()
            ? auth()->user()->wishlist()->pluck('product_id')->all()
            : [];

        return view('livewire.shop-catalog', [
            'products' => $products,
            'hasMore' => $total > $this->perPage,
            'wishlistIds' => $wishlistIds,
        ]);
    }
}
