<?php defined('BASEPATH') OR exit('No direct script access allowed'); $m = $mhs; ?>
<div class="row">
  <div class="col-md-4">
    <div class="box">
      <div class="profile-card">
        <?php if ($m->foto): ?><img src="<?= base_url('uploads/foto/' . $m->foto) ?>" class="foto-mhs">
        <?php else: ?><span class="avatar avatar-xl"><?= inisial($m->nama) ?></span><?php endif; ?>
        <h3><?= html_escape($m->nama) ?></h3>
        <p class="text-muted">NIM <?= html_escape($m->nim) ?></p>
      </div>
      <div class="box-body">
        <div class="row text-center">
          <div class="col-xs-6"><div class="stat-value"><?= number_format($ipk['ip'], 2) ?></div><div class="stat-label">IPK</div></div>
          <div class="col-xs-6"><div class="stat-value"><?= $ipk['sks'] ?></div><div class="stat-label">SKS Lulus</div></div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <div class="box box-primary">
      <div class="box-header with-border"><h3 class="box-title">Biodata Mahasiswa</h3>
        <div class="box-tools"><a href="<?= site_url('mahasiswa/edit/' . $m->nim) ?>" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i> Edit</a></div></div>
      <div class="box-body">
        <table class="table table-info">
          <tr><th>Program Studi</th><td><?= html_escape($m->jenjang . ' ' . $m->nama_prodi) ?></td></tr>
          <tr><th>Fakultas</th><td><?= html_escape($m->fakultas) ?></td></tr>
          <tr><th>Angkatan / Status</th><td><?= $m->angkatan ?> &middot; <?= $m->status ?></td></tr>
          <tr><th>Dosen Wali</th><td><?= html_escape($m->dosen_wali ?: '-') ?></td></tr>
          <tr><th>Jenis Kelamin</th><td><?= $m->gender === 'L' ? 'Laki-laki' : 'Perempuan' ?></td></tr>
          <tr><th>Tempat, Tgl Lahir</th><td><?= html_escape($m->tempat_lahir) ?>, <?= tgl_indo($m->tanggal_lahir) ?></td></tr>
          <tr><th>Email / Telepon</th><td><?= html_escape($m->email) ?> &middot; <?= html_escape($m->telpon) ?></td></tr>
          <tr><th>Alamat</th><td><?= html_escape($m->alamat) ?></td></tr>
        </table>
      </div>
    </div>
    <div class="box box-info">
      <div class="box-header with-border"><h3 class="box-title">Riwayat Studi</h3>
        <div class="box-tools"><a href="<?= site_url('transkrip?nim=' . $m->nim) ?>" class="btn btn-default btn-sm"><i class="fa fa-certificate"></i> Transkrip</a></div></div>
      <div class="box-body table-responsive">
        <table class="table table-bordered">
          <thead><tr><th>Tahun Akademik</th><th>SKS Diambil</th><th>IPS</th><th></th></tr></thead>
          <tbody>
          <?php foreach ($riwayat as $r): ?>
            <tr><td><?= html_escape($r->label) ?></td><td><?= $r->sks ?></td><td><b><?= number_format($r->ips, 2) ?></b></td>
              <td><a href="<?= site_url('khs?nim=' . $m->nim . '&id_tahun_akademik=' . $r->id_ta) ?>" class="btn btn-xs btn-primary">KHS</a></td></tr>
          <?php endforeach; ?>
          <?php if (empty($riwayat)): ?><tr><td colspan="4" class="text-center text-muted">Belum ada riwayat KRS.</td></tr><?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
