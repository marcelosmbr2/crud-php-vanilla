<?php
namespace App\Database;

use PDO;

abstract class Migration {
    protected PDO $db;
    
    public function __construct() {
        $this->db = Connection::getInstance();
    }
    
    abstract public function up(): void;
    abstract public function down(): void;
    
    protected function createTable(string $table, callable $callback): void {
        echo "Criando tabela: {$table}...\n";
        $callback($this);
    }
    
    protected function dropTable(string $table): void {
        echo "Removendo tabela: {$table}...\n";
        $this->db->exec("DROP TABLE IF EXISTS {$table}");
    }
}