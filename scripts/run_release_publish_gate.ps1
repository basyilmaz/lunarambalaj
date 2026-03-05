param(
    [string]$ProjectRoot = (Get-Location).Path,
    [string]$Remote = 'origin',
    [string]$MainBranch = 'main',
    [string]$ReleaseTag = '',
    [string]$ReleaseNotesPath = '',
    [switch]$SkipQualityGate,
    [switch]$ExecutePush,
    [switch]$SkipTag
)

Set-StrictMode -Version Latest
$ErrorActionPreference = 'Stop'

$results = New-Object System.Collections.Generic.List[object]

function Add-Result {
    param(
        [string]$Severity,
        [string]$Check,
        [string]$Status,
        [string]$Evidence,
        [string]$Action
    )

    $results.Add([pscustomobject]@{
        Severity = $Severity
        Check = $Check
        Status = $Status
        Evidence = $Evidence
        Action = $Action
    })
}

function Run-Git {
    param([string[]]$GitArgs)

    $prevEap = $ErrorActionPreference
    $ErrorActionPreference = 'Continue'
    try {
        $output = & git @GitArgs 2>&1
        $exitCode = $LASTEXITCODE
    }
    finally {
        $ErrorActionPreference = $prevEap
    }

    return [pscustomobject]@{
        ExitCode = $exitCode
        Output = ($output -join "`n")
    }
}

