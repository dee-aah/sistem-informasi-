<?php include 'koneksi.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Anggota</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="p-4">

  <div class="container">
    <h2 class="mb-2 text-center">TAMBAH DATA KEGIATAN</h2>
    <h3 class="text-center text-primary"> Barudak Mania Kikisik Galunggung</h3>

    <form action="" method="post">
      <div class="mb-3">
        <label>Nama Kegiatan</label>
        <input type="text" name="nama" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Tanggal dan Waktu</label>
        <input type="datetime-local" name="tanggal" class="form-control" required>
      </div>
       <div class="mb-3">
        <label>Tempat</label>
        <input type="text" name="tempat" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Penanggung Jawab</label>
        <input type="text" name="jawab" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Status Kegiatan</label>
        <select type="text" name="status" class="form-control" required><i class="bi bi-chevron-down"></i>
          <option value="Tersedia">Tersedia</option>
          <option value="Tidak Tersedia">Tidak Tersedia</option>
        </select>
      </div >
      <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <button type="submit" name="simpan" class="btn btn-primary me-md-2">Simpan</button>
        <a href="kegiatan.php" class="btn btn-secondary">Batal</a>
      </div>
      
      
    </form>
  </div>

</body>
</html>

<?php
if (isset($_POST['simpan'])) {
  $nama = $_POST['nama'];
  $tanggal = $_POST['tanggal'];
  $tempat= $_POST['tempat'];
  $jawab = $_POST['jawab'];
  $status = $_POST['status'];

  $query = "INSERT INTO kegiatan (nama, tanggal, tempat, jawab, status) VALUES ('$nama', '$tanggal', '$tempat', '$jawab', '$status')";
  $hasil = mysqli_query($conn, $query);

  if ($hasil) {
    echo "<script>alert('Data berhasil ditambahkan!'); window.location='anggota.php';</script>";
  } else {
    echo "<script>alert('Gagal menambahkan data.');</script>";
  }
}
?>
