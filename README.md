# Pemrograman Berbasis Kerangka Kerja (PBKK)
| | |
| :-- | :-- |
| Mata kuliah | Pemrograman Berbasis Kerangka Kerja (PBKK) |
| Pertemuan | 9 |
| Nama | *Andie Azril Alfrianto* |
| NRP | *5025241054* |


Repositori ini berisi tugas praktikum mata kuliah Pemrograman Berbasis Kerangka Kerja. Semua aplikasi dibuat menggunakan
CodeIgniter 3 dan dikerjakan secara bertahap, mulai dari latihan dasar sampai studi kasus aplikasi yang lebih lengkap.

Ada tiga aplikasi di repositori ini:

1. Student Management (Latihan 1), aplikasi pengelolaan data mahasiswa untuk menerapkan materi dasar CodeIgniter.
2. SIAKAD (Latihan 2a), sistem informasi akademik kampus untuk admin, dosen, dan mahasiswa.
3. TokoKita (Latihan 2b), aplikasi toko online dengan halaman pelanggan dan panel admin.

Setiap aplikasi berada di folder sendiri dan memiliki database serta dokumentasi masing-masing.

## Latihan 1

[Dokumentasi Student Management](student_management/README.md)

Latihan 1 berisi materi tutorial dasar CodeIgniter. Materinya saya terapkan dalam aplikasi Student Management, yang
mencakup instalasi dan konfigurasi, routing dan controller, view dengan Bootstrap, template SB Admin 2, model dan CRUD,
upload foto, serta login dan session.

## Latihan 2

[Dokumentasi SIAKAD (Latihan 2a)](siakad/README.md)

SIAKAD adalah sistem informasi akademik perguruan tinggi. Admin mengelola data master dan jadwal kuliah, mahasiswa
mengisi KRS, dosen wali menyetujui KRS, dan dosen pengampu menginput nilai. Hasilnya berupa KHS dan transkrip yang bisa
dicetak.

[Dokumentasi TokoKita (Latihan 2b)](toko_kita/README.md)

TokoKita adalah aplikasi toko online. Pengunjung bisa melihat katalog, pelanggan bisa berbelanja sampai checkout dan
mengirim bukti transfer, sedangkan admin memverifikasi pembayaran, mengirim pesanan, dan melihat laporan penjualan.

## Perbandingan Fitur

| | Student Management | SIAKAD | TokoKita |
|---|---|---|---|
| Pengguna | Admin | Admin, dosen, mahasiswa | Admin, pelanggan |
| Data utama | Mahasiswa | Mahasiswa, dosen, prodi, mata kuliah, jadwal | Produk, kategori, pesanan, pelanggan |
| Alur utama | CRUD mahasiswa dan upload foto | KRS, perwalian, input nilai | Keranjang, checkout, pembayaran, pengiriman |
| Output | Tabel data mahasiswa | KHS dan transkrip | Invoice dan laporan penjualan |

## Teknologi yang Digunakan

Semua aplikasi berjalan di PHP 7.4 sampai 8.3. File CSS dan JavaScript disimpan di folder `assets` masing-masing,
jadi aplikasi tetap bisa dijalankan tanpa internet.

| | Student Management | SIAKAD | TokoKita |
|---|---|---|---|
| Framework | CodeIgniter 3.1.13 | CodeIgniter 3.1.13 | CodeIgniter 3.1.13 |
| Tampilan | Bootstrap 4, SB Admin 2 | Bootstrap 3, AdminLTE | Bootstrap 3, AdminLTE |
| Database | MySQL / MariaDB | MySQL / MariaDB | MySQL / MariaDB |

## Screenshot

Setiap README aplikasi dilengkapi screenshot yang diletakkan setelah penjelasan fiturnya.

| Aplikasi | Jumlah screenshot | Lokasi |
|---|---|---|
| Student Management | 10 | `student_management/docs/screenshots` |
| SIAKAD | 6 | `siakad/docs/screenshots` |
| TokoKita | 6 | `toko_kita/docs/screenshots` |

## Struktur Folder

```
pbkk/
├── README.md
├── student_management/   Latihan 1
├── siakad/               Latihan 2a
└── toko_kita/            Latihan 2b
```

## Cara Menjalankan

Cara menjalankan ketiga aplikasi pada dasarnya sama:

1. Salin folder aplikasi ke `C:\xampp\htdocs`.
2. Import file SQL dari folder `database` melalui phpMyAdmin.
3. Sesuaikan pengaturan database di `application/config/database.php` jika diperlukan.
4. Buka aplikasi melalui browser.

Penjelasan lengkapnya ada di README masing-masing aplikasi.

| Aplikasi | URL | Database | Akun |
|---|---|---|---|
| [Student Management](student_management/README.md#cara-menjalankan) | `localhost/student_management` | `student_management` | `admin` / `123456` |
| [SIAKAD](siakad/README.md#instalasi-xampp) | `localhost/siakad` | `db_siakad` | `admin`, `dosen`, `mahasiswa` / `123456` |
| [TokoKita](toko_kita/README.md#instalasi-xampp) | `localhost/toko_kita` dan `localhost/toko_kita/admin` | `db_tokoonline` | `admin` / `123456`, pelanggan `budi@contoh.com` / `123456` |

Student Management menggunakan MySQL di port 3310 karena menyesuaikan XAMPP yang saya pakai, sedangkan SIAKAD dan
TokoKita menggunakan port 3306. Jika port MySQL di komputer Anda berbeda, ubah di file `database.php`.

## Catatan

Semua data di aplikasi ini hanya data contoh untuk keperluan belajar. Pembayaran di TokoKita masih berupa simulasi
transfer manual, tidak terhubung ke payment gateway. Foto mahasiswa, gambar produk, dan bukti transfer pada data contoh
juga hanya ilustrasi.
