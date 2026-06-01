<?php

class AdminDashboardController
{
    public function index(): void
    {
        if (empty($_SESSION['admin'])) {
            header('Location: /admin/login');
            exit;
        }

        require_once __DIR__ . '/../../views/admin/dashboard.php';
    }
}
