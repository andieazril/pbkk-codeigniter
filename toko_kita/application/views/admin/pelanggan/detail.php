<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row">
  <div class="col-md-4">
    <div class="box">
      <div class="profile-card"><span class="avatar avatar-xl"><?= inisial($p->nama) ?></span><h3><?= html_escape($p->nama) ?></h3><p class="text-muted"><?= html_escape($p->email) ?></p></div>
      <div class="box-body"><table class="table table-info">
        <tr><th>No. HP</th><td><?= html_escape($p->telpon) ?></td></tr>
        <tr><th>Alamat</th><td><?= html_escape($p->alamat ?: '-') ?><br><?= html_escape(($p->kota ?: '') . ' ' . $p->kode_pos) ?></td></tr>
        <tr><th>Terdaftar</th><td><?= tgl_indo($p->created_at) ?></td></tr>
      </table></div>
    </div>
  </div>
  <div class="col-md-8">
    <div class="box box-primary">
      <div class="box-header with-border"><h3 class="box-title">Riwayat Pesanan</h3></div>
      <div class="box-body table-responsive">
        <table class="table table-bordered">
          <thead><tr><th>Kode</th><th>Tanggal</th><th>Item</th><th>Total</th><th>Status</th><th></th></tr></thead>
          <tbody>
          <?php if (empty($pesanan)): ?><tr><td colspan="6"><div class="empty-state"><i class="fa fa-shopping-cart"></i>Belum ada pesanan.</div></td></tr><?php endif; ?>
          <?php foreach ($pesanan as $o): ?>
            <tr><td><b><?= html_escape($o->kode_pesanan) ?></b></td><td><?= tgl_jam($o->created_at) ?></td><td><?= $o->jml_item ?></td><td><?= rupiah($o->total) ?></td>
              <td><?= label_status($o->status) ?></td><td><a href="<?= site_url('admin/pesanan/detail/' . $o->id_pesanan) ?>" class="btn btn-xs btn-primary">Detail</a></td></tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
