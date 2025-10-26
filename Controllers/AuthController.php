<?php
// controllers/AuthController.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


include_once './config/Database.php';
include_once './models/UserModel.php';
include_once './models/MenuModel.php';

class AuthController
{
    private $db;
    private $userModel;
    private $menuModel;
    private $pesananModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->userModel = new User($this->db);
        $this->pesananModel = new PesananModel($this->db);
    }

    public function showLogin()
    {
        include './views/auth/login.php';
    }

    public function showRegister()
    {
        include './views/auth/register.php';
    }

    // Memproses data dari form register
    public function handleRegister()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $this->userModel->email = $_POST['email'];
            $this->userModel->username = $_POST['username'];
            $this->userModel->password = $_POST['password'];
            $this->userModel->role = $_POST['role'] ?? 'pelanggan';

            $duplicate = $this->userModel->checkDuplicates($this->userModel->email, $this->userModel->username);

            if ($duplicate) {
                // Jika ada duplikat
                if ($duplicate == 'email') {
                    $error = "Email sudah terdaftar!";
                } else {
                    $error = "Username sudah terdaftar!";
                }

                // Jika manajer yang mendaftar, tetap di halaman manajemen akun
                if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'manajer') {
                    include './views/manajemen/manajemen_akun.php';
                } else {
                    include './views/auth/register.php';
                }
            } else {
                // Tidak ada duplikat → lanjut register
                if ($this->userModel->register()) {

                    // Jika manajer yang mendaftarkan akun baru
                    if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'manajer') {
                        $success = "Akun berhasil dibuat!";
                        include './views/manajemen/manajemen_akun.php';
                    } else {
                        // Kalau pelanggan yang daftar biasa → arahkan ke login
                        header('Location: index.php?action=login&status=reg_success');
                    }
                } else {
                    // Jika gagal register
                    $error = "Registrasi gagal!";
                    if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'manajer') {
                        include './views/manajemen/manajemen_akun.php';
                    } else {
                        include './views/auth/register.php';
                    }
                }
            }
        }
    }


    // Memproses data dari form login
    public function handleLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->userModel->login_identifier = $_POST['login_identifier'];
            $this->userModel->password = $_POST['password'];

            $user = $this->userModel->login();

            if ($user) {
                $_SESSION['user_id'] = $user['id_akun'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_username'] = $user['username'];

                if ($user['role'] == 'pelanggan') {
                    header('Location: index.php?action=dashboardPelanggan');
                } elseif ($user['role'] == 'admin') {
                    header('Location: index.php?action=dashboardAdmin');
                } elseif ($user['role'] == 'manajer') {
                    header('Location: index.php?action=dashboardManajer');
                } elseif ($user['role'] == 'operasional') {
                    header('Location: index.php?action=dashboardStaffOperasional');
                } elseif ($user['role'] == 'kasir') {
                    header('Location: index.php?action=pos_kasir');
                } elseif ($user['role'] == 'pengadaan') {
                    header('Location: index.php?action=dashboardStaffPengadaan');
                } elseif ($user['role'] == 'cs') {
                    header('Location: index.php?action=dashboardCS');
                } elseif ($user['role'] == 'marketing') {
                    header('Location: index.php?action=dashboardStaffMarketing');
                }
                exit();
            } else {
                $error = "Email/Username atau password salah!";
                include './views/auth/login.php';
            }
        }
    }

    // Proses logout (TIDAK BERUBAH)
    public function logout()
    {
        session_destroy();
        header('Location: index.php?action=login');
        exit();
    }

    // pelanggan
    public function showDashboardPelanggan()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'pelanggan') {
            header('Location: index.php?action=login');
            exit();
        }
        include './views/pemesanan/pemesanan.php';
    }

    // admin
    public function showDashboardAdmin()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
            header('Location: index.php?action=login');
            exit();
        }
        include './views/admin/admin.php';
    }

    // manajer
    public function showDashboardManajer()
    {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['manajer', 'kasir'])) {
            header('Location: index.php?action=login');
            exit();
        }

        $menuModel = new MenuModel($this->db);
        $menus = $menuModel->getAllMenus();

        include './views/manajemen/manajemen_menu.php';
    }
    public function showManajemenAkun()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'manajer') {
            header('Location: index.php?action=login');
            exit();
        }
        include './views/manajemen/manajemen_akun.php';
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

    // kasir
    public function showPembayaran()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit();
        }
        include './views/pembayaran/pembayaran.php';
    }
    // public function showVerfikasiPickUp()
    // {
    //     if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'kasir') {
    //         header('Location: index.php?action=login');
    //         exit();
    //     }
    //     include './views/verifikasipickup/verifikasi_pickup.php';
    // }
    public function showStatusPesanan()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'kasir') {
            header('Location: index.php?action=login');
            exit();
        }
        include './views/statuspesanan/status_pesanan.php';
    }
}
