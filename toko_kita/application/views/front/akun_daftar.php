<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="auth-wrap">
  <div class="auth-card">
    <h2>Daftar Akun</h2>
    <p class="text-muted" style="margin-bottom:22px">Sudah punya akun? <a href="<?= site_url('akun/login?redirect=' . rawurlencode($redirect)) ?>">Masuk</a></p>
    <?= form_open('akun/daftar') ?>
      <input type="hidden" name="redirect" value="<?= html_escape($redirect) ?>">
      <div class="form-group"><label>Nama Lengkap</label><input name="nama" class="form-control" required></div>
      <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" required></div>
      <div class="form-group"><label>No. HP / WhatsApp</label><input name="telpon" class="form-control" inputmode="numeric" placeholder="08xxxxxxxxxx" required></div>
      <div class="row">
        <div class="col-sm-6 form-group"><label>Password</label><input type="password" name="password" class="form-control" minlength="6" required></div>
        <div class="col-sm-6 form-group"><label>Ulangi Password</label><input type="password" name="konfirmasi" class="form-control" required></div>
      </div>
      <button class="btn btn-primary btn-lg btn-block">Daftar</button>
    <?= form_close() ?>
  </div>
</div>
