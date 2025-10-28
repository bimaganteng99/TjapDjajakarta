<?php
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'manajer') {
    header('Location: index.php?action=login'); exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Menu</title>
  <style>
    body{font-family:Arial;padding:20px}
    label{display:block;margin-top:10px}
    input,select,textarea,button{padding:6px 10px;margin-top:6px}
    img{height:100px;border-radius:8px;margin-top:8px}
    .back{margin-top:15px;display:inline-block}
    button{background:#009879;color:#fff;border:0;border-radius:4px;cursor:pointer}
  </style>
</head>
<body>

<?php include './views/layout/navbar.php'; ?>

<h2>Edit Menu: <?= htmlspecialchars($menu['nama']) ?></h2>

<form action="index.php?action=update_menu" method="post" enctype="multipart/form-data">
  <input type="hidden" name="id_menu" value="<?= (int)$menu['id_menu'] ?>">

  <label>Nama</label>
  <input type="text" name="nama" value="<?= htmlspecialchars($menu['nama']) ?>" required>

  <label>Harga</label>
  <input type="number" step="0.01" name="harga" value="<?= htmlspecialchars($menu['harga']) ?>" required>

  <label>Status</label>
  <select name="status" required>
    <option value="tersedia" <?= $menu['status']==='tersedia'?'selected':''; ?>>Tersedia</option>
    <option value="habis"    <?= $menu['status']==='habis'?'selected':''; ?>>Habis</option>
  </select>

  <label>Deskripsi</label>
  <textarea name="deskripsi" rows="3" cols="40"><?= htmlspecialchars($menu['deskripsi'] ?? '') ?></textarea>

  <label>Gambar (opsional, isi jika ingin mengganti)</label>
  <?php if (!empty($menu['gambar'])): ?>
    <div>
      <img src="index.php?action=image_menu&id=<?= (int)$menu['id_menu'] ?>" alt="Gambar saat ini">
    </div>
  <?php else: ?>
    <em>Tidak ada gambar saat ini</em>
  <?php endif; ?>
  <input type="file" name="gambar" accept="image/png,image/jpeg,image/webp">

  <br><br>
  <button type="submit">Simpan Perubahan</button>
</form>

<a class="back" href="index.php?action=manajemen_menu">← Kembali ke Manajemen Menu</a>

</body>
</html>
