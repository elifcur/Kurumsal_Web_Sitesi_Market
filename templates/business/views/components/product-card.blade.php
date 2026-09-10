@props(['product'])
@php($whatsapp = \Templates\Business\Support\WhatsApp::link('Merhaba, "'.$product->title.'" ürününü sipariş etmek istiyorum.'))
<article class="card group flex flex-col overflow-hidden">
    <a href="{{ $product->url() }}" class="relative flex aspect-[4/3] items-center justify-center overflow-hidden bg-cream p-6">
        @if($product->image)
            <img src="{{ upload_url($product->image) }}" alt="{{ $product->title }}" class="max-h-full w-auto object-contain transition duration-300 group-hover:scale-105" loading="lazy">
        @else
            <x-icon name="box" class="h-14 w-14 text-primary/30" />
        @endif
        @if($product->is_featured)<span class="absolute top-3 left-3 rounded-md bg-accent px-2.5 py-1 text-[11px] font-semibold text-white">Çok Satan</span>@endif
    </a>
    <div class="flex flex-1 flex-col p-5">
        @if($product->category)<a href="{{ route('products.index', ['kategori' => $product->category->slug]) }}" class="text-[11px] font-semibold tracking-[0.12em] text-primary uppercase">{{ $product->category->name }}</a>@endif
        <h3 class="mt-1 text-base font-semibold text-ink"><a href="{{ $product->url() }}" class="hover:text-primary">{{ $product->title }}</a></h3>
        @if($product->excerpt)<p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-500">{{ $product->excerpt }}</p>@endif
        <div class="mt-auto flex items-center justify-between gap-3 pt-5">
            <span><span class="font-display text-xl font-bold text-ink">{{ $product->formattedPrice() ?? ($product->price_note ?: 'Fiyat sorunuz') }}</span>@if($product->formattedPrice() && $product->price_note)<span class="ml-1 text-xs text-slate-400">{{ $product->price_note }}</span>@endif</span>
            @if($whatsapp)
                <a href="{{ $whatsapp }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-3 py-2 text-xs font-semibold text-white transition hover:bg-primary-dark" aria-label="{{ $product->title }} için WhatsApp'tan sipariş ver"><x-icon name="whatsapp" class="h-4 w-4" /> Sipariş</a>
            @else
                <a href="{{ route('quote.show', ['urun' => $product->slug]) }}" class="btn-brand !px-4 !py-2 text-xs">Sipariş Ver</a>
            @endif
        </div>
    </div>
</article>
