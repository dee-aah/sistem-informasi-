<?php
include "koneksi.php";
//anggota
$result = mysqli_query($conn, "SELECT * FROM kegiatan");
$cari = isset($_GET['cari']) ? $_GET['cari'] : '';
if ($cari != '') {
  $result = mysqli_query($conn, "SELECT * FROM kegiatan WHERE nama LIKE '%$cari%'");
} else {
  $result = mysqli_query($conn, "SELECT * FROM kegiatan");
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
  <link href="style.css" rel="stylesheet"/>
</head>
<body>
 <header>
    <nav class="navbar bg-light shadow navbar-light  fixed-top">
      <div class="container-fluid">
        <h2 class="text-dark text-center fw-bold" style="padding-left: 50px;">BMKG</h2>
      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search"/>
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
      </div>
    </nav>
  </header>
  <main>
  <div class=" container-fluid  " style="margin-top: 55px;" > 
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
          <h3>Daftar Kegiatan Komunitas</h3> 
          <div class="mb-3 justify-content-between d-flex">
            <div class="me-2">
              <select class="form-select">
                <option selected>Pilih</option>
                <option value="1">Semua</option>
                <option value="2">Aktif</option>
                <option value="3">Selesai</option>
              </select>
            </div>
            <div class="d-flex gap-2"> 
              <a class="btn btn-success" id="btnPilih"><i class="bi bi-check-circle"></i> Pilih
              </a>
              <a class="btn btn-primary" href="tambahkegiatan.php" role="button"><i class="bi bi-plus-circle"></i> Tambah Data
              </a>
            </div>
          </div>
          <div class="table-responsive text-center">
            <table class="table table-bordered">
              <thead>
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
      <div class="alert alert-success">
      Data berhasil dihapus.
      </div>
    <?php endif; ?>
   <?php
    $no = 1;
    while($row = mysqli_fetch_assoc($result)) {
      echo "<tr>
        <td>{$no}</td>
        <td>{$row['nama']}</td>
        <td>{$row['tanggal']}</td>
        <td>{$row['tempat']}</td>
        <td>{$row['jawab']}</td>
        <td>{$row['status']}</td>
        <td class='aksi-col d-none'>
          <a href='edit.php?id={$row['id']}' class='btn btn-sm btn-warning'>Edit</a>
          <a href='hapus.php?id={$row['id']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Yakin ingin menghapus?')\">Hapus</a>
        </td>
      </tr>";
      $no++;
    }
    ?>
                <!-- Tambahkan data lainnya -->
              </tbody>
            </table>
          </div>

        </div>
      
    </div>
  </div>
</main>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
  document.getElementById('btnPilih').addEventListener('click', function () {
    const aksiCols = document.querySelectorAll('.aksi-col');
    aksiCols.forEach(col => {
      col.classList.toggle('d-none');
    });
  });
</script>
</body>
</html>