<?php
class MenuModel
{
    private $conn;
    private $table = 'menu';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAllMenus()
    {
        $query = "SELECT * FROM menu ORDER BY nama ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addMenu($nama, $harga, $status, $deskripsi, $gambar)
    {
        $query = "INSERT INTO {$this->table} (nama, harga, status, deskripsi, gambar, created_at)
                  VALUES (:nama, :harga, :status, :deskripsi, :gambar, NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':harga', $harga);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':deskripsi', $deskripsi);
        $stmt->bindParam(':gambar', $gambar);
        return $stmt->execute();
    }

    public function deleteMenu($id)
    {
        $query = "DELETE FROM {$this->table} WHERE id_menu = :id_menu";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_menu', $id);
        return $stmt->execute();
    }
    // models/MenuModel.php

    public function getAvailableMenus()
    {
        $query = "SELECT * FROM menu WHERE status = 'tersedia'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
