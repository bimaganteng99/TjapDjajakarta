<?php
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'manajer') {
  header('Location: index.php?action=login');
  exit();
}
$menus = $menus ?? [];
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Manajemen Menu</title>
  <style>
    body {
      font-family: Arial;
      padding: 20px
    }

    table {
      border-collapse: collapse;
      width: 100%;
      margin-top: 15px
    }

    th,
    td {
      border: 1px solid #ccc;
      padding: 8px 10px;
      text-align: center
    }

    th {
      background: #f4f4f4
    }

    img {
      height: 70px;
      border-radius: 6px
    }

    button {
      background: #009879;
      color: #fff;
      border: 0;
      border-radius: 4px;
      padding: 6px 10px;
      cursor: pointer
    }
  </style>
</head>

<body>

  <?php include './views/layout/navbar.php'; ?>

  <h2>🍽️ Manajemen Menu</h2>
  <p>Anda login sebagai <strong>Manajer</strong></p>

  <h3>Tambah Menu Baru</h3>
  <form action="index.php?action=handleMenu" method="post" enctype="multipart/form-data">
    <input type="hidden" name="menu_action" value="add">
    <input type="text" name="nama" placeholder="Nama Menu" required>
    <input type="number" step="0.01" name="harga" placeholder="Harga" required>
    <select name="status" required>
      <option value="tersedia">Tersedia</option>
      <option value="habis">Habis</option>
    </select>
    <textarea name="deskripsi" placeholder="Deskripsi (opsional)" rows="2" cols="30"></textarea>
    <input type="file" name="gambar" accept="image/png,image/jpeg,image/webp">
    <button type="submit">Tambah</button>
  </form>

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
      <?php foreach ($menus as $m): ?>
        <tr>
          <td>
            <?php if (!empty($m['has_gambar'])): ?>
              <img src="index.php?action=image_menu&id=<?= (int)$m['id_menu'] ?>" alt="Gambar Menu">
            <?php else: ?>
              <em>Tidak ada</em>
            <?php endif; ?>
          </td>
          <td><?= htmlspecialchars($m['nama']) ?></td>
          <td>Rp <?= number_format($m['harga'], 0, ',', '.') ?></td>
          <td><?= htmlspecialchars($m['status']) ?></td>
          <td><?= htmlspecialchars($m['deskripsi'] ?? '') ?></td>
          <td>
            <a href="index.php?action=edit_menu&id=<?= (int)$m['id_menu'] ?>">Edit</a>
            | <a href="index.php?action=edit_resep&id=<?= $m['id_menu'] ?>">Edit Resep</a>
            <form action="index.php?action=handleMenu" method="post" style="display:inline-block" onsubmit="return confirm('Hapus menu ini?')">
              <input type="hidden" name="menu_action" value="delete">
              <input type="hidden" name="id_menu" value="<?= (int)$m['id_menu'] ?>">
              <button type="submit" style="background:#c62828">Hapus</button>
            </form>
          </td>

          <!-- <td>
        <form action="index.php?action=handleMenu" method="post" onsubmit="return confirm('Hapus menu ini?')">
          <input type="hidden" name="menu_action" value="delete">
          <input type="hidden" name="id_menu" value="<?= (int)$m['id_menu'] ?>">
          <button type="submit" style="background:#c62828">Hapus</button>
        </form>
      </td> -->
        </tr>
      <?php endforeach; ?>
    </table>
  <?php else: ?>
    <p><em>Belum ada menu.</em></p>
  <?php endif; ?>

</body>

</html>