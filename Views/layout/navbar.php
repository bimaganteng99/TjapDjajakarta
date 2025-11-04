<nav>
    <ul>
        <?php if ($_SESSION['user_role'] == 'manajer'): ?>
            <li><a href="index.php?action=manajemen_menu">Manajemen Menu</a></li>
            <li><a href="index.php?action=manajemen_akun">Manajemen Akun</a></li>
            <!-- <li><a href="index.php?action=edit_resep">Manajemen Resep</a></li> -->

        <?php elseif ($_SESSION['user_role'] == 'admin'): ?>
            <li><a href="index.php?action=dashboardAdmin">Dashboard Admin</a></li>
            <li><a href="index.php?action=data_user">Data User</a></li>

        <?php elseif ($_SESSION['user_role'] == 'kasir'): ?>
            <li><a href="index.php?action=pos_kasir">POS</a></li>
            <li><a href="index.php?action=pembayaran">Pembayaran</a></li>
            <li><a href="index.php?action=verifikasi_pickup">Verifikasi PickUp</a></li>
            <!-- <li><a href="index.php?action=status_pesanan">Status Pesanan</a></li> -->

        <?php elseif ($_SESSION['user_role'] == 'pelanggan'): ?>
            <li><a href="index.php?action=pos_kasir">POS</a></li>
            <li><a href="index.php?action=pembayaran">Pembayaran</a></li>

        <?php elseif ($_SESSION['user_role'] == 'operasional'): ?>
            <!-- <li><a href="index.php?action=pos_kasir">POS</a></li> -->
            <li><a href="index.php?action=status_pesanan">Status Pesanan</a></li>

        <?php elseif ($_SESSION['user_role'] == 'pengadaan'): ?>
            <!-- <li><a href="index.php?action=pos_kasir">POS</a></li> -->
            <li><a href="index.php?action=stock">Kelola Stock</a></li>

        <?php endif; ?>
        <li><a href="index.php?action=logout">Logout</a></li>
    </ul>
</nav>