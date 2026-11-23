-- =========================================================
--  DATABASE SIAKAD PERGURUAN TINGGI
--  CodeIgniter 3 + AdminLTE
--  Password semua akun demo: 123456
-- =========================================================
CREATE DATABASE IF NOT EXISTS db_siakad CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE db_siakad;

SET FOREIGN_KEY_CHECKS = 0;

-- hapus tabel versi sekolah (jika ada)
DROP TABLE IF EXISTS tbl_siswa, tbl_guru, tbl_jurusan, tbl_mapel, tbl_rombel, tbl_riwayat_kelas,
  tbl_kurikulum, tbl_kurikulum_detail, tbl_walikelas, tbl_nilai, tbl_sekolah_info;

-- ---------------------------------------------------------
-- USER, LEVEL, MENU, HAK AKSES
-- ---------------------------------------------------------
DROP TABLE IF EXISTS tbl_level_user;
CREATE TABLE tbl_level_user (
  id_level_user INT AUTO_INCREMENT PRIMARY KEY,
  nama_level    VARCHAR(50) NOT NULL
) ENGINE=InnoDB;
INSERT INTO tbl_level_user (id_level_user, nama_level) VALUES
(1, 'Administrator'), (2, 'Dosen'), (3, 'Mahasiswa');

DROP TABLE IF EXISTS tbl_user;
CREATE TABLE tbl_user (
  id_user       INT AUTO_INCREMENT PRIMARY KEY,
  nama_lengkap  VARCHAR(100) NOT NULL,
  username      VARCHAR(50)  NOT NULL UNIQUE,
  password      VARCHAR(255) NOT NULL,
  id_level_user INT NOT NULL,
  id_dosen      INT DEFAULT NULL,          -- relasi untuk level Dosen
  nim           VARCHAR(20) DEFAULT NULL,  -- relasi untuk level Mahasiswa
  created_at    DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
INSERT INTO tbl_user (id_user, nama_lengkap, username, password, id_level_user, id_dosen, nim) VALUES
(1, 'Administrator',            'admin',     '$2y$10$J/aXJPHY7klWr3Tr8KMdY.ZWNezEfa.lsf166owOUNpNt7LB.MReC', 1, NULL, NULL),
(2, 'Dr. Budi Santoso, M.Kom',  'dosen',     '$2y$10$J/aXJPHY7klWr3Tr8KMdY.ZWNezEfa.lsf166owOUNpNt7LB.MReC', 2, 1, NULL),
(3, 'Andi Pratama',             'mahasiswa', '$2y$10$J/aXJPHY7klWr3Tr8KMdY.ZWNezEfa.lsf166owOUNpNt7LB.MReC', 3, NULL, '2024001');

DROP TABLE IF EXISTS tbl_menu;
CREATE TABLE tbl_menu (
  id_menu      INT AUTO_INCREMENT PRIMARY KEY,
  nama_menu    VARCHAR(50) NOT NULL,
  link         VARCHAR(100) NOT NULL,
  icon         VARCHAR(50) NOT NULL DEFAULT 'fa fa-circle-o',
  is_main_menu INT NOT NULL DEFAULT 0,   -- 0 = menu utama, selain itu = id_menu induk
  urutan       INT NOT NULL DEFAULT 0
) ENGINE=InnoDB;
INSERT INTO tbl_menu (id_menu, nama_menu, link, icon, is_main_menu, urutan) VALUES
(1,  'Dashboard',         'dashboard',     'fa fa-dashboard',        0, 1),
(2,  'Data Master',       '#',             'fa fa-database',         0, 2),
(3,  'Mahasiswa',         'mahasiswa',     'fa fa-users',            2, 1),
(4,  'Dosen',             'dosen',         'fa fa-user-secret',      2, 2),
(5,  'Program Studi',     'prodi',         'fa fa-sitemap',          2, 3),
(6,  'Mata Kuliah',       'matakuliah',    'fa fa-book',             2, 4),
(7,  'Ruangan',           'ruangan',       'fa fa-building',         2, 5),
(8,  'Tahun Akademik',    'tahunakademik', 'fa fa-calendar',         2, 6),
(9,  'Akademik',          '#',             'fa fa-graduation-cap',   0, 4),
(10, 'Jadwal Kuliah',     'jadwal',        'fa fa-clock-o',          9, 1),
(11, 'KRS',               'krs',           'fa fa-list-alt',         9, 2),
(12, 'Perwalian',         'perwalian',     'fa fa-check-square-o',   9, 3),
(13, 'Input Nilai',       'nilai',         'fa fa-pencil-square-o',  9, 4),
(14, 'KHS',               'khs',           'fa fa-file-text-o',      9, 5),
(15, 'Transkrip Nilai',   'transkrip',     'fa fa-certificate',      9, 6),
(16, 'Pengaturan',        '#',             'fa fa-cogs',             0, 5),
(17, 'Data User',         'users',         'fa fa-user',            16, 1),
(18, 'Level User',        'leveluser',     'fa fa-key',             16, 2),
(19, 'Menu',              'menu',          'fa fa-bars',            16, 3),
(20, 'Hak Akses',         'hakakses',      'fa fa-lock',            16, 4),
(21, 'Info Kampus',       'kampus',        'fa fa-institution',     16, 5),
(22, 'Jadwal Mengajar',   'jadwalsaya',    'fa fa-calendar-check-o', 0, 3);

DROP TABLE IF EXISTS tbl_user_rule;
CREATE TABLE tbl_user_rule (
  id_rule       INT AUTO_INCREMENT PRIMARY KEY,
  id_menu       INT NOT NULL,
  id_level_user INT NOT NULL,
  UNIQUE KEY uq_rule (id_menu, id_level_user)
) ENGINE=InnoDB;
-- Administrator: semua menu kecuali Jadwal Mengajar
INSERT INTO tbl_user_rule (id_menu, id_level_user) SELECT id_menu, 1 FROM tbl_menu WHERE id_menu <> 22;
-- Dosen
INSERT INTO tbl_user_rule (id_menu, id_level_user) VALUES (1,2),(9,2),(12,2),(13,2),(14,2),(15,2),(22,2);
-- Mahasiswa
INSERT INTO tbl_user_rule (id_menu, id_level_user) VALUES (1,3),(9,3),(11,3),(14,3),(15,3);

-- ---------------------------------------------------------
-- INFO KAMPUS
-- ---------------------------------------------------------
DROP TABLE IF EXISTS tbl_kampus_info;
CREATE TABLE tbl_kampus_info (
  id_kampus     INT PRIMARY KEY,
  nama_kampus   VARCHAR(100) NOT NULL,
  kode_pt       VARCHAR(20),
  alamat        TEXT,
  telpon        VARCHAR(20),
  email         VARCHAR(100),
  website       VARCHAR(100),
  pimpinan      VARCHAR(100),   -- Rektor / Ketua
  jabatan_pimpinan VARCHAR(50) DEFAULT 'Rektor',
  nip_pimpinan  VARCHAR(30)
) ENGINE=InnoDB;
INSERT INTO tbl_kampus_info VALUES
(1, 'Universitas Nusantara', '041234', 'Jl. Pendidikan No. 1, Jakarta', '021-1234567', 'info@unusantara.ac.id',
 'www.unusantara.ac.id', 'Prof. Dr. H. Ahmad Fauzi, M.Sc', 'Rektor', '196501011990031001');

-- ---------------------------------------------------------
-- DATA MASTER
-- ---------------------------------------------------------
DROP TABLE IF EXISTS tbl_prodi;
CREATE TABLE tbl_prodi (
  kd_prodi     VARCHAR(10) PRIMARY KEY,
  nama_prodi   VARCHAR(100) NOT NULL,
  jenjang      ENUM('D3','D4','S1','S2','S3') NOT NULL DEFAULT 'S1',
  fakultas     VARCHAR(100),
  ketua_prodi  VARCHAR(100),
  nidn_kaprodi VARCHAR(30)
) ENGINE=InnoDB;
INSERT INTO tbl_prodi VALUES
('TI', 'Teknik Informatika',     'S1', 'Fakultas Ilmu Komputer', 'Ahmad Rizki, M.T',      '0412078501'),
('SI', 'Sistem Informasi',       'S1', 'Fakultas Ilmu Komputer', 'Siti Aminah, M.Kom',    '0420088802'),
('MI', 'Manajemen Informatika',  'D3', 'Fakultas Ilmu Komputer', 'Dr. Budi Santoso, M.Kom','0401018001');

DROP TABLE IF EXISTS tbl_ruangan;
CREATE TABLE tbl_ruangan (
  kd_ruangan   VARCHAR(10) PRIMARY KEY,
  nama_ruangan VARCHAR(50) NOT NULL,
  kapasitas    INT DEFAULT 40
) ENGINE=InnoDB;
INSERT INTO tbl_ruangan VALUES
('A101', 'Gedung A Ruang 101', 40),
('A102', 'Gedung A Ruang 102', 40),
('LAB1', 'Laboratorium Komputer 1', 35);

DROP TABLE IF EXISTS tbl_tahun_akademik;
CREATE TABLE tbl_tahun_akademik (
  id_tahun_akademik INT AUTO_INCREMENT PRIMARY KEY,
  tahun_akademik    VARCHAR(10) NOT NULL,          -- 2025/2026
  semester          ENUM('Ganjil','Genap') NOT NULL,
  is_aktif          ENUM('y','n') NOT NULL DEFAULT 'n'
) ENGINE=InnoDB;
INSERT INTO tbl_tahun_akademik (id_tahun_akademik, tahun_akademik, semester, is_aktif) VALUES
(1, '2024/2025', 'Ganjil', 'n'),
(2, '2024/2025', 'Genap',  'n'),
(3, '2025/2026', 'Ganjil', 'y');

DROP TABLE IF EXISTS tbl_dosen;
CREATE TABLE tbl_dosen (
  id_dosen      INT AUTO_INCREMENT PRIMARY KEY,
  nidn          VARCHAR(20),
  nama_dosen    VARCHAR(100) NOT NULL,
  gender        ENUM('L','P') NOT NULL,
  kd_prodi      VARCHAR(10),
  tempat_lahir  VARCHAR(50),
  tanggal_lahir DATE,
  alamat        TEXT,
  telpon        VARCHAR(20),
  email         VARCHAR(100)
) ENGINE=InnoDB;
INSERT INTO tbl_dosen (id_dosen, nidn, nama_dosen, gender, kd_prodi, tempat_lahir, tanggal_lahir, alamat, telpon, email) VALUES
(1, '0401018001', 'Dr. Budi Santoso, M.Kom', 'L', 'TI', 'Bandung', '1980-01-01', 'Jl. Merdeka 10', '081234567890', 'budi@unusantara.ac.id'),
(2, '0420088802', 'Siti Aminah, M.Kom',      'P', 'SI', 'Bogor',   '1988-08-20', 'Jl. Kenanga 5',  '081298765432', 'siti@unusantara.ac.id'),
(3, '0412078501', 'Ahmad Rizki, M.T',        'L', 'TI', 'Depok',   '1985-07-12', 'Jl. Flamboyan 7','081311112222', 'rizki@unusantara.ac.id');

DROP TABLE IF EXISTS tbl_mahasiswa;
CREATE TABLE tbl_mahasiswa (
  nim           VARCHAR(20) PRIMARY KEY,
  nama          VARCHAR(100) NOT NULL,
  gender        ENUM('L','P') NOT NULL,
  tempat_lahir  VARCHAR(50),
  tanggal_lahir DATE,
  agama         VARCHAR(20),
  alamat        TEXT,
  telpon        VARCHAR(20),
  email         VARCHAR(100),
  nama_ortu     VARCHAR(100),
  kd_prodi      VARCHAR(10) NOT NULL,
  angkatan      YEAR NOT NULL,
  id_dosen_wali INT,
  status        ENUM('Aktif','Cuti','Lulus','Keluar') NOT NULL DEFAULT 'Aktif',
  foto          VARCHAR(100)
) ENGINE=InnoDB;
INSERT INTO tbl_mahasiswa (nim, nama, gender, tempat_lahir, tanggal_lahir, agama, alamat, telpon, email, nama_ortu, kd_prodi, angkatan, id_dosen_wali, status) VALUES
('2024001', 'Andi Pratama',  'L', 'Jakarta', '2005-01-15', 'Islam',   'Jl. Melati 1',  '0811111111', 'andi@student.unusantara.ac.id',  'Slamet',  'TI', 2024, 1, 'Aktif'),
('2024002', 'Bunga Lestari', 'P', 'Depok',   '2005-03-22', 'Islam',   'Jl. Mawar 2',   '0812222222', 'bunga@student.unusantara.ac.id', 'Hendra',  'TI', 2024, 1, 'Aktif'),
('2025001', 'Citra Dewi',    'P', 'Bekasi',  '2007-07-30', 'Kristen', 'Jl. Anggrek 3', '0813333333', 'citra@student.unusantara.ac.id', 'Yohanes', 'SI', 2025, 2, 'Aktif'),
('2025002', 'Dedi Kurniawan','L', 'Bogor',   '2007-02-11', 'Islam',   'Jl. Dahlia 4',  '0814444444', 'dedi@student.unusantara.ac.id',  'Sutrisno','TI', 2025, 3, 'Aktif');

DROP TABLE IF EXISTS tbl_matakuliah;
CREATE TABLE tbl_matakuliah (
  kd_mk     VARCHAR(10) PRIMARY KEY,
  nama_mk   VARCHAR(100) NOT NULL,
  sks       TINYINT NOT NULL DEFAULT 3,
  semester  TINYINT NOT NULL DEFAULT 1,
  jenis     ENUM('Wajib','Pilihan') NOT NULL DEFAULT 'Wajib',
  kd_prodi  VARCHAR(10) NOT NULL
) ENGINE=InnoDB;
INSERT INTO tbl_matakuliah VALUES
('TI101', 'Algoritma dan Pemrograman', 4, 1, 'Wajib', 'TI'),
('TI102', 'Matematika Diskrit',        3, 1, 'Wajib', 'TI'),
('TI103', 'Pengantar Teknologi Informasi', 2, 1, 'Wajib', 'TI'),
('TI201', 'Struktur Data',             4, 2, 'Wajib', 'TI'),
('TI202', 'Basis Data',                3, 2, 'Wajib', 'TI'),
('TI203', 'Statistika',                3, 2, 'Wajib', 'TI'),
('TI301', 'Pemrograman Web',           3, 3, 'Wajib', 'TI'),
('TI302', 'Jaringan Komputer',         3, 3, 'Wajib', 'TI'),
('TI303', 'Sistem Operasi',            3, 3, 'Wajib', 'TI'),
('TI304', 'Interaksi Manusia dan Komputer', 2, 3, 'Pilihan', 'TI'),
('SI101', 'Pengantar Sistem Informasi',3, 1, 'Wajib', 'SI'),
('SI102', 'Logika dan Algoritma',      3, 1, 'Wajib', 'SI'),
('MI101', 'Aplikasi Perkantoran',      2, 1, 'Wajib', 'MI');

-- ---------------------------------------------------------
-- AKADEMIK
-- ---------------------------------------------------------
DROP TABLE IF EXISTS tbl_jadwal;
CREATE TABLE tbl_jadwal (
  id_jadwal         INT AUTO_INCREMENT PRIMARY KEY,
  id_tahun_akademik INT NOT NULL,
  kd_mk             VARCHAR(10) NOT NULL,
  kelas             VARCHAR(5) NOT NULL DEFAULT 'A',
  id_dosen          INT NOT NULL,
  kd_ruangan        VARCHAR(10) NOT NULL,
  hari              ENUM('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
  jam_mulai         TIME NOT NULL,
  jam_selesai       TIME NOT NULL,
  kuota             INT NOT NULL DEFAULT 40
) ENGINE=InnoDB;
INSERT INTO tbl_jadwal (id_jadwal, id_tahun_akademik, kd_mk, kelas, id_dosen, kd_ruangan, hari, jam_mulai, jam_selesai, kuota) VALUES
-- 2024/2025 Ganjil
(1, 1, 'TI101', 'A', 1, 'LAB1', 'Senin',  '08:00', '11:20', 35),
(2, 1, 'TI102', 'A', 3, 'A101', 'Selasa', '08:00', '10:30', 40),
(3, 1, 'TI103', 'A', 1, 'A102', 'Rabu',   '10:00', '11:40', 40),
-- 2024/2025 Genap
(4, 2, 'TI201', 'A', 1, 'LAB1', 'Senin',  '08:00', '11:20', 35),
(5, 2, 'TI202', 'A', 3, 'A101', 'Selasa', '08:00', '10:30', 40),
(6, 2, 'TI203', 'A', 3, 'A102', 'Kamis',  '13:00', '15:30', 40),
-- 2025/2026 Ganjil (aktif)
(7,  3, 'TI301', 'A', 1, 'LAB1', 'Senin',  '08:00', '10:30', 35),
(8,  3, 'TI302', 'A', 3, 'A101', 'Selasa', '08:00', '10:30', 40),
(9,  3, 'TI303', 'A', 3, 'A102', 'Rabu',   '10:00', '12:30', 40),
(10, 3, 'TI304', 'A', 1, 'A101', 'Kamis',  '13:00', '14:40', 40),
(11, 3, 'TI101', 'A', 1, 'LAB1', 'Selasa', '13:00', '16:20', 35),
(12, 3, 'TI102', 'A', 3, 'A102', 'Kamis',  '08:00', '10:30', 40),
(13, 3, 'TI103', 'A', 1, 'A101', 'Jumat',  '08:00', '09:40', 40),
(14, 3, 'SI101', 'A', 2, 'A102', 'Senin',  '13:00', '15:30', 40),
(15, 3, 'SI102', 'A', 2, 'A101', 'Rabu',   '13:00', '15:30', 40);

-- KRS sekaligus menyimpan nilai mahasiswa per jadwal
DROP TABLE IF EXISTS tbl_krs;
CREATE TABLE tbl_krs (
  id_krs      INT AUTO_INCREMENT PRIMARY KEY,
  nim         VARCHAR(20) NOT NULL,
  id_jadwal   INT NOT NULL,
  status      ENUM('Diajukan','Disetujui') NOT NULL DEFAULT 'Diajukan',
  nilai_tugas DECIMAL(5,2) DEFAULT NULL,
  nilai_uts   DECIMAL(5,2) DEFAULT NULL,
  nilai_uas   DECIMAL(5,2) DEFAULT NULL,
  nilai_akhir DECIMAL(5,2) DEFAULT NULL,
  nilai_huruf VARCHAR(2)   DEFAULT NULL,
  bobot       DECIMAL(3,2) DEFAULT NULL,
  created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_krs (nim, id_jadwal)
) ENGINE=InnoDB;
INSERT INTO tbl_krs (nim, id_jadwal, status, nilai_tugas, nilai_uts, nilai_uas, nilai_akhir, nilai_huruf, bobot) VALUES
-- Andi: semester 1 & 2 sudah dinilai
('2024001', 1, 'Disetujui', 88, 85, 90, 87.90, 'A',  4.00),
('2024001', 2, 'Disetujui', 75, 70, 80, 75.50, 'B',  3.00),
('2024001', 3, 'Disetujui', 90, 88, 92, 90.20, 'A',  4.00),
('2024001', 4, 'Disetujui', 80, 78, 85, 81.40, 'AB', 3.50),
('2024001', 5, 'Disetujui', 70, 75, 72, 72.30, 'B',  3.00),
('2024001', 6, 'Disetujui', 65, 60, 70, 65.50, 'BC', 2.50),
-- Andi: semester 3 (aktif) sudah disetujui dosen wali
('2024001', 7, 'Disetujui', 85, 80, NULL, NULL, NULL, NULL),
('2024001', 8, 'Disetujui', NULL, NULL, NULL, NULL, NULL, NULL),
('2024001', 9, 'Disetujui', NULL, NULL, NULL, NULL, NULL, NULL),
-- Bunga: semester 1 & 2
('2024002', 1, 'Disetujui', 80, 75, 78, 77.70, 'B',  3.00),
('2024002', 2, 'Disetujui', 82, 80, 85, 82.60, 'AB', 3.50),
('2024002', 4, 'Disetujui', 70, 68, 72, 70.20, 'B',  3.00),
('2024002', 5, 'Disetujui', 88, 85, 90, 87.90, 'A',  4.00),
-- Bunga: KRS semester aktif masih menunggu persetujuan dosen wali
('2024002', 7, 'Diajukan', NULL, NULL, NULL, NULL, NULL, NULL),
('2024002', 8, 'Diajukan', NULL, NULL, NULL, NULL, NULL, NULL),
-- Mahasiswa baru 2025
('2025002', 11, 'Disetujui', NULL, NULL, NULL, NULL, NULL, NULL),
('2025002', 12, 'Disetujui', NULL, NULL, NULL, NULL, NULL, NULL),
('2025001', 14, 'Disetujui', NULL, NULL, NULL, NULL, NULL, NULL),
('2025001', 15, 'Disetujui', NULL, NULL, NULL, NULL, NULL, NULL);

SET FOREIGN_KEY_CHECKS = 1;
