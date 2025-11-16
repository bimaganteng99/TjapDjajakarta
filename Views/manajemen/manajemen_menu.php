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
  <link rel="stylesheet" href="assets/css/manajemen_menu.css">
</head>

<body>

  <?php include './views/layout/navbar.php'; ?>

  <div class="page-container">
    <div class="card">
      <h3>Tambah Menu Baru</h3>

      <div class="form-table">
        <form action="index.php?action=handleMenu" method="post" enctype="multipart/form-data">
          <input type="hidden" name="menu_action" value="add">
          <input type="text" name="nama" placeholder="Nama Menu" required>
          <input type="number" step="0.01" name="harga" placeholder="Harga" required>
          <select name="status" required>
            <option value="tersedia">Tersedia</option>
            <option value="habis">Habis</option>
          </select>
          <textarea name="deskripsi" placeholder="Deskripsi (opsional)" rows="2"></textarea>
          <input type="file" name="gambar" accept="image/png,image/jpeg,image/webp">
          <button type="submit">Tambah</button>
        </form>
      </div>
    </div>


    <h3>Daftar Menu</h3>
    <div class="menu-grid">
      <?php foreach ($menus as $m): ?>
        <div class="menu-card">
          <?php if (!empty($m['has_gambar'])): ?>
            <img src="index.php?action=image_menu&id=<?= (int)$m['id_menu'] ?>">
          <?php else: ?>
            <img src="https://via.placeholder.com/300x160?text=No+Image">
          <?php endif; ?>

          <h4><?= htmlspecialchars($m['nama']) ?></h4>
          <p>Rp <?= number_format($m['harga'], 0, ',', '.') ?></p>
          <p><em><?= htmlspecialchars($m['status']) ?></em></p>
          <p><?= htmlspecialchars($m['deskripsi'] ?? '') ?></p>

          <div class="menu-actions">
            <a href="index.php?action=edit_menu&id=<?= $m['id_menu'] ?>">Edit</a>
            <a href="index.php?action=edit_resep&id=<?= $m['id_menu'] ?>">Resep</a>

            <form action="index.php?action=handleMenu" method="post" style="display:inline-block"
              onsubmit="return confirm('Hapus menu ini?')">
              <input type="hidden" name="menu_action" value="delete">
              <input type="hidden" name="id_menu" value="<?= (int)$m['id_menu'] ?>">
              <button type="submit">Hapus</button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    </div>


</body>

</html>