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
        $slug = $_GET['slug'] ?? '';

        $conn = Database::connect();
        $repository = new ServiceRepository($conn);

        $service = $repository->findBySlug($slug);

        if (!$service) {
            http_response_code(404);
            echo "Prestation introuvable";
            return;
        }

        require_once __DIR__ . '/../../views/services/show.php';
    }
}
