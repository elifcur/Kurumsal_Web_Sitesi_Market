@props(['service'])
<a href="{{ $service->url() }}" class="card group flex flex-col overflow-hidden">
    @if($service->image)
        <div class="relative aspect-[4/3] overflow-hidden bg-cream">
            <img src="{{ upload_url($service->image) }}" alt="{{ $service->title }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105" loading="lazy">
            <span class="absolute bottom-3 left-4 flex h-11 w-11 items-center justify-center rounded-full bg-white text-primary shadow"><x-icon :name="$service->icon ?: 'sparkles'" class="h-5 w-5" /></span>
        </div>
        <div class="flex flex-1 flex-col p-5">
    @else
        <div class="flex flex-1 flex-col p-6">
            <span class="mb-5 flex h-12 w-12 items-center justify-center rounded-full bg-primary-light text-primary transition group-hover:bg-primary group-hover:text-primary-contrast"><x-icon :name="$service->icon ?: 'sparkles'" class="h-6 w-6" /></span>
    @endif
            <h3 class="text-base font-semibold text-ink group-hover:text-primary">{{ $service->title }}</h3>
            @if($service->excerpt)<p class="mt-2 line-clamp-3 text-sm leading-6 text-slate-600">{{ $service->excerpt }}</p>@endif
            <span class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-primary">Detaylı Bilgi <x-icon name="arrow-right" class="h-4 w-4 transition group-hover:translate-x-1" /></span>
        </div>
</a>
