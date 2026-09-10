@extends('template::layouts.app')
@section('seo')<x-seo title="Randevu / Teklif Al" :description="setting('quote_intro')" robots="index, follow" />@endsection
@section('content')
    @php($whatsapp = \Templates\Business\Support\WhatsApp::link('Merhaba, randevu almak istiyorum.'))
    <x-page-header title="Randevu / *Teklif* Al" :subtitle="setting('quote_intro')" :breadcrumbs="[['label' => 'Randevu / Teklif']]" />
    <section class="section">
        <div class="container-x grid gap-12 lg:grid-cols-5">
            <div class="space-y-6 lg:col-span-2">
                <ol class="space-y-5">
                    @foreach([['Bilgilerinizi bırakın', 'Adınız ve telefonunuz yeterli. İsterseniz hizmet ve tercih ettiğiniz günü de seçin.'], ['Sizi arayalım', 'Çalışma saatleri içinde en kısa sürede arayıp saat veriyoruz.'], ['Gelin, rahatlayın', 'Randevu saatinde beklemeden hizmet alın; ödemeyi çıkışta yapın.']] as $step)
                        <li class="flex gap-4">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-ink font-display text-lg text-white">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <div><h3 class="text-lg font-semibold tracking-tight text-ink">{{ $step[0] }}</h3><p class="mt-1 text-sm leading-6 text-slate-600">{{ $step[1] }}</p></div>
                        </li>
                    @endforeach
                </ol>
                <div class="rounded-2xl border border-slate-200 bg-cream p-6 text-sm">
                    <p class="font-bold text-ink">Beklemek istemiyor musunuz?</p>
                    @if(setting('phone'))<a href="{{ phone_href(setting('phone')) }}" class="mt-3 flex items-center gap-2 text-slate-700 hover:text-primary"><x-icon name="phone" class="h-4 w-4 text-primary" /> {{ setting('phone') }}</a>@endif
                    @if($whatsapp)<a href="{{ $whatsapp }}" target="_blank" rel="noopener" class="mt-2 flex items-center gap-2 text-slate-700 hover:text-primary"><x-icon name="whatsapp" class="h-4 w-4 text-whatsapp" /> WhatsApp'tan yazın</a>@endif
                    @if(setting('working_hours'))<span class="mt-2 flex items-center gap-2 text-slate-700"><x-icon name="clock" class="h-4 w-4 text-primary" /> {{ setting('working_hours') }}</span>@endif
                    @if(setting('opening_note'))<span class="mt-2 flex items-center gap-2 font-medium text-primary-dark"><x-icon name="check-circle" class="h-4 w-4" /> {{ setting('opening_note') }}</span>@endif
                </div>
            </div>
            <div class="lg:col-span-3">
                <div class="rounded-2xl border border-slate-200 p-6 shadow-sm md:p-8">
                    <h2 class="font-display text-2xl text-ink">Sipariş / Teslimat Formu</h2>
                    <p class="mt-1 mb-6 text-sm text-slate-500">Yıldızlı alanlar zorunludur; e-posta isteğe bağlıdır.</p>
                    @if(session('success'))<x-alert type="success" class="mb-6">{{ session('success') }}</x-alert>@endif
                    <x-quote-form :options="$options" :selected="$selected" :prefill="$prefill" />
                </div>
            </div>
        </div>
    </section>
@endsection
