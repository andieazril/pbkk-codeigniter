<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Data Mahasiswa</h1>

    <div class="card shadow mb-4">

        <!-- Header -->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">

            <h6 class="m-0 font-weight-bold text-primary">
                Daftar Mahasiswa
            </h6>

            <a href="<?= site_url('mahasiswa/tambah') ?>"
               class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i>
                Tambah Mahasiswa
            </a>

        </div>

        <!-- Table -->
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover" width="100%">

                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Jurusan</th>
                            <th>Foto</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (!empty($mahasiswa)): ?>

                            <?php foreach ($mahasiswa as $m): ?>

                                <tr>

                                    <td><?= $m->id ?></td>

                                    <td><?= html_escape($m->nim) ?></td>

                                    <td><?= html_escape($m->nama) ?></td>

                                    <td><?= html_escape($m->jurusan) ?></td>

                                    <td class="text-center">

                                        <?php if (!empty($m->foto)): ?>

                                            <img src="<?= base_url('assets/uploads/mahasiswa/' . $m->foto) ?>"
                                                 width="60"
                                                 height="60"
                                                 style="object-fit:cover; border-radius:8px;">

                                        <?php else: ?>

                                            <span class="text-muted">Tidak ada foto</span>

                                        <?php endif; ?>

                                    </td>

                                    <td>

                                        <a href="<?= site_url('mahasiswa/edit/' . $m->id) ?>"
                                           class="btn btn-warning btn-sm mb-1">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>

                                        <a href="<?= site_url('mahasiswa/hapus/' . $m->id) ?>"
                                           class="btn btn-danger btn-sm mb-1"
                                           onclick="return confirm('Yakin ingin menghapus mahasiswa ini?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="6" class="text-center">
                                    Belum ada data mahasiswa.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>