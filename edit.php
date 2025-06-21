<?php
include "koneksi.php";
$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM anggota WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (isset($_POST['simpan'])) {
  $nama = $_POST['nama'];
  $tgl_lahir = $_POST['tgl_lahir'];
  $no_hp = $_POST['no_hp'];
  $tgl_bergabung = $_POST['tgl_bergabung'];
  $status = $_POST['status'];

  mysqli_query($conn, "UPDATE anggota SET 
    nama = '$nama',
    tgl_lahir = '$tgl_lahir',
    no_hp = '$no_hp',
    tgl_bergabung = '$tgl_bergabung',
    status = '$status'
    WHERE id = $id");

  header("Location: anggota.php");
  exit();
}
?>
<?php include 'koneksi.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Data Anggota</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="p-4">
<div class="container">
   <h2 class="mb-2 text-center">EDIT DATA ANGGOTA</h2>
    <h3 class="text-center text-primary"> Barudak Mania Kikisik Galunggung</h3></form>
   <form method="post">
  <input type="text" class="form-control" name="nama" value="<?= $data['nama'] ?>" required><br>
  <input type="date" class="form-control" name="tgl_lahir" value="<?= $data['tgl_lahir'] ?>" required><br>
  <input type="text" class="form-control" name="no_hp" value="<?= $data['no_hp'] ?>" required><br>
  <input type="date" class="form-control" name="tgl_bergabung" value="<?= $data['tgl_bergabung'] ?>" required><br>
  <select class="form-control" name="status">
    <option value="Aktif" <?= $data['status'] == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
    <option value="Tidak Aktif" <?= $data['status'] == 'Tidak Aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
  </select><br>
    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <button type="submit" name="simpan" class="btn btn-primary me-md-2">Simpan</button>
        <a href="anggota.php" class="btn btn-secondary">Batal</a>
    </div>
</form>
</div>

</body>
</html>