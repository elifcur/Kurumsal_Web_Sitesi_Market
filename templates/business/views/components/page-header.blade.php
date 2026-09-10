@props(['title', 'subtitle' => null, 'image' => null, 'breadcrumbs' => []])
@push('head')<script type="application/ld+json">{!! breadcrumb_schema($breadcrumbs) !!}</script>@endpush
@php($background = upload_url($image ?: setting('hero_image')))
{{-- İç sayfa başlığı: hero fotoğrafı üstüne kahve-siyah overlay, beyaz serif başlık + breadcrumb --}}
<section class="hero-bg relative overflow-hidden text-white">
    @if($background)<img src="{{ $background }}" alt="" class="absolute inset-0 h-full w-full object-cover object-[right_center]">@endif
    <div class="hero-overlay absolute inset-0"></div>
    <div class="container-x relative py-14 md:py-16">
        <nav class="mb-4 flex flex-wrap items-center gap-2 text-xs text-white/70" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-white">Ana Sayfa</a>
            @foreach($breadcrumbs as $crumb)
                <x-icon name="chevron-right" class="h-3 w-3 text-white/50" />
                @if(isset($crumb['url']))<a href="{{ $crumb['url'] }}" class="hover:text-white">{{ $crumb['label'] }}</a>@else<span class="text-white">{{ $crumb['label'] }}</span>@endif
            @endforeach
        </nav>
        <h1 class="max-w-3xl font-display text-4xl leading-[1.05] md:text-5xl">{!! emphasize($title) !!}</h1>
        @if($subtitle)<p class="mt-4 max-w-2xl text-base leading-7 text-white/80 md:text-lg">{{ $subtitle }}</p>@endif
        @if(setting('phone') || setting('working_hours'))
            <div class="mt-6 flex flex-wrap gap-x-6 gap-y-2 text-sm text-white/80">
                @if(setting('phone'))<a href="{{ phone_href(setting('phone')) }}" class="flex items-center gap-1.5 font-semibold text-white hover:text-accent"><x-icon name="phone" class="h-4 w-4 text-accent" /> {{ setting('phone') }}</a>@endif
                @if(setting('working_hours'))<span class="flex items-center gap-1.5"><x-icon name="clock" class="h-4 w-4 text-accent" /> {{ setting('working_hours') }}</span>@endif
            </div>
        @endif
    </div>
</section>
