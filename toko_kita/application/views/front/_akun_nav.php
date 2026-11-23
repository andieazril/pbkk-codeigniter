<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="panel-card">
  <div style="display:flex;gap:12px;align-items:center;margin-bottom:14px">
    <span class="avatar"><?= inisial($this->session->userdata('nama_pelanggan')) ?></span>
    <div style="min-width:0"><b style="color:#0f172a"><?= html_escape($this->session->userdata('nama_pelanggan')) ?></b>
      <div class="text-muted" style="font-size:12.5px;overflow:hidden;text-overflow:ellipsis"><?= html_escape($this->session->userdata('email_pelanggan')) ?></div></div>
  </div>
  <ul class="acc-nav">
    <li><a href="<?= site_url('pesanan') ?>" class="<?= $menu === 'pesanan' ? 'active' : '' ?>"><i class="fa fa-file-text-o"></i> Pesanan Saya</a></li>
    <li><a href="<?= site_url('akun') ?>" class="<?= $menu === 'profil' ? 'active' : '' ?>"><i class="fa fa-user-o"></i> Profil &amp; Alamat</a></li>
    <li><a href="<?= site_url('akun/logout') ?>"><i class="fa fa-sign-out"></i> Keluar</a></li>
  </ul>
</div>
