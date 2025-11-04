<?php
// controllers/ResepController.php

include_once './config/Database.php';
include_once './models/ResepModel.php';
include_once './models/MenuModel.php';      // Kita butuh ini untuk nama menu
include_once './models/BahanBakuModel.php'; // Kita butuh ini untuk dropdown

class ResepController {
    
    private $db;
    private $resepModel;
    private $menuModel;
    private $bahanBakuModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->resepModel = new ResepModel($this->db);
        $this->menuModel = new MenuModel($this->db);
        $this->bahanBakuModel = new BahanBakuModel($this->db);
    }

    /**
     * Menampilkan halaman "Edit Resep"
     */
    public function showResepPage() {
        // Keamanan (hanya manajer)
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'manajer') {
            header('Location: index.php?action=login');
            exit();
        }

        $id_menu = (int)($_GET['id'] ?? 0);
        if ($id_menu <= 0) {
            header('Location: index.php?action=manajemen_menu');
            exit();
        }

        // 1. Ambil info menu (misal: "Es Dawet")
        $menu = $this->menuModel->getMenuById($id_menu);

        // 2. Ambil resep saat ini untuk menu itu
        $resep_sekarang = $this->resepModel->getResepByIdMenu($id_menu);

        // 3. Ambil SEMUA bahan baku (untuk dropdown "Tambah Bahan")
        $semua_bahan_baku = $this->bahanBakuModel->getAllBahanBaku();

        // Muat view dan kirimkan ketiga data tersebut
        include './views/manajemen/edit_resep.php';
    }

    /**
     * Menangani form (Tambah Bahan / Hapus Bahan)
     */
    public function handleResepAction() {
        // Keamanan
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'manajer') {
            header('Location: index.php?action=login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
             header('Location: index.php?action=manajemen_menu');
             exit();
        }

        $id_menu = (int)($_POST['id_menu'] ?? 0);
        $action = $_POST['resep_action'] ?? null;

        if ($action === 'add') {
            // Logika tambah bahan ke resep
            $id_bahan = (int)($_POST['id_bahan'] ?? 0);
            $jumlah = (float)($_POST['jumlah_dibutuhkan'] ?? 0);

            if ($id_menu > 0 && $id_bahan > 0 && $jumlah > 0) {
                $this->resepModel->addBahanKeResep($id_menu, $id_bahan, $jumlah);
            }
        
        } elseif ($action === 'delete') {
            // Logika hapus bahan dari resep
            $id_resep = (int)($_POST['id_resep'] ?? 0);
            if ($id_resep > 0) {
                $this->resepModel->removeBahanDariResep($id_resep);
            }
        }

        // Redirect kembali ke halaman edit resep
        header('Location: index.php?action=edit_resep&id=' . $id_menu);
        exit();
    }
}
?>