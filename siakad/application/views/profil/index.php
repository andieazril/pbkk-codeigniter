<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row">
  <div class="col-md-4">
    <div class="box">
      <div class="profile-card">
        <span class="avatar avatar-xl"><?= inisial($user->nama_lengkap) ?></span>
        <h3><?= html_escape($user->nama_lengkap) ?></h3>
        <span class="label label-primary"><?= html_escape($user->nama_level) ?></span>
      </div>
      <div class="box-body">
        <ul class="list-group list-group-unbordered" style="margin:0">
          <li class="list-group-item"><span class="text-muted">Username</span> <b class="pull-right"><?= html_escape($user->username) ?></b></li>
          <?php if ($user->nim): ?><li class="list-group-item"><span class="text-muted">NIS</span> <b class="pull-right"><?= html_escape($user->nim) ?></b></li><?php endif; ?>
          <li class="list-group-item"><span class="text-muted">Terdaftar</span> <b class="pull-right"><?= tgl_indo($user->created_at) ?></b></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <div class="box box-warning">
      <div class="box-header with-border"><h3 class="box-title">Ganti Password</h3></div>
      <?= form_open('profil/password', array('class' => 'form-horizontal')) ?>
      <div class="box-body">
        <div class="form-group"><label class="col-sm-4 control-label">Password Lama</label>
          <div class="col-sm-7"><input type="password" name="password_lama" class="form-control" required></div></div>
        <div class="form-group"><label class="col-sm-4 control-label">Password Baru</label>
          <div class="col-sm-7"><input type="password" name="password_baru" class="form-control" minlength="6" required>
            <span class="help-block">Minimal 6 karakter.</span></div></div>
        <div class="form-group"><label class="col-sm-4 control-label">Konfirmasi Password</label>
          <div class="col-sm-7"><input type="password" name="konfirmasi" class="form-control" required></div></div>
      </div>
      <div class="box-footer text-right"><button class="btn btn-primary"><i class="fa fa-save"></i> Simpan Password</button></div>
      <?= form_close() ?>
    </div>
  </div>
</div>
