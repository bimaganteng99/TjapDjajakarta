<?php
$allowed_roles = ['kasir', 'pelanggan'];
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], $allowed_roles)) {
  header('Location: index.php?action=login');
  exit();
}
$isKasir = ($_SESSION['user_role'] === 'kasir');
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>POS Kasir</title>
  <style>
    body { font-family: Arial, sans-serif; padding: 20px; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
    th, td { border: 1px solid #ddd; padding: 10px; text-align: left; vertical-align: top; }
    th { background: #f4f4f4; }
    .tersedia { background: #d4edda; }
    .habis { background: #f8d7da; }
    img { height: 48px; border-radius: 6px; display:block; }
    .mini-form { margin-top: 8px; display: flex; flex-wrap: wrap; gap: 6px; align-items: center; }
    .mini-form input[type="number"] { width: 70px; }
    .mini-form input[type="text"] { min-width: 160px; }
    .mini-form select, .mini-form input, .mini-form button { padding: 6px 8px; }
    .muted { opacity: .65; }
    /* Toast (dipakai kasir) */
    #toast-notification {
      visibility: hidden; min-width: 250px; background-color: #333; color: #fff;
      text-align: center; border-radius: 5px; padding: 16px; position: fixed; z-index: 100;
      right: 30px; top: 30px;
    }
    #toast-notification.show { visibility: visible; animation: fadein 0.5s, fadeout 0.5s 2.5s; }
    @keyframes fadein { from { top: 0; opacity: 0; } to { top: 30px; opacity: 1; } }
    @keyframes fadeout { from { top: 30px; opacity: 1; } to { top: 0; opacity: 0; } }
  </style>
</head>
<body>
  <?php include './views/layout/navbar.php'; ?>

  <h2>Daftar Menu POS</h2>

  <!-- ====== MENU TERSEDIA ====== -->
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
              <img src="index.php?action=image_menu&id=<?= (int)$menu['id_menu'] ?>" alt="<?= htmlspecialchars($menu['nama']) ?>">
            <?php else: ?><span class="muted">-</span><?php endif; ?>
          </td>
          <td><?= htmlspecialchars($menu['nama']) ?></td>
          <td>Rp<?= number_format((float)$menu['harga']) ?></td>
          <td><?= htmlspecialchars($menu['status']) ?></td>
          <td>
            <?= htmlspecialchars($menu['deskripsi'] ?? '') ?>

            <?php if (!$isKasir): // PELANGGAN: pesan langsung dari item ?>
              <form class="mini-form" action="index.php?action=tambah_pesanan" method="POST">
                <input type="hidden" name="id_menu" value="<?= (int)$menu['id_menu'] ?>">
                <input type="number" name="jumlah" value="1" min="1" required>
                <select name="jenis_pesanan" required>
                  <option value="dine in">Dine In</option>
                  <option value="delivery">Delivery</option>
                </select>
                <input type="text" name="catatan" placeholder="Catatan (opsional)">
                <button type="submit">Pesan</button>
              </form>
            <?php endif; ?>

          </td>
        </tr>
      <?php endif; ?>
    <?php endforeach; ?>
  </table>

  <!-- ====== MENU HABIS ====== -->
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
              <img src="index.php?action=image_menu&id=<?= (int)$menu['id_menu'] ?>" alt="<?= htmlspecialchars($menu['nama']) ?>">
            <?php else: ?><span class="muted">-</span><?php endif; ?>
          </td>
          <td><?= htmlspecialchars($menu['nama']) ?></td>
          <td>Rp<?= number_format((float)$menu['harga']) ?></td>
          <td><?= htmlspecialchars($menu['status']) ?></td>
          <td>
            <?= htmlspecialchars($menu['deskripsi'] ?? '') ?>

            <?php if (!$isKasir): // pelanggan TIDAK bisa pesan yang habis ?>
              <div class="muted" style="margin-top:6px;">Tidak bisa dipesan (habis)</div>
            <?php endif; ?>

          </td>
        </tr>
      <?php endif; ?>
    <?php endforeach; ?>
  </table>

  <?php if ($isKasir): ?>
    <!-- ====== KASIR SAJA: Form pemesanan global ====== -->
    <h2>Halaman POS Kasir</h2>
    <form action="index.php?action=tambah_pesanan" method="POST">
      <label for="menu">Menu:</label>
      <select name="id_menu" id="menu" required>
        <?php foreach ($menus as $menu): ?>
          <?php if ($menu['status'] === 'tersedia'): ?>
            <option value="<?= (int)$menu['id_menu'] ?>" data-harga="<?= htmlspecialchars($menu['harga']) ?>">
              <?= htmlspecialchars($menu['nama']) ?> - Rp<?= number_format((float)$menu['harga']) ?>
            </option>
          <?php endif; ?>
        <?php endforeach; ?>
      </select>

      <label for="jumlah">Jumlah:</label>
      <input type="number" name="jumlah" id="jumlah" value="1" min="1" required>

      <label for="jenis_pesanan">Jenis Pesanan:</label>
      <select name="jenis_pesanan" id="jenis_pesanan" required>
        <option value="dine in">Dine In</option>
        <option value="delivery">Delivery</option>
      </select>

      <label for="catatan">Catatan:</label>
      <input type="text" name="catatan" id="catatan">

      <button type="submit">Tambah Pesanan</button>
    </form>

    <hr>

    <!-- ====== KASIR SAJA: Daftar Pesanan + Polling ====== -->
    <h3>Daftar Pesanan</h3>
    <table border="1" cellpadding="5">
      <thead>
        <tr>
          <th>ID</th>
          <th>Kode</th>
          <th>Menu</th>
          <th>Jumlah</th>
          <th>Total</th>
          <th>Jenis</th>
          <th>Status</th>
          <th>Catatan</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($daftar_pesanan as $pesanan): ?>
          <tr>
            <td><?= (int)$pesanan['id_pesanan'] ?></td>
            <td><?= htmlspecialchars($pesanan['kode_pesanan']) ?></td>
            <td><?= htmlspecialchars($pesanan['nama_menu']) ?></td>
            <td><?= htmlspecialchars($pesanan['jumlah']) ?></td>
            <td>Rp<?= number_format((float)$pesanan['total_harga']) ?></td>
            <td><?= htmlspecialchars($pesanan['jenis_pesanan']) ?></td>
            <td id="status-kasir-<?= (int)$pesanan['id_pesanan'] ?>"><?= htmlspecialchars($pesanan['status_pesanan']) ?></td>
            <td><?= htmlspecialchars($pesanan['catatan'] ?: '-') ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div id="toast-notification"></div>

    <script>
      // Polling hanya untuk kasir
      const pollingInterval = setInterval(fetchUpdates, 5000);

      function fetchUpdates() {
        fetch('index.php?action=get_pesanan_updates')
          .then(r => { if (!r.ok) throw new Error('Network'); return r.json(); })
          .then(data => perbaruiTabel(data))
          .catch(err => console.error('Error polling:', err));
      }

      function perbaruiTabel(dataPesananBaru) {
        dataPesananBaru.forEach(pesanan => {
          const id = pesanan.id_pesanan;
          const statusBaru = pesanan.status_pesanan;
          const tdStatus = document.getElementById('status-kasir-' + id);

          if (tdStatus) {
            const lama = tdStatus.innerText;
            if (lama !== statusBaru) {
              tdStatus.innerText = statusBaru;
              tdStatus.parentElement.style.backgroundColor = '#fff3cd';
              showNotification('Pesanan ID #' + id + ' kini statusnya: ' + statusBaru);
            }
          }
        });
      }

      function showNotification(message) {
        let toast = document.getElementById("toast-notification");
        toast.innerText = message;
        toast.className = "show";
        setTimeout(() => { toast.className = toast.className.replace("show", ""); }, 3000);
      }

      document.addEventListener('DOMContentLoaded', fetchUpdates);
    </script>
  <?php endif; // end kasir only ?>

</body>
</html>
