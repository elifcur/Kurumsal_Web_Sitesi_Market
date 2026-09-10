@extends('template::layouts.app')
@section('seo')<x-seo :title="setting('gallery_label', 'Galeri')" description="Ustalarımızın elinden çıkan çalışmalar ve salonumuzdan kareler." />@endsection
@section('content')
    <x-page-header title="Çalışmalarımız" subtitle="Ustalarımızın elinden çıkan son işler ve salonumuzdan kareler." :breadcrumbs="[['label' => setting('gallery_label', 'Galeri')]]" />
    <section class="section">
        <div class="container-x">
            @if($items->isEmpty())
                <x-empty-state icon="photo" title="Galeri henüz boş" text="Yakında çalışmalarımızı burada paylaşacağız." />
            @else
                <x-gallery-grid :items="$items" :categories="$categories" />
            @endif
            @if(setting('instagram'))
                <div class="mt-12 flex flex-col items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-cream p-6 text-center md:flex-row md:text-left">
                    <div class="flex items-center gap-4"><span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary-light text-primary"><x-icon name="instagram" class="h-6 w-6" /></span><p class="text-slate-700">{{ setting('instagram_feed_note', 'Güncel çalışmalarımız için Instagram hesabımızı takip edin.') }}</p></div>
                    <a href="{{ setting('instagram') }}" target="_blank" rel="noopener" class="btn-outline shrink-0">Instagram'da Gör <x-icon name="external" class="h-4 w-4" /></a>
                </div>
            @endif
        </div>
    </section>
@endsection
