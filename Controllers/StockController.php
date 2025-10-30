<?php
// controllers/StockController.php

include_once './config/Database.php';
include_once './models/StockModel.php';

class StockController
{

    private $db;
    private $stockModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
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

        $stockData = $this->stockModel->getAllStockWithMenuName();

        // Kirim data $stockData (bukan $menus) ke view
        include './views/stock/stock.php';
    }

    /**
     * Menangani update stok dari form.
     */
    public function handleStockUpdate()
    {
        $allowed_roles = ['pengadaan'];
        var_dump('Role saat update:', $_SESSION['user_role']);
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], $allowed_roles)) {
            header('Location: index.php?action=login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_menu']) && isset($_POST['stock'])) {
            $id_menu = $_POST['id_menu'];
            $stock_baru = (int)$_POST['stock'];
            $this->stockModel->updateStockByIdMenu($id_menu, $stock_baru);

            header('Location: index.php?action=stock&status=update_success');
            exit();
        }

        header('Location: index.php?action=stock');
        exit();
    }
}
