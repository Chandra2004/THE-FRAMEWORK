# THE-FRAMEWORK - MVC Native PHP Framework

## 📌 Pengenalan

**THE-FRAMEWORK** adalah framework PHP berbasis MVC (Model-View-Controller) yang dibuat oleh **Chandra Tri A**. Framework ini dirancang untuk memberi struktur yang bersih dan terorganisir pada aplikasi PHP, dengan fitur-fitur utama:

* Manajemen namespace dinamis PSR‑4
* Blade Templating
* Migrasi dan seeding database
* Artisan CLI untuk scaffolding dan manajemen proyek
* Support folder `resources/Views` dan fallback ke `services/`
* Upload file terstruktur di folder `private-uploads/`

---

## ⚠️ Catatan Penggunaan

> **Framework ini khusus direkomendasikan untuk digunakan pada hosting berbayar atau premium.**
> Karena THE-FRAMEWORK membutuhkan fitur server berikut:
>
> * PHP 8.0+ dengan ekstensi lengkap
> * Akses CLI (command-line) untuk menjalankan perintah `php artisan`
> * Composer terinstal di server
> * Dukungan `.htaccess` (mod\_rewrite aktif)
> * Izin baca/tulis folder di luar direktori publik (`private-uploads/`)

🚫 **Hosting gratis** kemungkinan besar tidak mendukung fitur-fitur ini dan bisa menyebabkan error saat setup atau deployment.

---

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

   * Perintah ini akan membuat `.env`, memperbarui namespace, menjalankan migrasi, dan generate key enkripsi.
   * Sesuaikan `DB_*` di `.env` jika dibutuhkan sebelum menjalankan.

4. **Refresh Composer**:

   ```bash
   composer dump-autoload
   ```

5. **Jalankan Server**:

   ```bash
   php artisan serve
   ```

   Akses di `http://localhost:8080`.

### Persyaratan

* PHP 8.0+
* Composer
* MySQL (atau kompatibel)

---

## 📂 Struktur Direktori

```
THE-FRAMEWORK/
├── app/
│   ├── App/
│   │   ├── Blueprint.php
│   │   ├── CacheManager.php
│   │   ├── Config.php
│   │   ├── Database.php
│   │   ├── ImageOptimizer.php
│   │   ├── Logging.php
│   │   ├── RateLimiter.php
│   │   ├── Router.php
│   │   ├── Schema.php
│   │   ├── SessionManager.php
│   │   └── View.php
│   ├── Database/
│   │   └── Migration.php
│   ├── Helpers/
│   │   ├── Helper.php
│   │   └── helpers.php
│   ├── Http/
│   │   └── Controllers/
│   │       ├── ErrorController.php
│   │       └── HomeController.php
│   ├── Middleware/
│   │   ├── AuthMiddleware.php
│   │   ├── CsrfMiddleware.php
│   │   ├── Middleware.php
│   │   ├── ValidationMiddleware.php
│   │   └── WAFMiddleware.php
│   ├── Models/
│   │   ├── Seeders/
│   │   │   └── UserSeeder.php
│   │   └── HomeModel.php
│   ├── Storage/
│   │   ├── cache/
│   │   └── logs/
│   └── BladeInit.php
├── database/
│   ├── migrations/
│   │   └── UsersTable.php
│   └── seeders/
│       └── UserSeeder.php
├── htdocs/
│   ├── .htaccess
│   ├── file.php
│   └── index.php
├── private-uploads/
│   ├── dummy/
│   └── user-pictures/
├── resources/
│   ├── css/
│   ├── js/
│   └── Views/
│       └── (Views Blade di sini)
├── services/
│   └── error/
│       ├── 404.blade.php
│       ├── 500.blade.php
│       ├── maintenance.blade.php
│       └── payment.blade.php
├── vendor/
├── .env
├── .env.example
├── .gitignore
├── artisan
├── composer.json
├── composer.lock
└── README.md
```

---

## 🔧 Perintah Artisan

* **Setup proyek**        : `php artisan setup`
* **Jalankan server**     : `php artisan serve`
* **Migrasi database**    :

  * `php artisan migrate`
  * `php artisan migrate:fresh`
  * `php artisan rollback`
* **Seeding data**        :

  * `php artisan seed`
  * `php artisan seed --class=ClassName`
* **Scaffold file**       :

  * `php artisan make:controller NameController`
  * `php artisan make:model NameModel`
  * `php artisan make:seeder NameSeeder`
  * `php artisan make:migration CreateNameTable`
  * `php artisan make:middleware NameMiddleware`

> Semua file yang dihasilkan akan menggunakan namespace sesuai PSR‑4 di `composer.json`.

---

## 🌐 Konfigurasi ENV

Sesuaikan file `.env`:

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=the_framework
DB_USER=root
DB_PASS=

REDIS_HOST=127.0.0.1
REDIS_PORT=6379
REDIS_PASSWORD=null

MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=no-reply@example.com
MAIL_FROM_NAME="The Framework"

CACHE_DRIVER=file
SESSION_DRIVER=file

LOG_CHANNEL=single
LOG_LEVEL=warning

APP_TIMEZONE=UTC
APP_LOCALE=en
```

---

## 🌐 Konfigurasi Jika Menggunakan Google Project IDX

Sesuaikan file `idx/dev.nix`:
```
{ pkgs, ... }: {
  channel = "stable-24.05";

  packages = [
    pkgs.php
    pkgs.php81Packages.composer
    pkgs.nodejs_20
    pkgs.python3
    pkgs.tailwindcss
  ];

  services.mysql = {
    enable = true;
    package = pkgs.mariadb;
  };

  env = {
    PHP_PATH = "/usr/bin/php";
  };

  idx = {
    extensions = [
      "rangav.vscode-thunder-client"
      "amirmarmul.laravel-blade-vscode"
      "bradlc.vscode-tailwindcss"
      "cweijan.dbclient-jdbc"
      "cweijan.vscode-database-client2"
      "formulahendry.vscode-mysql"
      "imgildev.vscode-tailwindcss-snippets"
      "onecentlin.laravel-blade"
      "shufo.vscode-blade-formatter"
      "yandeu.five-server"
    ];
    previews = {
      enable = true;
      previews = {
        web = {
          command = ["python3" "-m" "http.server" "$PORT" "--bind" "0.0.0.0"];
          manager = "web";
        };
      };
    };
    workspace = {
      onCreate = {
        default.openFiles = ["index.php"];
      };
      onStart = {
        run-server = "php -S localhost:8080 -t htdocs";
      };
    };
  };
}

```

---

## 🤝 Kontribusi

Kami terbuka untuk kontribusi! Silakan buat pull request atau hubungi:

* WhatsApp: 085730676143
* Email   : [chandratriantomo123@gmail.com](mailto:chandratriantomo123@gmail.com)
* Website : [https://www.kiwkiw-native.free.nf](https://www.the-framework.ct.ws)
