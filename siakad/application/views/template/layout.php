<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= isset($title) ? html_escape($title) . ' | ' : '' ?>SIAKAD <?= html_escape(info_kampus('nama_kampus')) ?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendor/font-awesome/css/font-awesome.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendor/datatables/dataTables.bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendor/select2/select2.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendor/adminlte/css/AdminLTE.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/siakad.css') ?>">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <a href="<?= site_url('dashboard') ?>" class="logo">
      <span class="logo-mini"><span class="brand-icon"><i class="fa fa-graduation-cap"></i></span></span>
      <span class="logo-lg"><span class="brand-icon"><i class="fa fa-graduation-cap"></i></span>SIAKAD</span>
    </a>
    <nav class="navbar navbar-static-top">
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button"><i class="fa fa-bars"></i><span class="sr-only">Toggle navigation</span></a>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="hidden-xs">
            <span class="topbar-chip"><i class="fa fa-calendar-check-o"></i>
              <?= $tahun_aktif ? html_escape($tahun_aktif['tahun_akademik'] . ' · ' . $tahun_aktif['semester']) : 'TA belum diset' ?>
            </span>
          </li>
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <span class="avatar"><?= inisial($this->session->userdata('nama_lengkap')) ?></span>
              <span class="hidden-xs">
                <span class="user-name"><?= html_escape($this->session->userdata('nama_lengkap')) ?></span>
                <span class="user-role"><?= html_escape($this->session->userdata('nama_level')) ?></span>
              </span>
              <i class="fa fa-angle-down hidden-xs" style="color:#94a3b8"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-right user-dropdown">
              <li class="dd-head"><b><?= html_escape($this->session->userdata('nama_lengkap')) ?></b>
                <small>@<?= html_escape($this->session->userdata('username')) ?></small></li>
              <li><a href="<?= site_url('profil') ?>"><i class="fa fa-user-o"></i> Profil Saya</a></li>
              <li><a href="<?= site_url('profil') ?>"><i class="fa fa-key"></i> Ganti Password</a></li>
              <li><a href="<?= site_url('auth/logout') ?>" class="text-danger"><i class="fa fa-sign-out"></i> Keluar</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <?php $this->load->view('template/sidebar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1><?= isset($title) ? html_escape($title) : '' ?> <small><?= isset($subtitle) ? html_escape($subtitle) : '' ?></small></h1>
      <ol class="breadcrumb">
        <li><a href="<?= site_url('dashboard') ?>"><i class="fa fa-home"></i> Home</a></li>
        <li class="active"><?= isset($title) ? html_escape($title) : '' ?></li>
      </ol>
    </section>
    <section class="content">
      <?= show_alert() ?>
      <?= $content ?>
    </section>
  </div>

  <footer class="main-footer">
    <div class="pull-right hidden-xs">SIAKAD v2.0</div>
    &copy; <?= date('Y') ?> <strong><?= html_escape(info_kampus('nama_kampus')) ?></strong> &middot; Sistem Informasi Akademik Perguruan Tinggi
  </footer>
</div>

<script src="<?= base_url('assets/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/datatables/dataTables.bootstrap.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/select2/select2.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/adminlte/js/adminlte.min.js') ?>"></script>
<script>
  var CSRF_NAME = '<?= $this->security->get_csrf_token_name() ?>';
  var CSRF_HASH = '<?= $this->security->get_csrf_hash() ?>';
  $(function () {
    $('.datatable').DataTable({
      language: { url: '<?= base_url('assets/vendor/datatables/id.json') ?>' }
    });
    $('.select2').select2({ width: '100%' });
    // auto submit filter
    $('.auto-submit').on('change', function () { this.form.submit(); });
  });
</script>
<?php if (isset($script)) echo $script; ?>
</body>
</html>
