import os
import codecs

def check_for_bom(directory):
    bom_files = []
    print(f"Scanning directory: {directory}")
    for root, _, files in os.walk(directory):
        for file in files:
            if file.endswith(('.php', '.blade.php', '.js', '.css', '.json', '.md')):
                file_path = os.path.join(root, file)
                try:
                    with open(file_path, 'rb') as f:
                        raw = f.read(4)
                    if raw.startswith(codecs.BOM_UTF8):
                        bom_files.append(file_path)
                        print(f"BOM found: {file_path}")
                except Exception as e:
                    print(f"Could not read {file_path}: {e}")
    return bom_files

directories_to_check = [
    'c:/YazilimProjeler/lunarambalaj/app',
    'c:/YazilimProjeler/lunarambalaj/resources',
    'c:/YazilimProjeler/lunarambalaj/lang',
    'c:/YazilimProjeler/lunarambalaj/database',
    'c:/YazilimProjeler/lunarambalaj/config',
    'c:/YazilimProjeler/lunarambalaj/routes'
]

total_bom_files = []
for d in directories_to_check:
    if os.path.exists(d):
        total_bom_files.extend(check_for_bom(d))

if total_bom_files:
    print(f"\nFound {len(total_bom_files)} files with BOM.")
    # Uncomment to remove BOM (be careful)
    # for file_path in total_bom_files:
    #     with open(file_path, 'rb') as f:
    #         content = f.read()
    #     content = content[3:]
    #     with open(file_path, 'wb') as f:
    #         f.write(content)
    #     print(f"Removed BOM from: {file_path}")
else:
    print("\nNo files with BOM found.")
