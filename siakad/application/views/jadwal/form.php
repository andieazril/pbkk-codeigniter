<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="box box-primary">
  <div class="box-header with-border"><h3 class="box-title"><?= $row->id_jadwal ? 'Edit' : 'Tambah' ?> Jadwal Kuliah</h3></div>
  <?= form_open('jadwal/save', array('class' => 'form-horizontal')) ?>
  <input type="hidden" name="id_jadwal" value="<?= $row->id_jadwal ?>">
  <div class="box-body">
    <div class="form-group"><label class="col-sm-3 control-label">Tahun Akademik</label>
      <div class="col-sm-6"><?= cmb_array('id_tahun_akademik', $ta_opt, $row->id_tahun_akademik, 'required') ?></div></div>
    <div class="form-group"><label class="col-sm-3 control-label">Mata Kuliah</label>
      <div class="col-sm-6"><?= cmb_array('kd_mk', $mk_opt, $row->kd_mk, 'class="select2" required') ?></div></div>
    <div class="form-group"><label class="col-sm-3 control-label">Kelas</label>
      <div class="col-sm-2"><input name="kelas" class="form-control" value="<?= html_escape($row->kelas) ?>" maxlength="5" required></div>
      <label class="col-sm-2 control-label">Kuota</label>
      <div class="col-sm-2"><input type="number" name="kuota" class="form-control" value="<?= (int) $row->kuota ?>" min="1" required></div></div>
    <div class="form-group"><label class="col-sm-3 control-label">Dosen Pengampu</label>
      <div class="col-sm-6"><?= cmb_array('id_dosen', $dosen_opt, $row->id_dosen, 'class="select2" required') ?></div></div>
    <div class="form-group"><label class="col-sm-3 control-label">Ruangan</label>
      <div class="col-sm-6"><?= cmb_dinamis('kd_ruangan', 'tbl_ruangan', 'nama_ruangan', 'kd_ruangan', $row->kd_ruangan, 'required') ?></div></div>
    <div class="form-group"><label class="col-sm-3 control-label">Hari</label>
      <div class="col-sm-6"><?= cmb_array('hari', array_combine($hari, $hari), $row->hari, 'required') ?></div></div>
    <div class="form-group"><label class="col-sm-3 control-label">Jam</label>
      <div class="col-sm-3"><input type="time" name="jam_mulai" class="form-control" value="<?= substr($row->jam_mulai, 0, 5) ?>" required></div>
      <div class="col-sm-3"><input type="time" name="jam_selesai" class="form-control" value="<?= substr($row->jam_selesai, 0, 5) ?>" required></div></div>
  </div>
  <div class="box-footer">
    <a href="<?= site_url('jadwal') ?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali</a>
    <button class="btn btn-primary pull-right"><i class="fa fa-save"></i> Simpan</button>
  </div>
  <?= form_close() ?>
</div>
