<?php
// (Pastikan session_start() sudah ada di index.php)
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'pengadaan') {
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

    <h2>Manajemen Stok Menu</h2>
    <?php if (isset($_GET['status']) && $_GET['status'] == 'update_success'): ?>
        <p style="color: green; font-weight: bold;">Stok berhasil diperbarui!</p>
    <?php endif; ?>

    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>ID Menu</th>
                <th>Nama Menu</th>
                <th>Stock Saat Ini</th>
                <th>Status</th>
                <th>Update Stock</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($stockData as $item): ?>
                <tr>
                    <td><?= $item['id_menu'] ?></td>
                    <td><?= htmlspecialchars($item['nama_menu']) ?></td>
                    <td><?= $item['jumlah_stock'] ?></td>
                    <td><?= htmlspecialchars($item['status']) ?></td>
                    <td>
                        <form action="index.php?action=update_stock" method="POST" style="display: inline;">
                            <input type="hidden" name="id_menu" value="<?= $item['id_menu'] ?>">
                            <input type="number" name="stock" value="<?= $item['jumlah_stock'] ?>" min="0" required style="width: 60px;">
                            <button type="submit">Simpan</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>