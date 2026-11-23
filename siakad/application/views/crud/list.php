<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="box box-primary">
  <div class="box-header with-border">
    <a href="<?= site_url($module . '/add') ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah Data</a>
  </div>
  <div class="box-body table-responsive">
    <table class="table table-bordered table-striped datatable">
      <thead>
        <tr>
          <th width="40">No</th>
          <?php if (!$pk_auto): ?><th>Kode</th><?php endif; ?>
          <?php foreach ($fields as $name => $f): if (isset($f['list']) && !$f['list']) continue; ?>
            <th><?= html_escape($f['label']) ?></th>
          <?php endforeach; ?>
          <th width="130">Aksi</th>
        </tr>
      </thead>
      <tbody>
      <?php $no = 1; foreach ($rows as $r): ?>
        <tr>
          <td><?= $no++ ?></td>
          <?php if (!$pk_auto): ?><td><?= html_escape($r->{$pk}) ?></td><?php endif; ?>
          <?php foreach ($fields as $name => $f): if (isset($f['list']) && !$f['list']) continue;
            $v = $r->{$name};
            if (isset($f['display']) && isset($r->{$f['display']})) $v = $r->{$f['display']};
            elseif (isset($f['options'][$v])) $v = $f['options'][$v];
            elseif (isset($f['type']) && $f['type'] === 'date') $v = tgl_indo($v); ?>
            <td><?= html_escape($v) ?></td>
          <?php endforeach; ?>
          <td><?= btn_aksi($module, $r->{$pk}) ?> <?= $ctrl->_row_actions($r) ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
