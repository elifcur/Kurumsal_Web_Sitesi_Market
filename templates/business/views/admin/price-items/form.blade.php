@extends('layouts.admin')
@section('title', $item->exists ? 'Fiyat Kalemi Düzenle' : 'Yeni Fiyat Kalemi')
@section('content')
    <x-admin.page-header :title="$item->exists ? 'Fiyat Kalemi Düzenle' : 'Yeni Fiyat Kalemi'" :subtitle="$item->exists ? $item->name : null" />
    <form method="post" action="{{ $item->exists ? route('admin.price-items.update', $item) : route('admin.price-items.store') }}">
        @csrf
        @if($item->exists)@method('PUT')@endif
        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <div class="card"><div class="card-body space-y-4">
                    <x-admin.input name="name" label="Kalem Adı" :value="$item->name" required placeholder="Saç Kesimi" />
                    <x-admin.input name="description" label="Kısa Açıklama" :value="$item->description" placeholder="Yıkama ve fön dahil" help="Fiyat listesinde adın altında küçük yazıyla gösterilir." />
                    <div class="grid gap-4 sm:grid-cols-3">
                        <x-admin.input name="price" label="Fiyat (₺)" type="number" step="0.01" min="0" :value="$item->price" help="Boş bırakılırsa 'Fiyat sorunuz' yazar." />
                        <x-admin.input name="price_note" label="Fiyat Notu" :value="$item->price_note" placeholder="başlangıç, /saat, KDV dahil" />
                        <x-admin.input name="group" label="Grup" :value="$item->group" placeholder="Saç, Sakal, Bakım" list="price-groups" />
                    </div>
                    <datalist id="price-groups">@foreach($groups as $group)<option value="{{ $group }}">@endforeach</datalist>
                </div></div>
            </div>
            <div class="space-y-6">
                <div class="card"><div class="card-body space-y-4">
                    <x-admin.checkbox name="is_active" label="Aktif (sitede görünür)" :checked="$item->is_active" />
                    <x-admin.input name="sort_order" label="Sıra" type="number" min="0" :value="$item->sort_order" help="Listede sürükle-bırak ile de değiştirebilirsiniz." />
                </div></div>
                <x-admin.form-actions :back="route('admin.price-items.index')" />
            </div>
        </div>
    </form>
@endsection
