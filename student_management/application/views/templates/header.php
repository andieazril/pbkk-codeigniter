<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= isset($title) ? $title : 'Dashboard' ?></title>

    <link href="<?= base_url('assets/sbadmin/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">

    <link href="<?= base_url('assets/sbadmin/css/sb-admin-2.min.css') ?>" rel="stylesheet">
</head>

<body id="page-top">

<div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion">

        <a class="sidebar-brand d-flex align-items-center justify-content-center"
           href="<?= site_url('dashboard') ?>">

            <div class="sidebar-brand-text mx-3">
                Student Management
            </div>
        </a>

        <hr class="sidebar-divider">

        <li class="nav-item">
            <a class="nav-link" href="<?= site_url('dashboard') ?>">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="<?= site_url('mahasiswa') ?>">
                <i class="fas fa-fw fa-users"></i>
                <span>Data Mahasiswa</span>
            </a>
        </li>

        <hr class="sidebar-divider">

    </ul>
    <!-- End Sidebar -->

    <div id="content-wrapper" class="d-flex flex-column">

        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">

                <div class="d-flex align-items-center">

                    <span class="navbar-text mr-3">
                        Student Management System
                    </span>

                    <a href="<?= site_url('logout') ?>" class="btn btn-danger btn-sm">
                        Logout
                    </a>

                </div>

            </nav>
            <!-- End Topbar -->