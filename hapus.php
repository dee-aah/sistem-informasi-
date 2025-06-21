<?php
include "koneksi.php"; // koneksi ke database

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Hapus data berdasarkan id
    $query = mysqli_query($conn, "DELETE FROM anggota WHERE id = '$id'");

    if ($query) {
        // Redirect balik ke halaman utama dengan pesan sukses (opsional)
        header("Location: anggota.php?pesan=hapus_berhasil");
        exit();
    } else {
        echo "Gagal menghapus data.";
    }
} else {
    echo "ID tidak ditemukan.";
}
?>
