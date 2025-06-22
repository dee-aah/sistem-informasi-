<?php
include "koneksi.php";
//anggota
$result = mysqli_query($conn, "SELECT * FROM keuangan");
$cari = isset($_GET['cari']) ? $_GET['cari'] : '';
if ($cari != '') {
  $result = mysqli_query($conn, "SELECT * FROM keuangan WHERE nama LIKE '%$cari%'");
} else {
  $result = mysqli_query($conn, "SELECT * FROM keuangan");
}
// total pemasukan dan pengeluaran
$query = mysqli_query($conn, "SELECT 
    SUM(pemasukan) AS total_pemasukan,
    SUM(pengeluaran) AS total_pengeluaran 
    FROM keuangan");

$data = mysqli_fetch_assoc($query);

// Hitung saldo
$totalPemasukan = $data['total_pemasukan'];
$saldopemasukan = number_format($totalPemasukan, 2, ',', '.');
$totalPengeluaran = $data['total_pengeluaran'];
$saldopengeluaran = number_format($totalPengeluaran, 2, ',', '.');
$saldo = $totalPemasukan - $totalPengeluaran;
$totalsaldo = number_format($saldo, 2, ',', '.');

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Keuangan</title>
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
                    <a href="kegiatan.php" class="nav-link"><i class="bi bi-calendar-event-fill me-2"></i>Kegiatan</a>
                    <a href="keuangan.php" class="nav-link text-primary"><i class="bi bi-cash-stack me-2"></i>Keuangan</a>
                    <a href="#" class="nav-link"><i class="bi bi-file-earmark-text-fill me-2"></i>Laporan Bulanan</a>
                </nav>
                </div>
            </div>
            <div class=" col-10  " style="margin-top: 40px;">
                <div class="content">
                    <h3 class="fw-bold">Data Keuangan</h3> 
                    <div class="row mt-3 mb-3">
                        <div class="col-2 align-items-center">
                        <select class="form-select">
                            <option selected>Pilih</option>
                            <option value="1">Semua</option>
                            <option value="2">Aktif</option>
                            <option value="3">Selesai</option>
                        </select>                                 
                        </div>
                        <div class="col-5 align-items-center ">
                            <p class=" align-items-center">Jumlah Saldo : Rp <strong><?=$totalsaldo?> </strong></p>
                        </div>
                        <div class="col justify-content-end d-flex"> 
                            <a class="btn btn-success pe-4 ps-4 me-3 " id="btnPilih"><i class="bi bi-check-circle pe-2"></i> Pilih
                            </a>
                            <a class="btn btn-primary" href="tambahsaldo.php" role="button"><i class="bi bi-plus-circle"></i> Tambah Data
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive text-center">
                        <table class="table table-bordered">
                            <thead>
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
                                <div class="alert alert-success">Data berhasil dihapus.</div>
                            <?php endif; ?>
                            <?php
                                $no = 1;
                                while($row = mysqli_fetch_assoc($result)) {
                            ?>
                            <tr>
                                <td><?= $no ?></td>
                                <td><?= $row['waktu'] ?></td>
                                <td><?= $row['keterangan'] ?></td>
                                <td>Rp <?= number_format($row['pemasukan'] ?? 0, 0, ',', '.') ?></td>
                                <td>Rp <?= number_format($row['pengeluaran'] ?? 0, 0, ',', '.') ?></td>
                                <td class="aksi-col d-none">
                                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="hps.saldo.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                                </td>
                            </tr>
                            <?php $no++;}?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div> 
                <li>Total Pemasukan : Rp <?= $saldopemasukan ?></li>
                <li>Total Pengeluaran : Rp <?= $saldopengeluaran ?></li> 
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