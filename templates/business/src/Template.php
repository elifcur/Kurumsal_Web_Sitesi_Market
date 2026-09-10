<?php

namespace Templates\Business;

use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Faq;
use App\Models\Partner;
use App\Models\Post;
use App\Models\Product;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Templates\Template as BaseTemplate;
use Templates\Business\Models\GalleryItem;
use Templates\Business\Models\PriceItem;

/**
 * Küçük işletme / esnaf şablonu: market, manav, kasap, kafe, tesisatçı
 * gibi tek şubeli işletmeler için ürün kategorileri, günün fiyatları, mağaza
 * galerisi ve hızlı sipariş / teslimat talebi formu. Telefon, WhatsApp, konum
 * ve çalışma saatleri her sayfada göz önündedir.
 */
class Template extends BaseTemplate
{
    public function key(): string
    {
        return 'business';
    }

    public function name(): string
    {
        return 'İşletme';
    }

    public function description(): string
    {
        return 'Küçük işletme ve esnaf için: ürün kategorileri, günün fiyatları, mağaza galerisi, sipariş/teslimat talebi formu, WhatsApp ile sipariş, harita ve çalışma saatleri.';
    }

    public function features(): array
    {
        return [
            'services' => true,
            'products' => true,
            'blog' => true,
            'team' => true,
            'testimonials' => true,
            'faqs' => true,
            'partners' => true,
        ];
    }

    public function defaultSettings(): array
    {
        return [
            'general' => [
                'site_name' => 'Nova Market',
                'site_tagline' => 'Her Alışverişte Daha Fazlası',
                'primary_color' => '#16a34a',
                'services_label' => 'Hizmetlerimiz',
                'products_label' => 'Ürünlerimiz',
                'blog_label' => 'Blog',
            ],
            'contact' => [
                'working_hours' => 'Her gün 07:00 - 22:00',
            ],
            'seo' => [
                'meta_title' => 'Nova Market | Taze Ürün, Güvenilir Market',
                'meta_description' => 'Meyve sebze, süt ürünleri, et ve şarküteri, fırın, temel gıda, temizlik ve kişisel bakım. Hızlı teslimat, uygun fiyat; WhatsApp ile sipariş.',
            ],
            'home' => [
                'hero_title' => 'İhtiyacınız Olan *Her Şey Tek Adreste*',
                'hero_subtitle' => 'Kaliteli ürünler, uygun fiyatlar ve güler yüzlü hizmetle daima yanınızdayız. Nova Market ile alışveriş şimdi daha kolay, daha keyifli!',
                'hero_button_text' => 'Alışverişe Başla',
                'hero_button_url' => '/urunler',
                'hero_button2_text' => 'Mağazalarımız',
                'hero_button2_url' => '/iletisim',
                'about_title' => 'Mahallenizin *Marketi*',
                'about_text' => "Yıllardır aynı mahallede, aynı güler yüzle hizmet veriyoruz. Amacımız; kaliteli ürünleri, uygun fiyatlarla ve sıcak bir komşuluk anlayışıyla sizlere sunmak.\nÇünkü biz, sadece ürün değil, güven satıyoruz.",
                'services_intro' => 'Raftan fazlası: kapınıza teslimat, taze ekmek, tüp ve su siparişi.',
                'products_intro' => 'Raflarımızdaki taze ve kaliteli ürünler; fiyatlar rafta yazdığı gibidir.',
                'blog_intro' => 'Kampanyalar, yeni ürünler ve mahalleden haberler.',
                'cta_title' => 'Siparişiniz *kapınızda*',
                'cta_text' => 'Telefonla, WhatsApp\'tan ya da formu doldurarak sipariş verin; aynı gün kapınıza getirelim.',
                'cta_button_text' => 'Sipariş Ver',
                'cta_button_url' => '/teklif',
                'stats' => '30+|Yıllık Tecrübe;2000+|Mutlu Müşteri;5.000+|Ürün Çeşidi;Aynı Gün|Teslimat',
            ],
            'template' => [
                'hero_eyebrow' => 'Taze ürün, güvenilir market',
                'hero_features' => 'truck|Hızlı|Teslimat;shield|Güvenli|Alışveriş;phone|7/24|Destek',
                'home_promos' => 'green|Her Zaman|Taze, Her Zaman Doğal|Sebzeleri İncele|/urunler;cream|Güvenilir Markalar|Uygun Fiyatlar|Süt Ürünlerini Keşfet|/urunler;yellow|Aylık|Kampanyaları Kaçırma!|Kampanyaları Gör|/blog',
                'products_home_title' => 'Çok Satan Ürünler',
                'search_placeholder' => 'Binlerce ürün arasında arama yapın...',
                'categories_title' => 'Sizin İçin Seçtiklerimiz',
                'about_quote' => 'Taze ürünler, mutlu sofralar.',
                'about_quote_author' => 'Nova Market',
                'price_list_label' => 'Günün Fiyatları',
                'gallery_label' => 'Galeri',
                'quote_intro' => 'Adınızı, telefonunuzu ve istediğiniz ürünleri yazın; sizi arayıp siparişinizi aynı gün kapınıza getirelim.',
                'quote_success' => 'Siparişiniz alındı. En kısa sürede sizi arayacağız.',
                'opening_note' => 'Eve teslimat: 09:00 - 21:00',
                'instagram_feed_note' => 'Günün taze ürünleri ve kampanyalar için Instagram hesabımızı takip edin.',
            ],
        ];
    }

