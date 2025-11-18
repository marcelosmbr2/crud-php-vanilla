<?php
namespace App\Models;

use App\Database\Connection;
use PDO;

class User {
    private PDO $db;
    
    public function __construct() {
        $this->db = Connection::getInstance();
    }
    
    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM users ORDER BY id DESC");
        return $stmt->fetchAll();
    }
    
    public function find(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
    
    public function create(array $data): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO users (name, email) VALUES (:name, :email)"
        );
        return $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email']
        ]);
    }
    
    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare(
            "UPDATE users SET name = :name, email = :email WHERE id = :id"
        );
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'email' => $data['email']
        ]);
    }
    
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}