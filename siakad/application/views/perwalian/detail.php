<?php defined('BASEPATH') OR exit('No direct script access allowed');
$sks = array_sum(array_map(function ($k) { return $k->sks; }, $krs)); ?>
<div class="row">
  <div class="col-md-4">
    <div class="box">
      <div class="profile-card">
        <span class="avatar avatar-xl"><?= inisial($mhs->nama) ?></span>
        <h3><?= html_escape($mhs->nama) ?></h3>
        <p class="text-muted"><?= html_escape($mhs->nim) ?> &middot; <?= html_escape($mhs->jenjang . ' ' . $mhs->nama_prodi) ?></p>
      </div>
      <div class="box-body">
        <table class="table table-info">
          <tr><th>IPK</th><td><?= number_format($ipk['ip'], 2) ?></td></tr>
          <tr><th>SKS Lulus</th><td><?= $ipk['sks'] ?></td></tr>
          <tr><th>IPS Lalu</th><td><?= $ips_lalu === NULL ? '-' : number_format($ips_lalu, 2) ?></td></tr>
          <tr><th>Maks SKS</th><td><?= $maks_sks ?></td></tr>
        </table>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <div class="box box-primary">
      <div class="box-header with-border"><h3 class="box-title">KRS <?= html_escape(label_ta($ta)) ?></h3>
        <div class="box-tools"><span class="label label-<?= $status === 'Disetujui' ? 'success' : ($status ? 'warning' : 'default') ?>"><?= $status ?: 'Belum isi' ?></span></div></div>
      <div class="box-body table-responsive">
        <table class="table table-bordered">
          <thead><tr><th>Kode</th><th>Mata Kuliah</th><th>Smt</th><th>SKS</th><th>Kelas</th><th>Jadwal</th></tr></thead>
          <tbody>
          <?php if (empty($krs)): ?><tr><td colspan="6"><div class="empty-state"><i class="fa fa-list-alt"></i>Mahasiswa belum mengisi KRS.</div></td></tr><?php endif; ?>
          <?php foreach ($krs as $k): ?>
            <tr><td><b><?= html_escape($k->kd_mk) ?></b></td><td><?= html_escape($k->nama_mk) ?></td><td><?= $k->smt_mk ?></td><td><?= $k->sks ?></td>
              <td><?= html_escape($k->kelas) ?></td><td><?= $k->hari ?>, <?= substr($k->jam_mulai, 0, 5) ?>-<?= substr($k->jam_selesai, 0, 5) ?></td></tr>
          <?php endforeach; ?>
          </tbody>
          <?php if ($krs): ?><tfoot><tr><th colspan="3" class="text-right">Total</th><th colspan="3"><?= $sks ?> / <?= $maks_sks ?> SKS</th></tr></tfoot><?php endif; ?>
        </table>
      </div>
      <div class="box-footer">
        <a href="<?= site_url('perwalian') ?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali</a>
        <div class="pull-right">
          <?php if ($status === 'Diajukan'): ?>
            <?= form_open('perwalian/setujui/' . $mhs->nim, array('style' => 'display:inline', 'onsubmit' => "return confirm('Setujui KRS mahasiswa ini?')")) ?>
              <button class="btn btn-success"><i class="fa fa-check"></i> Setujui KRS</button><?= form_close() ?>
          <?php elseif ($status === 'Disetujui'): ?>
            <?= form_open('perwalian/batalkan/' . $mhs->nim, array('style' => 'display:inline', 'onsubmit' => "return confirm('Batalkan persetujuan KRS?')")) ?>
              <button class="btn btn-default"><i class="fa fa-undo"></i> Batalkan Persetujuan</button><?= form_close() ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
