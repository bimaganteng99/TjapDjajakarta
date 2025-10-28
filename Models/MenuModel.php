<?php
class MenuModel
{
    private $conn;
    private $table = 'menu';

    public function __construct($db) { $this->conn = $db; }

    /** Ambil semua menu – jangan tarik BLOB besar untuk list.
        Pakai flag has_gambar biar ringan. */
    public function getAllMenus()
    {
        $sql = "SELECT id_menu, nama, harga, status, deskripsi,
                       (gambar IS NOT NULL) AS has_gambar
                FROM {$this->table}
                ORDER BY nama ASC";
        $st = $this->conn->prepare($sql);
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Ambil satu menu (termasuk BLOB) */
    public function getMenuById($id_menu)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id_menu = :id LIMIT 1";
        $st  = $this->conn->prepare($sql);
        $st->bindParam(':id', $id_menu, PDO::PARAM_INT);
        $st->execute();
        return $st->fetch(PDO::FETCH_ASSOC);
    }

    /** Insert menu dengan BLOB */
    public function addMenuBlob($nama, $harga, $status, $deskripsi, $blob)
    {
        $sql = "INSERT INTO {$this->table}
                (nama, harga, status, deskripsi, gambar, created_at)
                VALUES (:n,:h,:s,:d,:g,NOW())";
        $st = $this->conn->prepare($sql);
        $st->bindParam(':n', $nama);
        $st->bindParam(':h', $harga);
        $st->bindParam(':s', $status);
        $st->bindParam(':d', $deskripsi);
        // penting: simpan sebagai LOB
        $st->bindParam(':g', $blob, PDO::PARAM_LOB);
        return $st->execute();
    }

    public function deleteMenu($id)
    {
        $st = $this->conn->prepare("DELETE FROM {$this->table} WHERE id_menu = :id");
        $st->bindParam(':id', $id, PDO::PARAM_INT);
        return $st->execute();
    }

    public function updateMenuAll($id, $nama, $harga, $status, $deskripsi, $blob = null)
    {
        if ($blob === null) {
            // update tanpa menyentuh kolom BLOB
            $sql = "UPDATE {$this->table}
                    SET nama = :n, harga = :h, status = :s, deskripsi = :d
                    WHERE id_menu = :id";
            $st  = $this->conn->prepare($sql);
            $st->bindParam(':n', $nama);
            $st->bindParam(':h', $harga);
            $st->bindParam(':s', $status);
            $st->bindParam(':d', $deskripsi);
            $st->bindParam(':id', $id, PDO::PARAM_INT);
            return $st->execute();
        } else {
            // update + ganti gambar (BLOB)
            $sql = "UPDATE {$this->table}
                    SET nama = :n, harga = :h, status = :s, deskripsi = :d, gambar = :g
                    WHERE id_menu = :id";
            $st  = $this->conn->prepare($sql);
            $st->bindParam(':n', $nama);
            $st->bindParam(':h', $harga);
            $st->bindParam(':s', $status);
            $st->bindParam(':d', $deskripsi);
            $st->bindParam(':g', $blob, PDO::PARAM_LOB);
            $st->bindParam(':id', $id, PDO::PARAM_INT);
            return $st->execute();
        }
    }

}
