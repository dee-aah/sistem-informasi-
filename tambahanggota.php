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
    <h2 class="mb-2 text-center">TAMBAH DATA ANGGOTA</h2>
    <h3 class="text-center text-primary"> Barudak Mania Kikisik Galunggung</h3>

    <form action="" method="post">
      <div class="mb-3">
        <label>Nama Anggota</label>
        <input type="text" name="nama" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Tanggal Lahir</label>
        <input type="date" name="tgl_lahir" class="form-control" required>
      </div>
       <div class="mb-3">
        <label>No Telphone</label>
        <input type="text" name="no_hp" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Tanggal Bergabung</label>
        <input type="date" name="tgl_bergabung" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Status Anggota</label>
        <select type="text" name="status" class="form-control" required><i class="bi bi-chevron-down"></i>
          <option value="Aktif">Aktif</option>
          <option value="Tidak Aktif">Tidak Aktif</option>
        </select>
      </div >
      <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <button type="submit" name="simpan" class="btn btn-primary me-md-2">Simpan</button>
        <a href="anggota.php" class="btn btn-secondary">Batal</a>
      </div>
      
      
    </form>
  </div>

</body>
</html>

<?php
if (isset($_POST['simpan'])) {
  $nama = $_POST['nama'];
  $tgl_lahir = $_POST['tgl_lahir'];
  $no_hp = $_POST['no_hp'];
  $tgl_bergabung = $_POST['tgl_bergabung'];
  $status = $_POST['status'];

  $query = "INSERT INTO anggota (nama, tgl_lahir, no_hp, tgl_bergabung, status) VALUES ('$nama', '$tgl_lahir', '$no_hp', '$tgl_bergabung', '$status')";
  $hasil = mysqli_query($conn, $query);

  if ($hasil) {
    echo "<script>alert('Data berhasil ditambahkan!'); window.location='anggota.php';</script>";
  } else {
    echo "<script>alert('Gagal menambahkan data.');</script>";
  }
}
?>
