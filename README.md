# 🖥️ Geeko Komputer — Sistem Servis & Estimasi Biaya

Aplikasi manajemen servis komputer dengan fitur estimasi biaya transparan, booking online, tracking status perbaikan real-time, manajemen multi-cabang, SLA tracker, cetak invoice, dan cloud storage untuk foto progres.

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

> 💡 **Pakai XAMPP / Laragon?** PHP dan MySQL sudah termasuk. Project ini sepenuhnya kompatibel dengan *local environment* biasa. Install Node.js dan Composer secara terpisah.

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

# 4. Sesuaikan .env (Terutama bagian DB & Cloudinary)

# 5. Migrate + seed
php artisan migrate:fresh --seed

# 6. Storage link (untuk foto lokal)
php artisan storage:link

# 7. Build assets
npm run build

# 8. Jalankan (Buka 2 Terminal)
# Terminal 1: Backend
php artisan serve
# Terminal 2: Frontend (jika butuh auto-rebuild / development)
npm run dev
```

Buka **http://localhost:8000** 🎉

---

## 🔑 Akun Demo

Setelah menjalankan seeder (`php artisan migrate:fresh --seed`), Anda dapat login menggunakan akun berikut:

| Role | Email | Password | Akses / Keterangan |
|------|-------|----------|--------------------|
| **Admin** | `admin@geeko.com` | `123456` | Akses penuh panel admin, cabang, SLA, audit log, penugasan teknisi. |
| **Teknisi** | `teknisi@geeko.com` | `123456` | Update status servis, upload foto progres, tambah item invoice. |
| **Pelanggan** | `pelanggan@geeko.com` | `123456` | Booking servis, cek estimasi, cek riwayat, print invoice. |

---

## 🌟 Fitur Unggulan

1. **Estimasi & Booking Flow**: Pelanggan dapat mengecek estimasi harga tanpa login, lalu otomatis diarahkan ke form booking dengan data yang telah tersimpan.
2. **Manajemen Multi-Cabang & Penugasan**: Admin dapat mendistribusikan tiket servis berdasarkan cabang. Fitur **Auto-Assign** secara cerdas menugaskan tiket ke teknisi yang paling sedikit beban kerjanya.
3. **SLA Tracker (Service Level Agreement)**: Pemantauan otomatis terhadap target waktu penyelesaian servis berdasarkan prioritas. Sistem memberikan alert jika ada servis yang *Overdue*.
4. **Status Forward-Only**: Alur perbaikan satu arah (Diterima → Sedang dicek → Perbaikan → Testing → Selesai) dilengkapi kewajiban upload bukti progres foto.
5. **Manajemen Invoice & Cetak Struk**: Pembuatan rincian biaya dinamis per item (sparepart/jasa) dan fitur cetak/print invoice (struk) PDF.
6. **Audit & Activity Log**: Perekaman aktivitas secara mendetail di sisi admin untuk mencegah manipulasi data.

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
│             │     │             │     │ 7. Buat/Edit │────▶│ 8. Lihat &  │
│             │     │             │     │    Invoice   │     │    Cetak    │
│             │     │             │     │    Item      │     │    Invoice  │
│             │     │             │     │              │     │             │
│             │     │             │     │ 9. Selesai   │────▶│ 10. Ambil   │
│             │     │             │     │              │     │    perangkat│
└─────────────┘     └─────────────┘     └──────────────┘     └─────────────┘
```

- **Pembatalan Booking**: Pelanggan dapat membatalkan booking hanya jika status servis masih **Diterima**.

---

## 🌐 Halaman & Hak Akses

### Publik (Tanpa Login)
| URL | Fitur |
|-----|-------|
| `/` | Landing page — layanan, keunggulan, CTA, kontak |
| `/estimasi` | Cek estimasi biaya instan (pilih perangkat + kerusakan) |

### Pelanggan
| URL | Fitur |
|-----|-------|
| `/dashboard` | Statistik booking, progress tracker servis aktif, riwayat servis |
| `/booking` | Form booking servis otomatis terintegrasi dari halaman estimasi |
| `/servis/{id}` | Detail timeline update, foto bukti, status, dan pembatalan tiket |
| `/servis/{id}/invoice`| Halaman cetak/print invoice tagihan final |

### Teknisi
| URL | Fitur |
|-----|-------|
| `/dashboard` | Statistik (total/selesai/proses/revenue) untuk cabang teknisi ybs |
| `/servis/{id}` | Detail timeline update |
| `/servis/{id}/status` | Update progres servis (Catatan & Foto Bukti) |
| `/servis/{id}/invoice/edit`| Tambah/Hapus rincian item tagihan ke pelanggan |

### Admin Panel (`/admin/*`)
| URL | Fitur |
|-----|-------|
| `/dashboard` | Statistik global dan alert SLA (Overdue tracking) |
| `/penugasan` | Manajemen tiket: Assign manual atau fitur **Auto-Assign** |
| `/cabang` | CRUD data kantor cabang |
| `/estimasi` | CRUD master data harga estimasi layanan |
| `/sla` | Konfigurasi target waktu penyelesaian (Low/Medium/High) |
| `/users` | Kelola user, aktif/nonaktif akun, reset password |
| `/audit` | Monitor aktivitas login dan manipulasi data sistem |

---

## ☁️ Cloud Storage (Cloudinary)

Foto progres servis dapat disimpan secara cloud menggunakan **Cloudinary**.

### Konfigurasi di `.env`
```env
CLOUDINARY_URL=cloudinary://API_KEY:API_SECRET@CLOUD_NAME
```
> Dapatkan credentials di [cloudinary.com](https://cloudinary.com) → Dashboard → **Root** API key.

### Command Migrasi
Jika Anda ingin memindahkan foto yang sebelumnya tersimpan di lokal (storage disk) ke cloud:
```bash
php artisan photos:migrate-cloud
```

---

## 📁 Struktur Project

Pola arsitektur menggunakan **MVC (Model-View-Controller)** yang disediakan Laravel.

```
app/
├── Http/Controllers/
│   ├── Admin/                  # Controller spesifik Admin Panel (Cabang, SLA, Audit, dll)
│   ├── Auth/                   # Login, Register (Laravel Breeze)
│   ├── BookingController       # Form booking & store tiket
│   ├── InvoiceController       # Pengelolaan item invoice (Teknisi)
│   ├── StatusController        # Update log perbaikan & upload foto (Teknisi)
│   └── ...
├── Http/Middleware/
│   └── RoleMiddleware          # Proteksi route per akses role (Admin/Teknisi/Pelanggan)
└── Models/
    ├── Cabang, SlaConfig, ActivityLog # Model baru untuk Admin
    ├── EstimasiHarga           # Tabel referensi harga awal
    ├── Servis & ServisLog      # Data tiket dan tracking status
    └── InvoiceItem             # Rincian item per tagihan
...
```

---

## 🛠️ Command Berguna

```bash
php artisan optimize:clear         # Clear semua cache (Gunakan jika error tak terduga)
php artisan storage:link           # Symlink storage (wajib dijalankan sekali)
php artisan route:list             # Lihat semua route terdaftar
npm run build                      # Build aset frontend (Tailwind) untuk production
```

---

## 👥 Tim Developer

- **YZ** — Full Stack Development
- **KZ** — AI & Architecture
- **FR** — Deployment & DevOps
