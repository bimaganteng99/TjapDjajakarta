<?php
$allowed_roles = ['kasir', 'pelanggan'];
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], $allowed_roles)) {
  header('Location: index.php?action=login');
  exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>POS Kasir</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 30px;
    }

    th,
    td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: left;
    }

    th {
      background: #f4f4f4;
    }

    .tersedia {
      background: #d4edda;
    }

    .habis {
      background: #f8d7da;
    }

    img {
      height: 48px;
      border-radius: 6px;
    }

    /* CSS untuk Notifikasi Toast (jika belum ada di file lain) */
    #toast-notification {
      visibility: hidden;
      min-width: 250px;
      background-color: #333;
      color: #fff;
      text-align: center;
      border-radius: 5px;
      padding: 16px;
      position: fixed;
      z-index: 100;
      right: 30px;
      top: 30px;
    }

    #toast-notification.show {
      visibility: visible;
      animation: fadein 0.5s, fadeout 0.5s 2.5s;
    }

    @keyframes fadein {
      from {
        top: 0;
        opacity: 0;
      }

      to {
        top: 30px;
        opacity: 1;
      }
    }

    @keyframes fadeout {
      from {
        top: 30px;
        opacity: 1;
      }

      to {
        top: 0;
        opacity: 0;
      }
    }
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
              <img src="index.php?action=image_menu&id=<?= (int)$menu['id_menu'] ?>" alt="<?= htmlspecialchars($menu['nama']) ?>">
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
              <img src="index.php?action=image_menu&id=<?= (int)$menu['id_menu'] ?>" alt="<?= htmlspecialchars($menu['nama']) ?>">
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

  <h2>Halaman POS Kasir</h2>
  <form action="index.php?action=tambah_pesanan" method="POST">
    <label for="menu">Menu:</label>
    <select name="id_menu" id="menu" required>
      <?php foreach ($menus as $menu): ?>
        <?php if ($menu['status'] === 'tersedia'): // Hanya tampilkan menu tersedia di dropdown 
        ?>
          <option value="<?= $menu['id_menu'] ?>" data-harga="<?= $menu['harga'] ?>">
            <?= $menu['nama'] ?> - Rp<?= number_format($menu['harga']) ?>
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
          <td><?= $pesanan['id_pesanan'] ?></td>
          <td><?= htmlspecialchars($pesanan['kode_pesanan']) ?></td>
          <td><?= htmlspecialchars($pesanan['nama_menu']) ?></td>
          <td><?= htmlspecialchars($pesanan['jumlah']) ?></td>
          <td>Rp<?= number_format($pesanan['total_harga']) ?></td>
          <td><?= htmlspecialchars($pesanan['jenis_pesanan']) ?></td>
          <td id="status-kasir-<?= $pesanan['id_pesanan'] ?>"><?= htmlspecialchars($pesanan['status_pesanan']) ?></td>
          <td><?= htmlspecialchars($pesanan['catatan'] ?: '-') ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div id="toast-notification"></div>

  <script>
    // Jalankan fungsi fetchUpdates() setiap 5 detik (5000 milidetik)
    const pollingInterval = setInterval(fetchUpdates, 5000);

    // Fungsi untuk memanggil API
    function fetchUpdates() {
      fetch('index.php?action=get_pesanan_updates')
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.json();
        })
        .then(dataPesananBaru => {
          // Panggil fungsi untuk membandingkan dan update tabel
          perbaruiTabel(dataPesananBaru);
        })
        .catch(error => {
          console.error('Error polling:', error);
          // Hentikan polling jika ada error (misal: session habis)
          // clearInterval(pollingInterval); 
        });
    }

    // Fungsi untuk membandingkan data baru dengan tabel HTML
    function perbaruiTabel(dataPesananBaru) {
      // Kita juga harus cek baris baru
      const tbody = document.querySelector('h3+table tbody'); // Cari tbody setelah h3 Daftar Pesanan
      if (!tbody) return;

      let existingIds = {}; // Untuk melacak ID yang ada di tabel

      // Update baris yang ada
      dataPesananBaru.forEach(pesanan => {
        let id = pesanan.id_pesanan;
        existingIds[id] = true; // Tandai ID ini ada di data baru

        let statusBaru = pesanan.status_pesanan;
        let tdStatus = document.getElementById('status-kasir-' + id);

        if (tdStatus) {
          // 1. Update status jika ada
          let statusLama = tdStatus.innerText;
          if (statusLama !== statusBaru) {
            tdStatus.innerText = statusBaru;
            tdStatus.parentElement.style.backgroundColor = '#fff3cd'; // Kuning
            showNotification('Pesanan ID #' + id + ' kini statusnya: ' + statusBaru);
          }
        } else {
          // 2. Tambahkan baris baru jika ID-nya belum ada di tabel
          // Ini untuk pesanan yang baru masuk
          let newRow = tbody.insertRow(0); // Tambah di paling atas
          newRow.innerHTML = `
                        <td>${pesanan.id_pesanan}</td>
                        <td>${pesanan.kode_pesanan || ''}</td>
                        <td>${pesanan.nama_menu || ''}</td>
                        <td>${pesanan.jumlah || ''}</td>
                        <td>Rp${Number(pesanan.total_harga || 0).toLocaleString()}</td>
                        <td>${pesanan.jenis_pesanan || ''}</td>
                        <td id="status-kasir-${id}">${statusBaru}</td>
                        <td>${pesanan.catatan || '-'}</td>
                    `;
          newRow.style.backgroundColor = '#d4edda'; // Hijau
          showNotification('Pesanan Baru ID #' + id + ' telah masuk!');
        }
      });

      // (Opsional) Hapus baris lama yang sudah tidak ada di data baru
      // ...
    }

    // Fungsi untuk menampilkan notifikasi toast
    function showNotification(message) {
      let toast = document.getElementById("toast-notification");
      if (toast) {
        toast.innerText = message;
        toast.className = "show";
        setTimeout(function() {
          toast.className = toast.className.replace("show", "");
        }, 3000);
      }
    }

    // Panggil sekali saat halaman baru dimuat
    document.addEventListener('DOMContentLoaded', fetchUpdates);
  </script>
</body>

</html>