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
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'kasir' && $_SESSION['user_role'] !== 'pelanggan') {
            header('Location: index.php?action=login');
            exit();
        }

        // Ambil semua menu dari DB
        $menus = $this->menuModel->getAllMenus();
        $daftar_pesanan = $this->pesananModel->getAllPesanan();

        include './views/pos/pos_kasir.php';
    }

    public function tambahPesanan()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_akun = $_SESSION['user_id'];
            $id_menu = $_POST['id_menu'];
            $jumlah = $_POST['jumlah'];
            $jenis_pesanan = $_POST['jenis_pesanan'];
            $catatan = $_POST['catatan'];

            // (Logika menghitung total harga)
            // Asumsi kamu punya MenuModel untuk ambil harga
            $menu = $this->menuModel->getMenuById($id_menu);
            $total_harga = $menu['harga'] * $jumlah;

            // Ini akan membuat kode acak seperti "TJD-A3F1"
            $kode_pesanan = "TJD-" . strtoupper(substr(uniqid(), -4));

            // Panggil model dengan parameter baru
            $this->pesananModel->tambahPesanan(
                $id_akun,
                $id_menu,
                $jumlah,
                $total_harga,
                $jenis_pesanan,
                $catatan,
                $kode_pesanan // <-- Kirim kodenya
            );
        }
        header('Location: index.php?action=pos_kasir');
        exit();
    }
}
