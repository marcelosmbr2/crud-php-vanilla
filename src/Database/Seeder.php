<?php
namespace App\Database;

use PDO;

abstract class Seeder {
    protected PDO $db;
    protected string $driver;

    public function __construct() {
        $this->db = Connection::getInstance();
        $this->driver = $this->db->getAttribute(PDO::ATTR_DRIVER_NAME); 
    }

    abstract public function run(): void;

    /**
     * Trunca uma tabela de maneira compatível com MySQL e SQLite
     */
    protected function truncate(string $table): void {
        if ($this->driver === 'mysql') {
            // MySQL
            $this->db->exec("SET FOREIGN_KEY_CHECKS = 0");
            $this->db->exec("TRUNCATE TABLE {$table}");
            $this->db->exec("SET FOREIGN_KEY_CHECKS = 1");

        } elseif ($this->driver === 'sqlite') {
            // SQLite não suporta TRUNCATE nem FOREIGN_KEY_CHECKS
            $this->db->exec("PRAGMA foreign_keys = OFF");
            $this->db->exec("DELETE FROM {$table}");

            // Resetar autoincremento (SQLite armazena isso em sqlite_sequence)
            $this->db->exec("DELETE FROM sqlite_sequence WHERE name = '{$table}'");

            $this->db->exec("PRAGMA foreign_keys = ON");
        } else {
            throw new \Exception("Driver não suportado: {$this->driver}");
        }
    }

    /**
     * Insere um único registro
     */
    protected function insert(string $table, array $data): void {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
    }

    /**
     * Insere vários registros
     */
    protected function insertMany(string $table, array $rows): void {
        foreach ($rows as $row) {
            $this->insert($table, $row);
        }
    }

    /**
     * Executa outro seeder
     */
    protected function call(string $seederClass): void {
        echo "Executando: {$seederClass}\n";
        $seeder = new $seederClass();
        $seeder->run();
    }
}
