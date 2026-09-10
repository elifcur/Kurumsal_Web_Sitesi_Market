@extends('template::layouts.app')
@section('seo')<x-seo :title="setting('price_list_label', 'Fiyat Listesi')" description="Güncel hizmet fiyatlarımız. Listede yazan fiyat, ödediğiniz fiyattır." />@endsection
@section('content')
    @php($whatsapp = \Templates\Business\Support\WhatsApp::link('Merhaba, fiyat listesi hakkında bilgi almak istiyorum.'))
    <x-page-header :title="setting('price_list_label', 'Fiyat Listesi')" subtitle="Sürpriz yok: listede yazan fiyat, kasada ödediğiniz fiyattır. Ürüne göre değişen hizmetlerde başlangıç fiyatı belirtilir." :breadcrumbs="[['label' => setting('price_list_label', 'Fiyat Listesi')]]" />
    <section class="section">
        <div class="container-x">
            @if($total === 0)
                <x-empty-state icon="currency" title="Fiyat listesi henüz eklenmedi" text="Fiyat bilgisi için bizi arayabilirsiniz." />
            @else
                <x-price-list :groups="$groups" />
            @endif
            <div class="mt-12 flex flex-col items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-cream p-6 text-center md:flex-row md:text-left">
                <div><p class="font-display text-2xl text-ink">{!! emphasize('Hazırsanız *randevu* alalım') !!}</p><p class="mt-1 text-sm text-slate-600">Formu doldurun ya da WhatsApp'tan yazın, en uygun saati birlikte bulalım.</p></div>
                <div class="flex shrink-0 flex-wrap justify-center gap-3">
                    <a href="{{ route('quote.show') }}" class="btn-primary">Randevu / Sipariş <x-icon name="arrow-right" class="h-4 w-4" /></a>
                    @if($whatsapp)<a href="{{ $whatsapp }}" target="_blank" rel="noopener" class="btn-whatsapp"><x-icon name="whatsapp" class="h-4 w-4" /> WhatsApp'tan sor</a>@endif
                </div>
            </div>
        </div>
    </section>
@endsection
