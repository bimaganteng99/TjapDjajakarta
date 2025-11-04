<?php
// models/ResepModel.php

class ResepModel {
    private $conn;
    private $table = "resep";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Mengambil resep lengkap (bahan & jumlah) untuk SATU menu
     */
    public function getResepByIdMenu($id_menu) {
        // Kita JOIN dengan tabel bahan_baku untuk dapat nama & satuannya
        $query = "SELECT 
                    r.id_resep, 
                    r.id_menu, 
                    r.id_bahan, 
                    r.jumlah_dibutuhkan,
                    b.nama_bahan,
                    b.satuan
                  FROM 
                    {$this->table} r
                  JOIN 
                    bahan_baku b ON r.id_bahan = b.id_bahan
                  WHERE 
                    r.id_menu = :id_menu";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_menu', $id_menu, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Menambahkan satu bahan baku ke dalam resep menu
     */
    public function addBahanKeResep($id_menu, $id_bahan, $jumlah) {
        $query = "INSERT INTO {$this->table} (id_menu, id_bahan, jumlah_dibutuhkan)
                  VALUES (:id_menu, :id_bahan, :jumlah)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_menu', $id_menu, PDO::PARAM_INT);
        $stmt->bindParam(':id_bahan', $id_bahan, PDO::PARAM_INT);
        $stmt->bindParam(':jumlah', $jumlah); // Biarkan PDO tentukan tipe (bisa desimal)
        
        return $stmt->execute();
    }

    /**
     * Menghapus satu bahan baku dari resep
     */
    public function removeBahanDariResep($id_resep) {
        $query = "DELETE FROM {$this->table} WHERE id_resep = :id_resep";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_resep', $id_resep, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>