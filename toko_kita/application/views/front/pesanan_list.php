<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-title"><h1>Pesanan Saya</h1></div>
<div class="row">
  <div class="col-md-3"><?php $this->load->view('front/_akun_nav'); ?></div>
  <div class="col-md-9">
    <?php if (empty($pesanan)): ?>
      <div class="panel-card empty"><i class="fa fa-file-text-o"></i><h4>Belum ada pesanan</h4><a href="<?= site_url('produk') ?>" class="btn btn-primary">Mulai Belanja</a></div>
    <?php endif; ?>
    <?php foreach ($pesanan as $o): ?>
      <div class="order-card">
        <div class="top">
          <div><span class="kode"><?= html_escape($o->kode_pesanan) ?></span> <span class="text-muted">&middot; <?= tgl_jam($o->created_at) ?></span></div>
          <?= label_status($o->status) ?>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px">
          <div class="text-muted"><?= $o->jml_item ?> produk &middot; dikirim ke <?= html_escape($o->kota) ?><?= $o->no_resi ? ' &middot; Resi <b>' . html_escape($o->no_resi) . '</b>' : '' ?></div>
          <div><span class="text-muted">Total</span> <b class="price"><?= rupiah($o->total) ?></b>
            <a href="<?= site_url('pesanan/detail/' . $o->kode_pesanan) ?>" class="btn btn-<?= $o->status === 'Menunggu Pembayaran' ? 'accent' : 'default' ?> btn-sm" style="margin-left:10px"><?= $o->status === 'Menunggu Pembayaran' ? 'Bayar Sekarang' : 'Lihat Detail' ?></a></div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
