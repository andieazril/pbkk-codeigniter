<?php defined('BASEPATH') OR exit('No direct script access allowed');
$seg = $this->uri->segment(2) ?: 'dashboard';
$menu = array(
  array('Utama', array(array('dashboard', 'Dashboard', 'fa-dashboard'))),
  array('Penjualan', array(
    array('pesanan', 'Pesanan', 'fa-shopping-cart', $pesanan_baru),
    array('pelanggan', 'Pelanggan', 'fa-users'),
    array('laporan', 'Laporan Penjualan', 'fa-line-chart'),
  )),
  array('Katalog', array(
    array('produk', 'Produk', 'fa-cubes'),
    array('kategori', 'Kategori', 'fa-tags'),
  )),
  array('Pengaturan', array(
    array('ongkir', 'Ongkos Kirim', 'fa-truck'),
    array('rekening', 'Rekening Bank', 'fa-university'),
    array('toko', 'Info Toko', 'fa-cog'),
  )),
); ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title><?= isset($title) ? html_escape($title) . ' | ' : '' ?>Admin <?= html_escape(info_toko('nama_toko')) ?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendor/font-awesome/css/font-awesome.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendor/datatables/dataTables.bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendor/select2/select2.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendor/adminlte/css/AdminLTE.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <header class="main-header">
    <a href="<?= site_url('admin') ?>" class="logo">
      <span class="logo-mini"><span class="brand-icon"><i class="fa fa-shopping-bag"></i></span></span>
      <span class="logo-lg"><span class="brand-icon"><i class="fa fa-shopping-bag"></i></span><?= html_escape(info_toko('nama_toko')) ?></span>
    </a>
    <nav class="navbar navbar-static-top">
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button"><i class="fa fa-bars"></i><span class="sr-only">Toggle navigation</span></a>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="hidden-xs"><a href="<?= site_url() ?>" target="_blank"><i class="fa fa-external-link"></i> Lihat Toko</a></li>
          <?php if ($pesanan_baru): ?>
            <li><a href="<?= site_url('admin/pesanan?status=Menunggu+Verifikasi') ?>" title="Pembayaran perlu diverifikasi"><i class="fa fa-bell-o"></i> <span class="label label-warning"><?= $pesanan_baru ?></span></a></li>
          <?php endif; ?>
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <span class="avatar"><?= inisial($this->session->userdata('admin_nama')) ?></span>
              <span class="hidden-xs"><span class="user-name"><?= html_escape($this->session->userdata('admin_nama')) ?></span><span class="user-role">Administrator</span></span>
              <i class="fa fa-angle-down hidden-xs" style="color:#94a3b8"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-right user-dropdown">
              <li class="dd-head"><b><?= html_escape($this->session->userdata('admin_nama')) ?></b><small>@<?= html_escape($this->session->userdata('admin_username')) ?></small></li>
              <li><a href="<?= site_url('admin/profil') ?>"><i class="fa fa-key"></i> Ganti Password</a></li>
              <li><a href="<?= site_url('admin/auth/logout') ?>" class="text-danger"><i class="fa fa-sign-out"></i> Keluar</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <aside class="main-sidebar">
    <section class="sidebar">
      <div class="user-card">
        <span class="avatar"><?= inisial($this->session->userdata('admin_nama')) ?></span>
        <div class="info"><b><?= html_escape($this->session->userdata('admin_nama')) ?></b><span>Administrator</span></div>
      </div>
      <ul class="sidebar-menu" data-widget="tree">
        <?php foreach ($menu as $grp): ?>
          <li class="header"><?= strtoupper($grp[0]) ?></li>
          <?php foreach ($grp[1] as $m): ?>
            <li class="<?= $seg === $m[0] ? 'active' : '' ?>"><a href="<?= site_url('admin/' . $m[0]) ?>"><i class="fa <?= $m[2] ?>"></i> <span><?= $m[1] ?></span>
              <?php if (!empty($m[3])): ?><span class="pull-right-container"><span class="label label-warning pull-right"><?= $m[3] ?></span></span><?php endif; ?></a></li>
          <?php endforeach; ?>
        <?php endforeach; ?>
        <li class="header">AKUN</li>
        <li class="logout-item"><a href="<?= site_url('admin/auth/logout') ?>"><i class="fa fa-sign-out"></i> <span>Keluar</span></a></li>
      </ul>
    </section>
  </aside>

  <div class="content-wrapper">
    <section class="content-header">
      <h1><?= isset($title) ? html_escape($title) : '' ?> <small><?= isset($subtitle) ? html_escape($subtitle) : '' ?></small></h1>
      <ol class="breadcrumb">
        <li><a href="<?= site_url('admin') ?>"><i class="fa fa-home"></i> Admin</a></li>
        <li class="active"><?= isset($title) ? html_escape($title) : '' ?></li>
      </ol>
    </section>
    <section class="content">
      <?= show_alert() ?>
      <?= $content ?>
    </section>
  </div>
  <footer class="main-footer">
    <div class="pull-right hidden-xs">Toko Online v1.0</div>
    &copy; <?= date('Y') ?> <strong><?= html_escape(info_toko('nama_toko')) ?></strong> &middot; Panel Admin
  </footer>
</div>
<script src="<?= base_url('assets/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/datatables/dataTables.bootstrap.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/select2/select2.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/adminlte/js/adminlte.min.js') ?>"></script>
<script>
  $(function () {
    $('.datatable').DataTable({ language: { url: '<?= base_url('assets/vendor/datatables/id.json') ?>' }, order: [] });
    $('.select2').select2({ width: '100%' });
    $('.auto-submit').on('change', function () { this.form.submit(); });
  });
</script>
<?php if (isset($script)) echo $script; ?>
</body>
</html>
