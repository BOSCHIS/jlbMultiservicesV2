<?php

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Repository/CategoryRepository.php';

class HomeController
{
    public function index(): void
    {
        $conn = Database::connect();

        $categoryRepository = new CategoryRepository($conn);

        $categories = $categoryRepository->findActive();

        require_once __DIR__ . '/../../views/home/index.php';
    }

    public function entreprise(): void
    {
        require_once __DIR__ . '/../../views/home/entreprise.php';
    }
}
