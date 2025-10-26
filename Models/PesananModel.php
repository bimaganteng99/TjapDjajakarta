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
    // 1. Tambahkan $kode_pesanan di parameter
    public function tambahPesanan($id_akun, $id_menu, $jumlah, $total_harga, $jenis_pesanan, $catatan, $kode_pesanan)
    {
        // 2. Tambahkan 'kode_pesanan' di query
        $query = "INSERT INTO {$this->table} 
                  (kode_pesanan, id_akun, id_menu, jumlah, total_harga, jenis_pesanan, catatan, status_pesanan, created_at)
                  VALUES (:kode_pesanan, :id_akun, :id_menu, :jumlah, :total_harga, :jenis_pesanan, :catatan, 'menunggu', NOW())";

        $stmt = $this->conn->prepare($query);

        // 3. Bind parameter baru
        $stmt->bindParam(':kode_pesanan', $kode_pesanan);
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
        // Query ini mengambil semua pesanan dan menggabungkannya dengan tabel 'menu'
        // untuk mendapatkan 'nama_menu'
        $query = "SELECT p.*, m.nama AS nama_menu, m.harga 
                  FROM {$this->table} p
                  JOIN menu m ON p.id_menu = m.id_menu
                  ORDER BY p.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        // fetchAll() akan mengembalikan array (meskipun kosong)
        // Ini akan memperbaiki error 'null'
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatusPesanan($id_pesanan, $status_pesanan)
    {
        $query = "UPDATE {$this->table} SET status_pesanan = :status_pesanan WHERE id_pesanan = :id_pesanan";

        try {
            $stmt = $this->conn->prepare($query);

            // Perbaikan Tipe Data: Paksa id_pesanan sebagai Angka (Integer)
            $stmt->bindParam(':id_pesanan', $id_pesanan, PDO::PARAM_INT);

            // Status tetap sebagai string (sudah benar)
            $stmt->bindParam(':status_pesanan', $status_pesanan, PDO::PARAM_STR);

            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    public function findByKode($kode_pesanan)
    {
        $query = "SELECT p.*, m.nama AS nama_menu
                  FROM {$this->table} p
                  JOIN menu m ON p.id_menu = m.id_menu
                  WHERE p.kode_pesanan = :kode_pesanan
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':kode_pesanan', $kode_pesanan, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return null; // Kembalikan null jika tidak ada
    }
}
