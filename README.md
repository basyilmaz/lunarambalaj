# LunarAmbalaj MVP

Laravel 11 + PHP 8.3 + MySQL + Blade + TailwindCSS + Filament v3 ile TR/EN uretici kurumsal site ve katalog MVP.

## Localhost Kurulum
1. `composer install`
2. `npm install`
3. `.env.example` dosyasini `.env` olarak kopyalayin.
4. `php artisan key:generate`
5. `.env` icinde DB bilgilerini ayarlayin.
6. `php artisan migrate:fresh --seed`
7. `php artisan storage:link`
8. `npm run build` (veya gelistirme icin `npm run dev`)
9. `php artisan serve`
10. Site: `http://127.0.0.1:8000` / Admin: `http://127.0.0.1:8000/admin`

Not: Bu proje localde 4050 portunda da calistirilabilir:
- `php artisan serve --host=127.0.0.1 --port=4050`

## Varsayilan Admin
- Email: `admin@lunarambalaj.com.tr`
- Sifre: `password`
- Rol: `admin`

## ENV Konfig
Temel alanlar:
- `APP_URL=https://lunarambalaj.com`
- `CANONICAL_URL=https://lunarambalaj.com`
- `CANONICAL_HOST=lunarambalaj.com`
- `APP_LOCALE=tr`
- `APP_FALLBACK_LOCALE=en`
- `DB_DATABASE=lunarambalaj`
- `DB_USERNAME=root`
- `DB_PASSWORD=`
- `GTM_ID=`
- `META_PIXEL_ID=`
- `MAIL_MAILER=smtp`
- `MAIL_HOST=` / `MAIL_PORT=` / `MAIL_USERNAME=` / `MAIL_PASSWORD=`
- `MAIL_FROM_ADDRESS=info@lunarambalaj.com.tr`

Not: GTM ve Pixel ID alanlari env veya admin `settings` uzerinden verilebilir.

## Icerik Kapsami (Seed)
- 4 dil: `tr` (default), `en`, `ru`, `ar`
- 6 kategori: Pipet, Bardak, Pecete, Islak Mendil, Bayrakli Kurdan, Stick Seker
- 18 urun (TR+EN)
- 12 SSS (TR+EN)
- 8 blog yazisi (TR+EN)
- Hakkimizda + KVKK + Cerez + Gizlilik sayfalari (TR+EN)
- Guncel iletisim/NAP bilgileri

## Deployment Notlari
1. Web server tarafinda `www -> non-www` ve `http -> https` 301 yonlendirmelerini aktif edin.
2. Uygulamada canonical middleware bulunur (`app/Http/Middleware/EnforceCanonicalDomain.php`).
3. Build + cache:
   - `php artisan config:cache`
   - `php artisan route:cache`
   - `php artisan view:cache`
   - `npm run build`
4. Scheduler + queue worker:
   - Cron: `* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1`
   - Queue worker: `php artisan queue:work --tries=3`

## SEO Checklist
- [x] Canonical tag (dil bazli)
- [x] `hreflang`: `tr-TR`, `en`, `ru`, `ar`, `x-default`
- [x] Meta title (60) + meta description (160) limiti
- [x] OpenGraph + Twitter card
- [x] JSON-LD: Organization, LocalBusiness, Product, FAQPage, BreadcrumbList
- [x] `/robots.txt`
- [x] `/sitemap.xml`
- [x] `/llms.txt`

## Ads / Meta Checklist
- [x] GTM include (env/settings)
- [x] DataLayer events:
  - `lead_submit` (`lead_type`, `product_category`, `quantity`)
  - `click_phone`
  - `click_whatsapp`
  - `click_quote_cta`
- [x] Meta Pixel include (env/settings)
- [x] Quote success: `Lead`
- [x] Product detail: `ViewContent`
- [x] Server-side event logging endpoint: `POST /track/event`

## Ads Yonetim Modulu (Filament)
- `Ads Insights` sayfasi: son 30 gun lead/attribution/spend/CPL ozeti
- Tarih filtresi (from/to) + CSV export
- Attribution model filtresi: `last_touch` / `first_touch`
- UTM hygiene metrikleri: source coverage, invalid source, missing campaign, gclid/fbclid dagilimi
- Kaynak bazli lead dagilimi (`attribution_logs`)
- Urun ve kategori bazli quote donusum kirilimi (`leads.meta`)
- Event logs (`event_logs`) - phone / whatsapp / quote CTA / lead_submit
- Kampanya performans tablosu (`campaign_snapshots`)
- Yonetilebilir kaynaklar:
  - `AdIntegrationResource`
  - `TrackingEventResource`
  - `ConversionMappingResource`
  - `CampaignSnapshotResource` (read-only)
  - `AttributionLogResource` (read-only)
