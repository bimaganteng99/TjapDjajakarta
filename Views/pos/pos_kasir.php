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
  <title>POS</title>
  <link rel="stylesheet" href="assets/css/posstyle.css">
</head>

<body>
  <?php include './views/layout/navbar.php'; ?>

  <div class="page-container">
    <h3>Menu Tersedia</h3>
    <div class="menu-grid" id="menu-tersedia-grid">

      <?php foreach ($menus as $menu): ?>
        <?php if ($menu['status'] === 'tersedia'): ?>
          <div class="menu-card"
            id="menu-card-<?= $menu['id_menu'] ?>"
            data-menu-id="<?= $menu['id_menu'] ?>">


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
                <input type="number" name="jumlah" value="1" min="1" required style="width:40px;">
                <input type="hidden" name="jenis_pesanan" value="dine in">
                <input type="text" name="catatan" placeholder="Catatan (opsional)">
                <button type="submit">Pesan</button>
              </form>
            <?php endif; ?>

          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>


    <h3>Menu Habis</h3>
    <div class="menu-grid habis-card" id="menu-habis-grid">

      <?php foreach ($menus as $menu): ?>
        <?php if ($menu['status'] === 'habis'): ?>
          <div class="menu-card habis-card"
            id="menu-habis-<?= $menu['id_menu'] ?>"
            data-menu-id="<?= $menu['id_menu'] ?>">
            <div class="image-wrapper">
              <img src="index.php?action=image_menu&id=<?= (int)$menu['id_menu'] ?>" alt="<?= htmlspecialchars($menu['nama']) ?>">
            </div>
            <h4><?= htmlspecialchars($menu['nama']) ?></h4>
            <span class="status habis">Habis</span>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>


    <?php if ($isKasir): ?>

      <div class="section-card">
        <h2>Halaman POS Kasir</h2>
        <form action="index.php?action=tambah_pesanan" method="POST">
          <label for="menu">Menu:</label>
          <select name="id_menu" id="menu" required>
            <?php foreach ($menus as $menu): ?>
              <?php
              // Tentukan apakah statusnya 'tersedia'
              $isTersedia = ($menu['status'] === 'tersedia');
              // Siapkan teks (Habis) jika statusnya 'habis'
              $textHabis = $isTersedia ? '' : ' (Habis)';
              // Nonaktifkan jika 'habis'
              $isDisabled = !$isTersedia ? 'disabled' : '';
              ?>
              <option value="<?= (int)$menu['id_menu'] ?>" data-harga="<?= htmlspecialchars($menu['harga']) ?>" <?= $isDisabled ?>>
                <?= htmlspecialchars($menu['nama']) ?> - Rp<?= number_format((float)$menu['harga']) . $textHabis ?>
              </option>
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
      </div>

      <div class="section-card">
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

    <?php endif; // --- AKHIR BLOK KHUSUS KASIR --- 
    ?>

  </div>
  <div id="toast-notification"></div>

  <script>
    // Set global JS variable agar JS tahu role user
    window.isKasir = <?= json_encode($isKasir) ?>;
  </script>

  <?php if ($isKasir): ?>
    <script>
      // --- 1. POLLING UNTUK STATUS PESANAN ---
      const pesananInterval = setInterval(fetchPesananUpdates, 5000);

      function fetchPesananUpdates() {
        fetch('index.php?action=get_pesanan_updates')
          .then(r => r.ok ? r.json() : Promise.reject('Network (Pesanan)'))
          .then(data => perbaruiTabelPesanan(data))
          .catch(err => console.error('Error polling pesanan:', err));
      }

      function perbaruiTabelPesanan(dataPesananBaru) {
        dataPesananBaru.forEach(pesanan => {
          const id = pesanan.id_pesanan;
          const statusBaru = pesanan.status_pesanan;
          const tdStatus = document.getElementById(`status-kasir-${id}`);

          if (tdStatus) {
            const lama = tdStatus.innerText;
            if (lama !== statusBaru) {
              tdStatus.innerText = statusBaru;
              tdStatus.parentElement.style.backgroundColor = '#fff3cd';
              showNotification(`Pesanan ID #${id} kini statusnya: ${statusBaru}`);
            }
          }
        });
      }


      // --- 2. POLLING UNTUK STOK MENU ---
      const stokInterval = setInterval(fetchStockUpdates, 6000);

      function fetchStockUpdates() {
        fetch('index.php?action=get_stock_updates')
          .then(r => r.ok ? r.json() : Promise.reject('Network (Stok)'))
          .then(dataMenu => perbaruiTampilanMenu(dataMenu))
          .catch(err => console.error('Error polling stok:', err));
      }

      function perbaruiTampilanMenu(dataMenuBaru) {
        const selectMenu = document.getElementById('menu');
        if (!selectMenu) return;

        dataMenuBaru.forEach(menu => {
          const idMenu = menu.id_menu;
          const statusKalkulasi = menu.status;
          // Kirim seluruh object menu ke updateMenuCard agar bisa akses harga, deskripsi, dll
          const optionMenu = selectMenu.querySelector(`option[value="${idMenu}"]`);

          // Determine current/previous states
          const sedangHabis = (statusKalkulasi === 'habis');
          const sebelumnyaDisabled = optionMenu ? optionMenu.disabled : false;

          // Fallback display name: prefer nama from server, else derive from option text or existing card
          const displayName = menu.nama || (optionMenu ? optionMenu.textContent.split(' - ')[0].trim() : null);

          // Update option element state/text
          if (optionMenu) {
            if (sedangHabis && !sebelumnyaDisabled) {
              optionMenu.disabled = true;
              optionMenu.textContent = optionMenu.textContent.replace(' (Habis)', '') + ' (Habis)';
            } else if (!sedangHabis && sebelumnyaDisabled) {
              optionMenu.disabled = false;
              optionMenu.textContent = optionMenu.textContent.replace(' (Habis)', '');
            }
          }

          // Kirim object menu ke updateMenuCard
          updateMenuCard(idMenu, statusKalkulasi, menu);

          // --- Pindahkan card menu ---
          const card = document.querySelector(`[data-menu-id="${idMenu}"]`);
          if (card) {
            const targetId = sedangHabis ? "menu-habis-grid" : "menu-tersedia-grid";
            const target = document.getElementById(targetId);
            if (target && card.parentElement !== target) {
              target.appendChild(card);
            }
          }
        });
      }

      function updateMenuCard(idMenu, statusKalkulasi, menuData) {
        // menuData bisa berupa string (nama) atau object (data lengkap)
        let namaMenu = null,
          harga = null,
          deskripsi = null;
        if (typeof menuData === 'object' && menuData !== null) {
          namaMenu = menuData.nama || null;
          harga = menuData.harga || null;
          deskripsi = menuData.deskripsi || null;
        } else {
          namaMenu = menuData;
        }
        const displayName = namaMenu || (`Menu #${idMenu}`);

        const cardTersedia = document.getElementById("menu-card-" + idMenu);
        const cardHabis = document.getElementById("menu-habis-" + idMenu);
        const gridTersedia = document.getElementById("menu-tersedia-grid");
        const gridHabis = document.getElementById("menu-habis-grid");

        // Helper: generate innerHTML card tersedia
        function getCardTersediaHTML() {
          let formPelanggan = '';
          if (!window.isKasir) {
            formPelanggan = `<form class="mini-form" action="index.php?action=tambah_pesanan" method="POST">
              <input type="hidden" name="id_menu" value="${idMenu}">
              <input type="number" name="jumlah" value="1" min="1" required style="width:40px;"> 
              <input type="hidden" name="jenis_pesanan" value="dine in">
              <input type="text" name="catatan" placeholder="Catatan (opsional)">
              <button type="submit">Pesan</button>
            </form>`;
          }
          return `
            <div class="image-wrapper">
              <img src="index.php?action=image_menu&id=${idMenu}" alt="${displayName}">
              <span class="price-badge">Rp${harga ? Number(harga).toLocaleString('id-ID') : '-'}</span>
            </div>
            <h4>${displayName}</h4>
            <p class="desc">${deskripsi ? deskripsi : '-'}</p>
            <span class="status tersedia">Tersedia</span>
            ${formPelanggan}
          `;
        }
        // Helper: generate innerHTML card habis
        function getCardHabisHTML() {
          return `
            <div class="image-wrapper">
              <img src="index.php?action=image_menu&id=${idMenu}" alt="${displayName}">
            </div>
            <h4>${displayName}</h4>
            <span class="status habis">Habis</span>
          `;
        }

        // Jika status berubah menjadi habis
        if (statusKalkulasi === "habis") {
          if (cardTersedia) {
            cardTersedia.remove();
            showNotification(`Stok untuk ${displayName} habis!`);
            const newCard = document.createElement("div");
            newCard.id = "menu-habis-" + idMenu;
            newCard.className = "menu-card habis-card";
            newCard.setAttribute('data-menu-id', idMenu);
            newCard.innerHTML = getCardHabisHTML();
            if (gridHabis) gridHabis.appendChild(newCard);
          } else if (!cardHabis) {
            const newCard = document.createElement("div");
            newCard.id = "menu-habis-" + idMenu;
            newCard.className = "menu-card habis-card";
            newCard.setAttribute('data-menu-id', idMenu);
            newCard.innerHTML = getCardHabisHTML();
            if (gridHabis) gridHabis.appendChild(newCard);
            showNotification(`Stok untuk ${displayName} habis!`);
          }
        } else {
          if (cardHabis) {
            cardHabis.remove();
            showNotification(`Stok untuk ${displayName} telah tersedia!`);
            const newCard = document.createElement("div");
            newCard.id = "menu-card-" + idMenu;
            newCard.className = "menu-card";
            newCard.setAttribute('data-menu-id', idMenu);
            newCard.innerHTML = getCardTersediaHTML();
            if (gridTersedia) gridTersedia.appendChild(newCard);
          } else if (!cardTersedia) {
            const newCard = document.createElement("div");
            newCard.id = "menu-card-" + idMenu;
            newCard.className = "menu-card";
            newCard.setAttribute('data-menu-id', idMenu);
            newCard.innerHTML = getCardTersediaHTML();
            if (gridTersedia) gridTersedia.appendChild(newCard);
            showNotification(`Stok untuk ${displayName} telah tersedia!`);
          }
        }
      }

      // --- 3. FUNGSI NOTIFIKASI ---
      function showNotification(message) {
        let toast = document.getElementById("toast-notification");
        if (toast) {
          toast.innerText = message;
          toast.className = "show";
          setTimeout(() => {
            toast.className = toast.className.replace("show", "");
          }, 3000);
        }
      }


      // --- 4. DOM CONTENT LOADED ---
      document.addEventListener('DOMContentLoaded', () => {
        fetchPesananUpdates();
        fetchStockUpdates();
      });
    </script>
  <?php endif; // --- AKHIR SCRIPT KHUSUS KASIR --- 
  ?>

</body>

</html>