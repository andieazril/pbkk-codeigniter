-- =========================================================
--  DATABASE TOKO ONLINE — CodeIgniter 3
--  Admin   : admin / 123456
--  Pelanggan: budi@contoh.com / 123456
-- =========================================================
CREATE DATABASE IF NOT EXISTS db_tokoonline CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE db_tokoonline;
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------
-- ADMIN & PENGATURAN
-- ---------------------------------------------------------
DROP TABLE IF EXISTS tbl_admin;
CREATE TABLE tbl_admin (
  id_admin   INT AUTO_INCREMENT PRIMARY KEY,
  nama       VARCHAR(100) NOT NULL,
  username   VARCHAR(50) NOT NULL UNIQUE,
  password   VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
INSERT INTO tbl_admin (nama, username, password) VALUES ('Administrator', 'admin', '$2y$10$8yQKsHtzp/BMsGNoRi.GEORQ1LeiiIx9N57vg./9utwgXgaavwhFi');

DROP TABLE IF EXISTS tbl_toko;
CREATE TABLE tbl_toko (
  id_toko    INT PRIMARY KEY,
  nama_toko  VARCHAR(100) NOT NULL,
  slogan     VARCHAR(150),
  alamat     TEXT,
  kota       VARCHAR(50),
  telpon     VARCHAR(20),
  whatsapp   VARCHAR(20),
  email      VARCHAR(100),
  deskripsi  TEXT
) ENGINE=InnoDB;
INSERT INTO tbl_toko VALUES
(1, 'TokoKita', 'Belanja mudah, harga bersahabat', 'Jl. Merdeka No. 10', 'Bandung', '022-1234567', '6281234567890',
 'halo@tokokita.id', 'Toko online yang menyediakan fashion, elektronik, dan aksesoris pilihan dengan pengiriman ke seluruh Indonesia.');

DROP TABLE IF EXISTS tbl_rekening;
CREATE TABLE tbl_rekening (
  id_rekening INT AUTO_INCREMENT PRIMARY KEY,
  nama_bank   VARCHAR(50) NOT NULL,
  no_rekening VARCHAR(30) NOT NULL,
  atas_nama   VARCHAR(100) NOT NULL,
  is_aktif    ENUM('y','n') NOT NULL DEFAULT 'y'
) ENGINE=InnoDB;
INSERT INTO tbl_rekening (nama_bank, no_rekening, atas_nama) VALUES
('Bank Nusantara', '1234567890', 'PT TokoKita Indonesia'),
('Bank Rakyat Sejahtera', '0987654321', 'PT TokoKita Indonesia');

DROP TABLE IF EXISTS tbl_ongkir;
CREATE TABLE tbl_ongkir (
  id_ongkir   INT AUTO_INCREMENT PRIMARY KEY,
  kota        VARCHAR(50) NOT NULL,
  tarif_per_kg INT NOT NULL,          -- rupiah per kg (dibulatkan ke atas)
  estimasi    VARCHAR(30) DEFAULT '2-3 hari'
) ENGINE=InnoDB;
INSERT INTO tbl_ongkir (kota, tarif_per_kg, estimasi) VALUES
('Bandung',      8000,  '1 hari'),
('Jakarta',      12000, '1-2 hari'),
('Bekasi',       12000, '1-2 hari'),
('Bogor',        11000, '1-2 hari'),
('Surabaya',     18000, '2-3 hari'),
('Yogyakarta',   15000, '2-3 hari'),
('Semarang',     15000, '2-3 hari'),
('Medan',        30000, '3-5 hari'),
('Makassar',     35000, '3-5 hari');

-- ---------------------------------------------------------
-- KATALOG
-- ---------------------------------------------------------
DROP TABLE IF EXISTS tbl_kategori;
CREATE TABLE tbl_kategori (
  id_kategori   INT AUTO_INCREMENT PRIMARY KEY,
  nama_kategori VARCHAR(50) NOT NULL,
  slug          VARCHAR(60) NOT NULL UNIQUE,
  icon          VARCHAR(40) DEFAULT 'fa fa-tag'
) ENGINE=InnoDB;
INSERT INTO tbl_kategori (id_kategori, nama_kategori, slug, icon) VALUES
(1, 'Fashion Pria',   'fashion-pria',   'fa fa-male'),
(2, 'Fashion Wanita', 'fashion-wanita', 'fa fa-female'),
(3, 'Elektronik',     'elektronik',     'fa fa-plug'),
(4, 'Aksesoris',      'aksesoris',      'fa fa-diamond'),
(5, 'Rumah Tangga',   'rumah-tangga',   'fa fa-home');

DROP TABLE IF EXISTS tbl_produk;
CREATE TABLE tbl_produk (
  id_produk   INT AUTO_INCREMENT PRIMARY KEY,
  id_kategori INT NOT NULL,
  nama_produk VARCHAR(150) NOT NULL,
  slug        VARCHAR(170) NOT NULL UNIQUE,
  deskripsi   TEXT,
  harga       INT NOT NULL,
  harga_coret INT DEFAULT NULL,          -- harga sebelum diskon (opsional)
  stok        INT NOT NULL DEFAULT 0,
  berat       INT NOT NULL DEFAULT 500,  -- gram
  gambar      VARCHAR(150),
  is_aktif    ENUM('y','n') NOT NULL DEFAULT 'y',
  terjual     INT NOT NULL DEFAULT 0,
  created_at  DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
INSERT INTO tbl_produk (id_produk, id_kategori, nama_produk, slug, deskripsi, harga, harga_coret, stok, berat, gambar, terjual, created_at) VALUES
(1,  1, 'Kemeja Flanel Kotak Lengan Panjang', 'kemeja-flanel-kotak', 'Kemeja flanel bahan katun tebal, nyaman dipakai harian. Tersedia ukuran M, L, XL.', 149000, 189000, 25, 400, 'produk-1.png', 42, '2026-10-01 10:00:00'),
(2,  1, 'Kaos Polos Premium Cotton 30s',      'kaos-polos-premium',  'Kaos polos cotton combed 30s, adem dan tidak mudah melar.',                         59000,  NULL,   80, 200, 'produk-2.png', 120,'2026-10-03 10:00:00'),
(3,  1, 'Celana Chino Slim Fit',              'celana-chino-slim',   'Celana chino stretch potongan slim fit, cocok untuk kerja maupun santai.',          175000, 210000, 18, 600, 'produk-3.png', 35, '2026-10-05 10:00:00'),
(4,  2, 'Blouse Rayon Motif Bunga',           'blouse-rayon-bunga',  'Blouse rayon jatuh dan ringan dengan motif bunga kecil.',                           99000,  NULL,   30, 250, 'produk-4.png', 58, '2026-10-07 10:00:00'),
(5,  2, 'Rok Plisket Panjang',                'rok-plisket-panjang', 'Rok plisket bahan hyget premium, pinggang karet nyaman.',                           89000,  119000, 22, 350, 'produk-5.png', 40, '2026-10-09 10:00:00'),
(6,  2, 'Hijab Segi Empat Voal',              'hijab-voal',          'Hijab voal ultrafine, mudah dibentuk, tidak menerawang.',                           45000,  NULL,   100,100, 'produk-6.png', 210,'2026-10-11 10:00:00'),
(7,  3, 'Earphone Bluetooth TWS',             'earphone-tws',        'Earphone nirkabel dengan case pengisi daya, baterai hingga 20 jam.',                189000, 249000, 15, 150, 'produk-7.png', 77, '2026-10-13 10:00:00'),
(8,  3, 'Power Bank 10.000 mAh',              'power-bank-10000',    'Power bank 2 port USB dengan indikator daya, mendukung pengisian cepat.',          129000, NULL,   40, 300, 'produk-8.png', 95, '2026-10-15 10:00:00'),
(9,  3, 'Lampu Meja LED Lipat',               'lampu-meja-led',      'Lampu meja LED 3 mode cahaya, dapat diisi ulang via USB.',                          85000,  NULL,   12, 500, 'produk-9.png', 21, '2026-10-17 10:00:00'),
(10, 4, 'Jam Tangan Analog Kulit',            'jam-tangan-analog',   'Jam tangan analog strap kulit sintetis, tahan percikan air.',                       159000, 199000, 10, 200, 'produk-10.png',33, '2026-10-19 10:00:00'),
(11, 4, 'Tas Selempang Kanvas',               'tas-selempang-kanvas','Tas selempang kanvas dengan banyak kompartemen, cocok untuk harian.',              119000, NULL,   3,  450, 'produk-11.png',27, '2026-10-21 10:00:00'),
(12, 5, 'Set Wadah Makan Kaca 3 pcs',         'wadah-makan-kaca',    'Wadah makan kaca tahan panas dengan tutup kedap udara, aman untuk microwave.',     135000, 165000, 0,  1500,'produk-12.png',15, '2026-10-23 10:00:00');

-- ---------------------------------------------------------
-- PELANGGAN & PESANAN
-- ---------------------------------------------------------
DROP TABLE IF EXISTS tbl_pelanggan;
CREATE TABLE tbl_pelanggan (
  id_pelanggan INT AUTO_INCREMENT PRIMARY KEY,
  nama         VARCHAR(100) NOT NULL,
  email        VARCHAR(100) NOT NULL UNIQUE,
  password     VARCHAR(255) NOT NULL,
  telpon       VARCHAR(20),
  alamat       TEXT,
  id_ongkir    INT,
  kode_pos     VARCHAR(10),
  created_at   DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
INSERT INTO tbl_pelanggan (id_pelanggan, nama, email, password, telpon, alamat, id_ongkir, kode_pos, created_at) VALUES
(1, 'Budi Santoso', 'budi@contoh.com', '$2y$10$8yQKsHtzp/BMsGNoRi.GEORQ1LeiiIx9N57vg./9utwgXgaavwhFi', '081211112222', 'Jl. Kenanga No. 5', 2, '12345', '2026-10-20 09:00:00'),
(2, 'Siti Aminah',  'siti@contoh.com', '$2y$10$8yQKsHtzp/BMsGNoRi.GEORQ1LeiiIx9N57vg./9utwgXgaavwhFi', '081233334444', 'Jl. Melati No. 8', 5, '60111', '2026-10-25 09:00:00');

DROP TABLE IF EXISTS tbl_pesanan;
CREATE TABLE tbl_pesanan (
  id_pesanan    INT AUTO_INCREMENT PRIMARY KEY,
  kode_pesanan  VARCHAR(20) NOT NULL UNIQUE,
  id_pelanggan  INT NOT NULL,
  nama_penerima VARCHAR(100) NOT NULL,
  telpon        VARCHAR(20) NOT NULL,
  alamat        TEXT NOT NULL,
  kota          VARCHAR(50) NOT NULL,
  kode_pos      VARCHAR(10),
  catatan       VARCHAR(255),
  total_berat   INT NOT NULL,          -- gram
  subtotal      INT NOT NULL,
  ongkir        INT NOT NULL,
  total         INT NOT NULL,
  status        ENUM('Menunggu Pembayaran','Menunggu Verifikasi','Diproses','Dikirim','Selesai','Dibatalkan') NOT NULL DEFAULT 'Menunggu Pembayaran',
  no_resi       VARCHAR(50),
  created_at    DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at    DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

DROP TABLE IF EXISTS tbl_pesanan_detail;
CREATE TABLE tbl_pesanan_detail (
  id_detail   INT AUTO_INCREMENT PRIMARY KEY,
  id_pesanan  INT NOT NULL,
  id_produk   INT NOT NULL,
  nama_produk VARCHAR(150) NOT NULL,   -- disalin agar riwayat tetap utuh walau produk diubah
  harga       INT NOT NULL,
  qty         INT NOT NULL,
  berat       INT NOT NULL,
  subtotal    INT NOT NULL
) ENGINE=InnoDB;

DROP TABLE IF EXISTS tbl_pembayaran;
CREATE TABLE tbl_pembayaran (
  id_pembayaran    INT AUTO_INCREMENT PRIMARY KEY,
  id_pesanan       INT NOT NULL,
  id_rekening      INT NOT NULL,
  nama_pengirim    VARCHAR(100) NOT NULL,
  bank_pengirim    VARCHAR(50) NOT NULL,
  jumlah           INT NOT NULL,
  tanggal_transfer DATE NOT NULL,
  bukti            VARCHAR(150) NOT NULL,
  status           ENUM('Menunggu','Diterima','Ditolak') NOT NULL DEFAULT 'Menunggu',
  catatan_admin    VARCHAR(255),
  created_at       DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- contoh pesanan (berat detail = berat per unit; ongkir = tarif/kg x pembulatan ke atas kg)
INSERT INTO tbl_pesanan (id_pesanan, kode_pesanan, id_pelanggan, nama_penerima, telpon, alamat, kota, kode_pos, catatan, total_berat, subtotal, ongkir, total, status, no_resi, created_at) VALUES
(1, 'INV-20261102-0001', 1, 'Budi Santoso', '081211112222', 'Jl. Kenanga No. 5', 'Jakarta',  '12345', NULL,         650, 248000, 12000, 260000, 'Selesai',             'JNX1234567890', '2026-11-02 10:15:00'),
(2, 'INV-20261110-0002', 2, 'Siti Aminah',  '081233334444', 'Jl. Melati No. 8',  'Surabaya', '60111', 'Warna hitam', 150, 189000, 18000, 207000, 'Dikirim',             'JNX9876543210', '2026-11-10 14:20:00'),
(3, 'INV-20261118-0003', 1, 'Budi Santoso', '081211112222', 'Jl. Kenanga No. 5', 'Jakarta',  '12345', NULL,         700, 178000, 12000, 190000, 'Menunggu Verifikasi', NULL,            '2026-11-18 08:40:00'),
(4, 'INV-20261121-0004', 2, 'Siti Aminah',  '081233334444', 'Jl. Melati No. 8',  'Surabaya', '60111', NULL,         300, 129000, 18000, 147000, 'Menunggu Pembayaran', NULL,            '2026-11-21 19:05:00'),
(5, 'INV-20261122-0005', 1, 'Budi Santoso', '081211112222', 'Jl. Kenanga No. 5', 'Jakarta',  '12345', 'Kirim pagi', 500, 263000, 12000, 275000, 'Diproses',            NULL,            '2026-11-22 11:30:00');
INSERT INTO tbl_pesanan_detail (id_pesanan, id_produk, nama_produk, harga, qty, berat, subtotal) VALUES
(1, 1,  'Kemeja Flanel Kotak Lengan Panjang', 149000, 1, 400, 149000),
(1, 4,  'Blouse Rayon Motif Bunga',            99000, 1, 250,  99000),
(2, 7,  'Earphone Bluetooth TWS',             189000, 1, 150, 189000),
(3, 5,  'Rok Plisket Panjang',                 89000, 2, 350, 178000),
(4, 8,  'Power Bank 10.000 mAh',              129000, 1, 300, 129000),
(5, 10, 'Jam Tangan Analog Kulit',            159000, 1, 200, 159000),
(5, 6,  'Hijab Segi Empat Voal',               45000, 1, 100,  45000),
(5, 2,  'Kaos Polos Premium Cotton 30s',       59000, 1, 200,  59000);
INSERT INTO tbl_pembayaran (id_pesanan, id_rekening, nama_pengirim, bank_pengirim, jumlah, tanggal_transfer, bukti, status, created_at) VALUES
(1, 1, 'Budi Santoso', 'Bank Nusantara', 260000, '2026-11-02', 'bukti-contoh.png', 'Diterima', '2026-11-02 12:00:00'),
(2, 2, 'Siti Aminah',  'Bank Sejahtera', 207000, '2026-11-10', 'bukti-contoh.png', 'Diterima', '2026-11-10 16:00:00'),
(3, 1, 'Budi Santoso', 'Bank Nusantara', 190000, '2026-11-18', 'bukti-contoh.png', 'Menunggu', '2026-11-18 09:00:00'),
(5, 1, 'Budi Santoso', 'Bank Nusantara', 275000, '2026-11-22', 'bukti-contoh.png', 'Diterima', '2026-11-22 12:00:00');

SET FOREIGN_KEY_CHECKS = 1;
