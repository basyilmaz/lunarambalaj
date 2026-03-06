# DNS Cutover Checklist (.com.tr -> .com)

## Scope
- Primary domain: `https://lunarambalaj.com`
- Secondary domain: `https://lunarambalaj.com.tr`
- Rule: all `.com.tr/*` requests must return `301` to `.com/*` and keep query string.

## 1) DNS
- Point `lunarambalaj.com.tr` and `www.lunarambalaj.com.tr` to the active web server/CDN.
- Wait until DNS propagation is visible from at least 2 public resolvers.

## 2) Web Server Redirect (preferred at edge/server)
- Apply permanent redirect on `.com.tr` host:
  - `https://lunarambalaj.com.tr$request_uri` -> `https://lunarambalaj.com$request_uri` with `301`.
- Do not chain redirects (`www` -> non-www and `.com.tr` -> `.com`) in multiple hops.

## 3) Post-cutover Tests
- Run:
  - `powershell -ExecutionPolicy Bypass -File .\scripts\test-canonical-redirect.ps1 -SecondaryBaseUrl https://lunarambalaj.com.tr -PrimaryBaseUrl https://lunarambalaj.com -IncludeQueryStringCheck`
- Expected:
  - `/kvkk` -> `301` -> `https://lunarambalaj.com/kvkk`
  - `/en/privacy-policy` -> `301` -> `https://lunarambalaj.com/en/privacy-policy`
  - `/kvkk?x=1` keeps `x=1`

## 4) SEO Smoke
- Confirm canonical tags are still `.com` on TR/EN/RU/AR pages.
- Confirm `sitemap.xml` and `robots.txt` use `.com` links.
