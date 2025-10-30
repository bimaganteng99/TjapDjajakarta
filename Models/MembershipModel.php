<?php
class MembershipModel {
    private $db;

    public function __construct($conn) {
        $this->db = $conn;
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM membership ORDER BY id_member DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM membership WHERE id_member = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO membership (nama_member, email, no_hp, tier, poin, status) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['nama_member'],
            $data['email'],
            $data['no_hp'],
            $data['tier'],
            $data['poin'],
            $data['status']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE membership SET nama_member=?, email=?, no_hp=?, tier=?, poin=?, status=? WHERE id_member=?");
        return $stmt->execute([
            $data['nama_member'],
            $data['email'],
            $data['no_hp'],
            $data['tier'],
            $data['poin'],
            $data['status'],
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM membership WHERE id_member=?");
        return $stmt->execute([$id]);
    }
}
