param(
    [string]$ProjectRoot = (Get-Location).Path,
    [string]$BaseUrl = 'http://127.0.0.1:4050',
    [ValidateSet('local','staging','production')]
    [string]$Target = 'production',
    [string]$OutputPath = '',
    [switch]$SkipQualityGate,
    [switch]$SkipDbHealth,
    [switch]$SkipResponsiveAudit
)

Set-StrictMode -Version Latest
$ErrorActionPreference = 'Stop'

$psCmd = Get-Command pwsh -ErrorAction SilentlyContinue
if (-not $psCmd) {
    $psCmd = Get-Command powershell -ErrorAction SilentlyContinue
}
if (-not $psCmd) {
    throw 'No PowerShell executable found (pwsh/powershell).'
}

$results = New-Object System.Collections.Generic.List[object]

function Add-Result {
    param(
        [string]$Severity,
        [string]$Area,
        [string]$Check,
        [string]$Status,
        [string]$Evidence,
        [string]$Action
    )

    $results.Add([pscustomobject]@{
        Severity = $Severity
        Area = $Area
        Check = $Check
        Status = $Status
        Evidence = $Evidence
        Action = $Action
    })
}

function Get-EnvMap {
    param([string]$EnvPath)

    $map = @{}
    if (-not (Test-Path $EnvPath)) {
        return $map
    }

    foreach ($line in (Get-Content $EnvPath)) {
        if ($line -match '^\s*#') { continue }
        if ($line -match '^\s*$') { continue }
        if ($line -cmatch '^\s*([A-Z0-9_]+)=(.*)$') {
            $k = $matches[1]
            $v = $matches[2].Trim()
            if ($v.StartsWith('"') -and $v.EndsWith('"')) {
                $v = $v.Substring(1, $v.Length - 2)
            }
            $map[$k] = $v
        }
    }

    return $map
}

function Get-Http {
    param([string]$Url)

    try {
        $response = Invoke-WebRequest -Uri $Url -UseBasicParsing -TimeoutSec 20
        return [pscustomobject]@{ StatusCode = [int]$response.StatusCode; Content = [string]$response.Content; Error = '' }
    }
    catch {
        if ($_.Exception.Response) {
            $statusCode = [int]$_.Exception.Response.StatusCode
            return [pscustomobject]@{ StatusCode = $statusCode; Content = ''; Error = $_.Exception.Message }
        }

        return [pscustomobject]@{ StatusCode = 0; Content = ''; Error = $_.Exception.Message }
    }
}

function Test-WritableDir {
    param([string]$Path)

    if (-not (Test-Path $Path)) {
        return [pscustomobject]@{ Ok = $false; Evidence = 'Directory missing' }
    }

    $probe = Join-Path $Path ('.write-test-' + [guid]::NewGuid().ToString('N') + '.tmp')

    try {
        [System.IO.File]::WriteAllText($probe, 'ok', (New-Object System.Text.UTF8Encoding($false)))
        Remove-Item $probe -Force
        return [pscustomobject]@{ Ok = $true; Evidence = 'Writable' }
    }
    catch {
        return [pscustomobject]@{ Ok = $false; Evidence = $_.Exception.Message }
    }
}

function Test-Utf8Bom {
    param([string]$Root)

    $scanRoots = @(
        (Join-Path $Root 'app'),
        (Join-Path $Root 'routes'),
        (Join-Path $Root 'resources\views'),
        (Join-Path $Root 'lang'),
        (Join-Path $Root 'database\seeders')
    )

    $bomHits = New-Object System.Collections.Generic.List[string]

    foreach ($scanRoot in $scanRoots) {
        if (-not (Test-Path $scanRoot)) { continue }

        Get-ChildItem -Path $scanRoot -Recurse -File | ForEach-Object {
            $file = $_.FullName
            try {
                $bytes = [System.IO.File]::ReadAllBytes($file)
                if ($bytes.Length -ge 3 -and $bytes[0] -eq 0xEF -and $bytes[1] -eq 0xBB -and $bytes[2] -eq 0xBF) {
                    $bomHits.Add($file)
                }
            }
            catch {
                # ignore unreadable files
            }
        }
    }

    return $bomHits
}

