<head>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        nav {
            background: linear-gradient(135deg, #5B8A72, #A47148);
            padding: 20px;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
            margin: 0;
            padding: 0;
            justify-content: flex-end;
        }

        .tjap-name {
            margin-right: auto;
        }

        .tjap-name a {
            font-weight: bold;
        }

        nav ul li a {
            color: #f0f0f0;
            text-decoration: none;
            font-weight: 500;
            padding: 8px 14px;
            border-radius: 6px;
            transition: 0.3s ease;
        }

        nav ul li a:hover {
            background: rgba(0, 0, 0, 0.2);
            /* efek gelap lembut */
            backdrop-filter: blur(2px);
            /* opsional: efek modern */
        }


        nav ul li a.active {
            text-decoration: underline;
            text-underline-offset: 5px;
            font-weight: bold;
        }

        .logout {
            background: #B24A3F;
        }
    </style>
</head>

<body>
    <nav>
        <ul>
            <li class="tjap-name"><a>Tjap Djajakarta</a></li>

            <?php if ($_SESSION['user_role'] == 'manajer'): ?>
                <li><a class="<?= ($_GET['action'] == 'manajemen_menu') ? 'active' : '' ?>" href="index.php?action=manajemen_menu">Manajemen Menu</a></li>
                <li><a class="<?= ($_GET['action'] == 'manajemen_akun') ? 'active' : '' ?>" href="index.php?action=manajemen_akun">Manajemen Akun</a></li>
                <!-- <li><a href="index.php?action=edit_resep">Manajemen Resep</a></li> -->

            <?php elseif ($_SESSION['user_role'] == 'admin'): ?>
                <li><a class="<?= ($_GET['action'] == 'dashboardAdmin') ? 'active' : '' ?>" href="index.php?action=dashboardAdmin">Dashboard Admin</a></li>
                <li><a class="<?= ($_GET['action'] == 'data_user') ? 'active' : '' ?>" href="index.php?action=data_user">Data User</a></li>

            <?php elseif ($_SESSION['user_role'] == 'kasir'): ?>
                <li><a class="<?= ($_GET['action'] == 'pos_kasir') ? 'active' : '' ?>" href="index.php?action=pos_kasir">POS</a></li>
                <li><a class="<?= ($_GET['action'] == 'pembayaran') ? 'active' : '' ?>" href="index.php?action=pembayaran">Pembayaran</a></li>
                <li><a class="<?= ($_GET['action'] == 'verifikasi_pickup') ? 'active' : '' ?>" href="index.php?action=verifikasi_pickup">Verifikasi PickUp</a></li>
                <!-- <li><a href="index.php?action=status_pesanan">Status Pesanan</a></li> -->

            <?php elseif ($_SESSION['user_role'] == 'pelanggan'): ?>
                <li><a class="<?= ($_GET['action'] == 'pos_kasir') ? 'active' : '' ?>" href="index.php?action=pos_kasir">POS</a></li>
                <li><a class="<?= ($_GET['action'] == 'pembayaran') ? 'active' : '' ?>" href="index.php?action=pembayaran">Pembayaran</a></li>

            <?php elseif ($_SESSION['user_role'] == 'operasional'): ?>
                <!-- <li><a href="index.php?action=pos_kasir">POS</a></li> -->
                <li><a class="<?= ($_GET['action'] == 'status_pesanan') ? 'active' : '' ?>" href="index.php?action=status_pesanan">Status Pesanan</a></li>

            <?php elseif ($_SESSION['user_role'] == 'pengadaan'): ?>
                <!-- <li><a href="index.php?action=pos_kasir">POS</a></li> -->
                <li><a class="<?= ($_GET['action'] == 'stock') ? 'active' : '' ?>" href="index.php?action=stock">Kelola Stock</a></li>

            <?php endif; ?>
            <li><a class="logout" href="index.php?action=logout">Logout</a></li>
        </ul>
    </nav>
</body>