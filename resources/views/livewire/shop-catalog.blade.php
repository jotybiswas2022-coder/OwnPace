<div>
    <!-- ===== HEADER ===== -->
    <section class="relative overflow-hidden border-b border-ink/5 bg-white">
        <div class="pointer-events-none absolute inset-0" aria-hidden="true">
            <div class="absolute -top-24 right-[8%] h-60 w-96 rounded-full bg-mango/10 blur-3xl"></div>
            <div class="absolute -bottom-28 left-[5%] h-60 w-80 rounded-full bg-brand/10 blur-3xl"></div>
        </div>

        <div class="relative mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-14">
            <div class="flex flex-wrap items-end justify-between gap-5">
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

            <!-- Active filter chips -->
            @php
                $activeChips = [];
                foreach ($selectedCategories as $cid) {
                    $name = $this->categories->firstWhere('id', $cid)?->name;
                    if ($name) $activeChips[] = ['key' => 'categories', 'id' => $cid, 'label' => $name];
                }
                foreach ($selectedBrands as $bid) {
                    $name = $this->brands->firstWhere('id', $bid)?->name;
                    if ($name) $activeChips[] = ['key' => 'brands', 'id' => $bid, 'label' => $name];
                }
            @endphp
            @if(trim($search) !== '' || count($activeChips))
            <div class="mt-6 flex flex-wrap items-center gap-2">
                <span class="text-xs font-semibold uppercase tracking-[0.12em] text-slate">Active:</span>
                @if(trim($search) !== '')
                <button type="button" wire:click="clearSearch" class="os-chip os-chip-brand transition-colors hover:bg-ember/10 hover:text-ember-deep" title="Remove search">
                    <i class="bi bi-search"></i> "{{ Str::limit($search, 24) }}" <i class="bi bi-x-lg text-[9px]"></i>
                </button>
                @endif
                @foreach($activeChips as $chip)
                <button type="button" wire:click="removeFilter('{{ $chip['key'] }}', {{ $chip['id'] }})" class="os-chip os-chip-brand transition-colors hover:bg-ember/10 hover:text-ember-deep" title="Remove filter">
                    {{ $chip['label'] }} <i class="bi bi-x-lg text-[9px]"></i>
                </button>
                @endforeach
                <button type="button" wire:click="resetFilters" class="text-xs font-semibold text-brand underline-offset-2 transition-colors hover:text-ember hover:underline">Clear all</button>
            </div>
            @endif
        </div>
    </section>

    <!-- ===== CATALOG ===== -->
    <section class="os-section">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="grid gap-8 lg:grid-cols-[250px_1fr]">

                <!-- ===== FILTER SIDEBAR ===== -->
                <aside class="lg:sticky lg:top-24 lg:self-start">
                    <div class="glass rounded-2xl p-5 shadow-soft">
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
                                @forelse($this->categories as $category)
                                <label class="flex cursor-pointer items-center gap-2 rounded-lg px-2 py-1.5 text-sm text-ink transition-colors hover:bg-brand/5">
                                    <input
                                        type="checkbox"
                                        value="{{ $category->id }}"
                                        wire:model.live="selectedCategories"
                                        class="h-4 w-4 rounded border-ink/20 accent-brand"
                                    >
                                    <span class="min-w-0 flex-1 truncate">{{ $category->name }}</span>
                                    <span class="font-mono text-[10px] text-slate">{{ $category->products_count ?? 0 }}</span>
                                </label>
                                @empty
                                <p class="px-2 py-1 text-sm text-slate">No categories yet.</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Brand (multi-select) -->
                        <div class="mt-5 border-t border-ink/5 pt-5">
                            <p class="mb-2 text-xs font-semibold text-slate">Brand</p>
                            <div class="space-y-1.5">
                                @forelse($this->brands as $brand)
                                <label class="flex cursor-pointer items-center gap-2 rounded-lg px-2 py-1.5 text-sm text-ink transition-colors hover:bg-brand/5">
                                    <input
                                        type="checkbox"
                                        value="{{ $brand->id }}"
                                        wire:model.live="selectedBrands"
                                        class="h-4 w-4 rounded border-ink/20 accent-brand"
                                    >
                                    <span class="min-w-0 flex-1 truncate">{{ $brand->name }}</span>
                                </label>
                                @empty
                                <p class="px-2 py-1 text-sm text-slate">No brands yet.</p>
                                @endforelse
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
                        <div wire:key="product-{{ $product->id }}">
                            <x-frontend.pcard :product="$product" :wishlist-ids="$wishlistIds"/>
                        </div>
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