- Ads entegrasyon kimlik bilgileri veritabaninda sifreli olarak saklanir (`credentials_encrypted`), model katmaninda geriye uyumlu okunur.
- UTM/gclid/fbclid parametreleri middleware ile session'da tutulur ve lead olusunca attribution log'a yazilir.
- Attribution modeli: `first_touch` + `last_touch` (log meta icinde birlikte saklanir, raporlama varsayilani `last_touch`).

## Ads Sync (Scheduler + Job)
- Komut: `php artisan ads:sync-campaign-snapshots`
- Secenekler:
  - `--platform=google_ads|meta_ads`
  - `--from=YYYY-MM-DD --to=YYYY-MM-DD`
  - `--queue` (job queue'ya atar)
- Zamanlama: saatlik otomatik calisir (`routes/console.php`).

Offline conversion export:
- Komut: `php artisan ads:export-offline-conversions`
- Secenekler:
  - `--platform=google_ads|meta_ads`
  - `--from=YYYY-MM-DD --to=YYYY-MM-DD`
  - `--queue` (job queue'ya atar)
- Cikti: `storage/app/offline-conversions/*.csv`
- Zamanlama: gunluk otomatik calisir (`routes/console.php`).
- Adminden manuel tetikleme:
  - `Ads Insights > Sync Now`
  - `Ad Integrations > Sync Now` (platform satir aksiyonu)
  - `Ad Integrations > Test Connection` (1 gunluk test sync)
  - `Ad Integrations > Rotate Credentials` (admin/developer)
- Her sync denemesi `ads_sync_logs` tablosuna yazilir (`success`, `failed`, `skipped`).
- Son sync durumu `ad_integrations.last_sync_status` ve `last_sync_error` alanlarinda izlenir.

## Test
- `php artisan test`
- `powershell -ExecutionPolicy Bypass -File scripts/run-quality-gate.ps1`
- `powershell -ExecutionPolicy Bypass -File scripts/run-quality-gate.ps1 -BaseUrl http://127.0.0.1:4050` (responsive audit dahil)
- `pwsh ./scripts/run-responsive-audit.ps1 -ProjectRoot . -BaseUrl http://127.0.0.1:4050`
- `pwsh ./scripts/run-psi-report.ps1 -ProjectRoot . -Url https://lunarambalaj.com`

## Prelaunch Audit ve Release Gate
Staging prelaunch audit:
- `pwsh ./scripts/run_prelaunch_audit.ps1 -ProjectRoot . -BaseUrl http://127.0.0.1:4050 -Target staging`

Production prelaunch audit:
- `pwsh ./scripts/run_prelaunch_audit.ps1 -ProjectRoot . -BaseUrl https://lunarambalaj.com -Target production`

Release publish gate (dry-run):
- `pwsh ./scripts/run_release_publish_gate.ps1 -ProjectRoot . -Remote origin -MainBranch main`

Not:
- Her iki script de raporları `docs/release/` altına yazar.
- Blocker varsa non-zero exit code döner (CI fail).
- Push/tag sadece `-ExecutePush` ile çalışır.

## CI / Workflow
- `.github/workflows/quality-gate.yml`
  - `run-quality-gate.ps1 -BaseUrl <staging-url>` adımını çalıştırır.
  - `STAGING_BASE_URL` repo variable tanımlı değilse local `http://127.0.0.1:4050` fallback kullanır.
- `.github/workflows/psi-report.yml`
  - Günlük PSI raporu üretir ve `docs/release/perf-*.md` olarak commitler.
  - `PSI_API_KEY` (secret) verilirse API kotası daha stabil olur.

## Admin Is Akisi
- Lead Pipeline (Kanban): `/admin/lead-pipeline`
- Durumlar: `new`, `read`, `replied`, `archived`
- Drag & drop ile kolonlar arasi durum gecisi
- Kapsam:
  - Tum public route'lar 200 (TR+EN)
  - `robots.txt` 200
  - `sitemap.xml` XML doner
  - `llms.txt` 200
  - Contact form lead kaydi
  - Quote form lead kaydi + tesekkur sayfasina redirect

## Notlar
- Browser language auto redirect yoktur.
- Dil tercihi `site_lang` cookie ile saklanir.
- Formlarda honeypot + rate limit vardir.
- Uretim gorselleri SVG olarak proje icinde uretilmistir (`public/images/*.svg`).

