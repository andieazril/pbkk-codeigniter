<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="box box-primary">
  <div class="box-header with-border no-print">
    <form method="get" class="form-inline">
      <a href="<?= site_url('jadwal/add') ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah Jadwal</a>
      <span class="filter-label"><i class="fa fa-filter"></i> Filter</span>
      <div class="form-group"><?= cmb_array('id_tahun_akademik', $ta_opt, $ta, 'class="auto-submit"') ?></div>
      <div class="form-group"><?= cmb_array('kd_prodi', $prodi_opt, $prodi, 'class="auto-submit"', 'Semua Prodi') ?></div>
      <button type="button" onclick="window.print()" class="btn btn-default btn-sm"><i class="fa fa-print"></i> Cetak</button>
    </form>
  </div>
  <div class="box-body table-responsive">
    <table class="table table-bordered table-striped">
      <thead><tr><th>Hari</th><th>Jam</th><th>Kode</th><th>Mata Kuliah</th><th>SKS</th><th>Kelas</th><th>Dosen</th><th>Ruang</th><th>Peserta</th><th class="no-print" width="90">Aksi</th></tr></thead>
      <tbody>
      <?php if (empty($rows)): ?>
        <tr><td colspan="10"><div class="empty-state"><i class="fa fa-calendar-o"></i>Belum ada jadwal kuliah.</div></td></tr>
      <?php endif; ?>
      <?php $prev = NULL; foreach ($rows as $r): ?>
        <tr>
          <td><b><?= $prev !== $r->hari ? $r->hari : '' ?></b></td>
          <td><?= substr($r->jam_mulai, 0, 5) ?> - <?= substr($r->jam_selesai, 0, 5) ?></td>
          <td><?= html_escape($r->kd_mk) ?></td>
          <td><?= html_escape($r->nama_mk) ?></td>
          <td><?= $r->sks ?></td>
          <td><span class="label label-primary"><?= html_escape($r->kelas) ?></span></td>
          <td><?= html_escape($r->nama_dosen) ?></td>
          <td><?= html_escape($r->kd_ruangan) ?></td>
          <td><span class="label label-<?= $r->terisi >= $r->kuota ? 'danger' : 'default' ?>"><?= $r->terisi ?>/<?= $r->kuota ?></span></td>
          <td class="no-print"><?= btn_aksi('jadwal', $r->id_jadwal) ?></td>
        </tr>
      <?php $prev = $r->hari; endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
