<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="box box-primary">
  <div class="box-header with-border">
    <form method="get" class="form-inline"><span class="filter-label" style="margin-left:0"><i class="fa fa-calendar"></i> Tahun Akademik</span>
      <div class="form-group"><?= cmb_array('id_tahun_akademik', $ta_opt, $ta, 'class="auto-submit"') ?></div>
    </form>
  </div>
  <div class="box-body table-responsive">
    <table class="table table-bordered table-striped datatable">
      <thead><tr><th>Kode</th><th>Mata Kuliah</th><th>SKS</th><th>Kelas</th><th>Dosen</th><th>Jadwal</th><th>Peserta</th><th>Aksi</th></tr></thead>
      <tbody>
      <?php foreach ($rows as $r): ?>
        <tr><td><b><?= html_escape($r->kd_mk) ?></b></td><td><?= html_escape($r->nama_mk) ?></td><td><?= $r->sks ?></td>
          <td><span class="label label-primary"><?= html_escape($r->kelas) ?></span></td><td><?= html_escape($r->nama_dosen) ?></td>
          <td><?= $r->hari ?>, <?= substr($r->jam_mulai, 0, 5) ?>-<?= substr($r->jam_selesai, 0, 5) ?></td><td><?= $r->terisi ?> mhs</td>
          <td><a href="<?= site_url('nilai/input/' . $r->id_jadwal) ?>" class="btn btn-xs btn-success"><i class="fa fa-pencil-square-o"></i> Input Nilai</a></td></tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
