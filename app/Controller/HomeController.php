<?php

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Repository/CategoryRepository.php';
require_once __DIR__ . '/../Repository/CompanyRepository.php';
require_once __DIR__ . '/../Repository/RealisationRepository.php';

class HomeController
{
    public function index(): void
    {
        $conn = Database::connect();

        $categoryRepository = new CategoryRepository($conn);
        $categories = $categoryRepository->findActive();

        $realisationRepository = new RealisationRepository($conn);
        $realisations = $realisationRepository->findActiveForHome(20);

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
