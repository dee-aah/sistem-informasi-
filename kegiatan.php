<?php
include "koneksi.php";

// Ubah status 
$today = date('Y-m-d H:i:s');
mysqli_query($conn, "UPDATE kegiatan SET status = 'Tidak Tersedia' WHERE tanggal < '$today' AND status = 'Tersedia'");

// filter
$where = '';
if (isset($_GET['filter'])) {
  $filter = $_GET['filter'];
  $today = date('Y-m-d');

  if ($filter == 'hari_ini') {
    $where = "WHERE DATE(tanggal) = '$today'";
  } elseif ($filter == 'akan_datang') {
    $where = "WHERE DATE(tanggal) > '$today'";
  } elseif ($filter == 'selesai') {
    $where = "WHERE DATE(tanggal) < '$today'";
  }
}

//  pencarian nama kegiatan
$cari = isset($_GET['cari']) ? $_GET['cari'] : '';
if ($cari != '') {
  if ($where != '') {
    $where .= " AND nama LIKE '%$cari%'";
  } else {
    $where = "WHERE nama LIKE '%$cari%'";
  }
}

$result = mysqli_query($conn, "SELECT * FROM kegiatan $where ORDER BY tanggal ASC");

// tambah kegiatan
if (isset($_POST['simpan'])) {
  $nama = $_POST['nama'];
  $tanggal = $_POST['tanggal'];
  $tempat = $_POST['tempat'];
  $jawab = $_POST['jawab'];
  $status = $_POST['status'];

  $query = "INSERT INTO kegiatan (nama, tanggal, tempat, jawab, status) VALUES ('$nama', '$tanggal', '$tempat', '$jawab', '$status')";
  $hasil = mysqli_query($conn, $query);

  if ($hasil) {
    echo "<script>alert('Data berhasil ditambahkan!'); window.location='kegiatan.php';</script>";
  } else {
    echo "<script>alert('Gagal menambahkan data.');</script>";
  }
}

