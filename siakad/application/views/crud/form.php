<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="box box-primary">
  <div class="box-header with-border"><h3 class="box-title"><?= html_escape($subtitle) ?></h3></div>
  <?= form_open($module . '/save', array('class' => 'form-horizontal')) ?>
  <input type="hidden" name="_id" value="<?= $row ? html_escape($row->{$pk}) : '' ?>">
  <div class="box-body">
    <?php foreach ($fields as $name => $f):
      $type  = isset($f['type']) ? $f['type'] : 'text';
      $value = $row ? $row->{$name} : (isset($f['default']) ? $f['default'] : '');
      $req   = (isset($f['rules']) && strpos($f['rules'], 'required') !== FALSE) ? 'required' : ''; ?>
      <div class="form-group">
        <label class="col-sm-3 control-label"><?= html_escape($f['label']) ?> <?= $req ? '<span class="text-red">*</span>' : '' ?></label>
        <div class="col-sm-7">
          <?php if ($type === 'select'): ?>
            <?= cmb_array($name, $f['options'], $value, $req) ?>
          <?php elseif ($type === 'textarea'): ?>
            <textarea name="<?= $name ?>" class="form-control" rows="3" <?= $req ?>><?= html_escape($value) ?></textarea>
          <?php else: ?>
            <input type="<?= $type ?>" name="<?= $name ?>" class="form-control" value="<?= html_escape($value) ?>" <?= $req ?>>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <div class="box-footer">
    <a href="<?= site_url($module) ?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali</a>
    <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-save"></i> Simpan</button>
  </div>
  <?= form_close() ?>
</div>
