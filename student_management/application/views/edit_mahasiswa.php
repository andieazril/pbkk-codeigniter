<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">
        Edit Mahasiswa
    </h1>

    <div class="card shadow mb-4">

        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Form Edit Mahasiswa
            </h6>
        </div>

        <div class="card-body">

            <?= validation_errors('<div class="alert alert-danger">', '</div>') ?>

            <?= form_open_multipart('mahasiswa/edit/' . $mahasiswa->id) ?>

                <div class="form-group">
                    <label>NIM</label>
                    <input type="text" name="nim" class="form-control"
                           value="<?= set_value('nim', $mahasiswa->nim) ?>">
                </div>

                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control"
                           value="<?= set_value('nama', $mahasiswa->nama) ?>">
                </div>

                <div class="form-group">
                    <label>Jurusan</label>
                    <input type="text" name="jurusan" class="form-control"
                           value="<?= set_value('jurusan', $mahasiswa->jurusan) ?>">
                </div>

                <div class="form-group">
                    <label>Foto Saat Ini</label><br>

                    <?php if ($mahasiswa->foto != ''): ?>
                        <img src="<?= base_url('assets/uploads/mahasiswa/' . $mahasiswa->foto) ?>"
                             width="120" class="img-thumbnail mb-2">
                    <?php else: ?>
                        <p class="text-muted">Belum ada foto.</p>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Ganti Foto</label>
                    <input type="file" name="foto" class="form-control">
                    <small class="text-muted">
                        Format JPG, JPEG, atau PNG. Maksimal 2 MB.
                    </small>
                </div>

                <button type="submit" class="btn btn-primary">
                    Simpan Perubahan
                </button>

                <a href="<?= site_url('mahasiswa') ?>" class="btn btn-secondary">
                    Kembali
                </a>

            <?= form_close() ?>

        </div>

    </div>

</div>