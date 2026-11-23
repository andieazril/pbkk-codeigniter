<?php defined('BASEPATH') OR exit('No direct script access allowed'); $m = $mhs; ?>
<!DOCTYPE html>
<html lang="id">
<head><meta charset="utf-8"><title>KHS <?= html_escape($m->nim) ?></title><?php $this->load->view('template/print_style'); ?></head>
<body>
<?php $this->load->view('template/print_kop'); ?>
<h3 class="judul">KARTU HASIL STUDI (KHS)</h3>
<table class="info">
  <tr><td width="18%">Nama</td><td width="37%">: <b><?= html_escape($m->nama) ?></b></td><td width="18%">Tahun Akademik</td><td>: <?= html_escape($ta->tahun_akademik) ?></td></tr>
  <tr><td>NIM</td><td>: <?= html_escape($m->nim) ?></td><td>Semester</td><td>: <?= html_escape($ta->semester) ?> (Semester <?= $smt ?>)</td></tr>
  <tr><td>Program Studi</td><td>: <?= html_escape($m->jenjang . ' ' . $m->nama_prodi) ?></td><td>Dosen Wali</td><td>: <?= html_escape($m->dosen_wali) ?></td></tr>
</table>
<table class="grid">
  <thead><tr><th width="30">No</th><th width="70">Kode</th><th>Mata Kuliah</th><th width="40">SKS</th><th width="55">Nilai</th><th width="45">Huruf</th><th width="50">Bobot</th><th width="70">SKS×Bobot</th></tr></thead>
  <tbody>
  <?php $no = 1; foreach ($rows as $r): ?>
    <tr><td class="c"><?= $no++ ?></td><td class="c"><?= html_escape($r->kd_mk) ?></td><td><?= html_escape($r->nama_mk) ?></td><td class="c"><?= $r->sks ?></td>
      <td class="c"><?= $r->nilai_akhir !== NULL ? (float) $r->nilai_akhir : '-' ?></td><td class="c"><?= $r->nilai_huruf ?: '-' ?></td>
      <td class="c"><?= $r->bobot !== NULL ? number_format($r->bobot, 2) : '-' ?></td><td class="c"><?= $r->bobot !== NULL ? number_format($r->sks * $r->bobot, 2) : '-' ?></td></tr>
  <?php endforeach; ?>
  </tbody>
  <tfoot><tr><th colspan="3" style="text-align:right">Jumlah</th><th><?= $ips['sks'] ?></th><th colspan="3"></th><th><?= number_format($ips['mutu'], 2) ?></th></tr></tfoot>
</table>
<table class="ringkas" style="width:auto;margin-top:10px">
  <tr><td>Indeks Prestasi Semester (IPS)</td><td>: <b><?= number_format($ips['ip'], 2) ?></b></td></tr>
  <tr><td>Indeks Prestasi Kumulatif (IPK)</td><td>: <b><?= number_format($ipk['ip'], 2) ?></b></td></tr>
  <tr><td>Total SKS Lulus</td><td>: <?= $ipk['sks'] ?></td></tr>
  <tr><td>Maks. SKS semester berikutnya</td><td>: <?= maks_sks($ips['sks'] ? $ips['ip'] : NULL) ?></td></tr>
</table>
<table class="ttd">
  <tr>
    <td>Mengetahui,<br>Ketua Program Studi<br><br><br><br><br><b><u><?= html_escape($m->ketua_prodi ?: '.........................') ?></u></b><br>NIDN. <?= html_escape($m->nidn_kaprodi) ?></td>
    <td><?= tgl_indo(date('Y-m-d')) ?><br>Dosen Wali<br><br><br><br><br><b><u><?= html_escape($m->dosen_wali ?: '.........................') ?></u></b><br>NIDN. <?= html_escape($m->nidn_wali) ?></td>
  </tr>
</table>
</body>
</html>
