<?php
// (Pastikan session_start() sudah ada di index.php)
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'pelanggan') {
    header('Location: index.php?action=login');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Membership</title>
</head>

<body>
    <?php include './views/layout/navbar.php'; ?>

    <h1>Membership Anda</h1>
    <h2>Selamat datang, <?= htmlspecialchars($_SESSION['user_username']); ?>!</h2>
    <p>Di halaman ini Anda dapat melihat status dan detail membership Anda.</p>

    <?php if (isset($member) && $member): ?>
        <table border="1" cellpadding="6" style="margin-top:20px;">
            <tr>
                <th>ID Member</th>
                <td><?= htmlspecialchars($member['id_member']) ?></td>
            </tr>
            <tr>
                <th>Tanggal Daftar</th>
                <td><?= htmlspecialchars($member['tanggal_daftar']) ?></td>
            </tr>
            <tr>
                <th>Tier</th>
                <td><?= htmlspecialchars($member['tier']) ?></td>
            </tr>
            <tr>
                <th>Poin</th>
                <td><?= htmlspecialchars($member['poin']) ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?= htmlspecialchars($member['status']) ?></td>
            </tr>
            <tr>
                <th>Tanggal Kadaluwarsa</th>
                <td><?= htmlspecialchars($member['tanggal_kadaluwarsa']) ?></td>
            </tr>
        </table>
        <br>
        <?php if (isset($_GET['action']) && $_GET['action'] === 'edit_member'): ?>
            <form action="index.php?action=edit_member" method="post" style="margin-top:20px;">
                <label>Tier:
                    <select name="tier" required>
                        <option value="basic" <?= $member['tier'] === 'basic' ? 'selected' : '' ?>>Basic</option>
                        <option value="silver" <?= $member['tier'] === 'silver' ? 'selected' : '' ?>>Silver</option>
                        <option value="gold" <?= $member['tier'] === 'gold' ? 'selected' : '' ?>>Gold</option>
                        <option value="platinum" <?= $member['tier'] === 'platinum' ? 'selected' : '' ?>>Platinum</option>
                    </select>
                </label><br>
                <label>Poin: <input type="number" name="poin" value="<?= htmlspecialchars($member['poin']) ?>" required></label><br>
                <label>Status: <input type="text" name="status" value="<?= htmlspecialchars($member['status']) ?>" required></label><br>
                <label>Tanggal Kadaluwarsa: <input type="date" name="tanggal_kadaluwarsa" value="<?= htmlspecialchars($member['tanggal_kadaluwarsa']) ?>" required></label><br>
                <button type="submit">Simpan Perubahan</button>
                <a href="index.php?action=membership">Batal</a>
            </form>
        <?php else: ?>
            <a href="index.php?action=edit_member">Edit Membership</a>
            <form action="index.php?action=hapus_member" method="post" style="display:inline;">
                <button type="submit" onclick="return confirm('Yakin hapus membership?')">Hapus</button>
            </form>
        <?php endif; ?>
    <?php else: ?>
        <p style="margin-top:30px;">Anda belum memiliki membership.</p>
        <?php
        $today = date('Y-m-d');
        $nextMonth = date('Y-m-d', strtotime('+1 month'));
        ?>
        <form action="index.php?action=tambah_member" method="post" style="margin-top:20px;">
            <label>Tier:
                <select name="tier" required>
                    <option value="basic">Basic</option>
                    <option value="silver">Silver</option>
                    <option value="gold">Gold</option>
                    <option value="platinum">Platinum</option>
                </select>
            </label><br>
            <label>Poin: <input type="number" name="poin" value="0" required></label><br>
            <label>Status: <input type="text" name="status" value="aktif" required></label><br>
            <label>Tanggal Kadaluwarsa: <input type="date" name="tanggal_kadaluwarsa" value="<?= $nextMonth ?>" readonly required></label><br>
            <button type="submit">Daftar Membership</button>
        </form>
    <?php endif; ?>

    <br><br>
</body>

</html>