param(
    [string]$ProjectRoot = (Get-Location).Path,
    [string]$Url = 'https://lunarambalaj.com',
    [string[]]$Strategies = @('mobile', 'desktop'),
    [string]$ApiKey = '',
    [string]$OutputPath = '',
    [switch]$FailOnError
)

Set-StrictMode -Version Latest
$ErrorActionPreference = 'Stop'

if (-not (Test-Path (Join-Path $ProjectRoot 'docs\release'))) {
    New-Item -ItemType Directory -Path (Join-Path $ProjectRoot 'docs\release') -Force | Out-Null
}

$allowed = @('mobile', 'desktop')
foreach ($strategy in $Strategies) {
    if ($allowed -notcontains $strategy) {
        throw "Invalid strategy '$strategy'. Allowed: mobile, desktop"
    }
}

if ([string]::IsNullOrWhiteSpace($ApiKey) -and $env:PSI_API_KEY) {
    $ApiKey = $env:PSI_API_KEY
}

$now = Get-Date
if ([string]::IsNullOrWhiteSpace($OutputPath)) {
    $stamp = $now.ToString('yyyyMMdd-HHmmss')
    $OutputPath = Join-Path $ProjectRoot "docs\release\perf-$stamp.md"
}

function Normalize-Score {
    param($Score)
    if ($null -eq $Score) { return '-' }
    return [int][Math]::Round([double]$Score * 100)
}

function Normalize-Text {
    param([string]$Value)

    if ([string]::IsNullOrWhiteSpace($Value)) {
        return '-'
    }

    $normalized = $Value.Replace([char]0x00A0, ' ')
    $normalized = $normalized -replace '\s+', ' '
    return $normalized.Trim()
}

function Get-AuditDisplay {
    param(
        [hashtable]$Audits,
        [string]$Key
    )

    if (-not $Audits.ContainsKey($Key)) {
        return [pscustomobject]@{ Score = '-'; Display = '-' }
    }

    $audit = $Audits[$Key]
    $score = Normalize-Score -Score $audit.score
    $display = Normalize-Text -Value ([string]$audit.displayValue)
    return [pscustomobject]@{ Score = $score; Display = $display }
}

function Parse-LighthousePayload {
    param($Payload)

    $lhr = $Payload.lighthouseResult
    if (-not $lhr) {
        throw 'Missing lighthouseResult in payload.'
    }

    $audits = @{}
    foreach ($prop in $lhr.audits.PSObject.Properties) {
        $audits[$prop.Name] = $prop.Value
    }

    $perfScore = Normalize-Score -Score $lhr.categories.performance.score

    $metrics = [pscustomobject]@{
        Score = $perfScore
        FCP = (Get-AuditDisplay -Audits $audits -Key 'first-contentful-paint').Display
        LCP = (Get-AuditDisplay -Audits $audits -Key 'largest-contentful-paint').Display
        SpeedIndex = (Get-AuditDisplay -Audits $audits -Key 'speed-index').Display
        TBT = (Get-AuditDisplay -Audits $audits -Key 'total-blocking-time').Display
        CLS = (Get-AuditDisplay -Audits $audits -Key 'cumulative-layout-shift').Display
        TTI = (Get-AuditDisplay -Audits $audits -Key 'interactive').Display
    }

    $diagnosticMap = @(
        @{ Key = 'redirects'; Label = 'Avoid multiple page redirects' },
        @{ Key = 'network-dependency-tree'; Label = 'Network dependency tree' },
        @{ Key = 'forced-reflow'; Label = 'Forced reflow' },
        @{ Key = 'server-response-time'; Label = 'Document request latency' },
        @{ Key = 'uses-long-cache-ttl'; Label = 'Use efficient cache lifetimes' },
        @{ Key = 'uses-optimized-images'; Label = 'Improve image delivery' },
        @{ Key = 'interactive'; Label = 'Time to Interactive' },
        @{ Key = 'largest-contentful-paint-element'; Label = 'LCP breakdown' }
    )

    $diagnostics = @()
    foreach ($item in $diagnosticMap) {
        $audit = Get-AuditDisplay -Audits $audits -Key $item.Key
        $diagnostics += [pscustomobject]@{
            Key = $item.Key
            Label = $item.Label
            Score = $audit.Score
            Display = $audit.Display
        }
    }

    return [pscustomobject]@{
        Metrics = $metrics
        Diagnostics = $diagnostics
    }
}

function Get-PsiFromApi {
    param(
        [string]$TargetUrl,
        [string]$Strategy,
        [string]$Key
    )

    $encodedUrl = [Uri]::EscapeDataString($TargetUrl)
    $uri = "https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=$encodedUrl&strategy=$Strategy&category=PERFORMANCE"
    if (-not [string]::IsNullOrWhiteSpace($Key)) {
        $uri += "&key=$([Uri]::EscapeDataString($Key))"
    }

    $response = Invoke-RestMethod -Uri $uri -Method Get -TimeoutSec 180
    $parsed = Parse-LighthousePayload -Payload $response

    return [pscustomobject]@{
        Source = 'pagespeed-api'
        Parsed = $parsed
        Error = ''
    }
}

