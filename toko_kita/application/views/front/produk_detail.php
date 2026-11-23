<?php defined('BASEPATH') OR exit('No direct script access allowed');
$disc = diskon_persen($p->harga, $p->harga_coret); ?>
<div class="page-title" style="margin-bottom:10px">
  <div class="crumb"><a href="<?= site_url() ?>">Beranda</a><i class="fa fa-chevron-right"></i><a href="<?= site_url('produk?kategori=' . $p->slug_kategori) ?>"><?= html_escape($p->nama_kategori) ?></a><i class="fa fa-chevron-right"></i><?= html_escape($p->nama_produk) ?></div>
</div>
<div class="row">
  <div class="col-md-5"><div class="pd-img"><img src="<?= gambar_produk($p->gambar) ?>" alt="<?= html_escape($p->nama_produk) ?>"></div></div>
  <div class="col-md-7 pd-info">
    <a href="<?= site_url('produk?kategori=' . $p->slug_kategori) ?>" class="label label-primary"><?= html_escape($p->nama_kategori) ?></a>
    <h1><?= html_escape($p->nama_produk) ?></h1>
    <div class="pd-stats"><span><i class="fa fa-check-circle" style="color:#10b981"></i> <?= $p->terjual ?> terjual</span>
      <span><i class="fa fa-cube"></i> <?= $p->stok > 0 ? 'Stok ' . $p->stok : '<b class="text-danger">Stok habis</b>' ?></span></div>
    <div><span class="price"><?= rupiah($p->harga) ?></span>
      <?php if ($disc): ?><span class="price-old"><?= rupiah($p->harga_coret) ?></span> <span class="label label-danger">Hemat <?= $disc ?>%</span><?php endif; ?></div>

    <div class="pd-box">
      <?php if ($p->stok > 0): ?>
        <?= form_open('keranjang/tambah') ?>
          <input type="hidden" name="id_produk" value="<?= $p->id_produk ?>">
          <label style="display:block">Jumlah</label>
          <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
            <div class="qty"><button type="button" data-d="-">−</button><input type="number" name="qty" value="1" min="1" max="<?= $p->stok ?>"><button type="button" data-d="+">+</button></div>
            <button class="btn btn-outline btn-lg" name="aksi" value="keranjang"><i class="fa fa-cart-plus"></i> Keranjang</button>
            <button class="btn btn-accent btn-lg" name="aksi" value="beli"><i class="fa fa-bolt"></i> Beli Sekarang</button>
          </div>
        <?= form_close() ?>
      <?php else: ?>
        <div class="text-muted"><i class="fa fa-info-circle"></i> Stok produk ini sedang habis. Silakan cek kembali nanti.</div>
      <?php endif; ?>
    </div>

    <table class="spec">
      <tr><td>Kategori</td><td><?= html_escape($p->nama_kategori) ?></td></tr>
      <tr><td>Berat</td><td><?= number_format($p->berat, 0, ',', '.') ?> gram</td></tr>
      <tr><td>Pengiriman</td><td>Dari <?= html_escape(info_toko('kota')) ?>, ongkir dihitung saat checkout</td></tr>
    </table>
    <h4 style="font-weight:700;margin-top:24px">Deskripsi Produk</h4>
    <div style="line-height:1.7;color:#334155"><?= nl2br(html_escape($p->deskripsi)) ?></div>
  </div>
</div>
<?php if ($terkait): ?>
<section class="section">
  <div class="section-head"><h2>Produk Terkait</h2></div>
  <div class="prod-grid"><?php foreach ($terkait as $t) $this->load->view('front/_produk_card', array('p' => $t)); ?></div>
</section>
<?php endif; ?>
