@extends('template::layouts.app')

@section('content')
    @php($whatsapp = \Templates\Business\Support\WhatsApp::link('Merhaba, sipariş vermek istiyorum.'))

    {{-- Hero: tam genişlik fotoğraf, soldan koyu gradient, sağda hizmet şeridi --}}
    <section class="relative flex min-h-[440px] items-center overflow-hidden bg-ink text-white md:min-h-[520px]">
        @if(setting('hero_image'))
            <img src="{{ upload_url(setting('hero_image')) }}" alt="" class="absolute inset-0 h-full w-full object-cover object-[70%_center]" fetchpriority="high">
        @else
            <div class="hero-bg absolute inset-0"></div>
        @endif
        <div class="hero-overlay absolute inset-0"></div>

        <div class="container-x relative py-16 md:py-20">
            <div class="max-w-xl">
                @if(setting('hero_eyebrow'))<p class="mb-3 text-[11px] font-semibold tracking-[0.22em] text-white/80 uppercase">{{ setting('hero_eyebrow') }}</p>@endif
                <h1 class="text-4xl leading-[1.1] text-white md:text-5xl lg:text-6xl">{!! emphasize(setting('hero_title', 'İhtiyacınız Olan *Her Şey Tek Adreste*')) !!}</h1>
                @if(setting('hero_subtitle'))<p class="mt-5 max-w-lg text-base leading-7 text-white/85 md:text-lg">{{ setting('hero_subtitle') }}</p>@endif
                <div class="mt-8 flex flex-wrap gap-3">
                    @if(setting('hero_button_text'))<a href="{{ setting('hero_button_url', route('products.index')) }}" class="btn-primary !px-6 !py-3.5"><x-icon name="box" class="h-4 w-4" /> {{ setting('hero_button_text') }} <x-icon name="arrow-right" class="h-4 w-4" /></a>@endif
                    @if(setting('hero_button2_text'))<a href="{{ setting('hero_button2_url', route('contact.show')) }}" class="btn-outline-light !px-6 !py-3.5"><x-icon name="map-pin" class="h-4 w-4" /> {{ setting('hero_button2_text') }}</a>@endif
                </div>
            </div>
        </div>

        @if($heroFeatures !== [])
            <div class="absolute top-1/2 right-6 hidden -translate-y-1/2 flex-col divide-y divide-white/15 rounded-xl bg-ink/70 px-5 py-2 text-center backdrop-blur xl:flex">
                @foreach($heroFeatures as $feature)
                    <div class="w-28 py-5">
                        <x-icon :name="$feature['icon']" class="mx-auto h-7 w-7 text-white" />
                        <p class="mt-2 text-xs leading-4 font-semibold text-white">{{ $feature['title'] }}@if($feature['text'])<span class="block">{{ $feature['text'] }}</span>@endif</p>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    {{-- Kategoriler: hero'ya binen beyaz kart --}}
    @if($categories->isNotEmpty())
        <div class="container-x relative z-10 -mt-8 md:-mt-10">
            <div class="grid grid-cols-3 gap-1 rounded-2xl bg-white p-4 shadow-lg sm:grid-cols-5 lg:grid-cols-10">
                @foreach($categories as $category)
                    <a href="{{ route('products.index', ['kategori' => $category->slug]) }}" class="cat-tile group">
                        @if($category->image)
                            <img src="{{ upload_url($category->image) }}" alt="{{ $category->name }}" loading="lazy">
                        @else
                            <span class="flex h-14 w-14 items-center justify-center rounded-full bg-primary-light text-primary md:h-16 md:w-16"><x-icon name="box" class="h-7 w-7" /></span>
                        @endif
                        <span>{{ $category->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Kampanya bantları --}}
    @if($promos !== [])
        <section class="pt-10 md:pt-14">
            <div class="container-x grid gap-5 md:grid-cols-3">
                @foreach($promos as $index => $promo)
                    <div class="promo" style="background: {{ $promo['bg'] }}">
                        <div class="promo-body">
                            <p class="text-sm font-medium {{ $promo['muted'] }}">{{ $promo['line1'] }}</p>
                            <p class="font-display text-xl leading-tight {{ $promo['strong'] }}">{{ $promo['line2'] }}</p>
                            @if($promo['button'])
                                <a href="{{ $promo['url'] }}" class="mt-3 inline-flex w-fit items-center gap-2 rounded-lg bg-white px-4 py-2 text-[13px] font-semibold text-ink shadow-sm transition hover:bg-cream">{{ $promo['button'] }} <x-icon name="arrow-right" class="h-4 w-4" /></a>
                            @endif
                        </div>
                        <img src="{{ asset('images/templates/business/banner-'.($index + 1).'.jpg') }}" alt="" class="promo-photo" loading="lazy">
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Çok satan ürünler --}}
    @if($featuredProducts->isNotEmpty())
        <section class="section">
            <div class="container-x">
                <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <h2 class="section-title">{{ setting('products_home_title', 'Çok Satan Ürünler') }}</h2>
                        <span class="accent-line" aria-hidden="true"></span>
                    </div>
                    <a href="{{ route('products.index') }}" class="link-more shrink-0">Tüm Ürünleri Gör <x-icon name="arrow-right" class="h-4 w-4" /></a>
                </div>
                <div class="mb-8 flex snap-x gap-2 overflow-x-auto pb-1">
                    <a href="{{ route('products.index') }}" class="pill pill-active">Tümü</a>
                    @foreach($categories->take(7) as $category)
                        <a href="{{ route('products.index', ['kategori' => $category->slug]) }}" class="pill">{{ $category->name }}</a>
                    @endforeach
                </div>
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($featuredProducts as $product)<x-product-card :product="$product" />@endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Hizmetler --}}
    @if($services->isNotEmpty())
        <section class="section bg-cream">
            <div class="container-x">
                <x-section-heading eyebrow="Neler Yapıyoruz" :title="setting('services_label', 'Hizmetlerimiz')" :subtitle="setting('services_intro')" :actionUrl="route('services.index')" actionLabel="Tüm Hizmetler" />
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($services as $service)<x-service-card :service="$service" />@endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Günün fiyatları --}}
    @if($priceItems->isNotEmpty())
        <section class="section">
            <div class="container-x">
                <x-section-heading eyebrow="Şeffaf Fiyat" :title="setting('price_list_label', 'Günün Fiyatları')" subtitle="Sürpriz yok: listede yazan fiyat, kasada ödediğiniz fiyattır." :actionUrl="route('prices.index')" actionLabel="Tüm Fiyatlar" />
                <div class="mx-auto max-w-3xl">
                    <x-price-list :groups="collect(['' => $priceItems])" compact />
                    <div class="mt-6 flex flex-wrap justify-center gap-3">
                        <a href="{{ route('quote.show') }}" class="btn-primary">Sipariş Ver <x-icon name="arrow-right" class="h-4 w-4" /></a>
                        @if($whatsapp)<a href="{{ $whatsapp }}" target="_blank" rel="noopener" class="btn-whatsapp"><x-icon name="whatsapp" class="h-4 w-4" /> WhatsApp'tan sor</a>@endif
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- Hakkımızda --}}
    @if(setting('about_text'))
        <section class="section bg-cream">
            <div class="container-x grid gap-8 lg:grid-cols-12 lg:items-stretch">
                <div class="lg:col-span-5">
                    <span class="eyebrow">Hakkımızda</span>
                    <h2 class="section-title">{!! emphasize(setting('about_title', 'Mahallenizin Marketi')) !!}</h2>
                    <span class="accent-line" aria-hidden="true"></span>
                    <div class="mt-6 space-y-4 text-base leading-7 text-slate-600">
                        @foreach(preg_split('/\r\n|\r|\n/', setting('about_text')) as $paragraph)@if(trim($paragraph) !== '')<p>{{ $paragraph }}</p>@endif @endforeach
                    </div>
                    @if($stats->isNotEmpty())
                        <dl class="mt-8 grid grid-cols-2 gap-4">
                            @foreach($stats as $stat)
                                <div class="rounded-xl border border-slate-200 bg-white p-4"><dd class="font-display text-2xl text-primary">{{ $stat['value'] }}</dd><dt class="mt-1 text-sm text-slate-500">{{ $stat['label'] }}</dt></div>
                            @endforeach
                        </dl>
                    @endif
                    <a href="{{ route('page.show', 'hakkimizda') }}" class="btn-brand mt-8">Hakkımızı İncele <x-icon name="arrow-right" class="h-4 w-4" /></a>
                </div>
                <div class="overflow-hidden rounded-2xl bg-white lg:col-span-4">
                    @if(setting('about_image'))
                        <img src="{{ upload_url(setting('about_image')) }}" alt="{{ setting('about_title', 'Mağazamız') }}" class="h-full min-h-[280px] w-full object-cover" loading="lazy">
                    @else
                        <div class="flex h-full min-h-[280px] items-center justify-center text-primary/30"><x-icon name="box" class="h-24 w-24" /></div>
                    @endif
                </div>
                <div class="flex flex-col items-center justify-center rounded-2xl bg-white p-8 text-center lg:col-span-3">
                    @if(setting('about_quote'))<p class="font-display text-lg leading-relaxed text-ink">“{{ setting('about_quote') }}”</p>@endif
                    <x-icon name="heart" class="mt-5 h-6 w-6 text-accent" />
                    @if(setting('about_quote_author'))<p class="mt-2 text-xs font-medium tracking-[0.15em] text-slate-400 uppercase">{{ setting('about_quote_author') }}</p>@endif
                    <div class="mt-8 grid w-full grid-cols-3 gap-3 text-xs font-medium text-slate-500">
                        <span class="flex flex-col items-center gap-2"><x-icon name="check-circle" class="h-7 w-7 text-primary" /> Güven</span>
                        <span class="flex flex-col items-center gap-2"><x-icon name="users" class="h-7 w-7 text-primary" /> Komşuluk</span>
                        <span class="flex flex-col items-center gap-2"><x-icon name="heart" class="h-7 w-7 text-primary" /> Memnuniyet</span>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- Marka logoları --}}
    @if($partners->isNotEmpty())
        <section class="border-y border-slate-200 bg-white py-12">
            <div class="container-x">
                <p class="mx-auto mb-8 max-w-xl text-center font-display text-xl text-ink">Güvendiğiniz markalar raflarımızda.</p>
                <x-partner-strip :partners="$partners" />
            </div>
        </section>
    @endif

    {{-- Hızlı sipariş --}}
    <section class="section" id="siparis">
        <div class="container-x">
            <div class="mx-auto max-w-4xl rounded-2xl border border-slate-200 bg-white p-6 shadow-lg md:p-8">
                <div class="mb-5 flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
                    <div>
                        <span class="eyebrow">Sipariş / Teslimat</span>
                        <h2 class="section-title !text-2xl">{!! emphasize('Listenizi bırakın, *aynı gün* kapınızda') !!}</h2>
                    </div>
                    @if(session('success'))<span class="text-sm font-medium text-emerald-700">{{ session('success') }}</span>@elseif($errors->any())<a href="{{ route('quote.show') }}" class="text-sm font-medium text-red-600">Formda hata var, tam forma gidin</a>@else<a href="{{ route('quote.show') }}" class="link-more">Tam form <x-icon name="arrow-right" class="h-4 w-4" /></a>@endif
                </div>
                <x-quote-form :options="\Templates\Business\Support\QuoteOptions::options()" compact />
                @if($whatsapp)<p class="mt-4 text-sm text-slate-500">Ya da <a href="{{ $whatsapp }}" target="_blank" rel="noopener" class="font-semibold text-emerald-700 underline underline-offset-4">WhatsApp'tan yazın</a>, listenizi hemen hazırlayalım.</p>@endif
            </div>
        </div>
    </section>

    {{-- Galeri --}}
    @if($galleryItems->isNotEmpty())
        <section class="section bg-cream">
            <div class="container-x">
                <x-section-heading eyebrow="Mağazamızdan" :title="setting('gallery_label', 'Galeri')" subtitle="Reyonlarımız, taze ürünlerimiz ve mağazamızdan kareler." :actionUrl="route('gallery.index')" actionLabel="Tüm Kareler" />
                <x-gallery-grid :items="$galleryItems" :lightbox="false" />
            </div>
        </section>
    @endif

    {{-- Yorumlar + SSS --}}
    @if($testimonials->isNotEmpty() || $faqs->isNotEmpty())
        <section class="section">
            <div class="container-x grid gap-14 lg:grid-cols-2">
                @if($testimonials->isNotEmpty())
                    <div>
                        <span class="eyebrow">Müşterilerimiz</span>
                        <h2 class="section-title !text-3xl">{!! emphasize('Ne *diyorlar*?') !!}</h2>
                        <div class="mt-8 grid gap-5">@foreach($testimonials as $testimonial)<x-testimonial-card :testimonial="$testimonial" />@endforeach</div>
                    </div>
                @endif
                @if($faqs->isNotEmpty())
                    <div>
                        <span class="eyebrow">Merak Edilenler</span>
                        <h2 class="section-title !text-3xl">Sık Sorulan Sorular</h2>
                        <div class="mt-8"><x-faq-list :faqs="$faqs" /></div>
                    </div>
                @endif
            </div>
        </section>
    @endif

    {{-- Bize ulaşın --}}
    <section class="section bg-cream" id="bize-ulasin">
        <div class="container-x">
            <x-section-heading eyebrow="Mağazamız" title="Bize *ulaşın*" subtitle="Yol üstündeyiz; uğrayın ya da bir telefonla siparişinizi verin." :actionUrl="route('contact.show')" actionLabel="İletişim Sayfası" />
            <div class="grid gap-5 lg:grid-cols-3">
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white lg:col-span-2">
                    @if(setting('map_embed'))
                        <iframe src="{{ setting('map_embed') }}" class="h-80 w-full border-0 lg:h-full lg:min-h-[22rem]" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen title="Harita"></iframe>
                    @else
                        <div class="flex h-full min-h-[16rem] flex-col items-center justify-center gap-3 p-8 text-center">
                            <span class="flex h-14 w-14 items-center justify-center rounded-full bg-primary-light text-primary"><x-icon name="map-pin" class="h-7 w-7" /></span>
                            @if(setting('address'))<p class="max-w-md text-lg text-ink">{{ setting('address') }}</p><a href="https://www.google.com/maps/search/?api=1&query={{ rawurlencode(setting('address')) }}" target="_blank" rel="noopener" class="btn-outline">Yol Tarifi Al</a>@else<p class="text-slate-500">Adres bilgisi Site Ayarları'ndan eklenebilir.</p>@endif
                        </div>
                    @endif
                </div>
                <div class="flex flex-col gap-4">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6">
                        <p class="flex items-center gap-2 text-sm font-semibold text-ink"><x-icon name="clock" class="h-5 w-5 text-primary" /> Çalışma Saatleri</p>
                        <p class="mt-3 text-lg text-slate-700">{{ setting('working_hours', 'Her gün 07:00 - 22:00') }}</p>
                        @if(setting('opening_note'))<p class="mt-1 text-sm font-medium text-primary">{{ setting('opening_note') }}</p>@endif
                        @if(setting('address') && setting('map_embed'))<p class="mt-4 flex gap-2 text-sm text-slate-500"><x-icon name="map-pin" class="mt-0.5 h-4 w-4 shrink-0 text-primary" /> {{ setting('address') }}</p>@endif
                    </div>
                    <div class="rounded-2xl bg-ink p-6 text-white">
                        <p class="font-display text-xl">Hemen arayın</p>
                        @if(setting('phone'))<a href="{{ phone_href(setting('phone')) }}" class="btn-light mt-4 w-full"><x-icon name="phone" class="h-4 w-4" /> {{ setting('phone') }}</a>@endif
                        @if($whatsapp)<a href="{{ $whatsapp }}" target="_blank" rel="noopener" class="btn-whatsapp mt-3 w-full"><x-icon name="whatsapp" class="h-4 w-4" /> WhatsApp'tan yazın</a>@endif
                        <a href="{{ route('quote.show') }}" class="btn-ghost-light mt-3 w-full">Sipariş Formu</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Blog --}}
    @if($posts->isNotEmpty())
        <section class="section">
            <div class="container-x">
                <x-section-heading eyebrow="Bizden Haberler" :title="setting('blog_label', 'Haberler')" :subtitle="setting('blog_intro')" :actionUrl="route('blog.index')" actionLabel="Tüm Yazılar" />
                <div class="grid gap-5 md:grid-cols-3">@foreach($posts as $post)<x-post-card :post="$post" />@endforeach</div>
            </div>
        </section>
    @endif

    {{-- CTA --}}
    @if(setting('cta_title'))
        <section class="relative overflow-hidden bg-primary-dark text-white">
            <div class="absolute -top-20 -left-20 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
            <div class="container-x relative flex flex-col items-center justify-between gap-6 py-14 text-center md:flex-row md:text-left">
                <div><h2 class="font-display text-2xl text-white md:text-3xl">{!! emphasize(setting('cta_title')) !!}</h2>@if(setting('cta_text'))<p class="mt-3 max-w-xl text-base text-white/80">{{ setting('cta_text') }}</p>@endif</div>
                <div class="flex shrink-0 flex-wrap items-center justify-center gap-3">
                    @if(setting('cta_button_text'))<a href="{{ setting('cta_button_url', route('quote.show')) }}" class="btn-primary !px-7 !py-3.5">{{ setting('cta_button_text') }} <x-icon name="arrow-right" class="h-4 w-4" /></a>@endif
                    @if(setting('phone'))<a href="{{ phone_href(setting('phone')) }}" class="btn-ghost-light !py-3.5"><x-icon name="phone" class="h-4 w-4" /> {{ setting('phone') }}</a>@endif
                </div>
            </div>
        </section>
    @endif
@endsection
