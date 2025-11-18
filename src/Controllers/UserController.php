<?php
namespace App\Controllers;

use App\Models\User;

class UserController {
    
    private User $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    public function index(): void {
        $users = $this->userModel->getAll();
        require __DIR__ . '/../../resources/views/users/index.php';
    }
    
    public function create(): void {
        require __DIR__ . '/../../resources/views/users/create.php';
    }
    
    public function store(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /users/create');
            exit;
        }
        
        $data = [
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? ''
        ];
        
        if (empty($data['name']) || empty($data['email'])) {
            $_SESSION['error'] = 'Nome e email são obrigatórios';
            header('Location: /users/create');
            exit;
        }
        
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Email inválido';
            header('Location: /users/create');
            exit;
        }
        
        if ($this->userModel->create($data)) {
            $_SESSION['success'] = 'Usuário criado com sucesso!';
            header('Location: /users');
            exit;
        }
        
        $_SESSION['error'] = 'Erro ao criar usuário';
        header('Location: /users/create');
        exit;
    }
    
    public function edit(int $id): void {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            $_SESSION['error'] = 'Usuário não encontrado';
            header('Location: /users');
            exit;
        }
        
        require __DIR__ . '/../../resources/views/users/edit.php';
    }
    
    public function update(int $id): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /users');
            exit;
        }
        
        $data = [
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? ''
        ];
        
        if (empty($data['name']) || empty($data['email'])) {
            $_SESSION['error'] = 'Nome e email são obrigatórios';
            header("Location: /users/edit/$id");
            exit;
        }
        
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Email inválido';
            header("Location: /users/edit/$id");
            exit;
        }
        
        if ($this->userModel->update($id, $data)) {
            $_SESSION['success'] = 'Usuário atualizado com sucesso!';
            header('Location: /users');
            exit;
        }
        
        $_SESSION['error'] = 'Erro ao atualizar usuário';
        header("Location: /users/edit/$id");
        exit;
    }
    
    public function delete(int $id): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /users');
            exit;
        }
        
        if ($this->userModel->delete($id)) {
            $_SESSION['success'] = 'Usuário deletado com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao deletar usuário';
        }
        
        header('Location: /users');
        exit;
    }
}