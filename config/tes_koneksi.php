<?php
// config/tes-koneksi.php

$host = 'localhost';
$db   = 'TjapDjajakarta';
$user = 'root';
$pass = ''; // <-- GANTI DENGAN PASSWORD YANG KAMU YAKIN 100% BENAR
$port = '3307';      // <-- PASTIKAN PORT-MU BENAR
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
     // JIKA SAMPAI SINI, KONEKSI BERHASIL
     echo "<h1>✅ BERHASIL TERKONEKSI!</h1>";
     echo "Password dan Port kamu sudah benar.";

} catch (\PDOException $e) {
     // JIKA MASUK SINI, KONEKSI GAGAL
     echo "<h1>❌ GAGAL KONEKSI!</h1>";
     echo "Error: " . $e->getMessage();
     echo "<br><br>Ini artinya password, port, atau username kamu PASTI salah.";
}
?>