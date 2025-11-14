<?php
// index.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once './models/BahanBakuModel.php';
require_once './models/ResepModel.php';
require_once './controllers/AuthController.php';
require_once './controllers/MenuController.php';
require_once './controllers/PosController.php';
include_once './controllers/StatusController.php';
include_once './controllers/NotificationController.php';
require_once './controllers/VerifikasiController.php';
require_once './controllers/StockController.php';
require_once './controllers/ResepController.php';


$authController = new AuthController();
$menuController = new MenuController();
$posController = new PosController();
$statusController = new StatusController();
$notificationController = new NotificationController();
$verifikasiController = new VerifikasiController();
$stockController = new StockController();
$resepController = new ResepController();

// Ambil parameter action
$action = isset($_GET['action']) ? $_GET['action'] : 'login';

// Routing utama
switch ($action) {
    case 'auth':
        $authController->showLogin();
        break;

    case 'handleRegister':
        $authController->handleRegister();
        break;

    case 'handleLogin':
        $authController->handleLogin();
        break;

    case 'logout':
        $authController->logout();
        break;

    // DASHBOARD
    case 'dashboardAdmin':
        $authController->showDashboardAdmin();
        break;
    case 'dashboardManajer':
        $authController->showDashboardManajer();
        break;
    // case 'dashboardPelanggan':
    //     $authController->showDashboardPelanggan();
    //     break;

    // FITUR KHUSUS
    case 'manajemen_akun':
        $authController->showManajemenAkun();
        break;
    case 'pembayaran':
        $authController->showPembayaran();
        break;
    case 'verifikasi_pickup':
        $verifikasiController->showVerifikasiPage(); // Panggil method baru
        break;
    case 'status_pesanan':
        $statusController->showStatusPage();
        break;

    // MENU
    case 'manajemen_menu':
        $menuController->showManajemenMenu();
        break;
    case 'handleMenu':
        $menuController->handleMenuAction();
        break;

    case 'image_menu':            // STREAM gambar dari BLOB
        $menuController->streamMenuImage();
        break;

    case 'edit_menu':
        $menuController->showEditMenu();      // form edit
        break;

    case 'update_menu':
        $menuController->updateMenu();        // submit edit
        break;

    case 'edit_resep': // Menampilkan halaman edit resep
        $resepController->showResepPage();
        break;
    case 'handle_resep': // Menangani tambah/hapus bahan resep
        $resepController->handleResepAction();
        break;


    // POS
    case 'pos_kasir':
        $posController->showPOSKasir();
        break;

    case 'pos_kasir':
        $pesananController->showPesananForm();
        break;

    case 'tambah_pesanan':
        $controller = new PosController();
        $controller->tambahPesanan();
        break;

    case 'get_pesanan_updates':
        $notificationController->getPesananUpdates();
        break;

    case 'dashboardStaffOperasional':
        $statusController->showStatusPage();
        break;

    case 'update_status_pesanan':
        $statusController->updateStatusPesanan();
        break;

    case 'konfirmasi_pickup':
        $verifikasiController->konfirmasiPesanan();
        break;

    //pengadaan
    case 'stock':
        $stockController->showStockPage();
        break;

    case 'handle_stock':
        $stockController->handleStockAction();
        break;


    //membership
    case 'membership':
        $controller = new MembershipController($conn);
        $controller->index();
        break;

    case 'tambah_member':
        $controller = new MembershipController($conn);
        $controller->tambah();
        break;

    case 'edit_member':
        $controller = new MembershipController($conn);
        $controller->edit();
        break;

    case 'hapus_member':
        $controller = new MembershipController($conn);
        $controller->hapus();
        break;

    default:
        $authController->showLogin();
        break;
}
