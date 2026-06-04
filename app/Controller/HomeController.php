<?php

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Repository/CategoryRepository.php';
require_once __DIR__ . '/../Repository/CompanyRepository.php';

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
        $conn = Database::connect();

        $companyRepository = new CompanyRepository($conn);

        $companyContents = $companyRepository->findActive();

        require_once __DIR__ . '/../../views/home/entreprise.php';
    }
}
