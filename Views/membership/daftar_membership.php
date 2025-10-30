<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Membership</title>
</head>
<body>
    <h1>Daftar Pengguna Membership</h1>
    <a href="index.php?action=tambah_member">+ Tambah Member Baru</a>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Email</th>
            <th>No HP</th>
            <th>Tier</th>
            <th>Poin</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($members as $m): ?>
        <tr>
            <td><?= $m['id_member'] ?></td>
            <td><?= htmlspecialchars($m['nama_member']) ?></td>
            <td><?= htmlspecialchars($m['email']) ?></td>
            <td><?= htmlspecialchars($m['no_hp']) ?></td>
            <td><?= $m['tier'] ?></td>
            <td><?= $m['poin'] ?></td>
            <td><?= $m['status'] ?></td>
            <td>
                <a href="index.php?action=edit_member&id=<?= $m['id_member'] ?>">Edit</a> |
                <a href="index.php?action=hapus_member&id=<?= $m['id_member'] ?>" onclick="return confirm('Hapus member ini?')">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
