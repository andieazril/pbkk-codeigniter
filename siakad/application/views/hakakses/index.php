<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="nav-tabs-custom">
  <ul class="nav nav-tabs">
    <?php foreach ($levels as $l): ?>
      <li class="<?= $l->id_level_user == $id_level ? 'active' : '' ?>"><a href="<?= site_url('hakakses/index/' . $l->id_level_user) ?>"><i class="fa fa-key"></i> <?= html_escape($l->nama_level) ?></a></li>
    <?php endforeach; ?>
  </ul>
  <div class="tab-content">
    <?= form_open('hakakses/save') ?>
    <input type="hidden" name="id_level_user" value="<?= $id_level ?>">
    <table class="table table-bordered table-hover">
      <thead><tr><th width="60" class="text-center"><input type="checkbox" onclick="$('.cb-menu').prop('checked', this.checked)"></th><th>Menu</th><th>Link</th></tr></thead>
      <tbody>
      <?php foreach ($menus as $m): ?>
        <tr>
          <td class="text-center"><input type="checkbox" class="cb-menu" name="id_menu[]" value="<?= $m->id_menu ?>" <?= in_array($m->id_menu, $akses) ? 'checked' : '' ?>></td>
          <td><?= $m->is_main_menu ? '&nbsp;&nbsp;&nbsp;&nbsp;&#8627; ' : '<b>' ?><i class="<?= html_escape($m->icon) ?>"></i> <?= html_escape($m->nama_menu) ?><?= $m->is_main_menu ? '' : '</b>' ?></td>
          <td><code><?= html_escape($m->link) ?></code></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    <button class="btn btn-primary"><i class="fa fa-save"></i> Simpan Hak Akses</button>
    <?= form_close() ?>
  </div>
</div>
