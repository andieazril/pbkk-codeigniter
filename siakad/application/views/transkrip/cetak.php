<?php defined('BASEPATH') OR exit('No direct script access allowed'); $m = $mhs; ?>
<!DOCTYPE html>
<html lang="id">
<head><meta charset="utf-8"><title>Transkrip <?= html_escape($m->nim) ?></title><?php $this->load->view('template/print_style'); ?></head>
<body>
<?php $this->load->view('template/print_kop'); ?>
<h3 class="judul">TRANSKRIP NILAI SEMENTARA</h3>
<table class="info">
  <tr><td width="18%">Nama</td><td width="37%">: <b><?= html_escape($m->nama) ?></b></td><td width="18%">Program Studi</td><td>: <?= html_escape($m->jenjang . ' ' . $m->nama_prodi) ?></td></tr>
  <tr><td>NIM</td><td>: <?= html_escape($m->nim) ?></td><td>Fakultas</td><td>: <?= html_escape($m->fakultas) ?></td></tr>
  <tr><td>Tempat, Tgl Lahir</td><td>: <?= html_escape($m->tempat_lahir) ?>, <?= tgl_indo($m->tanggal_lahir) ?></td><td>Angkatan</td><td>: <?= $m->angkatan ?></td></tr>
</table>
<table class="grid">
  <thead><tr><th width="30">No</th><th width="40">Smt</th><th width="70">Kode</th><th>Mata Kuliah</th><th width="40">SKS</th><th width="45">Huruf</th><th width="50">Bobot</th><th width="70">SKS×Bobot</th></tr></thead>
  <tbody>
  <?php $no = 1; foreach ($rows as $r): ?>
    <tr><td class="c"><?= $no++ ?></td><td class="c"><?= $r->smt_mk ?></td><td class="c"><?= html_escape($r->kd_mk) ?></td><td><?= html_escape($r->nama_mk) ?></td>
      <td class="c"><?= $r->sks ?></td><td class="c"><?= $r->nilai_huruf ?></td><td class="c"><?= number_format($r->bobot, 2) ?></td><td class="c"><?= number_format($r->sks * $r->bobot, 2) ?></td></tr>
  <?php endforeach; ?>
  </tbody>
  <tfoot><tr><th colspan="4" style="text-align:right">Jumlah</th><th><?= $ipk['sks'] ?></th><th colspan="2"></th><th><?= number_format($ipk['mutu'], 2) ?></th></tr></tfoot>
</table>
<table class="ringkas" style="width:auto;margin-top:10px">
  <tr><td>Total SKS</td><td>: <b><?= $ipk['sks'] ?></b></td></tr>
  <tr><td>Indeks Prestasi Kumulatif (IPK)</td><td>: <b><?= number_format($ipk['ip'], 2) ?></b></td></tr>
  <tr><td>Predikat</td><td>: <?= $predikat ?></td></tr>
</table>
<table class="ttd">
  <tr>
    <td></td>
    <td><?= tgl_indo(date('Y-m-d')) ?><br><?= html_escape(info_kampus('jabatan_pimpinan')) ?><br><br><br><br><br>
      <b><u><?= html_escape(info_kampus('pimpinan')) ?></u></b><br>NIP. <?= html_escape(info_kampus('nip_pimpinan')) ?></td>
  </tr>
</table>
</body>
</html>
