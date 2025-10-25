<?php
// views/manajemen/manajemen_menu.php

// Batasi akses hanya untuk manajer
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'manajer') {
    header('Location: index.php?action=login');
    exit();
}

// Pastikan $menus tidak null
$menus = $menus ?? [];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Manajemen Menu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px 10px;
            text-align: center;
        }

        th {
            background-color: #f4f4f4;
        }

        input,
        textarea,
        select,
        button {
            padding: 6px 10px;
            margin: 5px;
        }

        button {
            cursor: pointer;
            border: none;
            border-radius: 4px;
            background-color: #009879;
            color: #fff;
        }

        button:hover {
            background-color: #007a63;
        }

        a {
            text-decoration: none;
            color: #007a63;
        }

        a:hover {
            text-decoration: underline;
        }

        img {
            width: 70px;
            border-radius: 6px;
        }
    </style>
</head>

<body>

    <?php include './views/layout/navbar.php'; ?>

    <h2>🍽️ Manajemen Menu</h2>
    <p>Anda login sebagai <strong>Manajer</strong></p>

    <!-- Form tambah menu -->
    <h3>Tambah Menu Baru</h3>
    <form action="index.php?action=handleMenu" method="post">
        <input type="hidden" name="menu_action" value="add">
        <input type="text" name="nama" placeholder="Nama Menu" required>
        <input type="number" name="harga" placeholder="Harga" required>
        <input type="text" name="status" placeholder="Status (tersedia / habis)" required>
        <button type="submit">Tambah</button>
    </form>


    <!-- Tabel daftar menu -->
    <h3>Daftar Menu</h3>
    <?php if (!empty($menus)): ?>
        <table>
            <tr>
                <th>Gambar</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Status</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($menus as $menu): ?>
                <tr>
                    <td>
                        <?php if (!empty($menu['gambar'])): ?>
                            <img src="data:image/jpeg;base64,<?= base64_encode($menu['gambar']); ?>" alt="Gambar Menu">
                        <?php else: ?>
                            <em>Tidak ada</em>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($menu['nama']); ?></td>
                    <td>Rp <?= number_format($menu['harga'], 0, ',', '.'); ?></td>
                    <td><?= htmlspecialchars(ucfirst($menu['status'])); ?></td>
                    <td><?= htmlspecialchars($menu['deskripsi']); ?></td>
                    <td>
                        <a href="index.php?action=edit_menu&id=<?= $menu['id_menu']; ?>">Edit</a> |
                        <a href="index.php?action=hapus_menu&id=<?= $menu['id_menu']; ?>"
                            onclick="return confirm('Yakin ingin menghapus menu ini?');">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p><em>Belum ada menu yang ditambahkan.</em></p>
    <?php endif; ?>

    <br>
    <a href="index.php?action=logout">Logout</a>

</body>

</html>