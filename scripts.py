import os

# Path where you want to generate the files (set to your project root)
PROJECT_PATH = "COS10026-WebTechnologyProject"

# List of missing essential PHP files
missing_files = [
    "setup.php",
    "connection.php",
    "register.php",
    "logout.php",
    "process_enquiry.php",
    "process_joinus.php",
    "process_login.php",
    "process_registration.php",
    "profile.php",
    "edit_profile.php",
    "upload.php",
    "admin_dashboard.php",
    "admin_view_enquiries.php",
    "admin_view_members.php",
    "error.php",
    "functions.php"
]

# PHP file boilerplate content
php_template = """<?php
// {filename}
// Auto-generated placeholder. Fill with logic as needed.
?>"""

# Make sure the project directory exists
os.makedirs(PROJECT_PATH, exist_ok=True)

# Generate each missing file with a header comment
for filename in missing_files:
    file_path = os.path.join(PROJECT_PATH, filename)
    if not os.path.exists(file_path):
        with open(file_path, "w", encoding="utf-8") as f:
            f.write(php_template.format(filename=filename))
        print(f"Created: {file_path}")
    else:
        print(f"Already exists: {file_path}")

print("✅ All missing essential PHP files generated.")
