<?php defined('BASEPATH') OR exit('No direct script access allowed');
$v = function ($f, $d = '') use ($row) { return $row ? html_escape($row->$f) : $d; }; ?>
<?= form_open_multipart('admin/produk/save') ?>
<input type="hidden" name="id_produk" value="<?= $row ? $row->id_produk : '' ?>">
<div class="row">
  <div class="col-md-8">
    <div class="box box-primary">
      <div class="box-header with-border"><h3 class="box-title">Informasi Produk</h3></div>
      <div class="box-body">
        <div class="form-group"><label>Nama Produk <span class="text-red">*</span></label><input name="nama_produk" class="form-control" value="<?= $v('nama_produk') ?>" maxlength="150" required></div>
        <div class="form-group"><label>Kategori <span class="text-red">*</span></label><?= cmb_array('id_kategori', $kategori, $row ? $row->id_kategori : '', 'required') ?></div>
        <div class="form-group"><label>Deskripsi</label><textarea name="deskripsi" class="form-control" rows="6"><?= $v('deskripsi') ?></textarea></div>
        <div class="row">
          <div class="col-sm-6 form-group"><label>Harga Jual (Rp) <span class="text-red">*</span></label><input type="number" name="harga" class="form-control" value="<?= $v('harga') ?>" min="1" required></div>
          <div class="col-sm-6 form-group"><label>Harga Coret (Rp)</label><input type="number" name="harga_coret" class="form-control" value="<?= $v('harga_coret') ?>" min="0">
            <span class="help-block">Isi jika produk sedang diskon. Harus lebih besar dari harga jual.</span></div>
        </div>
        <div class="row">
          <div class="col-sm-6 form-group"><label>Stok <span class="text-red">*</span></label><input type="number" name="stok" class="form-control" value="<?= $v('stok', '0') ?>" min="0" required></div>
          <div class="col-sm-6 form-group"><label>Berat (gram) <span class="text-red">*</span></label><input type="number" name="berat" class="form-control" value="<?= $v('berat', '500') ?>" min="1" required>
            <span class="help-block">Dipakai untuk menghitung ongkos kirim.</span></div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="box box-success">
      <div class="box-header with-border"><h3 class="box-title">Gambar &amp; Status</h3></div>
      <div class="box-body">
        <img id="preview" src="<?= gambar_produk($row ? $row->gambar : NULL) ?>" style="width:100%;aspect-ratio:1/1;object-fit:cover;border-radius:12px;border:1px solid #e2e8f0;margin-bottom:12px">
        <div class="form-group"><input type="file" name="gambar" accept="image/png,image/jpeg,image/webp" onchange="if(this.files[0])document.getElementById('preview').src=URL.createObjectURL(this.files[0])">
          <span class="help-block">jpg/png/webp, maks 2MB, disarankan persegi.</span></div>
        <div class="form-group"><label>Status</label><?= cmb_array('is_aktif', array('y' => 'Tampil di toko', 'n' => 'Nonaktif'), $row ? $row->is_aktif : 'y', 'required') ?></div>
      </div>
      <div class="box-footer">
        <a href="<?= site_url('admin/produk') ?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali</a>
        <button class="btn btn-primary pull-right"><i class="fa fa-save"></i> Simpan</button>
      </div>
    </div>
  </div>
</div>
<?= form_close() ?>
