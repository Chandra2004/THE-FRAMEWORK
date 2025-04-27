# THE-FRAMEWORK - MVC Native PHP Framework

## 📌 Pengenalan

**THE-FRAMEWORK** adalah framework PHP berbasis MVC (Model-View-Controller) yang dibuat oleh **Chandra Tri A**. Framework ini dirancang untuk memberi struktur yang bersih dan terorganisir pada aplikasi PHP, dengan fitur-fitur utama:

- Manajemen namespace dinamis PSR‑4
- Blade Templating
- Migrasi dan seeding database
- Artisan CLI untuk scaffolding dan manajemen proyek
- Support folder `resources/Views` dan fallback ke `services/`
- Upload file terstruktur di folder `private-uploads/`

## 🚀 Instalasi

### Langkah-langkah

1. **Clone Proyek**:
   ```bash
   git clone https://github.com/Chandra2004/THE-FRAMEWORK.git
   cd THE-FRAMEWORK
   ```

2. **Install Dependensi**:
   ```bash
   composer install
   ```

3. **Setup Proyek**:
   ```bash
   php artisan setup
   ```
   - Perintah ini akan membuat `.env`, memperbarui namespace, menjalankan migrasi, dan generate key enkripsi.
   - Sesuaikan `DB_*` di `.env` jika dibutuhkan sebelum menjalankan.

4. **Jalankan Server**:
   ```bash
   php artisan serve
   ```
   Akses di `http://localhost:8080`.

### Persyaratan
- PHP 8.0+
- Composer
- MySQL (atau kompatibel)

## 📂 Struktur Direktori

