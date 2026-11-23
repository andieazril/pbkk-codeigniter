<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>

    <div class="row">

        <!-- Total Mahasiswa -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">

                        <div class="col">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Sistem
                            </div>

                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Student Management
                            </div>
                        </div>

                        <div class="col-auto">
                            <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Data Mahasiswa -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">

                        <div class="col">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Menu
                            </div>

                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Data Mahasiswa
                            </div>
                        </div>

                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Akun -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">

                        <div class="col">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Login Sebagai
                            </div>

                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $this->session->userdata('username') ?>
                            </div>
                        </div>

                        <div class="col-auto">
                            <i class="fas fa-user fa-2x text-gray-300"></i>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Welcome Card -->
    <div class="card shadow mb-4">

        <div class="card-body p-4">

            <h4 class="font-weight-bold text-gray-800">
                Selamat Datang
            </h4>

            <p class="text-gray-600 mb-4">
                Selamat datang di Student Management System.
                Aplikasi ini digunakan untuk mengelola data mahasiswa,
                mulai dari melihat, menambah, mengedit, hingga menghapus data.
            </p>

            <a href="<?= site_url('mahasiswa') ?>" class="btn btn-primary">
                <i class="fas fa-users mr-2"></i>
                Kelola Data Mahasiswa
            </a>

        </div>

    </div>

</div>