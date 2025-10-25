<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once './config/Database.php';
include_once './models/MenuModel.php';

class MenuController
{
    private $db;
    private $menuModel;

    public function __construct()
    {
        // Pastikan session hanya dimulai sekali di index.php
        $database = new Database();
        $this->db = $database->getConnection();
        $this->menuModel = new MenuModel($this->db);
    }

    public function showManajemenMenu()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'manajer') {
            header('Location: index.php?action=login');
            exit();
        }

        $menus = $this->menuModel->getAllMenus();
        include './views/manajemen/manajemen_menu.php';
    }

    public function handleMenuAction()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'manajer') {
            header('Location: index.php?action=login');
            exit();
        }

        if (isset($_POST['menu_action'])) {
            $action = $_POST['menu_action'];

            if ($action === 'add') {
                $nama = $_POST['nama'];
                $harga = $_POST['harga'];
                $status = $_POST['status'];
                $deskripsi = $_POST['deskripsi'] ?? null;
                $gambar = $_POST['gambar'] ?? null;

                $this->menuModel->addMenu($nama, $harga, $status, $deskripsi, $gambar);
            } elseif ($action === 'delete') {
                $id = $_POST['id_menu'];
                $this->menuModel->deleteMenu($id);
            }
        }

        header('Location: index.php?action=manajemen_menu');
        exit();
    }
}
