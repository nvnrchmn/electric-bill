# Aplikasi Pembayaran Listrik (Electric Bill)

Aplikasi Pembayaran Listrik adalah sebuah sistem manajemen tagihan dan penggunaan listrik yang dirancang untuk memudahkan baik pelanggan maupun admin/petugas dalam mengelola data listrik. Aplikasi ini memungkinkan pelanggan untuk melihat riwayat tagihan dan penggunaan mereka sendiri, sementara admin/petugas dapat mengelola data pelanggan, tarif, penggunaan, hingga pembayaran.

## Fitur Utama

Aplikasi ini memiliki dua sisi pengguna utama: **Pelanggan** dan **Admin/Petugas**.

### Sisi Pelanggan

-   **Login Pelanggan:** Pelanggan dapat masuk ke akun mereka sendiri.
-   **Dashboard Pelanggan:** Ringkasan informasi terkait akun dan tagihan.
-   **Lihat Tagihan Saya:**
    -   Melihat daftar tagihan listrik (tagihan bulan ini, riwayat tagihan).
    -   Melihat status tagihan (belum dibayar, sudah dibayar, dibatalkan).
    -   Melihat detail tagihan (jumlah meter, total tagihan).
    -   Filter tagihan berdasarkan bulan dan tahun.
-   **Lihat Riwayat Penggunaan:**
    -   Melihat daftar riwayat penggunaan listrik (bulan, tahun, meter awal, meter akhir, total penggunaan).
    -   Filter penggunaan berdasarkan bulan dan tahun.

### Sisi Admin/Petugas (Direncanakan/Parsial)

-   **Login Admin/Petugas:** Admin/petugas dapat masuk ke sistem.
-   **Dashboard Admin/Petugas:** (Akan dikembangkan lebih lanjut) Ringkasan data umum.
-   **Manajemen Data Master:**
    -   **Data Pelanggan:** Menambah, melihat, mengedit, menghapus data pelanggan.
    -   **Data Tarif:** Menambah, melihat, mengedit, menghapus data tarif listrik.
    -   **Data User/Petugas:** Menambah, melihat, mengedit, menghapus data user (admin/petugas) dan mengatur level/peran.
-   **Manajemen Transaksi:**
    -   **Input Penggunaan Listrik:** Mencatat meter awal dan meter akhir penggunaan listrik pelanggan setiap bulan.
    -   **Generate Tagihan:** Menghasilkan tagihan secara otomatis berdasarkan penggunaan dan tarif.
    -   **Konfirmasi Pembayaran:** Mencatat dan mengonfirmasi pembayaran tagihan oleh pelanggan.
    -   **Lihat Riwayat Pembayaran:** Melihat daftar dan detail pembayaran yang telah dilakukan.

## Teknologi yang Digunakan

-   **Framework:** Laravel (PHP Framework)
-   **Database:** MySQL
-   **Frontend:** Blade Template Engine, Tailwind CSS (dengan DaisyUI)
-   **Bahasa Pemrograman:** PHP, JavaScript, HTML, CSS

## Persyaratan Sistem

-   PHP >= 8.1
-   Composer
-   MySQL Database
-   Node.js & NPM (untuk kompilasi aset frontend)

## Instalasi Proyek

Ikuti langkah-langkah di bawah ini untuk menginstal dan menjalankan proyek di lingkungan lokal Anda:

1.  **Clone Repositori:**

    ```bash
    git clone https://github.com/nvnrchmn/electric-bill.git
    cd electric-bill-app
    ```

2.  **Instal Dependensi PHP:**

    ```bash
    composer install
    ```

3.  **Salin File Konfigurasi Lingkungan:**

    ```bash
    cp .env.example .env
    ```

4.  **Buat Kunci Aplikasi:**

    ```bash
    php artisan key:generate
    ```

5.  **Konfigurasi Database:**
    Buka file `.env` dan sesuaikan pengaturan database Anda:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=electric_bill_db  # Ganti dengan nama database Anda
    DB_USERNAME=root              # Ganti dengan username database Anda
    DB_PASSWORD=                  # Ganti dengan password database Anda
    ```

6.  **Jalankan Migrasi Database:**
    Ini akan membuat tabel-tabel yang diperlukan di database Anda.

    ```bash
    php artisan migrate
    ```

    _Jika Anda ingin mengisi data dummy:_

    ```bash
    php artisan db:seed
    ```

7.  **Instal Dependensi NPM & Kompilasi Aset Frontend:**

    ```bash
    npm install
    npm run dev  # Untuk pengembangan
    # npm run build # Untuk produksi
    ```

8.  **Jalankan Server Pengembangan:**
    ```bash
    php artisan serve
    ```
    Aplikasi akan tersedia di `http://127.0.0.1:8000`.

## Struktur Proyek Penting

-   `app/Models/`: Berisi definisi model Eloquent (`User.php`, `Pelanggan.php`, `Tagihan.php`, `Penggunaan.php`, `Tarif.php`, `Pembayaran.php`, `Level.php`).
-   `app/Http/Controllers/`: Berisi logika aplikasi.
    -   `Admin/`: Controller untuk fungsionalitas admin.
    -   `Pelanggan/`: Controller untuk fungsionalitas pelanggan.
    -   `Auth/`: Controller kustom untuk autentikasi (misalnya `LoginPelangganController.php`).
-   `resources/views/`: Berisi template Blade untuk tampilan frontend.
    -   `admin/`: View untuk sisi admin.
    -   `pelanggan/`: View untuk sisi pelanggan.
    -   `auth/`: View untuk halaman login (termasuk `pelanggan_login.blade.php`).
    -   `layouts/`: Struktur layout utama aplikasi.
-   `routes/web.php`: Definisi semua rute web aplikasi.
-   `config/auth.php`: Konfigurasi sistem autentikasi (guard dan provider).

## Penggunaan

### Login Admin/Petugas

-   Akses: `/login` (default Laravel auth)
-   Gunakan kredensial admin/petugas yang ada di database Anda (atau buat melalui seeder).
-   Setelah login, admin akan diarahkan ke `/admin/dashboard`.

### Login Pelanggan

-   Akses: `/pelanggan/login`
-   Gunakan username dan password pelanggan yang terdaftar.
-   Setelah login, pelanggan akan diarahkan ke `/pelanggan/dashboard`.

## Kontribusi

Kontribusi dipersilakan! Jika Anda ingin berkontribusi, silakan fork repositori ini, buat branch baru, lakukan perubahan, dan kirim _pull request_.

## Lisensi

Proyek ini dilisensikan di bawah Lisensi MIT.

---
