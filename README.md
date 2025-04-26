# Kiwkiw - MVC Native PHP Framework

## 📌 Pengenalan

**Kiwkiw** adalah framework PHP berbasis MVC (Model-View-Controller) yang dibuat oleh **Chandra Tri A**. Framework ini dirancang untuk pengelolaan proyek PHP yang terstruktur, mendukung migrasi database, seeder, Blade templating, dan pembuatan file otomatis melalui perintah `artisan`. Kiwkiw memungkinkan pengembangan aplikasi web yang cepat dan terorganisir dengan fitur seperti manajemen namespace dinamis berdasarkan PSR-4 dan konfigurasi yang mudah.

## 🚀 Instalasi

### Langkah-langkah

1. **Clone Proyek**:
   ```sh
   git clone https://github.com/Chandra2004/Kiwkiw-Native.git
   cd Kiwkiw-Native
   ```

2. **Install Dependensi**:
   ```sh
   composer install
   ```

3. **Jalankan Setup**:
   Perintah ini akan meminta informasi proyek (nama, deskripsi, penulis, namespace), mengatur `.env`, memperbarui namespace, menjalankan migrasi, dan menghasilkan kunci enkripsi:
   ```sh
   php artisan setup
   ```
   - Tekan Enter untuk menggunakan nilai default (misalnya, namespace `Vendor\Project`).
   - Sesuaikan variabel `DB_*` di `.env` sebelum setup jika menggunakan database.

4. **Jalankan Server**:
   ```sh
   php artisan serve
   ```
   Akses aplikasi di `http://localhost:8080`.

### Catatan
- **Persyaratan**: PHP >= 8.0, Composer, dan database (misalnya, MySQL).
- **Kustomisasi**:
  - **Nama Proyek**: Masukkan format `vendor/project` (misalnya, `john/my-app`).
  - **Deskripsi**: Jelaskan proyek Anda.
  - **Penulis**: Masukkan nama dan email Anda.
  - **Namespace**: Gunakan namespace kustom (misalnya, `MyApp`) atau default `Vendor\Project`.
- **Manual Namespace**: Jalankan `php update-namespace.php YourNamespace` sebelum `php artisan setup` untuk namespace khusus tanpa prompt.
- **Keamanan**: File `.env` secara otomatis diatur dengan izin aman (`0600`) selama setup.

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
│   │   ├── Router.php
│   │   ├── Schema.php
│   │   └── View.php
│   ├── Helpers/
│   │   ├── Helper.php
│   │   ├── helpers.php
│   ├── Controller/
│   │   ├── ErrorController.php
│   │   └── HomeController.php
│   ├── Database/
│   │   └── Migration.php
│   ├── Middleware/
│   │   ├── Middleware.php
│   │   ├── CsrfMiddleware.php
│   │   └── AuthMiddleware.php
│   ├── Models/
│   │   ├── Seeders/
│   │   │   └── User.php
│   │   └── HomeModel.php
│   ├── Storage/
│   │   └── cache/
│   │       ├── views/
│   │       └── routes.cache
│   ├── View/
│   │   ├── error/
│   │   │   ├── payment.blade.php
│   │   │   ├── maintenance.blade.php
│   │   │   ├── error404.blade.php
│   │   │   └── error500.blade.php
│   │   └── interface/
│   │       ├── detail.blade.php
│   │       ├── home.blade.php
│   │       └── user.blade.php
│   └── BladeInit.php
├── database/
│   ├── migrations/
│   │   └── CreateUsersTable.php
│   └── seeders/
│       └── UserSeeder.php
├── htdocs/
│   ├── .htaccess
│   ├── file.php
│   └── index.php
├── private-Uploads/
├── vendor/
├── .env
├── .env.example
├── .gitignore
├── artisan
├── composer.json
├── composer.lock
├── README.md
└── update-namespace.php
```

## 🔥 Menggunakan Perintah Artisan

Perintah `artisan` adalah alat utama untuk mengelola proyek Kiwkiw, termasuk pembuatan file, migrasi database, dan seeding data. Berikut adalah daftar perintah yang tersedia:

### Setup Proyek
Mengatur proyek dengan membuat file `.env`, memperbarui `composer.json`, menginstal dependensi, menjalankan migrasi, dan menghasilkan kunci enkripsi:
```sh
php artisan setup
```

### Menjalankan Server
Menjalankan server pengembangan di `http://localhost:8080`:
```sh
php artisan serve
```

### Migrasi Database
Mengelola skema database melalui file migrasi di `database/migrations/`:
```sh
php artisan migrate                    # Jalankan semua migrasi
php artisan migrate --class=ClassName  # Jalankan migrasi spesifik
php artisan migrate:fresh              # Hapus dan buat ulang semua tabel
php artisan migrate:refresh            # Rollback dan jalankan ulang migrasi
php artisan rollback                   # Batalkan migrasi terakhir
php artisan rollback --class=ClassName # Batalkan migrasi spesifik
```

### Seeding Data
Mengisi database dengan data awal melalui file seeder di `database/seeders/`:
```sh
php artisan seed                     # Jalankan semua seeder
php artisan seed --class=ClassName   # Jalankan seeder spesifik
```

### Membuat File
Perintah `make:*` digunakan untuk membuat file baru dengan struktur dan namespace yang sesuai berdasarkan konfigurasi PSR-4 di `composer.json`. Namespace diambil secara dinamis, memastikan fleksibilitas jika struktur proyek berubah.

