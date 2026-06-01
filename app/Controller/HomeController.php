<?php

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Repository/ServiceRepository.php';

class HomeController
{
    public function index(): void
    {
        $conn = Database::connect();

        $serviceRepository = new ServiceRepository($conn);

        $services = $serviceRepository->findAll();

        require_once __DIR__ . '/../../views/home/index.php';
    }

    public function entreprise(): void
    {
        require_once __DIR__ . '/../../views/home/entreprise.php';
    }
}
