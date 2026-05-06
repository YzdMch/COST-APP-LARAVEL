# 🖥️ Geeko Komputer — Sistem Servis & Estimasi Biaya

Aplikasi manajemen servis komputer dengan fitur estimasi biaya transparan, booking online, tracking status perbaikan real-time, dan cloud storage untuk foto progres.

**Tech Stack:** Laravel 12 · Blade · Tailwind CSS · Alpine.js · MySQL · Cloudinary

---

## ⚡ Quick Start

### Prasyarat

| Tool | Versi Minimum | Download |
|------|---------------|----------|
| PHP | 8.2+ | [php.net](https://www.php.net/) |
| Composer | 2.x | [getcomposer.org](https://getcomposer.org/) |
| Node.js | 18+ | [nodejs.org](https://nodejs.org/) |
| MySQL | 5.7+ / MariaDB 10.3+ | [mysql.com](https://dev.mysql.com/downloads/) |

> 💡 **Pakai XAMPP?** PHP dan MySQL sudah termasuk. Install Node.js dan Composer terpisah.

### Setup

```bash
# 1. Clone & install
git clone <url-repo> COST-APP-LARAVEL
cd COST-APP-LARAVEL
composer install
npm install

# 2. Environment
cp .env.example .env
php artisan key:generate

# 3. Database — buat database di MySQL/phpMyAdmin:
#    CREATE DATABASE cost_db_laravel;

# 4. Sesuaikan .env (lihat bagian Konfigurasi di bawah)

# 5. Migrate + seed
php artisan migrate --seed

# 6. Storage link (untuk foto lokal)
php artisan storage:link

# 7. Build assets
npm run build

# 8. Jalankan
php artisan serve
```

Buka **http://localhost:8000** 🎉

---

## 🔑 Akun Demo

| Role | Email | Password |
|------|-------|----------|
| Pelanggan | `pelanggan@geeko.com` | `123456` |
| Teknisi | `teknisi@geeko.com` | `123456` |

---

## 🔄 Alur Bisnis Aplikasi

```
┌─────────────┐     ┌─────────────┐     ┌──────────────┐     ┌─────────────┐
│  PELANGGAN  │     │   SISTEM    │     │   TEKNISI    │     │  PELANGGAN  │
│             │     │             │     │              │     │             │
│ 1. Cek      │────▶│ Tampilkan   │     │              │     │             │
│    Estimasi  │     │ kisaran     │     │              │     │             │
│    (publik)  │     │ harga       │     │              │     │             │
│             │     │             │     │              │     │             │
│ 2. Register │────▶│ Buat akun   │     │              │     │             │
│    / Login   │     │ pelanggan   │     │              │     │             │
│             │     │             │     │              │     │             │
│ 3. Booking  │────▶│ Generate    │────▶│ 4. Terima    │     │             │
│    servis    │     │ nomor tiket │     │    servis    │     │             │
│             │     │ (GK-xxxx)   │     │              │     │             │
│             │     │             │     │ 5. Update    │────▶│ 6. Lihat    │
│             │     │             │     │    status    │     │    progres  │
│             │     │             │     │    + foto    │     │    real-time│
│             │     │             │     │              │     │             │
│             │     │             │     │ 7. Konfirmasi│────▶│ 8. Lihat    │
│             │     │             │     │    harga     │     │    harga    │
│             │     │             │     │    final     │     │    final    │
│             │     │             │     │              │     │             │
│             │     │             │     │ 9. Selesai   │────▶│ 10. Ambil   │
│             │     │             │     │              │     │    perangkat│
└─────────────┘     └─────────────┘     └──────────────┘     └─────────────┘
```

### Status Lifecycle

```
Diterima → Sedang dicek → Perbaikan → Testing → Selesai
```

- Status hanya bisa **maju ke depan** (tidak bisa mundur)
- Setiap perubahan status **wajib** disertai catatan tindakan
- Foto bukti progres bisa dilampirkan (disimpan di Cloudinary)
- Pelanggan bisa melihat semua update di halaman Detail Servis

---

## 🌐 Halaman & Fitur

### Publik (Tanpa Login)

| URL | Fitur |
|-----|-------|
| `/` | Landing page — layanan, keunggulan, CTA, kontak |
| `/estimasi` | Cek estimasi biaya instan (pilih perangkat + kerusakan) |

### Pelanggan

| URL | Fitur |
|-----|-------|
| `/dashboard` | Statistik booking, progress tracker servis aktif, riwayat servis |
| `/booking` | Form booking servis (isi data diri + deskripsi keluhan) |
| `/servis/{id}` | Detail servis: info perangkat, timeline update, foto bukti |

### Teknisi

| URL | Fitur |
|-----|-------|
| `/dashboard` | Statistik (total/selesai/proses/revenue), filter status, search, aktivitas terakhir |
| `/servis/{id}` | Detail servis + timeline (sama seperti pelanggan) |
| `/servis/{id}/status` | Update status (forward-only) + catatan + foto |
| `/servis/{id}/harga` | Update harga final + alasan perubahan |

### Auth

| URL | Fitur |
|-----|-------|
| `/login` | Login (split-screen, password toggle, demo quick-fill) |
| `/register` | Register (otomatis jadi pelanggan) |

---

## ☁️ Cloud Storage (Cloudinary)

Foto progres servis disimpan di **Cloudinary** (free tier: 25GB).

### Konfigurasi di `.env`

```env
CLOUDINARY_URL=cloudinary://API_KEY:API_SECRET@CLOUD_NAME
```

> Dapatkan credentials di [cloudinary.com](https://cloudinary.com) → Dashboard → **Root** API key.

### Command

```bash
# Migrasi foto lama dari local ke cloud
php artisan photos:migrate-cloud
```

---

## 📁 Struktur Project

```
app/
├── Console/Commands/
│   └── MigratePhotosToCloud    # Migrasi foto local → Cloudinary
├── Http/Controllers/
│   ├── Auth/                   # Login, Register (Laravel Breeze)
│   ├── BookingController       # Form booking + store
│   ├── DashboardController     # Dashboard (auto-detect role, stats)
│   ├── EstimasiController      # Estimasi harga AJAX
│   ├── PageController          # Landing page
│   ├── ServisController        # Detail servis + update harga
│   └── StatusController        # Update status (forward-only)
├── Http/Middleware/
│   └── RoleMiddleware          # Proteksi route by role
└── Models/
    ├── User                    # + role, no_telepon
    ├── EstimasiHarga           # Tabel referensi harga
    ├── Servis                  # Data booking/servis
    └── ServisLog               # Log status + foto (accessor foto_url)

database/
├── migrations/                 # Schema tabel
└── seeders/                    # Data demo (estimasi + users)

resources/views/
├── layouts/
│   ├── app.blade.php           # Layout utama (navbar glassmorphism + footer 4 kolom)
│   ├── guest.blade.php         # Layout auth (split-screen)
│   └── navigation.blade.php   # Navbar responsive
├── auth/                       # Login, register (modern split-screen)
├── estimasi/                   # Card-based selection + instant result
├── booking/                    # Form data diri + estimasi summary
├── dashboard/
│   ├── pelanggan.blade.php     # Progress tracker + riwayat card
│   └── teknisi.blade.php      # Stats, filter, search, 2 modal (status + harga)
├── servis/
│   └── show.blade.php          # Progress bar + timeline foto + image preview
└── welcome.blade.php           # Landing page (7 section modern)
```

---

## 🛠️ Command Berguna

```bash
php artisan serve                  # Jalankan server
php artisan migrate:fresh --seed   # Reset database
php artisan storage:link           # Symlink storage (sekali saja)
php artisan photos:migrate-cloud   # Pindah foto ke Cloudinary
php artisan optimize:clear         # Clear semua cache
npm run build                      # Build CSS/JS untuk production
npm run dev                        # Dev mode (auto-rebuild)
php artisan route:list             # Lihat semua route
```

---

## 🔧 Catatan XAMPP

1. Start **Apache** dan **MySQL** di XAMPP Control Panel
2. Buka **phpMyAdmin** → buat database `cost_db_laravel`
3. Edit `.env`:
   ```
   DB_PASSWORD=          ← kosong (default XAMPP)
   ```
4. Jalankan `php artisan serve` (bukan via Apache)

---

## 👥 Tim Developer

- **YZ** — Full Stack Development
- **KZ** — AI & Architecture
- **FR** — Deployment & DevOps
