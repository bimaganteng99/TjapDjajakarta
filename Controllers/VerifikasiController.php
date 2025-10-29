<?php
// controllers/VerifikasiController.php

include_once './config/Database.php';
include_once './models/PesananModel.php';

class VerifikasiController
{

    private $db;
    private $pesananModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->pesananModel = new PesananModel($this->db);
    }

    /**
     * Menampilkan halaman Verifikasi.
     * Halaman ini akan menangani 2 kondisi:
     * 1. (GET) Menampilkan form pencarian kosong.
     * 2. (POST) Menampilkan hasil pencarian (jika ada POST 'kode_pesanan').
     */
    public function showVerifikasiPage()
    {
        // Keamanan: Hanya 'kasir'
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'kasir') {
            header('Location: index.php?action=login');
            exit();
        }

        $pesanan = null; // Data pesanan (jika ditemukan)
        $error = null;   // Pesan error (jika tidak valid)
        $success = $_GET['status'] ?? null; // Pesan sukses (setelah konfirmasi)

        // Logika untuk panah "TIDAK VALID" (jika ada pencarian)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kode_pesanan'])) {
            $kode_pesanan = $_POST['kode_pesanan'];
            $pesanan = $this->pesananModel->findByKode($kode_pesanan);

            // Cek validitas
            if (!$pesanan) {
                $error = "Error: Kode Pesanan '{$kode_pesanan}' tidak ditemukan.";
            } elseif ($pesanan['status_pesanan'] == 'menunggu') {
                $error = "Error: Pesanan ini masih 'menunggu' (belum diproses).";
            } elseif ($pesanan['status_pesanan'] == 'batal') {
                $error = "Error: Pesanan ini statusnya 'batal'.";
            }
            // Jika statusnya 'diproses', maka $pesanan akan lolos dan ditampilkan (VALID)
        }

        // Muat file View dan kirimkan data
        include './views/verifikasipickup/verifikasi_pickup.php';
    }

    /**
     * Menangani logika saat tombol "Konfirmasi Pick Up" ditekan.
     * Ini adalah logika panah "VALID"
     */
    public function konfirmasiPesanan()
    {
        // Keamanan: Hanya 'kasir'
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'kasir') {
            header('Location: index.php?action=login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_pesanan'])) {
            $id_pesanan = $_POST['id_pesanan'];

            // Ubah status menjadi 'selesai'
            $this->pesananModel->updateStatusPesanan($id_pesanan, 'diambil');

            // Kembalikan ke halaman verifikasi dengan pesan sukses
            header('Location: index.php?action=verifikasi_pickup&status=success');
            exit();
        }

        // Jika ada yang aneh, kembalikan saja
        header('Location: index.php?action=verifikasi_pickup');
        exit();
    }
}
