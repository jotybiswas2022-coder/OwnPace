@php
    // Collect Laravel session flashes to seed the Alpine toast host.
    $osFlashes = [];
    foreach (['success' => 'success', 'error' => 'error', 'warning' => 'warning', 'info' => 'info'] as $osKey => $osType) {
        if (session($osKey)) {
            $osFlashes[] = ['type' => $osType, 'message' => session($osKey)];
        }
    }
@endphp

<div
    class="pointer-events-none fixed inset-x-0 top-4 z-[80] flex flex-col items-center gap-2 px-4 sm:items-end sm:pr-6"
    aria-live="polite"
    aria-atomic="false"
    x-data="toastHost(@json($osFlashes))"
>
    <template x-for="(t, i) in toasts" :key="t.id">
        <div
            x-show="t.visible"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0 translate-y-1"
            class="pointer-events-auto flex w-full max-w-sm items-start gap-3 rounded-xl border bg-white p-4 shadow-lift"
            :class="{
                'border-grass/30': t.type === 'success',
                'border-ember/30': t.type === 'error',
                'border-mango/40': t.type === 'warning',
                'border-indigo/20': t.type === 'info'
            }"
            role="status"
        >
            <span
                class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full text-sm"
                :class="{
                    'bg-grass/10 text-grass-deep': t.type === 'success',
                    'bg-ember/10 text-ember-deep': t.type === 'error',
                    'bg-mango/15 text-mango-ink': t.type === 'warning',
                    'bg-indigo/10 text-brand': t.type === 'info'
                }"
            >
                <i :class="{
                    'bi bi-check-lg': t.type === 'success',
                    'bi bi-exclamation-triangle': t.type === 'error',
                    'bi bi-exclamation-circle': t.type === 'warning',
                    'bi bi-info-circle': t.type === 'info'
                }"></i>
            </span>
            <p class="flex-1 text-sm font-medium leading-snug text-ink" x-text="t.message"></p>
            <button type="button" class="text-slate transition-colors hover:text-ink" @click="dismiss(t.id)" aria-label="Dismiss notification">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    </template>
</div>