function Find-Keyword {
    param(
        [string]$Root,
        [string]$Keyword
    )

    $rg = Get-Command rg -ErrorAction SilentlyContinue
    if ($rg) {
        Push-Location $Root
        try {
            $lines = & rg -n -S --glob '!vendor/**' --glob '!node_modules/**' --glob '!storage/**' $Keyword resources app routes 2>$null
            if ($LASTEXITCODE -eq 0 -and $lines) {
                return @($lines)
            }
            return @()
        }
        finally {
            Pop-Location
        }
    }

    $hits = Select-String -Path (Join-Path $Root 'resources\*'), (Join-Path $Root 'app\*'), (Join-Path $Root 'routes\*') -Pattern $Keyword -SimpleMatch -Recurse -ErrorAction SilentlyContinue
    return @($hits | ForEach-Object { "$($_.Path):$($_.LineNumber)" })
}

if (-not (Test-Path (Join-Path $ProjectRoot 'artisan'))) {
    throw "Invalid ProjectRoot: artisan not found at $ProjectRoot"
}

$envPath = Join-Path $ProjectRoot '.env'
$envMap = Get-EnvMap -EnvPath $envPath

if ($envMap.Count -eq 0) {
    Add-Result -Severity 'BLOCKER' -Area 'Env' -Check '.env availability' -Status 'FAIL' -Evidence '.env missing or empty' -Action 'Prepare production-ready .env before release.'
}
else {
    $expectations = @{
        APP_ENV = 'production'
        APP_DEBUG = 'false'
        APP_URL = 'https://lunarambalaj.com'
        APP_LOCALE = 'tr'
        APP_FALLBACK_LOCALE = 'en'
    }

    foreach ($k in $expectations.Keys) {
        $actual = ''
        if ($envMap.ContainsKey($k)) { $actual = $envMap[$k] }
        $expected = $expectations[$k]

        if ([string]::IsNullOrWhiteSpace($actual)) {
            Add-Result -Severity 'BLOCKER' -Area 'Env' -Check $k -Status 'FAIL' -Evidence 'Missing value' -Action "Set $k=$expected"
            continue
        }

        if ($Target -eq 'production' -and $actual -ne $expected) {
            if ($k -eq 'APP_URL') {
                Add-Result -Severity 'WARNING' -Area 'Env' -Check $k -Status 'WARN' -Evidence "Actual=$actual Expected=$expected" -Action 'Prefer APP_URL primary domain for consistency; CANONICAL_URL remains source of truth.'
            }
            else {
                Add-Result -Severity 'BLOCKER' -Area 'Env' -Check $k -Status 'FAIL' -Evidence "Actual=$actual Expected=$expected" -Action "Fix $k for production release"
            }
        }
        elseif ($actual -ne $expected) {
            Add-Result -Severity 'WARNING' -Area 'Env' -Check $k -Status 'WARN' -Evidence "Actual=$actual Expected=$expected" -Action 'Confirm this is intentional for non-production target.'
        }
        else {
            Add-Result -Severity 'PASS' -Area 'Env' -Check $k -Status 'OK' -Evidence $actual -Action ''
        }
    }

    $dbConnection = if ($envMap.ContainsKey('DB_CONNECTION')) { $envMap['DB_CONNECTION'] } else { '' }
    if ($Target -eq 'production' -and $dbConnection -eq 'sqlite') {
        Add-Result -Severity 'BLOCKER' -Area 'Env' -Check 'DB_CONNECTION' -Status 'FAIL' -Evidence 'sqlite' -Action 'Use mysql/mariadb in production.'
    }
    else {
        Add-Result -Severity 'PASS' -Area 'Env' -Check 'DB_CONNECTION' -Status 'OK' -Evidence $dbConnection -Action ''
    }

    $mailMailer = if ($envMap.ContainsKey('MAIL_MAILER')) { $envMap['MAIL_MAILER'] } else { '' }
    if ($mailMailer -ne 'smtp') {
        Add-Result -Severity 'WARNING' -Area 'Env' -Check 'MAIL_MAILER' -Status 'WARN' -Evidence "Actual=$mailMailer" -Action 'Set smtp and validate sending from contact/quote forms.'
    }
    else {
        Add-Result -Severity 'PASS' -Area 'Env' -Check 'MAIL_MAILER' -Status 'OK' -Evidence 'smtp' -Action ''
    }

    $canonicalUrl = if ($envMap.ContainsKey('CANONICAL_URL')) { $envMap['CANONICAL_URL'] } else { '' }
    if ([string]::IsNullOrWhiteSpace($canonicalUrl)) {
        Add-Result -Severity 'WARNING' -Area 'Env' -Check 'CANONICAL_URL' -Status 'WARN' -Evidence 'Missing value (config fallback used)' -Action 'Set CANONICAL_URL=https://lunarambalaj.com for explicit production config.'
    }
    elseif ($canonicalUrl -ne 'https://lunarambalaj.com') {
        Add-Result -Severity 'WARNING' -Area 'Env' -Check 'CANONICAL_URL' -Status 'WARN' -Evidence "Actual=$canonicalUrl Expected=https://lunarambalaj.com" -Action 'Align canonical URL to primary domain.'
    }
    else {
        Add-Result -Severity 'PASS' -Area 'Env' -Check 'CANONICAL_URL' -Status 'OK' -Evidence $canonicalUrl -Action ''
    }

    $canonicalHost = if ($envMap.ContainsKey('CANONICAL_HOST')) { $envMap['CANONICAL_HOST'] } else { '' }
    if ([string]::IsNullOrWhiteSpace($canonicalHost)) {
        Add-Result -Severity 'WARNING' -Area 'Env' -Check 'CANONICAL_HOST' -Status 'WARN' -Evidence 'Missing value (config fallback used)' -Action 'Set CANONICAL_HOST=lunarambalaj.com for explicit production config.'
    }
    elseif ($canonicalHost -ne 'lunarambalaj.com') {
        Add-Result -Severity 'WARNING' -Area 'Env' -Check 'CANONICAL_HOST' -Status 'WARN' -Evidence "Actual=$canonicalHost Expected=lunarambalaj.com" -Action 'Align canonical host to primary domain.'
    }
    else {
        Add-Result -Severity 'PASS' -Area 'Env' -Check 'CANONICAL_HOST' -Status 'OK' -Evidence $canonicalHost -Action ''
    }
}

