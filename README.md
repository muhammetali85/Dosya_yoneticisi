# PHP Dosya Yöneticisi

Basit, güvenli ve modern bir **web tabanlı dosya yöneticisi**.  
PHP + JavaScript (Tailwind CSS) ile geliştirildi.

## Özellikler

- Şifre ile giriş (`1234`)
- Canlı arama & uzantı filtresi
- Resim ve PDF önizleme
- Güvenli indirme
- Dosya silme & yeniden adlandırma
- Responsive + Dark Mode

## Kurulum

```bash
php -S localhost:8000
uploads/ klasörüne yazma izni ver:
bashchmod 777 uploads/
Güvenlik

Oturum kontrolü (auth.php)
Yol geçişi engellendi (realpath, basename)
MIME türü kontrolü

Not
Şifre 1234 → Üretimde değiştirin!