function Get-PsiFromLighthouse {
    param(
        [string]$TargetUrl,
        [string]$Strategy
    )

    if (-not (Get-Command npx -ErrorAction SilentlyContinue)) {
        return [pscustomobject]@{
            Source = 'lighthouse-cli'
            Parsed = $null
            Error = 'npx not found for lighthouse fallback.'
        }
    }

    $tmp = Join-Path $env:TEMP ("psi-$Strategy-" + [guid]::NewGuid().ToString('N') + '.json')
    $cmd = @(
        '--yes', 'lighthouse', $TargetUrl,
        '--only-categories=performance',
        "--emulated-form-factor=$Strategy",
        '--quiet',
        '--chrome-flags=--headless --no-sandbox',
        '--output=json',
        "--output-path=$tmp"
    )

    & npx @cmd *> $null
    $code = $LASTEXITCODE
    if ($code -ne 0 -or -not (Test-Path $tmp)) {
        return [pscustomobject]@{
            Source = 'lighthouse-cli'
            Parsed = $null
            Error = "lighthouse fallback failed with exit code $code."
        }
    }

    try {
        $json = Get-Content $tmp -Raw -Encoding UTF8 | ConvertFrom-Json
        $wrap = [pscustomobject]@{ lighthouseResult = $json }
        $parsed = Parse-LighthousePayload -Payload $wrap
        return [pscustomobject]@{
            Source = 'lighthouse-cli'
            Parsed = $parsed
            Error = ''
        }
    }
    finally {
        Remove-Item $tmp -ErrorAction SilentlyContinue -Force
    }
}

$results = New-Object System.Collections.Generic.List[object]
$hardFailures = 0

foreach ($strategy in $Strategies) {
    $apiResult = $null
    $errorText = ''

    try {
        $apiResult = Get-PsiFromApi -TargetUrl $Url -Strategy $strategy -Key $ApiKey
    }
    catch {
        $statusCode = ''
        try {
            if ($_.Exception.Response.StatusCode) {
                $statusCode = [int]$_.Exception.Response.StatusCode
            }
        }
        catch {
            $statusCode = ''
        }

        if ([string]::IsNullOrWhiteSpace($statusCode)) {
            $errorText = 'API unavailable.'
        }
        else {
            $errorText = "API unavailable (HTTP $statusCode)."
        }
    }

    if ($apiResult -and $apiResult.Parsed) {
        $results.Add([pscustomobject]@{
            Strategy = $strategy
            Source = $apiResult.Source
            Status = 'OK'
            Error = ''
            Metrics = $apiResult.Parsed.Metrics
            Diagnostics = $apiResult.Parsed.Diagnostics
        })
        continue
    }

    $fallback = Get-PsiFromLighthouse -TargetUrl $Url -Strategy $strategy
    if ($fallback.Parsed) {
        $results.Add([pscustomobject]@{
            Strategy = $strategy
            Source = $fallback.Source
            Status = 'OK'
            Error = if ([string]::IsNullOrWhiteSpace($errorText)) { 'API unavailable, fallback used.' } else { (Normalize-Text -Value $errorText) }
            Metrics = $fallback.Parsed.Metrics
            Diagnostics = $fallback.Parsed.Diagnostics
        })
        continue
    }

    $hardFailures++
    $results.Add([pscustomobject]@{
        Strategy = $strategy
        Source = 'unavailable'
        Status = 'FAIL'
        Error = if ([string]::IsNullOrWhiteSpace($errorText)) { (Normalize-Text -Value $fallback.Error) } else { (Normalize-Text -Value "$errorText | $($fallback.Error)") }
        Metrics = [pscustomobject]@{
            Score = '-'; FCP = '-'; LCP = '-'; SpeedIndex = '-'; TBT = '-'; CLS = '-'; TTI = '-'
        }
        Diagnostics = @()
    })
}

$summaryRows = $results | ForEach-Object {
    "| $($_.Strategy) | $($_.Source) | $($_.Status) | $($_.Metrics.Score) | $($_.Metrics.FCP) | $($_.Metrics.LCP) | $($_.Metrics.SpeedIndex) | $($_.Metrics.TBT) | $($_.Metrics.CLS) | $($_.Metrics.TTI) |"
}

$diagSections = $results | ForEach-Object {
    $diagRows = if ($_.Diagnostics.Count -gt 0) {
        ($_.Diagnostics | ForEach-Object { "| $($_.Label) | $($_.Score) | $($_.Display) |" }) -join "`n"
    }
    else {
        '| (no data) | - | - |'
    }

    $errorLine = if ([string]::IsNullOrWhiteSpace($_.Error)) { '' } else { "`n- Note: $($_.Error)`n" }
@"
### $($_.Strategy.ToUpperInvariant()) Diagnostics
$errorLine
| Diagnostic | Score | Display |
|---|---:|---|
$diagRows
"@
}

$decision = if ($hardFailures -gt 0) { 'PARTIAL' } else { 'OK' }

$report = @"
# PSI Performance Report

- URL: $Url
- GeneratedAt: $($now.ToString('yyyy-MM-dd HH:mm:ss zzz'))
- Decision: **$decision**

## Summary

| Strategy | Source | Status | Score | FCP | LCP | Speed Index | TBT | CLS | TTI |
|---|---|---|---:|---|---|---|---|---|---|
$($summaryRows -join "`n")

## Details

$($diagSections -join "`n`n")
"@

$utf8NoBom = New-Object System.Text.UTF8Encoding($false)
[System.IO.File]::WriteAllText($OutputPath, $report, $utf8NoBom)

Write-Host "[psi-report] Report: $OutputPath"
Write-Host "[psi-report] Failures=$hardFailures Strategies=$($Strategies.Count)"

if ($hardFailures -gt 0 -and $FailOnError) {
    exit 1
}

exit 0
