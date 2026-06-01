<?php

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Repository/ContactRepository.php';
require_once __DIR__ . '/../Repository/ServiceRepository.php';
require_once __DIR__ . '/../Service/ContactService.php';

class ContactController
{
    public function index(): void
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $selectedService = null;

        $slug = trim($_GET['service'] ?? '');

        if ($slug !== '') {
            $conn = Database::connect();

            $serviceRepository = new ServiceRepository($conn);

            $selectedService = $serviceRepository->findBySlug($slug);

            $conn->close();
        }

        require_once __DIR__ . '/../../views/contact/index.php';
    }

    public function send(): void
    {
        header('Content-Type: application/json');

        $conn = Database::connect();

        $repository = new ContactRepository($conn);
        $service = new ContactService($repository);

        echo json_encode($service->process($_POST));

        $conn->close();
    }
}
