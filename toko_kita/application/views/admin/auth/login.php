<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8"><title>Login Admin | <?= html_escape(info_toko('nama_toko')) ?></title>
  <meta content="width=device-width, initial-scale=1" name="viewport">
  <link rel="stylesheet" href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendor/font-awesome/css/font-awesome.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
</head>
<body class="login-modern">
<div class="login-wrap">
  <div class="login-hero">
    <div class="brand"><span class="brand-icon"><i class="fa fa-shopping-bag"></i></span> <?= html_escape(info_toko('nama_toko')) ?></div>
    <div>
      <h1>Panel Admin<br>Toko Online</h1>
      <p class="lead">Kelola produk, verifikasi pembayaran, proses pesanan, dan pantau penjualan.</p>
      <ul class="features">
        <li><i class="fa fa-cubes"></i> Katalog produk &amp; stok</li>
        <li><i class="fa fa-check-square-o"></i> Verifikasi pembayaran &amp; input resi</li>
        <li><i class="fa fa-line-chart"></i> Laporan penjualan</li>
      </ul>
    </div>
    <div class="foot">&copy; <?= date('Y') ?> <?= html_escape(info_toko('nama_toko')) ?></div>
  </div>
  <div class="login-panel">
    <div class="login-card">
      <h2>Masuk Admin</h2>
      <p class="sub">Gunakan akun administrator toko.</p>
      <?= show_alert() ?>
      <?= form_open('admin/auth/login') ?>
        <label>Username</label>
        <div class="input-icon"><i class="fa fa-user-o"></i><input type="text" name="username" class="form-control" placeholder="Username" required autofocus></div>
        <label>Password</label>
        <div class="input-icon"><i class="fa fa-lock"></i><input type="password" name="password" class="form-control" placeholder="Password" required></div>
        <button type="submit" class="btn btn-primary btn-block btn-login">Masuk <i class="fa fa-arrow-right"></i></button>
      <?= form_close() ?>
      <div class="demo"><b>Akun demo:</b> <code>admin</code> / <code>123456</code> &middot; <a href="<?= site_url() ?>">&larr; ke toko</a></div>
    </div>
  </div>
</div>
</body>
</html>
