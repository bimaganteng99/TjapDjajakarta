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
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin-top: 10px;
            color: #444;
        }

        input,
        select {
            width: 100%;
            padding: 8px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .back {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: #555;
        }

        .back:hover {
            text-decoration: underline;
        }
    </style>
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