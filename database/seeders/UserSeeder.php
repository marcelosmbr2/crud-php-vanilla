<?php

use App\Database\Seeder;

class UserSeeder extends Seeder {
    public function run(): void {
        echo "Populando tabela 'users'...\n";
        
        $this->truncate('users');
        
        $users = [
            [
                'name' => 'João Silva',
                'email' => 'joao@email.com',
                'password' => password_hash('senha123', PASSWORD_DEFAULT)
            ],
            [
                'name' => 'Maria Santos',
                'email' => 'maria@email.com',
                'password' => password_hash('senha123', PASSWORD_DEFAULT)
            ],
            [
                'name' => 'Pedro Oliveira',
                'email' => 'pedro@email.com',
                'password' => password_hash('senha123', PASSWORD_DEFAULT)
            ],
            [
                'name' => 'Ana Costa',
                'email' => 'ana@email.com',
                'password' => password_hash('senha123', PASSWORD_DEFAULT)
            ],
            [
                'name' => 'Carlos Souza',
                'email' => 'carlos@email.com',
                'password' => password_hash('senha123', PASSWORD_DEFAULT)
            ],
            [
                'name' => 'Juliana Lima',
                'email' => 'juliana@email.com',
                'password' => password_hash('senha123', PASSWORD_DEFAULT)
            ],
            [
                'name' => 'Roberto Alves',
                'email' => 'roberto@email.com',
                'password' => password_hash('senha123', PASSWORD_DEFAULT)
            ],
            [
                'name' => 'Fernanda Rocha',
                'email' => 'fernanda@email.com',
                'password' => password_hash('senha123', PASSWORD_DEFAULT)
            ],
            [
                'name' => 'Lucas Martins',
                'email' => 'lucas@email.com',
                'password' => password_hash('senha123', PASSWORD_DEFAULT)
            ],
            [
                'name' => 'Patricia Gomes',
                'email' => 'patricia@email.com',
                'password' => password_hash('senha123', PASSWORD_DEFAULT)
            ]
        ];
        
        $this->insertMany('users', $users);
        
        echo "✓ " . count($users) . " usuários inseridos com sucesso!\n";
    }
}