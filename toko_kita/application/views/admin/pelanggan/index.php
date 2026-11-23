<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="box box-primary">
  <div class="box-body table-responsive">
    <table class="table table-bordered table-striped datatable">
      <thead><tr><th>Nama</th><th>Email</th><th>No. HP</th><th>Kota</th><th>Pesanan</th><th>Total Belanja</th><th>Terdaftar</th><th></th></tr></thead>
      <tbody>
      <?php foreach ($rows as $r): ?>
        <tr><td><span class="avatar" style="width:30px;height:30px;font-size:11px;margin-right:6px"><?= inisial($r->nama) ?></span><b><?= html_escape($r->nama) ?></b></td>
          <td><?= html_escape($r->email) ?></td><td><?= html_escape($r->telpon) ?></td><td><?= html_escape($r->kota ?: '-') ?></td>
          <td><?= $r->jml_pesanan ?></td><td data-order="<?= $r->total_belanja ?>"><?= rupiah($r->total_belanja) ?></td>
          <td data-order="<?= $r->created_at ?>"><?= tgl_indo($r->created_at) ?></td>
          <td><a href="<?= site_url('admin/pelanggan/detail/' . $r->id_pelanggan) ?>" class="btn btn-xs btn-info"><i class="fa fa-eye"></i></a></td></tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
