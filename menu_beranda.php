<?php
// --- PROSES MENANDAI SELESAI ---
if (isset($_GET["selesai"])) {
    $id_selesai = $_GET["selesai"];
    mysqli_query($conn, "UPDATE penyewaan SET status_sewa = 'Selesai' WHERE id = '$id_selesai' AND user_id = '$user_id'");
    header("Location: beranda.php?page=dashboard"); exit;
}

// --- PROSES TAMBAH SEWA ---
if (isset($_POST["tambah_sewa"])) {
    $nama_penyewa = mysqli_real_escape_string($conn, $_POST["nama_penyewa"]);
    $jenis_ruangan = $_POST["jenis_ruangan"];
    $slot_id = $_POST["slot_id"];
    $tanggal_sewa = $_POST["tanggal_sewa"];
    $durasi_jam = $_POST["durasi_jam"];
    $total_harga = $_POST["total_harga"];
    $status_pembayaran = $_POST["status_pembayaran"];
    
    mysqli_query($conn, "INSERT INTO penyewaan (user_id, nama_penyewa, jenis_ruangan, slot_id, tanggal_sewa, durasi_jam, total_harga, status_pembayaran) 
                         VALUES ('$user_id', '$nama_penyewa', '$jenis_ruangan', '$slot_id', '$tanggal_sewa', '$durasi_jam', '$total_harga', '$status_pembayaran')");
    header("Location: beranda.php?page=dashboard"); exit;
}

// --- PROSES EDIT SEWA ---
if (isset($_POST["update_sewa"])) {
    $id_sewa = $_POST["id_sewa"];
    $nama_penyewa = mysqli_real_escape_string($conn, $_POST["nama_penyewa"]);
    $jenis_ruangan = $_POST["jenis_ruangan"];
    $slot_id = $_POST["slot_id"];
    $tanggal_sewa = $_POST["tanggal_sewa"];
    $durasi_jam = $_POST["durasi_jam"];
    $total_harga = $_POST["total_harga"];
    $status_pembayaran = $_POST["status_pembayaran"];

    mysqli_query($conn, "UPDATE penyewaan SET 
                nama_penyewa = '$nama_penyewa', jenis_ruangan = '$jenis_ruangan', slot_id = '$slot_id', 
                tanggal_sewa = '$tanggal_sewa', durasi_jam = '$durasi_jam', total_harga = '$total_harga', status_pembayaran = '$status_pembayaran'
              WHERE id = '$id_sewa' AND user_id = '$user_id'");
    header("Location: beranda.php?page=dashboard"); exit;
}

// --- DATA UNTUK JS ---
$ruangan_js = [];
$q_ruang = mysqli_query($conn, "SELECT * FROM pengaturan_ruangan WHERE user_id = '$user_id'");
while($r = mysqli_fetch_assoc($q_ruang)) {
    $q_aktif = mysqli_query($conn, "SELECT slot_id FROM penyewaan WHERE jenis_ruangan = '{$r['nama_ruangan']}' AND status_sewa = 'Aktif' AND user_id = '$user_id'");
    $terpakai = [];
    while($aktif = mysqli_fetch_assoc($q_aktif)) { $terpakai[] = $aktif['slot_id']; }
    $ruangan_js[$r['nama_ruangan']] = [ 'harga' => $r['harga_per_jam'], 'prefix' => $r['kode_prefix'], 'jumlah' => $r['jumlah_unit'], 'terpakai' => $terpakai ];
}
$data_penyewaan = mysqli_query($conn, "SELECT * FROM penyewaan WHERE user_id = '$user_id' ORDER BY id DESC");
?>

<div class="header-container">
    <h1>Daftar Penyewaan</h1>
    <button class="btn btn-auto" onclick="document.getElementById('modalTambah').style.display='block'">+ Tambah Sewa</button>
</div>
<hr>

<table>
    <tr>
        <th>Tgl</th><th>Penyewa</th><th>Ruang (Slot)</th><th>Durasi</th><th>Tagihan</th><th>Pembayaran</th><th>Sesi</th><th>Aksi</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($data_penyewaan)) : ?>
    <tr class="<?php if($row['status_sewa'] == 'Selesai') echo 'row-selesai'; ?>">
        <td><?php echo $row["tanggal_sewa"]; ?></td>
        <td><?php echo $row["nama_penyewa"]; ?></td>
        <td><strong><?php echo $row["jenis_ruangan"]; ?></strong> <br><span class="slot-id-label">[ID: <?php echo $row["slot_id"]; ?>]</span></td>
        <td><?php echo $row["durasi_jam"]; ?> Jam</td>
        <td>Rp <?php echo number_format($row["total_harga"], 0, ',', '.'); ?></td>
        <td>
            <?php echo ($row["status_pembayaran"] == 'Belum Lunas') ? "<span style='color:#ff6b6b; font-weight:bold;'>Belum Lunas</span>" : "<span style='color:#4cd137; font-weight:bold;'>Lunas</span>"; ?>
        </td>
        <td>
            <?php if($row["status_sewa"] == 'Aktif') : ?>
                <span class="status-badge status-aktif">Aktif</span>
            <?php else : ?>
                <span class="status-badge status-selesai">Selesai</span>
            <?php endif; ?>
        </td>
        <td>
            <?php if($row["status_sewa"] == 'Aktif') : ?>
                <button class="btn btn-edit" onclick="bukaModalEdit('<?php echo $row['id']; ?>')">Edit</button>
                <a href="beranda.php?page=dashboard&selesai=<?php echo $row['id']; ?>" class="btn-selesai" onclick="return confirm('Sewa selesai? Slot akan kosong kembali.');">Selesai</a>
            <?php endif; ?>
            <a href="delete.php?id=<?php echo $row["id"]; ?>" class="btn-hapus" onclick="return confirm('Hapus permanen?');">Hapus</a>
        </td>
    </tr>

    <div id="modalEdit<?php echo $row['id']; ?>" class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('modalEdit<?php echo $row['id']; ?>').style.display='none'">&times;</span>
            <h2>Edit Sewa</h2>
            <form action="" method="POST">
                <input type="hidden" name="id_sewa" value="<?php echo $row['id']; ?>">
                <label>Nama Penyewa:</label> <input type="text" name="nama_penyewa" value="<?php echo $row['nama_penyewa']; ?>" required>
                <label>Pilih Ruangan:</label>
                <select name="jenis_ruangan" id="edit_ruang_<?php echo $row['id']; ?>" onchange="updateSlot('edit', <?php echo $row['id']; ?>)" required>
                    <?php 
                    $opsi_ruang = mysqli_query($conn, "SELECT * FROM pengaturan_ruangan WHERE user_id = '$user_id'");
                    while($ruang = mysqli_fetch_assoc($opsi_ruang)) {
                        $sel = ($row['jenis_ruangan'] == $ruang['nama_ruangan']) ? 'selected' : '';
                        echo "<option value='{$ruang['nama_ruangan']}' $sel>{$ruang['nama_ruangan']} (Rp " . number_format($ruang['harga_per_jam'],0,',','.') . "/Jam)</option>";
                    }
                    ?>
                </select>
                <label>Slot ID:</label>
                <select name="slot_id" id="edit_slot_<?php echo $row['id']; ?>" data-current="<?php echo $row['slot_id']; ?>" required></select>
                <label>Tanggal:</label> <input type="date" name="tanggal_sewa" value="<?php echo $row['tanggal_sewa']; ?>" required>
                <label>Durasi (Jam):</label> <input type="number" name="durasi_jam" id="edit_durasi_<?php echo $row['id']; ?>" value="<?php echo $row['durasi_jam']; ?>" min="1" oninput="updateHarga('edit', <?php echo $row['id']; ?>)" required>
                <label>Total Tagihan (Rp):</label> <input type="number" name="total_harga" id="edit_harga_<?php echo $row['id']; ?>" value="<?php echo $row['total_harga']; ?>" readonly>
                <label>Pembayaran:</label>
                <select name="status_pembayaran">
                    <option value="Belum Lunas" <?php if($row["status_pembayaran"] == 'Belum Lunas') echo 'selected'; ?>>Belum Lunas</option>
                    <option value="Lunas" <?php if($row["status_pembayaran"] == 'Lunas') echo 'selected'; ?>>Lunas</option>
                </select>
                <button type="submit" name="update_sewa" class="btn btn-full" style="background-color: #28a745;">Simpan Perubahan</button>
            </form>
        </div>
    </div>
    <?php endwhile; ?>
</table>

<div id="modalTambah" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('modalTambah').style.display='none'">&times;</span>
        <h2>Sewa Baru</h2>
        <form action="" method="POST">
            <label>Nama Penyewa:</label> <input type="text" name="nama_penyewa" required>
            <label>Pilih Ruangan:</label>
            <select name="jenis_ruangan" id="tambah_ruang" onchange="updateSlot('tambah')" required>
                <option value="">-- Pilih Ruangan --</option>
                <?php 
                $opsi_ruang = mysqli_query($conn, "SELECT * FROM pengaturan_ruangan WHERE user_id = '$user_id'");
                while($ruang = mysqli_fetch_assoc($opsi_ruang)) {
                    echo "<option value='{$ruang['nama_ruangan']}'>{$ruang['nama_ruangan']} (Rp " . number_format($ruang['harga_per_jam'],0,',','.') . "/Jam)</option>";
                }
                ?>
            </select>
            <label>Slot Kosong:</label> <select name="slot_id" id="tambah_slot" required><option value="">-- Pilih Ruangan Dulu --</option></select>
            <label>Tanggal:</label> <input type="date" name="tanggal_sewa" value="<?php echo date('Y-m-d'); ?>" required>
            <label>Durasi (Jam):</label> <input type="number" name="durasi_jam" id="tambah_durasi" min="1" oninput="updateHarga('tambah')" required>
            <label>Total Tagihan (Rp):</label> <input type="number" name="total_harga" id="tambah_harga" readonly>
            <label>Pembayaran:</label>
            <select name="status_pembayaran"><option value="Belum Lunas">Belum Lunas</option><option value="Lunas">Lunas</option></select>
            <button type="submit" name="tambah_sewa" class="btn btn-full">Simpan Transaksi</button>
        </form>
    </div>
</div>

<script>
const dataRuangan = <?php echo json_encode($ruangan_js); ?>;

function updateSlot(tipe, id = '') {
    let idRuang = (tipe === 'tambah') ? 'tambah_ruang' : 'edit_ruang_' + id;
    let idSlot  = (tipe === 'tambah') ? 'tambah_slot' : 'edit_slot_' + id;
    let namaRuang = document.getElementById(idRuang).value;
    let slotSelect = document.getElementById(idSlot);
    let currentSlot = slotSelect.getAttribute('data-current') || '';

    slotSelect.innerHTML = '<option value="">-- Pilih Slot Kosong --</option>';
    if(namaRuang && dataRuangan[namaRuang]) {
        let r = dataRuangan[namaRuang];
        for(let i = 1; i <= r.jumlah; i++) {
            let s = r.prefix + i;
            if(!r.terpakai.includes(s) || s === currentSlot) {
                let sel = (s === currentSlot) ? 'selected' : '';
                slotSelect.innerHTML += `<option value="${s}" ${sel}>${s}</option>`;
            }
        }
    }
    updateHarga(tipe, id);
}

function updateHarga(tipe, id = '') {
    let idRuang = (tipe === 'tambah') ? 'tambah_ruang' : 'edit_ruang_' + id;
    let idDurasi = (tipe === 'tambah') ? 'tambah_durasi' : 'edit_durasi_' + id;
    let idHarga = (tipe === 'tambah') ? 'tambah_harga' : 'edit_harga_' + id;
    let namaRuang = document.getElementById(idRuang).value;
    let durasi = document.getElementById(idDurasi).value;
    if(namaRuang && dataRuangan[namaRuang] && durasi > 0) {
        document.getElementById(idHarga).value = dataRuangan[namaRuang].harga * durasi;
    } else { document.getElementById(idHarga).value = ''; }
}

function bukaModalEdit(id) {
    document.getElementById('modalEdit' + id).style.display = 'block';
    updateSlot('edit', id);
}
</script>