<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Member</title>
</head>
<body>
    <h1>Edit Data Member</h1>

    <?php if ($member): ?>
    <form method="POST" action="index.php?action=edit_member&id=<?= $member['id_member'] ?>">
        <label>Nama Member:</label><br>
        <input type="text" name="nama_member" value="<?= htmlspecialchars($member['nama_member']) ?>" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="<?= htmlspecialchars($member['email']) ?>" required><br><br>

        <label>No HP:</label><br>
        <input type="text" name="no_hp" value="<?= htmlspecialchars($member['no_hp']) ?>" required><br><br>

        <label>Tier:</label><br>
        <select name="tier" required>
            <option value="Silver" <?= $member['tier'] == 'Silver' ? 'selected' : '' ?>>Silver</option>
            <option value="Gold" <?= $member['tier'] == 'Gold' ? 'selected' : '' ?>>Gold</option>
            <option value="Platinum" <?= $member['tier'] == 'Platinum' ? 'selected' : '' ?>>Platinum</option>
        </select><br><br>

        <label>Poin:</label><br>
        <input type="number" name="poin" value="<?= $member['poin'] ?>" min="0" required><br><br>

        <label>Status:</label><br>
        <select name="status" required>
            <option value="Aktif" <?= $member['status'] == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
            <option value="Nonaktif" <?= $member['status'] == 'Nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
        </select><br><br>

        <button type="submit">Perbarui</button>
        <a href="index.php?action=membership">Batal</a>
    </form>
    <?php else: ?>
        <p>Data member tidak ditemukan.</p>
        <a href="index.php?action=membership">Kembali</a>
    <?php endif; ?>
</body>
</html>
