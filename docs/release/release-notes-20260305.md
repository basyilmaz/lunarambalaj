# Release Notes - 2026-03-05

## Summary
- Completed legal infrastructure rollout for 4 locales (`tr`, `en`, `ru`, `ar`) with shared Laravel legal page rendering.
- Enforced primary canonical domain strategy (`https://lunarambalaj.com`) across canonical/hreflang generation.
- Cleared release gate blocker by finalizing commits and re-running governance checks.

## Changes
- Added `LegalPageController` and new legal routes for:
  - KVKK / Privacy Notice
  - Privacy Policy
  - Cookie Policy
  - Distance Sales Contract
  - Terms of Use
- Added shared legal template: `resources/views/legal/page.blade.php`.
- Added migration: `2026_03_05_120000_add_key_to_pages_table.php`.
- Rebuilt legal seed data in `LegalPolicyContentSeeder` for all 4 locales with structured legal sections.
- Added explicit legal notice block under legal page H1 (TR/EN/RU/AR).
- Updated quote form KVKK consent checkbox text in 4 languages with locale-specific KVKK links.
- Updated language switcher to use current page alternates with safe locale-home fallback.
- Expanded product/blog detail hreflang alternates to include `ru` and `ar`.
- Added canonical source hardening via `site.canonical_url` (`CANONICAL_URL`) and `CANONICAL_HOST`.
- Updated static SEO files:
  - `public/robots.txt` sitemap URL -> `.com`
  - `public/llms.txt` important URLs -> `.com`
- Updated docs/env examples for canonical config fields.

## Validation
- `php artisan test` -> pass.
- `scripts/run-quality-gate.ps1` -> pass.
- Prelaunch audit (staging dry run):
  - `docs/release/prelaunch-audit-20260305-122610.md`
  - `Blockers=0`, `Warnings=1`
- Release publish gate (dry run, no push):
  - `docs/release/release-publish-gate-20260305-122416.md`
  - `Blockers=0`, `Warnings=1`

## Remaining Risks
- SMTP config warning remains (`MAIL_MAILER` check in audit) and should be validated with real production SMTP credentials.
- Remote baseline warning remains (`origin/main` not found) until remote main branch is initialized/pushed.
- Push/tag intentionally not executed pending explicit go/no-go approval.
