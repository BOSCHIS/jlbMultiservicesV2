<?php

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Repository/ContactRepository.php';

class AdminContactController
{
    private function checkAdmin(): void
    {
        if (empty($_SESSION['admin'])) {
            header('Location: /admin/login');
            exit;
        }
    }

    public function index(): void
    {
        $this->checkAdmin();

        if (empty($_SESSION['admin_csrf_token'])) {
            $_SESSION['admin_csrf_token'] = bin2hex(random_bytes(32));
        }

        $conn = Database::connect();

        $repository = new ContactRepository($conn);

        $contacts = $repository->findAll();

        require_once __DIR__ . '/../../views/admin/contacts/index.php';
    }

    public function delete(): void
    {
        $this->checkAdmin();

        if (
            empty($_POST['csrf_token'])
            || empty($_SESSION['admin_csrf_token'])
            || !hash_equals($_SESSION['admin_csrf_token'], $_POST['csrf_token'])
        ) {
            header('Location: /admin/contacts');
            exit;
        }

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            header('Location: /admin/contacts');
            exit;
        }

        $conn = Database::connect();

        $repository = new ContactRepository($conn);

        $repository->delete($id);

        header('Location: /admin/contacts');
        exit;
    }
}
