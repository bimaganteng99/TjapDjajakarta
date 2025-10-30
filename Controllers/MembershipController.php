<?php
require_once 'models/MembershipModel.php';

class MembershipController {
    private $model;

    public function __construct($conn) {
        $this->model = new MembershipModel($conn);
    }

    public function index() {
        $members = $this->model->getAll();
        include 'views/membership/daftar_membership.php';
    }

    public function tambah() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->create($_POST);
            header('Location: index.php?action=membership');
            exit();
        }
        include 'views/membership/tambah_member.php';
    }

    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?action=membership');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->update($id, $_POST);
            header('Location: index.php?action=membership');
            exit();
        }

        $member = $this->model->getById($id);
        include 'views/membership/edit_member.php';
    }

    public function hapus() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->model->delete($id);
        }
        header('Location: index.php?action=membership');
        exit();
    }
}
