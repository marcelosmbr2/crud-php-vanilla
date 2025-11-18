<?php
namespace App\Database;

use PDO;

abstract class Seeder {
    protected PDO $db;
    
    public function __construct() {
        $this->db = Connection::getInstance();
    }
    
    abstract public function run(): void;
    
    protected function truncate(string $table): void {
        $this->db->exec("SET FOREIGN_KEY_CHECKS = 0");
        $this->db->exec("TRUNCATE TABLE {$table}");
        $this->db->exec("SET FOREIGN_KEY_CHECKS = 1");
    }
    
    protected function insert(string $table, array $data): void {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
    }
    
    protected function insertMany(string $table, array $rows): void {
        foreach ($rows as $row) {
            $this->insert($table, $row);
        }
    }
    
    protected function call(string $seederClass): void {
        echo "Executando: {$seederClass}\n";
        $seeder = new $seederClass();
        $seeder->run();
    }
}