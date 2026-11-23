<?php defined('BASEPATH') OR exit('No direct script access allowed');
$max = 1; foreach ($grafik as $g) $max = max($max, $g->penjualan); ?>
<div class="welcome-banner">
  <i class="fa fa-shopping-bag banner-icon"></i>
  <h2>Halo, <?= html_escape($this->session->userdata('admin_nama')) ?>!</h2>
  <p>Ringkasan penjualan <?= html_escape(info_toko('nama_toko')) ?> hari ini.</p>
  <span class="banner-chip"><i class="fa fa-calendar"></i> <?= tgl_indo(date('Y-m-d')) ?></span>
</div>
<div class="row">
  <div class="col-lg-3 col-sm-6"><a href="<?= site_url('admin/laporan') ?>" class="stat-card stat-indigo"><div class="stat-icon"><i class="fa fa-money"></i></div>
    <div><div class="stat-value" style="font-size:21px"><?= rupiah($bulan_ini->penjualan) ?></div><div class="stat-label">Penjualan bulan ini</div></div></a></div>
  <div class="col-lg-3 col-sm-6"><a href="<?= site_url('admin/pesanan') ?>" class="stat-card stat-green"><div class="stat-icon"><i class="fa fa-shopping-cart"></i></div>
    <div><div class="stat-value"><?= $bulan_ini->jml ?></div><div class="stat-label">Pesanan terjual bulan ini</div></div></a></div>
  <div class="col-lg-3 col-sm-6"><a href="<?= site_url('admin/pesanan?status=Menunggu+Verifikasi') ?>" class="stat-card stat-amber"><div class="stat-icon"><i class="fa fa-hourglass-half"></i></div>
    <div><div class="stat-value"><?= $status['Menunggu Verifikasi'] ?></div><div class="stat-label">Pembayaran perlu dicek</div></div></a></div>
  <div class="col-lg-3 col-sm-6"><a href="<?= site_url('admin/pesanan?status=Diproses') ?>" class="stat-card stat-rose"><div class="stat-icon"><i class="fa fa-cube"></i></div>
    <div><div class="stat-value"><?= $status['Diproses'] ?></div><div class="stat-label">Pesanan perlu dikirim</div></div></a></div>
</div>
<div class="row">
  <div class="col-md-8">
    <div class="box box-primary">
      <div class="box-header with-border"><h3 class="box-title">Penjualan 30 Hari Terakhir</h3></div>
      <div class="box-body">
        <div style="display:flex;align-items:flex-end;gap:4px;height:180px;border-bottom:1px solid #e2e8f0;padding-bottom:2px">
          <?php foreach ($grafik as $g): $h = $g->penjualan ? max(4, round($g->penjualan / $max * 170)) : 2; ?>
            <div title="<?= tgl_indo($g->tgl) ?>: <?= rupiah($g->penjualan) ?> (<?= $g->jml ?> pesanan)" style="flex:1;height:<?= $h ?>px;border-radius:4px 4px 0 0;background:<?= $g->penjualan ? 'linear-gradient(180deg,#818cf8,#4f46e5)' : '#e2e8f0' ?>"></div>
          <?php endforeach; ?>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:12px;color:#94a3b8;margin-top:6px"><span><?= tgl_indo($grafik[0]->tgl) ?></span><span>Hari ini</span></div>
      </div>
    </div>
    <div class="box">
      <div class="box-header with-border"><h3 class="box-title">Pesanan Terbaru</h3><div class="box-tools"><a href="<?= site_url('admin/pesanan') ?>" class="btn btn-default btn-sm">Semua pesanan</a></div></div>
      <div class="box-body table-responsive">
        <table class="table">
          <thead><tr><th>Kode</th><th>Pelanggan</th><th>Total</th><th>Status</th><th></th></tr></thead>
          <tbody>
          <?php foreach ($terbaru as $o): ?>
            <tr><td><b><?= html_escape($o->kode_pesanan) ?></b><br><small class="text-muted"><?= tgl_jam($o->created_at) ?></small></td><td><?= html_escape($o->nama_pelanggan) ?></td>
              <td><?= rupiah($o->total) ?></td><td><?= label_status($o->status) ?></td><td><a href="<?= site_url('admin/pesanan/detail/' . $o->id_pesanan) ?>" class="btn btn-xs btn-primary">Detail</a></td></tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="box box-success">
      <div class="box-header with-border"><h3 class="box-title">Status Pesanan</h3></div>
      <div class="box-body">
        <table class="table table-info">
          <?php foreach ($status as $s => $n): ?><tr><th style="width:auto"><a href="<?= site_url('admin/pesanan?status=' . rawurlencode($s)) ?>"><?= $s ?></a></th><td class="text-right"><b><?= $n ?></b></td></tr><?php endforeach; ?>
          <tr><th>Total pelanggan</th><td class="text-right"><b><?= $jml_pelanggan ?></b></td></tr>
        </table>
      </div>
    </div>
    <div class="box box-warning">
      <div class="box-header with-border"><h3 class="box-title">Stok Menipis</h3></div>
      <div class="box-body">
        <?php if (empty($stok_menipis)): ?><div class="empty-state"><i class="fa fa-check-circle"></i>Semua stok aman.</div><?php endif; ?>
        <?php foreach ($stok_menipis as $p): ?>
          <div class="schedule-item"><img src="<?= gambar_produk($p->gambar) ?>" style="width:40px;height:40px;border-radius:8px;object-fit:cover">
            <div style="flex:1"><div class="title" style="font-size:13px"><?= html_escape($p->nama_produk) ?></div></div>
            <span class="label label-<?= $p->stok == 0 ? 'danger' : 'warning' ?>"><?= $p->stok == 0 ? 'Habis' : 'Sisa ' . $p->stok ?></span>
            <a href="<?= site_url('admin/produk/edit/' . $p->id_produk) ?>" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></a></div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>
