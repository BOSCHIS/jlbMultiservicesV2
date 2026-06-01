<?php

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Repository/ServiceRepository.php';

class ServiceController
{
    public function nettoyage(): void
    {
        echo "Page nettoyage";
    }

    public function bricolage(): void
    {
        echo "Page bricolage";
    }

    public function jardinage(): void
    {
        echo "Page jardinage";
    }

    public function debarras(): void
    {
        echo "Page débarras";
    }

    public function show(): void
    {
        $slug = trim($_GET['slug'] ?? '');

        if ($slug === '') {
            header('Location: /');
            exit;
        }

        $service = $this->repository->findBySlug($slug);

        if (!$service) {
            header('Location: /');
            exit;
        }

        require_once __DIR__ . '/../../views/services/show.php';
    }
}
