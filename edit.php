<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

$id_sewa = $_GET["id"];
$user_id = $_SESSION["id"];

// Ambil data spesifik yang mau diedit
$result = mysqli_query($conn, "SELECT * FROM penyewaan WHERE id = '$id_sewa' AND user_id = '$user_id'");
$data = mysqli_fetch_assoc($result);

// Jika data tidak ditemukan, kembalikan ke beranda
if (!$data) {
    header("Location: beranda.php");
    exit;
}

// Proses jika tombol Simpan Perubahan ditekan
if (isset($_POST["update"])) {
    $nama_penyewa = mysqli_real_escape_string($conn, $_POST["nama_penyewa"]);
    $jenis_ruangan = $_POST["jenis_ruangan"];
    $tanggal_sewa = $_POST["tanggal_sewa"];
    $durasi_jam = $_POST["durasi_jam"];
    $total_harga = $_POST["total_harga"];
    $status_pembayaran = $_POST["status_pembayaran"];

    $query = "UPDATE penyewaan SET 
                nama_penyewa = '$nama_penyewa',
                jenis_ruangan = '$jenis_ruangan',
                tanggal_sewa = '$tanggal_sewa',
                durasi_jam = '$durasi_jam',
                total_harga = '$total_harga',
                status_pembayaran = '$status_pembayaran'
              WHERE id = '$id_sewa' AND user_id = '$user_id'";
              
    mysqli_query($conn, $query);
    
    echo "<script>alert('Data berhasil diupdate!'); window.location='beranda.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head><title>Edit Data Sewa</title></head>
<body>
    <h2>Edit Data Penyewaan</h2>
    <form action="" method="POST">
        <label>Nama Penyewa:</label><br>
        <input type="text" name="nama_penyewa" value="<?php echo $data["nama_penyewa"]; ?>" required><br>
        
        <label>Jenis Ruangan:</label><br>
        <select name="jenis_ruangan">
            <option value="Hot Desk" <?php if($data["jenis_ruangan"] == 'Hot Desk') echo 'selected'; ?>>Hot Desk</option>
            <option value="Private Office" <?php if($data["jenis_ruangan"] == 'Private Office') echo 'selected'; ?>>Private Office</option>
            <option value="Meeting Room" <?php if($data["jenis_ruangan"] == 'Meeting Room') echo 'selected'; ?>>Meeting Room</option>
            <option value="Pod Studio" <?php if($data["jenis_ruangan"] == 'Pod Studio') echo 'selected'; ?>>Pod Studio</option>
        </select><br>

        <label>Tanggal Sewa:</label><br>
        <input type="date" name="tanggal_sewa" value="<?php echo $data["tanggal_sewa"]; ?>" required><br>

        <label>Durasi (Jam):</label><br>
        <input type="number" name="durasi_jam" value="<?php echo $data["durasi_jam"]; ?>" required><br>

        <label>Total Harga (Rp):</label><br>
        <input type="number" name="total_harga" value="<?php echo $data["total_harga"]; ?>" required><br>

        <label>Status Pembayaran:</label><br>
        <select name="status_pembayaran">
            <option value="Belum Lunas" <?php if($data["status_pembayaran"] == 'Belum Lunas') echo 'selected'; ?>>Belum Lunas</option>
            <option value="Lunas" <?php if($data["status_pembayaran"] == 'Lunas') echo 'selected'; ?>>Lunas</option>
        </select><br><br>
        
        <button type="submit" name="update">Simpan Perubahan</button>
        <a href="beranda.php"><button type="button">Batal</button></a>
    </form>
</body>
</html>