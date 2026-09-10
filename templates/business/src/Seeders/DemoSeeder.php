<?php

namespace Templates\Business\Seeders;

use App\Models\Category;
use App\Models\Faq;
use App\Models\Partner;
use App\Models\Post;
use App\Models\Product;
use App\Models\Service;
use App\Models\Tag;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\User;
use App\Services\Settings;
use Illuminate\Database\Seeder;
use Templates\Business\Models\GalleryItem;
use Templates\Business\Models\PriceItem;

/**
 * Demo content for a neighbourhood supermarket ("Nova Market") so the business
 * site looks complete out of the box. The same template fits any small local
 * business (café, butcher, florist, plumber...) once the content is edited.
 * Idempotent: nothing is created when price items already exist.
 */
class DemoSeeder extends Seeder
{
    private const IMAGE_PATH = '/images/templates/business';

    /** Kategori adı => ikon dosyası (mockup'tan kesilen yuvarlak ikonlar). */
    private const CATEGORIES = [
        'Meyve & Sebze' => 'cat-01.png',
        'Süt Ürünleri' => 'cat-02.png',
        'Et & Şarküteri' => 'cat-03.png',
        'Fırın Ürünleri' => 'cat-04.png',
        'Temel Gıda' => 'cat-05.png',
        'Dondurulmuş Ürünler' => 'cat-06.png',
        'Temizlik Ürünleri' => 'cat-07.png',
        'Kişisel Bakım' => 'cat-08.png',
        'Evcil Hayvan Ürünleri' => 'cat-09.png',
        'Bebek Ürünleri' => 'cat-10.png',
    ];

    public function run(): void
    {
        if (PriceItem::query()->exists()) {
            return;
        }

        $settings = app(Settings::class);
        $settings->set('logo', self::IMAGE_PATH.'/logo.png', 'general');
        $settings->set('hero_image', self::IMAGE_PATH.'/hero.jpg', 'home');
        $settings->set('about_image', self::IMAGE_PATH.'/about.jpg', 'home');
        $settings->set('phone', '0 850 000 00 00', 'contact');
        $settings->set('whatsapp', '905321234567', 'contact');
        $settings->set('address', 'Çınarlı Mah. Çarşı Cad. No:12, Kadıköy / İstanbul', 'contact');

        $this->seedServices();
        $this->seedPriceList();
        $this->seedGallery();
        $this->seedProducts();
        $this->seedTeam();
        $this->seedSocialProof();
        $this->seedNews();
    }

    private function seedServices(): void
    {
        $services = [
            ['title' => 'Aynı Gün Teslimat', 'icon' => 'truck', 'excerpt' => 'Saat 18:00\'e kadar verilen siparişler aynı gün, 150 ₺ üzeri alışverişte ücretsiz kapınızda.'],
            ['title' => 'Online Sipariş', 'icon' => 'phone', 'excerpt' => 'Telefon, WhatsApp ya da sipariş formu; listenizi bırakın, hazırlayıp yola çıkaralım.'],
            ['title' => 'Taze Garantisi', 'icon' => 'sparkles', 'excerpt' => 'Meyve sebze her sabah halden gelir; beğenmediğiniz ürünü sorusuz değiştiririz.'],
            ['title' => 'Kampanyalar ve Nova Kart', 'icon' => 'currency', 'excerpt' => 'Her ay yüzlerce üründe indirim, Nova Kart ile alışverişinize puan.'],
        ];

        foreach ($services as $index => $service) {
            Service::factory()->create($service + ['sort_order' => $index, 'is_featured' => true]);
        }
    }

    private function seedPriceList(): void
    {
        $groups = [
            'Meyve & Sebze' => [
                ['Domates (kg)', 'Yerli, salkım', 42, null],
                ['Salatalık (kg)', null, 38, null],
                ['Elma (kg)', 'Amasya', 45, 'başlangıç'],
                ['Patates (kg)', null, 24, null],
            ],
            'Süt Ürünleri' => [
                ['Süt (1 L)', 'Günlük pastörize', 38, null],
                ['Beyaz Peynir (500 g)', 'Tam yağlı', 165, null],
                ['Yoğurt (1 kg)', null, 72, null],
                ['Tereyağı (250 g)', null, 118, null],
            ],
            'Temel Gıda' => [
                ['Ekmek (250 g)', 'Günlük, fırından', 15, null],
                ['Yumurta (30\'lu)', 'Köy yumurtası', 145, null],
                ['Ayçiçek Yağı (1 L)', null, 98, null],
                ['Pirinç (1 kg)', 'Baldo', 79, 'başlangıç'],
            ],
        ];

        $position = 0;

        foreach ($groups as $group => $items) {
            foreach ($items as [$name, $description, $price, $note]) {
                PriceItem::factory()->create([
                    'name' => $name,
                    'description' => $description,
                    'price' => $price,
                    'price_note' => $note,
                    'group' => $group,
                    'sort_order' => $position++,
                ]);
            }
        }
    }

