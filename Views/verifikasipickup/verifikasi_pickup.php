<?php
// (File: views/verifikasipickup/verifikasi_pickup.php)
// (Security check sudah ada di controller, tapi boleh ditambah di sini)
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'kasir') {
    header('Location: index.php?action=login');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Verifikasi Pick Up</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
        }

        .search-box,
        .result-box {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .search-box input {
            width: 80%;
            padding: 10px;
        }

        .search-box button {
            padding: 10px 15px;
        }

        .error {
            color: red;
            font-weight: bold;
        }

        .success {
            color: green;
            font-weight: bold;
        }

        .pesanan-detail {
            line-height: 1.6;
        }

        .btn-konfirmasi {
            background-color: #28a745;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <?php include './views/layout/navbar.php'; ?>

    <div class="container">
        <h2>Verifikasi Pesanan Pick Up</h2>

        <div class="search-box">
            <form action="index.php?action=verifikasi_pickup" method="POST">
                <label for="kode_pesanan">Masukkan Kode Pesanan:</label><br><br>
                <input type="text" id="kode_pesanan" name="kode_pesanan" required>
                <button type="submit">Cari Pesanan</button>
            </form>
        </div>

        <?php if (isset($error)): ?>
            <div class="result-box">
                <p class="error"><?= htmlspecialchars($error); ?></p>
            </div>
        <?php endif; ?>

        <?php if (isset($success) && $success == 'success'): ?>
            <div class="result-box">
                <p class="success">Berhasil! Pesanan telah ditandai "diambil".</p>
            </div>
        <?php endif; ?>


        <?php if (isset($pesanan) && $pesanan['status_pesanan'] == 'selesai'): ?>
            <div class="result-box">
                <h3>Pesanan Ditemukan (Status: SELESAI)</h3>
                <div class="pesanan-detail">
                    <strong>Kode:</strong> <?= htmlspecialchars($pesanan['kode_pesanan']) ?><br>
                    <strong>Menu:</strong> <?= htmlspecialchars($pesanan['nama_menu']) ?><br>
                    <strong>Jumlah:</strong> <?= htmlspecialchars($pesanan['jumlah']) ?><br>
                    <strong>Catatan:</strong> <?= htmlspecialchars($pesanan['catatan'] ?: '-') ?><br>
                </div>
                <hr>
                <p>Konfirmasi bahwa pesanan ini sudah siap dan akan diambil oleh driver?</p>

                <form action="index.php?action=konfirmasi_pickup" method="POST">
                    <input type="hidden" name="id_pesanan" value="<?= $pesanan['id_pesanan'] ?>">
                    <button type="submit" class="btn-konfirmasi">Konfirmasi Pick Up</button>
                </form>
            </div>
        <?php endif; ?>
        <?php if (isset($pesanan) && $pesanan['status_pesanan'] == 'diambil'): ?>
            <div class="result-box" style="background-color: #f8f9fa;">
                <h3>Info: Pesanan sudah diambil oleh driver</h3>
                <div class="pesanan-detail">
                    <strong>Kode:</strong> <?= htmlspecialchars($pesanan['kode_pesanan']) ?><br>
                    <strong>Menu:</strong> <?= htmlspecialchars($pesanan['nama_menu']) ?><br>
                    <strong>Jumlah:</strong> <?= htmlspecialchars($pesanan['jumlah']) ?><br>
                    <strong>Catatan:</strong> <?= htmlspecialchars($pesanan['catatan'] ?: '-') ?><br>
                    <strong>Status:</strong> <span style="font-weight: bold; color: green;">DIAMBIL</span>
                </div>
                <hr>
                <p>Pesanan ini sudah diambil dan tidak perlu konfirmasi lagi.</p>
            </div>
        <?php endif; ?>
    </div>

    </div>
</body>

</html>