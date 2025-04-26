# Kiwkiw - MVC Native PHP Framework

## 📌 Pengenalan

**Kiwkiw** adalah framework PHP berbasis MVC (Model-View-Controller) yang dibuat oleh **Chandra Tri A**. Framework ini dirancang untuk mempermudah pengelolaan proyek PHP dengan arsitektur yang lebih terstruktur dan mendukung fitur seperti migrasi database dan seeder ala Laravel.

## 🚀 Cara Instalasi

### 1️⃣ Clone Proyek

```sh
  git clone https://github.com/Chandra2004/Kiwkiw-Native.git
  cd Kiwkiw-Native
```

📌 **Catatan**: jika ingin tidak memakai ada redis.
```sh
git clone -b mvc-finish https://github.com/Chandra2004/Kiwkiw-Native.git
```

### 2️⃣ Inisialisasi Composer

```sh
  composer init
```

📌 **Catatan**: Pastikan untuk tidak meletakkan file di `src/`, tetapi ubah ke `app/`.

### 3️⃣ Konfigurasi Token GitHub (Opsional, jika menggunakan repositori privat)

1. Buka **GitHub** dan masuk ke **Settings**
2. Pilih **Developer settings** > **Personal access tokens** > **Tokens (classic)**
3. Klik **Generate new token** (beri akses ke Composer, misalnya repo)
4. Copy token tersebut
5. Jalankan perintah berikut di terminal:
   ```sh
   composer config --global github-oauth.github.com [TOKEN_GITHUB]
   ```

### 4️⃣ Install Dependensi dengan Composer

```sh
  composer require vlucas/phpdotenv   # Untuk konfigurasi .env
  composer require fakerphp/faker    # Untuk seeder
  composer require illuminate/view illuminate/events illuminate/filesystem    # Untuk blade templating
  composer require predis/predis # Untuk Redis cache
  composer require spatie/image-optimizer #image
```

### 5️⃣ Konfigurasi `composer.json`

Tambahkan pengaturan berikut ke dalam file `composer.json`:

```json
{
    "name": "namaprojek/anda",
    "autoload": {
        "psr-4": {
            "Database\\": "database/",
            "Database\\Migrations\\": "database/migrations/",
            "Database\\Seeders\\": "database/seeders/"
        }
    },
    "files": [
        "app/App/Config.php",
        "app/Helpers/helpers.php"
    ],
    "require": {
        "php": ">=8"
    },
    "scripts": {
      "post-autoload-dump": [
          "{{NAMESPACE}}\\App\\Router::cacheRoutes"
      ],
      "post-install-cmd": [
          "php update-namespace.php"
      ],
      "post-update-cmd": [
          "php update-namespace.php"
      ]
    }
}
```

📌 **Setelah mencopy `composer.json` tersebut, jalankan perintah:**

```sh
  composer dump-autoload
```

### 6️⃣ Update Namespace Setelah Instalasi

Jalankan update namespace agar autoload berjalan:

```sh
  php update-namespace.blade.php
```

### 7 Setting .env :
```env
  BASE_URL=http://localhost:8080
```

### 8 Jalankan Server Kiwkiw :
```sh
  php artisan serve
```

akses melalui browser: `http://localhost:8080`

---

## 📂 Struktur Direktori

```
Kiwkiw-Native/
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
├── private-uploads/
├── vendor/
├── .env
├── gitignore
├── artisan
├── artisan.bat
├── composer.json
├── composer.lock
├── README.md
└── update-namespace.php
```

---

### .HTACCESS jika menggunakan server gratisan

