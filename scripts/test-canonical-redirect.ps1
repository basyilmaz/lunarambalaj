param(
    [string]$SecondaryBaseUrl = 'https://lunarambalaj.com.tr',
    [string]$PrimaryBaseUrl = 'https://lunarambalaj.com',
    [string[]]$Paths = @('/kvkk', '/en/privacy-policy'),
    [switch]$IncludeQueryStringCheck
)

Set-StrictMode -Version Latest
$ErrorActionPreference = 'Stop'

function Get-RedirectResponse {
    param([string]$Url)

    try {
        $response = Invoke-WebRequest -Uri $Url -MaximumRedirection 0 -UseBasicParsing -TimeoutSec 20
        return [pscustomobject]@{
            StatusCode = [int]$response.StatusCode
            Location = if ($response.Headers['Location']) { [string]$response.Headers['Location'] } else { '' }
            Error = ''
        }
    }
    catch {
        $hasResponse = $null -ne $_.Exception -and $_.Exception.PSObject.Properties.Name -contains 'Response' -and $null -ne $_.Exception.Response
        if ($hasResponse) {
            $resp = $_.Exception.Response
            return [pscustomobject]@{
                StatusCode = [int]$resp.StatusCode
                Location = if ($resp.Headers['Location']) { [string]$resp.Headers['Location'] } else { '' }
                Error = $_.Exception.Message
            }
        }

        return [pscustomobject]@{
            StatusCode = 0
            Location = ''
            Error = $_.Exception.Message
        }
    }
}

$failures = 0

foreach ($path in $Paths) {
    $source = $SecondaryBaseUrl.TrimEnd('/') + $path
    $target = $PrimaryBaseUrl.TrimEnd('/') + $path
    $result = Get-RedirectResponse -Url $source

    $ok = ($result.StatusCode -eq 301) -and ($result.Location -eq $target -or $result.Location -eq $path)
    if ($ok) {
        Write-Host "[PASS] $source -> status=$($result.StatusCode) location=$($result.Location)"
    }
    else {
        $failures++
        Write-Host "[FAIL] $source -> status=$($result.StatusCode) location=$($result.Location) error=$($result.Error)"
        Write-Host "       expected: 301 and location '$target' (or '$path' when same-host rewrite is fronted)."
    }
}

if ($IncludeQueryStringCheck) {
    $path = '/kvkk?x=1'
    $source = $SecondaryBaseUrl.TrimEnd('/') + $path
    $target = $PrimaryBaseUrl.TrimEnd('/') + $path
    $result = Get-RedirectResponse -Url $source

    $ok = ($result.StatusCode -eq 301) -and ($result.Location -like '*x=1*')
    if ($ok) {
        Write-Host "[PASS] query string preserved: $source -> $($result.Location)"
    }
    else {
        $failures++
        Write-Host "[FAIL] query string preserved: $source -> status=$($result.StatusCode) location=$($result.Location)"
        Write-Host "       expected: 301 and location containing 'x=1' (ideal target: $target)."
    }
}

if ($failures -gt 0) {
    Write-Host "[redirect-test] Failures=$failures"
    exit 1
}

Write-Host '[redirect-test] All checks passed.'
exit 0
