<?php

namespace Tests\Feature\Templates;

use App\Mail\ContactMessageReceived;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use App\Services\Settings;
use Database\Seeders\SettingsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Templates\Business\Models\GalleryItem;
use Templates\Business\Models\PriceItem;
use Templates\Business\Seeders\DemoSeeder;
use Templates\Business\Support\WhatsApp;
use Tests\TestCase;

class BusinessTemplateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if ($this->activeTemplateKey() !== 'business') {
            $this->markTestSkipped('Bu test yalnızca business şablonu aktifken çalışır (APP_TEMPLATE=business).');
        }
    }

    public function test_home_page_renders_sector_sections(): void
    {
        app(Settings::class)->set('whatsapp', '+90 555 000 00 00', 'contact');
        Service::factory()->create(['title' => 'Damat Paketi']);
        PriceItem::factory()->create(['name' => 'Saç Kesimi', 'price' => 350, 'group' => 'Saç']);
        PriceItem::factory()->inactive()->create(['name' => 'Gizli Kalem']);
        GalleryItem::factory()->create(['title' => 'Klasik Skin Fade', 'category' => 'Saç']);
        Product::factory()->create(['title' => 'Mat Wax 100 ml', 'is_featured' => true]);
        Partner::factory()->create(['name' => 'Proraso']);

        $this->get('/')->assertOk()
            ->assertSee('Damat Paketi')
            ->assertSee('Saç Kesimi')
            ->assertSee('350 ₺')
            ->assertDontSee('Gizli Kalem')
            ->assertSee('Klasik Skin Fade')
            ->assertSee('Mat Wax 100 ml')
            ->assertSee('Proraso')
            ->assertSee('Listenizi bırakın')
            ->assertSee('Bize')
            ->assertSee('https://wa.me/905550000000', false)
            ->assertSee(route('quote.store'), false);
    }

    public function test_price_list_page_groups_items_and_links_whatsapp(): void
    {
        app(Settings::class)->set('whatsapp', '905550000000', 'contact');
        PriceItem::factory()->create(['name' => 'Sakal Tıraşı', 'price' => 200, 'group' => 'Sakal', 'sort_order' => 1]);
        PriceItem::factory()->create(['name' => 'Saç Boyama', 'price' => 600.5, 'price_note' => 'başlangıç', 'group' => 'Saç', 'sort_order' => 0]);
        PriceItem::factory()->create(['name' => 'Kaş Düzeltme', 'price' => null, 'group' => null, 'sort_order' => 2]);
        PriceItem::factory()->inactive()->create(['name' => 'Pasif Kalem']);

        $this->get('/fiyat-listesi')->assertOk()
            ->assertSeeInOrder(['Saç', 'Saç Boyama', '600,50 ₺', 'başlangıç', 'Sakal', 'Sakal Tıraşı', '200 ₺', 'Kaş Düzeltme', 'Fiyat sorunuz'])
            ->assertSee('Randevu / Sipariş')
            ->assertSee('https://wa.me/905550000000?text=', false)
            ->assertDontSee('Pasif Kalem');

        $this->assertSame('https://wa.me/905550000000?text='.rawurlencode('Merhaba, "Sakal Tıraşı" için bilgi almak istiyorum.'), PriceItem::query()->where('name', 'Sakal Tıraşı')->firstOrFail()->whatsappUrl());

        app(Settings::class)->set('whatsapp', '', 'contact');
        $this->assertNull(WhatsApp::link('x'));
        $this->get('/fiyat-listesi')->assertOk()->assertDontSee('wa.me');
    }

    public function test_gallery_page_renders_categories_and_items(): void
    {
        GalleryItem::factory()->create(['title' => 'Pompadour', 'category' => 'Saç', 'image' => 'media/pompadour.jpg']);
        GalleryItem::factory()->create(['title' => 'Salonumuz', 'category' => 'Mekan', 'image' => null]);
        GalleryItem::factory()->inactive()->create(['title' => 'Gizli Kare']);
        app(Settings::class)->set('instagram', 'https://instagram.com/yildiz', 'social');

        $this->get('/galeri')->assertOk()
            ->assertSee('Pompadour')->assertSee('Salonumuz')->assertDontSee('Gizli Kare')
            ->assertSee('role="tab"', false)->assertSee('Mekan')
            ->assertSee(upload_url('media/pompadour.jpg'), false)
            ->assertSee('https://instagram.com/yildiz', false);
    }

    public function test_quote_form_preselects_service_and_stores_message(): void
    {
        Mail::fake();
        app(Settings::class)->set('email', 'usta@test.com');
        $service = Service::factory()->create(['title' => 'Cilt Bakımı']);
        $item = PriceItem::factory()->create(['name' => 'Saç Kesimi', 'group' => 'Saç']);
        $product = Product::factory()->create(['title' => 'Sakal Yağı 50 ml']);

        $this->get('/teklif?hizmet='.$service->slug)->assertOk()
            ->assertSee('Sipariş / Teslimat Formu')
            ->assertSee('value="hizmet:'.$service->slug.'" selected', false)
            ->assertSee('fiyat:'.$item->id, false);

        $this->get('/teklif?urun='.$product->slug)->assertOk()->assertSee('Sakal Yağı 50 ml');

        $this->from('/teklif')->post('/teklif', [
            'name' => 'Ali Veli', 'phone' => '0555 111 22 33', 'service' => 'fiyat:'.$item->id,
            'preferred_date' => now()->addDays(2)->toDateString(), 'message' => 'Öğleden sonra uygun.', 'kvkk' => 1,
        ])->assertRedirect('/teklif')->assertSessionHas('success');

        $message = ContactMessage::query()->where('type', 'quote')->firstOrFail();
        $this->assertSame('', $message->email);
        $this->assertSame('0555 111 22 33', $message->phone);
        $this->assertSame('Sipariş / Teslimat Talebi: Saç Kesimi', $message->subject);
        $this->assertSame('Saç Kesimi', $message->meta['Hizmet']);
        $this->assertArrayHasKey('Tercih Edilen Tarih', $message->meta);
        $this->assertSame('Sipariş / Teslimat Talebi', $message->typeLabel());
        Mail::assertSent(ContactMessageReceived::class, fn (ContactMessageReceived $mail) => $mail->contactMessage->email === 'usta@test.com' && $mail->hasTo('usta@test.com'));

        $this->from('/')->post('/teklif', ['name' => 'Ayşe', 'phone' => '0533 000 00 00', 'email' => 'ayse@example.com', 'kvkk' => 1])->assertRedirect('/teklif');
        $this->assertDatabaseHas('contact_messages', ['type' => 'quote', 'email' => 'ayse@example.com', 'subject' => 'Sipariş / Teslimat Talebi']);
    }

    public function test_quote_form_validation_and_honeypot(): void
    {
        Mail::fake();

        $this->from('/teklif')->post('/teklif', ['email' => 'not-an-email', 'service' => 'fiyat:999', 'preferred_date' => now()->subDay()->toDateString()])
            ->assertRedirect('/teklif')
            ->assertSessionHasErrors(['name', 'phone', 'email', 'service', 'preferred_date', 'kvkk']);

        $this->post('/teklif', ['name' => 'Bot', 'phone' => '1', 'kvkk' => 1, 'website' => 'http://spam'])
            ->assertSessionHasErrors('website');

        $this->assertDatabaseCount('contact_messages', 0);
        Mail::assertNothingSent();
    }

    public function test_product_catalog_and_detail_render(): void
    {
        app(Settings::class)->set('whatsapp', '905550000000', 'contact');
        $hair = Category::factory()->create(['name' => 'Saç Bakım']);
        $beard = Category::factory()->create(['name' => 'Sakal ve Cilt']);
        $wax = Product::factory()->create(['category_id' => $hair->id, 'title' => 'Mat Wax 100 ml', 'price' => 320, 'price_note' => 'KDV dahil', 'specs' => [['label' => 'Kullanım', 'value' => 'Günlük']]]);
        Product::factory()->create(['category_id' => $beard->id, 'title' => 'Sakal Yağı 50 ml']);
        Product::factory()->create(['category_id' => $beard->id, 'title' => 'Pasif Ürün', 'is_active' => false]);

        $this->get('/urunler')->assertOk()->assertSee('Mat Wax 100 ml')->assertSee('Sakal Yağı 50 ml')->assertDontSee('Pasif Ürün')->assertSee('WhatsApp');
        $this->get('/urunler?kategori='.$hair->slug)->assertOk()->assertSee('Mat Wax 100 ml')->assertDontSee('Sakal Yağı 50 ml');
        $this->get('/urunler?kategori=olmayan')->assertNotFound();

        $this->get($wax->url())->assertOk()
            ->assertSee('Mat Wax 100 ml')->assertSee('320,00 ₺')->assertSee('KDV dahil')->assertSee('Günlük')
            ->assertSee('https://wa.me/905550000000?text=', false)
            ->assertSee(route('quote.show', ['urun' => $wax->slug]), false);
    }

    public function test_admin_can_manage_price_items_and_reorder(): void
    {
        $user = $this->editor();

        $this->actingAs($user)->get(route('admin.price-items.index'))->assertOk();
        $this->actingAs($user)->get(route('admin.price-items.create'))->assertOk();
        $this->actingAs($user)->post(route('admin.price-items.store'), [
            'name' => 'Saç Kesimi', 'description' => 'Yıkama dahil', 'price' => '350', 'price_note' => '', 'group' => ' Saç ', 'is_active' => 1,
        ])->assertRedirect();

        $item = PriceItem::query()->firstOrFail();
        $this->assertSame('Saç', $item->group);
        $this->assertNull($item->price_note);
        $this->assertSame('350 ₺', $item->formattedPrice());

        $this->actingAs($user)->post(route('admin.price-items.store'), ['name' => 'Hatalı', 'price' => '-5'])->assertSessionHasErrors('price');

        $this->actingAs($user)->get(route('admin.price-items.edit', $item))->assertOk()->assertSee('Saç Kesimi');
        $this->actingAs($user)->put(route('admin.price-items.update', $item), ['name' => 'Saç Kesimi', 'price' => '', 'price_note' => 'Fiyat sorunuz', 'is_active' => 0])->assertRedirect();
        $this->assertNull($item->fresh()->price);
        $this->assertFalse($item->fresh()->is_active);

        $other = PriceItem::factory()->create();
        $this->actingAs($user)->postJson(route('admin.price-items.reorder'), ['order' => [$other->id, $item->id]])->assertOk();
        $this->assertSame(0, $other->fresh()->sort_order);
        $this->assertSame(1, $item->fresh()->sort_order);

        $this->actingAs($user)->delete(route('admin.price-items.destroy', $item));
        $this->assertModelMissing($item);
    }

    public function test_admin_can_manage_gallery_items(): void
    {
        $user = $this->editor();

        $this->actingAs($user)->get(route('admin.gallery.index'))->assertOk();
        $this->actingAs($user)->get(route('admin.gallery.create'))->assertOk();
        $this->actingAs($user)->post(route('admin.gallery.store'), ['title' => 'Skin Fade', 'image' => 'media/fade.jpg', 'category' => 'Saç', 'is_active' => 1])->assertRedirect(route('admin.gallery.index'));
        $this->actingAs($user)->post(route('admin.gallery.store'), ['title' => 'Görselsiz'])->assertSessionHasErrors('image');

        $item = GalleryItem::query()->firstOrFail();
        $this->assertSame('media/fade.jpg', $item->image);

        $this->actingAs($user)->get(route('admin.gallery.edit', $item))->assertOk()->assertSee('Skin Fade');
        $this->actingAs($user)->put(route('admin.gallery.update', $item), ['title' => '', 'image' => 'media/fade-2.jpg', 'category' => '', 'is_active' => 0])->assertRedirect();
        $this->assertNull($item->fresh()->title);
        $this->assertNull($item->fresh()->category);
        $this->assertFalse($item->fresh()->is_active);

        $this->actingAs($user)->delete(route('admin.gallery.destroy', $item));
        $this->assertModelMissing($item);
    }

    public function test_demo_seeder_is_idempotent_and_fills_the_site(): void
    {
        User::factory()->superAdmin()->create();

        // Gerçek kurulum sırası: önce ayarlar (şablon varsayılanları), sonra demo içerik.
        $this->seed(SettingsSeeder::class);
        $this->seed(DemoSeeder::class);
        $this->seed(DemoSeeder::class);

        $this->assertSame(12, PriceItem::query()->count());
        $this->assertSame(8, GalleryItem::query()->count());
        $this->assertSame(4, Service::query()->count());
        $this->assertSame(20, Product::query()->count());
        $this->assertSame(10, Category::query()->ofType('product')->count());
        $this->assertSame(6, Partner::query()->count());
        $this->assertDatabaseCount('team_members', 3);
        $this->assertDatabaseCount('testimonials', 4);
        $this->assertDatabaseCount('faqs', 5);
        $this->assertDatabaseCount('posts', 3);

        $this->get('/')->assertOk()->assertSee('Nova Market')->assertSee('Aynı Gün Teslimat')->assertSee('Süt Ürünleri')->assertSee('Ülker')->assertSee('/images/templates/business/brands/ulker.png', false)->assertSee('/images/templates/business/hero.jpg', false)->assertSee('/images/templates/business/cat-01.png', false)->assertDontSee('Bakkal');
        $this->get('/fiyat-listesi')->assertOk()->assertSeeInOrder(['Meyve & Sebze', 'Süt Ürünleri', 'Temel Gıda']);
        $this->get('/galeri')->assertOk()->assertSee('Meyve Sebze Reyonu');
    }

    public function test_sector_admin_routes_require_permission_and_dashboard_and_sitemap(): void
    {
        $this->get(route('admin.price-items.index'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.gallery.index'))->assertRedirect(route('admin.login'));

        ContactMessage::factory()->create(['type' => 'quote', 'is_read' => false]);

        $this->actingAs($this->superAdmin())->get(route('admin.dashboard'))->assertOk()
            ->assertSee('Fiyat Listesi')->assertSee('Galeri')->assertSee('Bekleyen Siparişler')->assertSee('İşletme');

        $this->get('/sitemap.xml')->assertOk()
            ->assertSee(route('prices.index'))
            ->assertSee(route('gallery.index'))
            ->assertSee(route('quote.show'))
            ->assertSee(route('products.index'));
    }
}
