<?php
class MenuModel
{
    private $conn;
    private $table = 'menu';
    private $bahanBakuModel; // Kita butuh ini
    private $resepModel;     // Kita butuh ini

    public function __construct($db)
    {
        $this->conn = $db;
        $this->bahanBakuModel = new BahanBakuModel($db);
        $this->resepModel = new ResepModel($db);
    }

    public function getAllMenus()
    {
        $sql = "SELECT id_menu, nama, harga, status, deskripsi,
                       (gambar IS NOT NULL) AS has_gambar
                FROM {$this->table}
                WHERE status != 'diarsipkan'  -- TAMBAHKAN INI
                ORDER BY nama ASC";
        $st = $this->conn->prepare($sql);
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllMenusWithCalculatedStock()
    {
        // 1. Ambil semua menu (kecuali yg diarsipkan)
        $semua_menu = $this->getAllMenus(); // Memakai fungsi di atas

        // 2. Ambil stok bahan (sekali saja, untuk efisiensi)
        $stok_bahan = $this->bahanBakuModel->getAllBahanBakuIndexed();

        $menu_kalkulasi = [];

        // 3. Loop setiap menu dan kalkulasi ulang statusnya
        foreach ($semua_menu as $menu) {
            $status_kalkulasi = $menu['status']; // Ambil status manual (tersedia/habis)

            // Hanya cek resep jika status manualnya 'tersedia'
            if ($status_kalkulasi == 'tersedia') {
                $resep = $this->resepModel->getResepByIdMenu($menu['id_menu']);

                if (!empty($resep)) {
                    // Jika menu ini punya resep, cek stok bahannya
                    foreach ($resep as $item) {
                        $id_bahan = $item['id_bahan'];
                        $butuh = (float)$item['jumlah_dibutuhkan'];
                        $tersedia = $stok_bahan[$id_bahan] ?? 0; // Stok bahan saat ini

                        if ($tersedia < $butuh) {
                            // JIKA SATU BAHAN SAJA KURANG, timpa statusnya!
                            $status_kalkulasi = 'habis';
                            break; // Stop cek resep ini, lanjut ke menu berikutnya
                        }
                    }
                }
            }

            // 4. Masukkan status baru (hasil kalkulasi) ke array menu
            $menu['status'] = $status_kalkulasi;
            $menu_kalkulasi[] = $menu;
        }

        return $menu_kalkulasi;
    }

    /** Ambil satu menu (termasuk BLOB) */
    public function getMenuById($id_menu)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id_menu = :id LIMIT 1";
        $st  = $this->conn->prepare($sql);
        $st->bindParam(':id', $id_menu, PDO::PARAM_INT);
        $st->execute();
        return $st->fetch(PDO::FETCH_ASSOC);
    }

    /** Insert menu dengan BLOB */
    public function addMenuBlob($nama, $harga, $status, $deskripsi, $blob)
    {
        $sql = "INSERT INTO {$this->table}
                (nama, harga, status, deskripsi, gambar, created_at)
                VALUES (:n,:h,:s,:d,:g,NOW())";
        $st = $this->conn->prepare($sql);
        $st->bindParam(':n', $nama);
        $st->bindParam(':h', $harga);
        $st->bindParam(':s', $status);
        $st->bindParam(':d', $deskripsi);
        // penting: simpan sebagai LOB
        $st->bindParam(':g', $blob, PDO::PARAM_LOB);
        return $st->execute();
    }

    public function deleteMenu($id)
    {
        $st = $this->conn->prepare("UPDATE {$this->table} SET status = 'diarsipkan' WHERE id_menu = :id");
        $st->bindParam(':id', $id, PDO::PARAM_INT);
        return $st->execute();
    }

    public function updateMenuAll($id, $nama, $harga, $status, $deskripsi, $blob = null)
    {
        if ($blob === null) {
            // update tanpa menyentuh kolom BLOB
            $sql = "UPDATE {$this->table}
                    SET nama = :n, harga = :h, status = :s, deskripsi = :d
                    WHERE id_menu = :id";
            $st  = $this->conn->prepare($sql);
            $st->bindParam(':n', $nama);
            $st->bindParam(':h', $harga);
            $st->bindParam(':s', $status);
            $st->bindParam(':d', $deskripsi);
            $st->bindParam(':id', $id, PDO::PARAM_INT);
            return $st->execute();
        } else {
            // update + ganti gambar (BLOB)
            $sql = "UPDATE {$this->table}
                    SET nama = :n, harga = :h, status = :s, deskripsi = :d, gambar = :g
                    WHERE id_menu = :id";
            $st  = $this->conn->prepare($sql);
            $st->bindParam(':n', $nama);
            $st->bindParam(':h', $harga);
            $st->bindParam(':s', $status);
            $st->bindParam(':d', $deskripsi);
            $st->bindParam(':g', $blob, PDO::PARAM_LOB);
            $st->bindParam(':id', $id, PDO::PARAM_INT);
            return $st->execute();
        }
    }
}