```
THE-FRAMEWORK/
├── app/                               # Folder utama aplikasi
│   ├── App/                           # Berisi file inti framework
│   │   ├── Blueprint.php             # Blueprint untuk migrasi database
│   │   ├── CacheManager.php          # Pengelola cache
│   │   ├── Config.php                # File konfigurasi utama
│   │   ├── Database.php              # Koneksi dan query database menggunakan PDO
│   │   ├── ImageOptimizer.php        # Optimasi gambar
│   │   ├── Logging.php               # Logging menggunakan Monolog
│   │   ├── RateLimiter.php           # Pembatasan jumlah request
│   │   ├── Router.php                # Pengaturan routing aplikasi
│   │   ├── Schema.php                # Fungsionalitas untuk migrasi dan pengelolaan tabel
│   │   ├── SessionManager.php        # Pengelolaan sesi yang aman
│   │   └── View.php                  # Pengelolaan tampilan dan Blade templating
│   ├── Database/                     # Berisi file migrasi database
│   │   └── Migration.php             # Dasar dari migrasi, digunakan untuk membuat tabel
│   ├── Helpers/                      # Fungsi pembantu untuk berbagai kegunaan
│   │   ├── Helper.php                # Helper utama
│   │   └── helpers.php               # Fungsi tambahan untuk akses cepat
│   ├── Http/                         # Folder untuk HTTP-related files
│   │   └── Controllers/              # Controller aplikasi yang menangani logika bisnis
│   │       ├── ErrorController.php   # Controller untuk menangani error
│   │       ├── HomeController.php    # Controller utama untuk halaman beranda
│   │       └── ProductController.php # Controller untuk halaman produk
│   ├── Middleware/                   # Middleware untuk kontrol alur request
│   │   ├── AuthMiddleware.php        # Middleware untuk autentikasi pengguna
│   │   ├── CsrfMiddleware.php        # Middleware untuk perlindungan CSRF
│   │   ├── Middleware.php            # Interface umum untuk middleware
│   │   ├── ValidationMiddleware.php  # Middleware untuk validasi input
│   │   └── WAFMiddleware.php         # Middleware untuk keamanan web aplikasi
│   ├── Models/                       # Berisi model yang berinteraksi dengan database
│   │   ├── Seeders/                  # Seeder untuk pengisian data awal ke database
│   │   │   └── UserSeeder.php        # Seeder untuk mengisi data pengguna
│   │   └── HomeModel.php             # Model untuk mengelola data pengguna dan halaman utama
│   ├── Storage/                      # Penyimpanan file sementara, cache, dan log
│   │   ├── cache/                    # Folder untuk cache
│   │   └── logs/                     # Folder untuk log aplikasi
│   └── BladeInit.php                 # Inisialisasi Blade templating engine
├── database/                         # Folder untuk migrasi dan seeder database
│   ├── migrations/                   # Berisi file migrasi untuk struktur tabel database
│   │   └── UsersTable.php            # Contoh migrasi untuk tabel pengguna
│   └── seeders/                      # Seeder untuk data awal
│       └── UserSeeder.php            # Seeder untuk mengisi data pengguna
├── htdocs/                           # Public folder untuk akses HTTP
│   ├── .htaccess                     # File konfigurasi .htaccess untuk Apache
│   ├── file.php                      # File PHP untuk pengelolaan file
│   └── index.php                     # Entry point aplikasi
├── private-uploads/                  # Folder untuk upload file yang tidak dapat diakses langsung
│   ├── dummy/                        # Folder untuk file dummy atau placeholder
│   └── user-pictures/                # Folder untuk gambar profil pengguna
├── resources/                        # Folder untuk file sumber daya
│   ├── css/                          # File CSS untuk styling
│   ├── js/                           # File JavaScript untuk interaksi front-end
│   └── Views/                        # Folder untuk view Blade (.blade.php)
│       └── (Views Blade di sini)      # Tampilan halaman aplikasi menggunakan Blade
├── services/                         # Folder untuk file layanan aplikasi
│   └── error/                        # Folder untuk tampilan error
│       ├── 404.blade.php             # Halaman 404 not found
│       ├── 500.blade.php             # Halaman 500 internal server error
│       ├── maintenance.blade.php     # Halaman pemeliharaan
│       └── payment.blade.php         # Halaman pembayaran
├── vendor/                           # Folder untuk dependensi composer
├── .env                              # File konfigurasi environment untuk pengaturan database dan API key
├── .env.example                      # Contoh file .env untuk setup awal
├── .gitignore                        # File konfigurasi Git untuk mengabaikan file tertentu
├── artisan                           # File untuk menjalankan perintah Artisan
├── composer.json                     # File konfigurasi Composer untuk manajemen dependensi
├── composer.lock                     # File lock untuk dependensi Composer
└── README.md                         # Dokumentasi proyek (README)

```

## 🔧 Perintah Artisan

- **Setup proyek**        : `php artisan setup`
- **Jalankan server**     : `php artisan serve`
- **Migrasi database**    :
  - `php artisan migrate`
  - `php artisan migrate:fresh`
  - `php artisan rollback`
- **Seeding data**        :
  - `php artisan seed`
  - `php artisan seed --class=ClassName`
- **Scaffold file**       :
  - `php artisan make:controller NameController`
  - `php artisan make:model NameModel`
  - `php artisan make:seeder NameSeeder`
  - `php artisan make:migration CreateNameTable`
  - `php artisan make:middleware NameMiddleware`

> Semua file yang dihasilkan akan menggunakan namespace sesuai PSR‑4 di `composer.json`.

## 🌐 Konfigurasi ENV

Sesuaikan file `.env`:
```ini
BASE_URL=http://localhost:8080
DB_HOST=127.0.0.1
DB_NAME=your_database
DB_USER=your_user
DB_PASS=your_password
ENCRYPTION_KEY=generated_key_here
```

## 🤝 Kontribusi

Kami terbuka untuk kontribusi! Silakan buat pull request atau hubungi:

- WhatsApp: 085730676143
- Email   : chandratriantomo123@gmail.com
- Website : https://www.kiwkiw-native.free.nf