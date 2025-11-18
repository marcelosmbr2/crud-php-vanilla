<?php
namespace App\Controllers;

use App\Models\User;

class HomeController {

    private User $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function index(): void {
        $users = $this->userModel->getAll();
        require __DIR__ . '/../../resources/views/home.php';
    }
}