$writeChecks = @('storage', 'bootstrap\cache')
foreach ($dir in $writeChecks) {
    $fullPath = Join-Path $ProjectRoot $dir
    $write = Test-WritableDir -Path $fullPath
    if (-not $write.Ok) {
        Add-Result -Severity 'BLOCKER' -Area 'Filesystem' -Check "$dir writable" -Status 'FAIL' -Evidence $write.Evidence -Action 'Fix folder existence and write permissions.'
    }
    else {
        Add-Result -Severity 'PASS' -Area 'Filesystem' -Check "$dir writable" -Status 'OK' -Evidence $write.Evidence -Action ''
    }
}

$qualityGatePath = Join-Path $ProjectRoot 'scripts\run-quality-gate.ps1'
if ($SkipQualityGate) {
    Add-Result -Severity 'WARNING' -Area 'QA' -Check 'run-quality-gate.ps1' -Status 'WARN' -Evidence 'Skipped by parameter' -Action 'Use skip only for fast local diagnostics.'
}
elseif (Test-Path $qualityGatePath) {
    Push-Location $ProjectRoot
    try {
        & $psCmd.Source -NoProfile -ExecutionPolicy Bypass -File $qualityGatePath *> $null
        $qualityCode = $LASTEXITCODE
    }
    finally {
        Pop-Location
    }

    if ($qualityCode -ne 0) {
        Add-Result -Severity 'BLOCKER' -Area 'QA' -Check 'run-quality-gate.ps1' -Status 'FAIL' -Evidence "ExitCode=$qualityCode" -Action 'Fix failing tests/linters and rerun.'
    }
    else {
        Add-Result -Severity 'PASS' -Area 'QA' -Check 'run-quality-gate.ps1' -Status 'OK' -Evidence 'All gates green' -Action ''
    }
}
else {
    Add-Result -Severity 'BLOCKER' -Area 'QA' -Check 'run-quality-gate.ps1' -Status 'FAIL' -Evidence 'Script missing' -Action 'Add mandatory quality gate script.'
}

