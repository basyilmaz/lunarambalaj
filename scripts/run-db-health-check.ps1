param(
    [string]$ProjectRoot = (Get-Location).Path,
    [switch]$ExpectSeeded,
    [string]$PhpBin = '',
    [string]$DbConnection = '',
    [string]$DbDatabase = '',
    [string]$DbHost = '',
    [string]$DbPort = '',
    [string]$DbUsername = '',
    [string]$DbPassword = ''
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
    $envBackup = @{}
    $envOverrides = @{}

    if (-not [string]::IsNullOrWhiteSpace($DbConnection)) { $envOverrides['DB_CONNECTION'] = $DbConnection }
    if (-not [string]::IsNullOrWhiteSpace($DbDatabase)) { $envOverrides['DB_DATABASE'] = $DbDatabase }
    if (-not [string]::IsNullOrWhiteSpace($DbHost)) { $envOverrides['DB_HOST'] = $DbHost }
    if (-not [string]::IsNullOrWhiteSpace($DbPort)) { $envOverrides['DB_PORT'] = $DbPort }
    if (-not [string]::IsNullOrWhiteSpace($DbUsername)) { $envOverrides['DB_USERNAME'] = $DbUsername }
    if (-not [string]::IsNullOrWhiteSpace($DbPassword)) { $envOverrides['DB_PASSWORD'] = $DbPassword }

    foreach ($entry in $envOverrides.GetEnumerator()) {
        $envName = [string] $entry.Key
        $newValue = [string] $entry.Value
        $envBackup[$envName] = [Environment]::GetEnvironmentVariable($envName, 'Process')
        [Environment]::SetEnvironmentVariable($envName, $newValue, 'Process')
    }

    $args = @('artisan', 'ops:db-health', '--json')
    if ($ExpectSeeded) {
        $args += '--expect-seeded'
    }

    try {
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
        foreach ($entry in $envBackup.GetEnumerator()) {
            $envName = [string] $entry.Key
            $oldValue = $entry.Value
            [Environment]::SetEnvironmentVariable($envName, $oldValue, 'Process')
        }
    }
}
finally {
    Pop-Location
}

exit 0
