<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once './config/Database.php';
include_once './models/MenuModel.php';
include_once './models/StockModel.php';

class MenuController
{
    private $db;
    private $menuModel;
    private $stockModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->menuModel = new MenuModel($this->db);
        $this->stockModel = new StockModel($this->db);
    }

    /** Halaman Manajemen Menu (khusus manajer) */
    public function showManajemenMenu()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'manajer') {
            header('Location: index.php?action=login');
            exit();
        }

        $menus = $this->menuModel->getAllMenus();   // sudah termasuk flag has_gambar
        include './views/manajemen/manajemen_menu.php';
    }

    /** Terima tambah/hapus + upload file -> simpan ke BLOB */
    public function handleMenuAction()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'manajer') {
            header('Location: index.php?action=login');
            exit();
        }

        if (!isset($_POST['menu_action'])) {
            header('Location: index.php?action=manajemen_menu');
            exit();
        }

        $action = $_POST['menu_action'];

        if ($action === 'add') {
            $nama      = trim($_POST['nama'] ?? '');
            $harga     = (float)($_POST['harga'] ?? 0);
            $status    = trim($_POST['status'] ?? 'tersedia');
            $deskripsi = trim($_POST['deskripsi'] ?? '');

            // --- proses file (opsional) ---
            $blob = null;
            $mime = null;
            if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] !== UPLOAD_ERR_NO_FILE) {
                // ... (Logika validasi file kamu sudah benar) ...
                $blob = file_get_contents($_FILES['gambar']['tmp_name']);
            }

            // Panggil fungsi addMenuBlob
            $this->menuModel->addMenuBlob($nama, $harga, $status, $deskripsi, $blob);

            // Dapatkan ID dari menu yang BARU saja kamu buat
            $new_menu_id = $this->db->lastInsertId();

            if ($new_menu_id > 0) {
                // Panggil StockModel untuk membuat data stok awalnya (default 0)
                $this->stockModel->addInitialStock($new_menu_id);
            }

        }

        if ($action === 'delete') {
            $id = (int)($_POST['id_menu'] ?? 0);
            $this->menuModel->deleteMenu($id);
        }

        header('Location: index.php?action=manajemen_menu');
        exit();
    }

    /** STREAM gambar dari BLOB (tanpa kolom mime_type) */
    public function streamMenuImage()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(400);
            exit('Bad Request');
        }

        $menu = $this->menuModel->getMenuById($id);
        if (!$menu || empty($menu['gambar'])) {
            http_response_code(404);
            exit('Not Found');
        }

        // Deteksi MIME dari BLOB menggunakan finfo
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->buffer($menu['gambar']) ?: 'image/jpeg';

        header('Content-Type: ' . $mime);
        header('Cache-Control: public, max-age=86400');
        echo $menu['gambar']; // langsung output binary
        exit();
    }

    public function showEditMenu()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'manajer') {
            header('Location: index.php?action=login');
            exit();
        }

        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: index.php?action=manajemen_menu');
            exit();
        }

        $menu = $this->menuModel->getMenuById($id);
        if (!$menu) {
            header('Location: index.php?action=manajemen_menu');
            exit();
        }

        include './views/manajemen/edit_menu.php';
    }

    public function updateMenu()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'manajer') {
            header('Location: index.php?action=login');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=manajemen_menu');
            exit();
        }

        $id        = (int)($_POST['id_menu'] ?? 0);
        $nama      = trim($_POST['nama'] ?? '');
        $harga     = (float)($_POST['harga'] ?? 0);
        $status    = trim($_POST['status'] ?? 'tersedia');
        $deskripsi = trim($_POST['deskripsi'] ?? '');

        if ($id <= 0 || $nama === '' || $harga < 0) {
            header('Location: index.php?action=manajemen_menu');
            exit();
        }

        // gambar optional; jika tidak diupload, tetap pakai yang lama
        $blob = null;
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] !== UPLOAD_ERR_NO_FILE) {
            $f = $_FILES['gambar'];
            if ($f['error'] === UPLOAD_ERR_OK) {
                $allowed = ['image/jpeg', 'image/png', 'image/webp'];
                $mime = mime_content_type($f['tmp_name']);
                if (in_array($mime, $allowed) && $f['size'] <= 2 * 1024 * 1024) {
                    $blob = file_get_contents($f['tmp_name']); // BLOB baru
                }
            }
        }

        // kalau $blob null → tidak ganti gambar; kalau berisi → ganti gambar
        $this->menuModel->updateMenuAll($id, $nama, $harga, $status, $deskripsi, $blob);

        header('Location: index.php?action=manajemen_menu');
        exit();
    }
}
