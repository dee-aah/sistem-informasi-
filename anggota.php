<?php
include "koneksi.php";
//anggota
$query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM anggota");
$data = mysqli_fetch_assoc($query);
$totalAnggota = $data['total'];
$result = mysqli_query($conn, "SELECT * FROM anggota");
//cari data
$cari = isset($_GET['cari']) ? $_GET['cari'] : '';
if ($cari != '') {
  $result = mysqli_query($conn, "SELECT * FROM anggota WHERE nama LIKE '%$cari%'");
} else {
  $result = mysqli_query($conn, "SELECT * FROM anggota");
}
//tambah 
if (isset($_POST['simpan'])) {
  $nama = $_POST['nama'];
  $tgl_lahir = $_POST['tgl_lahir'];
  $no_hp = $_POST['no_hp'];
  $tgl_bergabung = $_POST['tgl_bergabung'];
  $status = $_POST['status'];

  $query = "INSERT INTO anggota (nama, tgl_lahir, no_hp, tgl_bergabung, status) VALUES ('$nama', '$tgl_lahir', '$no_hp', '$tgl_bergabung', '$status')";
  $hasil = mysqli_query($conn, $query);

  if ($hasil) {
    echo "<script>alert('Data berhasil ditambahkan!'); window.location='anggota.php';</script>";
  } else {
    echo "<script>alert('Gagal menambahkan data.');</script>";
  }
}
//hapus
if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  $hapus = mysqli_query($conn, "DELETE FROM anggota WHERE id = '$id'");
  if ($hapus) {
    echo "<script>window.location.href='anggota.php?pesan=hapus_berhasil';</script>";
    exit();
  } else {
    echo "<script>alert('Gagal menghapus data');</script>";
  }
}
//update data
if (isset($_POST['update'])) {
  $id = $_POST['id'];
  $nama = $_POST['nama'];
  $tgl_lahir = $_POST['tgl_lahir'];
  $no_hp = $_POST['no_hp'];
  $tgl_bergabung = $_POST['tgl_bergabung'];
  $status = $_POST['status'];

  $update = mysqli_query($conn, "UPDATE anggota SET nama='$nama', tgl_lahir='$tgl_lahir', no_hp='$no_hp', tgl_bergabung='$tgl_bergabung', status='$status' WHERE id='$id'");
  if ($update) {
    echo "<script>alert('Data berhasil diubah!'); window.location='anggota.php';</script>";
  } else {
    echo "<script>alert('Gagal mengubah data.');</script>";
  }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Data Anggota</title>
  <link href="style.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
  <header>
    <nav class="navbar bg-light shadow navbar-light  fixed-top">
      <div class="container-fluid">
        <h2 class="text-dark text-center fw-bold" style="padding-left: 50px;">BMKG</h2>
        <div class="col-3">
          <form class="d-flex" role="search" method="GET">
            <input class="form-control me-2 width-20" type="search" name="cari" placeholder="cari nama anggota" value="<?= htmlspecialchars($cari) ?>">
            <button class="btn d-flex btn-outline-primary" type="submit"><i class="bi bi-search me-2"></i>Search</button>
          </form>
        </div>
      </div>
    </nav>
  </header>
  <main>
    <div class="container-fluid" style="margin-top: 55px;">
      <div class="row ">
        <div class="col-2 border-end sidebarmenu  ">
          <div class="position-fixed ">
            <nav class="nav flex-column text-dark mt-4">
              <a href="dashboard.php" class="nav-link"><i class="bi bi-house-fill me-2"></i>Beranda</a>
              <a href="anggota.php" class="nav-link text-primary"><i class="bi bi-people-fill me-2"></i>Data Anggota</a>
              <a href="kegiatan.php" class="nav-link"><i class="bi bi-calendar-event-fill me-2"></i>Kegiatan</a>
              <a href="keuangan.php" class="nav-link"><i class="bi bi-cash-stack me-2"></i>Keuangan</a>
              <a href="laporan.php" class="nav-link"><i class="bi bi-file-earmark-text-fill me-2"></i>Laporan Bulanan</a>
            </nav>
          </div>
        </div>
        <div class=" col-10 bg-light " style="padding-top: 60px;">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0 fs-4 font-monospace  fw-bold" style="color: #003366;">Jumlah Anggota : <strong><?= $totalAnggota ?> Orang</strong></h3>
            <div class="d-flex row">
              <div class="d-flex gap-2">
                <a class="btn btn-success" id="btnPilih"><i class="bi bi-check-circle"></i> Pilih
                </a>
                <a class="btn btn-primary" data-bs-target="#modalTambahanggota" data-bs-toggle="modal" role="button"><i class="bi bi-plus-circle"></i> Tambah Data
                </a>
              </div>
            </div>

          </div>
          <div class="table-responsive">
            <table class="table text-center table-bordered">
              <thead class="table-primary">
                <tr>
                  <th>No</th>
                  <th>Nama Anggota</th>
                  <th>Tanggal Lahir</th>
                  <th>No Telphone</th>
                  <th>Tanggal Bergabung</th>
                  <th>Status Anggota</th>
                  <th class="aksi-col d-none">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'hapus_berhasil') : ?>
                  <div class="alert alert-success" id="alerthps">
                    Data berhasil dihapus </div>
                <?php endif; ?>
                <?php
                $no = 1;
                mysqli_data_seek($result, 0); // pastikan kursor di awal
                while ($row = mysqli_fetch_assoc($result)) :
                ?>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $row['nama'] ?></td>
                    <td><?= $row['tgl_lahir'] ?></td>
                    <td><?= $row['no_hp'] ?></td>
                    <td><?= $row['tgl_bergabung'] ?></td>
                    <td><?= $row['status'] ?></td>
                    <td class="aksi-col d-none">
                      <!-- Tombol Edit -->
                      <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">Edit</button>
                      <!-- Tombol Hapus -->
                      <a href="anggota.php?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                    </td>
                  </tr>
                  <!-- Modal Edit -->
                  <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <form method="post" action="">
                          <input type="hidden" name="id" value="<?= $row['id'] ?>">
                          <div class="modal-header text-center text-primary">
                            <h5 class="modal-title ">Edit Data Anggota</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                          </div>
                          <div class="modal-body text-start">
                            <div class=" text-start mb-3">
                              <label>Nama Lengkap</label>
                              <input type="text" name="nama" class="form-control" value="<?= $row['nama'] ?>" required>
                            </div>
                            <div class="mb-3">
                              <label>Tanggal Lahir</label>
                              <input type="date" name="tgl_lahir" class="form-control" value="<?= date('Y-m-d', strtotime($row['tgl_lahir'])) ?>" required>
                            </div>
                            <div class="mb-3">
                              <label>No Telphone</label>
                              <input type="text" name="no_hp" class="form-control" value="<?= $row['no_hp'] ?>" required>
                            </div>
                            <div class="mb-3">
                              <label>Tanggal Bergabung</label>
                              <input type="date" name="tgl_bergabung" class="form-control" value="<?= date('Y-m-d', strtotime($row['tgl_bergabung'])) ?>" required>
                            </div>
                            <div class="mb-3">
                              <label>Status</label>
                              <select name="status" class="form-control" required>
                                <option value="Aktif" <?= $row['status'] == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                                <option value="Tidak Aktif" <?= $row['status'] == 'Tidak Aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
                              </select>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="submit" name="update" class="btn btn-warning">Simpan Perubahan</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                <?php endwhile; ?>
                <!-- form tambah anggota -->
                <div class="modal fade" id="modalTambahanggota" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <form method="post" action="">
                        <div class="modal-header text-center text-primary">
                          <h5 class="modal-title " id="modalTambahLabel">Tambah Data Anggota</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body text-start">
                          <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                          </div>
                          <div class="mb-3">
                            <label>Tanggal Lahir</label>
                            <input type="date" id="tgl_lahir" name="tgl_lahir" class="form-control" required>
                          </div>
                          <div class="mb-3">
                            <label for="no_hp" class="form-label">No Telphone</label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp" required>
                          </div>
                          <div class="mb-3">
                            <label for="tgl_bergabung" class="form-label">Tanggal Bergabung</label>
                            <input type="date" class="form-control" id="tgl_bergabung" name="tgl_bergabung" required>
                          </div>
                          <div class="mb-3">
                            <label>Status Kegiatan</label>
                            <select type="text" name="status" id="status" class="form-control" required><i class="bi bi-chevron-down"></i>
                              <option value="Aktif">Aktif</option>
                              <option value="Tidak Aktif">Tidak Aktif</option>
                            </select>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
  </main>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    document.getElementById('btnPilih').addEventListener('click', function() {
      const aksiCols = document.querySelectorAll('.aksi-col');
      aksiCols.forEach(col => {
        col.classList.toggle('d-none');
      });
    });
    setTimeout(function() {
      const alertEl = document.getElementById('alerthps');
      if (alertEl) {
        alertEl.style.display = 'none';
      }
    }, 3000);
  </script>

</body>

</html>