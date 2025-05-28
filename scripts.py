import os

def is_page(filename):
    # Skip common backend includes/partials/config files
    lower = filename.lower()
    if lower.startswith('_'):
        return False
    if 'include' in lower or 'partial' in lower or 'config' in lower or 'navbar' in lower or 'footer' in lower or 'connection' in lower:
        return False
    return lower.endswith('.php')

def scan_php_pages(root_dir, ignore_dirs=None):
    if ignore_dirs is None:
        ignore_dirs = {'vendor', 'node_modules', 'uploads', 'assets', 'images', '__pycache__'}
    php_pages = []
    for dirpath, dirnames, filenames in os.walk(root_dir):
        # Modify dirnames in-place to skip ignored folders
        dirnames[:] = [d for d in dirnames if d not in ignore_dirs]
        for filename in filenames:
            if is_page(filename):
                rel_path = os.path.relpath(os.path.join(dirpath, filename), root_dir)
                php_pages.append(rel_path.replace('\\', '/'))  # Normalize path
    return sorted(set(php_pages))

if __name__ == "__main__":
    root = os.getcwd()  # Run this script from your project root
    pages = scan_php_pages(root)
    print("\n--- PHP Web Pages in Project ---")
    for page in pages:
        print(page)
    print(f"\nTotal: {len(pages)} pages found.")