    private function seedGallery(): void
    {
        $items = [
            ['Meyve Sebze Reyonu', 'Reyon', self::IMAGE_PATH.'/shelf-1.jpg'],
            ['Soğuk Zincir Dolapları', 'Reyon', self::IMAGE_PATH.'/shelf-2.jpg'],
            ['Kasa Önü', 'Mağaza', self::IMAGE_PATH.'/shelf-3.jpg'],
            ['Mağazamız', 'Mağaza', self::IMAGE_PATH.'/about.jpg'],
            ['Taze Sebzeler', 'Reyon', self::IMAGE_PATH.'/banner-1.jpg'],
            ['Süt Ürünleri', 'Reyon', self::IMAGE_PATH.'/banner-2.jpg'],
            ['Aylık Kampanyalar', 'Kampanya', self::IMAGE_PATH.'/banner-3.jpg'],
            ['Girişimiz', 'Mağaza', self::IMAGE_PATH.'/hero.jpg'],
        ];

        foreach ($items as $index => [$title, $category, $image]) {
            GalleryItem::factory()->create(['title' => $title, 'category' => $category, 'image' => $image, 'sort_order' => $index]);
        }
    }

    private function seedProducts(): void
    {
        $catalog = [
            'Meyve & Sebze' => [['Salkım Domates (kg)', 'Yerli üretici, her sabah halden.', 42], ['Yeşil Salata (adet)', 'Kıvırcık, taze.', 25]],
            'Süt Ürünleri' => [['Günlük Süt 1 L', 'Sabah gelen, pastörize.', 38], ['Ezine Beyaz Peynir 600 g', 'Tam yağlı, olgunlaştırılmış.', 210]],
            'Et & Şarküteri' => [['Dana Kıyma (kg)', 'Günlük çekim.', 620], ['Tavuk But (kg)', 'Bütün but.', 168]],
            'Fırın Ürünleri' => [['Taze Ekmek', 'Günde üç kez fırından.', 15], ['Susamlı Simit', 'Sabah 07:00\'de rafta.', 20]],
            'Temel Gıda' => [['Makarna 500 g', 'Burgu, İtalyan tipi.', 28], ['Ayçiçek Yağı 1 L', null, 98]],
            'Dondurulmuş Ürünler' => [['Dondurulmuş Sebze Karışık 1 kg', null, 95], ['Pizza (2\'li)', 'Karışık.', 135]],
            'Temizlik Ürünleri' => [['Yüzey Temizleyici 1 L', 'Çam kokulu.', 65], ['Bulaşık Deterjanı 750 ml', null, 48]],
            'Kişisel Bakım' => [['Şampuan 400 ml', 'Her saç tipi için.', 89], ['Diş Macunu 75 ml', null, 42]],
            'Evcil Hayvan Ürünleri' => [['Kedi Maması 1,5 kg', 'Tavuklu.', 240], ['Köpek Ödül Maması', null, 85]],
            'Bebek Ürünleri' => [['Bebek Bezi (4 numara)', '32\'li paket.', 320], ['Bebek Islak Mendili', '3\'lü paket.', 96]],
        ];

        $index = 0;

        foreach ($catalog as $categoryName => $products) {
            $image = self::IMAGE_PATH.'/'.self::CATEGORIES[$categoryName];
            $category = Category::factory()->create([
                'name' => $categoryName,
                'image' => $image,
                'sort_order' => $index,
                'description' => $categoryName.' reyonu',
            ]);

            foreach ($products as $productIndex => [$title, $excerpt, $price]) {
                Product::factory()->create([
                    'category_id' => $category->id,
                    'title' => $title,
                    'sku' => null,
                    'image' => $image,
                    'excerpt' => $excerpt,
                    'price' => $price,
                    'price_note' => 'KDV dahil',
                    'specs' => [['label' => 'Stok', 'value' => 'Rafta'], ['label' => 'Teslimat', 'value' => 'Aynı gün']],
                    'is_featured' => $productIndex === 0 && $index < 8,
                    'sort_order' => $productIndex,
                ]);
            }

            $index++;
        }
    }

