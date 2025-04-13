# E-Raport Al-Mufiid (vibe Coding)

Sistem manajemen rapor elektronik untuk TPA Al-Mufiid berbasis Laravel.

## Teknologi yang Digunakan

- PHP 8.1+
- Laravel 10.x
- Filament 3.2 (Admin Panel)
- TailwindCSS
- Alpine.js
- DomPDF
- Laravel Permission (Spatie)

## Fitur Utama

- Manajemen Data Santri
- Manajemen Nilai
- Generasi Rapor PDF
- Multi-role (Admin, Ustadz/Guru, Santri)
- Import/Export Nilai
- Dashboard Analytics
- Sistem Otentikasi & Otorisasi

## Persyaratan Sistem

- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL/MariaDB
- Extension PHP: BCMath, Ctype, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML

## Instalasi

1. Clone repositori
```bash
git clone https://github.com/[username]/al-mufiid-laravel.git
cd al-mufiid-laravel
```

2. Install dependensi PHP
```bash
composer install
```

3. Install dependensi JavaScript
```bash
npm install
```

4. Salin file environment
```bash
cp .env.example .env
```

5. Generate key aplikasi
```bash
php artisan key:generate
```

6. Konfigurasi database di file .env

7. Jalankan migrasi dan seeder
```bash
php artisan migrate --seed
```

8. Build assets
```bash
npm run build
```

9. Jalankan server development
```bash
php artisan serve
```

## Penggunaan

1. Akses panel admin di `/admin`
2. Login dengan kredensial yang telah disediakan
3. Kelola data santri, nilai, dan generate rapor sesuai kebutuhan

## Struktur Proyek

- `app/Filament/` - Konfigurasi dan resources Filament
- `app/Http/Controllers/` - Controller aplikasi
- `app/Models/` - Model Eloquent
- `database/migrations/` - Migrasi database
- `resources/views/` - Blade templates
- `routes/` - Definisi routing

## Lisensi

[MIT License](LICENSE.md)

## Kontribusi

1. Fork repositori
2. Buat branch fitur (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## Kontak

Untuk pertanyaan dan dukungan, silakan hubungi:
[Kontak Email/Informasi Lainnya]

