# Release Notes - 2026-03-11

## Scope
- Completed Spanish (`es`) rollout across public web content and legal surfaces.
- Enforced translation coverage controls in Filament admin before publishing active records.
- Hardened prelaunch DB health flow for non-production audits with explicit sqlite override support.

## Functional Changes
- Added native `es` localization keys for site copy and security messages.
- Expanded controller-level locale content/SEO mappings to include `es`.
- Refactored multi-language view rendering paths (home, services, solutions, products, faq, blog, quote, legal/page templates).
- Added `es` routes and locale handling updates where required.
- Added/updated tests for multilingual public routes, legal pages, SEO endpoints, and lead forms.

## Admin & Governance
- Added reusable translation coverage enforcement support for Filament create/edit workflows.
- Applied coverage guardrails to key resources (page/product/category/service/post/faq/case-study/testimonial).
- Updated translation relation/resource selectors for expanded language set.

## Quality & Audit
- Quality gate passing.
- Prelaunch audit passing with `Blockers=0` on staging base URL.
- Release publish gate currently blocked only by branch hygiene checks when workspace is dirty or release notes are missing.