if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  $hapus = mysqli_query($conn, "DELETE FROM kegiatan WHERE id = '$id'");
  if ($hapus) {
    echo "<script>window.location.href='kegiatan.php?pesan=hapus_berhasil';</script>";
    exit();
  } else {
    echo "<script>alert('Gagal menghapus data');</script>";
  }
}
// update data
if (isset($_POST['update'])) {
  $id = $_POST['id'];
  $nama = $_POST['nama'];
  $tanggal = $_POST['tanggal'];
  $tempat = $_POST['tempat'];
  $jawab = $_POST['jawab'];
  $status = $_POST['status'];

  $update = mysqli_query($conn, "UPDATE kegiatan SET nama='$nama', tanggal='$tanggal', tempat='$tempat', jawab='$jawab', status='$status' WHERE id='$id'");
  if ($update) {
    echo "<script>alert('Data berhasil diubah!'); window.location='kegiatan.php';</script>";
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
  <title>Dashboard Komunitas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="style.css" rel="stylesheet" />
</head>

<body>
  <header>
    <nav class="navbar bg-light shadow navbar-light  fixed-top">
      <div class="container-fluid">
        <h2 class="text-dark text-center fw-bold" style="padding-left: 50px;">BMKG</h2>
        <form class="d-flex" role="search">
          <input class="form-control me-2" type="search" aria-label="Search" name="cari" placeholder="Cari Nama Kegiatan" value="<?= htmlspecialchars($cari) ?>" />
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
      </div>
    </nav>
  </header>
  <main>
    <div class=" container-fluid  " style="margin-top: 55px;">
      <div class="row ">
        <div class="col-2 border-end sidebarmenu bg-light  ">
          <div class="position-fixed ">
            <nav class="nav flex-column text-dark mt-4">
              <a href="dashboard.php" class="nav-link"><i class="bi bi-house-fill me-2"></i>Beranda</a>
              <a href="anggota.php" class="nav-link"><i class="bi bi-people-fill me-2"></i>Data Anggota</a>
              <a href="kegiatan.php" class="nav-link text-primary"><i class="bi bi-calendar-event-fill me-2"></i>Kegiatan</a>
              <a href="keuangan.php" class="nav-link"><i class="bi bi-cash-stack me-2"></i>Keuangan</a>
              <a href="#" class="nav-link"><i class="bi bi-file-earmark-text-fill me-2"></i>Laporan Bulanan</a>
            </nav>
          </div>
        </div>
        <div class=" col-10  " style="margin-top: 40px;">
          <div class="content">
            <h3 class="font-monospace text-center" style="font-weight: bold; font-size: 2rem; color: #003366;">Daftar Kegiatan</h3>
            <div class="mb-3 justify-content-between d-flex">
              <div class="me-2">
                <form method="get" id="filterForm" class="d-inline">
                  <select name="filter" class="form-select" onchange="document.getElementById('filterForm').submit()">
                    <option value="">Semua</option>
                    <option value="hari_ini" <?= isset($_GET['filter']) && $_GET['filter'] == 'hari_ini' ? 'selected' : '' ?>>Hari Ini</option>
                    <option value="akan_datang" <?= isset($_GET['filter']) && $_GET['filter'] == 'akan_datang' ? 'selected' : '' ?>>Akan Datang</option>
                    <option value="selesai" <?= isset($_GET['filter']) && $_GET['filter'] == 'selesai' ? 'selected' : '' ?>>Sudah Lewat</option>
                  </select>
                </form>
              </div>
              <div class="d-flex gap-2">
                <a class="btn btn-success" id="btnPilih"><i class="bi bi-check-circle"></i> Pilih
                </a>
                <a class="btn btn-primary" data-bs-target="#modalTambahKegiatan" data-bs-toggle="modal" role="button"><i class="bi bi-plus-circle"></i> Tambah Data
                </a>
              </div>
            </div>
            <div class="table-responsive text-center">
              <table class="table text-center table-bordered">
                <thead class="table-primary" >
                  <tr>
                    <th>No</th>
                    <th>Nama Kegiatan</th>
                    <th>Tanggal dan Waktu</th>
                    <th>Tempat</th>
                    <th>Penanggung Jawab</th>
                    <th>Status</th>
                    <th class="aksi-col d-none">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'hapus_berhasil') : ?>
                    <div class="alert alert-success" id="alerthps">
                      Data berhasil dihapus.
                    </div>
                  <?php endif; ?>
                  <?php
                  $no = 1;
                  mysqli_data_seek($result, 0); // pastikan kursor di awal
                  while ($row = mysqli_fetch_assoc($result)) :
                  ?>
                    <tr>
                      <td><?= $no++ ?></td>
                      <td><?= $row['nama'] ?></td>
                      <td><?= $row['tanggal'] ?></td>
                      <td><?= $row['tempat'] ?></td>
                      <td><?= $row['jawab'] ?></td>
                      <td><?= $row['status'] ?></td>
                      <td class="aksi-col d-none">
                        <!-- Tombol Edit -->
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">Edit</button>
                        <!-- Tombol Hapus -->
                        <a href="kegiatan.php?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                      </td>
                    </tr>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <form method="post" action="">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <div class="modal-header justify-content-center text-primary">
                              <h5 class="modal-title ">Edit Data Kegiatan</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-start">
                              <div class="mb-3">
                                <label>Nama</label>
                                <input type="text" name="nama" class="form-control" value="<?= $row['nama'] ?>" required>
                              </div>
                              <div class="mb-3">
                                <label>Tanggal</label>
                                <input type="datetime-local" name="tanggal" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($row['tanggal'])) ?>" required>
                              </div>
                              <div class="mb-3">
                                <label>Tempat</label>
                                <input type="text" name="tempat" class="form-control" value="<?= $row['tempat'] ?>" required>
                              </div>
                              <div class="mb-3">
                                <label>Penanggung Jawab</label>
                                <input type="text" name="jawab" class="form-control" value="<?= $row['jawab'] ?>" required>
                              </div>
                              <div class="mb-3">
                                <label>Status</label>
                                <select name="status" class="form-control" required>
                                  <option value="Tersedia" <?= $row['status'] == 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                                  <option value="Tidak Tersedia" <?= $row['status'] == 'Tidak Tersedia' ? 'selected' : '' ?>>Tidak Tersedia</option>
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
                  <!-- form tambah kegiatan -->
                  <div class="modal fade" id="modalTambahKegiatan" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <form method="post" action="">
                          <div class="modal-header justify-content-center text-primary">
                            <h5 class="modal-title" id="modalTambahLabel">Tambah Data Kegiatan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                          </div>
                          <div class="modal-body text-start">
                            <div class="mb-3">
                              <label for="nama" class="form-label">Nama Kegiatan</label>
                              <input type="text" class="form-control" id="nama" name="nama" required>
                            </div>
                            <div class="mb-3">
                              <label>Tanggal dan Waktu</label>
                              <input type="datetime-local" id="tanggal" name="tanggal" class="form-control" required>
                            </div>
                            <div class="mb-3">
                              <label for="tempat" class="form-label">Tempat</label>
                              <input type="text" class="form-control" id="tempat" name="tempat" required>
                            </div>
                            <div class="mb-3">
                              <label for="jawab" class="form-label">Penanggung Jawab</label>
                              <input type="text" class="form-control" id="jawab" name="jawab" required>
                            </div>
                            <div class="mb-3">
                              <label>Status Kegiatan</label>
                              <select type="text" name="status" id="status" class="form-control" required><i class="bi bi-chevron-down"></i>
                                <option value="Tersedia">Tersedia</option>
                                <option value="Tidak Tersedia">Tidak Tersedia</option>
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
  <script>
    document.getElementById('btnPilih').addEventListener('click', function() {
      const aksiCols = document.querySelectorAll('.aksi-col');
      aksiCols.forEach(col => {
        col.classList.toggle('d-none');
      });
    });
    // Hilangkan alert setelah 9 detik 
    setTimeout(function() {
      const alertEl = document.getElementById('alerthps');
      if (alertEl) {
        alertEl.style.display = 'none';
      }
    }, 3000);
  </script>
</body>

</html>