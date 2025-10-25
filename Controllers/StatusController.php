<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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

    // Update status pesanan
    public function updateStatusPesanan()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_pesanan = $_POST['id_pesanan'];
            $status_pesanan = $_POST['status_pesanan'];

            $result = $this->pesananModel->updateStatusPesanan($id_pesanan, $status_pesanan);

            echo json_encode(['success' => $result]);
            exit;
        }
    }
}