```.htaccess

# Disable directory browsing
Options All -Indexes

# ----------------------------------------------------------------------
# Rewrite engine
# ----------------------------------------------------------------------

# Turning on the rewrite engine is necessary for the following rules and features.
# FollowSymLinks must be enabled for this to work.
<IfModule mod_rewrite.c>
	Options +FollowSymlinks
	RewriteEngine On

	# If you installed CodeIgniter in a subfolder, you will need to
	# change the following line to match the subfolder you need.
	# http://httpd.apache.org/docs/current/mod/mod_rewrite.html#rewritebase
	# RewriteBase /

	# Redirect Trailing Slashes...
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteCond %{REQUEST_URI} (.+)/$
	RewriteRule ^ %1 [L,R=301]

	# Rewrite "www.example.com -> example.com"
	RewriteCond %{HTTPS} !=on
	RewriteCond %{HTTP_HOST} ^www\.(.+)$ [NC]
	RewriteRule ^ http://%1%{REQUEST_URI} [R=301,L]

	# Checks to see if the user is attempting to access a valid file,
	# such as an image or css document, if this isn't true it sends the
	# request to the front controller, index.php
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteRule ^([\s\S]*)$ index.php/$1 [L,NC,QSA]

	# Ensure Authorization header is passed along
	RewriteCond %{HTTP:Authorization} .
	RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
</IfModule>

<IfModule !mod_rewrite.c>
	# If we don't have mod_rewrite installed, all 404's
	# can be sent to index.php, and everything works as normal.
	ErrorDocument 404 index.php
</IfModule>

# Disable server signature start
	ServerSignature Off
# Disable server signature end
```

---

## 🔥 INFORMASI JIKA ANDA MENGGUNAKAN IDX GOOGLE (dev.nix)
```nix

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
        run-server = "php -S localhost:3000 -t htdocs";
      };
    };
  };
}

```

```sh

mysql
SELECT @hostname;

```

## 🔥 Cara Menggunakan Migration dan Seeder

### 🔹 **Migration** (Migrasi Database)

#### 📌 Membuat File Migrasi

Buat file migrasi baru di `database/migrations/` (contoh: `CreateUsersTable.php`).

#### 📌 Menjalankan Migrasi

```sh
  php artisan migrate                    # Jalankan semua migrasi
  php artisan migrate --class=NamaClass  # Jalankan migrasi spesifik
  php artisan migrate:fresh              # Hapus semua tabel, buat ulang, lalu jalankan seeder
  php artisan migrate:refresh            # Hapus dan buat ulang tabel tanpa menjalankan seeder
  php artisan migrate:refresh --class=NamaClass  # Jalankan ulang migrasi tertentu
```

### 🔹 **Seeder** (Mengisi Database dengan Data Dummy)

#### 📌 Membuat File Seeder

Buat file seeder baru di `database/seeders/` (contoh: `UserSeeder.php`).

#### 📌 Menjalankan Seeder

```sh
  php artisan seed                     # Jalankan semua seeder
  php artisan seed --class=NamaClass   # Jalankan seeder spesifik
```

---

## 🌍 Konfigurasi **BASE\_URL** di `.env`

Tambahkan BASE\_URL di file `.env` sesuai dengan file path dari `public/`:

```env
BASE_URL=http://localhost:8080
```

---

## 🔎 Melihat Database Setelah Penginstalan

Jika Anda ingin melihat bagaimana database bekerja setelah penginstalan Kiwkiw Native, jalankan perintah berikut di terminal:

```sh
  php artisan migrate:fresh
```

Setelah itu, buka URL `/user` di browser Anda. Untuk melihat lebih detail dari data user, klik pada user yang ingin dituju.

---

#### 📌 Note Patch

Menambahkan sedikit pembaharuan :

(09 Februari 2025)
1. daerah upload (ketika user ingin mengupload foto atau selainnya).
2. daerah migration & seeder lebih dipermudah karena adanya schema dan blueprint.
3. daerah artisan agar CLI lebih interaktif.

(15 maret 2025)

1. CSRF
2. mengganti direktori htdocs/upload menjadi ../private-uploads
3. menambahkan keamanan agar tidak mudah diserang
4. sanitasi file

(17 maret 2025)

1. menambahkan running server `php artisan serve` di port 8080
2. menambahkan helper

(05 april 2025)

1. menambahkan generateUUID() function di helper

(06 april 2025)

1. menambahkan enviroment payment & maintenance


---

## ✨ Kontribusi

Jika ingin berkontribusi, silakan buat pull request atau hubungi saya!

📞 **Kontak**:

- **WhatsApp**: 085730676143
- **Email**: [chandratriantomo123@gmail.com](mailto\:chandratriantomo123@gmail.com)
- **Website**: [Kiwkiw-Native](https://www.kiwkiw-native.free.nf)
  
---

Terima kasih telah menggunakan **Kiwkiw-Native**! 🚀