    private function seedTeam(): void
    {
        $team = [
            ['name' => 'Hasan Yılmaz', 'title' => 'Market Müdürü', 'attributes' => [['label' => 'Görev', 'value' => 'Mağaza yönetimi'], ['label' => 'Deneyim', 'value' => '22 yıl']]],
            ['name' => 'Ayşe Demir', 'title' => 'Reyon Sorumlusu', 'attributes' => [['label' => 'Görev', 'value' => 'Meyve sebze, kahvaltılık'], ['label' => 'Deneyim', 'value' => '12 yıl']]],
            ['name' => 'Kerem Aksoy', 'title' => 'Teslimat Sorumlusu', 'attributes' => [['label' => 'Görev', 'value' => 'Aynı gün teslimat'], ['label' => 'Deneyim', 'value' => '4 yıl']]],
        ];

        foreach ($team as $index => $member) {
            TeamMember::factory()->create($member + ['sort_order' => $index, 'is_featured' => true, 'socials' => []]);
        }
    }

    private function seedSocialProof(): void
    {
        foreach ([
            ['Fatma T.', 'Müşterimiz', 'Sipariş verdiğim gün akşam kapımdaydı. Meyve sebze gerçekten taze, market rafından seçer gibi.'],
            ['Murat K.', 'Müşterimiz', 'Fiyatlar zincir marketlerle aynı, üstelik eksik ürünü arayıp soruyorlar. Bu ilgi az bulunur.'],
            ['Selin A.', 'Müşterimiz', 'WhatsApp\'tan listeyi atıyorum, yarım saatte hazırlanıyor. Kalabalıkta sıra beklemeye son.'],
            ['Ahmet Y.', 'Müşterimiz', 'Kampanyaları kaçırmıyorum; aylık indirimler ciddi fark yaratıyor.'],
        ] as $i => [$name, $title, $content]) {
            Testimonial::factory()->create(['name' => $name, 'title' => $title, 'content' => $content, 'rating' => 5, 'sort_order' => $i]);
        }

        foreach ([
            ['Teslimat ücreti var mı?', '150 ₺ ve üzeri siparişlerde teslimat ücretsizdir; altındaki siparişlerde 20 ₺ teslimat ücreti alınır.'],
            ['Kaça kadar açıksınız?', 'Her gün 07:00 - 22:00 arası açığız; teslimat 09:00 - 21:00 arasında yapılır.'],
            ['Kredi kartı ve yemek kartı geçiyor mu?', 'Evet, tüm kredi kartları ve yaygın yemek kartları geçerlidir. Kapıda ödeme de yapabilirsiniz.'],
            ['Nasıl sipariş verebilirim?', 'Telefonla arayabilir, WhatsApp\'tan yazabilir ya da sitedeki sipariş formunu doldurabilirsiniz.'],
            ['Ürün beğenmezsem ne olur?', 'Teslimat sırasında beğenmediğiniz ürünü alıp iade ediyoruz; ücreti siparişinizden düşülür.'],
        ] as $i => [$question, $answer]) {
            Faq::factory()->create(['question' => $question, 'answer' => $answer, 'sort_order' => $i]);
        }

        $brands = [
            ['Sütaş', 'sutas', 'https://www.sutas.com.tr'],
            ['Ülker', 'ulker', 'https://www.ulker.com.tr'],
            ['Eti', 'eti', 'https://www.etietieti.com'],
            ['Pınar', 'pinar', 'https://www.pinar.com.tr'],
            ['Torku', 'torku', 'https://www.torku.com.tr'],
            ['Coca-Cola', 'coca-cola', 'https://www.coca-cola.com.tr'],
        ];

        foreach ($brands as $i => [$name, $file, $url]) {
            Partner::factory()->create([
                'name' => $name,
                'logo' => self::IMAGE_PATH.'/brands/'.$file.'.png',
                'url' => $url,
                'sort_order' => $i,
            ]);
        }
    }

    private function seedNews(): void
    {
        $category = Category::factory()->post()->create(['name' => 'Haberler']);
        $tags = collect(['kampanya', 'taze', 'teslimat'])->map(fn ($n) => Tag::factory()->create(['name' => $n]));
        $author = User::query()->first();

        foreach (['Ekim Ayı Kampanyaları Başladı', 'Meyve Sebze Reyonumuz Yenilendi', 'Aynı Gün Teslimat Saatleri Uzadı'] as $title) {
            $post = Post::factory()->create(['title' => $title, 'user_id' => $author?->id, 'category_id' => $category->id]);
            $post->tags()->sync($tags->random(2)->pluck('id'));
        }
    }
}
