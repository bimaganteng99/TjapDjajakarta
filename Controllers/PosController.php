<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once './config/Database.php';
include_once './models/MenuModel.php';
include_once './models/PesananModel.php';

class PosController
{
    private $db;
    private $menuModel;
    private $pesananModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->menuModel = new MenuModel($this->db);
        $this->pesananModel = new PesananModel($this->db);
    }

    public function showPOSKasir()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'kasir') {
            header('Location: index.php?action=login');
            exit();
        }

        // Ambil semua menu dari DB
        $menus = $this->menuModel->getAllMenus();

        include './views/pos/pos_kasir.php';
    }

    public function tambahPesanan()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_user = $_SESSION['user_id'];
            $id_menu = $_POST['id_menu'] ?? null;
            $jumlah = $_POST['jumlah'] ?? 0;
            $total_harga = $_POST['total_harga'] ?? 0;
            $jenis_pesanan = $_POST['jenis_pesanan'] ?? '';
            $catatan = $_POST['catatan'] ?? '';

            $pesananModel = new PesananModel($this->db);
            $result = $pesananModel->tambahPesanan(
                $id_user,
                $id_menu,
                $jumlah,
                $total_harga,
                $jenis_pesanan,
                $catatan
            );

            if ($result) {
                header('Location: index.php?action=pos_kasir');
                exit;
            } else {
                echo "<script>alert('Gagal menambahkan pesanan!'); history.back();</script>";
            }
        } else {
            include './views/pos/tambah_pesanan.php';
        }
    }
}
