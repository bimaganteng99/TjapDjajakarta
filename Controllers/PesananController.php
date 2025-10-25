<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once './config/Database.php';
include_once './models/PesananModel.php';
include_once './models/MenuModel.php';

class PesananController
{
    private $db;
    private $pesananModel;
    private $menuModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->pesananModel = new PesananModel($this->db);
        $this->menuModel = new MenuModel($this->db);
    }

    public function showPesananForm()
    {
        session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'kasir') {
            header('Location: index.php?action=login');
            exit();
        }

        $menus = $this->menuModel->getAvailableMenus();
        include './views/pos/pos_kasir.php';
    }

    public function handleTambahPesanan()
    {
        session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'kasir') {
            header('Location: index.php?action=login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_akun = $_SESSION['user_id'];
            $id_menu = $_POST['id_menu'];
            $jumlah = $_POST['jumlah'];
            $total = $_POST['total'];
            $jenis_pesanan = $_POST['jenis_pesanan'];
            $catatan = $_POST['catatan'];

            $this->pesananModel->tambahPesanan(
                $id_akun,
                $id_menu,
                $jumlah,
                $total,
                $jenis_pesanan,
                $catatan
            );
        }

        header('Location: index.php?action=pos_kasir');
        exit();
    }
}
