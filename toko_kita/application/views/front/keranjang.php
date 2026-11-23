<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-title"><h1>Keranjang Belanja</h1></div>
<?php if (empty($items)): ?>
  <div class="panel-card empty"><i class="fa fa-shopping-cart"></i><h4>Keranjang masih kosong</h4><p>Yuk, cari produk favoritmu.</p>
    <a href="<?= site_url('produk') ?>" class="btn btn-primary btn-lg">Mulai Belanja</a></div>
<?php else: ?>
<div class="row">
  <div class="col-md-8">
    <div class="panel-card">
      <?= form_open('keranjang/ubah', array('id' => 'form-cart')) ?>
      <?php foreach ($items as $i): ?>
        <div class="cart-item">
          <img src="<?= gambar_produk($i->gambar) ?>" alt="">
          <div class="info">
            <a href="<?= site_url('p/' . $i->slug) ?>"><?= html_escape($i->nama_produk) ?></a>
            <div class="price" style="font-size:15px;margin-top:4px"><?= rupiah($i->harga) ?></div>
            <?php if ($i->stok_kurang): ?><div class="warn"><i class="fa fa-exclamation-triangle"></i> Stok tersisa <?= $i->stok ?>, kurangi jumlah.</div><?php endif; ?>
          </div>
          <div class="qty"><button type="button" data-d="-">−</button><input type="number" class="qty-input" name="qty[<?= $i->id_produk ?>]" value="<?= $i->qty ?>" min="1" max="<?= max(1, $i->stok) ?>"><button type="button" data-d="+">+</button></div>
          <div style="width:120px;text-align:right"><b><?= rupiah($i->subtotal) ?></b></div>
          <button type="submit" form="hapus-<?= $i->id_produk ?>" class="btn btn-link text-danger" title="Hapus" onclick="return confirm('Hapus produk ini dari keranjang?')"><i class="fa fa-trash-o"></i></button>
        </div>
      <?php endforeach; ?>
      <?= form_close() ?>
      <?php foreach ($items as $i): ?><?= form_open('keranjang/hapus/' . $i->id_produk, array('id' => 'hapus-' . $i->id_produk)) ?><?= form_close() ?><?php endforeach; ?>
    </div>
    <a href="<?= site_url('produk') ?>"><i class="fa fa-arrow-left"></i> Lanjut belanja</a>
  </div>
  <div class="col-md-4">
    <div class="panel-card summary">
      <h4>Ringkasan Belanja</h4>
      <div class="row-sum"><span>Total harga (<?= $ringkas['qty'] ?> barang)</span><b><?= rupiah($ringkas['subtotal']) ?></b></div>
      <div class="row-sum"><span>Total berat</span><span><?= number_format($ringkas['berat'] / 1000, 2, ',', '.') ?> kg</span></div>
      <div class="row-sum"><span>Ongkos kirim</span><span class="text-muted">dihitung saat checkout</span></div>
      <div class="row-sum total"><span>Subtotal</span><span class="price"><?= rupiah($ringkas['subtotal']) ?></span></div>
      <?php if ($ringkas['stok_kurang']): ?>
        <div class="alert alert-warning" style="margin-top:12px">Sesuaikan jumlah produk yang melebihi stok.</div>
      <?php else: ?>
        <a href="<?= site_url('checkout') ?>" class="btn btn-accent btn-lg btn-block" style="margin-top:14px">Checkout <i class="fa fa-arrow-right"></i></a>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php ob_start(); ?>
<script>
  var t; $('.qty-input').on('change', function () { clearTimeout(t); t = setTimeout(function () { $('#form-cart').submit(); }, 400); });
</script>
<?php $this->load->vars(array('script' => ob_get_clean())); ?>
<?php endif; ?>
