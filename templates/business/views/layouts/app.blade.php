<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @hasSection('seo')
        @yield('seo')
    @else
        <x-seo />
    @endif
    @php($primary = setting('primary_color', '#c2591b'))
    <style>
        :root {
            --color-primary: {{ $primary }};
            --color-primary-dark: color-mix(in srgb, {{ $primary }}, #000 22%);
            --color-primary-light: color-mix(in srgb, {{ $primary }}, #fff 88%);
        }
    </style>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600|poppins:600,700&display=swap" rel="stylesheet">
    @if(setting('favicon'))
        <link rel="icon" href="{{ upload_url(setting('favicon')) }}">
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}">
    @endif
    @vite($template->assets())
    @if(setting('google_analytics_id'))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ setting('google_analytics_id') }}"></script>
        <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','{{ setting('google_analytics_id') }}');</script>
    @endif
    @if(request()->routeIs('home'))
        <script type="application/ld+json">{!! organization_schema() !!}</script>
    @endif
    {!! setting('head_scripts') !!}
    @stack('head')
</head>
<body class="flex min-h-screen flex-col pb-16 lg:pb-0" x-data="siteHeader">
    @include('template::partials.header')

    <main class="flex-1">
        @yield('content')
    </main>

    @include('template::partials.footer')

    @php($whatsapp = \Templates\Business\Support\WhatsApp::link('Merhaba, sipariş vermek istiyorum.'))
    @if($whatsapp)
        <a href="{{ $whatsapp }}" target="_blank" rel="noopener"
           class="fixed right-5 bottom-5 z-40 hidden h-14 w-14 items-center justify-center rounded-full bg-whatsapp text-white shadow-lg transition hover:scale-105 lg:flex"
           aria-label="WhatsApp ile sipariş verin">
            <x-icon name="whatsapp" class="h-7 w-7" />
        </a>
    @endif

    {{-- Mobil alt sabit çubuk: Ara / WhatsApp / Konum --}}
    <nav class="bottom-bar" aria-label="Hızlı iletişim">
        @if(setting('phone'))<a href="{{ phone_href(setting('phone')) }}"><x-icon name="phone" class="h-5 w-5 text-primary" /> Ara</a>@else<a href="{{ route('quote.show') }}"><x-icon name="box" class="h-5 w-5 text-primary" /> Sipariş</a>@endif
        @if($whatsapp)<a href="{{ $whatsapp }}" target="_blank" rel="noopener"><x-icon name="whatsapp" class="h-5 w-5 text-whatsapp" /> WhatsApp</a>@else<a href="{{ route('contact.show') }}"><x-icon name="mail" class="h-5 w-5 text-primary" /> Yazın</a>@endif
        <a href="{{ \Templates\Business\Support\Location::mapUrl() }}" @if(\Templates\Business\Support\Location::isExternal()) target="_blank" rel="noopener" @endif><x-icon name="map-pin" class="h-5 w-5 text-primary" /> Konum</a>
    </nav>
    @stack('scripts')
</body>
</html>
