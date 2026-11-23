<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if ($opsi_mhs): ?>
<div class="box box-default">
  <div class="box-body">
    <form method="get" class="form-inline">
      <span class="filter-label" style="margin-left:0"><i class="fa fa-user"></i> Mahasiswa</span>
      <div class="form-group" style="width:340px"><?= cmb_array('nim', $opsi_mhs, $nim, 'class="select2 auto-submit"', 'Pilih mahasiswa...') ?></div>
    </form>
  </div>
</div>
<?php endif; ?>

<?php if (!$ta): ?>
  <div class="callout callout-warning">Tahun akademik aktif belum diatur oleh administrator.</div>
<?php elseif (!$mhs): ?>
  <div class="callout callout-info">Silakan pilih mahasiswa terlebih dahulu.</div>
<?php else:
  $persen = min(100, $maks_sks ? round($total_sks / $maks_sks * 100) : 0);
  $st_cls = $status === 'Disetujui' ? 'success' : ($status === 'Diajukan' ? 'warning' : 'default'); ?>
<div class="row">
  <div class="col-md-8">
    <div class="box">
      <div class="box-body" style="display:flex;align-items:center;gap:16px;flex-wrap:wrap">
        <span class="avatar avatar-lg"><?= inisial($mhs->nama) ?></span>
        <div style="flex:1;min-width:220px">
          <div style="font-size:17px;font-weight:700"><?= html_escape($mhs->nama) ?></div>
          <div class="text-muted"><?= html_escape($mhs->nim) ?> &middot; <?= html_escape($mhs->jenjang . ' ' . $mhs->nama_prodi) ?> &middot; Semester <?= $smt ?></div>
          <div class="text-muted"><i class="fa fa-user-circle-o"></i> Dosen wali: <?= html_escape($mhs->dosen_wali ?: '-') ?></div>
        </div>
        <div class="text-right">
          <span class="label label-<?= $st_cls ?>" style="font-size:12.5px"><?= $status ? 'KRS ' . $status : 'Belum mengisi KRS' ?></span><br><br>
          <?php if ($krs): ?><a href="<?= site_url('krs/cetak' . ($this->session->userdata('id_level_user') == 3 ? '' : '?nim=' . $mhs->nim)) ?>" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-print"></i> Cetak KRS</a><?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="box">
      <div class="box-body">
        <div class="bar-row" style="margin:0">
          <div class="bar-meta"><span>SKS diambil</span><b><?= $total_sks ?> / <?= $maks_sks ?> SKS</b></div>
          <div class="bar-track"><div class="bar-fill" style="width: <?= $persen ?>%"></div></div>
        </div>
        <p class="text-muted" style="margin:10px 0 0;font-size:12.5px">IPS semester lalu: <b><?= $ips_lalu === NULL ? '- (mahasiswa baru)' : number_format($ips_lalu, 2) ?></b> &rarr; maks <?= $maks_sks ?> SKS</p>
      </div>
    </div>
  </div>
</div>

<?php if ($terkunci): ?><div class="alert alert-info"><i class="icon fa fa-lock"></i> <?= html_escape($terkunci) ?></div><?php endif; ?>

<div class="box box-primary">
  <div class="box-header with-border"><h3 class="box-title">Mata Kuliah Diambil</h3></div>
  <div class="box-body table-responsive">
    <table class="table table-bordered">
      <thead><tr><th>Kode</th><th>Mata Kuliah</th><th>SKS</th><th>Kelas</th><th>Jadwal</th><th>Dosen</th><th>Status</th><th width="60"></th></tr></thead>
      <tbody>
      <?php if (empty($krs)): ?><tr><td colspan="8"><div class="empty-state"><i class="fa fa-list-alt"></i>Belum ada mata kuliah. Pilih dari daftar di bawah.</div></td></tr><?php endif; ?>
      <?php foreach ($krs as $k): ?>
        <tr>
          <td><b><?= html_escape($k->kd_mk) ?></b></td><td><?= html_escape($k->nama_mk) ?></td><td><?= $k->sks ?></td>
          <td><span class="label label-primary"><?= html_escape($k->kelas) ?></span></td>
          <td><?= $k->hari ?>, <?= substr($k->jam_mulai, 0, 5) ?>-<?= substr($k->jam_selesai, 0, 5) ?> <span class="text-muted">(<?= html_escape($k->nama_ruangan) ?>)</span></td>
          <td><?= html_escape($k->nama_dosen) ?></td>
          <td><span class="label label-<?= $k->status === 'Disetujui' ? 'success' : 'warning' ?>"><?= $k->status ?></span></td>
          <td><?= (!$terkunci && $k->nilai_huruf === NULL) ? btn_post('krs/hapus/' . $k->id_krs . '?nim=' . rawurlencode($mhs->nim), '<i class="fa fa-times"></i>', 'btn-danger', 'Hapus mata kuliah ini dari KRS?') : '' ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
      <?php if ($krs): ?><tfoot><tr><th colspan="2" class="text-right">Total</th><th><?= $total_sks ?> SKS</th><th colspan="5"></th></tr></tfoot><?php endif; ?>
    </table>
  </div>
</div>

<?php if (!$terkunci): ?>
<div class="box box-success">
  <div class="box-header with-border"><h3 class="box-title">Jadwal Tersedia &mdash; <?= html_escape($mhs->nama_prodi) ?></h3></div>
  <div class="box-body table-responsive">
    <table class="table table-bordered table-striped datatable">
      <thead><tr><th>Smt</th><th>Kode</th><th>Mata Kuliah</th><th>SKS</th><th>Kelas</th><th>Jadwal</th><th>Dosen</th><th>Kuota</th><th></th></tr></thead>
      <tbody>
      <?php foreach ($tersedia as $j): $penuh = $j->terisi >= $j->kuota; ?>
        <tr>
          <td><?= $j->smt_mk ?></td><td><b><?= html_escape($j->kd_mk) ?></b></td>
          <td><?= html_escape($j->nama_mk) ?> <?= $j->jenis === 'Pilihan' ? '<span class="label label-info">Pilihan</span>' : '' ?></td>
          <td><?= $j->sks ?></td><td><?= html_escape($j->kelas) ?></td>
          <td><?= $j->hari ?>, <?= substr($j->jam_mulai, 0, 5) ?>-<?= substr($j->jam_selesai, 0, 5) ?></td>
          <td><?= html_escape($j->nama_dosen) ?></td>
          <td><span class="label label-<?= $penuh ? 'danger' : 'default' ?>"><?= $j->terisi ?>/<?= $j->kuota ?></span></td>
          <td>
            <?php if ($j->diambil): ?><span class="label label-success"><i class="fa fa-check"></i> Diambil</span>
            <?php elseif ($penuh): ?><span class="text-muted">Penuh</span>
            <?php else: ?>
              <?= form_open('krs/ambil', array('style' => 'display:inline')) ?>
                <input type="hidden" name="id_jadwal" value="<?= $j->id_jadwal ?>">
                <input type="hidden" name="nim" value="<?= html_escape($mhs->nim) ?>">
                <button class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Ambil</button>
              <?= form_close() ?>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php endif; ?>
<?php endif; ?>
