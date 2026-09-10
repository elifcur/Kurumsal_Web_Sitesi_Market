@extends('template::layouts.app')
@section('seo')<x-seo :title="$service->meta_title ?: $service->title" :description="$service->meta_description ?: $service->excerpt" :image="$service->image" />@endsection
@section('content')
    @php($whatsapp = \Templates\Business\Support\WhatsApp::link('Merhaba, "'.$service->title.'" hizmeti için randevu almak istiyorum.'))
    <x-page-header :title="$service->title" :subtitle="$service->excerpt" :image="$service->image" :breadcrumbs="[['label' => setting('services_label', 'Hizmetlerimiz'), 'url' => route('services.index')], ['label' => $service->title]]" />
    <section class="section">
        <div class="container-x grid gap-12 lg:grid-cols-3">
            <div class="lg:col-span-2">
                @if($service->image)<img src="{{ upload_url($service->image) }}" alt="{{ $service->title }}" class="mb-8 w-full rounded-2xl object-cover">@endif
                <div class="prose-cms">{!! $service->content !!}</div>
            </div>
            <aside class="space-y-6">
                <div class="rounded-2xl bg-ink p-6 text-white">
                    <h3 class="font-display text-2xl">Randevu alın</h3>
                    <p class="mt-2 text-sm text-slate-300">Bir telefon ya da mesaj yeter; size en uygun saati ayarlayalım.</p>
                    <a href="{{ route('quote.show', ['hizmet' => $service->slug]) }}" class="btn-brand mt-5 w-full">Randevu Formu</a>
                    @if($whatsapp)<a href="{{ $whatsapp }}" target="_blank" rel="noopener" class="btn-whatsapp mt-3 w-full"><x-icon name="whatsapp" class="h-4 w-4" /> WhatsApp'tan yazın</a>@endif
                    @if(setting('phone'))<a href="{{ phone_href(setting('phone')) }}" class="mt-4 flex items-center justify-center gap-2 text-sm font-semibold"><x-icon name="phone" class="h-4 w-4" /> {{ setting('phone') }}</a>@endif
                </div>
                @if($others->isNotEmpty())
                    <div class="rounded-2xl border border-slate-200 p-6">
                        <h3 class="font-display text-xl text-ink">Diğer {{ setting('services_label', 'Hizmetler') }}</h3>
                        <ul class="mt-4 divide-y divide-slate-100">@foreach($others as $other)<li><a href="{{ $other->url() }}" class="flex items-center gap-3 py-3 text-sm text-slate-700 hover:text-primary"><x-icon :name="$other->icon ?: 'chevron-right'" class="h-4 w-4 text-primary" /> {{ $other->title }}</a></li>@endforeach</ul>
                    </div>
                @endif
                <a href="{{ route('prices.index') }}" class="btn-outline w-full"><x-icon name="currency" class="h-4 w-4" /> {{ setting('price_list_label', 'Fiyat Listesi') }}</a>
            </aside>
        </div>
    </section>
@endsection
