<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">
        Tambah Mahasiswa
    </h1>

    <div class="card shadow mb-4">

        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Form Mahasiswa
            </h6>
        </div>

        <div class="card-body">

            <?= validation_errors('<div class="alert alert-danger">', '</div>') ?>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <?= form_open_multipart('mahasiswa/tambah') ?>

                <div class="form-group">
                    <label>NIM</label>
                    <input type="text" name="nim" class="form-control"
                           value="<?= set_value('nim') ?>">
                </div>

                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control"
                           value="<?= set_value('nama') ?>">
                </div>

                <div class="form-group">
                    <label>Jurusan</label>
                    <input type="text" name="jurusan" class="form-control"
                           value="<?= set_value('jurusan') ?>">
                </div>

                <div class="form-group">
                    <label>Foto Mahasiswa</label>
                    <input type="file" name="foto" class="form-control">
                    <small class="text-muted">
                        Format JPG, JPEG, PNG. Maksimal 2 MB.
                    </small>
                </div>

                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>

                <a href="<?= site_url('mahasiswa') ?>" class="btn btn-secondary">
                    Kembali
                </a>

            <?= form_close() ?>

        </div>

    </div>

</div>