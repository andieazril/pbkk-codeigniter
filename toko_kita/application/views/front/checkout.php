<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-title"><h1>Checkout</h1></div>
<div class="steps">
  <div class="st done"><b><i class="fa fa-check"></i></b> Keranjang</div>
  <div class="st now"><b>2</b> Alamat &amp; Pengiriman</div>
  <div class="st"><b>3</b> Pembayaran</div>
  <div class="st"><b>4</b> Selesai</div>
</div>
<?= form_open('checkout/proses', array('id' => 'form-co')) ?>
<div class="row">
  <div class="col-md-7">
    <div class="panel-card">
      <h4><i class="fa fa-map-marker"></i> Alamat Pengiriman</h4>
      <div class="row">
        <div class="col-sm-6 form-group"><label>Nama Penerima</label><input name="nama_penerima" class="form-control" value="<?= html_escape($p->nama) ?>" required></div>
        <div class="col-sm-6 form-group"><label>No. HP</label><input name="telpon" class="form-control" value="<?= html_escape($p->telpon) ?>" required></div>
      </div>
      <div class="form-group"><label>Alamat Lengkap</label><textarea name="alamat" class="form-control" rows="3" placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan" required><?= html_escape($p->alamat) ?></textarea></div>
      <div class="row">
        <div class="col-sm-8 form-group"><label>Kota Tujuan</label>
          <select name="id_ongkir" id="kota" class="form-control" required><option value="">-- Pilih kota --</option>
            <?php foreach ($kota as $k): ?>
              <option value="<?= $k->id_ongkir ?>" data-tarif="<?= $k->tarif_per_kg ?>" data-est="<?= html_escape($k->estimasi) ?>" <?= $p->id_ongkir == $k->id_ongkir ? 'selected' : '' ?>><?= html_escape($k->kota) ?> — <?= rupiah($k->tarif_per_kg) ?>/kg</option>
            <?php endforeach; ?>
          </select></div>
        <div class="col-sm-4 form-group"><label>Kode Pos</label><input name="kode_pos" class="form-control" value="<?= html_escape($p->kode_pos) ?>"></div>
      </div>
      <div class="form-group"><label>Catatan untuk penjual <span class="text-muted">(opsional)</span></label><input name="catatan" class="form-control" maxlength="255" placeholder="Misal: warna, ukuran, waktu pengiriman"></div>
    </div>
    <div class="panel-card">
      <h4><i class="fa fa-cube"></i> Produk Dipesan</h4>
      <?php foreach ($items as $i): ?>
        <div class="cart-item"><img src="<?= gambar_produk($i->gambar) ?>" alt="" style="width:60px;height:60px">
          <div class="info"><b><?= html_escape($i->nama_produk) ?></b><div class="text-muted"><?= $i->qty ?> x <?= rupiah($i->harga) ?></div></div>
          <b><?= rupiah($i->subtotal) ?></b></div>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="col-md-5">
    <div class="panel-card summary">
      <h4>Ringkasan Pembayaran</h4>
      <div class="row-sum"><span>Total harga (<?= $ringkas['qty'] ?> barang)</span><b><?= rupiah($ringkas['subtotal']) ?></b></div>
      <div class="row-sum"><span>Berat total</span><span><span id="kg"></span> kg</span></div>
      <div class="row-sum"><span>Ongkos kirim <small class="text-muted" id="est"></small></span><b id="ongkir">-</b></div>
      <div class="row-sum total"><span>Total Bayar</span><span class="price" id="total"><?= rupiah($ringkas['subtotal']) ?></span></div>
      <p class="text-muted" style="font-size:12.5px;margin:10px 0 0"><i class="fa fa-info-circle"></i> Pembayaran via transfer bank. Rekening tujuan ditampilkan setelah pesanan dibuat.</p>
      <button class="btn btn-accent btn-lg btn-block" style="margin-top:14px" onclick="return confirm('Buat pesanan sekarang?')"><i class="fa fa-lock"></i> Buat Pesanan</button>
    </div>
  </div>
</div>
<?= form_close() ?>
<?php ob_start(); ?>
<script>
  var SUB = <?= (int) $ringkas['subtotal'] ?>, BERAT = <?= (int) $ringkas['berat'] ?>;
  function rp(n) { return 'Rp ' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); }
  function hitung() {
    var o = $('#kota option:selected'), tarif = parseInt(o.data('tarif') || 0, 10), kg = Math.max(1, Math.ceil(BERAT / 1000));
    $('#kg').text(kg);
    if (!tarif) { $('#ongkir').text('-'); $('#est').text(''); $('#total').text(rp(SUB)); return; }
    $('#ongkir').text(rp(kg * tarif)); $('#est').text('(' + o.data('est') + ')'); $('#total').text(rp(SUB + kg * tarif));
  }
  $('#kota').on('change', hitung); hitung();
</script>
<?php $this->load->vars(array('script' => ob_get_clean())); ?>
