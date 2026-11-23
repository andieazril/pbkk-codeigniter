<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $lvl = (int) $this->session->userdata('id_level_user');
$jam = (int) date('G');
$sapa = $jam < 11 ? 'Selamat pagi' : ($jam < 15 ? 'Selamat siang' : ($jam < 18 ? 'Selamat sore' : 'Selamat malam')); ?>

<div class="welcome-banner">
  <i class="fa fa-graduation-cap banner-icon"></i>
  <h2><?= $sapa ?>, <?= html_escape($this->session->userdata('nama_lengkap')) ?>!</h2>
  <p>Anda masuk sebagai <b><?= html_escape($this->session->userdata('nama_level')) ?></b> di <?= html_escape(info_kampus('nama_kampus')) ?>.</p>
  <span class="banner-chip"><i class="fa fa-calendar"></i>
    <?= $hari_ini ?>, <?= tgl_indo(date('Y-m-d')) ?> &middot; Semester <?= html_escape(label_ta($tahun_aktif)) ?></span>
</div>

<?php if ($lvl === 1): ?>
<div class="row">
  <?php foreach (array(
    array('mahasiswa', 'Mahasiswa Aktif', 'stat-indigo', 'fa-users'),
    array('dosen', 'Dosen', 'stat-green', 'fa-user-secret'),
    array('prodi', 'Program Studi', 'stat-amber', 'fa-sitemap'),
    array('matakuliah', 'Mata Kuliah', 'stat-rose', 'fa-book'),
  ) as $b): ?>
  <div class="col-lg-3 col-sm-6">
    <a href="<?= site_url($b[0]) ?>" class="stat-card <?= $b[2] ?>">
      <div class="stat-icon"><i class="fa <?= $b[3] ?>"></i></div>
      <div><div class="stat-value"><?= number_format($jml[$b[0]]) ?></div><div class="stat-label"><?= $b[1] ?></div></div>
    </a>
  </div>
  <?php endforeach; ?>
</div>
<div class="row">
  <div class="col-md-7">
    <div class="box box-primary">
      <div class="box-header with-border"><h3 class="box-title">Mahasiswa Aktif per Program Studi</h3></div>
      <div class="box-body">
        <?php $max = 1; foreach ($per_prodi as $r) $max = max($max, (int) $r->total); ?>
        <?php foreach ($per_prodi as $r): ?>
          <div class="bar-row">
            <div class="bar-meta"><span><?= html_escape($r->jenjang . ' ' . $r->nama_prodi) ?></span><b><?= $r->total ?> mahasiswa</b></div>
            <div class="bar-track"><div class="bar-fill" style="width: <?= round($r->total / $max * 100) ?>%"></div></div>
          </div>
        <?php endforeach; ?>
        <?php if (empty($per_prodi)): ?><div class="empty-state"><i class="fa fa-bar-chart"></i>Belum ada program studi.</div><?php endif; ?>
      </div>
    </div>
  </div>
  <div class="col-md-5">
    <div class="box box-success">
      <div class="box-header with-border"><h3 class="box-title">Semester <?= html_escape(label_ta($tahun_aktif)) ?></h3></div>
      <div class="box-body">
        <table class="table table-info">
          <tr><th>Kelas dibuka</th><td><?= $krs['jadwal'] ?> kelas</td></tr>
          <tr><th>Sudah isi KRS</th><td><?= $krs['isi'] ?> / <?= $jml['mahasiswa'] ?> mahasiswa</td></tr>
          <tr><th>KRS disetujui</th><td><?= $krs['acc'] ?> mahasiswa</td></tr>
          <tr><th>Menunggu persetujuan</th><td><a href="<?= site_url('perwalian') ?>"><?= $krs['isi'] - $krs['acc'] ?> mahasiswa &rarr;</a></td></tr>
        </table>
      </div>
    </div>
  </div>
</div>

<?php else: ?>
<div class="row">
  <div class="col-md-8">
    <div class="box box-primary">
      <div class="box-header with-border"><h3 class="box-title">Jadwal Kuliah Hari Ini</h3>
        <div class="box-tools"><span class="label label-primary"><?= $hari_ini ?></span></div></div>
      <div class="box-body">
        <?php if (empty($jadwal_hari_ini)): ?>
          <div class="empty-state"><i class="fa fa-coffee"></i>Tidak ada jadwal kuliah hari ini.</div>
        <?php else: foreach ($jadwal_hari_ini as $j): ?>
          <div class="schedule-item">
            <div class="time"><?= substr($j->jam_mulai, 0, 5) ?> – <?= substr($j->jam_selesai, 0, 5) ?></div>
            <div>
              <div class="title"><?= html_escape($j->kd_mk . ' ' . $j->nama_mk) ?> <span class="label label-default">Kelas <?= html_escape($j->kelas) ?></span></div>
              <div class="meta"><i class="fa fa-book"></i> <?= $j->sks ?> SKS &nbsp;·&nbsp; <i class="fa fa-user"></i> <?= html_escape($j->nama_dosen) ?> &nbsp;·&nbsp; <i class="fa fa-map-marker"></i> <?= html_escape($j->nama_ruangan) ?></div>
            </div>
          </div>
        <?php endforeach; endif; ?>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <?php if ($lvl === 2): ?>
      <a href="<?= site_url('jadwalsaya') ?>" class="stat-card stat-indigo"><div class="stat-icon"><i class="fa fa-calendar-check-o"></i></div>
        <div><div class="stat-value"><?= isset($jml_kelas) ? $jml_kelas : 0 ?></div><div class="stat-label">Kelas diampu semester ini</div></div></a>
      <a href="<?= site_url('perwalian') ?>" class="stat-card stat-amber"><div class="stat-icon"><i class="fa fa-check-square-o"></i></div>
        <div><div class="stat-value"><?= isset($menunggu_acc) ? $menunggu_acc : 0 ?></div><div class="stat-label">KRS menunggu persetujuan</div></div></a>
      <a href="<?= site_url('perwalian') ?>" class="stat-card stat-green"><div class="stat-icon"><i class="fa fa-users"></i></div>
        <div><div class="stat-value"><?= isset($jml_bimbingan) ? $jml_bimbingan : 0 ?></div><div class="stat-label">Mahasiswa bimbingan</div></div></a>
    <?php else: ?>
      <a href="<?= site_url('transkrip') ?>" class="stat-card stat-indigo"><div class="stat-icon"><i class="fa fa-trophy"></i></div>
        <div><div class="stat-value"><?= number_format($ipk['ip'], 2) ?></div><div class="stat-label">IPK &middot; <?= $ipk['sks'] ?> SKS lulus</div></div></a>
      <a href="<?= site_url('krs') ?>" class="stat-card stat-<?= $krs_status === 'Disetujui' ? 'green' : ($krs_status ? 'amber' : 'rose') ?>"><div class="stat-icon"><i class="fa fa-list-alt"></i></div>
        <div><div class="stat-value" style="font-size:16px"><?= $krs_status ? 'KRS ' . $krs_status : 'Belum isi KRS' ?></div><div class="stat-label"><?= $krs_sks ?> SKS diambil semester ini</div></div></a>
      <a href="<?= site_url('khs') ?>" class="stat-card stat-green"><div class="stat-icon"><i class="fa fa-file-text-o"></i></div>
        <div><div class="stat-value" style="font-size:16px">KHS</div><div class="stat-label">Lihat hasil studi per semester</div></div></a>
    <?php endif; ?>
  </div>
</div>
<?php endif; ?>
