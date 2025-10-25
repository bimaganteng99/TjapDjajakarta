<?php
// (Pastikan session_start() sudah ada di index.php)
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
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
    <h1>Hi bos, <?= htmlspecialchars($_SESSION['user_username']); ?>!</h1>
    <h2>Ini Halaman Dashboard Admin</h2>
    <p>Di sini nanti kamu bisa arahkan ke halaman sesuai tugas staff.</p>
    
    <br>
    <a href="index.php?action=logout">Logout</a>
</body>
</html>