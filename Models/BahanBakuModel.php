<?php
// models/BahanBakuModel.php

class BahanBakuModel
{
    private $conn;
    private $table = "bahan_baku";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Mengambil semua bahan baku dari database
     */
    public function getAllBahanBaku()
    {
        $query = "SELECT * FROM {$this->table} ORDER BY nama_bahan ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Menambahkan bahan baku baru ke inventaris
     */
    public function addBahanBaku($nama, $stok, $satuan)
    {
        $query = "INSERT INTO {$this->table} (nama_bahan, jumlah_stok, satuan)
                  VALUES (:nama, :stok, :satuan)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':stok', $stok);
        $stmt->bindParam(':satuan', $satuan);

        return $stmt->execute();
    }

    /**
     * Mengupdate jumlah stok untuk satu bahan baku
     */
    public function updateStokBahan($id_bahan, $jumlah_stok_baru)
    {
        $query = "UPDATE {$this->table} SET jumlah_stok = :stok WHERE id_bahan = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':stok', $jumlah_stok_baru);
        $stmt->bindParam(':id', $id_bahan, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * (Opsional) Menghapus bahan baku
     */
    public function deleteBahanBaku($id_bahan)
    {
        // (Perhatian: Hapus ini juga akan menghapus resep yang terkait
        // jika 'ON DELETE CASCADE' diaktifkan di tabel resep)
        $query = "DELETE FROM {$this->table} WHERE id_bahan = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id_bahan, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Mengambil semua bahan baku, diindeks berdasarkan ID-nya
     * untuk pengecekan stok yang cepat.
     */
    public function getAllBahanBakuIndexed()
    {
        $stok_array = [];
        $query = "SELECT id_bahan, jumlah_stok FROM {$this->table}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $bahan_baku = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($bahan_baku as $bahan) {
            // Kunci array adalah ID bahan, nilainya adalah jumlah stok
            $stok_array[$bahan['id_bahan']] = (float)$bahan['jumlah_stok'];
        }
        return $stok_array;
    }
}
