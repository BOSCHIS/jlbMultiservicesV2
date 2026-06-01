<?php

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Repository/ContactRepository.php';

class AdminContactController
{
    public function index(): void
    {
        if (empty($_SESSION['admin'])) {

            header('Location: /admin/login');

            exit;
        }

        $conn = Database::connect();

        $repository = new ContactRepository($conn);

        $contacts = $repository->findAll();

        require_once __DIR__ . '/../../views/admin/contacts/index.php';
    }

    public function delete(): void
    {
        if (empty($_SESSION['admin'])) {

            header('Location: /admin/login');

            exit;
        }

        $id = (int) ($_GET['id'] ?? 0);

        $conn = Database::connect();

        $repository = new ContactRepository($conn);

        $repository->delete($id);

        header('Location: /admin/contacts');

        exit;
    }
}