Push-Location $ProjectRoot
try {
    $gitVersion = Run-Git -GitArgs @('--version')
    if ($gitVersion.ExitCode -ne 0) {
        Add-Result -Severity 'BLOCKER' -Check 'git available' -Status 'FAIL' -Evidence $gitVersion.Output -Action 'Install/configure git.'
    }
    else {
        Add-Result -Severity 'PASS' -Check 'git available' -Status 'OK' -Evidence $gitVersion.Output -Action ''
    }

    $isRepo = $false
    if ($gitVersion.ExitCode -eq 0) {
        $repoRes = Run-Git -GitArgs @('rev-parse', '--is-inside-work-tree')
        if ($repoRes.ExitCode -eq 0 -and $repoRes.Output.Trim().ToLower() -eq 'true') {
            $isRepo = $true
            Add-Result -Severity 'PASS' -Check 'git repository' -Status 'OK' -Evidence $ProjectRoot -Action ''
        }
        else {
            Add-Result -Severity 'BLOCKER' -Check 'git repository' -Status 'FAIL' -Evidence $repoRes.Output -Action 'Run inside a valid git repository.'
        }
    }

    $branch = ''

    if ($isRepo) {
        $branchRes = Run-Git -GitArgs @('rev-parse', '--abbrev-ref', 'HEAD')
        $branch = $branchRes.Output.Trim()
        if ($branchRes.ExitCode -ne 0 -or $branch -eq 'HEAD' -or [string]::IsNullOrWhiteSpace($branch)) {
            Add-Result -Severity 'BLOCKER' -Check 'current branch' -Status 'FAIL' -Evidence $branchRes.Output -Action 'Checkout a named release branch.'
        }
        else {
            Add-Result -Severity 'PASS' -Check 'current branch' -Status 'OK' -Evidence $branch -Action ''
        }

        $statusRes = Run-Git -GitArgs @('status', '--porcelain')
        $dirty = -not [string]::IsNullOrWhiteSpace($statusRes.Output)
        if ($dirty) {
            $sample = ($statusRes.Output -split "`n" | Select-Object -First 10) -join '; '
            Add-Result -Severity 'BLOCKER' -Check 'clean working tree' -Status 'FAIL' -Evidence $sample -Action 'Commit/stash changes before publish.'
        }
        else {
            Add-Result -Severity 'PASS' -Check 'clean working tree' -Status 'OK' -Evidence 'No uncommitted changes' -Action ''
        }

        $fetchRes = Run-Git -GitArgs @('fetch', $Remote, '--prune')
        if ($fetchRes.ExitCode -ne 0) {
            Add-Result -Severity 'WARNING' -Check 'git fetch' -Status 'WARN' -Evidence $fetchRes.Output -Action 'Verify remote connectivity.'
        }
        else {
            Add-Result -Severity 'PASS' -Check 'git fetch' -Status 'OK' -Evidence "$Remote fetched" -Action ''
        }

        if (-not [string]::IsNullOrWhiteSpace($branch) -and $branch -ne 'HEAD') {
            $aheadBehind = Run-Git -GitArgs @('rev-list', '--left-right', '--count', "$Remote/$MainBranch...HEAD")
            if ($aheadBehind.ExitCode -eq 0) {
                $parts = $aheadBehind.Output.Trim().Split("`t")
                if ($parts.Count -eq 2) {
                    $behind = [int]$parts[0]
                    $ahead = [int]$parts[1]
                    Add-Result -Severity 'PASS' -Check 'ahead/behind main' -Status 'OK' -Evidence "ahead=$ahead behind=$behind" -Action ''
                    if ($behind -gt 0) {
                        Add-Result -Severity 'WARNING' -Check 'branch up-to-date' -Status 'WARN' -Evidence "behind=$behind" -Action "Rebase/merge $Remote/$MainBranch before release."
                    }
                }
            }
            else {
                Add-Result -Severity 'WARNING' -Check 'ahead/behind main' -Status 'WARN' -Evidence $aheadBehind.Output -Action 'Verify baseline branch exists on remote.'
            }
        }
    }

    if ([string]::IsNullOrWhiteSpace($ReleaseNotesPath)) {
        $notesDate = Get-Date -Format 'yyyyMMdd'
        $ReleaseNotesPath = Join-Path $ProjectRoot "docs\release\release-notes-$notesDate.md"
    }

    if (-not (Test-Path $ReleaseNotesPath)) {
        Add-Result -Severity 'BLOCKER' -Check 'release notes file' -Status 'FAIL' -Evidence $ReleaseNotesPath -Action 'Create/update release notes before publish.'
    }
    else {
        Add-Result -Severity 'PASS' -Check 'release notes file' -Status 'OK' -Evidence $ReleaseNotesPath -Action ''
    }

    if (-not $SkipQualityGate) {
        $qualityPath = Join-Path $ProjectRoot 'scripts\run-quality-gate.ps1'
        if (-not (Test-Path $qualityPath)) {
            Add-Result -Severity 'BLOCKER' -Check 'quality gate script' -Status 'FAIL' -Evidence 'scripts/run-quality-gate.ps1 missing' -Action 'Add mandatory quality gate script.'
        }
        else {
            & powershell -NoProfile -ExecutionPolicy Bypass -File $qualityPath *> $null
            $qCode = $LASTEXITCODE
            if ($qCode -ne 0) {
                Add-Result -Severity 'BLOCKER' -Check 'quality gate result' -Status 'FAIL' -Evidence "ExitCode=$qCode" -Action 'Fix failing tests and rerun.'
            }
            else {
                Add-Result -Severity 'PASS' -Check 'quality gate result' -Status 'OK' -Evidence 'Passed' -Action ''
            }
        }
    }
    else {
        Add-Result -Severity 'WARNING' -Check 'quality gate result' -Status 'WARN' -Evidence 'Skipped by parameter' -Action 'Use skip only with explicit approval.'
    }

    $blockersNow = @($results | Where-Object { $_.Severity -eq 'BLOCKER' }).Count
    if ($ExecutePush -and $blockersNow -eq 0 -and $isRepo) {
        $pushRes = Run-Git -GitArgs @('push', $Remote, 'HEAD')
        if ($pushRes.ExitCode -ne 0) {
            Add-Result -Severity 'BLOCKER' -Check 'push branch' -Status 'FAIL' -Evidence $pushRes.Output -Action 'Resolve push conflict/permission issues.'
        }
        else {
            Add-Result -Severity 'PASS' -Check 'push branch' -Status 'OK' -Evidence "Pushed branch $branch to $Remote" -Action ''
        }

        if (-not $SkipTag) {
            if ([string]::IsNullOrWhiteSpace($ReleaseTag)) {
                $ReleaseTag = 'v' + (Get-Date -Format 'yyyy.MM.dd-HHmm')
            }

            $tagExistsRes = Run-Git -GitArgs @('tag', '--list', $ReleaseTag)
            if ($tagExistsRes.Output.Trim() -eq $ReleaseTag) {
                Add-Result -Severity 'BLOCKER' -Check 'create tag' -Status 'FAIL' -Evidence "Tag $ReleaseTag already exists" -Action 'Use a unique tag value.'
            }
            else {
                $tagCreateRes = Run-Git -GitArgs @('tag', '-a', $ReleaseTag, '-m', "Release $ReleaseTag")
                if ($tagCreateRes.ExitCode -ne 0) {
                    Add-Result -Severity 'BLOCKER' -Check 'create tag' -Status 'FAIL' -Evidence $tagCreateRes.Output -Action 'Fix tag creation error.'
                }
                else {
                    Add-Result -Severity 'PASS' -Check 'create tag' -Status 'OK' -Evidence $ReleaseTag -Action ''
                    $tagPushRes = Run-Git -GitArgs @('push', $Remote, $ReleaseTag)
                    if ($tagPushRes.ExitCode -ne 0) {
                        Add-Result -Severity 'BLOCKER' -Check 'push tag' -Status 'FAIL' -Evidence $tagPushRes.Output -Action 'Fix remote tag push issue.'
                    }
                    else {
                        Add-Result -Severity 'PASS' -Check 'push tag' -Status 'OK' -Evidence "Pushed tag $ReleaseTag" -Action ''
                    }
                }
            }
        }
        else {
            Add-Result -Severity 'WARNING' -Check 'tag push' -Status 'WARN' -Evidence 'Skipped by parameter' -Action 'Use only if tag policy permits.'
        }
    }
    elseif ($ExecutePush -and $blockersNow -gt 0) {
        Add-Result -Severity 'BLOCKER' -Check 'publish gate' -Status 'FAIL' -Evidence "Blockers=$blockersNow" -Action 'Resolve blockers before push.'
    }
    else {
        Add-Result -Severity 'PASS' -Check 'publish mode' -Status 'OK' -Evidence 'Dry-run only, no push executed' -Action ''
    }
}
finally {
    Pop-Location
}

