<?php
session_start();
require 'koneksi.php';

// Cek keamanan
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

// Menangkap ID dari URL
$id_sewa = $_GET["id"];
$user_id = $_SESSION["id"];

// Menghapus data dari database
$query_hapus = "DELETE FROM penyewaan WHERE id = '$id_sewa' AND user_id = '$user_id'";
$sukses = mysqli_query($conn, $query_hapus);

if($sukses) {
    // Arahkan kembali ke menu dashboard dengan benar
    header("Location: beranda.php?page=dashboard");
} else {
    // Menampilkan pesan error jika query gagal
    echo "Gagal menghapus data: " . mysqli_error($conn);
}
exit;
?>