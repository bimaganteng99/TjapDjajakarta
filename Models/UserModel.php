<?php
// models/User.php
class User {
    private $conn;
    private $table_name = 'akun';

    // Properti user
    public $id;
    public $email;
    public $username; 
    public $password;
    public $role;
    
    // Properti helper untuk login
    public $login_identifier; 

    public function __construct($db) {
        $this->conn = $db;
    }

    // Metode untuk mendaftarkan user baru
    // Query ini TIDAK BERUBAH
    public function register() {
        $query = 'INSERT INTO ' . $this->table_name . '
                  SET email = :email, username = :username, password = :password, role = :role';

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->role = htmlspecialchars(strip_tags($this->role));

        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);

        // Binding data
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $password_hash);
        $stmt->bindParam(':role', $this->role);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Metode untuk login user
    // Logika ini TIDAK BERUBAH
    public function login() {
        $user = $this->findByEmailOrUsername($this->login_identifier);

        if ($user) {
            if (password_verify($this->password, $user['password'])) {
                return $user;
            }
        }
        return false;
    }

    // =========================================================
    // PERUBAHAN DI SINI
    // =========================================================

    // HAPUS findByEmail()
    // HAPUS findByUsername()

    /**
     * Metode helper untuk mencari user berdasarkan email ATAU username
     * (HANYA DIGUNAKAN UNTUK LOGIN)
     */
    public function findByEmailOrUsername($identifier) {
        $query = 'SELECT id_akun, email, username, password, role
                  FROM ' . $this->table_name . '
                  WHERE email = :identifier OR username = :identifier
                  LIMIT 1';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':identifier', $identifier);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }

    /**
     * (METODE BARU)
     * Cek duplikat email atau username dalam satu query saat registrasi.
     * Mengembalikan string 'email' atau 'username' jika ada duplikat.
     * Mengembalikan false jika aman (tidak ada duplikat).
     */
    public function checkDuplicates($email, $username) {
        $query = 'SELECT email, username FROM ' . $this->table_name . '
                  WHERE email = :email OR username = :username';
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Ada duplikat, cari tahu yang mana
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                if ($row['email'] == $email) {
                    return 'email'; // Email duplikat
                }
                if ($row['username'] == $username) {
                    return 'username'; // Username duplikat
                }
            }
        }
        
        return false; // Aman
    }
}
?>