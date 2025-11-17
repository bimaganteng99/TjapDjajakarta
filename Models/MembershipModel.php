<?php
class MembershipModel
{
    private $db;

    public function __construct($conn)
    {
        $this->db = $conn;
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM membership ORDER BY id_member DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM membership WHERE id_member = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByAkunId($id_akun)
    {
        $stmt = $this->db->prepare("SELECT * FROM membership WHERE id_akun = ? LIMIT 1");
        $stmt->execute([$id_akun]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO membership (id_akun, tanggal_daftar, tier, poin, status, tanggal_kadaluwarsa) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['id_akun'],
            $data['tanggal_daftar'],
            $data['tier'],
            $data['poin'],
            $data['status'],
            $data['tanggal_kadaluwarsa']
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE membership SET tier=?, poin=?, status=?, tanggal_kadaluwarsa=? WHERE id_member=?");
        return $stmt->execute([
            $data['tier'],
            $data['poin'],
            $data['status'],
            $data['tanggal_kadaluwarsa'],
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM membership WHERE id_member=?");
        return $stmt->execute([$id]);
    }
}
