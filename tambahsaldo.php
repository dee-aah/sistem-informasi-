<?php
include 'koneksi.php';

if (isset($_POST['simpan'])) {
  $waktu = $_POST['waktu'];
  $keterangan = $_POST['keterangan'];
  $pemasukan = $_POST['pemasukan'];
  $pengeluaran = $_POST['pengeluaran'];

  $query = mysqli_query($conn, "INSERT INTO keuangan (waktu, keterangan, pemasukan, pengeluaran) 
                                VALUES ('$waktu', '$keterangan', '$pemasukan', '$pengeluaran')");

  if ($query) {
    header("Location: keuangan.php");
    exit();
  } else {
    echo "Gagal menambahkan data: " . mysqli_error($conn);
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Data Keuangan</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="p-4">
<div class="container">
  <h2 class="mb-3 text-center">TAMBAH DATA KEUANGAN</h2>
  <form method="post">
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
    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
      <button type="submit" name="simpan" class="btn btn-success">Simpan</button>
      <a href="keuangan.php" class="btn btn-secondary">Batal</a>
    </div>
  </form>
</div>
</body>
</html>
