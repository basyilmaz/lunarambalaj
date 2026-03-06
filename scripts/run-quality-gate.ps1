param(
    [string]$BaseUrl = '',
    [switch]$SkipResponsiveAudit
)

$ErrorActionPreference = 'Stop'

$phpBin = 'C:/xampp/php/php.exe'

if (-not (Test-Path $phpBin)) {
    throw "PHP executable not found at $phpBin"
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
    Invoke-CheckedCommand "php -l $file" { & $phpBin -l $file | Out-Null }
}

if (Test-Path "scripts/check-mojibake.py") {
    Write-Host "==> Mojibake check"
    Invoke-CheckedCommand "python scripts/check-mojibake.py" { python scripts/check-mojibake.py }
}

Write-Host "==> PHPUnit"
Invoke-CheckedCommand "php artisan test" { & $phpBin artisan test }

$responsiveAuditPath = "scripts/run-responsive-audit.ps1"
if (-not $SkipResponsiveAudit -and -not [string]::IsNullOrWhiteSpace($BaseUrl) -and (Test-Path $responsiveAuditPath)) {
    Write-Host "==> Responsive audit ($BaseUrl)"
    Invoke-CheckedCommand "powershell -File $responsiveAuditPath -BaseUrl $BaseUrl" {
        powershell -NoProfile -ExecutionPolicy Bypass -File $responsiveAuditPath -ProjectRoot . -BaseUrl $BaseUrl
    }
}

Write-Host "Quality gate passed."
