<?php

use App\Database\Migration;

return new class extends Migration {
    public function up(): void {
        $sql = "
            CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL,
                batch INT NOT NULL,
                executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ";
        
        $this->db->exec($sql);
        echo "✓ Tabela 'migrations' criada com sucesso!\n";
    }
    
    public function down(): void {
        $this->dropTable('migrations');
        echo "✓ Tabela 'migrations' removida com sucesso!\n";
    }
};