    public function settingFields(): array
    {
        return [
            'hero_eyebrow' => ['label' => 'Hero üst yazısı', 'type' => 'text', 'help' => 'Başlığın üstündeki küçük büyük harfli satır.', 'rules' => ['max:80']],
            'hero_features' => ['label' => 'Hero yan şeridi', 'type' => 'textarea', 'help' => 'Hero fotoğrafının sağındaki şerit. Her madde "ikon|Üst satır|Alt satır", maddeler ";" ile ayrılır. İkonlar: truck, shield, phone, clock, heart, sparkles, check-circle.', 'rules' => ['max:600']],
            'home_promos' => ['label' => 'Kampanya bantları', 'type' => 'textarea', 'help' => 'Ana sayfadaki üç bant. Her bant "renk|üst satır|kalın satır|buton|bağlantı", bantlar ";" ile ayrılır. Renkler: green, cream, yellow. Görseller public/images/templates/business/banner-1..3.jpg dosyalarından gelir.', 'rules' => ['max:1200']],
            'products_home_title' => ['label' => 'Ürün bölümü başlığı', 'type' => 'text', 'rules' => ['max:120']],
            'search_placeholder' => ['label' => 'Arama kutusu metni', 'type' => 'text', 'rules' => ['max:120']],
            'categories_title' => ['label' => 'Kategori bölümü başlığı', 'type' => 'text', 'rules' => ['max:120']],
            'about_quote' => ['label' => 'Hakkımızda alıntısı', 'type' => 'text', 'help' => 'Hakkımızda bölümündeki krem kartta italik gösterilir.', 'rules' => ['max:255']],
            'about_quote_author' => ['label' => 'Alıntı imzası', 'type' => 'text', 'rules' => ['max:80']],
            'price_list_label' => ['label' => 'Fiyat listesi menü etiketi', 'type' => 'text', 'rules' => ['max:50']],
            'gallery_label' => ['label' => 'Galeri menü etiketi', 'type' => 'text', 'rules' => ['max:50']],
            'quote_intro' => ['label' => 'Sipariş / teslimat sayfası açıklaması', 'type' => 'textarea', 'rules' => ['max:500']],
            'quote_success' => ['label' => 'Sipariş formu başarı mesajı', 'type' => 'text', 'rules' => ['max:255']],
            'opening_note' => ['label' => 'Çalışma saati notu', 'type' => 'text', 'help' => 'Örn. "Eve teslimat: 09:00 - 21:00". Saatlerin altında gösterilir.', 'rules' => ['max:120']],
            'instagram_feed_note' => ['label' => 'Instagram notu', 'type' => 'text', 'help' => 'Galeri sayfasının altında Instagram bağlantısıyla birlikte gösterilir.', 'rules' => ['max:255']],
        ];
    }

    public function adminMenu(): array
    {
        return [
            ['label' => 'Fiyat Listesi', 'route' => 'admin.price-items.index', 'pattern' => 'admin.price-items.*', 'icon' => 'currency', 'permission' => 'manage-sector'],
            ['label' => 'Galeri', 'route' => 'admin.gallery.index', 'pattern' => 'admin.gallery.*', 'icon' => 'photo', 'permission' => 'manage-sector'],
        ];
    }

    public function publicMenu(): array
    {
        return [
            ['label' => setting('price_list_label', 'Günün Fiyatları'), 'route' => 'prices.index', 'pattern' => 'fiyat-listesi*'],
            ['label' => setting('gallery_label', 'Galeri'), 'route' => 'gallery.index', 'pattern' => 'galeri*'],
        ];
    }

