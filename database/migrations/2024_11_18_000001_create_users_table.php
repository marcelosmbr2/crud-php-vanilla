<?php

use App\Database\Migration;

return new class extends Migration {
    public function up(): void {
        $config = require __DIR__ . '/../../config/database.php';
        
        if ($config['database'] === 'sqlite') {
            // Sintaxe para SQLite
            $sql = "
                CREATE TABLE IF NOT EXISTS users (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    name TEXT NOT NULL,
                    email TEXT NOT NULL UNIQUE,
                    password TEXT NULL,
                    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
                    updated_at TEXT DEFAULT CURRENT_TIMESTAMP
                )
            ";
            
            $this->db->exec($sql);
            
            // SQLite não suporta INDEX dentro do CREATE TABLE da mesma forma
            // Criamos o índice separadamente
            $this->db->exec("CREATE INDEX IF NOT EXISTS idx_email ON users(email)");
            
        } else {
            // Sintaxe para MySQL
            $sql = "
                CREATE TABLE IF NOT EXISTS users (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(100) NOT NULL,
                    email VARCHAR(100) NOT NULL UNIQUE,
                    password VARCHAR(255) NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    INDEX idx_email (email)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ";
            
            $this->db->exec($sql);
        }
        
        echo "✓ Tabela 'users' criada com sucesso!\n";
    }
    
    public function down(): void {
        $this->dropTable('users');
        echo "✓ Tabela 'users' removida com sucesso!\n";
    }
};