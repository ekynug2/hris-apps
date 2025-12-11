# PANDUAN DEPLOY KE CPANEL (Tanpa Terminal)

## LANGKAH 1: PERSIAPAN LOKAL

```bash
# Di komputer lokal, jalankan:
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan icons:cache
php artisan filament:cache-components
```

## LANGKAH 2: BUAT DATABASE DI CPANEL

1. Login ke cPanel
2. Buka **MySQL® Databases**
3. Buat database baru (contoh: `username_hris`)
4. Buat user baru dengan password kuat
5. Assign user ke database dengan **ALL PRIVILEGES**

## LANGKAH 3: EDIT .ENV

Copy `.env.example` ke `.env` dan edit:

```env
APP_NAME="HRIS Apps"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com
APP_TIMEZONE=Asia/Jakarta

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=username_hris
DB_USERNAME=username_dbuser
DB_PASSWORD=your_password

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=sync
```

## LANGKAH 4: UPLOAD FILES

Upload via **File Manager** atau **FTP**:

### Yang harus di-upload:
- ✅ Semua folder dan file
- ✅ Folder `vendor/` (sudah di-install di lokal)

### Yang TIDAK perlu di-upload:
- ❌ `.git/`
- ❌ `node_modules/`
- ❌ `.env.example` (setelah copy jadi `.env`)

### Struktur di cPanel:
```
public_html/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
│   ├── index.php
│   ├── install.php  ← Web installer
│   └── ...
├── resources/
├── routes/
├── storage/
├── vendor/
├── .env
└── ...
```

**PENTING:** Jika domain langsung point ke `public_html`, pindahkan isi folder `public/` ke `public_html/` dan sesuaikan path di `index.php`.

## LANGKAH 5: JALANKAN WEB INSTALLER

1. Buka browser: `https://yourdomain.com/install.php?key=hris-install-2025`
2. Ikuti langkah-langkah:
   - **Cek Requirements** - Pastikan semua ✓
   - **Fix Permissions** - Set folder writable
   - **Generate App Key** - Buat APP_KEY
   - **Run Migrations** - Buat tabel database
   - **Storage Link** - Link storage
   - **Optimize Cache** - Cache untuk production

## LANGKAH 6: FIX PERMISSIONS (Manual via File Manager)

Jika permission error:
1. Buka File Manager
2. Klik kanan folder `storage/` → Change Permissions → 755 (recursive)
3. Klik kanan folder `bootstrap/cache/` → Change Permissions → 755

## LANGKAH 7: SETUP STORAGE SYMLINK (Manual)

Jika symlink tidak bekerja:
1. Buka File Manager
2. Copy semua isi dari `storage/app/public/` 
3. Paste ke `public/storage/`

## LANGKAH 8: TEST APLIKASI

1. Buka `https://yourdomain.com/hris`
2. Login dengan user admin
3. Test semua fitur

## LANGKAH 9: HAPUS INSTALLER

**PENTING SEKALI!** Hapus file ini setelah selesai:
- `public/install.php`

## LANGKAH 10: SETUP ADMS (ZKTeco)

Update perangkat ZKTeco dengan URL baru:
```
https://yourdomain.com/iclock/cdata
```

---

## TROUBLESHOOTING

### Error 500
- Cek `.env` sudah benar
- Cek permissions storage & bootstrap/cache

### Blank Page
- Aktifkan `APP_DEBUG=true` sementara untuk melihat error
- Cek PHP version di cPanel (harus ≥ 8.2)

### Storage Error
- Pastikan `public/storage` link ke `storage/app/public`

### Database Error
- Pastikan kredensial database benar
- Pastikan user punya ALL PRIVILEGES

---

## PHP VERSION DI CPANEL

Pastikan PHP MultiPHP Manager set ke **PHP 8.2** atau lebih tinggi.
Extensions yang diperlukan:
- pdo_mysql
- fileinfo
- openssl
- mbstring
- tokenizer
- xml
- ctype
- bcmath

