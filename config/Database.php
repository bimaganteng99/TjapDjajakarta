<?php
// config/Database.php
class Database {
    private $host = 'localhost';
    private $db_name = 'TjapDjajakarta'; // Sesuaikan nama DB
    private $username = 'root'; // Sesuaikan username DB
    private $password = ''; // Sesuaikan password DB
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
            $this->conn->exec('set names utf8');
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo 'Connection error: ' . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>