<?php
// (Pastikan session_start() sudah ada di index.php)
$allowed_roles = ['kasir', 'pelanggan'];
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], $allowed_roles)) {
    header('Location: index.php?action=login');
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Staff</title>
</head>

<body>
    <?php include './views/layout/navbar.php'; ?>

    <?php if ($_SESSION['user_role'] == 'pelanggan'): ?>
        <h1>Halaman Pembayaran</h1>
        <h2>Daftar Pesanan Menunggu Pembayaran</h2>
        <?php if (!empty($pesanan)): ?>
            <table border="1" cellpadding="6" style="margin-top:20px;">
                <tr>
                    <th>Kode Pesanan</th>
                    <th>Menu</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
                <?php foreach ($pesanan as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['kode_pesanan']) ?></td>
                        <td><?= htmlspecialchars($p['nama_menu']) ?></td>
                        <td><?= (int)$p['jumlah'] ?></td>
                        <td>Rp<?= number_format((float)$p['total_harga']) ?></td>
                        <td><?= htmlspecialchars($p['status_pesanan']) ?></td>
                        <td>
                            <form action="index.php?action=proses_pembayaran" method="post" style="display:inline;">
                                <input type="hidden" name="id_pesanan" value="<?= (int)$p['id_pesanan'] ?>">
                                <select name="metode_pembayaran" required>
                                    <option value="tunai">Tunai</option>
                                    <option value="transfer">Transfer</option>
                                </select>
                                <button type="submit">Bayar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>Tidak ada pesanan yang menunggu pembayaran.</p>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($_SESSION['user_role'] == 'kasir'): ?>
        <h1>Halaman Pembayaran</h1>
        <h2>Daftar Semua Pesanan Menunggu Pembayaran</h2>
        <?php
        // Ambil semua pesanan menunggu pembayaran (dari semua pelanggan)
        // Pastikan variabel $pesanan_kasir dikirim dari controller
        if (!empty($pesanan_kasir)): ?>
            <table border="1" cellpadding="6" style="margin-top:20px;">
                <tr>
                    <th>Kode Pesanan</th>
                    <th>Pelanggan</th>
                    <th>Menu</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
                <?php foreach ($pesanan_kasir as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['kode_pesanan']) ?></td>
                        <td><?= htmlspecialchars($p['username'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($p['nama_menu']) ?></td>
                        <td><?= (int)$p['jumlah'] ?></td>
                        <td>Rp<?= number_format((float)$p['total_harga']) ?></td>
                        <td><?= htmlspecialchars($p['status_pesanan']) ?></td>
                        <td>
                            <form action="index.php?action=proses_pembayaran" method="post" style="display:inline;">
                                <input type="hidden" name="id_pesanan" value="<?= (int)$p['id_pesanan'] ?>">
                                <select name="metode_pembayaran" required>
                                    <option value="tunai">Tunai</option>
                                    <option value="transfer">Transfer</option>
                                </select>
                                <button type="submit">Bayar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>Tidak ada pesanan yang menunggu pembayaran.</p>
        <?php endif; ?>
    <?php endif; ?>

</body>

</html>