    public function dashboardStats(): array
    {
        return [
            ['label' => 'Fiyat Listesi', 'value' => PriceItem::query()->count(), 'sub' => PriceItem::query()->active()->count().' yayında', 'route' => 'admin.price-items.index', 'icon' => 'currency'],
            ['label' => 'Galeri', 'value' => GalleryItem::query()->count(), 'sub' => GalleryItem::query()->active()->count().' yayında', 'route' => 'admin.gallery.index', 'icon' => 'photo'],
            ['label' => 'Bekleyen Siparişler', 'value' => ContactMessage::query()->where('type', 'quote')->unread()->count(), 'sub' => 'okunmamış talep', 'route' => 'admin.messages.index', 'icon' => 'inbox', 'permission' => 'manage-messages'],
        ];
    }

    public function sitemapEntries(): array
    {
        return [
            ['url' => route('prices.index'), 'updated' => PriceItem::query()->active()->latest('updated_at')->value('updated_at'), 'priority' => '0.9'],
            ['url' => route('gallery.index'), 'updated' => GalleryItem::query()->active()->latest('updated_at')->value('updated_at'), 'priority' => '0.7'],
            ['url' => route('quote.show'), 'updated' => null, 'priority' => '0.8'],
        ];
    }

    public function messageTypes(): array
    {
        return ['quote' => 'Sipariş / Teslimat Talebi'];
    }

    public function homeData(): array
    {
        $showProducts = setting('show_products', '1') === '1';
        $featured = Product::query()->with('category')->active()->where('is_featured', true)->ordered()->limit(8)->get();

        if ($featured->isEmpty()) {
            $featured = Product::query()->with('category')->active()->ordered()->limit(8)->get();
        }

        return [
            'services' => setting('show_services', '1') === '1' ? Service::query()->active()->ordered()->limit(4)->get() : collect(),
            'categories' => $showProducts ? Category::query()->ofType('product')->active()->ordered()->whereHas('products', fn ($query) => $query->active())->limit(10)->get() : collect(),
            'heroFeatures' => $this->parsedList('hero_features'),
            'promos' => $this->promos(),
            'priceItems' => PriceItem::query()->active()->ordered()->limit(6)->get(),
            'galleryItems' => GalleryItem::query()->active()->ordered()->limit(6)->get(),
            'featuredProducts' => $showProducts ? $featured : collect(),
            'team' => TeamMember::query()->active()->ordered()->limit(3)->get(),
            'testimonials' => Testimonial::query()->active()->ordered()->limit(3)->get(),
            'faqs' => Faq::query()->active()->ordered()->limit(5)->get(),
            'partners' => Partner::query()->active()->ordered()->get(),
            'posts' => setting('show_blog', '1') === '1' ? Post::query()->with('category')->published()->latestPublished()->limit(3)->get() : collect(),
            'stats' => site_stats(),
        ];
    }

    /**
     * Parses an "icon|Title|Text;..." style setting.
     *
     * @return array<int, array{icon: string, title: string, text: string}>
     */
    private function parsedList(string $key): array
    {
        $items = [];

        foreach (explode(';', (string) setting($key, '')) as $item) {
            [$icon, $title, $text] = array_pad(array_map('trim', explode('|', $item, 3)), 3, '');

            if ($title === '') {
                continue;
            }

            $items[] = ['icon' => $icon !== '' ? $icon : 'check-circle', 'title' => $title, 'text' => $text];
        }

        return $items;
    }

    /**
     * Parses the "colour|line1|line2|button|url;..." home_promos setting.
     *
     * @return array<int, array{bg: string, muted: string, strong: string, line1: string, line2: string, button: string, url: string}>
     */
    private function promos(): array
    {
        $styles = [
            'green' => ['bg' => 'linear-gradient(90deg,#16a34a 0%,#22c55e 60%,#22c55e 100%)', 'muted' => 'text-white/85', 'strong' => 'text-white'],
            'cream' => ['bg' => '#f7f1e6', 'muted' => 'text-slate-500', 'strong' => 'text-ink'],
            'yellow' => ['bg' => 'linear-gradient(90deg,#fbbf24 0%,#f6c453 60%,#f6c453 100%)', 'muted' => 'text-amber-900/80', 'strong' => 'text-amber-950'],
        ];

        $promos = [];

        foreach (array_slice(explode(';', (string) setting('home_promos', '')), 0, 3) as $item) {
            [$colour, $line1, $line2, $button, $url] = array_pad(array_map('trim', explode('|', $item, 5)), 5, '');

            if ($line2 === '') {
                continue;
            }

            $promos[] = ($styles[$colour] ?? $styles['cream']) + [
                'line1' => $line1,
                'line2' => $line2,
                'button' => $button,
                'url' => $url !== '' ? $url : route('products.index'),
            ];
        }

        return $promos;
    }
}
