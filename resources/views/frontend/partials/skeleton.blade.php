{{--
    Skeleton loading block. Wrap in a container with the same
    grid/card layout as the real content so nothing jumps.
    Props: lines (number of text lines), variant (card|list|circle).
--}}
@props(['variant' => 'card', 'lines' => 3])

@if($variant === 'card')
    <div class="os-card os-card-hover flex flex-col overflow-hidden" aria-hidden="true">
        <div class="os-skeleton aspect-square w-full rounded-none"></div>
        <div class="space-y-3 p-4">
            <div class="os-skeleton h-3.5 w-3/4"></div>
            <div class="os-skeleton h-3.5 w-1/2"></div>
            <div class="os-skeleton h-8 w-full rounded-md"></div>
        </div>
    </div>
@elseif($variant === 'list')
    <div class="os-card space-y-4 p-5" aria-hidden="true">
        @for($osS = 0; $osS < $lines; $osS++)
            <div class="flex items-center gap-4">
                <div class="os-skeleton os-skeleton-circle h-11 w-11 shrink-0"></div>
                <div class="flex-1 space-y-2">
                    <div class="os-skeleton h-3.5 w-2/3"></div>
                    <div class="os-skeleton h-3 w-1/3"></div>
                </div>
            </div>
        @endfor
    </div>
@else
    <div class="space-y-3" aria-hidden="true">
        @for($osS = 0; $osS < $lines; $osS++)
            <div class="os-skeleton h-3.5 w-full"></div>
        @endfor
    </div>
@endif
