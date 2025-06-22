<?php
include "koneksi.php";
//anggota
$query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM anggota");
$data = mysqli_fetch_assoc($query);
$totalAnggota = $data['total'];
$result = mysqli_query($conn, "SELECT * FROM anggota");
$cari = isset($_GET['cari']) ? $_GET['cari'] : '';
if ($cari != '') {
  $result = mysqli_query($conn, "SELECT * FROM anggota WHERE nama LIKE '%$cari%'");
} else {
  $result = mysqli_query($conn, "SELECT * FROM anggota");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Data Anggota</title>
  <link href="style.css" rel="stylesheet"/>
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
  <div class="container-fluid" style="margin-top: 55px;" > 
    <div class="row ">
      <div class="col-2 border-end sidebarmenu  ">
        <div class="position-fixed ">           
          <nav class="nav flex-column text-dark mt-4">
          <a href="dashboard.php" class="nav-link"><i class="bi bi-house-fill me-2"></i>Beranda</a>
          <a href="anggota.php" class="nav-link text-primary"><i class="bi bi-people-fill me-2"></i>Data Anggota</a>
          <a href="kegiatan.php" class="nav-link"><i class="bi bi-calendar-event-fill me-2"></i>Kegiatan</a>
          <a href="keuangan.php" class="nav-link"><i class="bi bi-cash-stack me-2"></i>Keuangan</a>
          <a href="#" class="nav-link"><i class="bi bi-file-earmark-text-fill me-2"></i>Laporan Bulanan</a>
          </nav>
        </div>
      </div>
      <div class=" col-10 bg-light " style="padding-top: 60px;" >
        <div class="d-flex justify-content-between align-items-center mb-3">
            <p class="mb-0">Jumlah Anggota : <strong><?=$totalAnggota?> Orang</strong></p>
          <div class="d-flex row">
            <div class="d-flex gap-2"> 
              <a class="btn btn-success" id="btnPilih"><i class="bi bi-check-circle"></i> Pilih
              </a>
              <a class="btn btn-primary" href="tambahanggota.php" role="button"><i class="bi bi-plus-circle"></i> Tambah Data
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
        <td>{$row['tgl_lahir']}</td>
        <td>{$row['no_hp']}</td>
        <td>{$row['tgl_bergabung']}</td>
        <td>{$row['status']}</td>
        <td class='aksi-col d-none'>
          <a href='edit.php?id={$row['id']}' class='btn btn-sm btn-warning'>Edit</a>
          <a href='hapus.php?id={$row['id']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Yakin ingin menghapus?')\">Hapus</a>
        </td>
      </tr>";
      $no++;
    }
    ?>
  </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
