<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id"><head><meta charset="utf-8"><title>Laporan Penjualan <?= $dari ?> - <?= $sampai ?></title>
<style>
  body { font-family: Arial, sans-serif; font-size: 12.5px; margin: 30px; color: #000; }
  h2, h3 { margin: 0; text-align: center; } .sub { text-align: center; margin-bottom: 18px; }
  table { width: 100%; border-collapse: collapse; margin-top: 12px; } th, td { border: 1px solid #000; padding: 5px 7px; } th { background: #eee; }
  .r { text-align: right; } .noprint { margin-bottom: 12px; } @media print { .noprint { display: none; } body { margin: 10mm; } }
</style></head>
<body>
<div class="noprint"><button onclick="window.print()">Cetak</button></div>
<h2><?= html_escape(info_toko('nama_toko')) ?></h2>
<div class="sub"><?= html_escape(info_toko('alamat') . ', ' . info_toko('kota')) ?></div>
<h3>LAPORAN PENJUALAN</h3>
<div class="sub">Periode <?= tgl_indo($dari) ?> s/d <?= tgl_indo($sampai) ?></div>
<table>
  <thead><tr><th>No</th><th>Kode Pesanan</th><th>Tanggal</th><th>Pelanggan</th><th>Status</th><th class="r">Subtotal</th><th class="r">Ongkir</th><th class="r">Total</th></tr></thead>
  <tbody>
  <?php $no = 1; foreach ($rows as $r): ?>
    <tr><td><?= $no++ ?></td><td><?= html_escape($r->kode_pesanan) ?></td><td><?= tgl_indo($r->created_at) ?></td><td><?= html_escape($r->nama_pelanggan) ?></td><td><?= $r->status ?></td>
      <td class="r"><?= rupiah($r->subtotal) ?></td><td class="r"><?= rupiah($r->ongkir) ?></td><td class="r"><?= rupiah($r->total) ?></td></tr>
  <?php endforeach; ?>
  <?php if (empty($rows)): ?><tr><td colspan="8" style="text-align:center">Tidak ada transaksi.</td></tr><?php endif; ?>
  </tbody>
  <tfoot><tr><th colspan="5" class="r">Jumlah (<?= $ringkas->jml ?> pesanan)</th><th class="r"><?= rupiah($ringkas->penjualan) ?></th><th class="r"><?= rupiah($ringkas->ongkir) ?></th><th class="r"><?= rupiah($ringkas->total) ?></th></tr></tfoot>
</table>
<p style="margin-top:20px">Dicetak pada <?= tgl_jam(date('Y-m-d H:i:s')) ?></p>
</body></html>
