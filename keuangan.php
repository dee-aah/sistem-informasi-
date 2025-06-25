<?php
include "koneksi.php";

// filter
$where = '';
if (isset($_GET['filter'])) {
  $filter = $_GET['filter'];

  if ($filter == 'bulan_ini') {
    $bulan = date('m');
    $tahun = date('Y');
    $where = "WHERE MONTH(waktu) = $bulan AND YEAR(waktu) = $tahun";
  } elseif ($filter == 'bulan_lalu') {
    $bulan = date('m', strtotime('first day of last month'));
    $tahun = date('Y', strtotime('first day of last month'));
    $where = "WHERE MONTH(waktu) = $bulan AND YEAR(waktu) = $tahun";
  } elseif ($filter == '1_tahun') {
    $tahun_lalu = date('Y-m-d', strtotime('-1 year'));
    $where = "WHERE waktu >= '$tahun_lalu'";
  }
}

$result = mysqli_query($conn, "SELECT * FROM keuangan $where ORDER BY waktu DESC");

// Search data
$cari = isset($_GET['cari']) ? $_GET['cari'] : '';
if ($cari != '') {
  if ($where != '') {
    $where .= " AND keterangan LIKE '%$cari%'";
  } else {
    $where = "WHERE keterangan LIKE '%$cari%'";
  }
}
$result = mysqli_query($conn, "SELECT * FROM keuangan $where ORDER BY waktu DESC");

