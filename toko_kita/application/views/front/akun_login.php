<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="auth-wrap">
  <div class="auth-card">
    <h2>Masuk</h2>
    <p class="text-muted" style="margin-bottom:22px">Belum punya akun? <a href="<?= site_url('akun/daftar?redirect=' . rawurlencode($redirect)) ?>">Daftar gratis</a></p>
    <?= form_open('akun/login') ?>
      <input type="hidden" name="redirect" value="<?= html_escape($redirect) ?>">
      <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" placeholder="nama@email.com" required autofocus></div>
      <div class="form-group"><label>Password</label><input type="password" name="password" class="form-control" placeholder="••••••" required></div>
      <button class="btn btn-primary btn-lg btn-block">Masuk</button>
    <?= form_close() ?>
    <p class="text-muted text-center" style="margin:18px 0 0;font-size:12.5px">Akun demo: <code>budi@contoh.com</code> / <code>123456</code></p>
  </div>
</div>
