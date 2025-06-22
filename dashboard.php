<?php
include "koneksi.php";
//anggota
$query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM anggota");
$data = mysqli_fetch_assoc($query);
$totalAnggota = $data['total'];
//saldo
$query = mysqli_query($conn, "SELECT 
    SUM(pemasukan) AS total_pemasukan,
    SUM(pengeluaran) AS total_pengeluaran 
    FROM keuangan");

$data = mysqli_fetch_assoc($query);
$totalPemasukan = $data['total_pemasukan'];
$totalPengeluaran = $data['total_pengeluaran'];
$saldo = $totalPemasukan - $totalPengeluaran;
$totalsaldo = number_format($saldo, 2, ',', '.');

//kegiatan
$kegiatanQuery = mysqli_query($conn, "SELECT nama, tanggal FROM kegiatan WHERE tanggal >= CURDATE() ORDER BY tanggal ASC LIMIT 1");
if (mysqli_num_rows($kegiatanQuery) > 0) {
    $kegiatanData = mysqli_fetch_assoc($kegiatanQuery);
    $kegiatanTerdekat = $kegiatanData['nama'] . " (" . date('d M Y', strtotime($kegiatanData['tanggal'])) . ")";
} else {
    $kegiatanTerdekat = "Belum ada kegiatan";
}

// Data grafik
// $dataGrafik = mysqli_query($conn, "SELECT waktu, SUM(total) as total FROM keuangan GROUP BY bulan ORDER BY FIELD(bulan, 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des')");

// $labels = [];
// $jumlah = [];

// while ($row = mysqli_fetch_assoc($dataGrafik)) {
//     $labels[] = $row['bulan'];
//     $jumlah[] = $row['total'];
// }
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Komunitas</title>
  <link href="style.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.css" rel="stylesheet" />
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
      <div class="col-2 border-end sidebarmenu  ">
        <div class="position-fixed ">           
        <nav class="nav flex-column text-dark mt-4">
        <a href="dashboard.php" class="nav-link text-primary "><i class="bi bi-house-fill me-2"></i>Beranda</a>
        <a href="anggota.php" class="nav-link"><i class="bi bi-people-fill me-2"></i>Data Anggota</a>
        <a href="kegiatan.php" class="nav-link"><i class="bi bi-calendar-event-fill me-2"></i>Kegiatan</a>
        <a href="keuangan.php" class="nav-link"><i class="bi bi-cash-stack me-2"></i>Keuangan</a>
        <a href="#" class="nav-link"><i class="bi bi-file-earmark-text-fill me-2"></i>Laporan Bulanan</a>
        </nav>
        </div>
      </div>
      <div class=" col-10 bg-light" >
        <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="title-blue pt-5 ">DASHBOARD</h5>
        </div>
        <div class="row g-3 mb-3">
          <div class="col-md-4">
            <div class="dashboard-box border border-3 border-primary-subtle">
              <h3>Data Anggota</h3>
              <h5 class="mt-2"><?= $totalAnggota ?>  Orang</h5>
              <p class="text-muted">Anggota aktif komunitas</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="dashboard-box border border-3 border-primary-subtle">
              <h3>Jumlah Saldo</h3>
              <h5 class="mt-2">Rp.<?= $totalsaldo ?> </h5>
            </div>
          </div>
          <div class="col-md-4">
            <div class="dashboard-box border border-3 border-primary-subtle">
              <h3>Kegiatan Terdekat </h3>
              <li><?= $kegiatanTerdekat ?></li>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-8">
            <div>
            <h3>Grafik Keuangan</h3>
            <canvas id="grafikKeuangan"></canvas>
            </div
          </div>
        </div>
      </div>
    </div>
  </div>
  </main>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('grafikKeuangan').getContext('2d');
  const chart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: <?= json_encode($labels); ?>,
      datasets: [{
        label: 'Iuran Masuk (Rp)',
        data: <?= json_encode($jumlah); ?>,
        backgroundColor: 'rgba(13, 110, 253, 0.7)'
      }]
    },
    options: {
      responsive: true,
      plugins: {
        tooltip: {
          callbacks: {
            label: function(context) {
              let value = context.raw.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
              return 'Rp. ' + value;
            }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: function(value) {
              return 'Rp. ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }
          }
        }
      }
    }
  });
</script>



</body>
</html>
