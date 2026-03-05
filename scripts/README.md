# Release Scripts

Repo-local release governance scripts:

- `run_prelaunch_audit.ps1`
- `run_release_publish_gate.ps1`

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