$dbHealthPath = Join-Path $ProjectRoot 'scripts\run-db-health-check.ps1'
if ($SkipDbHealth) {
    Add-Result -Severity 'WARNING' -Area 'DB' -Check 'run-db-health-check.ps1' -Status 'WARN' -Evidence 'Skipped by parameter' -Action 'Skip only with explicit risk acceptance.'
}
elseif (Test-Path $dbHealthPath) {
    Push-Location $ProjectRoot
    try {
        $dbArgs = @('-NoProfile', '-ExecutionPolicy', 'Bypass', '-File', $dbHealthPath, '-ProjectRoot', $ProjectRoot)
        if ($Target -eq 'production') {
            $dbArgs += '-ExpectSeeded'
        }

        $dbOutput = & $psCmd.Source @dbArgs 2>&1
        $dbExitCode = $LASTEXITCODE
    }
    finally {
        Pop-Location
    }

    if ($dbExitCode -ne 0) {
        Add-Result -Severity 'BLOCKER' -Area 'DB' -Check 'run-db-health-check.ps1' -Status 'FAIL' -Evidence ($dbOutput | Out-String).Trim() -Action 'Fix DB schema/seed issues before release.'
    }
    else {
        Add-Result -Severity 'PASS' -Area 'DB' -Check 'run-db-health-check.ps1' -Status 'OK' -Evidence 'Database health validated' -Action ''
    }
}
else {
    Add-Result -Severity 'WARNING' -Area 'DB' -Check 'run-db-health-check.ps1' -Status 'WARN' -Evidence 'Script missing' -Action 'Add DB health script to prevent runtime schema failures.'
}

$responsiveAuditPath = Join-Path $ProjectRoot 'scripts\run-responsive-audit.ps1'
if ($SkipResponsiveAudit) {
    Add-Result -Severity 'WARNING' -Area 'QA' -Check 'run-responsive-audit.ps1' -Status 'WARN' -Evidence 'Skipped by parameter' -Action 'Use skip only for fast local diagnostics.'
}
elseif (Test-Path $responsiveAuditPath) {
    Push-Location $ProjectRoot
    try {
        $responsiveOutput = & $psCmd.Source -NoProfile -ExecutionPolicy Bypass -File $responsiveAuditPath -ProjectRoot $ProjectRoot -BaseUrl $BaseUrl 2>&1
        $responsiveExitCode = $LASTEXITCODE
    }
    finally {
        Pop-Location
    }

    $responsiveText = ($responsiveOutput | Out-String)
    $responsiveReport = ''
    if ($responsiveText -match '\[responsive-audit\]\s+Report:\s*(.+)') {
        $responsiveReport = $matches[1].Trim()
    }

    if ($responsiveExitCode -ne 0) {
        Add-Result -Severity 'BLOCKER' -Area 'QA' -Check 'run-responsive-audit.ps1' -Status 'FAIL' -Evidence "ExitCode=$responsiveExitCode Report=$responsiveReport" -Action 'Fix responsive overflow blockers and rerun.'
    }
    else {
        Add-Result -Severity 'PASS' -Area 'QA' -Check 'run-responsive-audit.ps1' -Status 'OK' -Evidence "Report=$responsiveReport" -Action ''
    }
}
else {
    Add-Result -Severity 'WARNING' -Area 'QA' -Check 'run-responsive-audit.ps1' -Status 'WARN' -Evidence 'Script missing' -Action 'Add responsive audit script for browser-width regression checks.'
}

$routes = @(
    '/', '/hakkimizda', '/urunler', '/cozumler', '/iletisim', '/teklif-al', '/kvkk', '/cerez-politikasi', '/gizlilik-politikasi', '/mesafeli-satis-sozlesmesi', '/kullanim-sartlari',
    '/en', '/en/about', '/en/products', '/en/solutions', '/en/contact', '/en/get-quote', '/en/kvkk', '/en/cookie-policy', '/en/privacy-policy', '/en/distance-sales-contract', '/en/terms-of-use',
    '/ru', '/ru/about', '/ru/products', '/ru/solutions', '/ru/contact', '/ru/get-quote', '/ru/kvkk', '/ru/cookie-policy', '/ru/privacy-policy', '/ru/distance-sales-contract', '/ru/terms-of-use',
    '/ar', '/ar/about', '/ar/products', '/ar/solutions', '/ar/contact', '/ar/get-quote', '/ar/kvkk', '/ar/cookie-policy', '/ar/privacy-policy', '/ar/distance-sales-contract', '/ar/terms-of-use',
    '/robots.txt', '/sitemap.xml', '/llms.txt'
)

