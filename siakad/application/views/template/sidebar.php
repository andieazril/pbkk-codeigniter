<?php defined('BASEPATH') OR exit('No direct script access allowed');
$active = $this->uri->segment(1) ?: 'dashboard'; ?>
<aside class="main-sidebar">
  <section class="sidebar">
    <div class="user-card">
      <span class="avatar"><?= inisial($this->session->userdata('nama_lengkap')) ?></span>
      <div class="info">
        <b><?= html_escape($this->session->userdata('nama_lengkap')) ?></b>
        <span><?= html_escape($this->session->userdata('nama_level')) ?></span>
      </div>
    </div>
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">NAVIGASI</li>
      <?php foreach ($menus as $m): ?>
        <?php if (empty($m->children)): ?>
          <li class="<?= $active === $m->link ? 'active' : '' ?>">
            <a href="<?= site_url($m->link) ?>"><i class="<?= html_escape($m->icon) ?>"></i> <span><?= html_escape($m->nama_menu) ?></span></a>
          </li>
        <?php else:
          $open = in_array($active, array_map(function ($c) { return $c->link; }, $m->children)); ?>
          <li class="treeview <?= $open ? 'active menu-open' : '' ?>">
            <a href="#"><i class="<?= html_escape($m->icon) ?>"></i> <span><?= html_escape($m->nama_menu) ?></span>
              <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
            <ul class="treeview-menu">
              <?php foreach ($m->children as $c): ?>
                <li class="<?= $active === $c->link ? 'active' : '' ?>">
                  <a href="<?= site_url($c->link) ?>"><i class="<?= html_escape($c->icon) ?>"></i> <?= html_escape($c->nama_menu) ?></a>
                </li>
              <?php endforeach; ?>
            </ul>
          </li>
        <?php endif; ?>
      <?php endforeach; ?>
      <li class="header">AKUN</li>
      <li class="<?= $active === 'profil' ? 'active' : '' ?>"><a href="<?= site_url('profil') ?>"><i class="fa fa-user-o"></i> <span>Profil Saya</span></a></li>
      <li class="logout-item"><a href="<?= site_url('auth/logout') ?>"><i class="fa fa-sign-out"></i> <span>Keluar</span></a></li>
    </ul>
  </section>
</aside>
