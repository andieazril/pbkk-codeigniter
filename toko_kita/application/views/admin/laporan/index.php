<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="box box-default">
  <div class="box-body">
    <form method="get" class="form-inline">
      <span class="filter-label" style="margin-left:0"><i class="fa fa-calendar"></i> Periode</span>
      <input type="date" name="dari" class="form-control" value="<?= $dari ?>"> s/d <input type="date" name="sampai" class="form-control" value="<?= $sampai ?>">
      <button class="btn btn-primary btn-sm">Tampilkan</button>
      <a href="<?= site_url('admin/laporan/cetak?dari=' . $dari . '&sampai=' . $sampai) ?>" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-print"></i> Cetak</a>
    </form>
  </div>
</div>
<div class="row">
  <div class="col-sm-3"><div class="stat-card stat-indigo"><div class="stat-icon"><i class="fa fa-money"></i></div><div><div class="stat-value" style="font-size:20px"><?= rupiah($ringkas->penjualan) ?></div><div class="stat-label">Penjualan produk</div></div></div></div>
  <div class="col-sm-3"><div class="stat-card stat-green"><div class="stat-icon"><i class="fa fa-shopping-cart"></i></div><div><div class="stat-value"><?= $ringkas->jml ?></div><div class="stat-label">Pesanan terjual</div></div></div></div>
  <div class="col-sm-3"><div class="stat-card stat-amber"><div class="stat-icon"><i class="fa fa-truck"></i></div><div><div class="stat-value" style="font-size:20px"><?= rupiah($ringkas->ongkir) ?></div><div class="stat-label">Ongkos kirim</div></div></div></div>
  <div class="col-sm-3"><div class="stat-card stat-rose"><div class="stat-icon"><i class="fa fa-calculator"></i></div><div><div class="stat-value" style="font-size:20px"><?= rupiah($ringkas->jml ? $ringkas->penjualan / $ringkas->jml : 0) ?></div><div class="stat-label">Rata-rata per pesanan</div></div></div></div>
</div>
<div class="row">
  <div class="col-md-7">
    <div class="box box-primary">
      <div class="box-header with-border"><h3 class="box-title">Daftar Transaksi <?= tgl_indo($dari) ?> – <?= tgl_indo($sampai) ?></h3></div>
      <div class="box-body table-responsive">
        <table class="table table-bordered table-striped datatable">
          <thead><tr><th>Kode</th><th>Tanggal</th><th>Pelanggan</th><th>Subtotal</th><th>Ongkir</th><th>Total</th></tr></thead>
          <tbody>
          <?php foreach ($rows as $r): ?>
            <tr><td><a href="<?= site_url('admin/pesanan/detail/' . $r->id_pesanan) ?>"><?= html_escape($r->kode_pesanan) ?></a></td><td data-order="<?= $r->created_at ?>"><?= tgl_indo($r->created_at) ?></td>
              <td><?= html_escape($r->nama_pelanggan) ?></td><td><?= rupiah($r->subtotal) ?></td><td><?= rupiah($r->ongkir) ?></td><td><b><?= rupiah($r->total) ?></b></td></tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-md-5">
    <div class="box box-success">
      <div class="box-header with-border"><h3 class="box-title">Produk Terlaris</h3></div>
      <div class="box-body">
        <?php if (empty($terlaris)): ?><div class="empty-state"><i class="fa fa-bar-chart"></i>Belum ada penjualan pada periode ini.</div><?php endif; ?>
        <?php $max = 1; foreach ($terlaris as $t) $max = max($max, (int) $t->qty); ?>
        <?php foreach ($terlaris as $t): ?>
          <div class="bar-row"><div class="bar-meta"><span><?= html_escape($t->nama_produk) ?></span><b><?= $t->qty ?> pcs &middot; <?= rupiah($t->omzet) ?></b></div>
            <div class="bar-track"><div class="bar-fill" style="width:<?= round($t->qty / $max * 100) ?>%"></div></div></div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>
