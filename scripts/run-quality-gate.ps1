param(
    [string]$BaseUrl = '',
    [switch]$SkipResponsiveAudit,
    [string]$PhpBin = ''
)

$ErrorActionPreference = 'Stop'

if ([string]::IsNullOrWhiteSpace($PhpBin)) {
    $phpCmd = Get-Command php -ErrorAction SilentlyContinue
    if ($phpCmd) {
        $PhpBin = $phpCmd.Source
    }
    elseif (Test-Path 'C:/xampp/php/php.exe') {
        $PhpBin = 'C:/xampp/php/php.exe'
    }
}

if ([string]::IsNullOrWhiteSpace($PhpBin) -or -not (Test-Path $PhpBin)) {
    throw "PHP executable not found. Provide -PhpBin or install php in PATH."
}

$psCmd = Get-Command pwsh -ErrorAction SilentlyContinue
if (-not $psCmd) {
    $psCmd = Get-Command powershell -ErrorAction SilentlyContinue
}
if (-not $psCmd) {
    throw 'No PowerShell executable found (pwsh/powershell).'
}

function Invoke-CheckedCommand {
    param(
        [string]$Command,
        [scriptblock]$Script
    )

    & $Script
    if ($LASTEXITCODE -ne 0) {
        throw "$Command failed with exit code $LASTEXITCODE"
    }
}

Write-Host "==> PHP syntax check"
$targets = @('app', 'config', 'database', 'routes', 'tests')
$phpFiles = @()
foreach ($target in $targets) {
    if (Test-Path $target) {
        $phpFiles += Get-ChildItem -Path $target -Recurse -Filter '*.php' | Select-Object -ExpandProperty FullName
    }
}

foreach ($file in $phpFiles) {
    Invoke-CheckedCommand "php -l $file" { & $PhpBin -l $file | Out-Null }
}

if (Test-Path "scripts/check-mojibake.py") {
    Write-Host "==> Mojibake check"
    Invoke-CheckedCommand "python scripts/check-mojibake.py" { python scripts/check-mojibake.py }
}

Write-Host "==> PHPUnit"
Invoke-CheckedCommand "php artisan test" { & $PhpBin artisan test }

$responsiveAuditPath = "scripts/run-responsive-audit.ps1"
if (-not $SkipResponsiveAudit -and -not [string]::IsNullOrWhiteSpace($BaseUrl) -and (Test-Path $responsiveAuditPath)) {
    Write-Host "==> Responsive audit ($BaseUrl)"
    Invoke-CheckedCommand "powershell -File $responsiveAuditPath -BaseUrl $BaseUrl" {
        & $psCmd.Source -NoProfile -ExecutionPolicy Bypass -File $responsiveAuditPath -ProjectRoot . -BaseUrl $BaseUrl
    }
}

Write-Host "Quality gate passed."