$routeFailures = 0
$captured = @{}
foreach ($route in $routes) {
    $url = "$BaseUrl$route"
    $http = Get-Http -Url $url
    $captured[$route] = $http
    if ($http.StatusCode -ne 200) {
        $routeFailures++
        Add-Result -Severity 'BLOCKER' -Area 'HTTP' -Check $route -Status 'FAIL' -Evidence "HTTP=$($http.StatusCode) $($http.Error)" -Action 'Fix route/controller/view before release.'
    }
    else {
        Add-Result -Severity 'PASS' -Area 'HTTP' -Check $route -Status 'OK' -Evidence '200' -Action ''
    }
}

$legalRoutes = @('/kvkk','/cerez-politikasi','/gizlilik-politikasi','/mesafeli-satis-sozlesmesi','/kullanim-sartlari','/en/kvkk','/en/cookie-policy','/en/privacy-policy','/en/distance-sales-contract','/en/terms-of-use','/ru/kvkk','/ru/cookie-policy','/ru/privacy-policy','/ru/distance-sales-contract','/ru/terms-of-use','/ar/kvkk','/ar/cookie-policy','/ar/privacy-policy','/ar/distance-sales-contract','/ar/terms-of-use')
foreach ($route in $legalRoutes) {
    if ($captured.ContainsKey($route) -and $captured[$route].StatusCode -eq 200) {
        $len = $captured[$route].Content.Length
        if ($len -lt 700) {
            Add-Result -Severity 'WARNING' -Area 'Legal' -Check "$route content length" -Status 'WARN' -Evidence "Length=$len" -Action 'Expand legal copy with policy-specific details.'
        }
        else {
            Add-Result -Severity 'PASS' -Area 'Legal' -Check "$route content length" -Status 'OK' -Evidence "Length=$len" -Action ''
        }
    }
}

if ($captured.ContainsKey('/robots.txt') -and $captured['/robots.txt'].StatusCode -eq 200) {
    $robots = $captured['/robots.txt'].Content
    if ($robots -notmatch 'Disallow:\s*/admin') {
        Add-Result -Severity 'WARNING' -Area 'SEO' -Check 'robots disallow admin' -Status 'WARN' -Evidence 'Rule missing' -Action 'Add Disallow: /admin'
    }
    else {
        Add-Result -Severity 'PASS' -Area 'SEO' -Check 'robots disallow admin' -Status 'OK' -Evidence 'Found' -Action ''
    }

    if ($robots -notmatch 'Sitemap:\s*https?://') {
        Add-Result -Severity 'WARNING' -Area 'SEO' -Check 'robots sitemap line' -Status 'WARN' -Evidence 'Sitemap line missing' -Action 'Add absolute sitemap URL.'
    }
    else {
        Add-Result -Severity 'PASS' -Area 'SEO' -Check 'robots sitemap line' -Status 'OK' -Evidence 'Found' -Action ''
    }
}

if ($captured.ContainsKey('/sitemap.xml') -and $captured['/sitemap.xml'].StatusCode -eq 200) {
    try {
        [xml]$sitemapXml = $captured['/sitemap.xml'].Content
        $urlCount = @($sitemapXml.urlset.url).Count
        if ($urlCount -lt 20) {
            Add-Result -Severity 'WARNING' -Area 'SEO' -Check 'sitemap url count' -Status 'WARN' -Evidence "Count=$urlCount" -Action 'Review missing pages/posts/products.'
        }
        else {
            Add-Result -Severity 'PASS' -Area 'SEO' -Check 'sitemap url count' -Status 'OK' -Evidence "Count=$urlCount" -Action ''
        }
    }
    catch {
        Add-Result -Severity 'BLOCKER' -Area 'SEO' -Check 'sitemap parse xml' -Status 'FAIL' -Evidence $_.Exception.Message -Action 'Return valid XML from sitemap endpoint.'
    }
}

if ($captured.ContainsKey('/llms.txt') -and $captured['/llms.txt'].StatusCode -eq 200) {
    $llms = $captured['/llms.txt'].Content
    if ($llms -match 'sitemap\.xml') {
        Add-Result -Severity 'PASS' -Area 'AI-Bot' -Check 'llms sitemap reference' -Status 'OK' -Evidence 'Found' -Action ''
    }
    else {
        Add-Result -Severity 'WARNING' -Area 'AI-Bot' -Check 'llms sitemap reference' -Status 'WARN' -Evidence 'Missing sitemap.xml mention' -Action 'Add sitemap link in llms.txt.'
    }
}

