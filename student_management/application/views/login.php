```php
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Student Management</title>

    <link href="<?= base_url('assets/sbadmin/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/sbadmin/css/sb-admin-2.min.css') ?>" rel="stylesheet">
</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="row justify-content-center align-items-center"
             style="min-height: 100vh;">

            <div class="col-xl-5 col-lg-6 col-md-8">

                <div class="card shadow-lg border-0 rounded-lg">

                    <div class="card-body p-5">

                        <div class="text-center mb-4">

                            <div class="mb-3">
                                <i class="fas fa-user-graduate fa-3x text-primary"></i>
                            </div>

                            <h2 class="font-weight-bold text-gray-800">
                                Student Management
                            </h2>

                            <p class="text-gray-600">
                                Silakan login untuk melanjutkan
                            </p>

                        </div>

                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger">
                                <?= $error ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="<?= site_url('login') ?>">

                            <div class="form-group">
                                <label for="username">Username</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </span>
                                    </div>

                                    <input type="text"
                                           id="username"
                                           name="username"
                                           class="form-control"
                                           placeholder="Masukkan username"
                                           required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                    </div>

                                    <input type="password"
                                           id="password"
                                           name="password"
                                           class="form-control"
                                           placeholder="Masukkan password"
                                           required>
                                </div>
                            </div>

                            <button type="submit"
                                    class="btn btn-primary btn-block mt-4">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Login
                            </button>

                        </form>

                    </div>

                </div>

                <div class="text-center mt-4 text-white">
                    <small>Student Management System</small>
                </div>

            </div>

        </div>

    </div>

</body>
</html>
```