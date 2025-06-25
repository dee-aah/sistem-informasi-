<?php
include 'koneksi.php';

//anggota
$query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM anggota");
$data = mysqli_fetch_assoc($query);
$totalAnggota = $data['total'];

$query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM kegiatan");
$data = mysqli_fetch_assoc($query);
$totalkegiatan = $data['total'];
//saldo
$query = mysqli_query($conn, "SELECT 
    SUM(pemasukan) AS total_pemasukan,
    SUM(pengeluaran) AS total_pengeluaran 
    FROM keuangan");

$data = mysqli_fetch_assoc($query);
$masuk = $data['total_pemasukan'];
$totalPemasukan =number_format($masuk, 2, ',', '.');
$keluar = $data['total_pengeluaran'];
$totalPengeluaran =number_format($keluar, 2, ',', '.');
$saldo = $masuk - $keluar;
$totalsaldo = number_format($saldo, 2, ',', '.');

//kegiatan
$kegiatanQuery = mysqli_query($conn, "SELECT nama, tanggal FROM kegiatan WHERE tanggal >= CURDATE() ORDER BY tanggal ASC LIMIT 1");
if (mysqli_num_rows($kegiatanQuery) > 0) {
    $kegiatanData = mysqli_fetch_assoc($kegiatanQuery);
    $kegiatanTerdekat = $kegiatanData['nama'] . " (" . date('d M Y', strtotime($kegiatanData['tanggal'])) . ")";
} else {
    $kegiatanTerdekat = "Belum ada kegiatan";
}

$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

$query = "SELECT * FROM keuangan 
          WHERE MONTH(waktu) = '$bulan' AND YEAR(waktu) = '$tahun' 
          ORDER BY waktu ASC";

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <title>Laporan Keuangan Bulanan</title>
    <link href="style.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>
    <header>
        <nav class="navbar bg-light shadow navbar-light  fixed-top">
            <div class="container-fluid">
                <h2 class="text-dark text-center fw-bold" style="padding-left: 50px;">BMKG</h2>
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" aria-label="Search" name="cari" placeholder="Cari Nama Keterangan"/>
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </nav>
    </header>
    <main>
        <div class=" container-fluid  " style="margin-top: 55px;">
            <div class="row ">
                <div class="col-2 border-end sidebarmenu   ">
                    <div class="position-fixed ">
                        <nav class="nav flex-column text-dark mt-4">
                            <a href="dashboard.php" class="nav-link"><i class="bi bi-house-fill me-2"></i>Beranda</a>
                            <a href="anggota.php" class="nav-link"><i class="bi bi-people-fill me-2"></i>Data Anggota</a>
                            <a href="kegiatan.php" class="nav-link"><i class="bi bi-calendar-event-fill me-2"></i>Kegiatan</a>
                            <a href="keuangan.php" class="nav-link "><i class="bi bi-cash-stack me-2"></i>Keuangan</a>
                            <a href="laporan.php" class="nav-link text-primary"><i class="bi bi-file-earmark-text-fill me-2"></i>Laporan Bulanan</a>
                        </nav>
                    </div>
                </div>
                <div class=" col-10 bg-light " style="padding-top: 40px;">
                    <div class="content">
                        <h2 class="fw-bold font-monospace pb-3 pt-2 text-center" style="font-weight: bold; font-size: 2rem; color: #003366;">Laporan Keuangan Bulan <?= date('F', mktime(0, 0, 0, $bulan, 1)) . " $tahun" ?></h2>
                        <form class="row g-3 mb-4" method="get">
                            <div class="col-auto">
                                <select name="bulan" class="form-select" required>
                                    <?php for ($i = 1; $i <= 12; $i++) {
                                        $selected = ($i == $bulan) ? "selected" : "";
                                        echo "<option value='$i' $selected>" . date('F', mktime(0, 0, 0, $i, 1)) . "</option>";
                                    } ?>
                                </select>
                            </div>
                            <div class="col-auto">
                                <input type="number" name="tahun" class="form-control" value="<?= $tahun ?>" required>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary">Tampilkan</button>
                            </div>
                            <div class="col-auto">
                                <a href="cetak.php?bulan=<?= $bulan ?>&tahun=<?= $tahun ?>" target="_blank" class="btn btn-danger">
                                    Cetak PDF
                                </a>
                            </div>
                        </form>

                        <table class="table table-bordered">
                            <thead class="text-center table-primary">
                                <tr>
                                    <th>No</th>
                                    <th>Waktu</th>
                                    <th>Keterangan</th>
                                    <th>Pemasukkan</th>
                                    <th>Pengeluaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no=1;
                                // $total = 0;
                                 while ($row = mysqli_fetch_assoc($result)):
                                //     $total += $row['pengeluaran'];
                                ?>
                                    <tr class="text-center">
                                        <td><?= $no++ ?></td>
                                        <td><?= date('d-m-Y', strtotime($row['waktu'])) ?></td>
                                        <td><?= $row['keterangan'] ?></td>
                                        <td>Rp <?= number_format($row['pemasukan'], 0, ',', '.') ?></td>
                                        <td>Rp <?= number_format($row['pengeluaran'], 0, ',', '.') ?></td>
                                    </tr>
                                <?php endwhile; ?>
                                <tr>
                                    <th class="text-start ps-3" colspan="3">Total</th>
                                    <th>Rp <?= $totalPemasukan ?></th>
                                    <th>Rp <?= $totalPengeluaran ?></th>
                                </tr>
                                <tr>
                                    <th class="text-start ps-3" colspan="4">Jumlah Saldo</th>
                                    <th>Rp <?= $totalsaldo ?></th>
                                </tr>
                            </tbody>
                        </table>
</body>

</html>