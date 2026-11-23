<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="box box-default">
  <div class="box-body">
    <form method="get" class="form-inline">
      <?php if ($opsi_mhs): ?>
        <span class="filter-label" style="margin-left:0"><i class="fa fa-user"></i> Mahasiswa</span>
        <div class="form-group" style="width:320px"><?= cmb_array('nim', $opsi_mhs, $nim, 'class="select2 auto-submit"', 'Pilih mahasiswa...') ?></div>
      <?php endif; ?>
      <?php if ($ta_opt): ?>
        <span class="filter-label" <?= $opsi_mhs ? '' : 'style="margin-left:0"' ?>><i class="fa fa-calendar"></i> Semester</span>
        <div class="form-group"><?= cmb_array('id_tahun_akademik', $ta_opt, $id_ta, 'class="auto-submit"') ?></div>
        <?php if ($khs): ?><a href="<?= site_url('khs/cetak/' . $nim . '/' . $id_ta) ?>" target="_blank" class="btn btn-primary btn-sm"><i class="fa fa-print"></i> Cetak KHS</a><?php endif; ?>
      <?php endif; ?>
    </form>
  </div>
</div>
<?php if (!$khs): ?>
  <div class="callout callout-info"><?= $nim ? 'Belum ada data KRS/KHS untuk mahasiswa ini.' : 'Silakan pilih mahasiswa terlebih dahulu.' ?></div>
<?php else: $m = $khs['mhs']; ?>
<div class="row">
  <div class="col-lg-3 col-sm-6"><div class="stat-card stat-indigo"><div class="stat-icon"><i class="fa fa-line-chart"></i></div>
    <div><div class="stat-value"><?= $khs['ips']['sks'] ? number_format($khs['ips']['ip'], 2) : '&ndash;' ?></div><div class="stat-label">IP Semester<?= $khs['ips']['sks'] ? '' : ' (belum ada nilai)' ?></div></div></div></div>
  <div class="col-lg-3 col-sm-6"><div class="stat-card stat-green"><div class="stat-icon"><i class="fa fa-trophy"></i></div>
    <div><div class="stat-value"><?= number_format($khs['ipk']['ip'], 2) ?></div><div class="stat-label">IP Kumulatif</div></div></div></div>
  <div class="col-lg-3 col-sm-6"><div class="stat-card stat-amber"><div class="stat-icon"><i class="fa fa-book"></i></div>
    <div><div class="stat-value"><?= $khs['sks_diambil'] ?></div><div class="stat-label">SKS Semester Ini</div></div></div></div>
  <div class="col-lg-3 col-sm-6"><div class="stat-card stat-rose"><div class="stat-icon"><i class="fa fa-check-circle"></i></div>
    <div><div class="stat-value"><?= $khs['ipk']['sks'] ?></div><div class="stat-label">Total SKS Lulus</div></div></div></div>
</div>
<div class="box box-primary">
  <div class="box-header with-border"><h3 class="box-title"><?= html_escape($m->nim . ' - ' . $m->nama) ?> &middot; <?= html_escape(label_ta($khs['ta'])) ?> (Semester <?= $khs['smt'] ?>)</h3></div>
  <div class="box-body table-responsive">
    <table class="table table-bordered table-striped">
      <thead><tr><th>No</th><th>Kode</th><th>Mata Kuliah</th><th>SKS</th><th>Nilai Akhir</th><th>Huruf</th><th>Bobot</th><th>SKS &times; Bobot</th></tr></thead>
      <tbody>
      <?php $no = 1; foreach ($khs['rows'] as $r): ?>
        <tr><td><?= $no++ ?></td><td><b><?= html_escape($r->kd_mk) ?></b></td><td><?= html_escape($r->nama_mk) ?></td><td><?= $r->sks ?></td>
          <td><?= $r->nilai_akhir !== NULL ? (float) $r->nilai_akhir : '-' ?></td>
          <td><?= $r->nilai_huruf ? '<span class="label label-' . ($r->bobot >= 2 ? 'success' : 'danger') . '">' . $r->nilai_huruf . '</span>' : '<span class="text-muted">Belum dinilai</span>' ?></td>
          <td><?= $r->bobot !== NULL ? number_format($r->bobot, 2) : '-' ?></td>
          <td><?= $r->bobot !== NULL ? number_format($r->sks * $r->bobot, 2) : '-' ?></td></tr>
      <?php endforeach; ?>
      </tbody>
      <tfoot><tr><th colspan="3" class="text-right">Jumlah</th><th><?= $khs['ips']['sks'] ?></th><th colspan="3"></th><th><?= number_format($khs['ips']['mutu'], 2) ?></th></tr></tfoot>
    </table>
  </div>
</div>
<?php endif; ?>
