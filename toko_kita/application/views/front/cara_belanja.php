<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-title"><h1>Cara Belanja</h1></div>
<div class="row">
  <div class="col-md-7">
    <div class="panel-card">
      <?php foreach (array(
        array('fa-search', 'Pilih produk', 'Cari produk lewat kolom pencarian atau kategori, lalu klik produk untuk melihat detail.'),
        array('fa-cart-plus', 'Masukkan ke keranjang', 'Tentukan jumlah, lalu klik Keranjang atau Beli Sekarang.'),
        array('fa-user-o', 'Masuk / daftar', 'Untuk checkout, masuk ke akun Anda. Belum punya akun? Daftar gratis.'),
        array('fa-map-marker', 'Isi alamat & kota tujuan', 'Ongkos kirim dihitung otomatis dari berat total dan kota tujuan.'),
        array('fa-university', 'Transfer & konfirmasi', 'Transfer sesuai total ke rekening toko, lalu unggah bukti transfer di menu Pesanan Saya.'),
        array('fa-truck', 'Pesanan dikirim', 'Setelah pembayaran diverifikasi, pesanan diproses dan nomor resi dapat dilihat di Pesanan Saya.'),
      ) as $i => $s): ?>
        <div style="display:flex;gap:14px;padding:12px 0;<?= $i ? 'border-top:1px dashed #e2e8f0' : '' ?>">
          <span class="avatar" style="flex-shrink:0"><i class="fa <?= $s[0] ?>"></i></span>
          <div><b style="color:#0f172a"><?= $i + 1 ?>. <?= $s[1] ?></b><div class="text-muted"><?= $s[2] ?></div></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="col-md-5">
    <div class="panel-card">
      <h4>Rekening Pembayaran</h4>
      <?php foreach ($rekening as $r): ?>
        <div class="rek"><i class="fa fa-university"></i><div><div class="text-muted"><?= html_escape($r->nama_bank) ?></div><div class="no"><?= html_escape($r->no_rekening) ?></div><small>a.n. <?= html_escape($r->atas_nama) ?></small></div></div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
