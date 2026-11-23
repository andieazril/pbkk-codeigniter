<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Masuk | SIAKAD <?= html_escape(info_kampus('nama_kampus')) ?></title>
  <meta content="width=device-width, initial-scale=1" name="viewport">
  <link rel="stylesheet" href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendor/font-awesome/css/font-awesome.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/siakad.css') ?>">
</head>
<body class="login-modern">
<div class="login-wrap">
  <div class="login-hero">
    <div class="brand"><span class="brand-icon"><i class="fa fa-graduation-cap"></i></span> SIAKAD</div>
    <div>
      <h1>Sistem Informasi<br>Akademik Kampus</h1>
      <p class="lead"><?= html_escape(info_kampus('nama_kampus')) ?> &mdash; kelola data mahasiswa, KRS, nilai, KHS, dan transkrip dalam satu tempat.</p>
      <ul class="features">
        <li><i class="fa fa-list-alt"></i> Pengisian KRS online &amp; perwalian dosen</li>
        <li><i class="fa fa-calendar"></i> Jadwal kuliah anti bentrok</li>
        <li><i class="fa fa-line-chart"></i> Nilai, KHS, IPK &amp; transkrip otomatis</li>
      </ul>
    </div>
    <div class="foot">&copy; <?= date('Y') ?> <?= html_escape(info_kampus('nama_kampus')) ?></div>
  </div>

  <div class="login-panel">
    <div class="login-card">
      <h2>Selamat datang 👋</h2>
      <p class="sub">Silakan masuk ke akun Anda untuk melanjutkan.</p>
      <?= show_alert() ?>
      <?= form_open('auth/login') ?>
        <label>Username</label>
        <div class="input-icon">
          <i class="fa fa-user-o"></i>
          <input type="text" name="username" class="form-control" placeholder="Masukkan username" value="<?= set_value('username') ?>" required autofocus>
        </div>
        <label>Password</label>
        <div class="input-icon">
          <i class="fa fa-lock"></i>
          <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
          <button type="button" class="toggle-pw" onclick="var p=document.getElementById('password');p.type=p.type==='password'?'text':'password';this.firstChild.className=p.type==='password'?'fa fa-eye':'fa fa-eye-slash'"><i class="fa fa-eye"></i></button>
        </div>
        <button type="submit" class="btn btn-primary btn-block btn-login">Masuk <i class="fa fa-arrow-right"></i></button>
      <?= form_close() ?>
      <div class="demo">
        <b>Akun demo</b> (password <code>123456</code>):<br>
        <code>admin</code> &middot; <code>dosen</code> &middot; <code>mahasiswa</code>
      </div>
    </div>
  </div>
</div>
</body>
</html>