// Tambah saldo
if (isset($_POST['simpan'])) {
  $waktu = $_POST['waktu'];
  $keterangan = $_POST['keterangan'];
  $pemasukan = $_POST['pemasukan'];
  $pengeluaran = $_POST['pengeluaran'];

  $query = mysqli_query($conn, "INSERT INTO keuangan (waktu, keterangan, pemasukan, pengeluaran)
                                VALUES ('$waktu', '$keterangan', '$pemasukan', '$pengeluaran')");
  
  if ($query) {
    echo "<script>window.location.href='keuangan.php';</script>";
  } else {
    echo "<script>alert('Gagal menambahkan data');</script>";
  }
}

// Total pemasukan & pengeluaran 
$queryTotal = mysqli_query($conn, "SELECT 
    SUM(pemasukan) AS total_pemasukan,
    SUM(pengeluaran) AS total_pengeluaran 
    FROM keuangan $where");

$data = mysqli_fetch_assoc($queryTotal);
$masuk = $data['total_pemasukan'];
$totalPemasukan =number_format($masuk, 2, ',', '.');
$keluar = $data['total_pengeluaran'];
$totalPengeluaran =number_format($keluar, 2, ',', '.');
$saldo = $masuk - $keluar;
$totalsaldo = number_format($saldo, 2, ',', '.');
//update data
if (isset($_POST['update'])) {
  $id = $_POST['id'];
  $waktu = $_POST['waktu'];
  $keterangan = $_POST['keterangan'];
  $pemasukan = $_POST['pemasukan'];
  $pegeluaran = $_POST['pengeluaran'];

  $update = mysqli_query($conn, "UPDATE keuangan SET waktu='$waktu', keterangan='$keterangan', pemasukan='$pemasukan', pengeluaran='$pengeluaran' WHERE id='$id'");
  if ($update) {
    echo "<script>alert('Data berhasil diubah!'); window.location='keuangan.php';</script>";
  } else {
    echo "<script>alert('Gagal mengubah data.');</script>";
  }
}
//hapus data
if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  $hapus = mysqli_query($conn, "DELETE FROM keuangan WHERE id = '$id'");
  if ($hapus) {
    echo "<script>window.location.href='keuangan.php?pesan=hapus_berhasil';</script>";
    exit();
  } else {
    echo "<script>alert('Gagal menghapus data');</script>";
  }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Keuangan</title>
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
          <input class="form-control me-2" type="search" aria-label="Search" name="cari" placeholder="Cari Nama Keterangan" value="<?= htmlspecialchars($cari) ?>" />
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
              <a href="kegiatan.php" class="nav-link"><i class="bi bi-calendar-event-fill me-2"></i>Kegiatan</a>
              <a href="keuangan.php" class="nav-link text-primary"><i class="bi bi-cash-stack me-2"></i>Keuangan</a>
              <a href="laporan.php" class="nav-link"><i class="bi bi-file-earmark-text-fill me-2"></i>Laporan Bulanan</a>
            </nav>
          </div>
        </div>
        <div class=" col-10  " style="margin-top: 40px;">
          <div class="content">
            <h3 class="fw-bold font-monospace text-center" style="font-weight: bold; font-size: 2rem; color: #003366;">Data Keuangan</h3>
            <div class="mb-3 row d-flex">
              <div class="me-2 col-2">
                <form method="get" id="filterForm" class="d-inline">
                  <select name="filter" class="form-select" onchange="document.getElementById('filterForm').submit()">
                    <option value="">Pilih Waktu</option>
                    <option value="bulan_ini" <?= isset($_GET['filter']) && $_GET['filter'] == 'bulan_ini' ? 'selected' : '' ?>>Bulan Ini</option>
                    <option value="bulan_lalu" <?= isset($_GET['filter']) && $_GET['filter'] == 'bulan_lalu' ? 'selected' : '' ?>>Bulan Lalu</option>
                    <option value="1_tahun" <?= isset($_GET['filter']) && $_GET['filter'] == '1_tahun' ? 'selected' : '' ?>>1 Tahun Terakhir</option>
                  </select>
                </form>
              </div>
              <div class="col-6">
                <h5 class="pt-1  d-flex col text-start align-items-center justify-content-start">Jumlah Saldo : Rp <strong><?= $totalsaldo ?> </strong></h5>
              </div>
              <div class=" col align-items-center  d-grid gap-2 d-md-flex justify-content-md-end">
                <a class="btn  btn-success pe-4 ps-4 me-0" id="btnPilih"><i class="bi bi-check-circle"></i> Pilih</a>
                <a class="btn  btn-primary" data-bs-target="#modalTambahKeuangan" data-bs-toggle="modal" role="button"><i class="bi bi-plus-circle"></i> Tambah Data</a>
              </div>
            </div>
            <div class="table-responsive text-center">
              <table class="table table-bordered">
                <thead class="table-primary">
                  <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>Pemasukan</th>
                    <th>Pengeluaran</th>
                    <th class="aksi-col d-none">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'hapus_berhasil') : ?>
                    <div class="alert alert-success" id="alerthps">
                      Data berhasil dihapus 
                    </div>
                  <?php endif; ?>
                  <?php
                  $no = 1;
                  mysqli_data_seek($result, 0);
                  while ($row = mysqli_fetch_assoc($result)) :
                  ?>
                    <tr>
                      <td><?= $no++ ?></td>
                      <td><?= $row['waktu'] ?></td>
                      <td><?= $row['keterangan'] ?></td>
                      <td>Rp <?= $row['pemasukan'] ?></td>
                      <td>Rp <?= $row['pengeluaran'] ?></td>
                      <td class="aksi-col d-none">
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">Edit</button>
                        <a href="keuangan.php?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                      </td>
                    </tr>
                    <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <form method="post" action="">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <div class="modal-header justify-content-center text-primary">
                              <h5 class="modal-title ">Edit Data Keuangan</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-start">
                              <div class="mb-3">
                                <label>Waktu</label>
                                <input type="date" name="waktu" class="form-control" value="<?= date('Y-m-d', strtotime($row['waktu'])) ?>" required>
                              </div>
                              <div class="mb-3">
                                <label>Keterangan</label>
                                <input type="text" name="keterangan" class="form-control" value="<?= $row['keterangan'] ?>" required>
                              </div>
                              <div class="mb-3">
                                <label>Pemasukkan</label>
                                <input type="text" name="pemasukan" class="form-control" value="<?= $row['pemasukan'] ?>" required>
                              </div>
                              <div class="mb-3">
                                <label>Pengeluaran</label>
                                <input type="text" name="pengeluaran" class="form-control" value="<?= $row['pengeluaran'] ?>" required>
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
                  <tr>
                    <th class="text-start ps-4" colspan="3">Total</th>
                    <th>Rp <?= $totalPemasukan ?></th>
                    <th>Rp <?= $totalPengeluaran ?></th>
                  </tr>
                  <tr>
                    <th class="text-start ps-4" colspan="4">Jumlah Saldo</th>
                    <th>Rp <?= $totalsaldo ?></th>
                  </tr>
                
                  <!-- Modal Tambah Data Keuangan -->
                  <div class="modal fade" id="modalTambahKeuangan" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <form method="post" action="">
                          <div class="modal-header text-primary">
                            <h5 class="modal-title" id="modalTambahLabel">Tambah Data Keuangan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                          </div>
                          <div class="modal-body ">
                            <div class="mb-3">
                              <label for="waktu" class="form-label">Tanggal</label>
                              <input type="date" class="form-control" id="waktu" name="waktu" required>
                            </div>
                            <div class="mb-3">
                              <label for="keterangan" class="form-label">Keterangan</label>
                              <textarea class="form-control" id="keterangan" name="keterangan" rows="2" required></textarea>
                            </div>
                            <div class="mb-3">
                              <label for="pemasukan" class="form-label">Pemasukan</label>
                              <input type="text" class="form-control" id="pemasukan" name="pemasukkan" >
                            </div>
                            <div class="mb-3">
                              <label for="pengeluaran" class="form-label">Pengeluaran</label>
                              <input type="text" class="form-control" id="pengeluaran" name="pengeluaran">
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
    setTimeout(function() {
      const alertEl = document.getElementById('alerthps');
      if (alertEl) {
        alertEl.style.display = 'none';
      }
    }, 3000);
  </script>
</body>

</html>