<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="box box-primary">
  <div class="box-header with-border no-print">
    <form method="get" class="form-inline"><span class="filter-label" style="margin-left:0"><i class="fa fa-calendar"></i> Tahun Akademik</span>
      <div class="form-group"><?= cmb_array('id_tahun_akademik', $ta_opt, $ta, 'class="auto-submit"') ?></div>
      <button type="button" onclick="window.print()" class="btn btn-default btn-sm"><i class="fa fa-print"></i> Cetak</button>
    </form>
  </div>
  <div class="box-body table-responsive">
    <table class="table table-bordered table-striped">
      <thead><tr><th>Hari</th><th>Jam</th><th>Mata Kuliah</th><th>SKS</th><th>Kelas</th><th>Ruang</th><th>Peserta</th><th class="no-print">Nilai</th></tr></thead>
      <tbody>
      <?php if (empty($rows)): ?><tr><td colspan="8"><div class="empty-state"><i class="fa fa-calendar-o"></i>Belum ada jadwal mengajar.</div></td></tr><?php endif; ?>
      <?php foreach ($rows as $r): ?>
        <tr><td><?= $r->hari ?></td><td><?= substr($r->jam_mulai, 0, 5) ?> - <?= substr($r->jam_selesai, 0, 5) ?></td>
          <td><b><?= html_escape($r->kd_mk) ?></b> <?= html_escape($r->nama_mk) ?></td><td><?= $r->sks ?></td>
          <td><span class="label label-primary"><?= html_escape($r->kelas) ?></span></td><td><?= html_escape($r->nama_ruangan) ?></td>
          <td><?= $r->terisi ?> mhs</td>
          <td class="no-print"><a href="<?= site_url('nilai/input/' . $r->id_jadwal) ?>" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i> Input Nilai</a></td></tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
