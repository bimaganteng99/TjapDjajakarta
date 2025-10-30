<?php

class StockModel
{
    private $conn;
    private $table = "stockmenu";
    private $menuTable = "menu"; 

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAllStockWithMenuName()
    {
        $query = "SELECT s.id_menu, m.nama AS nama_menu, s.jumlah_stock, m.status 
                  FROM {$this->table} s
                  JOIN {$this->menuTable} m ON s.id_menu = m.id_menu
                  ORDER BY m.nama ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStockByIdMenu($id_menu)
    {
        $query = "SELECT * FROM {$this->table} WHERE id_menu = :id_menu LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_menu', $id_menu, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateStockByIdMenu($id_menu, $jumlah_stok_baru)
    {
        // 1. Tentukan status baru berdasarkan stok
        $status_menu_baru = ($jumlah_stok_baru > 0) ? 'tersedia' : 'habis';

        // 2. Mulai transaksi (karena kita akan update 2 tabel)
        $this->conn->beginTransaction();

        try {
            // 3. Update tabel stockmenu
            $queryStock = "UPDATE {$this->table} SET jumlah_stock = :stock WHERE id_menu = :id_menu";
            $stmtStock = $this->conn->prepare($queryStock);
            $stmtStock->bindParam(':stock', $jumlah_stok_baru, PDO::PARAM_INT);
            $stmtStock->bindParam(':id_menu', $id_menu, PDO::PARAM_INT);
            $stockUpdated = $stmtStock->execute();

            // 4. Update tabel menu (status)
            $queryMenu = "UPDATE {$this->menuTable} SET status = :status WHERE id_menu = :id_menu";
            $stmtMenu = $this->conn->prepare($queryMenu);
            $stmtMenu->bindParam(':status', $status_menu_baru, PDO::PARAM_STR);
            $stmtMenu->bindParam(':id_menu', $id_menu, PDO::PARAM_INT);
            $menuUpdated = $stmtMenu->execute();

            // 5. Jika kedua update berhasil, simpan permanen
            if ($stockUpdated && $menuUpdated) {
                $this->conn->commit();
                return true;
            } else {
                // Jika salah satu gagal, batalkan semua
                $this->conn->rollBack();
                return false;
            }
        } catch (PDOException $e) {
            // Jika ada error, batalkan semua
            $this->conn->rollBack();
            // (Opsional: catat error $e->getMessage())
            return false;
        }
    }

    public function addInitialStock($id_menu)
    {
        $query = "INSERT INTO {$this->table} (id_menu, jumlah_stock) VALUES (:id_menu, 0)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_menu', $id_menu, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
