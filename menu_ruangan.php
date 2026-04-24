<?php
// --- PROSES EDIT RUANGAN ---
if (isset($_POST["update_ruangan"])) {
    $id_ruang = $_POST["id_ruang"];
    $jumlah_unit = (int)$_POST["jumlah_unit"];
    $kapasitas = (int)$_POST["kapasitas_per_unit"];
    $harga = (int)$_POST["harga_per_jam"]; // Menangkap input harga baru

    mysqli_query($conn, "UPDATE pengaturan_ruangan SET jumlah_unit = '$jumlah_unit', kapasitas_per_unit = '$kapasitas', harga_per_jam = '$harga' WHERE id = '$id_ruang' AND user_id = '$user_id'");
    header("Location: beranda.php?page=ruangan"); exit;
}

$data_ruangan = mysqli_query($conn, "SELECT * FROM pengaturan_ruangan WHERE user_id = '$user_id'");
?>

<div class="header-container">
    <h1>Kelola Ketersediaan Ruangan & Harga</h1>
</div>
<hr>
<p style="color:#b3b3b3; margin-bottom: 20px;">Sesuaikan jumlah unit, kapasitas, dan harga per jam untuk setiap tipe ruangan.</p>

<table>
    <tr>
        <th>Tipe Ruangan</th>
        <th>Harga / Jam</th>
        <th>Jumlah Tersedia</th>
        <th>Maks. Orang</th>
        <th>Aksi</th>
    </tr>
    <?php while ($ruang = mysqli_fetch_assoc($data_ruangan)) : ?>
    <tr>
        <td><strong><?php echo $ruang['nama_ruangan']; ?></strong></td>
        <td><span style="color:#4cd137; font-weight:bold;">Rp <?php echo number_format($ruang['harga_per_jam'], 0, ',', '.'); ?></span></td>
        <td><?php echo $ruang['jumlah_unit']; ?> Unit</td>
        <td><?php echo $ruang['kapasitas_per_unit']; ?> Orang</td>
        <td>
            <button class="btn btn-edit" onclick="document.getElementById('modalEditRuang<?php echo $ruang['id']; ?>').style.display='block'">Atur</button>
        </td>
    </tr>

    <div id="modalEditRuang<?php echo $ruang['id']; ?>" class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('modalEditRuang<?php echo $ruang['id']; ?>').style.display='none'">&times;</span>
            <h2>Atur: <?php echo $ruang['nama_ruangan']; ?></h2>
            <form action="" method="POST">
                <input type="hidden" name="id_ruang" value="<?php echo $ruang['id']; ?>">
                
                <label>Harga per Jam (Rp):</label>
                <input type="number" name="harga_per_jam" value="<?php echo $ruang['harga_per_jam']; ?>" required min="0">

                <label>Total Unit Tersedia:</label>
                <input type="number" name="jumlah_unit" value="<?php echo $ruang['jumlah_unit']; ?>" required min="1">
                
                <label>Maksimal Kapasitas (Orang per unit):</label>
                <input type="number" name="kapasitas_per_unit" value="<?php echo $ruang['kapasitas_per_unit']; ?>" required min="1">
                
                <button type="submit" name="update_ruangan" class="btn" style="width:100%; margin-top:10px;">Simpan Perubahan</button>
            </form>
        </div>
    </div>
    <?php endwhile; ?>
</table>