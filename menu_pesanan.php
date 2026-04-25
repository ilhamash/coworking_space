<?php
// 1. DAFTAR MENU (Kamu bisa tambah/ubah harga di sini)
$daftar_snack = [
    ["nama" => "Nasi Goreng Special", "harga" => 30000],
    ["nama" => "Mie Goreng Jawa", "harga" => 25000],
    ["nama" => "French Fries", "harga" => 18000],
    ["nama" => "Cireng Rujak", "harga" => 15000],
    ["nama" => "Kopi Susu Gula Aren", "harga" => 20000],
    ["nama" => "Americano", "harga" => 15000],
    ["nama" => "Es Teh Manis", "harga" => 8000]
];

// --- PROSES TAMBAH PESANAN ---
if (isset($_POST["tambah_pesanan"])) {
    $p_id = $_POST["penyewaan_id"];
    $nama_pesanan = $_POST["nama_pesanan"];
    $harga = (int)$_POST["harga_satuan"];
    $qty  = (int)$_POST["jumlah"];
    $subtotal = $harga * $qty;

    mysqli_query($conn, "INSERT INTO pesanan_tambahan (user_id, penyewaan_id, nama_pesanan, harga_satuan, jumlah, subtotal) 
                         VALUES ('$user_id', '$p_id', '$nama_pesanan', '$harga', '$qty', '$subtotal')");
    header("Location: beranda.php?page=pesanan"); exit;
}

// --- PROSES UPDATE/EDIT PESANAN ---
if (isset($_POST["update_pesanan"])) {
    $id_pesanan = $_POST["id_pesanan"];
    $nama_pesanan = $_POST["nama_pesanan"];
    $harga = (int)$_POST["harga_satuan"];
    $qty  = (int)$_POST["jumlah"];
    $subtotal = $harga * $qty;

    mysqli_query($conn, "UPDATE pesanan_tambahan SET 
                nama_pesanan = '$nama_pesanan', 
                harga_satuan = '$harga', 
                jumlah = '$qty', 
                subtotal = '$subtotal' 
                WHERE id = '$id_pesanan' AND user_id = '$user_id'");
    header("Location: beranda.php?page=pesanan"); exit;
}

// --- PROSES HAPUS PESANAN ---
if (isset($_GET["hapus_pesan"])) {
    $id_h = $_GET["hapus_pesan"];
    mysqli_query($conn, "DELETE FROM pesanan_tambahan WHERE id = '$id_h' AND user_id = '$user_id'");
    header("Location: beranda.php?page=pesanan"); exit;
}

// Ambil data pesanan
$query_tampil = "SELECT p.*, s.nama_penyewa FROM pesanan_tambahan p 
                 JOIN penyewaan s ON p.penyewaan_id = s.id 
                 WHERE p.user_id = '$user_id' ORDER BY p.id DESC";
$list_pesanan = mysqli_query($conn, $query_tampil);
?>

<div class="header-container">
    <h1>Kasir Snack & Minuman</h1>
    <button class="btn btn-auto" onclick="document.getElementById('modalTambahPesan').style.display='block'">+ Pesanan Baru</button>
</div>
<hr>

<table>
    <tr>
        <th>Penyewa</th><th>Menu</th><th>Harga</th><th>Qty</th><th>Total</th><th>Aksi</th>
    </tr>
    <?php while ($lp = mysqli_fetch_assoc($list_pesanan)) : ?>
    <tr>
        <td><strong><?php echo $lp['nama_penyewa']; ?></strong></td>
        <td><?php echo $lp['nama_pesanan']; ?></td>
        <td>Rp <?php echo number_format($lp['harga_satuan'], 0, ',', '.'); ?></td>
        <td><?php echo $lp['jumlah']; ?></td>
        <td><strong>Rp <?php echo number_format($lp['subtotal'], 0, ',', '.'); ?></strong></td>
        <td>
            <button class="btn btn-edit" onclick="document.getElementById('modalEditPesan<?php echo $lp['id']; ?>').style.display='block'">Edit</button>
            <a href="beranda.php?page=pesanan&hapus_pesan=<?php echo $lp['id']; ?>" class="btn-hapus" onclick="return confirm('Hapus pesanan ini?')">Hapus</a>
        </td>
    </tr>

    <div id="modalEditPesan<?php echo $lp['id']; ?>" class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('modalEditPesan<?php echo $lp['id']; ?>').style.display='none'">&times;</span>
            <h2>Edit Pesanan</h2>
            <form action="" method="POST">
                <input type="hidden" name="id_pesanan" value="<?php echo $lp['id']; ?>">
                
                <label>Menu:</label>
                <select name="nama_pesanan" id="edit_menu_<?php echo $lp['id']; ?>" onchange="hitungOtomatis('edit', <?php echo $lp['id']; ?>)" required>
                    <?php foreach($daftar_snack as $snack) : ?>
                        <option value="<?php echo $snack['nama']; ?>" 
                                data-harga="<?php echo $snack['harga']; ?>"
                                <?php if($lp['nama_pesanan'] == $snack['nama']) echo 'selected'; ?>>
                            <?php echo $snack['nama']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <input type="hidden" name="harga_satuan" id="edit_harga_hidden_<?php echo $lp['id']; ?>" value="<?php echo $lp['harga_satuan']; ?>">

                <label>Jumlah (Qty):</label>
                <input type="number" name="jumlah" id="edit_qty_<?php echo $lp['id']; ?>" value="<?php echo $lp['jumlah']; ?>" min="1" oninput="hitungOtomatis('edit', <?php echo $lp['id']; ?>)" required>

                <label>Total Baru (Otomatis):</label>
                <input type="number" name="subtotal" id="edit_total_<?php echo $lp['id']; ?>" value="<?php echo $lp['subtotal']; ?>" readonly>

                <button type="submit" name="update_pesanan" class="btn btn-full" style="background-color: #28a745;">Simpan Perubahan</button>
            </form>
        </div>
    </div>
    <?php endwhile; ?>
</table>

<div id="modalTambahPesan" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('modalTambahPesan').style.display='none'">&times;</span>
        <h2>Input Pesanan</h2>
        <form action="" method="POST">
            <label>Penyewa Aktif:</label>
            <select name="penyewaan_id" required>
                <?php 
                $q_sewa = mysqli_query($conn, "SELECT id, nama_penyewa FROM penyewaan WHERE user_id = '$user_id' AND status_sewa = 'Aktif'");
                while($qs = mysqli_fetch_assoc($q_sewa)) {
                    echo "<option value='{$qs['id']}'>{$qs['nama_penyewa']}</option>";
                }
                ?>
            </select>

            <label>Pilih Menu:</label>
            <select name="nama_pesanan" id="tambah_menu" onchange="hitungOtomatis('tambah')" required>
                <option value="">-- Pilih Makanan/Minuman --</option>
                <?php foreach($daftar_snack as $snack) : ?>
                    <option value="<?php echo $snack['nama']; ?>" data-harga="<?php echo $snack['harga']; ?>">
                        <?php echo $snack['nama']; ?> (Rp <?php echo number_format($snack['harga'], 0, ',', '.'); ?>)
                    </option>
                <?php endforeach; ?>
            </select>

            <input type="hidden" name="harga_satuan" id="tambah_harga_hidden">

            <label>Jumlah (Qty):</label>
            <input type="number" name="jumlah" id="tambah_qty" value="1" min="1" oninput="hitungOtomatis('tambah')" required>

            <label>Total Bayar (Otomatis):</label>
            <input type="number" name="subtotal" id="tambah_total" readonly>

            <button type="submit" name="tambah_pesanan" class="btn btn-full">Simpan Pesanan</button>
        </form>
    </div>
</div>

<script>
// Fungsi Pintar untuk menghitung harga (Bekerja untuk Tambah maupun Edit)
function hitungOtomatis(tipe, id = '') {
    let menuId = (tipe === 'tambah') ? 'tambah_menu' : 'edit_menu_' + id;
    let qtyId  = (tipe === 'tambah') ? 'tambah_qty' : 'edit_qty_' + id;
    let totalId = (tipe === 'tambah') ? 'tambah_total' : 'edit_total_' + id;
    let hiddenId = (tipe === 'tambah') ? 'tambah_harga_hidden' : 'edit_harga_hidden_' + id;

    const menu = document.getElementById(menuId);
    const selectedOption = menu.options[menu.selectedIndex];
    
    // Ambil harga dari atribut data-harga
    const harga = selectedOption.getAttribute('data-harga') || 0;
    const qty = document.getElementById(qtyId).value || 0;
    
    const total = harga * qty;

    // Update tampilan
    document.getElementById(hiddenId).value = harga;
    document.getElementById(totalId).value = total;
}
</script>