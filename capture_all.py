import subprocess
import time
import os

chrome_path = r"C:\Program Files\Google\Chrome\Application\chrome.exe"
base_url = "http://127.0.0.1:8000"
out_dir = r"C:\xampp\htdocs\lms\screenshots"
os.makedirs(out_dir, exist_ok=True)

targets = [
    ("super_admin.png", f"{base_url}/screenshot-auth/super-admin?redirect=/admin/dashboard"),
    ("admin_cabang.png", f"{base_url}/screenshot-auth/admin-cabang?redirect=/cabang/dashboard"),
    ("trainer_dashboard.png", f"{base_url}/screenshot-auth/trainer?redirect=/trainer/dashboard"),
    ("trainer_classes.png", f"{base_url}/screenshot-auth/trainer?redirect=/trainer/classes"),
    ("trainer_graduations.png", f"{base_url}/screenshot-auth/trainer?redirect=/trainer/graduations"),
    ("peserta_dashboard.png", f"{base_url}/screenshot-auth/peserta?redirect=/dashboard"),
    ("peserta_catalog.png", f"{base_url}/screenshot-auth/peserta?redirect=/peserta/catalog"),
    ("peserta_study.png", f"{base_url}/screenshot-auth/peserta?redirect=/peserta/study"),
    ("peserta_certificates.png", f"{base_url}/screenshot-auth/peserta?redirect=/peserta/certificates"),
    ("news_portal.png", f"{base_url}/news"),
]

for filename, url in targets:
    output_file = os.path.join(out_dir, filename)
    cmd = [
        chrome_path,
        "--headless=new",
        "--no-sandbox",
        "--disable-gpu",
        "--window-size=1280,800",
        f"--screenshot={output_file}",
        url
    ]
    print(f"Capturing {filename} from {url}...")
    subprocess.run(cmd, check=True)
    time.sleep(0.5)

print("All screenshots captured successfully!")
