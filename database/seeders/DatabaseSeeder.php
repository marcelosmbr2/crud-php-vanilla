<?php

use App\Database\Seeder;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        echo "=== Iniciando Seeders ===\n\n";
        $this->call(UserSeeder::class);
        echo "\n✓ Seeders executados com sucesso!\n";
    }
}