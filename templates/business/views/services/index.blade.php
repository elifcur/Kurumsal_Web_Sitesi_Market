@extends('template::layouts.app')
@section('seo')<x-seo :title="setting('services_label', 'Hizmetlerimiz')" :description="setting('services_intro')" />@endsection
@section('content')
    <x-page-header :title="setting('services_label', 'Hizmetlerimiz')" :subtitle="setting('services_intro')" :breadcrumbs="[['label' => setting('services_label', 'Hizmetlerimiz')]]" />
    <section class="section">
        <div class="container-x">
            @if($services->isEmpty())
                <x-empty-state icon="sparkles" title="Henüz hizmet eklenmedi" />
            @else
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">@foreach($services as $service)<x-service-card :service="$service" />@endforeach</div>
                <div class="mt-10">{{ $services->links() }}</div>
            @endif
            <div class="mt-12 flex flex-col items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-cream p-6 text-center md:flex-row md:text-left">
                <div><p class="font-display text-2xl text-ink">Fiyatları merak mı ettiniz?</p><p class="mt-1 text-sm text-slate-600">Tüm hizmetlerimizin güncel fiyatları listede.</p></div>
                <a href="{{ route('prices.index') }}" class="btn-outline shrink-0">{{ setting('price_list_label', 'Fiyat Listesi') }} <x-icon name="arrow-right" class="h-4 w-4" /></a>
            </div>
        </div>
    </section>
    @if(setting('cta_title'))
        <section class="bg-ink text-white"><div class="container-x flex flex-col items-center justify-between gap-6 py-14 text-center md:flex-row md:text-left"><div><h2 class="font-display text-3xl md:text-4xl">{!! emphasize(setting('cta_title')) !!}</h2>@if(setting('cta_text'))<p class="mt-2 text-slate-300">{{ setting('cta_text') }}</p>@endif</div>@if(setting('cta_button_text'))<a href="{{ setting('cta_button_url', route('quote.show')) }}" class="btn-brand shrink-0">{{ setting('cta_button_text') }}</a>@endif</div></section>
    @endif
@endsection
