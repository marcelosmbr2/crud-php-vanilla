<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Database\Connection;

class Migrator {
    private PDO $db;
    private string $migrationsPath;
    
    public function __construct() {
        $this->db = Connection::getInstance();
        $this->migrationsPath = __DIR__ . '/database/migrations';
    }
    
    public function run(): void {
        echo "=== Executando Migrations ===\n\n";
        
        // Garante que a tabela de migrations existe
        $this->ensureMigrationsTable();
        
        // Pega todas as migrations
        $files = $this->getMigrationFiles();
        
        // Pega migrations já executadas
        $executed = $this->getExecutedMigrations();
        
        // Filtra apenas as pendentes
        $pending = array_diff($files, $executed);
        
        if (empty($pending)) {
            echo "✓ Nenhuma migration pendente.\n";
            return;
        }
        
        // Pega o próximo batch
        $batch = $this->getNextBatch();
        
        // Executa cada migration
        foreach ($pending as $file) {
            $this->runMigration($file, $batch);
        }
        
        echo "\n✓ Migrations executadas com sucesso!\n";
    }
    
    public function rollback(int $steps = 1): void {
        echo "=== Revertendo Migrations ===\n\n";
        
        // Pega o último batch
        $lastBatch = $this->getLastBatch();
        
        if (!$lastBatch) {
            echo "✓ Nenhuma migration para reverter.\n";
            return;
        }
        
        // Pega migrations do último batch
        $stmt = $this->db->prepare(
            "SELECT migration FROM migrations WHERE batch = :batch ORDER BY id DESC"
        );
        $stmt->execute(['batch' => $lastBatch]);
        $migrations = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Reverte cada migration
        foreach ($migrations as $migration) {
            $this->rollbackMigration($migration, $lastBatch);
        }
        
        echo "\n✓ Rollback executado com sucesso!\n";
    }
    
    public function reset(): void {
        echo "=== Resetando todas as Migrations ===\n\n";
        
        $stmt = $this->db->query("SELECT migration FROM migrations ORDER BY id DESC");
        $migrations = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        foreach ($migrations as $migration) {
            $this->rollbackMigration($migration);
        }
        
        echo "\n✓ Reset executado com sucesso!\n";
    }
    
    public function fresh(): void {
        echo "=== Recriando banco de dados ===\n\n";
        $this->reset();
        $this->run();
    }
    
    private function ensureMigrationsTable(): void {
        $sql = "
            CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL,
                batch INT NOT NULL,
                executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
        $this->db->exec($sql);
    }
    
    private function getMigrationFiles(): array {
        $files = scandir($this->migrationsPath);
        $migrations = [];
        
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $migrations[] = $file;
            }
        }
        
        sort($migrations);
        return $migrations;
    }
    
    private function getExecutedMigrations(): array {
        try {
            $stmt = $this->db->query("SELECT migration FROM migrations");
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (\PDOException $e) {
            return [];
        }
    }
    
    private function getNextBatch(): int {
        $stmt = $this->db->query("SELECT MAX(batch) as max_batch FROM migrations");
        $result = $stmt->fetch();
        return ($result['max_batch'] ?? 0) + 1;
    }
    
    private function getLastBatch(): ?int {
        $stmt = $this->db->query("SELECT MAX(batch) as max_batch FROM migrations");
        $result = $stmt->fetch();
        return $result['max_batch'] ?? null;
    }
    
    private function runMigration(string $file, int $batch): void {
        echo "Executando: {$file}\n";
        
        $migration = require $this->migrationsPath . '/' . $file;
        $migration->up();
        
        // Registra na tabela de migrations
        $stmt = $this->db->prepare(
            "INSERT INTO migrations (migration, batch) VALUES (:migration, :batch)"
        );
        $stmt->execute([
            'migration' => $file,
            'batch' => $batch
        ]);
    }
    
    private function rollbackMigration(string $file, ?int $batch = null): void {
        echo "Revertendo: {$file}\n";
        
        $migration = require $this->migrationsPath . '/' . $file;
        $migration->down();
        
        // Remove da tabela de migrations
        $stmt = $this->db->prepare("DELETE FROM migrations WHERE migration = :migration");
        $stmt->execute(['migration' => $file]);
    }
}

// CLI
$command = $argv[1] ?? 'run';

$migrator = new Migrator();

switch ($command) {
    case 'run':
    case 'migrate':
        $migrator->run();
        break;
    
    case 'rollback':
        $steps = (int)($argv[2] ?? 1);
        $migrator->rollback($steps);
        break;
    
    case 'reset':
        $migrator->reset();
        break;
    
    case 'fresh':
        $migrator->fresh();
        break;
    
    default:
        echo "Comandos disponíveis:\n";
        echo "  php migrate.php run      - Executa migrations pendentes\n";
        echo "  php migrate.php rollback - Reverte último batch\n";
        echo "  php migrate.php reset    - Reverte todas as migrations\n";
        echo "  php migrate.php fresh    - Reset + Run\n";
        break;
}