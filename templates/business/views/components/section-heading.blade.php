@props(['eyebrow' => null, 'title', 'subtitle' => null, 'align' => 'left', 'actionUrl' => null, 'actionLabel' => 'Tümünü Gör'])
{{-- Küçük büyük harfli üst yazı + serif başlık + kısa amber çizgi; sağda altı çizili "Tümü →" bağlantısı --}}
<div {{ $attributes->merge(['class' => 'mb-10 flex flex-col gap-6 '.($align === 'center' ? 'items-center text-center' : 'md:flex-row md:items-end md:justify-between')]) }}>
    <div>
        @if($eyebrow)<span class="eyebrow">{{ $eyebrow }}</span>@endif
        <h2 class="section-title">{!! emphasize($title) !!}</h2>
        <span class="accent-line {{ $align === 'center' ? 'mx-auto' : '' }}" aria-hidden="true"></span>
        @if($subtitle)<p class="section-subtitle {{ $align === 'center' ? 'mx-auto' : '' }}">{{ $subtitle }}</p>@endif
    </div>
    @if($actionUrl)<a href="{{ $actionUrl }}" class="link-more shrink-0 self-start md:self-auto md:mb-2">{{ $actionLabel }} <x-icon name="arrow-right" class="h-4 w-4" /></a>@endif
</div>
