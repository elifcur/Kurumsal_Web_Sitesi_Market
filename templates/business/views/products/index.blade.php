@extends('template::layouts.app')
@section('content')
    @php($heading = $currentCategory?->name ?? setting('products_label', 'Ürünler'))
    @section('seo')<x-seo :title="$heading" :description="$currentCategory?->description ?: setting('products_intro')" :robots="request()->hasAny(['q', 'page']) ? 'noindex, follow' : 'index, follow'" />@endsection
    <x-page-header :title="$heading" :subtitle="$currentCategory?->description ?: setting('products_intro', 'Salonumuzda kullandığımız ürünleri evinize de götürün.')" :image="$currentCategory?->image" :breadcrumbs="array_values(array_filter([['label' => setting('products_label', 'Ürünler'), 'url' => route('products.index')], $currentCategory ? ['label' => $currentCategory->name] : null]))" />
    <section class="section !pt-10">
        <div class="container-x">
            <div class="mb-10 flex flex-col gap-4 border-b border-slate-200 pb-6 lg:flex-row lg:items-center lg:justify-between">
                <nav class="flex flex-wrap gap-2" aria-label="Ürün kategorileri">
                    <a href="{{ route('products.index') }}" @class(['tab-pill', 'tab-pill-active' => ! $currentCategory])>Tümü</a>
                    @foreach($categories as $category)
                        <a href="{{ route('products.index', ['kategori' => $category->slug]) }}" @class(['tab-pill', 'tab-pill-active' => $currentCategory?->id === $category->id])>{{ $category->name }} <span class="opacity-60">({{ $category->products_count }})</span></a>
                    @endforeach
                </nav>
                <form method="get" action="{{ route('products.index') }}" class="relative w-full lg:w-72">
                    @if($currentCategory)<input type="hidden" name="kategori" value="{{ $currentCategory->slug }}">@endif
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Ürün ara..." class="form-input !pr-10">
                    <button type="submit" class="absolute top-1/2 right-3 -translate-y-1/2 text-slate-400 hover:text-primary" aria-label="Ara"><x-icon name="search" /></button>
                </form>
            </div>

            @if($products->isEmpty())
                <x-empty-state icon="box" title="Ürün bulunamadı" text="Aradığınız ürün listede yoksa bize sorabilirsiniz." />
                <div class="mt-6 text-center"><a href="{{ route('quote.show') }}" class="btn-primary">Sipariş / Bilgi İste</a></div>
            @else
                <p class="mb-4 text-sm text-slate-500"><strong class="text-ink">{{ $products->total() }}</strong> ürün listeleniyor</p>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">@foreach($products as $product)<x-product-card :product="$product" />@endforeach</div>
                <div class="mt-10">{{ $products->links() }}</div>
            @endif
        </div>
    </section>
    @php($whatsapp = \Templates\Business\Support\WhatsApp::link('Merhaba, ürünleriniz hakkında bilgi almak istiyorum.'))
    <section class="border-t border-slate-200 bg-cream py-12">
        <div class="container-x flex flex-col items-center justify-between gap-4 text-center md:flex-row md:text-left">
            <div><h2 class="font-display text-2xl text-ink">{!! emphasize("Sipariş için *yazmanız* yeterli") !!}</h2><p class="mt-1 text-sm text-slate-600">Ürünü ayırtalım, salondan teslim alın ya da kargo ile gönderelim.</p></div>
            <div class="flex shrink-0 flex-wrap gap-3">
                @if($whatsapp)<a href="{{ $whatsapp }}" target="_blank" rel="noopener" class="btn-whatsapp"><x-icon name="whatsapp" class="h-4 w-4" /> WhatsApp'tan sor</a>@endif
                <a href="{{ route('quote.show') }}" class="btn-primary">Sipariş Formu</a>
            </div>
        </div>
    </section>
@endsection
