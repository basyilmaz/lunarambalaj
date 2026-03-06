# Release Notes - 2026-03-06

## Scope
- Improved mobile performance for production pages.
- Added form-level anti-spam hardening for contact and quote flows.
- Localized lead email subject/body to Turkish.
- Strengthened static asset cache/compression behavior.

## Performance Changes
- Deferred non-critical JS with dynamic imports (`swiper-init`, `animations`).
- Added image sizing and decoding hints across key Blade templates.
- Added hero image preload and priority hints on homepage.
- Enabled long-lived cache headers and compression in `public/.htaccess`.
- Switched Google Fonts loading to non-blocking strategy in layout head.

## Form Security and Delivery
- Added `FormSpamGuard` nonce+signature+timestamp validation.
- Added per-IP and per-email rate limiting in contact/quote controllers.
- Added basic spam keyword/link heuristics before lead creation.
- Confirmed lead records are stored for TR/EN/RU/AR form submissions.

## Production Verification
- Live deploy applied on `https://lunarambalaj.com`.
- Contact and quote forms tested with real POST requests on all 4 locales.
- No mail-related error found in latest Laravel log tail after tests.
- `.com.tr` domain still points to different server (DNS-level issue), redirect cannot be enforced from current host.

## Known Risks / Follow-up
- `.com.tr -> .com` 301 must be configured on the server currently hosting `lunarambalaj.com.tr` or DNS must be moved to Hostinger first.
- Lighthouse/PSI scores vary run-to-run; use median of multiple runs for release KPI.
