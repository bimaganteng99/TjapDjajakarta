<?php
// (Pastikan session_start() sudah ada di index.php)
$allowed_roles = ['operasional'];
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], $allowed_roles)) {
    header('Location: index.php?action=login');
    exit();
}
if (!isset($pesanan) || !is_array($pesanan)) {
    $pesanan = [];
}

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Status Pesanan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/statpesstyle.css">
</head>

<body>
    <?php include './views/layout/navbar.php'; ?>

    <div class="container">

        <h2>Status Pesanan</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>ID Pesanan</th>
                    <th>Menu</th>
                    <th>Jumlah</th>
                    <th>Total Harga</th>
                    <th>Jenis Pesanan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="daftar-pesanan">
                <?php foreach ($pesanan as $p): ?>
                    <tr>
                        <td><?= $p['id_pesanan'] ?></td>
                        <td><?= $p['nama_menu'] ?></td>
                        <td><?= $p['jumlah'] ?></td>
                        <td>Rp<?= number_format($p['total_harga']) ?></td>
                        <td><?= $p['jenis_pesanan'] ?></td>
                        <td id="status-<?= $p['id_pesanan'] ?>"><?= $p['status_pesanan'] ?></td>
                        <td>
                            <select onchange="updateStatus(<?= $p['id_pesanan'] ?>, this.value)">
                                <option value="menunggu" <?= $p['status_pesanan'] == 'menunggu' ? 'selected' : '' ?>>Menunggu</option>
                                <option value="diproses" <?= $p['status_pesanan'] == 'diproses' ? 'selected' : '' ?>>Proses</option>
                                <option value="selesai" <?= $p['status_pesanan'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                                <option value="diambil" <?= $p['status_pesanan'] == 'diambil' ? 'selected' : '' ?>>Diambil</option>
                            </select>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        function updateStatus(id_pesanan, status_pesanan) {
            fetch('index.php?action=update_status_pesanan', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'id_pesanan=' + id_pesanan + '&status_pesanan=' + status_pesanan
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Ini sudah benar
                        document.getElementById('status-' + id_pesanan).innerText = status_pesanan;
                    } else {
                        alert('Gagal update status!');
                    }
                });
        }
    </script>

</body>

</html>