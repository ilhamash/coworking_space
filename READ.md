MyWorkspace adalah aplikasi berbasis web sederhana namun fungsional yang dirancang untuk mengelola operasional harian coworking space. Aplikasi ini membantu admin dalam mencatat penyewaan meja atau ruangan, mengatur harga, serta memantau ketersediaan slot secara real-time.

Sistem Autentikasi Aman: Login dan Registrasi menggunakan enkripsi password_hash dan manajemen session.

Manajemen Penyewaan (CRUD): Tambah, lihat, edit, dan hapus data penyewa dalam satu dashboard.

Pengaturan Ruangan Dinamis: Admin dapat mengatur jumlah unit tersedia, kapasitas maksimal, dan harga per jam untuk setiap tipe ruangan (Hot Desk, Meeting Room, dll).

Kalkulasi Harga Otomatis: Integrasi JavaScript untuk menghitung total tagihan secara instan berdasarkan durasi sewa.

Sistem Slot ID: Menghasilkan ID unik secara otomatis (contoh: h1, h2, m1) untuk setiap unit ruangan yang tersedia.

Filter Slot Kosong: Sistem secara cerdas hanya menampilkan ID meja/ruangan yang sedang tidak digunakan (Status: Aktif).

Antarmuka Modern (Dark Mode): Desain UI profesional dengan tema gelap, Sidebar navigasi, dan Form menggunakan sistem Pop-up (Modal).

Alur Penggunan :

Login "Username : admin // Pass admin"

Kelola Ruangan: Masuk ke menu "Kelola Ruangan" untuk mengatur harga per jam dan jumlah unit meja/ruangan yang kamu miliki.

Tambah Sewa: Kembali ke Dashboard, klik "+ Tambah Sewa", pilih ruangan, pilih slot ID yang tersedia, dan durasi. Harga akan muncul otomatis.

Selesaikan Sesi: Jika penyewa sudah pulang, klik "Tandai Selesai" agar slot tersebut dapat digunakan kembali oleh orang lain.

ujk_rent/
├── beranda.php # File utama (Rangka Dashboard)
├── login.php # Halaman masuk
├── register.php # Halaman pendaftaran akun
├── logout.php # Proses keluar sesi
├── koneksi.php # Konfigurasi database
├── style.css # File pusat desain (CSS)
├── sidebar.php # Komponen menu samping
├── menu_beranda.php # Konten Dashboard (Daftar Sewa & Modal)
├── menu_ruangan.php # Konten Pengaturan Ruangan & Harga
└── hapus_sewa.php # Logika penghapusan data

DATA BASE Name [db_rent]
File Mysql ada di dalam

atau jalankan di SQL tabel di bawah :

-- Tabel User
CREATE TABLE users (
id INT(11) AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(50) NOT NULL,
password VARCHAR(255) NOT NULL
);

-- Tabel Pengaturan Ruangan
CREATE TABLE pengaturan_ruangan (
id INT(11) AUTO_INCREMENT PRIMARY KEY,
user_id INT(11) NOT NULL,
nama_ruangan VARCHAR(50) NOT NULL,
kode_prefix VARCHAR(10) NOT NULL,
jumlah_unit INT(11) NOT NULL,
kapasitas_per_unit INT(11) NOT NULL,
harga_per_jam INT(11) NOT NULL DEFAULT 0,
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tabel Penyewaan
CREATE TABLE penyewaan (
id INT(11) AUTO_INCREMENT PRIMARY KEY,
user_id INT(11) NOT NULL,
nama_penyewa VARCHAR(100) NOT NULL,
jenis_ruangan VARCHAR(50) NOT NULL,
slot_id VARCHAR(20) NOT NULL,
tanggal_sewa DATE NOT NULL,
durasi_jam INT(11) NOT NULL,
total_harga INT(11) NOT NULL,
status_pembayaran ENUM('Belum Lunas', 'Lunas') DEFAULT 'Belum Lunas',
status_sewa ENUM('Aktif', 'Selesai') DEFAULT 'Aktif',
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
