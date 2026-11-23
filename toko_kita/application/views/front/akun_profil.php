<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-title"><h1>Profil Saya</h1></div>
<div class="row">
  <div class="col-md-3"><?php $this->load->view('front/_akun_nav'); ?></div>
  <div class="col-md-9">
    <div class="panel-card">
      <h4>Data Diri &amp; Alamat Pengiriman</h4>
      <?= form_open('akun/simpan') ?>
        <div class="row">
          <div class="col-sm-6 form-group"><label>Nama Lengkap</label><input name="nama" class="form-control" value="<?= html_escape($p->nama) ?>" required></div>
          <div class="col-sm-6 form-group"><label>No. HP</label><input name="telpon" class="form-control" value="<?= html_escape($p->telpon) ?>" required></div>
        </div>
        <div class="form-group"><label>Email</label><input class="form-control" value="<?= html_escape($p->email) ?>" disabled></div>
        <div class="form-group"><label>Alamat Lengkap</label><textarea name="alamat" class="form-control" rows="2"><?= html_escape($p->alamat) ?></textarea></div>
        <div class="row">
          <div class="col-sm-6 form-group"><label>Kota</label>
            <select name="id_ongkir" class="form-control"><option value="">-- Pilih kota --</option>
              <?php foreach ($kota as $k): ?><option value="<?= $k->id_ongkir ?>" <?= $p->id_ongkir == $k->id_ongkir ? 'selected' : '' ?>><?= html_escape($k->kota) ?></option><?php endforeach; ?>
            </select></div>
          <div class="col-sm-6 form-group"><label>Kode Pos</label><input name="kode_pos" class="form-control" value="<?= html_escape($p->kode_pos) ?>"></div>
        </div>
        <button class="btn btn-primary"><i class="fa fa-save"></i> Simpan Profil</button>
      <?= form_close() ?>
    </div>
    <div class="panel-card">
      <h4>Ganti Password</h4>
      <?= form_open('akun/password') ?>
        <div class="row">
          <div class="col-sm-4 form-group"><label>Password Lama</label><input type="password" name="password_lama" class="form-control" required></div>
          <div class="col-sm-4 form-group"><label>Password Baru</label><input type="password" name="password_baru" class="form-control" minlength="6" required></div>
          <div class="col-sm-4 form-group"><label>Ulangi Password Baru</label><input type="password" name="konfirmasi" class="form-control" required></div>
        </div>
        <button class="btn btn-default"><i class="fa fa-key"></i> Ganti Password</button>
      <?= form_close() ?>
    </div>
  </div>
</div>
