<?php

require_once __DIR__ . '/../Repository/AdminRepository.php';

class AdminAuthService
{
    public function __construct(private AdminRepository $repository) {}

    public function authenticate(string $username, string $password): bool
    {
        $admin = $this->repository->findByUsername($username);

        if (!$admin) {
            return false;
        }

        if (!password_verify($password, $admin['password_administrator'])) {
            return false;
        }

        $_SESSION['admin'] = [
            'id' => $admin['id_administrator'],
            'username' => $admin['name_administrator']
        ];

        return true;
    }
}
