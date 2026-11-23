<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="box box-primary">
  <div class="box-header with-border"><h3 class="box-title"><?= $row ? 'Edit' : 'Tambah' ?> User</h3></div>
  <?= form_open('users/save', array('class' => 'form-horizontal')) ?>
  <input type="hidden" name="id_user" value="<?= $row ? $row->id_user : '' ?>">
  <div class="box-body">
    <div class="form-group"><label class="col-sm-3 control-label">Nama Lengkap</label>
      <div class="col-sm-6"><input name="nama_lengkap" class="form-control" value="<?= $row ? html_escape($row->nama_lengkap) : '' ?>" required></div></div>
    <div class="form-group"><label class="col-sm-3 control-label">Username</label>
      <div class="col-sm-6"><input name="username" class="form-control" value="<?= $row ? html_escape($row->username) : '' ?>" required></div></div>
    <div class="form-group"><label class="col-sm-3 control-label">Password</label>
      <div class="col-sm-6"><input type="password" name="password" class="form-control" minlength="6" <?= $row ? '' : 'required' ?>>
        <?php if ($row): ?><span class="help-block">Kosongkan jika tidak ingin mengubah password.</span><?php endif; ?></div></div>
    <div class="form-group"><label class="col-sm-3 control-label">Level</label>
      <div class="col-sm-6"><?= cmb_dinamis('id_level_user', 'tbl_level_user', 'nama_level', 'id_level_user', $row ? $row->id_level_user : '', 'required', NULL, 'id_level_user') ?></div></div>
    <div class="form-group"><label class="col-sm-3 control-label">Data Dosen <small>(level Dosen)</small></label>
      <div class="col-sm-6"><?= cmb_dinamis('id_dosen', 'tbl_dosen', 'nama_dosen', 'id_dosen', $row ? $row->id_dosen : '') ?></div></div>
    <div class="form-group"><label class="col-sm-3 control-label">Data Mahasiswa <small>(level Mahasiswa)</small></label>
      <div class="col-sm-6"><?= cmb_dinamis('nim', 'tbl_mahasiswa', 'nama', 'nim', $row ? $row->nim : '') ?></div></div>
  </div>
  <div class="box-footer">
    <a href="<?= site_url('users') ?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali</a>
    <button class="btn btn-primary pull-right"><i class="fa fa-save"></i> Simpan</button>
  </div>
  <?= form_close() ?>
</div>
