<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row">
  <div class="col-md-8">
    <div class="box">
      <div class="box-body">
        <table class="table table-info" style="margin:0">
          <tr><th>Mata Kuliah</th><td><?= html_escape($jadwal->kd_mk . ' - ' . $jadwal->nama_mk) ?> (<?= $jadwal->sks ?> SKS)</td></tr>
          <tr><th>Kelas / Prodi</th><td><?= html_escape($jadwal->kelas) ?> &middot; <?= html_escape($jadwal->nama_prodi) ?></td></tr>
          <tr><th>Dosen Pengampu</th><td><?= html_escape($jadwal->nama_dosen) ?></td></tr>
          <tr><th>Tahun Akademik</th><td><?= html_escape($jadwal->tahun_akademik . ' ' . $jadwal->semester) ?></td></tr>
        </table>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="box">
      <div class="box-header with-border"><h3 class="box-title">Komponen &amp; Skala</h3></div>
      <div class="box-body" style="font-size:13px">
        <p style="margin-bottom:8px">Tugas <b>30%</b> &middot; UTS <b>30%</b> &middot; UAS <b>40%</b></p>
        <?php foreach (skala_nilai() as $s): ?><span class="label label-default" style="display:inline-block;margin:0 4px 4px 0"><?= $s[0] ?> &ge; <?= $s[1] ?> (<?= number_format($s[2], 1) ?>)</span><?php endforeach; ?>
      </div>
    </div>
  </div>
</div>
<?php if ($belum_acc): ?><div class="alert alert-warning"><i class="icon fa fa-info-circle"></i> <?= $belum_acc ?> mahasiswa belum disetujui KRS-nya oleh dosen wali, sehingga belum tampil di daftar ini.</div><?php endif; ?>
<div class="box box-primary">
  <?= form_open('nilai/save') ?>
  <input type="hidden" name="id_jadwal" value="<?= $jadwal->id_jadwal ?>">
  <div class="box-body table-responsive">
    <?php if (empty($rows)): ?>
      <div class="empty-state"><i class="fa fa-users"></i>Belum ada mahasiswa (dengan KRS disetujui) di kelas ini.</div>
    <?php else: ?>
    <table class="table table-bordered table-striped" id="tbl-nilai">
      <thead><tr><th width="40">No</th><th>NIM</th><th>Nama</th><th width="100">Tugas</th><th width="100">UTS</th><th width="100">UAS</th><th width="90">Akhir</th><th width="80">Huruf</th><th width="70">Bobot</th></tr></thead>
      <tbody>
      <?php $no = 1; foreach ($rows as $r): ?>
        <tr>
          <td><?= $no++ ?></td><td><b><?= html_escape($r->nim) ?></b></td><td><?= html_escape($r->nama) ?></td>
          <?php foreach (array('tugas', 'uts', 'uas') as $f): $col = 'nilai_' . $f; ?>
            <td><input type="number" step="0.01" min="0" max="100" class="form-control input-sm n-<?= $f ?>" name="nilai[<?= $r->id_krs ?>][<?= $f ?>]" value="<?= $r->$col !== NULL ? (float) $r->$col : '' ?>"></td>
          <?php endforeach; ?>
          <td class="n-akhir text-bold"><?= $r->nilai_akhir !== NULL ? (float) $r->nilai_akhir : '-' ?></td>
          <td class="n-huruf"><?= $r->nilai_huruf ? '<span class="label label-' . ($r->bobot >= 2 ? 'success' : 'danger') . '">' . $r->nilai_huruf . '</span>' : '-' ?></td>
          <td class="n-bobot"><?= $r->bobot !== NULL ? number_format($r->bobot, 2) : '-' ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>
  <div class="box-footer">
    <a href="<?= site_url($this->session->userdata('id_level_user') == 2 ? 'jadwalsaya' : 'nilai?id_tahun_akademik=' . $jadwal->id_tahun_akademik) ?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali</a>
    <?php if (!empty($rows)): ?><button class="btn btn-primary pull-right"><i class="fa fa-save"></i> Simpan Nilai</button><?php endif; ?>
  </div>
  <?= form_close() ?>
</div>
<?php ob_start(); ?>
<script>
$(function () {
  var SKALA = <?= json_encode(skala_nilai()) ?>;
  function huruf(n) { for (var i = 0; i < SKALA.length; i++) if (n >= SKALA[i][1]) return SKALA[i]; return SKALA[SKALA.length - 1]; }
  $('#tbl-nilai').on('input', 'input', function () {
    var tr = $(this).closest('tr');
    var t = parseFloat(tr.find('.n-tugas').val()) || 0, u = parseFloat(tr.find('.n-uts').val()) || 0, a = parseFloat(tr.find('.n-uas').val()) || 0;
    var akhir = Math.round((t * 0.3 + u * 0.3 + a * 0.4) * 100) / 100, h = huruf(akhir);
    tr.find('.n-akhir').text(akhir);
    tr.find('.n-huruf').html('<span class="label label-' + (h[2] >= 2 ? 'success' : 'danger') + '">' + h[0] + '</span>');
    tr.find('.n-bobot').text(Number(h[2]).toFixed(2));
  });
});
</script>
<?php $this->load->vars(array('script' => ob_get_clean())); ?>
