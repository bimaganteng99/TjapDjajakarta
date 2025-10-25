<?php
// config/Database.php

// Panggil file konfigurasi lokal
require_once 'koneksi-lokal.php';

class Database {
    // Ambil data dari file koneksi-lokal.php
    private $host = DB_HOST;
    private $db_name = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS;
    private $port = DB_PORT; // <-- TAMBAHKAN INI
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            // UBAH BARIS INI untuk menyertakan port
            $dsn = 'mysql:host=' . $this->host . ';port=' . $this->port . ';dbname=' . $this->db_name;
            $this->conn = new PDO($dsn, $this->username, $this->password);
            
            $this->conn->exec('set names utf8');
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo 'Connection error: ' . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>