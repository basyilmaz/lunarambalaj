param(
    [string]$ProjectRoot = (Get-Location).Path,
    [switch]$ExpectSeeded,
    [string]$PhpBin = ''
)

Set-StrictMode -Version Latest
$ErrorActionPreference = 'Stop'

if (-not (Test-Path (Join-Path $ProjectRoot 'artisan'))) {
    throw "Invalid ProjectRoot: artisan not found at $ProjectRoot"
}

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

Push-Location $ProjectRoot
try {
    $args = @('artisan', 'ops:db-health', '--json')
    if ($ExpectSeeded) {
        $args += '--expect-seeded'
    }

    $output = & $PhpBin @args 2>&1 | Out-String
    $exitCode = $LASTEXITCODE

    Write-Host $output.Trim()

    if ($exitCode -ne 0) {
        Write-Host "[db-health] FAILED (ExitCode=$exitCode)"
        exit $exitCode
    }

    Write-Host '[db-health] PASSED'
}
finally {
    Pop-Location
}

exit 0
