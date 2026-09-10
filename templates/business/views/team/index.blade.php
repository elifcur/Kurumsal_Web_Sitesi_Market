@extends('template::layouts.app')
@section('seo')<x-seo title="Ekibimiz" description="Sizi tanıyan, işini seven ustalarımızla tanışın." />@endsection
@section('content')
    <x-page-header title="Sizi *tanıyan* ekip" subtitle="Her müşterimizi adıyla tanıyan, acele etmeyen ustalar." :breadcrumbs="[['label' => 'Ekibimiz']]" />
    <section class="section">
        <div class="container-x">
            @if($members->isEmpty())
                <x-empty-state icon="users" title="Henüz ekip üyesi eklenmedi" />
            @else
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">@foreach($members as $member)<x-team-card :member="$member" />@endforeach</div>
            @endif
        </div>
    </section>
@endsection
