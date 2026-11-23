<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="box box-primary">
  <div class="box-header with-border"><a href="<?= site_url('users/add') ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah User</a></div>
  <div class="box-body table-responsive">
    <table class="table table-bordered table-striped datatable">
      <thead><tr><th>No</th><th>Nama Lengkap</th><th>Username</th><th>Level</th><th>Terhubung ke</th><th width="90">Aksi</th></tr></thead>
      <tbody>
      <?php $no = 1; foreach ($rows as $r): ?>
        <tr><td><?= $no++ ?></td><td><?= html_escape($r->nama_lengkap) ?></td><td><?= html_escape($r->username) ?></td>
          <td><span class="label label-<?= $r->id_level_user == 1 ? 'danger' : ($r->id_level_user == 2 ? 'success' : 'info') ?>"><?= html_escape($r->nama_level) ?></span></td>
          <td><?= $r->id_dosen ? 'Dosen #' . (int) $r->id_dosen : ($r->nim ? 'NIM ' . html_escape($r->nim) : '-') ?></td>
          <td><?= btn_aksi('users', $r->id_user) ?></td></tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
