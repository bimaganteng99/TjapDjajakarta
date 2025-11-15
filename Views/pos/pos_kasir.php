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
  <link rel="stylesheet" href="assets/css/posstyle.css">
</head>

<body>
  <?php include './views/layout/navbar.php'; ?>

  <div class="page-container">
    <!-- ====== MENU TERSEDIA ====== -->
    <h3>Menu Tersedia</h3>
    <div class="menu-grid">
      <?php foreach ($menus as $menu): ?>
        <?php if ($menu['status'] === 'tersedia'): ?>
          <div class="menu-card">

            <div class="image-wrapper">
              <img src="index.php?action=image_menu&id=<?= (int)$menu['id_menu'] ?>" alt="<?= htmlspecialchars($menu['nama']) ?>">
              <span class="price-badge">Rp<?= number_format((float)$menu['harga']) ?></span>
            </div>

            <h4><?= htmlspecialchars($menu['nama']) ?></h4>
            <p class="desc"><?= htmlspecialchars($menu['deskripsi'] ?? '-') ?></p>
            <span class="status tersedia">Tersedia</span>

            <?php if (!$isKasir): ?>
              <form class="mini-form" action="index.php?action=tambah_pesanan" method="POST">
                <input type="hidden" name="id_menu" value="<?= (int)$menu['id_menu'] ?>">
                <input type="number" name="jumlah" min="1" value="1">
                <select name="jenis_pesanan">
                  <option value="dine in">Dine In</option>
                  <option value="delivery">Delivery</option>
                </select>
                <input type="text" name="catatan" placeholder="Catatan (opsional)">
                <button type="submit">Pesan</button>
              </form>
            <?php endif; ?>

          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>


    <!-- ====== MENU HABIS ====== -->
    <h3>Menu Habis</h3>
    <div class="menu-grid">
      <?php foreach ($menus as $menu): ?>
        <?php if ($menu['status'] === 'habis'): ?>
          <div class="menu-card habis-card">

            <div class="image-wrapper">
              <img class="habis-img" src="index.php?action=image_menu&id=<?= (int)$menu['id_menu'] ?>">
              <span class="price-badge habis-badge">Rp<?= number_format((float)$menu['harga']) ?></span>
            </div>

            <h4><?= htmlspecialchars($menu['nama']) ?></h4>
            <p class="desc"><?= htmlspecialchars($menu['deskripsi'] ?? '-') ?></p>
            <span class="status habis">Habis</span>

            <div class="muted" style="margin-top: 6px;">Tidak bisa dipesan</div>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>


    <?php if ($isKasir): ?>
      <div class="section-card">
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
      </div>
  </div>

  <div id="toast-notification"></div>

  <script>
    // Polling hanya untuk kasir
    const pollingInterval = setInterval(fetchUpdates, 5000);

    function fetchUpdates() {
      fetch('index.php?action=get_pesanan_updates')
        .then(r => {
          if (!r.ok) throw new Error('Network');
          return r.json();
        })
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
      setTimeout(() => {
        toast.className = toast.className.replace("show", "");
      }, 3000);
    }

    document.addEventListener('DOMContentLoaded', fetchUpdates);
  </script>
<?php endif; // end kasir only 
?>

</body>

</html>