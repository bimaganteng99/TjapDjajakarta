<?php
// controllers/StockController.php

include_once './config/Database.php';
// =========================================================
// 1. GANTI INCLUDE MODEL (dan hapus MenuModel jika tidak perlu lagi)
// =========================================================
include_once './models/StockModel.php';

class StockController
{

    private $db;
    // =========================================================
    // 2. GANTI PROPERTY MODEL
    // =========================================================
    private $stockModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        // =========================================================
        // 3. INISIALISASI MODEL BARU
        // =========================================================
        $this->stockModel = new StockModel($this->db);
    }

    /**
     * Menampilkan halaman manajemen stok.
     */
    public function showStockPage()
    {
        $allowed_roles = ['pengadaan'];
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], $allowed_roles)) {
            header('Location: index.php?action=login');
            exit();
        }

        // =========================================================
        // 4. PANGGIL MODEL BARU UNTUK AMBIL DATA
        // =========================================================
        $stockData = $this->stockModel->getAllStockWithMenuName();

        // Kirim data $stockData (bukan $menus) ke view
        include './views/stock/stock.php';
    }

    /**
     * Menangani update stok dari form.
     */
    public function handleStockUpdate()
    {
        $allowed_roles = ['pengadaan', 'manajer'];
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], $allowed_roles)) {
            header('Location: index.php?action=login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_menu']) && isset($_POST['stok'])) {
            $id_menu = $_POST['id_menu'];
            $stok_baru = (int)$_POST['stok'];

            // =========================================================
            // 5. PANGGIL MODEL BARU UNTUK UPDATE
            // =========================================================
            $this->stockModel->updateStockByIdMenu($id_menu, $stok_baru);

            header('Location: index.php?action=stock_page&status=update_success');
            exit();
        }

        header('Location: index.php?action=stock_page');
        exit();
    }
}
