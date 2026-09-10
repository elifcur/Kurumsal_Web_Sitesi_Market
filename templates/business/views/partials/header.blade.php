@php
    $siteName = setting('site_name', config('app.name'));
    $logo = upload_url(setting('logo'));
    $mapUrl = \Templates\Business\Support\Location::mapUrl();
    $mapExternal = \Templates\Business\Support\Location::isExternal();
    $whatsapp = \Templates\Business\Support\WhatsApp::link('Merhaba, sipariş vermek istiyorum.');
@endphp
<header class="sticky top-0 z-50 bg-white shadow-sm" :class="scrolled && 'shadow-md'">
    {{-- Üst sıra: logo · ürün arama · telefon / WhatsApp / sipariş --}}
    <div class="container-x flex h-16 items-center justify-between gap-4 md:h-20 lg:gap-6">
        <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-2.5" aria-label="{{ $siteName }}">
            @if($logo)
                <img src="{{ $logo }}" alt="{{ $siteName }}" class="h-9 w-auto md:h-11">
            @else
                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary text-primary-contrast"><x-icon name="box" class="h-5 w-5" /></span>
                <span class="leading-tight"><span class="block font-display text-xl text-ink">{{ $siteName }}</span>@if(setting('site_tagline'))<span class="block text-[11px] text-slate-400">{{ setting('site_tagline') }}</span>@endif</span>
            @endif
        </a>

        <form method="get" action="{{ route('products.index') }}" class="relative hidden flex-1 md:block lg:max-w-2xl">
            <label for="site-search" class="sr-only">Ürün ara</label>
            <x-icon name="search" class="pointer-events-none absolute top-1/2 left-4 h-4 w-4 -translate-y-1/2 text-slate-400" />
            <input id="site-search" type="search" name="q" value="{{ request('q') }}" maxlength="100" placeholder="{{ setting('search_placeholder', 'Binlerce ürün arasında arama yapın...') }}" class="search-input">
            <button type="submit" class="search-submit">Ara</button>
        </form>

        <div class="flex shrink-0 items-center gap-3 xl:gap-6">
            @if(setting('phone'))
                <a href="{{ phone_href(setting('phone')) }}" class="header-action hidden lg:flex">
                    <x-icon name="phone" class="h-5 w-5 shrink-0 text-primary" />
                    <span><span class="header-action-title">{{ setting('phone') }}</span><span class="header-action-note hidden xl:block">Bizi Arayın</span></span>
                </a>
            @endif
            @if($whatsapp)
                <a href="{{ $whatsapp }}" target="_blank" rel="noopener" class="header-action hidden lg:flex" aria-label="WhatsApp ile sipariş verin">
                    <x-icon name="whatsapp" class="h-5 w-5 shrink-0 text-whatsapp" />
                    <span class="hidden xl:block"><span class="header-action-title">WhatsApp</span><span class="header-action-note">Sipariş Ver</span></span>
                </a>
            @endif
            <a href="{{ route('quote.show') }}" class="btn-primary hidden shrink-0 !py-2.5 whitespace-nowrap lg:inline-flex"><x-icon name="truck" class="h-4 w-4" /> Sipariş Ver</a>
            <button type="button" class="rounded-lg p-2 text-slate-700 hover:bg-slate-100 lg:hidden" @click="mobileOpen = !mobileOpen" :aria-expanded="mobileOpen" aria-label="Menüyü aç/kapat">
                <span x-show="!mobileOpen"><x-icon name="menu" class="h-6 w-6" /></span>
                <span x-show="mobileOpen" x-cloak><x-icon name="x" class="h-6 w-6" /></span>
            </button>
        </div>
    </div>

    {{-- Alt sıra: kategoriler · menü · mağaza --}}
    <div class="hidden border-t border-slate-100 lg:block">
        <div class="container-x flex h-14 items-center gap-4 xl:gap-8">
            <a href="{{ route('products.index') }}" class="btn-brand !py-2.5 shrink-0"><x-icon name="menu" class="h-4 w-4" /> Tüm Kategoriler</a>
            <nav class="flex flex-1 items-center gap-0.5 xl:gap-1" aria-label="Ana menü">
                @foreach($siteNav as $item)
                    <a href="{{ $item['url'] }}" @class(['nav-link', 'nav-link-active' => $item['active']])>{{ $item['label'] }}</a>
                @endforeach
            </nav>
            <a href="{{ $mapUrl }}" @if($mapExternal) target="_blank" rel="noopener" @endif class="header-action ml-auto hidden shrink-0 xl:flex">
                <x-icon name="map-pin" class="h-5 w-5 shrink-0 text-primary" />
                <span><span class="header-action-title">Size En Yakın Mağaza</span><span class="header-action-note">Mağaza Bul</span></span>
                <x-icon name="chevron-right" class="h-4 w-4 text-slate-300" />
            </a>
        </div>
    </div>

    {{-- Mobil: arama + menü --}}
    <div class="container-x pb-3 md:hidden">
        <form method="get" action="{{ route('products.index') }}" class="relative">
            <label for="site-search-mobile" class="sr-only">Ürün ara</label>
            <x-icon name="search" class="pointer-events-none absolute top-1/2 left-4 h-4 w-4 -translate-y-1/2 text-slate-400" />
            <input id="site-search-mobile" type="search" name="q" value="{{ request('q') }}" maxlength="100" placeholder="Ürün ara..." class="search-input !pr-20">
            <button type="submit" class="search-submit !px-4">Ara</button>
        </form>
    </div>

    <div x-show="mobileOpen" x-cloak x-transition.opacity class="fixed inset-0 top-16 z-40 bg-ink/40 lg:hidden" @click="mobileOpen = false"></div>
    <nav x-show="mobileOpen" x-cloak x-transition class="absolute inset-x-0 top-full z-50 max-h-[calc(100vh-4rem)] overflow-y-auto border-t border-slate-200 bg-white shadow-xl lg:hidden" aria-label="Mobil menü">
        <div class="container-x flex flex-col py-3">
            @foreach($siteNav as $item)
                <a href="{{ $item['url'] }}" @class(['flex items-center justify-between rounded-lg px-3 py-3 text-base font-medium', 'text-primary bg-primary-light' => $item['active'], 'text-slate-800 hover:bg-slate-50' => !$item['active']])>
                    {{ $item['label'] }} <x-icon name="chevron-right" class="h-4 w-4 text-slate-400" />
                </a>
            @endforeach
            <div class="mt-3 grid grid-cols-2 gap-2">
                <a href="{{ route('quote.show') }}" class="btn-primary w-full"><x-icon name="truck" class="h-4 w-4" /> Sipariş Ver</a>
                <a href="{{ $mapUrl }}" @if($mapExternal) target="_blank" rel="noopener" @endif class="btn-outline w-full"><x-icon name="map-pin" class="h-4 w-4" /> Mağazamız</a>
            </div>
            <div class="mt-3 flex flex-col gap-2 border-t border-slate-200 pt-4 text-sm text-slate-600">
                @if(setting('phone'))<a href="{{ phone_href(setting('phone')) }}" class="flex items-center gap-2 px-3"><x-icon name="phone" class="h-4 w-4 text-primary" /> {{ setting('phone') }}</a>@endif
                @if(setting('working_hours'))<span class="flex items-center gap-2 px-3"><x-icon name="clock" class="h-4 w-4 text-primary" /> {{ setting('working_hours') }}</span>@endif
                @if(setting('address'))<span class="flex items-center gap-2 px-3"><x-icon name="map-pin" class="h-4 w-4 text-primary" /> {{ setting('address') }}</span>@endif
            </div>
        </div>
    </nav>
</header>
