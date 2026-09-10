@props(['partners'])
@if($partners->isNotEmpty())
<div class="logo-grid">
    @foreach($partners as $partner)
        <div>
            @if($partner->logo)
                @if($partner->url)<a href="{{ $partner->url }}" target="_blank" rel="noopener nofollow"><img src="{{ upload_url($partner->logo) }}" alt="{{ $partner->name }}" class="max-h-12 w-auto object-contain" loading="lazy"></a>@else<img src="{{ upload_url($partner->logo) }}" alt="{{ $partner->name }}" class="max-h-12 w-auto object-contain" loading="lazy">@endif
            @else
                <span class="font-display text-lg text-slate-500">{{ $partner->name }}</span>
            @endif
        </div>
    @endforeach
</div>
@endif
