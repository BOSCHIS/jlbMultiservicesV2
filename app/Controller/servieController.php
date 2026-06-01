<?php

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Repository/ServiceRepository.php';

class ServiceController
{
    private ServiceRepository $repository;

    public function __construct()
    {
        $conn = Database::connect();

        $this->repository = new ServiceRepository($conn);
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
            http_response_code(404);
            require_once __DIR__ . '/../../views/services/not-found.php';
            return;
        }

        require_once __DIR__ . '/../../views/services/show.php';
    }

    public function nettoyage(): void
    {
        header('Location: /service?slug=nettoyage');
        exit;
    }

    public function bricolage(): void
    {
        header('Location: /service?slug=bricolage');
        exit;
    }

    public function jardinage(): void
    {
        header('Location: /service?slug=jardinage');
        exit;
    }

    public function debarras(): void
    {
        header('Location: /service?slug=debarras');
        exit;
    }
}
