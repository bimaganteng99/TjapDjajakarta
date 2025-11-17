<?php
require_once 'models/MembershipModel.php';

class MembershipController
{
    private $model;

    public function __construct($conn)
    {
        $this->model = new MembershipModel($conn);
    }

    public function index()
    {
        // Ambil membership milik user yang sedang login (berdasarkan id_akun)
        $id_akun = $_SESSION['user_id'];
        $member = $this->model->getByAkunId($id_akun);
        include 'views/membership/membership.php';
    }

    public function tambah()
    {
        $id_akun = $_SESSION['user_id'];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            $data['id_akun'] = $id_akun;
            $data['tanggal_daftar'] = date('Y-m-d');
            $this->model->create($data);
            header('Location: index.php?action=membership');
            exit();
        }
        // Tampilkan form tambah di halaman membership.php (tanpa redirect)
        $member = null;
        include 'views/membership/membership.php';
    }

    public function edit()
    {
        $id_akun = $_SESSION['user_id'];
        $member = $this->model->getByAkunId($id_akun);
        if (!$member) {
            header('Location: index.php?action=membership');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->update($member['id_member'], $_POST);
            header('Location: index.php?action=membership');
            exit();
        }
        include 'views/membership/membership.php';
    }

    public function hapus()
    {
        $id_akun = $_SESSION['user_id'];
        $member = $this->model->getByAkunId($id_akun);
        if ($member) {
            $this->model->delete($member['id_member']);
        }
        header('Location: index.php?action=membership');
        exit();
    }
}
