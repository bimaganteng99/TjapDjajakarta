<?php
// views/manajemenmenu/manajemen_akun.php

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'manajer') {
    header('Location: index.php?action=login');
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Manajemen Akun</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/menakunstyle.css">
</head>

<body>

    <?php include './views/layout/navbar.php'; ?>

    <div class="container">
        <h2>Manajemen Akun</h2>
        <form action="index.php?action=handleRegister" method="POST">
            <input type="hidden" name="source" value="manajer">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>

            <label for="username">Username</label>
            <input type="text" name="username" id="username" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <label for="role">Role</label>
            <select name="role" id="role" required>
                <option value="">-- Pilih Role --</option>
                <option value="admin">Admin</option>
                <option value="manajer">Manajer</option>
                <option value="operasional">Staff Operasional</option>
                <option value="pengadaan">Staff Pengadaan</option>
                <option value="marketing">Staff Marketing</option>
                <option value="kasir">Kasir</option>
                <option value="cs">Customer Service</option>
            </select>

            <button type="submit">Register Akun</button>
        </form>

        <a class="back" href="index.php?action=dashboardManajer">← Kembali ke Dashboard</a>
    </div>

</body>

</html>