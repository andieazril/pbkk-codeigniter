<?php defined('BASEPATH') OR exit('No direct script access allowed');
$v = function ($f) use ($row) { return $row ? html_escape($row->$f) : ''; };
$thn = array(); for ($y = (int) date('Y') + 1; $y >= (int) date('Y') - 8; $y--) $thn[$y] = $y; ?>
<div class="box box-primary">
  <div class="box-header with-border"><h3 class="box-title"><?= $row ? 'Edit' : 'Tambah' ?> Data Mahasiswa</h3></div>
  <?= form_open_multipart('mahasiswa/save') ?>
  <input type="hidden" name="_id" value="<?= $v('nim') ?>">
  <div class="box-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group"><label>NIM <span class="text-red">*</span></label>
          <input name="nim" class="form-control" value="<?= $v('nim') ?>" <?= $row ? 'readonly' : '' ?> required></div>
        <div class="form-group"><label>Nama Lengkap <span class="text-red">*</span></label><input name="nama" class="form-control" value="<?= $v('nama') ?>" required></div>
        <div class="row">
          <div class="col-sm-6 form-group"><label>Jenis Kelamin <span class="text-red">*</span></label>
            <?= cmb_array('gender', array('L' => 'Laki-laki', 'P' => 'Perempuan'), $row ? $row->gender : '', 'required') ?></div>
          <div class="col-sm-6 form-group"><label>Agama</label>
            <?= cmb_array('agama', array_combine($a = array('Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'), $a), $row ? $row->agama : '') ?></div>
        </div>
        <div class="row">
          <div class="col-sm-6 form-group"><label>Tempat Lahir</label><input name="tempat_lahir" class="form-control" value="<?= $v('tempat_lahir') ?>"></div>
          <div class="col-sm-6 form-group"><label>Tanggal Lahir</label><input type="date" name="tanggal_lahir" class="form-control" value="<?= $v('tanggal_lahir') ?>"></div>
        </div>
        <div class="row">
          <div class="col-sm-6 form-group"><label>No. Telepon</label><input name="telpon" class="form-control" value="<?= $v('telpon') ?>"></div>
          <div class="col-sm-6 form-group"><label>Email</label><input type="email" name="email" class="form-control" value="<?= $v('email') ?>"></div>
        </div>
        <div class="form-group"><label>Alamat</label><textarea name="alamat" class="form-control" rows="2"><?= $v('alamat') ?></textarea></div>
      </div>
      <div class="col-md-6">
        <div class="form-group"><label>Program Studi <span class="text-red">*</span></label>
          <?= cmb_array('kd_prodi', $prodi, $row ? $row->kd_prodi : '', 'class="select2" required') ?></div>
        <div class="row">
          <div class="col-sm-6 form-group"><label>Angkatan <span class="text-red">*</span></label>
            <?= cmb_array('angkatan', $thn, $row ? $row->angkatan : date('Y'), 'required') ?></div>
          <div class="col-sm-6 form-group"><label>Status <span class="text-red">*</span></label>
            <?= cmb_array('status', array('Aktif' => 'Aktif', 'Cuti' => 'Cuti', 'Lulus' => 'Lulus', 'Keluar' => 'Keluar'), $row ? $row->status : 'Aktif', 'required') ?></div>
        </div>
        <div class="form-group"><label>Dosen Wali (Pembimbing Akademik)</label>
          <?= cmb_array('id_dosen_wali', $dosen, $row ? $row->id_dosen_wali : '', 'class="select2"') ?></div>
        <div class="form-group"><label>Nama Orang Tua / Wali</label><input name="nama_ortu" class="form-control" value="<?= $v('nama_ortu') ?>"></div>
        <div class="form-group"><label>Foto (jpg/png, maks 1MB)</label>
          <?php if ($row && $row->foto): ?><br><img src="<?= base_url('uploads/foto/' . $row->foto) ?>" class="foto-mhs"><br><br><?php endif; ?>
          <input type="file" name="foto" accept="image/png,image/jpeg"></div>
        <?php if (!$row): ?>
        <div class="checkbox"><label><input type="checkbox" name="buat_akun" value="1" checked> Buatkan akun login (username &amp; password = NIM)</label></div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <div class="box-footer">
    <a href="<?= site_url('mahasiswa') ?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali</a>
    <button class="btn btn-primary pull-right"><i class="fa fa-save"></i> Simpan</button>
  </div>
  <?= form_close() ?>
</div>
