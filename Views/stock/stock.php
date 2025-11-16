<?php
// (File: views/stock/stock.php)
$allowed_roles = ['pengadaan', 'manajer'];
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], $allowed_roles)) {
    header('Location: index.php?action=login');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Manajemen Stok Bahan Baku</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/stockstyle.css">
</head>

<body>
    <?php include './views/layout/navbar.php'; ?>

    <div class="container">
        <h2>Manajemen Stok Bahan Baku</h2>

        <div class="form-tambah">
            <h3>Tambah Bahan Baku Baru</h3>
            <form action="index.php?action=handle_stock" method="POST">
                <input type="hidden" name="stock_action" value="add">

                <label for="nama_bahan">Nama Bahan:</label>
                <input type="text" id="nama_bahan" name="nama_bahan" required>

                <label for="stok_awal">Stok Awal:</label>
                <input type="number" id="stok_awal" name="stok_awal" step="0.01" value="0.00" required>

                <label for="satuan">Satuan (gr, ml, pcs):</label>
                <input type="text" id="satuan" name="satuan" required>

                <button type="submit">Tambah Bahan</button>
            </form>
        </div>

        <div class="table-stok">
            <h3>Daftar Stok Saat Ini</h3>
            <table cellpadding="5">
                <thead>
                    <tr>
                        <th>ID Bahan</th>
                        <th>Nama Bahan</th>
                        <th>Stok Saat Ini</th>
                        <th>Satuan</th>
                        <th>Update Stok</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bahanBaku as $bahan): ?>
                        <tr>
                            <td><?= $bahan['id_bahan'] ?></td>
                            <td><?= htmlspecialchars($bahan['nama_bahan']) ?></td>
                            <td><?= $bahan['jumlah_stok'] ?></td>
                            <td><?= htmlspecialchars($bahan['satuan']) ?></td>
                            <td>
                                <form action="index.php?action=handle_stock" method="POST" style="display: inline;">
                                    <input type="hidden" name="stock_action" value="update">
                                    <input type="hidden" name="id_bahan" value="<?= $bahan['id_bahan'] ?>">
                                    <input type="number" name="stok" value="<?= $bahan['jumlah_stok'] ?>" step="0.01" min="0" required style="width: 80px;">
                                    <button type="submit">Simpan</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>