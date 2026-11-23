<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="box box-default">
  <div class="box-body">
    <form method="get" class="form-inline">
      <?php if ($opsi_mhs): ?>
        <span class="filter-label" style="margin-left:0"><i class="fa fa-user"></i> Mahasiswa</span>
        <div class="form-group" style="width:320px"><?= cmb_array('nim', $opsi_mhs, $nim, 'class="select2 auto-submit"', 'Pilih mahasiswa...') ?></div>
      <?php endif; ?>
      <?php if ($tr): ?><a href="<?= site_url('transkrip/cetak/' . $nim) ?>" target="_blank" class="btn btn-primary btn-sm"><i class="fa fa-print"></i> Cetak Transkrip</a><?php endif; ?>
    </form>
  </div>
</div>
<?php if (!$tr): ?>
  <div class="callout callout-info">Silakan pilih mahasiswa terlebih dahulu.</div>
<?php else: $m = $tr['mhs']; ?>
<div class="row">
  <div class="col-lg-3 col-sm-6"><div class="stat-card stat-indigo"><div class="stat-icon"><i class="fa fa-trophy"></i></div>
    <div><div class="stat-value"><?= number_format($tr['ipk']['ip'], 2) ?></div><div class="stat-label">IPK</div></div></div></div>
  <div class="col-lg-3 col-sm-6"><div class="stat-card stat-green"><div class="stat-icon"><i class="fa fa-check-circle"></i></div>
    <div><div class="stat-value"><?= $tr['ipk']['sks'] ?></div><div class="stat-label">Total SKS</div></div></div></div>
  <div class="col-lg-6"><div class="stat-card stat-amber"><div class="stat-icon"><i class="fa fa-star"></i></div>
    <div><div class="stat-value" style="font-size:18px"><?= $tr['predikat'] ?></div><div class="stat-label">Predikat (berdasarkan IPK)</div></div></div></div>
</div>
<div class="box box-primary">
  <div class="box-header with-border"><h3 class="box-title"><?= html_escape($m->nim . ' - ' . $m->nama) ?> &middot; <?= html_escape($m->jenjang . ' ' . $m->nama_prodi) ?></h3></div>
  <div class="box-body table-responsive">
    <table class="table table-bordered">
      <thead><tr><th>Smt</th><th>Kode</th><th>Mata Kuliah</th><th>SKS</th><th>Huruf</th><th>Bobot</th><th>SKS &times; Bobot</th></tr></thead>
      <tbody>
      <?php if (empty($tr['rows'])): ?><tr><td colspan="7"><div class="empty-state"><i class="fa fa-certificate"></i>Belum ada nilai.</div></td></tr><?php endif; ?>
      <?php foreach ($tr['rows'] as $r): ?>
        <tr><td><?= $r->smt_mk ?></td><td><b><?= html_escape($r->kd_mk) ?></b></td><td><?= html_escape($r->nama_mk) ?></td><td><?= $r->sks ?></td>
          <td><span class="label label-<?= $r->bobot >= 2 ? 'success' : 'danger' ?>"><?= $r->nilai_huruf ?></span></td>
          <td><?= number_format($r->bobot, 2) ?></td><td><?= number_format($r->sks * $r->bobot, 2) ?></td></tr>
      <?php endforeach; ?>
      </tbody>
      <tfoot><tr><th colspan="3" class="text-right">Jumlah</th><th><?= $tr['ipk']['sks'] ?></th><th colspan="2"></th><th><?= number_format($tr['ipk']['mutu'], 2) ?></th></tr></tfoot>
    </table>
  </div>
</div>
<?php endif; ?>
