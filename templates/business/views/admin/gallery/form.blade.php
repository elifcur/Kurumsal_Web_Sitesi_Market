@extends('layouts.admin')
@section('title', $item->exists ? 'Galeri Öğesi Düzenle' : 'Yeni Galeri Öğesi')
@section('content')
    <x-admin.page-header :title="$item->exists ? 'Galeri Öğesi Düzenle' : 'Yeni Galeri Öğesi'" :subtitle="$item->exists ? ($item->title ?: 'Başlıksız') : null" />
    <form method="post" action="{{ $item->exists ? route('admin.gallery.update', $item) : route('admin.gallery.store') }}">
        @csrf
        @if($item->exists)@method('PUT')@endif
        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <div class="card"><div class="card-body space-y-4">
                    <x-admin.image name="image" label="Görsel *" :value="$item->image" help="Kare veya 4:3 oranlı, en az 1200px önerilir." />
                    <x-admin.input name="title" label="Başlık" :value="$item->title" placeholder="Klasik Skin Fade" help="İsteğe bağlı; görselin üzerinde gösterilir." />
                    <x-admin.input name="category" label="Kategori" :value="$item->category" placeholder="Saç, Sakal, Mekan" list="gallery-categories" help="Sitede sekme olarak görünür; boş bırakılabilir." />
                    <datalist id="gallery-categories">@foreach($categories as $category)<option value="{{ $category }}">@endforeach</datalist>
                </div></div>
            </div>
            <div class="space-y-6">
                <div class="card"><div class="card-body space-y-4">
                    <x-admin.checkbox name="is_active" label="Aktif (sitede görünür)" :checked="$item->is_active" />
                    <x-admin.input name="sort_order" label="Sıra" type="number" min="0" :value="$item->sort_order" help="Küçük sayı önce gösterilir." />
                </div></div>
                <x-admin.form-actions :back="route('admin.gallery.index')" />
            </div>
        </div>
    </form>
@endsection
