<?php defined('BASEPATH') OR exit('No direct script access allowed');
$alur = array('Menunggu Pembayaran', 'Menunggu Verifikasi', 'Diproses', 'Dikirim', 'Selesai');
$pos  = array_search($ps->status, $alur); ?>
<div class="page-title">
  <div class="crumb"><a href="<?= site_url('pesanan') ?>">Pesanan Saya</a><i class="fa fa-chevron-right"></i><?= html_escape($ps->kode_pesanan) ?></div>
  <h1>Pesanan <?= html_escape($ps->kode_pesanan) ?> <?= label_status($ps->status) ?></h1>
</div>
<div class="row">
  <div class="col-md-8">
    <?php if ($boleh_bayar): ?>
    <div class="panel-card" style="border-color:#fdba74">
      <h4><i class="fa fa-credit-card"></i> Selesaikan Pembayaran</h4>
      <p>Transfer tepat <b class="price" style="font-size:20px"><?= rupiah($ps->total) ?></b> ke salah satu rekening berikut:</p>
      <div class="row">
        <?php foreach ($rekening as $r): ?>
          <div class="col-sm-6"><div class="rek"><i class="fa fa-university"></i><div><div class="text-muted"><?= html_escape($r->nama_bank) ?></div><div class="no"><?= html_escape($r->no_rekening) ?></div><small>a.n. <?= html_escape($r->atas_nama) ?></small></div></div></div>
        <?php endforeach; ?>
      </div>
      <?php if ($bayar && $bayar[0]->status === 'Ditolak'): ?>
        <div class="alert alert-danger"><i class="fa fa-times-circle icon"></i>Pembayaran sebelumnya ditolak<?= $bayar[0]->catatan_admin ? ': ' . html_escape($bayar[0]->catatan_admin) : '' ?>. Silakan unggah ulang bukti yang benar.</div>
      <?php endif; ?>
      <h4 style="margin-top:14px">Konfirmasi Pembayaran</h4>
      <?= form_open_multipart('pesanan/bayar/' . $ps->kode_pesanan) ?>
        <div class="row">
          <div class="col-sm-6 form-group"><label>Transfer ke</label>
            <select name="id_rekening" class="form-control" required><?php foreach ($rekening as $r): ?><option value="<?= $r->id_rekening ?>"><?= html_escape($r->nama_bank . ' - ' . $r->no_rekening) ?></option><?php endforeach; ?></select></div>
          <div class="col-sm-6 form-group"><label>Tanggal Transfer</label><input type="date" name="tanggal_transfer" class="form-control" value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>" required></div>
          <div class="col-sm-6 form-group"><label>Nama Pemilik Rekening</label><input name="nama_pengirim" class="form-control" value="<?= html_escape($ps->nama_penerima) ?>" required></div>
          <div class="col-sm-6 form-group"><label>Bank Pengirim</label><input name="bank_pengirim" class="form-control" placeholder="Nama bank Anda" required></div>
          <div class="col-sm-6 form-group"><label>Jumlah Transfer (Rp)</label><input type="number" name="jumlah" class="form-control" value="<?= $ps->total ?>" min="1" required></div>
          <div class="col-sm-6 form-group"><label>Bukti Transfer <small class="text-muted">(jpg/png/pdf, maks 2MB)</small></label><input type="file" name="bukti" accept="image/png,image/jpeg,application/pdf" required></div>
        </div>
        <button class="btn btn-accent btn-lg"><i class="fa fa-upload"></i> Kirim Konfirmasi</button>
      <?= form_close() ?>
    </div>
    <?php endif; ?>

    <div class="panel-card">
      <h4>Produk</h4>
      <?php foreach ($items as $i): ?>
        <div class="cart-item"><img src="<?= gambar_produk($i->gambar) ?>" alt="" style="width:60px;height:60px">
          <div class="info"><?= $i->slug ? '<a href="' . site_url('p/' . $i->slug) . '">' . html_escape($i->nama_produk) . '</a>' : html_escape($i->nama_produk) ?>
            <div class="text-muted"><?= $i->qty ?> x <?= rupiah($i->harga) ?></div></div>
          <b><?= rupiah($i->subtotal) ?></b></div>
      <?php endforeach; ?>
      <div class="summary" style="position:static;margin-top:8px">
        <div class="row-sum"><span>Subtotal</span><span><?= rupiah($ps->subtotal) ?></span></div>
        <div class="row-sum"><span>Ongkir (<?= number_format($ps->total_berat / 1000, 2, ',', '.') ?> kg ke <?= html_escape($ps->kota) ?>)</span><span><?= rupiah($ps->ongkir) ?></span></div>
        <div class="row-sum total"><span>Total</span><span class="price"><?= rupiah($ps->total) ?></span></div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="panel-card">
      <h4>Status Pesanan</h4>
      <?php if ($ps->status === 'Dibatalkan'): ?>
        <div class="alert alert-danger" style="margin:0"><i class="fa fa-ban icon"></i>Pesanan dibatalkan.</div>
      <?php else: ?>
        <ul class="timeline">
          <?php foreach ($alur as $i => $s): $cls = $i < $pos ? 'done' : ($i === $pos ? 'now' : ''); ?>
            <li class="<?= $cls ?>"><span class="dot"><?= $i < $pos ? '<i class="fa fa-check"></i>' : '' ?></span><?= $s ?></li>
          <?php endforeach; ?>
        </ul>
        <?php if ($ps->no_resi): ?><div class="alert alert-info" style="margin:12px 0 0"><i class="fa fa-truck icon"></i>No. resi: <b><?= html_escape($ps->no_resi) ?></b></div><?php endif; ?>
      <?php endif; ?>
      <div style="margin-top:14px;display:flex;gap:8px;flex-wrap:wrap">
        <a href="<?= site_url('pesanan/invoice/' . $ps->kode_pesanan) ?>" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-print"></i> Invoice</a>
        <?php if ($ps->status === 'Dikirim'): ?>
          <?= form_open('pesanan/selesai/' . $ps->kode_pesanan, array('style' => 'display:inline', 'onsubmit' => "return confirm('Pesanan sudah diterima?')")) ?><button class="btn btn-primary btn-sm"><i class="fa fa-check"></i> Pesanan Diterima</button><?= form_close() ?>
        <?php endif; ?>
        <?php if ($ps->status === 'Menunggu Pembayaran'): ?>
          <?= form_open('pesanan/batal/' . $ps->kode_pesanan, array('style' => 'display:inline', 'onsubmit' => "return confirm('Batalkan pesanan ini?')")) ?><button class="btn btn-link text-danger btn-sm">Batalkan pesanan</button><?= form_close() ?>
        <?php endif; ?>
      </div>
    </div>
    <div class="panel-card">
      <h4>Alamat Pengiriman</h4>
      <b><?= html_escape($ps->nama_penerima) ?></b> <span class="text-muted">(<?= html_escape($ps->telpon) ?>)</span>
      <p style="margin:6px 0 0"><?= nl2br(html_escape($ps->alamat)) ?><br><?= html_escape($ps->kota . ' ' . $ps->kode_pos) ?></p>
      <?php if ($ps->catatan): ?><p class="text-muted" style="margin-top:8px"><i class="fa fa-sticky-note-o"></i> <?= html_escape($ps->catatan) ?></p><?php endif; ?>
    </div>
    <?php if ($bayar): ?>
    <div class="panel-card">
      <h4>Riwayat Pembayaran</h4>
      <?php foreach ($bayar as $b): ?>
        <div style="padding:8px 0;border-bottom:1px dashed #e2e8f0"><?= label_status($b->status) ?> <b><?= rupiah($b->jumlah) ?></b>
          <div class="text-muted" style="font-size:12.5px"><?= html_escape($b->bank_pengirim) ?> &rarr; <?= html_escape($b->nama_bank) ?> &middot; <?= tgl_indo($b->tanggal_transfer) ?></div></div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</div>
