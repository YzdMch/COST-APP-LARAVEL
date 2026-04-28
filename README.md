# 🖥️ Geeko Komputer — Sistem Servis & Estimasi Biaya

Aplikasi manajemen servis komputer dengan fitur estimasi biaya transparan, booking online, dan tracking status perbaikan.

**Tech Stack:** Laravel 12 · Blade · Tailwind CSS · Alpine.js · MySQL

---

## ⚡ Quick Start (Semua OS)

### Prasyarat

| Tool | Versi Minimum | Cek |
|------|---------------|-----|
| PHP | 8.2+ | `php -v` |
| Composer | 2.x | `composer -V` |
| Node.js | 18+ | `node -v` |
| MySQL | 5.7+ / MariaDB 10.3+ | `mysql --version` |

> 💡 **Pakai XAMPP?** Semua sudah termasuk kecuali Node.js dan Composer. Install keduanya terpisah.

---

### 1. Clone & Install Dependencies

```bash
git clone <url-repo> COST-APP-LARAVEL
cd COST-APP-LARAVEL

# Install PHP dependencies
composer install

# Install JS/CSS dependencies
npm install
```

### 2. Setup Environment

```bash
# Copy config
cp .env.example .env

# Generate app key
php artisan key:generate
```

### 3. Setup Database

Buat database baru di MySQL/phpMyAdmin:

```sql
CREATE DATABASE cost_db_laravel;
```

Atau via terminal:
```bash
mysql -u root -p -e "CREATE DATABASE cost_db_laravel;"
```

> ⚙️ **Sesuaikan `.env`** jika username/password MySQL berbeda:
> ```
> DB_USERNAME=root
> DB_PASSWORD=         ← kosong untuk XAMPP default
> ```

### 4. Jalankan Migration & Seeder

```bash
php artisan migrate --seed
```

Ini akan membuat semua tabel dan data demo (25 data estimasi harga + 2 akun demo).

### 5. Build Assets (Tailwind CSS)

```bash
npm run build
```

### 6. Jalankan Server

```bash
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

## 📁 Struktur Project

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/                  # Login, Register (Breeze)
│   │   ├── BookingController      # Booking servis
│   │   ├── DashboardController    # Dashboard (auto-detect role)
│   │   ├── EstimasiController     # Form estimasi + AJAX harga
│   │   ├── PageController         # Landing page
│   │   ├── ServisController       # CRUD servis (teknisi)
│   │   └── StatusController       # Update status (teknisi)
│   └── Middleware/
│       └── RoleMiddleware         # Proteksi route by role
├── Models/
│   ├── User                       # + role, no_telepon
│   ├── EstimasiHarga              # Tabel estimasi harga
│   ├── Servis                     # Data servis/booking
│   └── ServisLog                  # Log perubahan status
│
database/
├── migrations/                    # Schema tabel
└── seeders/                       # Data demo
│
resources/views/
├── layouts/                       # app.blade.php, navigation
├── auth/                          # login, register
├── estimasi/                      # Form estimasi biaya
├── booking/                       # Konfirmasi booking
├── dashboard/                     # Pelanggan & teknisi view
└── servis/                        # Detail & edit servis
```

---

## 🌐 Halaman & Fitur

| URL | Fitur | Akses |
|-----|-------|-------|
| `/` | Landing page (hero, layanan, kontak) | Semua |
| `/estimasi` | Form estimasi biaya otomatis | Semua |
| `/login` | Login | Guest |
| `/register` | Register (otomatis jadi pelanggan) | Guest |
| `/dashboard` | Dashboard — auto-detect role | Login |
| `/booking` | Konfirmasi booking servis | Pelanggan |
| `/servis/{id}` | Detail + timeline status | Login |
| `/servis/{id}/edit` | Edit data servis | Teknisi |

---

## 🔧 Catatan untuk XAMPP

1. Pastikan **Apache** dan **MySQL** sudah running di XAMPP Control Panel
2. Buka **phpMyAdmin** → buat database `cost_db_laravel`
3. Di `.env`, pastikan:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=cost_db_laravel
   DB_USERNAME=root
   DB_PASSWORD=
   ```
4. **Node.js** harus diinstall terpisah dari [nodejs.org](https://nodejs.org/)
5. **Composer** harus diinstall terpisah dari [getcomposer.org](https://getcomposer.org/)
6. Jalankan `php artisan serve` (jangan pakai Apache virtual host untuk development)

---

## 👥 Tim Developer

- **YZ** — Full Stack Development
- **KZ** — AI & Architecture
- **FR** — Deployment & DevOps
