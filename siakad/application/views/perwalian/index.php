<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row">
  <div class="col-sm-4"><div class="stat-card stat-amber"><div class="stat-icon"><i class="fa fa-hourglass-half"></i></div>
    <div><div class="stat-value"><?= $ringkas['Diajukan'] ?></div><div class="stat-label">Menunggu persetujuan</div></div></div></div>
  <div class="col-sm-4"><div class="stat-card stat-green"><div class="stat-icon"><i class="fa fa-check"></i></div>
    <div><div class="stat-value"><?= $ringkas['Disetujui'] ?></div><div class="stat-label">KRS disetujui</div></div></div></div>
  <div class="col-sm-4"><div class="stat-card stat-rose"><div class="stat-icon"><i class="fa fa-exclamation"></i></div>
    <div><div class="stat-value"><?= $ringkas['Kosong'] ?></div><div class="stat-label">Belum mengisi KRS</div></div></div></div>
</div>
<div class="box box-primary">
  <div class="box-header with-border">
    <?php if ($dosen_opt): ?>
      <form method="get" class="form-inline"><span class="filter-label" style="margin-left:0"><i class="fa fa-user-secret"></i> Dosen Wali</span>
        <div class="form-group"><?= cmb_array('id_dosen_wali', $dosen_opt, $id_dosen_wali, 'class="auto-submit"', 'Semua Dosen Wali') ?></div></form>
    <?php else: ?><h3 class="box-title">Mahasiswa Bimbingan</h3><?php endif; ?>
  </div>
  <div class="box-body table-responsive">
    <table class="table table-bordered table-striped datatable">
      <thead><tr><th>NIM</th><th>Nama</th><th>Prodi</th><th>Angkatan</th><?= $dosen_opt ? '<th>Dosen Wali</th>' : '' ?><th>SKS</th><th>Status KRS</th><th></th></tr></thead>
      <tbody>
      <?php foreach ($rows as $r): $cls = $r->krs_status === 'Disetujui' ? 'success' : ($r->krs_status === 'Diajukan' ? 'warning' : 'default'); ?>
        <tr><td><b><?= html_escape($r->nim) ?></b></td><td><?= html_escape($r->nama) ?></td><td><?= html_escape($r->kd_prodi) ?></td><td><?= $r->angkatan ?></td>
          <?= $dosen_opt ? '<td>' . html_escape($r->dosen_wali ?: '-') . '</td>' : '' ?>
          <td><?= $r->krs_sks ?></td>
          <td><span class="label label-<?= $cls ?>"><?= $r->krs_status ?: 'Belum isi' ?></span></td>
          <td><a href="<?= site_url('perwalian/detail/' . $r->nim) ?>" class="btn btn-xs btn-primary"><i class="fa fa-search"></i> Periksa</a></td></tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
