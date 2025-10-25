<?php
// (Pastikan session_start() sudah ada di index.php)
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'pelanggan') {
    header('Location: index.php?action=login');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Pelanggan</title>
</head>
<body>
    <?php include './views/layout/navbar.php'; ?>

    <h1>Selamat Datang, <?= htmlspecialchars($_SESSION['user_username']); ?>!</h1>
    <h2>Ini Halaman Pemesanan (Pelanggan)</h2>
    <p>Di sini nanti kamu bisa letakkan menu-menu dan fitur pemesanan.</p>
    
    <br>
    <a href="index.php?action=logout">Logout</a>
</body>
</html>