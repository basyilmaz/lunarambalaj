param(
    [string]$ProjectRoot = (Get-Location).Path,
    [string]$BaseUrl = 'http://127.0.0.1:4050',
    [string[]]$Viewports = @('390x844', '768x1024'),
    [string[]]$Routes = @(
        '/', '/hakkimizda', '/urunler', '/teklif-al', '/kvkk',
        '/en', '/en/products', '/en/get-quote', '/en/kvkk',
        '/ru', '/ru/products', '/ru/get-quote', '/ru/kvkk',
        '/ar', '/ar/products', '/ar/get-quote', '/ar/kvkk'
    ),
    [string]$OutputPath = ''
)

Set-StrictMode -Version Latest
$ErrorActionPreference = 'Stop'

function Invoke-Playwright {
    param(
        [string[]]$CliArgs,
        [switch]$AllowFailure
    )

    $output = & npx --yes "@playwright/cli" @CliArgs 2>&1 | Out-String
    $exitCode = $LASTEXITCODE

    if (-not $AllowFailure -and $exitCode -ne 0) {
        throw "Playwright command failed: @playwright/cli $($CliArgs -join ' ')`n$output"
    }

    return [pscustomobject]@{
        ExitCode = $exitCode
        Output = $output
    }
}

function Parse-Viewport {
    param([string]$Viewport)
    if ($Viewport -notmatch '^(\d+)x(\d+)$') {
        throw "Invalid viewport format '$Viewport'. Expected format: WIDTHxHEIGHT"
    }

    return [pscustomobject]@{
        Width = [int]$matches[1]
        Height = [int]$matches[2]
        Label = $Viewport
    }
}

if (-not (Get-Command npx -ErrorAction SilentlyContinue)) {
    throw 'npx not found. Install Node.js/npm before running responsive audit.'
}

if (-not (Test-Path (Join-Path $ProjectRoot 'artisan'))) {
    throw "Invalid ProjectRoot: artisan not found at $ProjectRoot"
}

$requiredViewports = @('390x844', '768x1024')
foreach ($required in $requiredViewports) {
    if ($Viewports -notcontains $required) {
        throw "Missing required viewport '$required'. Responsive audit mandates both 390x844 and 768x1024."
    }
}

$now = Get-Date
if ([string]::IsNullOrWhiteSpace($OutputPath)) {
    $reportDir = Join-Path $ProjectRoot 'docs\release'
    if (-not (Test-Path $reportDir)) {
        New-Item -ItemType Directory -Path $reportDir -Force | Out-Null
    }
    $stamp = $now.ToString('yyyyMMdd-HHmmss')
    $OutputPath = Join-Path $reportDir "responsive-audit-$stamp.md"
}

$findings = New-Object System.Collections.Generic.List[object]
$blockers = 0
$warnings = 0
$passes = 0

try {
    Invoke-Playwright -CliArgs @('open', $BaseUrl) | Out-Null

    foreach ($vpRaw in $Viewports) {
        $vp = Parse-Viewport -Viewport $vpRaw
        Invoke-Playwright -CliArgs @('resize', "$($vp.Width)", "$($vp.Height)") | Out-Null

        foreach ($route in $Routes) {
            $url = if ($route -match '^https?://') { $route } else { "$BaseUrl$route" }

            Invoke-Playwright -CliArgs @('goto', $url) | Out-Null

            $evalRaw = Invoke-Playwright -CliArgs @(
                'eval',
                "() => ({ path: location.pathname, vw: window.innerWidth, sw: document.documentElement.scrollWidth, overflow: Math.max(0, document.documentElement.scrollWidth - window.innerWidth), bodyOverflow: Math.max(0, document.body.scrollWidth - window.innerWidth) })"
            )

            $match = [regex]::Match($evalRaw.Output, '(?s)### Result\s*(\{.*?\})\s*### Ran')
            if (-not $match.Success) {
                throw "Cannot parse responsive audit payload for route '$route' viewport '$($vp.Label)'."
            }
            $data = $match.Groups[1].Value | ConvertFrom-Json

            $overflowPx = [int]([Math]::Max([double]$data.overflow, [double]$data.bodyOverflow))
            $severity = 'PASS'
            $status = 'OK'
            $action = ''

            if ($overflowPx -gt 0) {
                $severity = 'BLOCKER'
                $status = 'FAIL'
                $action = 'Fix horizontal overflow (layout width > viewport).'
                $blockers++
            }
            else {
                $passes++
            }

            $findings.Add([pscustomobject]@{
                Severity = $severity
                Viewport = "$($vp.Width)x$($vp.Height)"
                Route = $route
                Status = $status
                Overflow = $overflowPx
                Evidence = "Path=$($data.path) vw=$($data.vw) sw=$($data.sw)"
                Action = $action
            })
        }
    }

    $consoleDump = Invoke-Playwright -CliArgs @('console') -AllowFailure
    if ($consoleDump.Output -match 'Errors:\s*(\d+)') {
        $consoleErrors = [int]$matches[1]
        if ($consoleErrors -gt 0) {
            $warnings++
            $findings.Add([pscustomobject]@{
                Severity = 'WARNING'
                Viewport = '-'
                Route = '-'
                Status = 'WARN'
                Overflow = '-'
                Evidence = "Playwright console reported $consoleErrors error(s)"
                Action = 'Review browser console log for potential frontend/runtime issues.'
            })
        }
    }
}
finally {
    Invoke-Playwright -CliArgs @('close') -AllowFailure | Out-Null
}

$rows = $findings | ForEach-Object {
    "| $($_.Severity) | $($_.Viewport) | $($_.Route) | $($_.Status) | $($_.Overflow) | $($_.Evidence) | $($_.Action) |"
}

$decision = if ($blockers -gt 0) { 'NO-GO' } else { 'GO-CANDIDATE' }

$summary = @"
# Responsive Audit Report

- ProjectRoot: $ProjectRoot
- BaseUrl: $BaseUrl
- GeneratedAt: $($now.ToString('yyyy-MM-dd HH:mm:ss zzz'))

## Summary

| Metric | Count |
|---|---:|
| Blockers | $blockers |
| Warnings | $warnings |
| Pass | $passes |

## Findings

| Severity | Viewport | Route | Status | OverflowPx | Evidence | Action |
|---|---|---|---|---:|---|---|
$($rows -join "`n")

## Decision

- Result: **$decision**
- Rule: Blocker count must be zero.
"@

$utf8NoBom = New-Object System.Text.UTF8Encoding($false)
[System.IO.File]::WriteAllText($OutputPath, $summary, $utf8NoBom)

Write-Host "[responsive-audit] Report: $OutputPath"
Write-Host "[responsive-audit] Blockers=$blockers Warnings=$warnings Pass=$passes"

if ($blockers -gt 0) {
    exit 1
}

exit 0
