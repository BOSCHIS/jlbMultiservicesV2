<?php

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Repository/ServiceRepository.php';

class AdminServiceController
{
    private ServiceRepository $repository;

    public function __construct()
    {
        $conn = Database::connect();

        $this->repository = new ServiceRepository($conn);
    }

    private function checkAdmin(): void
    {
        if (empty($_SESSION['admin'])) {

            header('Location: /admin/login');

            exit;
        }
    }

    public function index(): void
    {
        $this->checkAdmin();

        if (empty($_SESSION['admin_csrf_token'])) {
            $_SESSION['admin_csrf_token'] = bin2hex(random_bytes(32));
        }

        $services = $this->repository->findAll();

        require_once __DIR__ . '/../../views/admin/services/index.php';
    }

    public function create(): void
    {
        $this->checkAdmin();

        $categories = $this->repository->findAllCategories();

        require_once __DIR__ . '/../../views/admin/services/create.php';
    }

    public function store(): void
    {
        $this->checkAdmin();

        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $categoryId = (int) ($_POST['category_id'] ?? 0);
        $displayOrder = (int) ($_POST['display_order'] ?? 1);

        if ($displayOrder < 1) {
            $displayOrder = 1;
        }

        $imagePath = null;

        if (!empty($_FILES['image']['name'])) {
            $uploadDir = __DIR__ . '/../../public/assets/uploads/services/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

            if (in_array($extension, $allowedExtensions, true)) {
                $fileName = uniqid('service_', true) . '.' . $extension;

                $destination = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                    $imagePath = $fileName;
                }
            }
        }

        $this->repository->shiftOrdersFrom($displayOrder);

        $this->repository->create(
            $title,
            $slug,
            $description,
            $categoryId,
            $displayOrder,
            $imagePath
        );

        header('Location: /admin/services');
        exit;
    }

    public function edit(): void
    {
        $this->checkAdmin();

        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            header('Location: /admin/services');
            exit;
        }

        $service = $this->repository->findById($id);

        if (!$service) {
            header('Location: /admin/services');
            exit;
        }

        $categories = $this->repository->findAllCategories();

        require_once __DIR__ . '/../../views/admin/services/edit.php';
    }

    public function update(): void
    {
        $this->checkAdmin();

        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            header('Location: /admin/services');
            exit;
        }

        $service = $this->repository->findById($id);

        if (!$service) {
            header('Location: /admin/services');
            exit;
        }

        $categoryId = (int) ($_POST['category_id'] ?? 0);
        $displayOrder = (int) ($_POST['display_order'] ?? 1);
        $slug = trim($_POST['slug'] ?? '');
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($displayOrder < 1) {
            $displayOrder = 1;
        }

        $oldOrder = (int) $service['display_order'];
        $imageName = $service['image'];

        if (
            isset($_FILES['image'])
            && $_FILES['image']['error'] === UPLOAD_ERR_OK
            && !empty($_FILES['image']['name'])
        ) {
            $uploadDirectory = __DIR__ . '/../../public/assets/uploads/services/';

            if (!is_dir($uploadDirectory)) {
                mkdir($uploadDirectory, 0777, true);
            }

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            $originalName = $_FILES['image']['name'];
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

            if (in_array($extension, $allowedExtensions, true)) {
                $newImageName = uniqid('service_', true) . '.' . $extension;
                $uploadPath = $uploadDirectory . $newImageName;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                    if (!empty($service['image'])) {
                        $oldImagePath = $uploadDirectory . $service['image'];

                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }

                    $imageName = $newImageName;
                }
            }
        }

        $this->repository->shiftOrdersForUpdate(
            $id,
            $oldOrder,
            $displayOrder
        );

        $this->repository->update(
            $id,
            $categoryId,
            $displayOrder,
            $slug,
            $title,
            $description,
            $imageName
        );

        $this->repository->normalizeDisplayOrders();

        header('Location: /admin/services');
        exit;
    }

    public function delete(): void
    {
        $this->checkAdmin();

        if (
            empty($_POST['csrf_token'])
            || empty($_SESSION['admin_csrf_token'])
            || !hash_equals($_SESSION['admin_csrf_token'], $_POST['csrf_token'])
        ) {
            header('Location: /admin/services');
            exit;
        }

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            header('Location: /admin/services');
            exit;
        }

        $service = $this->repository->findById($id);

        if ($service && !empty($service['image'])) {
            $imagePath = __DIR__ . '/../../public/assets/uploads/services/' . $service['image'];

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $this->repository->delete($id);

        $this->repository->normalizeDisplayOrders();

        header('Location: /admin/services');
        exit;
    }
}
