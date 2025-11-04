<?php
// controllers/StockController.php

include_once './config/Database.php';
// 1. GANTI INCLUDE MODEL
include_once './models/BahanBakuModel.php'; 

class StockController {
    
    private $db;
    // 2. GANTI PROPERTY MODEL
    private $bahanBakuModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        // 3. INISIALISASI MODEL BARU
        $this->bahanBakuModel = new BahanBakuModel($this->db);
    }

    /**
     * Menampilkan halaman manajemen stok bahan baku.
     */
    public function showStockPage() {
        $allowed_roles = ['pengadaan', 'manajer']; 
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], $allowed_roles)) {
            header('Location: index.php?action=login');
            exit();
        }

        // 4. PANGGIL MODEL BARU UNTUK AMBIL DATA
        $bahanBaku = $this->bahanBakuModel->getAllBahanBaku(); 
        
        // Kirim data $bahanBaku ke view
        include './views/stock/stock.php';
    }

    /**
     * Menangani aksi dari halaman stok (Update atau Tambah Bahan Baru)
     */
    public function handleStockAction() {
        $allowed_roles = ['pengadaan', 'manajer']; 
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], $allowed_roles)) {
            header('Location: index.php?action=login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
             header('Location: index.php?action=stock');
             exit();
        }

        // Cek aksi apa yang diminta (Update Stok atau Tambah Bahan)
        $action = $_POST['stock_action'] ?? null;

        if ($action === 'update') {
            // Logika untuk update stok yang ada
            $id_bahan = $_POST['id_bahan'];
            $stok_baru = (float)$_POST['stok']; // Pakai float untuk desimal
            $this->bahanBakuModel->updateStokBahan($id_bahan, $stok_baru);
        
        } elseif ($action === 'add') {
            // Logika untuk menambah bahan baku baru
            $nama_bahan = $_POST['nama_bahan'];
            $stok_awal = (float)$_POST['stok_awal'];
            $satuan = $_POST['satuan'];
            $this->bahanBakuModel->addBahanBaku($nama_bahan, $stok_awal, $satuan);
        }

        // Redirect kembali ke halaman stok
        header('Location: index.php?action=stock');
        exit();
    }
}
?>