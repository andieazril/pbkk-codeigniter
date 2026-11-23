<?php defined('BASEPATH') OR exit('No direct script access allowed');
$qs = function ($ubah) use ($f) { return site_url('produk') . '?' . http_build_query(array_filter(array_merge($f, $ubah), 'strlen')); }; ?>
<div class="page-title">
  <div class="crumb"><a href="<?= site_url() ?>">Beranda</a><i class="fa fa-chevron-right"></i>Produk<?= $kat ? '<i class="fa fa-chevron-right"></i>' . html_escape($kat->nama_kategori) : '' ?></div>
  <h1><?= html_escape($title) ?></h1>
</div>
<div class="row">
  <div class="col-md-3 hidden-sm hidden-xs">
    <div class="panel-card">
      <h4>Kategori</h4>
      <ul class="side-cat">
        <li><a href="<?= $qs(array('kategori' => '', 'hal' => '')) ?>" class="<?= !$f['kategori'] ? 'active' : '' ?>">Semua Produk</a></li>
        <?php foreach ($kategori as $k): ?>
          <li><a href="<?= $qs(array('kategori' => $k->slug, 'hal' => '')) ?>" class="<?= $f['kategori'] === $k->slug ? 'active' : '' ?>"><?= html_escape($k->nama_kategori) ?> <span class="text-muted"><?= $k->jml_produk ?></span></a></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
  <div class="col-md-9">
    <div class="toolbar">
      <div class="text-muted">Menampilkan <b><?= $total ?></b> produk<?= $f['q'] ? ' untuk "<b>' . html_escape($f['q']) . '</b>"' : '' ?></div>
      <form method="get" class="form-inline">
        <?php foreach (array('kategori', 'q') as $k): if ($f[$k] !== ''): ?><input type="hidden" name="<?= $k ?>" value="<?= html_escape($f[$k]) ?>"><?php endif; endforeach; ?>
        <select name="urut" class="form-control auto-submit">
          <?php foreach (array('' => 'Terbaru', 'terlaris' => 'Terlaris', 'murah' => 'Harga terendah', 'mahal' => 'Harga tertinggi') as $v => $l): ?>
            <option value="<?= $v ?>" <?= $f['urut'] === $v ? 'selected' : '' ?>>Urutkan: <?= $l ?></option>
          <?php endforeach; ?>
        </select>
      </form>
    </div>
    <?php if (empty($produk)): ?>
      <div class="panel-card empty"><i class="fa fa-search"></i><h4>Produk tidak ditemukan</h4><p>Coba kata kunci lain atau lihat <a href="<?= site_url('produk') ?>">semua produk</a>.</p></div>
    <?php else: ?>
      <div class="prod-grid cols-3">
        <?php foreach ($produk as $p) $this->load->view('front/_produk_card', array('p' => $p)); ?>
      </div>
      <?php if ($jml_hal > 1): ?>
        <div class="text-center"><ul class="pagination">
          <?php for ($i = 1; $i <= $jml_hal; $i++): ?><li class="<?= $i === $hal ? 'active' : '' ?>"><a href="<?= $qs(array('hal' => $i)) ?>"><?= $i ?></a></li><?php endfor; ?>
        </ul></div>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</div>
