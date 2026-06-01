<?php

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Repository/AdminRepository.php';
require_once __DIR__ . '/../Service/AdminAuthService.php';

class AdminAuthController
{
    public function login(): void
    {
        require_once __DIR__ . '/../../views/admin/login.php';
    }

    public function authenticate(): void
    {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        $conn = Database::connect();
        $repository = new AdminRepository($conn);
        $service = new AdminAuthService($repository);

        if (!$service->authenticate($username, $password)) {
            $_SESSION['error'] = "Identifiants invalides";
            header('Location: /admin/login');
            exit;
        }

        header('Location: /admin/dashboard');
        exit;
    }

    public function logout(): void
    {
        unset($_SESSION['admin']);
        session_destroy();

        header('Location: /admin/login');
        exit;
    }
}
