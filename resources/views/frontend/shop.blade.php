@extends('frontend.layouts.store')
@section('title', 'Shop — '.storeName())

@section('content')

<section class="os-section-sm bg-white border-b border-ink/10">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <span class="os-eyebrow"><i class="bi bi-grid-fill"></i> Shop</span>
                <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Find something to own</h1>
                <p class="mt-2 text-sm text-slate">{{ $products->total() ?? 0 }} products — pay them down at your own pace.</p>
            </div>
            <form action="{{ url('/shop') }}" method="GET" class="flex w-full max-w-sm items-center gap-2" role="search">
                @if(request()->query())
                    @foreach(request()->except(['search', 'page']) as $k => $v)
                        @if(!is_array($v))
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endif
                    @endforeach
                @endif
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products, brands…" aria-label="Search products" class="os-input">
                <button type="submit" class="os-btn os-btn-brand"><i class="bi bi-search"></i></button>
            </form>
        </div>
    </div>
</section>

<section class="os-section">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <div class="grid gap-8 lg:grid-cols-[240px_1fr]">

            <!-- ===== FILTER SIDEBAR ===== -->
            <aside class="lg:sticky lg:top-24 lg:self-start">
                <div class="os-card p-5">
                    <h2 class="font-display text-sm font-bold uppercase tracking-[0.12em] text-ink">Filters</h2>

                    <form action="{{ url('/shop') }}" method="GET" class="mt-4 space-y-5">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif

                        <div>
                            <label for="f-category" class="mb-1.5 block text-xs font-semibold text-slate">Category</label>
                            <select id="f-category" name="category_id" class="os-input" onchange="this.form.submit()">
                                <option value="">All categories</option>
                                @foreach($categories ?? [] as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="f-brand" class="mb-1.5 block text-xs font-semibold text-slate">Brand</label>
                            <select id="f-brand" name="brand_id" class="os-input" onchange="this.form.submit()">
                                <option value="">All brands</option>
                                @foreach($brands ?? [] as $brand)
                                    <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="f-sort" class="mb-1.5 block text-xs font-semibold text-slate">Sort by</label>
                            <select id="f-sort" name="sort" class="os-input" onchange="this.form.submit()">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A–Z</option>
                            </select>
                        </div>

                        <button type="submit" class="os-btn os-btn-brand w-full">Apply filters</button>
                        @if(request()->except('page')->count())
                            <a href="{{ url('/shop') }}" class="block text-center text-xs font-semibold text-slate transition-colors hover:text-brand">Clear all filters</a>
                        @endif
                    </form>
                </div>
            </aside>

            <!-- ===== PRODUCT GRID ===== -->
            <div>
                <div class="grid grid-cols-2 gap-4 sm:gap-6 xl:grid-cols-3">
                    @forelse($products ?? [] as $product)
                    <a href="{{ url('/product/'.$product->slug) }}" class="os-card os-card-hover group flex flex-col overflow-hidden">
                        <div class="relative aspect-square overflow-hidden bg-paper-deep">
                            @php $img = $product->primaryImage ?? $product->images->first(); @endphp
                            @if($img)
                                <img src="{{ asset('storage/'.$img->image_path) }}" alt="{{ $product->name }}" loading="lazy" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                            @else
                                <div class="flex h-full w-full items-center justify-center text-4xl text-ink/20"><i class="bi bi-image"></i></div>
                            @endif
                            @if($product->compare_price && $product->compare_price > $product->price)
                                @php $discount = round((($product->compare_price - $product->price) / $product->compare_price) * 100); @endphp
                                @if($discount > 0)
                                    <span class="absolute left-3 top-3 os-chip os-chip-ember">-{{ $discount }}%</span>
                                @endif
                            @endif
                        </div>
                        <div class="flex flex-1 flex-col p-4">
                            <h3 class="line-clamp-2 text-sm font-semibold leading-snug text-ink">{{ Str::limit($product->name, 46) }}</h3>
                            <div class="mt-2 flex items-baseline gap-2">
                                <span class="font-mono text-lg font-semibold text-brand">{{ formatPrice($product->price, 0) }}</span>
                                @if($product->compare_price)
                                    <span class="font-mono text-xs text-slate line-through">{{ formatPrice($product->compare_price, 0) }}</span>
                                @endif
                            </div>
                            <div class="mt-3 flex items-center justify-between border-t border-ink/5 pt-3">
                                <span class="os-chip os-chip-brand"><i class="bi bi-coin"></i> Flexible plans</span>
                                <x-progress-ring :percentage="25" amount="from" label="{{ $product->installment_from ? '₦'.number_format($product->installment_from, 0).'/mo' : '₦0/mo' }}" :size="44" :stroke="4" :animate="false"/>
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="col-span-full rounded-2xl border border-dashed border-ink/15 bg-white p-14 text-center">
                        <i class="bi bi-search text-4xl text-ink/15"></i>
                        <p class="mt-3 text-sm font-medium text-ink">No products match your filters</p>
                        <p class="mt-1 text-sm text-slate">Try clearing the filters or search for something else.</p>
                        <a href="{{ url('/shop') }}" class="os-btn os-btn-ghost os-btn-sm mt-5">Clear filters</a>
                    </div>
                    @endforelse
                </div>

                @if($products->hasPages())
                <div class="mt-10 flex justify-center">
                    {{ $products->withQueryString()->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
