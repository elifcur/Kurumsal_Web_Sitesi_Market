@props(['member'])
<a href="{{ $member->url() }}" class="card group overflow-hidden text-center">
    <div class="aspect-square overflow-hidden bg-cream">
        @if($member->image)<img src="{{ upload_url($member->image) }}" alt="{{ $member->name }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105" loading="lazy">@else<div class="flex h-full items-center justify-center text-primary/30"><x-icon name="user" class="h-20 w-20" /></div>@endif
    </div>
    <div class="p-5">
        <h3 class="font-semibold text-ink group-hover:text-primary">{{ $member->name }}</h3>
        @if($member->title)<p class="mt-1 text-sm text-slate-500">{{ $member->title }}</p>@endif
        @if($member->attribute('Uzmanlık'))<p class="mt-2 text-xs text-slate-500">{{ $member->attribute('Uzmanlık') }}</p>@endif
        @if($member->attribute('Deneyim'))<span class="chip mt-3">{{ $member->attribute('Deneyim') }} deneyim</span>@endif
    </div>
</a>
