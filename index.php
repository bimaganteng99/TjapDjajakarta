<?php
// index.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once './controllers/AuthController.php';
require_once './controllers/MenuController.php';
require_once './controllers/PosController.php';
require_once './controllers/PesananController.php';


$authController = new AuthController();
$menuController = new MenuController();
$posController = new PosController();
$pesananController = new PesananController();

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
    case 'dashboardPelanggan':
        $authController->showDashboardPelanggan();
        break;

    // FITUR KHUSUS
    case 'manajemen_akun':
        $authController->showManajemenAkun();
        break;
    case 'pembayaran':
        $authController->showPembayaran();
        break;
    case 'verifikasi_pickup':
        $authController->showVerfikasiPickUp();
        break;
    case 'status_pesanan':
        $authController->showStatusPesanan();
        break;

    // MENU
    case 'manajemen_menu':
        $menuController->showManajemenMenu();
        break;
    case 'handleMenu':
        $menuController->handleMenuAction();
        break;

    // POS
    case 'pos_kasir':
        $posController->showPOSKasir();
        break;

    case 'pos_kasir':
        $pesananController->showPesananForm();
        break;

    // case 'tambah_pemesanan':
    //     $pesananController->handleTambahPesanan();
    //     break;

    case 'tambah_pesanan':
        $controller = new PosController();
        $controller->tambahPesanan();
        break;

    default:
        $authController->showLogin();
        break;
}
