from pathlib import Path
import sys


ROOT = Path(__file__).resolve().parents[1]
TARGET_DIRS = ["app", "database", "resources", "routes", "config", "lang"]
SUFFIXES = {".php", ".blade.php", ".js", ".ts", ".json", ".md", ".txt", ".css"}

# Common mojibake fragments seen when UTF-8 text is decoded with single-byte encodings.
PATTERNS = [
    "Ã",
    "Ä±",
    "ÄŸ",
    "ÅŸ",
    "Ã¼",
    "Ã¶",
    "Ã§",
    "â€™",
    "â€“",
    "â€œ",
    "â€",
]


def should_scan(path: Path) -> bool:
    if path.name.endswith(".blade.php"):
        return True
    return path.suffix.lower() in SUFFIXES


def main() -> int:
    findings: list[tuple[Path, str]] = []

    for directory in TARGET_DIRS:
        base = ROOT / directory
        if not base.exists():
            continue
        for path in base.rglob("*"):
            if not path.is_file() or not should_scan(path):
                continue
            try:
                content = path.read_text(encoding="utf-8")
            except UnicodeDecodeError:
                findings.append((path, "utf-8 decode error"))
                continue

            for pattern in PATTERNS:
                if pattern in content:
                    findings.append((path, f"pattern `{pattern}` found"))
                    break

    if findings:
        print("Mojibake/encoding issues detected:")
        for path, reason in findings:
            print(f"- {path.relative_to(ROOT)}: {reason}")
        return 1

    print("No mojibake patterns detected.")
    return 0


if __name__ == "__main__":
    sys.exit(main())
