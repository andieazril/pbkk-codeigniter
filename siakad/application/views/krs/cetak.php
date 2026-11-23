<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>KRS <?= html_escape($mhs->nim) ?></title>
  <?php $this->load->view('template/print_style'); ?>
</head>
<body>
<?php $this->load->view('template/print_kop'); ?>
<h3 class="judul">KARTU RENCANA STUDI (KRS)</h3>
<table class="info">
  <tr><td width="18%">Nama</td><td width="37%">: <b><?= html_escape($mhs->nama) ?></b></td><td width="18%">Tahun Akademik</td><td>: <?= html_escape($ta['tahun_akademik']) ?></td></tr>
  <tr><td>NIM</td><td>: <?= html_escape($mhs->nim) ?></td><td>Semester</td><td>: <?= html_escape($ta['semester']) ?> (Semester <?= $smt ?>)</td></tr>
  <tr><td>Program Studi</td><td>: <?= html_escape($mhs->jenjang . ' ' . $mhs->nama_prodi) ?></td><td>Status KRS</td><td>: <?= $status ?: '-' ?></td></tr>
</table>
<table class="grid">
  <thead><tr><th width="30">No</th><th width="70">Kode</th><th>Mata Kuliah</th><th width="40">SKS</th><th width="45">Kelas</th><th>Jadwal</th><th>Dosen</th></tr></thead>
  <tbody>
  <?php $no = 1; $sks = 0; foreach ($krs as $k): $sks += $k->sks; ?>
    <tr><td class="c"><?= $no++ ?></td><td class="c"><?= html_escape($k->kd_mk) ?></td><td><?= html_escape($k->nama_mk) ?></td><td class="c"><?= $k->sks ?></td>
      <td class="c"><?= html_escape($k->kelas) ?></td><td><?= $k->hari ?>, <?= substr($k->jam_mulai, 0, 5) ?>-<?= substr($k->jam_selesai, 0, 5) ?></td><td><?= html_escape($k->nama_dosen) ?></td></tr>
  <?php endforeach; ?>
  </tbody>
  <tfoot><tr><th colspan="3" style="text-align:right">Jumlah SKS</th><th><?= $sks ?></th><th colspan="3"></th></tr></tfoot>
</table>
<table class="ttd">
  <tr>
    <td>Mengetahui,<br>Dosen Wali<br><br><br><br><br><b><u><?= html_escape($mhs->dosen_wali ?: '.........................') ?></u></b><br>NIDN. <?= html_escape($mhs->nidn_wali) ?></td>
    <td><?= tgl_indo(date('Y-m-d')) ?><br>Mahasiswa<br><br><br><br><br><b><u><?= html_escape($mhs->nama) ?></u></b><br>NIM. <?= html_escape($mhs->nim) ?></td>
  </tr>
</table>
</body>
</html>
