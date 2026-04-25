<div class="sidebar">
    <h2>MyWorkspace</h2>
    
    <a href="beranda.php?page=dashboard" class="menu-link <?php if($page == 'dashboard') echo 'active'; ?>">📊 Dashboard Sewa</a>
    <a href="beranda.php?page=ruangan" class="menu-link <?php if($page == 'ruangan') echo 'active'; ?>">🚪 Kelola Ruangan</a>
    <a href="beranda.php?page=pesanan" class="menu-link <?php if($page == 'pesanan') echo 'active'; ?>">🍲 Pesan Makanan</a>
    
    <div class="logout-box">
        <p style="text-align: center; margin-bottom:10px; color:#aaa; font-size: 14px;">Halo, <?php echo $_SESSION["username"]; ?></p>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</div>