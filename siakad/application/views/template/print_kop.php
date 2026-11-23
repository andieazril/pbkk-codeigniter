<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="no-print"><button onclick="window.print()">&#128424; Cetak</button></div>
<div class="kop">
  <div class="txt">
    <h2><?= html_escape(strtoupper(info_kampus('nama_kampus'))) ?></h2>
    <div class="sub"><?= html_escape(info_kampus('alamat')) ?> &middot; Telp. <?= html_escape(info_kampus('telpon')) ?></div>
    <div class="sub"><?= html_escape(info_kampus('email')) ?> &middot; <?= html_escape(info_kampus('website')) ?></div>
  </div>
</div>
