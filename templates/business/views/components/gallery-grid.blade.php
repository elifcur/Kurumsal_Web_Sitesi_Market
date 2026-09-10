@props(['items', 'categories' => collect(), 'lightbox' => true])
@php($payload = $items->map(fn ($item) => ['src' => $item->imageUrl(), 'title' => $item->title, 'category' => $item->category])->values()->all())
<div x-data="workGallery(@js($payload))" @keydown.escape.window="close()" @keydown.arrow-right.window="open && next()" @keydown.arrow-left.window="open && prev()">
    @if($categories->isNotEmpty())
        <div class="mb-8 flex flex-wrap gap-2" role="tablist" aria-label="Galeri kategorileri">
            <button type="button" @click="setFilter('')" class="tab-pill" :class="filter === '' && 'tab-pill-active'" role="tab">Tümü</button>
            @foreach($categories as $category)
                <button type="button" @click="setFilter(@js($category))" class="tab-pill" :class="filter === @js($category) && 'tab-pill-active'" role="tab">{{ $category }}</button>
            @endforeach
        </div>
    @endif

    <div class="grid grid-cols-2 gap-4 md:grid-cols-3">
        @foreach($items as $item)
            <figure class="group relative aspect-square overflow-hidden rounded-2xl border border-slate-200 bg-cream" x-show="matches(@js($item->category))">
                @if($item->image)
                    <img src="{{ $item->imageUrl() }}" alt="{{ $item->title ?? '' }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105" loading="lazy">
                    @if($lightbox)<button type="button" @click="show({{ $loop->index }})" class="absolute inset-0" aria-label="Büyüt: {{ $item->title ?? 'Görsel' }}"></button>@endif
                @else
                    <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-primary-light to-cream text-primary/40"><x-icon name="photo" class="h-12 w-12" /></div>
                @endif
                @if($item->title)
                    <figcaption class="pointer-events-none absolute inset-x-0 bottom-0 bg-gradient-to-t from-ink/70 to-transparent p-3 text-sm font-medium text-white">{{ $item->title }}@if($item->category)<span class="ml-2 text-xs opacity-80">{{ $item->category }}</span>@endif</figcaption>
                @endif
            </figure>
        @endforeach
    </div>

    @if($lightbox)
        <div x-show="open" x-cloak x-transition.opacity class="fixed inset-0 z-[60] flex items-center justify-center bg-ink/90 p-4" @click.self="close()" role="dialog" aria-modal="true">
            <button type="button" @click="close()" class="absolute top-4 right-4 rounded-full bg-white/10 p-2 text-white hover:bg-white/20" aria-label="Kapat"><x-icon name="x" class="h-6 w-6" /></button>
            <template x-if="visibleIndices.length > 1">
                <div>
                    <button type="button" @click="prev()" class="absolute top-1/2 left-4 -translate-y-1/2 rounded-full bg-white/10 p-2 text-white hover:bg-white/20" aria-label="Önceki"><x-icon name="chevron-left" class="h-6 w-6" /></button>
                    <button type="button" @click="next()" class="absolute top-1/2 right-4 -translate-y-1/2 rounded-full bg-white/10 p-2 text-white hover:bg-white/20" aria-label="Sonraki"><x-icon name="chevron-right" class="h-6 w-6" /></button>
                </div>
            </template>
            <figure class="max-h-full max-w-5xl text-center">
                <template x-if="current && current.src"><img :src="current.src" :alt="current.title ?? ''" class="max-h-[80vh] w-auto rounded-2xl object-contain"></template>
                <figcaption class="mt-3 text-sm text-slate-300"><span x-text="current?.title ?? ''"></span> <span class="ml-2 opacity-70" x-text="position"></span></figcaption>
            </figure>
        </div>
    @endif
</div>
