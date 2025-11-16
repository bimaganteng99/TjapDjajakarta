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
    private $bahanBakuModel; // (Tambahkan ini)
    private $resepModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->menuModel = new MenuModel($this->db);
        $this->pesananModel = new PesananModel($this->db);
        $this->bahanBakuModel = new BahanBakuModel($this->db);
        $this->resepModel = new ResepModel($this->db);
    }

    public function showPOSKasir()
    {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['kasir', 'pelanggan'])) {
            header('Location: index.php?action=login');
            exit();
        }

        // =========================================================
        // 3. LOGIKA "PINTAR" DIMULAI DI SINI
        // =========================================================

        // A. Ambil semua menu (versi "bodoh" dari DB)
        $semua_menu = $this->menuModel->getAllMenus();
        // B. Ambil semua stok bahan baku (array [id => stok])
        $stok_bahan = $this->bahanBakuModel->getAllBahanBakuIndexed();

        $menu_kalkulasi = []; // Ini akan jadi variabel $menus baru

        foreach ($semua_menu as $menu) {
            $id_menu = $menu['id_menu'];
            $status_kalkulasi = $menu['status']; // Ambil status manual

            // C. Cek resep hanya jika status manual 'tersedia'
            if ($status_kalkulasi == 'tersedia') {
                $resep = $this->resepModel->getResepByIdMenu($id_menu);

                if (!empty($resep)) {
                    // D. Cek stok bahan
                    foreach ($resep as $item) {
                        $id_bahan = $item['id_bahan'];
                        $butuh = (float)$item['jumlah_dibutuhkan'];
                        $tersedia = $stok_bahan[$id_bahan] ?? 0;

                        if ($tersedia < $butuh) {
                            $status_kalkulasi = 'habis'; // Timpa status jadi 'habis'
                            break;
                        }
                    }
                }
            }

            // E. Masukkan status baru (hasil kalkulasi) ke array
            $menu['status'] = $status_kalkulasi;
            $menu_kalkulasi[] = $menu;
        }

        // 4. KIRIM DATA "PINTAR" ($menus) KE VIEW
        $menus = $menu_kalkulasi;
        // =========================================================
        // LOGIKA "PINTAR" SELESAI
        // =========================================================

        // Ambil daftar pesanan (ini tidak berubah)
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
