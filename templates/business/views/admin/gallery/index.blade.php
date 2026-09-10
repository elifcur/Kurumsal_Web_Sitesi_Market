@extends('layouts.admin')
@section('title', 'Galeri')
@section('content')
    <x-admin.page-header title="Galeri" subtitle="Çalışmalarınızdan ve mekandan görseller; kategoriler sitede sekme olarak görünür.">
        <x-slot:actions>
            <a href="{{ route('gallery.index') }}" target="_blank" class="btn-secondary"><x-icon name="external" class="h-4 w-4" /> Sitede Gör</a>
            <a href="{{ route('admin.gallery.create') }}" class="btn-primary"><x-icon name="plus" class="h-4 w-4" /> Yeni Görsel</a>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="card">
        <div class="card-header">
            <x-admin.search placeholder="Başlık ara...">
                <select name="category" class="form-input !w-40"><option value="">Tüm kategoriler</option>@foreach($categories as $category)<option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>@endforeach</select>
                <select name="status" class="form-input !w-36"><option value="">Tüm durumlar</option><option value="active" @selected(request('status') === 'active')>Aktif</option><option value="inactive" @selected(request('status') === 'inactive')>Pasif</option></select>
            </x-admin.search>
        </div>
        @if($items->isEmpty())
            <x-admin.empty title="Galeri öğesi bulunamadı" icon="photo" />
        @else
            <div class="grid grid-cols-2 gap-4 p-5 sm:grid-cols-3 lg:grid-cols-5">
                @foreach($items as $item)
                    <div class="group relative overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                        <a href="{{ route('admin.gallery.edit', $item) }}" class="block aspect-square">
                            @if($item->image)<img src="{{ $item->imageUrl() }}" alt="{{ $item->title }}" class="h-full w-full object-cover">@else<div class="flex h-full items-center justify-center text-slate-300"><x-icon name="photo" class="h-10 w-10" /></div>@endif
                        </a>
                        <div class="flex items-start justify-between gap-2 p-3">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-medium text-slate-900">{{ $item->title ?: 'Başlıksız' }}</p>
                                <p class="mt-0.5 flex items-center gap-2 text-xs text-slate-500">{{ $item->category ?: '—' }} <x-admin.status-badge :active="$item->is_active" /></p>
                            </div>
                            <div class="flex shrink-0 items-center">
                                <a href="{{ route('admin.gallery.edit', $item) }}" class="btn-ghost btn-sm"><x-icon name="pencil" class="h-4 w-4" /></a>
                                <x-admin.delete-button :action="route('admin.gallery.destroy', $item)" />
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="px-5 py-4">{{ $items->links() }}</div>
        @endif
    </div>
@endsection
