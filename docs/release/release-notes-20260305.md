# Release Notes - 2026-03-05

## Summary
- Prepared production readiness checks for Lunar Ambalaj local environment.
- Cleared prelaunch blockers for production env assertions and SEO static endpoints.

## Changes
- Added repository bootstrap commit for current project snapshot.
- Added static `public/robots.txt` with `/admin` disallow and sitemap reference.
- Added static `public/llms.txt` with company summary and sitemap/robots links.
- Updated `.gitignore` to exclude local-only artifacts.
- Scoped GTM/Meta injection in layout to production environment only.

## Validation
- Prelaunch audit: blockers resolved (`Blockers=0`).
- Release publish gate (dry-run): pending re-run after release notes creation.

## Risks
- SMTP credentials are placeholders and must be set in production environment.
- Push/tag intentionally not executed pending explicit approval.
