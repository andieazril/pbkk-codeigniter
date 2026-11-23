<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8"><title>Invoice <?= html_escape($ps->kode_pesanan) ?></title>
  <style>
    @font-face { font-family: 'Inter'; font-weight: 400; src: url('<?= base_url('assets/vendor/inter/inter-latin-400-normal.woff2') ?>') format('woff2'); }
    @font-face { font-family: 'Inter'; font-weight: 700; src: url('<?= base_url('assets/vendor/inter/inter-latin-700-normal.woff2') ?>') format('woff2'); }
    body { font-family: 'Inter', Arial, sans-serif; color: #0f172a; margin: 0; background: #f1f5f9; font-size: 13.5px; }
    .page { max-width: 800px; margin: 24px auto; background: #fff; padding: 40px; border-radius: 12px; }
    .head { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #4f46e5; padding-bottom: 18px; }
    .brand { font-size: 22px; font-weight: 700; color: #4f46e5; } .muted { color: #64748b; }
    h1 { margin: 0; font-size: 26px; letter-spacing: 2px; color: #0f172a; text-align: right; }
    .grid { display: flex; gap: 30px; margin: 22px 0; } .grid > div { flex: 1; }
    .grid h4 { margin: 0 0 6px; font-size: 11.5px; text-transform: uppercase; letter-spacing: .06em; color: #64748b; }
    table { width: 100%; border-collapse: collapse; }
    th { text-align: left; background: #f8fafc; font-size: 11.5px; text-transform: uppercase; color: #64748b; padding: 10px; border-bottom: 1px solid #e2e8f0; }
    td { padding: 10px; border-bottom: 1px solid #f1f5f9; } .r { text-align: right; }
    .tot td { border: 0; padding: 5px 10px; } .tot .big td { font-size: 17px; font-weight: 700; border-top: 2px solid #0f172a; padding-top: 10px; }
    .status { display: inline-block; padding: 4px 12px; border-radius: 999px; background: #eef2ff; color: #4f46e5; font-weight: 700; font-size: 12px; }
    .foot { margin-top: 30px; text-align: center; color: #94a3b8; font-size: 12px; }
    .btn { display: inline-block; margin: 0 auto 12px; padding: 8px 16px; background: #4f46e5; color: #fff; border: 0; border-radius: 8px; cursor: pointer; font-family: inherit; }
    @media print { body { background: #fff; } .page { margin: 0; padding: 0; } .noprint { display: none; } }
  </style>
</head>
<body>
<div class="page">
  <div class="noprint" style="text-align:right"><button class="btn" onclick="window.print()">Cetak / Simpan PDF</button></div>
  <div class="head">
    <div><div class="brand"><?= html_escape(info_toko('nama_toko')) ?></div>
      <div class="muted"><?= html_escape(info_toko('alamat') . ', ' . info_toko('kota')) ?><br><?= html_escape(info_toko('telpon')) ?> &middot; <?= html_escape(info_toko('email')) ?></div></div>
    <div><h1>INVOICE</h1><div class="muted" style="text-align:right"><?= html_escape($ps->kode_pesanan) ?><br><?= tgl_jam($ps->created_at) ?></div></div>
  </div>
  <div class="grid">
    <div><h4>Ditagihkan kepada</h4><b><?= html_escape($ps->nama_pelanggan) ?></b><br><span class="muted"><?= html_escape($ps->email) ?></span></div>
    <div><h4>Dikirim ke</h4><b><?= html_escape($ps->nama_penerima) ?></b> (<?= html_escape($ps->telpon) ?>)<br><?= html_escape($ps->alamat) ?><br><?= html_escape($ps->kota . ' ' . $ps->kode_pos) ?></div>
    <div><h4>Status</h4><span class="status"><?= html_escape($ps->status) ?></span><?php if ($ps->no_resi): ?><br><span class="muted">Resi: <?= html_escape($ps->no_resi) ?></span><?php endif; ?></div>
  </div>
  <table>
    <thead><tr><th>Produk</th><th class="r">Harga</th><th class="r">Qty</th><th class="r">Subtotal</th></tr></thead>
    <tbody>
    <?php foreach ($items as $i): ?>
      <tr><td><?= html_escape($i->nama_produk) ?></td><td class="r"><?= rupiah($i->harga) ?></td><td class="r"><?= $i->qty ?></td><td class="r"><?= rupiah($i->subtotal) ?></td></tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <table class="tot" style="width:320px;margin-left:auto;margin-top:12px">
    <tr><td>Subtotal</td><td class="r"><?= rupiah($ps->subtotal) ?></td></tr>
    <tr><td>Ongkir (<?= number_format($ps->total_berat / 1000, 2, ',', '.') ?> kg)</td><td class="r"><?= rupiah($ps->ongkir) ?></td></tr>
    <tr class="big"><td>Total</td><td class="r"><?= rupiah($ps->total) ?></td></tr>
  </table>
  <div class="foot">Terima kasih telah berbelanja di <?= html_escape(info_toko('nama_toko')) ?>.</div>
</div>
</body>
</html>
