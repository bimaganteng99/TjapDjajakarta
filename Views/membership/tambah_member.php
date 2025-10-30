<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Member Baru</title>
</head>
<body>
    <h1>Tambah Data Member Baru</h1>

    <form method="POST" action="index.php?action=tambah_member">
        <label>Nama Member:</label><br>
        <input type="text" name="nama_member" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>No HP:</label><br>
        <input type="text" name="no_hp" required><br><br>

        <label>Tier:</label><br>
        <select name="tier" required>
            <option value="Silver">Silver</option>
            <option value="Gold">Gold</option>
            <option value="Platinum">Platinum</option>
        </select><br><br>

        <label>Poin Awal:</label><br>
        <input type="number" name="poin" value="0" min="0" required><br><br>

        <label>Status:</label><br>
        <select name="status" required>
            <option value="Aktif">Aktif</option>
            <option value="Nonaktif">Nonaktif</option>
        </select><br><br>

        <button type="submit">Simpan</button>
        <a href="index.php?action=membership">Kembali</a>
    </form>
</body>
</html>
