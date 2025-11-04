<?php
// (File: views/manajemen/edit_resep.php)
// Keamanan
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'manajer') {
    header('Location: index.php?action=login');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Resep</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .container {
            max-width: 700px;
            margin: auto;
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

        .form-resep,
        .tabel-resep {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        button {
            padding: 5px 10px;
            cursor: pointer;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
            border: none;
        }
    </style>
</head>

<body>
    <?php include './views/layout/navbar.php'; ?>

    <div class="container">
        <h2>Edit Resep untuk: <?= isset($menu) ? htmlspecialchars($menu['nama']) : 'Menu' ?></h2>
        <p><a href="index.php?action=manajemen_menu">&larr; Kembali ke Manajemen Menu</a></p>

        <div class="form-resep">
            <h3>Tambah Bahan ke Resep</h3>
            <form action="index.php?action=handle_resep" method="POST">
                <input type="hidden" name="resep_action" value="add">
                <input type="hidden" name="id_menu" value="<?= $menu['id_menu'] ?>">

                <label for="id_bahan">Pilih Bahan Baku:</label>
                <select id="id_bahan" name="id_bahan" required>
                    <option value="">-- Pilih Bahan --</option>
                    <?php foreach ($semua_bahan_baku as $bahan): ?>
                        <option value="<?= $bahan['id_bahan'] ?>">
                            <?= htmlspecialchars($bahan['nama_bahan']) ?> (Satuan: <?= $bahan['satuan'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="jumlah">Jumlah Dibutuhkan:</label>
                <input type="number" id="jumlah" name="jumlah_dibutuhkan" step="0.01" min="0.01" required>

                <button type="submit">Tambah Bahan</button>
            </form>
        </div>

        <div class="tabel-resep">
            <h3>Resep Saat Ini</h3>
            <table>
                <thead>
                    <tr>
                        <th>Nama Bahan</th>
                        <th>Jumlah Dibutuhkan</th>
                        <th>Satuan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($resep_sekarang)): ?>
                        <tr>
                            <td colspan="4" style="text-align: center;">Belum ada resep untuk menu ini.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($resep_sekarang as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['nama_bahan']) ?></td>
                                <td><?= $item['jumlah_dibutuhkan'] ?></td>
                                <td><?= htmlspecialchars($item['satuan']) ?></td>
                                <td>
                                    <form action="index.php?action=handle_resep" method="POST" style="display:inline;">
                                        <input type="hidden" name="resep_action" value="delete">
                                        <input type="hidden" name="id_resep" value="<?= $item['id_resep'] ?>">
                                        <input type="hidden" name="id_menu" value="<?= $menu['id_menu'] ?>"> <button type="submit" class="btn-delete" onclick="return confirm('Yakin hapus bahan ini dari resep?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</body>

</html>