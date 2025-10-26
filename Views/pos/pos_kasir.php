<?php
$allowed_roles = ['kasir', 'operasional'];
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], $allowed_roles)) {
    header('Location: index.php?action=login');
    exit();
}
// if (!isset($_SESSION['user_id'])) {
//     die('Session hilang, dicek dulu');
// }
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>POS Kasir</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        h2 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        .tersedia {
            background-color: #d4edda;
        }

        .habis {
            background-color: #f8d7da;
        }
    </style>
</head>

<body>
    <?php include './views/layout/navbar.php'; ?>

    <h2>Daftar Menu POS Kasir</h2>

    <h3>Menu Tersedia</h3>
    <table>
        <tr>
            <th>Nama Menu</th>
            <th>Harga</th>
            <th>Status</th>
        </tr>
        <?php foreach ($menus as $menu): ?>
            <?php if ($menu['status'] === 'tersedia'): ?>
                <tr class="tersedia">
                    <td><?= htmlspecialchars($menu['nama']) ?></td>
                    <td><?= htmlspecialchars($menu['harga']) ?></td>
                    <td><?= htmlspecialchars($menu['status']) ?></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </table>

    <h3>Menu Habis</h3>
    <table>
        <tr>
            <th>Nama Menu</th>
            <th>Harga</th>
            <th>Status</th>
        </tr>
        <?php foreach ($menus as $menu): ?>
            <?php if ($menu['status'] === 'habis'): ?>
                <tr class="habis">
                    <td><?= htmlspecialchars($menu['nama']) ?></td>
                    <td><?= htmlspecialchars($menu['harga']) ?></td>
                    <td><?= htmlspecialchars($menu['status']) ?></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </table>

    <h2>Halaman POS Kasir</h2>

    <form action="index.php?action=tambah_pesanan" method="POST">
        <label for="menu">Menu:</label>
        <select name="id_menu" id="menu" required>
            <?php foreach ($menus as $menu): ?>
                <option value="<?= $menu['id_menu'] ?>" data-harga="<?= $menu['harga'] ?>">
                    <?= $menu['nama'] ?> - Rp<?= number_format($menu['harga']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="jumlah">Jumlah:</label>
        <input type="number" name="jumlah" id="jumlah" value="1" min="1" required>

        <label for="jenis_pesanan">Jenis Pesanan:</label>
        <select name="jenis_pesanan" id="jenis_pesanan" required>
            <option value="dine in">Dine In</option>
            <option value="delivery">Delivery</option>
        </select>

        <label for="catatan">Catatan:</label>
        <input type="text" name="catatan" id="catatan">

        <button type="submit">Tambah Pesanan</button>
    </form>

    <hr>

    <h3>Daftar Pesanan</h3>
    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>Menu</th>
            <th>Jumlah</th>
            <th>Total</th>
            <th>Jenis</th>
            <th>Status</th>
            <th>Catatan</th>
        </tr>
        <?php foreach ($daftar_pesanan as $pesanan): ?>
            <tr>
                <td><?= $pesanan['id_pesanan'] ?></td>
                <td><?= $pesanan['nama_menu'] ?></td>
                <td><?= $pesanan['jumlah'] ?></td>
                <td><?= $pesanan['total_harga'] ?></td>
                <td><?= $pesanan['jenis_pesanan'] ?></td>
                <td id="status-kasir-<?= $pesanan['id_pesanan'] ?>"><?= $pesanan['status_pesanan'] ?></td>
                <td><?= $pesanan['catatan'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <script>
        // Jalankan fungsi fetchUpdates() setiap 5 detik (5000 milidetik)
        setInterval(fetchUpdates, 5000);

        // Fungsi untuk memanggil API
        function fetchUpdates() {

            // =========================================================
            // UBAH ACTION DI SINI
            // =========================================================
            fetch('index.php?action=get_pesanan_updates') // <-- Diubah ke action baru
                .then(response => response.json())
                .then(dataPesananBaru => {
                    // Panggil fungsi untuk membandingkan dan update tabel
                    perbaruiTabel(dataPesananBaru);
                })
                .catch(error => {
                    console.error('Error polling:', error);
                });
        }

        // Fungsi untuk membandingkan data baru dengan tabel HTML
        function perbaruiTabel(dataPesananBaru) {

            dataPesananBaru.forEach(pesanan => {
                let id = pesanan.id_pesanan;
                let statusBaru = pesanan.status_pesanan;

                // PENTING: Sesuaikan ID <td> status di tabel HTML-mu
                let tdStatus = document.getElementById('status-kasir-' + id);

                if (tdStatus) {
                    let statusBaruDiTabel = tdStatus.innerText;

                    // Cek apakah statusnya berbeda?
                    if (statusBaruDiTabel !== statusBaru) {

                        // 1. Update teks status di tabel
                        tdStatus.innerText = statusBaru;

                        // (Opsional) Beri highlight baris
                        tdStatus.parentElement.style.backgroundColor = '#fff3cd'; // Kuning

                        // 2. Tampilkan notifikasi
                        showNotification('Pesanan ID #' + id + ' kini statusnya: ' + statusBaru);
                    }
                }
            });
        }

        // Fungsi (Opsional) untuk menampilkan notifikasi toast
        function showNotification(message) {
            let toast = document.getElementById("toast-notification");
            toast.innerText = message;
            toast.className = "show";
            setTimeout(function() {
                toast.className = toast.className.replace("show", "");
            }, 3000);
        }

        // (Panggil sekali saat halaman baru dimuat)
        document.addEventListener('DOMContentLoaded', fetchUpdates);
    </script>
</body>

</html>