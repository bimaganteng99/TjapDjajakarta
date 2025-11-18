<?php
require_once './models/PesananModel.php';

class PembayaranController
{
    private $pesananModel;
    private $db;

    public function __construct($conn)
    {
        $this->db = $conn;
        $this->pesananModel = new PesananModel($conn);
    }

    // Tampilkan halaman pembayaran
    public function showPembayaran()
    {
        if ($_SESSION['user_role'] == 'kasir') {
            // Kasir: ambil semua pesanan menunggu pembayaran
            $pesanan_kasir = $this->pesananModel->getSemuaPesananMenungguPembayaran();
            $pesanan = [];
        } else {
            // Pelanggan: hanya pesanan miliknya
            $id_akun = $_SESSION['user_id'];
            $pesanan = $this->pesananModel->getPesananMenungguPembayaran($id_akun);
            $pesanan_kasir = [];
        }
        include './views/pembayaran/pembayaran.php';
    }

    // Proses pembayaran
    public function prosesPembayaran()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_pesanan = $_POST['id_pesanan'];
            $metode = $_POST['metode_pembayaran'];
            // Update status pesanan
            $this->pesananModel->updateStatusPesanan($id_pesanan, 'selesai dibayar');
            // (opsional: simpan metode pembayaran di kolom baru jika ada)
            header('Location: index.php?action=pembayaran&success=1');
            exit();
        }
        header('Location: index.php?action=pembayaran');
        exit();
    }
}
