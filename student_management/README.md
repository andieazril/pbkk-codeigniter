# Latihan 1 : Implementasi CodeIgniter

Latihan 1 sebenarnya berisi materi tutorial CodeIgniter, tetapi saya menerapkannya langsung dalam satu aplikasi yaitu
Student Management System.

| | |
|---|---|
| Framework | CodeIgniter 3.1.13 |
| Template | SB Admin 2 (Bootstrap 4) |
| Database | MySQL, database `student_management` |
| Login | `admin` / `123456` |

Daftar materi:

1. [Instalasi dan Konfigurasi CI](#1-instalasi-dan-konfigurasi-ci)
2. [Fungsi Router dan Controller](#2-fungsi-router-dan-controller)
3. [Menggunakan Bootstrap untuk View Aplikasi](#3-menggunakan-bootstrap-untuk-view-aplikasi)
4. [Menggunakan Template Bootstrap SBAdmin2](#4-menggunakan-template-bootstrap-sbadmin2)
5. [Mengenal Model dan CRUD](#5-mengenal-model-dan-crud)
6. [Penanganan File Upload](#6-penanganan-file-upload)
7. [Mengatur Akses User](#7-mengatur-akses-user)

## 1. Instalasi dan Konfigurasi CI

Pada latihan 1 saya telah membuat proyek CodeIgniter bernama `student_management` yang digunakan sebagai dasar
pengembangan aplikasi Student Management System.

Proyek telah dijalankan melalui localhost dan menampilkan halaman aplikasi. Konfigurasi awal juga dilakukan pada routing
dan view sehingga aplikasi dapat menampilkan halaman utama.

Pengaturan base URL ada di `application/config/config.php`, sedangkan library dan helper yang dimuat otomatis diatur di
`application/config/autoload.php`.

```php
$config['base_url']   = 'http://localhost/student_management/';
$config['index_page'] = 'index.php';

$autoload['libraries'] = array('database');
$autoload['helper']    = array('url', 'form');
```

Saat `http://localhost/student_management/` dibuka, aplikasi menampilkan halaman login karena pengguna belum login.

![Aplikasi berjalan di localhost](docs/screenshots/01-halaman-login.png)

## 2. Fungsi Router dan Controller

### Routing

Routing digunakan untuk menentukan controller yang dijalankan ketika pengguna mengakses URL tertentu. Pengaturan routing
dilakukan pada `application/config/routes.php`.

Pada aplikasi ini, controller Mahasiswa dijadikan halaman default dan Dashboard digunakan untuk mengakses halaman
dashboard. Selain itu ada route untuk login dan logout.

```php
$route['default_controller'] = 'mahasiswa';
$route['dashboard'] = 'dashboard';
$route['mahasiswa'] = 'mahasiswa';
$route['login']     = 'login';
$route['logout']    = 'login/logout';
```

Daftar URL yang digunakan pada aplikasi:

| URL | Controller | Keterangan |
|---|---|---|
| `index.php/dashboard` | `Dashboard::index()` | Halaman dashboard |
| `index.php/mahasiswa` | `Mahasiswa::index()` | Tabel data mahasiswa |
| `index.php/mahasiswa/tambah` | `Mahasiswa::tambah()` | Form tambah data |
| `index.php/mahasiswa/edit/{id}` | `Mahasiswa::edit($id)` | Form edit data |
| `index.php/mahasiswa/hapus/{id}` | `Mahasiswa::hapus($id)` | Hapus data |
| `index.php/login` | `Login::index()` | Halaman login |
| `index.php/logout` | `Login::logout()` | Keluar dari aplikasi |

### Controller

Controller digunakan untuk mengatur alur aplikasi dan menghubungkan model dengan view. Pada aplikasi ini terdapat
controller Dashboard, Mahasiswa, dan Login.

Controller Mahasiswa memiliki fungsi untuk menampilkan, menambah, mengedit, dan menghapus data mahasiswa, yaitu
`index()`, `tambah()`, `edit($id)`, dan `hapus($id)`. Contohnya fungsi `index()` berikut mengambil data dari model lalu
mengirimkannya ke view.

```php
public function index()
{
    $this->load->model('Mahasiswa_model');

    $data['mahasiswa'] = $this->Mahasiswa_model->get_all();
    $data['title'] = 'Data Mahasiswa';

    $this->load->view('templates/header', $data);
    $this->load->view('mahasiswa', $data);
    $this->load->view('templates/footer');
}
```

## 3. Menggunakan Bootstrap untuk View Aplikasi

View digunakan untuk menampilkan antarmuka aplikasi kepada pengguna. Aplikasi ini menggunakan beberapa view berikut.

| File view | Fungsi |
|---|---|
| `templates/header.php` | Bagian atas halaman, sidebar, dan topbar |
| `templates/footer.php` | Bagian bawah halaman dan file JavaScript |
| `dashboard.php` | Halaman dashboard |
| `mahasiswa.php` | Tabel data mahasiswa |
| `tambah_mahasiswa.php` | Form tambah mahasiswa |
| `edit_mahasiswa.php` | Form edit mahasiswa |
| `login.php` | Halaman login |

Header dan footer digunakan sebagai template bersama agar struktur halaman dan navigasi tetap konsisten. Setiap halaman
memanggil header, kemudian isi halaman, lalu footer.

Dashboard digunakan sebagai halaman utama yang menampilkan informasi aplikasi dan navigasi menuju pengelolaan data
mahasiswa.

![Dashboard](docs/screenshots/02-dashboard.png)

## 4. Menggunakan Template Bootstrap SBAdmin2

SBAdmin2 digunakan untuk memperbaiki tampilan aplikasi dengan menyediakan sidebar, navbar, card, tabel, dan komponen
antarmuka lainnya.

Template ditempatkan pada folder `assets/sbadmin` dan dihubungkan melalui file header dan footer.

Contoh pemanggilan CSS SBAdmin2 di `templates/header.php`:

```php
<link href="<?= base_url('assets/sbadmin/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
<link href="<?= base_url('assets/sbadmin/css/sb-admin-2.min.css') ?>" rel="stylesheet">
```

Dan pemanggilan JavaScript di `templates/footer.php`:

```php
<script src="<?= base_url('assets/sbadmin/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/sbadmin/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/sbadmin/js/sb-admin-2.min.js') ?>"></script>
```

Template kemudian diterapkan pada halaman Dashboard dan Data Mahasiswa agar tampilan aplikasi lebih terstruktur dan
konsisten.

![Template SB Admin 2 pada halaman Data Mahasiswa](docs/screenshots/03-sbadmin-data-mahasiswa.png)

## 5. Mengenal Model dan CRUD

Model digunakan untuk mengelola interaksi aplikasi dengan database. Pada aplikasi ini digunakan `Mahasiswa_model` yang
terhubung dengan tabel `mahasiswa` pada database `student_management`.

### Konfigurasi Database

Database dikonfigurasi melalui `application/config/database.php`. MySQL di XAMPP saya berjalan di port 3310.

```php
$db['default'] = array(
    'hostname' => '127.0.0.1',
    'port'     => 3310,
    'username' => 'root',
    'password' => '',
    'database' => 'student_management',
    'dbdriver' => 'mysqli',
    // ...
);
```

Database terdiri dari dua tabel. File SQL-nya ada di `database/student_management.sql`.

| Tabel | Kolom |
|---|---|
| `mahasiswa` | `id`, `nim`, `nama`, `jurusan`, `foto` |
| `users` | `id`, `username`, `password` |

### Model Mahasiswa

Model Mahasiswa_model digunakan untuk mengambil, menambahkan, mengubah, dan menghapus data mahasiswa melalui fungsi
`get_all()`, `get_by_id()`, `insert()`, `update()`, dan `delete()`.

```php
class Mahasiswa_model extends CI_Model {

    public function get_all()
    {
        return $this->db->get('mahasiswa')->result();
    }

    public function insert($data)
    {
        return $this->db->insert('mahasiswa', $data);
    }

    public function get_by_id($id)
    {
        return $this->db->get_where('mahasiswa', array('id' => $id))->row();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('mahasiswa', $data);
    }

    public function delete($id)
    {
        return $this->db->delete('mahasiswa', array('id' => $id));
    }
}
```

### Create

Create digunakan untuk menambahkan data mahasiswa baru melalui form yang berisi NIM, nama, dan jurusan. Data yang dikirim
akan diproses oleh controller dan disimpan ke database melalui model.

![Form tambah mahasiswa](docs/screenshots/04-create-form-tambah.png)

Sebelum disimpan, data dicek menggunakan form validation. Jika ada data yang kosong, akan muncul pesan kesalahan.

```php
$this->form_validation->set_rules('nim', 'NIM', 'required');
$this->form_validation->set_rules('nama', 'Nama', 'required');
$this->form_validation->set_rules('jurusan', 'Jurusan', 'required');
```

![Validasi form tambah](docs/screenshots/05-create-validasi.png)

### Read

Read digunakan untuk mengambil data mahasiswa dari database dan menampilkannya dalam bentuk tabel pada halaman Data
Mahasiswa.

![Data mahasiswa](docs/screenshots/06-read-data-mahasiswa.png)

### Update

Update digunakan untuk mengubah data mahasiswa yang telah tersimpan. Form edit menampilkan data sebelumnya sehingga
pengguna dapat melakukan perubahan.

![Form edit mahasiswa](docs/screenshots/07-update-form-edit.png)

### Delete

Delete digunakan untuk menghapus data mahasiswa melalui tombol Hapus yang tersedia pada tabel. Sebelum data dihapus
akan muncul konfirmasi terlebih dahulu. Jika mahasiswa memiliki foto, file fotonya juga ikut dihapus.

```php
public function hapus($id)
{
    $this->load->model('Mahasiswa_model');

    $mahasiswa = $this->Mahasiswa_model->get_by_id($id);

    if ($mahasiswa && $mahasiswa->foto != '')
    {
        unlink(FCPATH . 'assets/uploads/mahasiswa/' . $mahasiswa->foto);
    }

    $this->Mahasiswa_model->delete($id);

    redirect('mahasiswa');
}
```

Berikut tampilan tabel setelah data Citra Dewi dihapus.

![Data setelah dihapus](docs/screenshots/08-delete-data-terhapus.png)

## 6. Penanganan File Upload

File upload digunakan untuk mengunggah dan menyimpan foto mahasiswa pada aplikasi.

Folder `assets/uploads/mahasiswa` dibuat sebagai tempat penyimpanan foto. Form tambah dan edit mahasiswa dibuat dengan
`form_open_multipart()` yang otomatis menambahkan atribut `enctype="multipart/form-data"` agar file dapat dikirim ke
server.

```php
<?= form_open_multipart('mahasiswa/tambah') ?>

<input type="file" name="foto" class="form-control">
```

Controller menangani proses upload menggunakan library upload CodeIgniter. File yang diperbolehkan hanya JPG, JPEG, dan
PNG dengan ukuran maksimal 2 MB, dan nama file diacak agar tidak bentrok.

```php
$config['upload_path']   = FCPATH . 'assets/uploads/mahasiswa/';
$config['allowed_types'] = 'jpg|jpeg|png';
$config['max_size']      = 2048;
$config['encrypt_name']  = TRUE;

$this->load->library('upload', $config);
```

File yang berhasil diunggah disimpan pada folder `assets/uploads/mahasiswa`, sedangkan nama file disimpan pada kolom
`foto` di database. Foto kemudian ditampilkan pada tabel Data Mahasiswa melalui `base_url()`.

```php
<img src="<?= base_url('assets/uploads/mahasiswa/' . $m->foto) ?>" width="60" height="60">
```

![Foto pada tabel data mahasiswa](docs/screenshots/09-upload-foto-tabel.png)

## 7. Mengatur Akses User

Pengaturan akses user diterapkan melalui sistem login untuk membatasi akses terhadap aplikasi.

### Login

Halaman login digunakan untuk memasukkan username dan password. Data akun pengguna disimpan pada tabel `users` di
database, dan password disimpan dalam bentuk hash.

![Halaman login](docs/screenshots/01-halaman-login.png)

### Autentikasi dan Session

Autentikasi dilakukan dengan memeriksa username dan password pengguna di `User_model`. Password dicocokkan menggunakan
`password_verify()`.

```php
public function login($username, $password)
{
    $user = $this->db->get_where('users', array(
        'username' => $username
    ))->row();

    if ($user && password_verify($password, $user->password))
    {
        return $user;
    }

    return FALSE;
}
```

Jika berhasil, aplikasi membuat session yang digunakan untuk mengidentifikasi pengguna yang telah login.

```php
$this->session->set_userdata('logged_in', TRUE);
$this->session->set_userdata('username', $user->username);
```

Jika username atau password salah, akan muncul pesan kesalahan.

![Login gagal](docs/screenshots/10-login-gagal.png)

Session juga digunakan untuk membatasi akses ke halaman Dashboard dan Data Mahasiswa. Pengguna yang belum login akan
diarahkan ke halaman login.

```php
if (!$this->session->userdata('logged_in'))
{
    redirect('login');
}
```

### Logout

Logout digunakan untuk mengakhiri session pengguna sehingga pengguna harus login kembali untuk mengakses aplikasi.

```php
public function logout()
{
    $this->load->library('session');

    $this->session->sess_destroy();

    redirect('login');
}
```

## Cara Menjalankan

1. Salin folder `student_management` ke `C:\xampp\htdocs`.
2. Import file `database/student_management.sql` melalui phpMyAdmin.
3. Sesuaikan port di `application/config/database.php`. Proyek ini memakai port 3310, ubah menjadi 3306 jika MySQL
   menggunakan port bawaan.
4. Buka `http://localhost/student_management/` dan login dengan username `admin` dan password `123456`.
