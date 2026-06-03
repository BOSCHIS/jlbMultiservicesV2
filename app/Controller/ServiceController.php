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
            header('Location: /');
            exit;
        }

        require_once __DIR__ . '/../../views/services/show.php';
    }

    private function showCategory(string $slug): void
    {
        if (!$this->showDynamicCategory($slug)) {
            header('Location: /');
            exit;
        }
    }

    public function showDynamicCategory(string $slug): bool
    {
        $category = $this->repository->findCategoryBySlug($slug);

        if (!$category) {
            return false;
        }

        $services = $this->repository->findByCategorySlug($slug);

        require_once __DIR__ . '/../../views/services/category.php';

        return true;
    }
}
