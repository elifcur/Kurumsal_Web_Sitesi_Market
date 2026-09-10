@extends('layouts.admin')
@section('title', 'Fiyat Listesi')
@section('content')
    <x-admin.page-header title="Fiyat Listesi" subtitle="Sürükleyip bırakarak sıralayabilirsiniz; gruplar ilk kalemin sırasına göre dizilir.">
        <x-slot:actions>
            <a href="{{ route('prices.index') }}" target="_blank" class="btn-secondary"><x-icon name="external" class="h-4 w-4" /> Sitede Gör</a>
            <a href="{{ route('admin.price-items.create') }}" class="btn-primary"><x-icon name="plus" class="h-4 w-4" /> Yeni Kalem</a>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="card" x-data="sortableList(@js(route('admin.price-items.reorder')))">
        <div class="card-header">
            <x-admin.search placeholder="Kalem ara...">
                <select name="group" class="form-input !w-40"><option value="">Tüm gruplar</option>@foreach($groups as $group)<option value="{{ $group }}" @selected(request('group') === $group)>{{ $group }}</option>@endforeach</select>
                <select name="status" class="form-input !w-36"><option value="">Tüm durumlar</option><option value="active" @selected(request('status') === 'active')>Aktif</option><option value="inactive" @selected(request('status') === 'inactive')>Pasif</option></select>
            </x-admin.search>
            <span x-show="saved" x-cloak x-transition class="badge-green">Sıralama kaydedildi</span>
        </div>
        @if($items->isEmpty())
            <x-admin.empty title="Fiyat kalemi bulunamadı" icon="currency" />
        @else
            <div class="overflow-x-auto"><table class="table">
                <thead><tr><th class="w-8"></th><th>Kalem</th><th>Grup</th><th>Fiyat</th><th>Durum</th><th class="text-right">İşlem</th></tr></thead>
                <tbody x-ref="list">
                @foreach($items as $item)
                    <tr data-id="{{ $item->id }}" draggable="true" @dragstart="dragStart($event)" @dragover.prevent="dragOver($event)" @drop.prevent="drop($event)" @dragend="dragEnd()" class="cursor-move">
                        <td class="text-slate-300"><x-icon name="bars-arrow" class="h-4 w-4" /></td>
                        <td>
                            <a href="{{ route('admin.price-items.edit', $item) }}" class="font-medium text-slate-900 hover:text-primary">{{ $item->name }}</a>
                            @if($item->description)<span class="block max-w-md truncate text-xs text-slate-500">{{ $item->description }}</span>@endif
                        </td>
                        <td>@if($item->group)<span class="badge-blue">{{ $item->group }}</span>@else<span class="text-slate-400">—</span>@endif</td>
                        <td class="font-medium whitespace-nowrap text-slate-900">{{ $item->formattedPrice() ?? '—' }}@if($item->price_note)<span class="ml-1 text-xs font-normal text-slate-500">{{ $item->price_note }}</span>@endif</td>
                        <td><x-admin.status-badge :active="$item->is_active" /></td>
                        <td class="text-right whitespace-nowrap">
                            <a href="{{ route('admin.price-items.edit', $item) }}" class="btn-ghost btn-sm"><x-icon name="pencil" class="h-4 w-4" /></a>
                            <x-admin.delete-button :action="route('admin.price-items.destroy', $item)" />
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table></div>
            <div class="px-5 py-4">{{ $items->links() }}</div>
        @endif
    </div>
@endsection
