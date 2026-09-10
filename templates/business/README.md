# İşletme Şablonu (`business`)

Market, manav, kasap, kafe, çiçekçi, tesisatçı, oto tamir, terzi gibi **tek şubeli küçük işletmeler** için hazır site. Market düzeni: iki katlı başlık + ürün arama, fotoğraflı hero, yuvarlak kategori ikonları, kampanya bantları ve çok satan ürünler. Telefon, WhatsApp, konum ve çalışma saatleri her sayfada göz önünde (masaüstünde başlık, mobilde alt sabit çubuk).

Hazır bir CMS kurmuyoruz; Laravel + MySQL + kendi admin panelimiz + kendi şablonumuz.

## Sektör içeriği
- **Fiyat Listesi** (`price_items`): ad, açıklama, fiyat, fiyat notu ("başlangıç", "/saat"), grup ("Meyve & Sebze", "Süt Ürünleri"...), sıra, aktif. Public `/fiyat-listesi` gruplara ayrılmış liste; admin **İşletme > Fiyat Listesi** (sürükle-bırak sıralama).
- **Galeri** (`gallery_items`): başlık, görsel, kategori, sıra, aktif. Public `/galeri` kategori sekmeleri + lightbox; admin **İşletme > Galeri**. Görselsiz kayıtlar (demo) yumuşak bir yer tutucu ile gösterilir.
- **Hızlı Teklif / Sipariş** `/teklif`: ad + telefon zorunlu, e-posta isteğe bağlı, hizmet (hizmetler + fiyat kalemleri), tercih edilen tarih, mesaj, KVKK, honeypot. Mesajlar'a "Sipariş / Teslimat Talebi" olarak düşer ve e-posta ile bildirilir. Ana sayfa hero'daki mini form da aynı adrese gönderir. `?hizmet=slug`, `?fiyat=id`, `?urun=slug` ile ön seçim.
- **WhatsApp ile sipariş**: `whatsapp` ayarı doluysa fiyat listesi ve ürün kartlarında "WhatsApp'tan sor" bağlantısı (`wa.me/<no>?text=...`).

## Kullanılan çekirdek özellikler
Hizmetler, Ürünler (+ kategoriler), Blog ("Haberler"), Ekip, Müşteri Yorumları, SSS, Partnerler (marka logoları). Hepsi açık.

## Şablon ayarları (Site Ayarları > İşletme Ayarları)
Fiyat listesi / galeri menü etiketi, sipariş sayfası açıklaması ve başarı mesajı, çalışma saati notu ("Eve teslimat: 09:00 - 21:00"), Instagram notu. Harita için çekirdekteki `map_embed` ayarı kullanılır; ana sayfadaki "Bize Ulaşın" bölümünde harita, saatler ve telefon/WhatsApp kartı gösterilir.

## Ana sayfa bölümleri
Hero (fotoğraf + sağda hizmet şeridi) → kategori ikonları → üç kampanya bandı → çok satan ürünler → hizmetler → günün fiyatları → hakkımızda → markalar → hızlı sipariş → galeri → yorumlar/SSS → mağaza & harita → haberler → CTA. Hero şeridi `hero_features`, bantlar `home_promos` ayarından düzenlenir.

## Marka logoları
Demo içerikteki marka logoları (`public/images/templates/business/brands/`) yalnızca örnek amaçlıdır; markaların kendi sitelerinden alınmıştır. Gerçek bir müşteri sitesinde yalnızca kullanım izni olan logoları yükleyin (Admin > Partnerler).

## Demo içerik
`DemoSeeder` bir mahalle marketi senaryosu kurar ("Nova Market"): 4 hizmet, 12 fiyat kalemi (3 grup), 8 galeri öğesi, 10 kategoride 20 ürün, 3 ekip üyesi, 4 yorum, 5 SSS, 6 marka, "Haberler" kategorisinde 3 yazı. Logo, hero, kategori ikonları ve kampanya görselleri `public/images/templates/business/` altındadır.

Başka bir esnaf için: Site Ayarları'ndan işletme adını, sloganı, renkleri ve metinleri değiştirin; fiyat listesi ile galeriyi kendi içeriğinizle doldurun. Kod değişikliği gerekmez.
