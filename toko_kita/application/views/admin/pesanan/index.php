<?php defined('BASEPATH') OR exit('No direct script access allowed');
$tab = function ($s) use ($f) { return site_url('admin/pesanan') . '?' . http_build_query(array_filter(array('status' => $s, 'dari' => $f['dari'], 'sampai' => $f['sampai']), 'strlen')); }; ?>
<div class="nav-tabs-custom">
  <ul class="nav nav-tabs">
    <li class="<?= $f['status'] === '' ? 'active' : '' ?>"><a href="<?= $tab('') ?>">Semua <span class="label label-default"><?= array_sum($jumlah) ?></span></a></li>
    <?php foreach (status_pesanan() as $s => $cls): ?>
      <li class="<?= $f['status'] === $s ? 'active' : '' ?>"><a href="<?= $tab($s) ?>"><?= $s ?> <?php if ($jumlah[$s]): ?><span class="label label-<?= $cls ?>"><?= $jumlah[$s] ?></span><?php endif; ?></a></li>
    <?php endforeach; ?>
  </ul>
  <div class="tab-content">
    <form method="get" class="form-inline" style="margin-bottom:14px">
      <input type="hidden" name="status" value="<?= html_escape($f['status']) ?>">
      <span class="filter-label" style="margin-left:0"><i class="fa fa-calendar"></i> Tanggal</span>
      <input type="date" name="dari" class="form-control input-sm" value="<?= html_escape($f['dari']) ?>"> s/d
      <input type="date" name="sampai" class="form-control input-sm" value="<?= html_escape($f['sampai']) ?>">
      <button class="btn btn-default btn-sm">Terapkan</button>
      <?php if ($f['dari'] || $f['sampai']): ?><a href="<?= site_url('admin/pesanan') . ($f['status'] !== '' ? '?status=' . rawurlencode($f['status']) : '') ?>" class="btn btn-link btn-sm">Reset</a><?php endif; ?>
    </form>
    <div class="table-responsive">
      <table class="table table-bordered table-striped datatable">
        <thead><tr><th>Kode</th><th>Tanggal</th><th>Pelanggan</th><th>Kota</th><th>Total</th><th>Status</th><th width="70"></th></tr></thead>
        <tbody>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td><b><?= html_escape($r->kode_pesanan) ?></b></td>
            <td data-order="<?= $r->created_at ?>"><?= tgl_jam($r->created_at) ?></td>
            <td><?= html_escape($r->nama_pelanggan) ?></td>
            <td><?= html_escape($r->kota) ?></td>
            <td data-order="<?= $r->total ?>"><b><?= rupiah($r->total) ?></b></td>
            <td><?= label_status($r->status) ?></td>
            <td><a href="<?= site_url('admin/pesanan/detail/' . $r->id_pesanan) ?>" class="btn btn-xs btn-primary">Detail</a></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
