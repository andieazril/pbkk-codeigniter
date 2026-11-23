<?php defined('BASEPATH') OR exit('No direct script access allowed');
$disc = diskon_persen($p->harga, $p->harga_coret); ?>
<a href="<?= site_url('p/' . $p->slug) ?>" class="prod-card <?= $p->stok < 1 ? 'habis' : '' ?>">
  <?php if ($disc): ?><span class="badge-disc">-<?= $disc ?>%</span><?php endif; ?>
  <?php if ($p->stok < 1): ?><span class="badge-out">Stok habis</span><?php endif; ?>
  <div class="thumb"><img src="<?= gambar_produk($p->gambar) ?>" alt="<?= html_escape($p->nama_produk) ?>" loading="lazy"></div>
  <div class="body">
    <div class="cat"><?= html_escape($p->nama_kategori) ?></div>
    <div class="name"><?= html_escape($p->nama_produk) ?></div>
    <div><span class="price"><?= rupiah($p->harga) ?></span><?php if ($disc): ?><span class="price-old"><?= rupiah($p->harga_coret) ?></span><?php endif; ?></div>
    <div class="meta"><span><i class="fa fa-check-circle" style="color:#10b981"></i> <?= $p->terjual ?> terjual</span><span><?= $p->stok > 0 ? 'Stok ' . $p->stok : '' ?></span></div>
  </div>
</a>
