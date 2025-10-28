<?php
// index.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once './controllers/AuthController.php';
require_once './controllers/MenuController.php';
require_once './controllers/PosController.php';
require_once './controllers/PesananController.php';
include_once './controllers/StatusController.php';
include_once './controllers/NotificationController.php';
require_once './controllers/VerifikasiController.php';


$authController = new AuthController();
$menuController = new MenuController();
$posController = new PosController();
$pesananController = new PesananController();
$statusController = new StatusController();
$notificationController = new NotificationController();
$verifikasiController = new VerifikasiController();

// Ambil parameter action
$action = isset($_GET['action']) ? $_GET['action'] : 'login';

// Routing utama
switch ($action) {
    case 'login':
        $authController->showLogin();
        break;

    case 'register':
        $authController->showRegister();
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
        // INI YANG BENAR
        $statusController->showStatusPage();
        break;

    case 'update_status_pesanan':
        $statusController->updateStatusPesanan();
        break;

    case 'konfirmasi_pickup':
        $verifikasiController->konfirmasiPesanan();
        break;

    default:
        $authController->showLogin();
        break;
}
