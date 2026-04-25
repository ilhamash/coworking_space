<?php
session_start();
ob_start();
require 'koneksi.php';

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["id"];

$cek_ruangan = mysqli_query($conn, "SELECT * FROM pengaturan_ruangan WHERE user_id = '$user_id'");
if(mysqli_num_rows($cek_ruangan) == 0) {
    mysqli_query($conn, "INSERT INTO pengaturan_ruangan (user_id, nama_ruangan, kode_prefix, jumlah_unit, kapasitas_per_unit) VALUES 
    ('$user_id', 'Hot Desk', 'h', 10, 1),
    ('$user_id', 'Private Office', 'p', 5, 4),
    ('$user_id', 'Meeting Room', 'm', 3, 10)");
}

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Workspace</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="page-dashboard">

    <?php include 'sidebar.php'; ?>

    <div class="content-area">
        <?php 
        if ($page == 'dashboard') {
            include 'menu_beranda.php';
        } else if ($page == 'ruangan') {
            include 'menu_ruangan.php';
        } else if ($page == 'pesanan') { // Tambahkan ini
            include 'menu_pesanan.php';
        } else {
            echo "<h2>Halaman tidak ditemukan!</h2>";
        }
        ?>
    </div>

</body>
</html>
<?php 
ob_end_flush();
?>