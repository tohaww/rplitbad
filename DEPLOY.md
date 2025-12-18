# Panduan Automated Deployment

Ada beberapa cara untuk setup automated deployment:

---

## Cara Install Git di Server

### 1. Linux (Ubuntu/Debian)

```bash
# Update package list
sudo apt update

# Install Git
sudo apt install git -y

# Verifikasi instalasi
git --version
```

### 2. Linux (CentOS/RHEL)

```bash
# Install Git
sudo yum install git -y

# Atau untuk CentOS 8+
sudo dnf install git -y

# Verifikasi instalasi
git --version
```

### 3. Shared Hosting (cPanel/DirectAdmin)

**Opsi A: Install via SSH (jika ada akses SSH)**
```bash
# Download dan compile dari source
cd ~
wget https://github.com/git/git/archive/v2.42.0.tar.gz
tar -xzf v2.42.0.tar.gz
cd git-2.42.0
make configure
./configure --prefix=$HOME
make install
```

**Opsi B: Install via cPanel Terminal**
- Login ke cPanel
- Buka **Terminal** atau **SSH Access**
- Jalankan perintah install sesuai OS (lihat Opsi A atau B di atas)

**Opsi C: Cek apakah Git sudah terinstall**
```bash
# Cek apakah Git sudah ada
which git
git --version

# Jika sudah ada, langsung bisa pakai
```

### 4. Windows Server

**Download installer:**
- Download dari: https://git-scm.com/download/win
- Atau install via Chocolatey:
```powershell
choco install git -y
```

### 5. Konfigurasi Git di Server (Setelah Install)

```bash
# Set nama dan email (wajib untuk commit)
git config --global user.name "Server Deploy"
git config --global user.email "deploy@yourdomain.com"

# Set default branch ke main
git config --global init.defaultBranch main

# Verifikasi konfigurasi
git config --list
```

### 6. Setup SSH Key untuk Git (Opsional, untuk private repo)

```bash
# Generate SSH key
ssh-keygen -t ed25519 -C "deploy@yourdomain.com"

# Tampilkan public key (copy ini ke GitHub/GitLab)
cat ~/.ssh/id_ed25519.pub

# Test koneksi ke GitHub
ssh -T git@github.com
```

### 7. Clone Repository ke Server (Pertama Kali)

```bash
# Masuk ke folder yang diinginkan
cd /home/rplitbad

# Clone repository
git clone https://github.com/username/rplitbad.git

# Atau jika pakai SSH
git clone git@github.com:username/rplitbad.git

# Masuk ke folder project
cd rplitbad
```

### Troubleshooting

**Git tidak ditemukan di shared hosting:**
- Cek apakah ada di `/usr/bin/git` atau `/usr/local/bin/git`
- Tambahkan ke PATH: `export PATH=$PATH:/usr/local/bin`
- Atau gunakan full path: `/usr/local/bin/git pull`

**Permission denied:**
```bash
# Set permission untuk folder .git
chmod -R 755 .git
```

**Git sudah terinstall tapi tidak bisa dipanggil:**
```bash
# Cari lokasi Git
which git
whereis git

# Gunakan full path atau tambahkan ke PATH
echo 'export PATH=$PATH:/usr/local/bin' >> ~/.bashrc
source ~/.bashrc
```

---

## Opsi 1: Git + Auto Pull di Server (Paling Mudah)

### Setup di Server:
1. Login ke server via SSH
2. Masuk ke folder project: `cd /home/rplitbad/rplitbad`
3. Buat file `deploy.sh` di server:
```bash
#!/bin/bash
cd /home/rplitbad/rplitbad
git pull origin main
composer install --no-dev --optimize-autoloader
npm install
npm run build
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
chmod -R 775 storage bootstrap/cache
```

4. Set permission: `chmod +x deploy.sh`

### Setup Webhook (Opsional):
- Buat webhook di GitHub/GitLab yang trigger `deploy.sh` setiap ada push
- Atau gunakan GitHub Actions (lihat Opsi 2)

### Cara Pakai:
```bash
# Di lokal, setiap kali update:
git add .
git commit -m "Update fitur X"
git push origin main

# Di server, jalankan:
./deploy.sh
```

---

## Opsi 2: GitHub Actions (CI/CD Otomatis)

### Setup:
1. Buat repository di GitHub
2. Push project ke GitHub
3. Di GitHub, buka Settings → Secrets → Actions
4. Tambahkan secrets:
   - `FTP_SERVER`: alamat FTP server
   - `FTP_USERNAME`: username FTP
   - `FTP_PASSWORD`: password FTP
   - `SSH_HOST`: alamat SSH server (jika ada)
   - `SSH_USERNAME`: username SSH
   - `SSH_PRIVATE_KEY`: private key SSH

5. File `.github/workflows/deploy.yml` sudah dibuat
6. Setiap push ke branch `main` akan otomatis deploy

---

## Opsi 3: Deploy Script Manual (Windows)

### Cara Pakai:
1. Double-click `deploy.bat` di Windows
2. Script akan:
   - Pull dari Git
   - Install dependencies
   - Build assets
   - Clear cache
   - Optimize

### Upload ke Server:
Setelah script selesai, upload file ke server via FTP/File Manager:
- Semua file kecuali: `node_modules`, `vendor`, `.env`, `storage/logs`

---

## Opsi 4: Git + Server Auto Pull (Recommended untuk Shared Hosting)

### Setup:
1. Pastikan Git sudah terinstall di server
2. Di server, buat file `deploy.php` di public folder:
```php
<?php
// deploy.php - Akses via: http://yourdomain.com/deploy.php?key=YOUR_SECRET_KEY

$secretKey = 'YOUR_SECRET_KEY_HERE'; // Ganti dengan key rahasia
$projectPath = '/home/rplitbad/rplitbad';

if ($_GET['key'] !== $secretKey) {
    die('Unauthorized');
}

$output = [];
exec("cd {$projectPath} && git pull origin main 2>&1", $output);
exec("cd {$projectPath} && composer install --no-dev --optimize-autoloader 2>&1", $output);
exec("cd {$projectPath} && php artisan config:clear 2>&1", $output);
exec("cd {$projectPath} && php artisan cache:clear 2>&1", $output);

echo json_encode($output);
```

3. Setup webhook di GitHub:
   - Settings → Webhooks → Add webhook
   - Payload URL: `http://yourdomain.com/deploy.php?key=YOUR_SECRET_KEY`
   - Content type: `application/json`
   - Events: `Just the push event`

4. Setiap push akan otomatis trigger deploy

---

## Rekomendasi

Untuk **shared hosting** tanpa SSH: Gunakan **Opsi 4** (Git + Webhook)
Untuk **VPS/Server dengan SSH**: Gunakan **Opsi 1** (Git + Auto Pull)

---

## Catatan Penting

1. **Jangan commit file `.env`** - sudah di `.gitignore`
2. **File `public/build`** harus di-upload manual atau build di server
3. **Database migration** - jalankan manual jika perlu: `php artisan migrate`
4. **Backup dulu** sebelum deploy pertama kali

