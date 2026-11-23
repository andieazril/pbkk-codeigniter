<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row">
  <div class="col-md-8">
    <div class="box box-primary">
      <div class="box-header with-border"><h3 class="box-title"><?= html_escape($ps->kode_pesanan) ?></h3>
        <div class="box-tools"><?= label_status($ps->status) ?> <a href="<?= site_url('admin/pesanan/invoice/' . $ps->id_pesanan) ?>" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-print"></i> Invoice</a></div></div>
      <div class="box-body table-responsive">
        <table class="table table-bordered">
          <thead><tr><th>Produk</th><th>Harga</th><th>Qty</th><th>Berat</th><th class="text-right">Subtotal</th></tr></thead>
          <tbody>
          <?php foreach ($items as $i): ?>
            <tr><td><img src="<?= gambar_produk($i->gambar) ?>" style="width:36px;height:36px;border-radius:6px;object-fit:cover;margin-right:8px"><?= html_escape($i->nama_produk) ?></td>
              <td><?= rupiah($i->harga) ?></td><td><?= $i->qty ?></td><td><?= number_format($i->berat * $i->qty, 0, ',', '.') ?> gr</td><td class="text-right"><?= rupiah($i->subtotal) ?></td></tr>
          <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr><td colspan="4" class="text-right">Subtotal</td><td class="text-right"><?= rupiah($ps->subtotal) ?></td></tr>
            <tr><td colspan="4" class="text-right">Ongkir (<?= number_format($ps->total_berat / 1000, 2, ',', '.') ?> kg ke <?= html_escape($ps->kota) ?>)</td><td class="text-right"><?= rupiah($ps->ongkir) ?></td></tr>
            <tr><th colspan="4" class="text-right">Total</th><th class="text-right" style="font-size:16px"><?= rupiah($ps->total) ?></th></tr>
          </tfoot>
        </table>
      </div>
    </div>

    <div class="box box-warning">
      <div class="box-header with-border"><h3 class="box-title">Konfirmasi Pembayaran</h3></div>
      <div class="box-body">
        <?php if (empty($bayar)): ?><div class="empty-state"><i class="fa fa-credit-card"></i>Pelanggan belum mengirim konfirmasi pembayaran.</div><?php endif; ?>
        <?php foreach ($bayar as $b): $is_img = preg_match('/\.(png|jpe?g|webp)$/i', $b->bukti); ?>
          <div class="row" style="padding:12px 0;border-bottom:1px dashed #e2e8f0">
            <div class="col-sm-4">
              <?php if ($is_img): ?><a href="<?= base_url('uploads/bukti/' . $b->bukti) ?>" target="_blank"><img src="<?= base_url('uploads/bukti/' . $b->bukti) ?>" style="width:100%;max-height:220px;object-fit:contain;border:1px solid #e2e8f0;border-radius:10px;background:#f8fafc"></a>
              <?php else: ?><a href="<?= base_url('uploads/bukti/' . $b->bukti) ?>" target="_blank" class="btn btn-default"><i class="fa fa-file-pdf-o"></i> Lihat bukti (PDF)</a><?php endif; ?>
            </div>
            <div class="col-sm-8">
              <table class="table table-info" style="margin-bottom:10px">
                <tr><th>Status</th><td><?= label_status($b->status) ?></td></tr>
                <tr><th>Jumlah</th><td><b><?= rupiah($b->jumlah) ?></b> <?= (int) $b->jumlah !== (int) $ps->total ? '<span class="label label-danger">Tidak sama dengan total</span>' : '<span class="label label-success">Sesuai</span>' ?></td></tr>
                <tr><th>Dari</th><td><?= html_escape($b->nama_pengirim) ?> &middot; <?= html_escape($b->bank_pengirim) ?></td></tr>
                <tr><th>Ke</th><td><?= html_escape($b->nama_bank . ' ' . $b->no_rekening) ?></td></tr>
                <tr><th>Tgl Transfer</th><td><?= tgl_indo($b->tanggal_transfer) ?></td></tr>
                <?php if ($b->catatan_admin): ?><tr><th>Catatan</th><td><?= html_escape($b->catatan_admin) ?></td></tr><?php endif; ?>
              </table>
              <?php if ($b->status === 'Menunggu' && $ps->status === 'Menunggu Verifikasi'): ?>
                <?= form_open('admin/pesanan/verifikasi/' . $b->id_pembayaran, array('class' => 'form-inline')) ?>
                  <button name="aksi" value="terima" class="btn btn-success" onclick="return confirm('Terima pembayaran ini?')"><i class="fa fa-check"></i> Terima</button>
                  <input name="catatan" class="form-control" placeholder="Alasan jika ditolak" style="width:200px">
                  <button name="aksi" value="tolak" class="btn btn-danger" onclick="return confirm('Tolak pembayaran ini?')"><i class="fa fa-times"></i> Tolak</button>
                <?= form_close() ?>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="box box-success">
      <div class="box-header with-border"><h3 class="box-title">Tindakan</h3></div>
      <div class="box-body">
        <?php if (in_array($ps->status, array('Diproses', 'Dikirim'))): ?>
          <?= form_open('admin/pesanan/kirim/' . $ps->id_pesanan) ?>
            <label>Nomor Resi Pengiriman</label>
            <div class="input-group">
              <input name="no_resi" class="form-control" value="<?= html_escape($ps->no_resi) ?>" placeholder="Masukkan nomor resi" required>
              <span class="input-group-btn"><button class="btn btn-primary"><i class="fa fa-truck"></i> <?= $ps->status === 'Dikirim' ? 'Ubah' : 'Kirim' ?></button></span>
            </div>
          <?= form_close() ?>
        <?php endif; ?>
        <?php if ($ps->status === 'Dikirim'): ?>
          <?= form_open('admin/pesanan/selesai/' . $ps->id_pesanan, array('style' => 'margin-top:10px')) ?><button class="btn btn-success btn-block" onclick="return confirm('Tandai pesanan selesai?')"><i class="fa fa-check-circle"></i> Tandai Selesai</button><?= form_close() ?>
        <?php endif; ?>
        <?php if ($ps->status === 'Menunggu Pembayaran'): ?><p class="text-muted"><i class="fa fa-hourglass-half"></i> Menunggu pelanggan membayar.</p><?php endif; ?>
        <?php if ($ps->status === 'Menunggu Verifikasi'): ?><p class="text-muted"><i class="fa fa-info-circle"></i> Periksa bukti transfer di sebelah kiri.</p><?php endif; ?>
        <?php if ($ps->status === 'Selesai'): ?><p class="text-muted"><i class="fa fa-check-circle text-green"></i> Pesanan telah selesai.</p><?php endif; ?>
        <?php if (!in_array($ps->status, array('Selesai', 'Dibatalkan', 'Dikirim'))): ?>
          <?= form_open('admin/pesanan/batal/' . $ps->id_pesanan, array('style' => 'margin-top:14px')) ?><button class="btn btn-default btn-block text-danger" onclick="return confirm('Batalkan pesanan? Stok akan dikembalikan.')"><i class="fa fa-ban"></i> Batalkan Pesanan</button><?= form_close() ?>
        <?php endif; ?>
      </div>
    </div>
    <div class="box">
      <div class="box-header with-border"><h3 class="box-title">Pelanggan &amp; Pengiriman</h3></div>
      <div class="box-body">
        <table class="table table-info">
          <tr><th>Pelanggan</th><td><?= html_escape($ps->nama_pelanggan) ?><br><small class="text-muted"><?= html_escape($ps->email) ?></small></td></tr>
          <tr><th>Penerima</th><td><?= html_escape($ps->nama_penerima) ?><br><small class="text-muted"><?= html_escape($ps->telpon) ?></small></td></tr>
          <tr><th>Alamat</th><td><?= nl2br(html_escape($ps->alamat)) ?><br><?= html_escape($ps->kota . ' ' . $ps->kode_pos) ?></td></tr>
          <?php if ($ps->catatan): ?><tr><th>Catatan</th><td><?= html_escape($ps->catatan) ?></td></tr><?php endif; ?>
          <tr><th>Dipesan</th><td><?= tgl_jam($ps->created_at) ?></td></tr>
          <?php if ($ps->no_resi): ?><tr><th>No. Resi</th><td><b><?= html_escape($ps->no_resi) ?></b></td></tr><?php endif; ?>
        </table>
      </div>
    </div>
  </div>
</div>
