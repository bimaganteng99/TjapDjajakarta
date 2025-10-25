<?php
// (Pastikan session_start() sudah ada di index.php)
$allowed_roles = ['kasir', 'operasional'];
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], $allowed_roles)) {
    header('Location: index.php?action=login');
    exit();
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Staff</title>
</head>
<body>
    <?php include './views/layout/navbar.php'; ?>

    <h1>Selamat Bekerja, <?= htmlspecialchars($_SESSION['user_username']); ?>!</h1>
    <h2>Ini Halaman Dashboard Staff Operasional</h2>
    <p>Di sini nanti kamu bisa arahkan ke halaman sesuai tugas staff.</p>
    
    <br>
    <a href="index.php?action=logout">Logout</a>
</body>
</html>