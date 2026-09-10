@props(['category'])
{{-- Küçük fotoğraflı kategori kartı: fotoğraf, ad, ok → /urunler?kategori=slug --}}
<a href="{{ route('products.index', ['kategori' => $category->slug]) }}" class="card group flex flex-col overflow-hidden">
    <div class="aspect-[2/1] overflow-hidden bg-cream">
        @if($category->image)
            <img src="{{ upload_url($category->image) }}" alt="{{ $category->name }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105" loading="lazy">
        @else
            <div class="flex h-full w-full items-center justify-center text-primary/40"><x-icon name="box" class="h-9 w-9" /></div>
        @endif
    </div>
    <div class="flex items-center justify-between gap-2 px-4 py-3.5">
        <span class="truncate text-sm font-semibold text-ink group-hover:text-primary">{{ $category->name }}</span>
        <x-icon name="arrow-right" class="h-4 w-4 shrink-0 text-ink transition group-hover:translate-x-1 group-hover:text-primary" />
    </div>
</a>
