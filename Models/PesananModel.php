<?php
// models/PemesananModel.php
class PesananModel
{
    private $conn;
    private $table = "pesanan";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Tambah pesanan baru
    public function tambahPesanan($id_akun, $id_menu, $jumlah, $total_harga, $jenis_pesanan, $catatan)
    {
        $query = "INSERT INTO {$this->table} 
              (id_akun, id_menu, jumlah, total_harga, jenis_pesanan, catatan, status_pesanan, created_at)
              VALUES (:id_akun, :id_menu, :jumlah, :total_harga, :jenis_pesanan, :catatan, 'menunggu', NOW())";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_akun', $id_akun);
        $stmt->bindParam(':id_menu', $id_menu);
        $stmt->bindParam(':jumlah', $jumlah);
        $stmt->bindParam(':total_harga', $total_harga);
        $stmt->bindParam(':jenis_pesanan', $jenis_pesanan);
        $stmt->bindParam(':catatan', $catatan);

        return $stmt->execute();
    }


    // Ambil semua pesanan
    public function getAllPesanan()
    {
        $query = "SELECT p.*, m.nama AS nama_menu, m.harga 
                  FROM {$this->table} p
                  JOIN menu m ON p.id_menu = m.id_menu
                  ORDER BY p.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
