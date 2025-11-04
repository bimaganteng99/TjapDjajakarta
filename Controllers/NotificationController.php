<?php
// controllers/NotificationController.php

include_once './config/Database.php';
include_once './models/PesananModel.php';
include_once './models/BahanBakuModel.php';

class NotificationController
{

    private $db;
    private $pesananModel;
    private $bahanbakuModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->pesananModel = new PesananModel($this->db);
        $this->bahanbakuModel = new BahanBakuModel($this->db);
    }

    /**
     * API Endpoint untuk Polling
     * Mengambil semua pesanan dan mengirimnya sebagai JSON
     */
    public function getPesananUpdates()
    {

        // Keamanan: Pastikan hanya kasir yang bisa akses
        // (Atau role lain yang berhak melihat POS)
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'kasir') {
            header('Content-Type: application/json');
            http_response_code(403); // Akses Ditolak
            echo json_encode(['error' => 'Akses ditolak']);
            exit;
        }

        // Panggil model untuk ambil data
        $pesanan = $this->pesananModel->getAllPesanan();

        // Kirim data sebagai JSON
        header('Content-Type: application/json');
        echo json_encode($pesanan);
        exit();
    }

    public function getStockUpdates()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'pengadaan') {
            header('Content-Type: application/json');
            http_response_code(403); // Akses Ditolak
            echo json_encode(['error' => 'Akses ditolak']);
            exit;
        }

        // Panggil model BARU untuk ambil data stok + nama menu
        $stockData = $this->bahanbakuModel->getAllBahanBaku();

        // Kirim data sebagai JSON
        header('Content-Type: application/json');
        echo json_encode($stockData);
        exit();
    }
}
