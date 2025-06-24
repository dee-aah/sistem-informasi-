<?php
include "koneksi.php";

// Inisialisasi filter
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

// Query utama menggunakan filter waktu
$result = mysqli_query($conn, "SELECT * FROM keuangan $where ORDER BY waktu DESC");

// Search berdasarkan nama (opsional, hanya kalau pakai kolom 'nama')
$cari = isset($_GET['cari']) ? $_GET['cari'] : '';
if ($cari != '') {
  $result = mysqli_query($conn, "SELECT * FROM keuangan WHERE keterangan LIKE '%$cari%'");
}

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

// Total pemasukan & pengeluaran (tetap dari semua data, bukan hasil filter)
$queryTotal = mysqli_query($conn, "SELECT 
    SUM(pemasukan) AS total_pemasukan,
    SUM(pengeluaran) AS total_pengeluaran 
    FROM keuangan $where");

$data = mysqli_fetch_assoc($queryTotal);
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
                        <form method="get" id="filterForm" class="d-flex gap-2 mb-3">
                            <select name="filter" class="form-select" onchange="document.getElementById('filterForm').submit()">
                                <option value="">Pilih Waktu</option>
                                <option value="bulan_ini" <?= isset($_GET['filter']) && $_GET['filter'] == 'bulan_ini' ? 'selected' : '' ?>>Bulan Ini</option>
                                <option value="bulan_lalu" <?= isset($_GET['filter']) && $_GET['filter'] == 'bulan_lalu' ? 'selected' : '' ?>>Bulan Lalu</option>
                                <option value="1_tahun" <?= isset($_GET['filter']) && $_GET['filter'] == '1_tahun' ? 'selected' : '' ?>>1 Tahun Terakhir</option>
                            </select>
                        </form>                                 
                        </div>
                        <div class="col-5 align-items-center ">
                            <p class=" align-items-center">Jumlah Saldo : Rp <strong><?=$totalsaldo?> </strong></p>
                        </div>
                        <div class="col justify-content-end d-flex"> 
                            <a class="btn btn-success pe-4 ps-4 me-3 " id="btnPilih"><i class="bi bi-check-circle pe-2"></i> Pilih
                            </a>
                            <a class="btn btn-primary" data-bs-target="#modalTambahKeuangan" data-bs-toggle="modal" href="tambahsaldo.php" role="button"><i class="bi bi-plus-circle"></i> Tambah Data
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
    <!-- Modal Tambah Data Keuangan -->
<div class="modal fade" id="modalTambahKeuangan" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" action="">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTambahLabel">Tambah Data Keuangan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
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
            <input type="number" class="form-control" id="pemasukan" name="pemasukan" min="0" value="0">
          </div>
          <div class="mb-3">
            <label for="pengeluaran" class="form-label">Pengeluaran</label>
            <input type="number" class="form-control" id="pengeluaran" name="pengeluaran" min="0" value="0">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="simpan" class="btn btn-success">Simpan</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </form>
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