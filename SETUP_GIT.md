# Setup Git Repository & Automated Deployment

Git sudah terinstall di server (v2.48.2) ✅

## Langkah 1: Setup Git Repository di Lokal

### 1.1. Inisialisasi Git (jika belum)
```bash
git init
git add .
git commit -m "Initial commit"
```

### 1.2. Buat Repository di GitHub/GitLab
1. Login ke GitHub/GitLab
2. Klik "New Repository"
3. Nama: `rplitbad`
4. Jangan centang "Initialize with README"
5. Klik "Create repository"

### 1.3. Connect Lokal ke Remote Repository
```bash
# Ganti dengan URL repository kamu
git remote add origin https://github.com/username/rplitbad.git

# Atau pakai SSH (lebih aman)
git remote add origin git@github.com:username/rplitbad.git

# Push ke GitHub
git branch -M main
git push -u origin main
```

---

## Langkah 2: Setup Git di Server

### 2.1. Konfigurasi Git di Server
```bash
# Login ke server via SSH
ssh rplitbad@trust

# Konfigurasi Git
git config --global user.name "Server Deploy"
git config --global user.email "deploy@rplitb-ad.my.id"
```

### 2.2. Clone Repository ke Server
```bash
# Masuk ke folder parent
cd /home/rplitbad

# Clone repository (ganti dengan URL kamu)
git clone https://github.com/username/rplitbad.git

# Atau jika folder sudah ada, masuk ke folder dan pull
cd rplitbad
git remote add origin https://github.com/username/rplitbad.git
git pull origin main
```

### 2.3. Setup Deploy Script di Server
```bash
# Masuk ke folder project
cd /home/rplitbad/rplitbad

# Buat file deploy.sh
nano deploy.sh
```

**Isi file `deploy.sh`:**
```bash
#!/bin/bash

echo "🚀 Starting deployment..."

# Pull latest changes
echo "📥 Pulling latest changes..."
git pull origin main

# Install dependencies
echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader

# Build assets (jika npm tersedia)
if command -v npm &> /dev/null; then
    echo "🔨 Building assets..."
    npm install
    npm run build
fi

# Clear cache
echo "🧹 Clearing cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Optimize
echo "⚡ Optimizing..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
echo "🔐 Setting permissions..."
chmod -R 775 storage bootstrap/cache

echo "✅ Deployment completed!"
```

**Set permission:**
```bash
chmod +x deploy.sh
```

---

## Langkah 3: Setup Automated Deployment

### Opsi A: Manual Deploy (Paling Sederhana)

**Di lokal:**
```bash
git add .
git commit -m "Update fitur X"
git push origin main
```

**Di server:**
```bash
cd /home/rplitbad/rplitbad
./deploy.sh
```

### Opsi B: Auto Deploy via Webhook (Recommended)

**1. Buat file `deploy.php` di server (folder `public`):**
```bash
cd /home/rplitbad/rplitbad/public
nano deploy.php
```

**Isi file `deploy.php`:**
```php
<?php
// deploy.php - Akses via: http://app.rplitb-ad.my.id/deploy.php?key=YOUR_SECRET_KEY

$secretKey = 'ubah_dengan_key_rahasia_kamu'; // GANTI INI!
$projectPath = '/home/rplitbad/rplitbad';

// Cek secret key
if (!isset($_GET['key']) || $_GET['key'] !== $secretKey) {
    http_response_code(401);
    die('Unauthorized');
}

// Log deployment
$logFile = $projectPath . '/storage/logs/deploy.log';
file_put_contents($logFile, date('Y-m-d H:i:s') . " - Deployment started\n", FILE_APPEND);

// Execute deploy script
$output = [];
$returnVar = 0;
exec("cd {$projectPath} && bash deploy.sh 2>&1", $output, $returnVar);

// Log hasil
file_put_contents($logFile, date('Y-m-d H:i:s') . " - Deployment completed (exit code: {$returnVar})\n", FILE_APPEND);
file_put_contents($logFile, "Output: " . implode("\n", $output) . "\n\n", FILE_APPEND);

// Response
header('Content-Type: application/json');
echo json_encode([
    'status' => $returnVar === 0 ? 'success' : 'error',
    'exit_code' => $returnVar,
    'output' => $output,
    'timestamp' => date('Y-m-d H:i:s')
]);
```

**2. Set permission:**
```bash
chmod 644 deploy.php
```

**3. Setup Webhook di GitHub:**
- Buka repository di GitHub
- Settings → Webhooks → Add webhook
- **Payload URL**: `http://app.rplitb-ad.my.id/deploy.php?key=ubah_dengan_key_rahasia_kamu`
- **Content type**: `application/json`
- **Events**: Pilih "Just the push event"
- Klik "Add webhook"

**4. Test webhook:**
- Push perubahan ke GitHub
- Cek di GitHub → Settings → Webhooks → Recent Deliveries
- Atau akses langsung: `http://app.rplitb-ad.my.id/deploy.php?key=ubah_dengan_key_rahasia_kamu`

---

## Langkah 4: Setup File .env di Server

**PENTING:** File `.env` tidak boleh di-commit ke Git!

```bash
# Di server, buat file .env
cd /home/rplitbad/rplitbad
cp .env.example .env

# Edit file .env
nano .env

# Set konfigurasi:
# APP_URL=http://app.rplitb-ad.my.id
# DB_* (sesuaikan dengan database hosting)
# dll...

# Generate app key
php artisan key:generate
```

---

## Langkah 5: Setup Database & Migration

```bash
# Di server
cd /home/rplitbad/rplitbad

# Run migration
php artisan migrate

# Run seeder (jika perlu)
php artisan db:seed
```

---

## Workflow Deployment

### Setiap kali update:

**1. Di lokal (Windows):**
```bash
# Edit file
# ... buat perubahan ...

# Commit dan push
git add .
git commit -m "Deskripsi perubahan"
git push origin main
```

**2. Deploy otomatis:**
- Jika pakai webhook: Otomatis deploy setelah push
- Jika manual: Login ke server dan jalankan `./deploy.sh`

**3. Cek hasil:**
- Buka `http://app.rplitb-ad.my.id`
- Cek log: `tail -f storage/logs/deploy.log`

---

## Troubleshooting

### Git pull error: "Permission denied"
```bash
chmod -R 755 .git
chown -R rplitbad:rplitbad .git
```

### Composer tidak ditemukan
```bash
# Install composer di server
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### NPM tidak ditemukan
- Build di lokal, lalu commit folder `public/build`
- Atau install Node.js di server

### Deploy script tidak bisa dijalankan
```bash
chmod +x deploy.sh
bash deploy.sh
```

### Webhook tidak jalan
- Cek permission file `deploy.php`
- Cek log di `storage/logs/deploy.log`
- Test manual: akses URL webhook di browser

---

## Keamanan

1. **Jangan commit `.env`** - sudah di `.gitignore`
2. **Ganti secret key** di `deploy.php` dengan key yang kuat
3. **Hapus `deploy.php`** setelah testing, atau proteksi dengan IP whitelist
4. **Backup database** sebelum deploy besar

---

## Next Steps

1. ✅ Git sudah terinstall
2. ⏭️ Setup repository di GitHub
3. ⏭️ Clone ke server
4. ⏭️ Setup deploy script
5. ⏭️ Setup webhook
6. ⏭️ Test deployment

