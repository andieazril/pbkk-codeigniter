<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="hero">
  <i class="fa fa-shopping-bag hero-icon"></i>
  <h1><?= html_escape(info_toko('slogan')) ?></h1>
  <p>Temukan fashion, elektronik, dan kebutuhan rumah pilihan. Bayar via transfer bank, pesanan langsung kami proses.</p>
  <a href="<?= site_url('produk') ?>" class="btn btn-light btn-lg">Mulai Belanja <i class="fa fa-arrow-right"></i></a>
</section>

<div class="usp">
  <div class="it"><i class="fa fa-truck"></i><div><b>Kirim Seluruh Indonesia</b><span>Ongkir dihitung otomatis</span></div></div>
  <div class="it"><i class="fa fa-shield"></i><div><b>Belanja Aman</b><span>Pembayaran diverifikasi admin</span></div></div>
  <div class="it"><i class="fa fa-refresh"></i><div><b>Stok Real-time</b><span>Tidak ada pesanan fiktif</span></div></div>
  <div class="it"><i class="fa fa-whatsapp"></i><div><b>Bantuan Cepat</b><span>Hubungi kami via WhatsApp</span></div></div>
</div>

<section class="section">
  <div class="section-head"><h2>Kategori</h2></div>
  <div class="cat-grid">
    <?php foreach ($kategori_menu as $k): ?>
      <a href="<?= site_url('produk?kategori=' . $k->slug) ?>" class="cat-card"><i class="<?= html_escape($k->icon) ?>"></i><?= html_escape($k->nama_kategori) ?></a>
    <?php endforeach; ?>
  </div>
</section>

<section class="section">
  <div class="section-head"><h2>🔥 Paling Laris</h2><a href="<?= site_url('produk?urut=terlaris') ?>">Lihat semua <i class="fa fa-angle-right"></i></a></div>
  <div class="prod-grid">
    <?php foreach ($terlaris as $p) $this->load->view('front/_produk_card', array('p' => $p)); ?>
  </div>
</section>

<section class="section">
  <div class="section-head"><h2>Produk Terbaru</h2><a href="<?= site_url('produk') ?>">Lihat semua <i class="fa fa-angle-right"></i></a></div>
  <div class="prod-grid">
    <?php foreach ($terbaru as $p) $this->load->view('front/_produk_card', array('p' => $p)); ?>
  </div>
</section>
