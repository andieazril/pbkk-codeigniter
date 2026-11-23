<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="box box-primary">
  <div class="box-header with-border">
    <form method="get" class="form-inline">
      <a href="<?= site_url('admin/produk/add') ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah Produk</a>
      <span class="filter-label"><i class="fa fa-filter"></i> Filter</span>
      <div class="form-group"><?= cmb_array('id_kategori', $kategori, $f['id_kategori'], 'class="auto-submit"', 'Semua Kategori') ?></div>
      <div class="form-group"><?= cmb_array('stok', array('menipis' => 'Stok menipis (≤ 5)', 'habis' => 'Stok habis'), $f['stok'], 'class="auto-submit"', 'Semua Stok') ?></div>
    </form>
  </div>
  <div class="box-body table-responsive">
    <table class="table table-bordered table-striped datatable">
      <thead><tr><th width="56">Foto</th><th>Nama Produk</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Terjual</th><th>Status</th><th width="110">Aksi</th></tr></thead>
      <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><img src="<?= gambar_produk($r->gambar) ?>" style="width:44px;height:44px;border-radius:8px;object-fit:cover;border:1px solid #e2e8f0"></td>
          <td><b><?= html_escape($r->nama_produk) ?></b><br><small class="text-muted"><?= number_format($r->berat, 0, ',', '.') ?> gr</small></td>
          <td><?= html_escape($r->nama_kategori) ?></td>
          <td data-order="<?= $r->harga ?>"><?= rupiah($r->harga) ?><?= $r->harga_coret ? '<br><small class="text-muted"><s>' . rupiah($r->harga_coret) . '</s></small>' : '' ?></td>
          <td data-order="<?= $r->stok ?>"><span class="label label-<?= $r->stok == 0 ? 'danger' : ($r->stok <= 5 ? 'warning' : 'success') ?>"><?= $r->stok ?></span></td>
          <td><?= $r->terjual ?></td>
          <td><?= $r->is_aktif === 'y' ? '<span class="label label-success">Tampil</span>' : '<span class="label label-default">Nonaktif</span>' ?></td>
          <td>
            <a href="<?= site_url('p/' . $r->slug) ?>" target="_blank" class="btn btn-xs btn-info" title="Lihat di toko"><i class="fa fa-eye"></i></a>
            <?= btn_aksi('admin/produk', $r->id_produk) ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
