@props(['groups', 'compact' => false])
{{-- $groups: Collection<group name, Collection<PriceItem>> ("" anahtarı grupsuz kalemler) --}}
<div {{ $attributes->merge(['class' => 'grid gap-8 '.($compact ? '' : 'lg:grid-cols-2')]) }}>
    @foreach($groups as $group => $items)
        <div class="rounded-2xl border border-slate-200 bg-white p-6 md:p-8">
            @if($group !== '')<h3 class="mb-2 font-display text-2xl text-ink">{{ $group }}</h3>@endif
            <ul class="divide-y divide-slate-100">
                @foreach($items as $item)
                    <li class="price-row">
                        <div class="min-w-0">
                            <p class="font-semibold text-ink">{{ $item->name }}</p>
                            @if($item->description && ! $compact)<p class="mt-0.5 text-sm text-slate-500">{{ $item->description }}</p>@endif
                        </div>
                        <span class="price-row-dots" aria-hidden="true"></span>
                        <div class="flex shrink-0 items-baseline gap-2 text-right">
                            @if($item->formattedPrice())
                                <span class="price-row-amount">{{ $item->formattedPrice() }}</span>
                                @if($item->price_note)<span class="text-xs text-slate-500">{{ $item->price_note }}</span>@endif
                            @else
                                <span class="text-sm font-medium text-slate-500">{{ $item->price_note ?: 'Fiyat sorunuz' }}</span>
                            @endif
                            @if(! $compact && $item->whatsappUrl())<a href="{{ $item->whatsappUrl() }}" target="_blank" rel="noopener" class="ml-2 inline-flex items-center gap-1 text-xs font-semibold text-emerald-700 hover:underline" aria-label="{{ $item->name }} için WhatsApp'tan sor"><x-icon name="whatsapp" class="h-4 w-4" /> <span class="hidden sm:inline">WhatsApp'tan sor</span></a>@endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach
</div>
