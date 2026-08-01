<div>
    <section class="os-section-sm bg-white border-b border-ink/10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <span class="os-eyebrow"><i class="bi bi-grid-fill"></i> Shop</span>
                    <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Find something to own</h1>
                    <p class="mt-2 text-sm text-slate">{{ $products->count() }} products shown — pay them down at your own pace.</p>
                </div>
                <div class="relative w-full max-w-sm">
                    <i class="bi bi-search pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-slate"></i>
                    <input
                        type="search"
                        wire:model.live.debounce.350ms="search"
                        placeholder="Search products, brands…"
                        aria-label="Search products"
                        class="os-input w-full pl-10"
                    >
                </div>
            </div>
        </div>
    </section>

    <section class="os-section">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="grid gap-8 lg:grid-cols-[240px_1fr]">

                <!-- ===== FILTER SIDEBAR ===== -->
                <aside class="lg:sticky lg:top-24 lg:self-start">
                    <div class="os-card p-5">
                        <div class="flex items-center justify-between">
                            <h2 class="font-display text-sm font-bold uppercase tracking-[0.12em] text-ink">Filters</h2>
                            @if(count($selectedCategories) || count($selectedBrands) || trim($search) !== '')
                                <button type="button" wire:click="resetFilters" class="text-xs font-semibold text-brand transition-colors hover:text-ember">Clear</button>
                            @endif
                        </div>

                        <!-- Category (multi-select) -->
                        <div class="mt-5">
                            <p class="mb-2 text-xs font-semibold text-slate">Category</p>
                            <div class="space-y-1.5">
                                @foreach($this->categories as $category)
                                <label class="flex cursor-pointer items-center gap-2 rounded-lg px-2 py-1.5 text-sm text-ink transition-colors hover:bg-brand/5">
                                    <input
                                        type="checkbox"
                                        value="{{ $category->id }}"
                                        wire:model.live="selectedCategories"
                                        class="h-4 w-4 rounded border-ink/20 accent-brand"
                                    >
                                    {{ $category->name }}
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Brand (multi-select) -->
                        <div class="mt-5 border-t border-ink/5 pt-5">
                            <p class="mb-2 text-xs font-semibold text-slate">Brand</p>
                            <div class="space-y-1.5">
                                @foreach($this->brands as $brand)
                                <label class="flex cursor-pointer items-center gap-2 rounded-lg px-2 py-1.5 text-sm text-ink transition-colors hover:bg-brand/5">
                                    <input
                                        type="checkbox"
                                        value="{{ $brand->id }}"
                                        wire:model.live="selectedBrands"
                                        class="h-4 w-4 rounded border-ink/20 accent-brand"
                                    >
                                    {{ $brand->name }}
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Sort -->
                        <div class="mt-5 border-t border-ink/5 pt-5">
                            <label for="f-sort" class="mb-2 block text-xs font-semibold text-slate">Sort by</label>
                            <select id="f-sort" wire:model.live="sort" class="os-input w-full">
                                @foreach($this->sortOptions as $value => $label)
                                    <option value="{{ $value }}" {{ $sort === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </aside>

                <!-- ===== PRODUCT GRID ===== -->
                <div>
                    <div class="grid grid-cols-2 gap-4 sm:gap-6 xl:grid-cols-3">
                        @forelse($products as $product)
                        <a href="{{ url('/product/'.$product->slug) }}" class="os-card os-card-hover group flex flex-col overflow-hidden">
                            <div class="relative aspect-square overflow-hidden bg-paper-deep">
                                @php $img = $product->primaryImage ?? $product->images->first(); @endphp
                                @if($img)
                                    <img src="{{ imageUrl($img->image_path) }}" alt="{{ $product->name }}" loading="lazy" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-4xl text-ink/20"><i class="bi bi-image"></i></div>
                                @endif
                            </div>
                            <div class="flex flex-1 flex-col p-4">
                                <h3 class="line-clamp-2 text-sm font-semibold leading-snug text-ink">{{ Str::limit($product->name, 46) }}</h3>
                                <div class="mt-2 flex items-baseline gap-2">
                                    <span class="font-mono text-lg font-semibold text-brand">{{ formatPrice($product->price, 0) }}</span>
                                </div>
                                <div class="mt-3 flex items-center justify-between border-t border-ink/5 pt-3">
                                    <span class="os-chip os-chip-brand"><i class="bi bi-coin"></i> Flexible plans</span>
                                    <x-progress-ring
                                        :percentage="25"
                                        amount="from"
                                        label="{{ $product->installment_from ? currency().number_format($product->installment_from, 0).'/mo' : currency().'0/mo' }}"
                                        :size="44"
                                        :stroke="4"
                                        :animate="false"
                                    />
                                </div>
                            </div>
                        </a>
                        @empty
                        <div class="col-span-full rounded-2xl border border-dashed border-ink/15 bg-white p-14 text-center">
                            <i class="bi bi-search text-4xl text-ink/15"></i>
                            <p class="mt-3 text-sm font-medium text-ink">No products match your filters</p>
                            <p class="mt-1 text-sm text-slate">Try clearing the filters or search for something else.</p>
                            <button type="button" wire:click="resetFilters" class="os-btn os-btn-ghost os-btn-sm mt-5">Clear filters</button>
                        </div>
                        @endforelse
                    </div>

                    <!-- Infinite scroll sentinel + fallback button -->
                    @if($hasMore)
                        <div wire:intersect="loadMore" class="mt-10 flex justify-center">
                            <button type="button" wire:click="loadMore" class="os-btn os-btn-ghost">
                                <i class="bi bi-arrow-down-circle"></i> Load more products
                            </button>
                        </div>
                    @else
                        @if($products->count() > 0)
                            <p class="mt-10 text-center text-xs font-medium uppercase tracking-[0.12em] text-slate">You've seen everything</p>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
