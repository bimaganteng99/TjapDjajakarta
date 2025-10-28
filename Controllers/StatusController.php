<?php
// controllers/StatusController.php

include_once './config/Database.php';
include_once './models/PesananModel.php';

class StatusController
{

    private $db;
    private $pesananModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->pesananModel = new PesananModel($this->db);
    }

    public function showStatusPage()
    {
        // 1. Keamanan (Cek role dulu)
        $allowed_roles = ['operasional']; // Sesuaikan jika rolenya beda
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], $allowed_roles)) {
            header('Location: index.php?action=login');
            exit();
        }

        // 2. Ambil data dari Model
        // INI ADALAH BARIS YANG MEMPERBAIKI ERROR 'null'
        $pesanan = $this->pesananModel->getAllPesanan();

        // 3. Load View dan kirimkan datanya
        // Variabel $pesanan sekarang sudah ada isinya (array)
        include './views/statuspesanan/status_pesanan.php';
    }

    public function updateStatusPesanan()
    {

        // 1. Keamanan: Cek role (sesuaikan jika rolenya beda)
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'operasional') {
            header('Content-Type: application/json');
            http_response_code(403); // Akses Ditolak
            echo json_encode(['success' => false, 'message' => 'Akses ditolak.']);
            exit;
        }

        // 2. Hanya izinkan metode POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            http_response_code(405); // Metode Tidak Diizinkan
            echo json_encode(['success' => false, 'message' => 'Metode tidak diizinkan.']);
            exit;
        }

        // 3. Ambil data mentah dari body request
        $raw_data = file_get_contents("php://input");
        $data = [];
        parse_str($raw_data, $data);

        // 4. Ambil nilainya dari array $data
        $id_pesanan = $data['id_pesanan'] ?? null;
        $status_pesanan = $data['status_pesanan'] ?? null;

        $result = false; // Siapkan hasil default

        // 5. Pastikan data tidak kosong sebelum update DB
        if ($id_pesanan && $status_pesanan) {
            // Panggil Model untuk update database
            $result = $this->pesananModel->updateStatusPesanan($id_pesanan, $status_pesanan);
        }

        // 6. Kirim balasan dalam format JSON kembali ke JavaScript
        header('Content-Type: application/json');
        echo json_encode(['success' => $result]);
        exit();
    }
}