$trackingKeywords = @('lead_submit','click_phone','click_whatsapp')
foreach ($kw in $trackingKeywords) {
    $hits = Find-Keyword -Root $ProjectRoot -Keyword $kw
    if ($hits.Count -eq 0) {
        Add-Result -Severity 'WARNING' -Area 'Tracking' -Check $kw -Status 'WARN' -Evidence 'No source match' -Action 'Implement event trigger and verify via GTM/Pixel debugger.'
    }
    else {
        $sample = ($hits | Select-Object -First 2) -join ' | '
        Add-Result -Severity 'PASS' -Area 'Tracking' -Check $kw -Status 'OK' -Evidence $sample -Action ''
    }
}

$bomHits = @(Test-Utf8Bom -Root $ProjectRoot)
if ($bomHits.Count -gt 0) {
    $sample = ($bomHits | Select-Object -First 5) -join '; '
    Add-Result -Severity 'BLOCKER' -Area 'Encoding' -Check 'UTF-8 BOM scan' -Status 'FAIL' -Evidence "BOM files=$($bomHits.Count). Sample: $sample" -Action 'Convert files to UTF-8 without BOM.'
}
else {
    Add-Result -Severity 'PASS' -Area 'Encoding' -Check 'UTF-8 BOM scan' -Status 'OK' -Evidence 'No BOM found in scanned paths' -Action ''
}

$blockers = @($results | Where-Object { $_.Severity -eq 'BLOCKER' }).Count
$warnings = @($results | Where-Object { $_.Severity -eq 'WARNING' }).Count
$passes = @($results | Where-Object { $_.Severity -eq 'PASS' }).Count

if ([string]::IsNullOrWhiteSpace($OutputPath)) {
    $stamp = Get-Date -Format 'yyyyMMdd-HHmmss'
    $OutputPath = Join-Path $ProjectRoot "docs\release\prelaunch-audit-$stamp.md"
}

$outputDir = Split-Path -Path $OutputPath -Parent
if (-not (Test-Path $outputDir)) {
    New-Item -ItemType Directory -Path $outputDir -Force | Out-Null
}

$report = New-Object System.Collections.Generic.List[string]
$report.Add("# Prelaunch Audit Report")
$report.Add("")
$report.Add("- ProjectRoot: $ProjectRoot")
$report.Add("- BaseUrl: $BaseUrl")
$report.Add("- Target: $Target")
$report.Add("- GeneratedAt: $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss zzz')")
$report.Add("")
$report.Add("## Summary")
$report.Add("")
$report.Add("| Metric | Count |")
$report.Add("|---|---:|")
$report.Add("| Blockers | $blockers |")
$report.Add("| Warnings | $warnings |")
$report.Add("| Pass | $passes |")
$report.Add("")
$report.Add("## Findings")
$report.Add("")
$report.Add("| Severity | Area | Check | Status | Evidence | Action |")
$report.Add("|---|---|---|---|---|---|")

$ordered = $results | Sort-Object @{Expression={ if ($_.Severity -eq 'BLOCKER') {0} elseif ($_.Severity -eq 'WARNING') {1} else {2} }}, Area, Check
foreach ($item in $ordered) {
    $evidence = ($item.Evidence -replace '\|','/').Trim()
    $action = ($item.Action -replace '\|','/').Trim()
    $report.Add("| $($item.Severity) | $($item.Area) | $($item.Check) | $($item.Status) | $evidence | $action |")
}

$goNoGo = if ($blockers -eq 0) { 'GO-CANDIDATE' } else { 'NO-GO' }
$report.Add("")
$report.Add("## Decision")
$report.Add("")
$report.Add("- Result: **$goNoGo**")
$report.Add("- Rule: Blocker count must be zero for release.")

$enc = New-Object System.Text.UTF8Encoding($false)
[System.IO.File]::WriteAllLines($OutputPath, $report, $enc)

Write-Host "[prelaunch-audit] Report: $OutputPath"
Write-Host "[prelaunch-audit] Blockers=$blockers Warnings=$warnings Pass=$passes"

if ($blockers -gt 0) {
    exit 1
}

exit 0
