<?php
// controllers/NotificationController.php

include_once './config/Database.php';
include_once './models/PesananModel.php';
include_once './models/MenuModel.php';
include_once './models/BahanBakuModel.php';
include_once './models/ResepModel.php';

class NotificationController
{
    private $db;
    private $pesananModel;
    private $bahanbakuModel;
    private $resepModel;
    private $menuModel; // (Property ini hilang)

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->pesananModel = new PesananModel($this->db);
        // PERBAIKAN TYPO: 'b' harus besar -> BahanBakuModel
        $this->bahanbakuModel = new BahanBakuModel($this->db);
        $this->resepModel = new ResepModel($this->db);
        // (Tambahkan inisialisasi MenuModel)
        $this->menuModel = new MenuModel($this->db);
    }

    /**
     * API Endpoint untuk Polling Pesanan
     */
    public function getPesananUpdates()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'kasir') {
            header('Content-Type: application/json');
            http_response_code(403); // Akses Ditolak
            echo json_encode(['error' => 'Akses ditolak']);
            exit;
        }

        $pesanan = $this->pesananModel->getAllPesanan();

        header('Content-Type: application/json');
        echo json_encode($pesanan);
        exit();
    }

    public function getStockUpdates()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'kasir') {
            header('Content-Type: application/json');
            http_response_code(403);
            echo json_encode(['error' => 'Akses ditolak']);
            exit;
        }

        // Ambil menu dengan status hasil perhitungan stok
        $menu_kalkulasi = $this->menuModel->getAllMenusWithCalculatedStock();

        header('Content-Type: application/json');
        echo json_encode($menu_kalkulasi);
        exit();
    }
}