#### Membuat Controller
Membuat file controller di `app/Controller/` dengan `use` statements untuk kelas yang umum digunakan:
```sh
php artisan make:controller NamaController
```
Contoh output (`app/Controller/NamaController.php`):
```php
<?php

namespace Prochan\Praktikum\Controller;

use Prochan\Praktikum\App\{Config, Database, View, CacheManager};
use Prochan\Praktikum\Helpers\Helper;
use Exception;

class NamaController
{
    public function index()
    {
        //
    }
}
```

#### Membuat Model
Membuat file model di `app/Models/` yang mewarisi `Database` dengan `use` statements untuk kelas terkait:
```sh
php artisan make:model NamaModel
```
Contoh output (`app/Models/NamaModel.php`):
```php
<?php

namespace Prochan\Praktikum\Models;

use Prochan\Praktikum\App\CacheManager;
use Prochan\Praktikum\App\Database;
use Prochan\Praktikum\App\Config;
use Prochan\Praktikum\App\Logging;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;

class NamaModel extends Database
{
    // your code here
}
```

#### Membuat Seeder
Membuat dua file seeder secara otomatis:
1. Seeder di `database/seeders/` untuk pengisian data.
2. Model seeder di `app/Models/Seeders/` untuk operasi penyisipan data ke tabel.

Perintah:
```sh
php artisan make:seeder NamaSeeder
```
- **Seeder** (`database/seeders/NamaSeeder.php`):
  ```php
  <?php

  namespace Database\Seeders;

  use Prochan\Praktikum\App\Config;
  use Defuse\Crypto\Crypto;
  use Defuse\Crypto\Key;
  use Faker\Factory;

  class NamaSeeder
  {
      private $encryptionKey;

      public function __construct()
      {
          Config::loadEnv();
          $keyString = Config::get('ENCRYPTION_KEY');
          if (!$keyString) {
              throw new \Exception('Encryption key not configured.');
          }
          $this->encryptionKey = Key::loadFromAsciiSafeString($keyString);
      }

      public function run()
      {
          //
      }
  }
  ```
- **Model Seeder** (`app/Models/Seeders/Nama.php`, tanpa sufiks `Seeder`):
  ```php
  <?php

  namespace Prochan\Praktikum\Models\Seeders;

  use Prochan\Praktikum\App\Database;

  class Nama
  {
      protected static Database $db;

      public static function create(array $data)
      {
          self::$db = Database::getInstance();
          $columns = implode(", ", array_keys($data));
          $placeholders = ":" . implode(", :", array_keys($data));

          $sql = "INSERT INTO nama ($columns) VALUES ($placeholders)";
          self::$db->query($sql);

          foreach ($data as $key => $value) {
              self::$db->bind(":$key", $value);
          }

          return self::$db->execute();
      }
  }
  ```
Catatan: Nama tabel di model seeder diambil dari nama kelas dalam huruf kecil (misalnya, `ProductSeeder` menghasilkan tabel `product`).

#### Membuat Migrasi
Membuat file migrasi di `database/migrations/` untuk mengelola skema database:
```sh
php artisan make:migration CreateNamaTable
```
Contoh output (`database/migrations/CreateNamaTable.php`):
```php
<?php

namespace Database\Migrations;

use Prochan\Praktikum\App\Schema;

class CreateNamaTable
{
    public function up()
    {
        Schema::create('nama', function ($table) {
            // Define columns
        });
    }

    public function down()
    {
        Schema::dropIfExists('nama');
    }
}
```

#### Membuat Middleware
Membuat file middleware di `app/Middleware/` yang mengimplementasikan interface `Middleware`:
```sh
php artisan make:middleware NamaMiddleware
```
Contoh output (`app/Middleware/NamaMiddleware.php`):
```php
<?php

namespace Prochan\Praktikum\Middleware;

use Prochan\Praktikum\Middleware\Middleware;

class NamaMiddleware implements Middleware
{
    public function before()
    {
        //
    }
}
```

#### Opsi Tambahan
- Gunakan `--force` untuk menimpa file yang sudah ada:
  ```sh
  php artisan make:controller NamaController --force
  ```

### Catatan Artisan
- **Namespace Dinamis**: Semua file yang dibuat menggunakan namespace berdasarkan konfigurasi PSR-4 di `composer.json`. Misalnya, jika `composer.json` mendefinisikan `"Lain\Proyek\\Controller\\": "app/Controller/"`, controller akan menggunakan namespace `Lain\Proyek\Controller`.
- **Error Handling**: Jika mapping PSR-4 tidak ditemukan di `composer.json`, perintah akan gagal dengan pesan error.
- **Autoloader**: Setelah mengubah `composer.json`, jalankan `composer dump-autoload` untuk memperbarui autoloader.

## 🌍 Konfigurasi BASE_URL
Pastikan `BASE_URL` di `.env` sesuai dengan alamat aplikasi Anda, misalnya:
```env
BASE_URL=http://localhost:8080
```

## 🔎 Melihat Database
Setelah instalasi dan seeding, akses endpoint `/user` di browser untuk melihat data pengguna yang telah dimasukkan ke database.

## ✨ Kontribusi
Kami menyambut kontribusi untuk meningkatkan Kiwkiw! Silakan buat pull request atau hubungi saya melalui:
- **WhatsApp**: 085730676143
- **Email**: chandratriantomo123@gmail.com
- **Website**: https://www.kiwkiw-native.free.nf

Terima kasih telah menggunakan **THE FRAMEWORK**! 🚀
