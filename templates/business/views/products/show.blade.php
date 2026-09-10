@extends('template::layouts.app')
@section('seo')<x-seo :title="$product->meta_title ?: $product->title" :description="$product->meta_description ?: $product->excerpt" :image="$product->image" type="product" />@endsection
@section('content')
    @php($images = array_values(array_filter(array_unique([$product->image, ...($product->gallery ?? [])]))))
    @php($specs = array_values(array_filter(is_array($product->specs) ? $product->specs : [], fn ($row) => ! empty($row['label']))))
    @php($whatsapp = \Templates\Business\Support\WhatsApp::link('Merhaba, "'.$product->title.'" ürününü sipariş etmek istiyorum.'))
    <x-page-header :title="$product->title" :subtitle="$product->excerpt" :breadcrumbs="array_values(array_filter([['label' => setting('products_label', 'Ürünler'), 'url' => route('products.index')], $product->category ? ['label' => $product->category->name, 'url' => route('products.index', ['kategori' => $product->category->slug])] : null, ['label' => \Illuminate\Support\Str::limit($product->title, 40)]]))" />

    <section class="section !pt-10" x-data="gallery(@js(array_map(fn ($p) => upload_url($p), $images)))">
        <div class="container-x grid gap-12 lg:grid-cols-5">
            <div class="lg:col-span-3">
                <div class="relative aspect-square overflow-hidden rounded-2xl border border-slate-200 bg-cream md:aspect-[4/3]">
                    <template x-if="current"><img :src="current" alt="{{ $product->title }}" class="h-full w-full object-cover"></template>
                    <template x-if="!current"><div class="flex h-full items-center justify-center text-primary/30"><x-icon name="box" class="h-24 w-24" /></div></template>
                    <template x-if="images.length > 1">
                        <div>
                            <button type="button" @click="prev()" class="absolute top-1/2 left-3 -translate-y-1/2 rounded-full bg-white/90 p-2 text-ink shadow hover:bg-white" aria-label="Önceki"><x-icon name="chevron-left" class="h-5 w-5" /></button>
                            <button type="button" @click="next()" class="absolute top-1/2 right-3 -translate-y-1/2 rounded-full bg-white/90 p-2 text-ink shadow hover:bg-white" aria-label="Sonraki"><x-icon name="chevron-right" class="h-5 w-5" /></button>
                            <span class="absolute right-3 bottom-3 rounded-full bg-ink/80 px-2.5 py-1 text-xs font-semibold text-white" x-text="(index + 1) + ' / ' + images.length"></span>
                        </div>
                    </template>
                </div>
                <template x-if="images.length > 1">
                    <div class="mt-4 grid grid-cols-4 gap-3 sm:grid-cols-6">
                        <template x-for="(img, i) in images" :key="i">
                            <button type="button" @click="index = i" class="aspect-square overflow-hidden rounded-lg border-2" :class="index === i ? 'border-primary' : 'border-transparent'"><img :src="img" alt="" class="h-full w-full object-cover" loading="lazy"></button>
                        </template>
                    </div>
                </template>

                @if($product->content)
                    <div class="mt-10">
                        <h2 class="mb-4 font-display text-2xl text-ink">Ürün Hakkında</h2>
                        <div class="prose-cms">{!! $product->content !!}</div>
                    </div>
                @endif

                @if($specs !== [])
                    <div class="mt-10">
                        <h2 class="mb-4 font-display text-2xl text-ink">Özellikler</h2>
                        <dl class="grid gap-3 sm:grid-cols-2">
                            @foreach($specs as $row)<div class="rounded-xl border border-slate-200 bg-white px-4 py-3"><dt class="text-xs text-slate-500">{{ $row['label'] }}</dt><dd class="font-semibold text-ink">{{ $row['value'] ?? '' }}</dd></div>@endforeach
                        </dl>
                    </div>
                @endif
            </div>

            <aside class="lg:col-span-2">
                <div class="sticky top-24 space-y-5">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex flex-wrap items-center gap-2 text-xs">
                            @if($product->category)<a href="{{ route('products.index', ['kategori' => $product->category->slug]) }}" class="chip-primary">{{ $product->category->name }}</a>@endif
                            @if($product->sku)<span class="chip font-mono">{{ $product->sku }}</span>@endif
                        </div>
                        <p class="mt-4 font-display text-4xl text-ink">{{ $product->formattedPrice() ?? ($product->price_note ?: 'Fiyat sorunuz') }}</p>
                        @if($product->formattedPrice() && $product->price_note)<p class="mt-1 text-xs text-slate-500">{{ $product->price_note }}</p>@endif
                        @if($whatsapp)<a href="{{ $whatsapp }}" target="_blank" rel="noopener" class="btn-whatsapp mt-5 w-full !py-3.5"><x-icon name="whatsapp" class="h-5 w-5" /> WhatsApp'tan sipariş ver</a>@endif
                        <a href="{{ route('quote.show', ['urun' => $product->slug]) }}" class="{{ $whatsapp ? 'btn-outline' : 'btn-brand' }} mt-3 w-full">Sipariş Formu <x-icon name="arrow-right" class="h-4 w-4" /></a>
                        @if(setting('phone'))<a href="{{ phone_href(setting('phone')) }}" class="mt-4 flex items-center justify-center gap-2 text-sm font-semibold text-slate-700 hover:text-primary"><x-icon name="phone" class="h-4 w-4 text-primary" /> {{ setting('phone') }}</a>@endif
                    </div>
                    <ul class="rounded-2xl border border-slate-200 bg-cream p-6 text-sm text-slate-700">
                        @foreach(['Salonda kullandığımız orijinal ürünler', 'Salondan teslim ya da kargo', 'Nasıl kullanılacağını ustamız anlatır'] as $item)
                            <li class="flex items-center gap-2 py-1.5"><x-icon name="check-circle" class="h-4 w-4 shrink-0 text-primary" /> {{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
            </aside>
        </div>
    </section>

    @if($related->isNotEmpty())
        <section class="section border-t border-slate-200 bg-slate-50 !pt-16"><div class="container-x"><h2 class="section-title mb-8 !text-3xl">Benzer Ürünler</h2><div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">@foreach($related as $item)<x-product-card :product="$item" />@endforeach</div></div></section>
    @endif
@endsection
