<?php
require_once __DIR__ . '/vendor/autoload.php';

// Autoload para os seeders
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/database/seeders/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

use App\Database\Connection;

class SeederRunner {
    private array $seeders = [];
    
    public function run(?string $seederClass = null): void {
        try {
            if ($seederClass) {
                $this->runSeeder($seederClass);
            } else {
                $this->runSeeder(DatabaseSeeder::class);
            }
        } catch (\Exception $e) {
            echo "❌ Erro: " . $e->getMessage() . "\n";
            exit(1);
        }
    }
    
    private function runSeeder(string $seederClass): void {
        if (!class_exists($seederClass)) {
            throw new \Exception("Seeder '{$seederClass}' não encontrado.");
        }
        
        $seeder = new $seederClass();
        $seeder->run();
    }
}

// CLI
$seederClass = $argv[1] ?? null;

$runner = new SeederRunner();
$runner->run($seederClass);