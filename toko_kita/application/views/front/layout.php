<?php defined('BASEPATH') OR exit('No direct script access allowed');
$seg = $this->uri->segment(1); $kat_aktif = $this->input->get('kategori'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= isset($title) ? html_escape($title) . ' | ' : '' ?><?= html_escape(info_toko('nama_toko')) ?> — <?= html_escape(info_toko('slogan')) ?></title>
  <meta name="description" content="<?= html_escape(isset($meta_desc) ? $meta_desc : info_toko('deskripsi')) ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendor/font-awesome/css/font-awesome.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/toko.css') ?>">
</head>
<body>
<div class="topbar hidden-xs">
  <div class="container">
    <i class="fa fa-truck"></i> Kirim ke seluruh Indonesia
    <span class="sep">|</span><i class="fa fa-phone"></i> <?= html_escape(info_toko('telpon')) ?>
    <span class="pull-right"><a href="<?= site_url('pesanan') ?>">Lacak Pesanan</a><span class="sep">|</span><a href="<?= site_url('home/cara_belanja') ?>">Cara Belanja</a></span>
  </div>
</div>
<header class="site-header">
  <div class="container">
    <div class="bar">
      <a href="<?= site_url() ?>" class="brand"><span class="logo"><i class="fa fa-shopping-bag"></i></span><?= html_escape(info_toko('nama_toko')) ?></a>
      <form class="search" action="<?= site_url('produk') ?>" method="get">
        <input type="text" name="q" placeholder="Cari produk, misalnya: kemeja, power bank..." value="<?= html_escape($this->input->get('q')) ?>">
        <button type="submit"><i class="fa fa-search"></i></button>
      </form>
      <div class="head-actions">
        <a href="<?= site_url('keranjang') ?>" class="head-btn" title="Keranjang"><span class="ic"><i class="fa fa-shopping-cart"></i>
          <?php if ($jml_keranjang): ?><span class="badge"><?= $jml_keranjang ?></span><?php endif; ?></span><span class="lbl">Keranjang</span></a>
        <?php if ($this->session->userdata('id_pelanggan')): ?>
          <div class="dropdown" style="display:inline-block">
            <a href="#" class="head-btn dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user-circle-o"></i>
              <span class="lbl"><?= html_escape(explode(' ', $this->session->userdata('nama_pelanggan'))[0]) ?></span> <i class="fa fa-angle-down" style="font-size:13px"></i></a>
            <ul class="dropdown-menu dropdown-menu-right">
              <li><a href="<?= site_url('pesanan') ?>"><i class="fa fa-file-text-o"></i> Pesanan Saya</a></li>
              <li><a href="<?= site_url('akun') ?>"><i class="fa fa-user-o"></i> Profil</a></li>
              <li><a href="<?= site_url('akun/logout') ?>"><i class="fa fa-sign-out"></i> Keluar</a></li>
            </ul>
          </div>
        <?php else: ?>
          <a href="<?= site_url('akun/login') ?>" class="head-btn"><i class="fa fa-user-o"></i><span class="lbl">Masuk</span></a>
          <a href="<?= site_url('akun/daftar') ?>" class="btn btn-primary hidden-xs">Daftar</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</header>
<nav class="catnav">
  <div class="container">
    <ul>
      <li><a href="<?= site_url('produk') ?>" class="<?= $seg === 'produk' && !$kat_aktif ? 'active' : '' ?>"><i class="fa fa-th-large"></i> Semua Produk</a></li>
      <?php foreach ($kategori_menu as $k): ?>
        <li><a href="<?= site_url('produk?kategori=' . $k->slug) ?>" class="<?= $kat_aktif === $k->slug ? 'active' : '' ?>"><i class="<?= html_escape($k->icon) ?>"></i> <?= html_escape($k->nama_kategori) ?></a></li>
      <?php endforeach; ?>
    </ul>
  </div>
</nav>

<main class="container" style="min-height:50vh">
  <div style="margin-top:18px"><?= show_alert() ?></div>
  <?= $content ?>
</main>

<footer class="site-footer">
  <div class="container">
    <div class="row">
      <div class="col-md-5">
        <a href="<?= site_url() ?>" class="brand" style="color:#fff !important"><span class="logo"><i class="fa fa-shopping-bag"></i></span><?= html_escape(info_toko('nama_toko')) ?></a>
        <p style="margin-top:14px;max-width:380px"><?= html_escape(info_toko('deskripsi')) ?></p>
      </div>
      <div class="col-md-3 col-sm-6">
        <h5>Belanja</h5>
        <ul>
          <?php foreach (array_slice($kategori_menu, 0, 5) as $k): ?><li><a href="<?= site_url('produk?kategori=' . $k->slug) ?>"><?= html_escape($k->nama_kategori) ?></a></li><?php endforeach; ?>
        </ul>
      </div>
      <div class="col-md-4 col-sm-6">
        <h5>Hubungi Kami</h5>
        <ul>
          <li><i class="fa fa-map-marker"></i> <?= html_escape(info_toko('alamat') . ', ' . info_toko('kota')) ?></li>
          <li><i class="fa fa-phone"></i> <?= html_escape(info_toko('telpon')) ?></li>
          <li><i class="fa fa-envelope-o"></i> <?= html_escape(info_toko('email')) ?></li>
          <li><a href="<?= site_url('home/cara_belanja') ?>"><i class="fa fa-question-circle-o"></i> Cara Belanja</a></li>
        </ul>
      </div>
    </div>
    <div class="copy">&copy; <?= date('Y') ?> <?= html_escape(info_toko('nama_toko')) ?>. Semua hak dilindungi.</div>
  </div>
</footer>
<?php if (info_toko('whatsapp')): ?>
  <a class="wa-float" href="https://wa.me/<?= html_escape(info_toko('whatsapp')) ?>" target="_blank" rel="noopener" title="Chat WhatsApp"><i class="fa fa-whatsapp"></i></a>
<?php endif; ?>
<script src="<?= base_url('assets/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.min.js') ?>"></script>
<script>
  // tombol + / - jumlah
  $(document).on('click', '.qty button', function () {
    var inp = $(this).siblings('input'), v = parseInt(inp.val() || 1, 10), max = parseInt(inp.attr('max') || 999, 10);
    v = $(this).data('d') === '+' ? Math.min(max, v + 1) : Math.max(1, v - 1);
    inp.val(v).trigger('change');
  });
  $(document).on('change', '.auto-submit', function () { this.form.submit(); });
</script>
<?php if (isset($script)) echo $script; ?>
</body>
</html>
