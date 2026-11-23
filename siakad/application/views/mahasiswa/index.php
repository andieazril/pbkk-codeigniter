<?php defined('BASEPATH') OR exit('No direct script access allowed');
$status_cls = array('Aktif' => 'success', 'Cuti' => 'warning', 'Lulus' => 'primary', 'Keluar' => 'danger'); ?>
<div class="box box-primary">
  <div class="box-header with-border">
    <form method="get" class="form-inline">
      <a href="<?= site_url('mahasiswa/add') ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah Mahasiswa</a>
      <span class="filter-label"><i class="fa fa-filter"></i> Filter</span>
      <div class="form-group"><?= cmb_array('kd_prodi', $prodi, $filter['kd_prodi'], 'class="auto-submit"', 'Semua Prodi') ?></div>
      <div class="form-group"><?= cmb_array('angkatan', $angkatan, $filter['angkatan'], 'class="auto-submit"', 'Semua Angkatan') ?></div>
      <div class="form-group"><?= cmb_array('status', array('Aktif' => 'Aktif', 'Cuti' => 'Cuti', 'Lulus' => 'Lulus', 'Keluar' => 'Keluar'), $filter['status'], 'class="auto-submit"', 'Semua Status') ?></div>
      <div class="form-group"><?= cmb_array('id_dosen_wali', $dosen, $filter['id_dosen_wali'], 'class="auto-submit"', 'Semua Dosen Wali') ?></div>
      <a href="<?= site_url('mahasiswa') ?>" class="btn btn-default btn-sm">Reset</a>
    </form>
  </div>
  <div class="box-body table-responsive">
    <table class="table table-bordered table-striped datatable">
      <thead><tr><th>No</th><th>NIM</th><th>Nama</th><th>L/P</th><th>Program Studi</th><th>Angkatan</th><th>Dosen Wali</th><th>Status</th><th width="120">Aksi</th></tr></thead>
      <tbody>
      <?php $no = 1; foreach ($rows as $r): ?>
        <tr>
          <td><?= $no++ ?></td>
          <td><b><?= html_escape($r->nim) ?></b></td>
          <td><?= html_escape($r->nama) ?></td>
          <td><?= $r->gender ?></td>
          <td><?= html_escape($r->jenjang . ' ' . $r->nama_prodi) ?></td>
          <td><?= $r->angkatan ?></td>
          <td><?= html_escape($r->dosen_wali ?: '-') ?></td>
          <td><span class="label label-<?= $status_cls[$r->status] ?>"><?= $r->status ?></span></td>
          <td>
            <a href="<?= site_url('mahasiswa/detail/' . $r->nim) ?>" class="btn btn-xs btn-info" title="Detail"><i class="fa fa-eye"></i></a>
            <?= btn_aksi('mahasiswa', $r->nim) ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
