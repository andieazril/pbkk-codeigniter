<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row"><div class="col-md-6">
  <div class="box box-warning">
    <div class="box-header with-border"><h3 class="box-title">Ganti Password Admin</h3></div>
    <?= form_open('admin/profil/save') ?>
    <div class="box-body">
      <div class="form-group"><label>Password Lama</label><input type="password" name="password_lama" class="form-control" required></div>
      <div class="form-group"><label>Password Baru</label><input type="password" name="password_baru" class="form-control" minlength="6" required></div>
      <div class="form-group"><label>Ulangi Password Baru</label><input type="password" name="konfirmasi" class="form-control" required></div>
    </div>
    <div class="box-footer text-right"><button class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button></div>
    <?= form_close() ?>
  </div>
</div></div>
