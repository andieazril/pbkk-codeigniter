<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="box box-primary">
  <div class="box-header with-border"><h3 class="box-title">Identitas Perguruan Tinggi</h3></div>
  <?= form_open('kampus/save', array('class' => 'form-horizontal')) ?>
  <div class="box-body">
    <?php foreach ($fields as $f => $label): ?>
      <div class="form-group"><label class="col-sm-3 control-label"><?= $label ?></label>
        <div class="col-sm-7">
          <?php if ($f === 'alamat'): ?>
            <textarea name="alamat" class="form-control" rows="2"><?= $row ? html_escape($row->alamat) : '' ?></textarea>
          <?php else: ?>
            <input name="<?= $f ?>" class="form-control" value="<?= $row ? html_escape($row->$f) : '' ?>" <?= $f === 'nama_kampus' ? 'required' : '' ?>>
          <?php endif; ?>
        </div></div>
    <?php endforeach; ?>
    <p class="help-block col-sm-offset-3" style="padding-left:15px">Data ini dipakai sebagai kop pada cetak KRS, KHS, dan Transkrip.</p>
  </div>
  <div class="box-footer text-right"><button class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button></div>
  <?= form_close() ?>
</div>
