<?php
$allowed_roles = ['kasir','pelanggan'];
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'],$allowed_roles)) {
    header('Location: index.php?action=login'); exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>POS Kasir</title>
  <style>
    body{font-family:Arial,sans-serif;padding:20px}
    table{width:100%;border-collapse:collapse;margin-bottom:30px}
    th,td{border:1px solid #ddd;padding:10px;text-align:left}
    th{background:#f4f4f4}
    .tersedia{background:#d4edda}
    .habis{background:#f8d7da}
    img{height:48px;border-radius:6px}
  </style>
</head>
<body>
<?php include './views/layout/navbar.php'; ?>

<h2>Daftar Menu POS Kasir</h2>

<h3>Menu Tersedia</h3>
<table>
  <tr>
    <th>Gambar</th>
    <th>Nama Menu</th>
    <th>Harga</th>
    <th>Status</th>
    <th>Deskripsi</th>
  </tr>
  <?php foreach ($menus as $menu): ?>
    <?php if ($menu['status'] === 'tersedia'): ?>
      <tr class="tersedia">
        <td>
          <?php if (!empty($menu['has_gambar'])): ?>
            <img src="index.php?action=image_menu&id=<?= (int)$menu['id_menu'] ?>" alt="">
          <?php else: ?><span style="opacity:.6">-</span><?php endif; ?>
        </td>
        <td><?= htmlspecialchars($menu['nama']) ?></td>
        <td><?= htmlspecialchars($menu['harga']) ?></td>
        <td><?= htmlspecialchars($menu['status']) ?></td>
        <td><?= htmlspecialchars($menu['deskripsi'] ?? '') ?></td>
      </tr>
    <?php endif; ?>
  <?php endforeach; ?>
</table>

<h3>Menu Habis</h3>
<table>
  <tr>
    <th>Gambar</th>
    <th>Nama Menu</th>
    <th>Harga</th>
    <th>Status</th>
    <th>Deskripsi</th>
  </tr>
  <?php foreach ($menus as $menu): ?>
    <?php if ($menu['status'] === 'habis'): ?>
      <tr class="habis">
        <td>
          <?php if (!empty($menu['has_gambar'])): ?>
            <img src="index.php?action=image_menu&id=<?= (int)$menu['id_menu'] ?>" alt="">
          <?php else: ?><span style="opacity:.6">-</span><?php endif; ?>
        </td>
        <td><?= htmlspecialchars($menu['nama']) ?></td>
        <td><?= htmlspecialchars($menu['harga']) ?></td>
        <td><?= htmlspecialchars($menu['status']) ?></td>
        <td><?= htmlspecialchars($menu['deskripsi'] ?? '') ?></td>
      </tr>
    <?php endif; ?>
  <?php endforeach; ?>
</table>

<!-- form tambah pesanan dll milikmu tetap -->
</body>
</html>
