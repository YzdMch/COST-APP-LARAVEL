# 🖥️ Geeko Komputer — Sistem Servis & Estimasi Biaya

Aplikasi manajemen servis komputer dengan fitur estimasi biaya transparan, booking online, dan tracking status perbaikan.

**Tech Stack:** Laravel 12 · Blade · Tailwind CSS · Alpine.js · MySQL

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

# 4. Sesuaikan .env jika perlu (lihat komentar di .env.example)

# 5. Migrate + seed
php artisan migrate --seed

# 6. Build assets
npm run build

# 7. Jalankan
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

## 🌐 Fitur & Halaman

| URL | Fitur | Akses |
|-----|-------|-------|
| `/` | Landing page | Semua |
| `/estimasi` | Form estimasi biaya otomatis | Semua |
| `/login` | Login | Guest |
| `/register` | Register (otomatis jadi pelanggan) | Guest |
| `/dashboard` | Dashboard — auto-detect role | Login |
| `/booking` | Booking servis | Pelanggan |
| `/servis/{id}` | Detail + timeline status | Login |
| `/servis/{id}/edit` | Edit data servis | Teknisi |

---

## 📁 Struktur Project

```
app/
├── Http/Controllers/
│   ├── Auth/                   # Login, Register (Laravel Breeze)
│   ├── BookingController       # Booking servis
│   ├── DashboardController     # Dashboard (auto-detect role)
│   ├── EstimasiController      # Form estimasi + AJAX harga
│   ├── PageController          # Landing page
│   ├── ServisController        # CRUD servis
│   └── StatusController        # Update status servis
├── Http/Middleware/
│   └── RoleMiddleware          # Proteksi route by role
└── Models/
    ├── User                    # + role, no_telepon
    ├── EstimasiHarga           # Tabel harga referensi
    ├── Servis                  # Data booking/servis
    └── ServisLog               # Log perubahan status

database/
├── migrations/                 # Schema tabel
└── seeders/                    # Data demo (estimasi + users)

resources/views/
├── layouts/                    # Layout + navigasi
├── auth/                       # Login, register
├── estimasi/                   # Form estimasi
├── booking/                    # Konfirmasi booking
├── dashboard/                  # Pelanggan & teknisi
└── servis/                     # Detail & edit
```

---

## 🛠️ Command Berguna

```bash
php artisan migrate:fresh --seed   # Reset database
php artisan optimize:clear         # Clear semua cache
npm run build                      # Build ulang CSS/JS
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
