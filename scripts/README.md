# Release Scripts

Repo-local release governance scripts:

- `run_prelaunch_audit.ps1`
- `run_release_publish_gate.ps1`
- `run-responsive-audit.ps1`
- `run-psi-report.ps1`

## Prelaunch Audit

Staging:

```powershell
pwsh ./scripts/run_prelaunch_audit.ps1 -ProjectRoot . -BaseUrl http://127.0.0.1:4050 -Target staging
```

Production:

```powershell
pwsh ./scripts/run_prelaunch_audit.ps1 -ProjectRoot . -BaseUrl https://lunarambalaj.com -Target production
```

Behavior:

- Writes report under `docs/release/prelaunch-audit-YYYYMMDD-HHMMSS.md`
- Returns non-zero exit code when `Blockers > 0`

## Release Publish Gate

Dry-run:

```powershell
pwsh ./scripts/run_release_publish_gate.ps1 -ProjectRoot . -Remote origin -MainBranch main
```

Behavior:

- Writes report under `docs/release/release-publish-gate-YYYYMMDD-HHMMSS.md`
- Returns non-zero exit code when `Blockers > 0`
- Push/tag only when `-ExecutePush` is explicitly set

## Responsive Audit

Responsive overflow regression scan (browser-based):

```powershell
pwsh ./scripts/run-responsive-audit.ps1 -ProjectRoot . -BaseUrl http://127.0.0.1:4050
```

Behavior:

- Checks horizontal overflow across critical routes and breakpoints.
- Writes report under `docs/release/responsive-audit-YYYYMMDD-HHMMSS.md`
- Returns non-zero exit code when any overflow blocker exists.

## PSI Report

PageSpeed Insights + Lighthouse fallback report:

```powershell
pwsh ./scripts/run-psi-report.ps1 -ProjectRoot . -Url https://lunarambalaj.com
```

Behavior:

- Writes report under `docs/release/perf-YYYYMMDD-HHMMSS.md`
- Uses PSI API first (if available), falls back to `npx lighthouse` when API fails/rate-limited.
- `-FailOnError` is optional for strict CI failure behavior.