$blockers = @($results | Where-Object { $_.Severity -eq 'BLOCKER' }).Count
$warnings = @($results | Where-Object { $_.Severity -eq 'WARNING' }).Count
$passes = @($results | Where-Object { $_.Severity -eq 'PASS' }).Count

$stamp = Get-Date -Format 'yyyyMMdd-HHmmss'
$reportPath = Join-Path $ProjectRoot "docs\release\release-publish-gate-$stamp.md"
$reportDir = Split-Path -Parent $reportPath
if (-not (Test-Path $reportDir)) {
    New-Item -Path $reportDir -ItemType Directory -Force | Out-Null
}

$lines = New-Object System.Collections.Generic.List[string]
$lines.Add('# Release Publish Gate Report')
$lines.Add('')
$lines.Add("- ProjectRoot: $ProjectRoot")
$lines.Add("- Remote: $Remote")
$lines.Add("- MainBranch: $MainBranch")
$lines.Add("- ExecutePush: $ExecutePush")
$lines.Add("- GeneratedAt: $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss zzz')")
$lines.Add('')
$lines.Add('## Summary')
$lines.Add('')
$lines.Add('| Metric | Count |')
$lines.Add('|---|---:|')
$lines.Add("| Blockers | $blockers |")
$lines.Add("| Warnings | $warnings |")
$lines.Add("| Pass | $passes |")
$lines.Add('')
$lines.Add('## Findings')
$lines.Add('')
$lines.Add('| Severity | Check | Status | Evidence | Action |')
$lines.Add('|---|---|---|---|---|')

$ordered = $results | Sort-Object @{Expression={ if ($_.Severity -eq 'BLOCKER') {0} elseif ($_.Severity -eq 'WARNING') {1} else {2} }}, Check
foreach ($item in $ordered) {
    $evidence = ($item.Evidence -replace '\|','/').Trim()
    $action = ($item.Action -replace '\|','/').Trim()
    $lines.Add("| $($item.Severity) | $($item.Check) | $($item.Status) | $evidence | $action |")
}

$decision = if ($blockers -eq 0) { 'GO-CANDIDATE' } else { 'NO-GO' }
$lines.Add('')
$lines.Add('## Decision')
$lines.Add('')
$lines.Add("- Result: **$decision**")
$lines.Add('- Rule: Blocker count must be zero.')

$enc = New-Object System.Text.UTF8Encoding($false)
[System.IO.File]::WriteAllLines($reportPath, $lines, $enc)

Write-Host "[release-gate] Report: $reportPath"
Write-Host "[release-gate] Blockers=$blockers Warnings=$warnings Pass=$passes"

if ($blockers -gt 0) {
    exit 1
}

exit 0