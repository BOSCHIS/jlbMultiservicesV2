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

        $selectedCategoryId = !empty($_GET['category_id'])
            ? (int) $_GET['category_id']
            : null;

        $categories = $this->repository->findAllCategories();

        $services = $this->repository->findAllForAdmin($selectedCategoryId);

        require_once __DIR__ . '/../../views/admin/services/index.php';
    }

    public function create(): void
    {
        $this->checkAdmin();

        $categories = $this->repository->findAllCategories();

        require_once __DIR__ . '/../../views/admin/services/create.php';
    }

    private function uploadServiceImage(array $file): ?string
    {
        if (
            empty($file['name'])
            || $file['error'] !== UPLOAD_ERR_OK
        ) {
            return null;
        }

        $maxSize = 25 * 1024 * 1024;

        if ($file['size'] > $maxSize) {
            return null;
        }

        $uploadDirectory = __DIR__ . '/../../public/assets/uploads/services/';

        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0777, true);
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        $allowedMimeTypes = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedExtensions, true)) {
            return null;
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        if (!in_array($mimeType, $allowedMimeTypes, true)) {
            return null;
        }

        if (!getimagesize($file['tmp_name'])) {
            return null;
        }

        $fileName = uniqid('service_', true) . '.' . $extension;
        $destination = $uploadDirectory . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return null;
        }

        return $fileName;
    }

    public function store(): void
    {
        $this->checkAdmin();

        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $categoryId = (int) ($_POST['category_id'] ?? 0);
        $displayOrder = (int) ($_POST['display_order'] ?? 1);

        $slug = $this->repository->generateUniqueSlug($slug);

        if ($displayOrder < 1) {
            $displayOrder = 1;
        }

        if (
            $title === ''
            || $slug === ''
            || $description === ''
            || $categoryId <= 0
        ) {
            header('Location: /admin/service/create');
            exit;
        }

        $imagePath = null;

        if (isset($_FILES['image'])) {
            $imagePath = $this->uploadServiceImage($_FILES['image']);
        }

        $this->repository->shiftOrdersFrom($categoryId, $displayOrder);

        $this->repository->create(
            $title,
            $slug,
            $description,
            $categoryId,
            $displayOrder,
            $imagePath
        );

        $this->repository->normalizeDisplayOrdersByCategory($categoryId);

        header('Location: /admin/services?category_id=' . $categoryId);
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

        $oldCategoryId = (int) $service['category_id'];
        $oldOrder = (int) $service['display_order'];

        $newCategoryId = (int) ($_POST['category_id'] ?? 0);
        $newOrder = (int) ($_POST['display_order'] ?? 1);

        $slug = trim($_POST['slug'] ?? '');
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');

        $slug = $this->repository->generateUniqueSlug($slug, $id);

        if ($newOrder < 1) {
            $newOrder = 1;
        }

        if (
            $title === ''
            || $slug === ''
            || $description === ''
            || $newCategoryId <= 0
        ) {
            header('Location: /admin/service/edit?id=' . $id);
            exit;
        }

        $imageName = $service['image'];

        if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
            $newImageName = $this->uploadServiceImage($_FILES['image']);

            if ($newImageName !== null) {
                $uploadDirectory = __DIR__ . '/../../public/assets/uploads/services/';

                if (!empty($service['image'])) {
                    $oldImagePath = $uploadDirectory . $service['image'];

                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $imageName = $newImageName;
            }
        }

        if ($newCategoryId === $oldCategoryId) {
            $this->repository->shiftOrdersForUpdate(
                $id,
                $newCategoryId,
                $oldOrder,
                $newOrder
            );
        } else {
            $this->repository->shiftOrdersFrom($newCategoryId, $newOrder);
        }

        $this->repository->update(
            $id,
            $newCategoryId,
            $newOrder,
            $slug,
            $title,
            $description,
            $imageName
        );

        $this->repository->normalizeDisplayOrdersByCategory($oldCategoryId);
        $this->repository->normalizeDisplayOrdersByCategory($newCategoryId);

        header('Location: /admin/services?category_id=' . $newCategoryId);
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

        if (!$service) {
            header('Location: /admin/services');
            exit;
        }

        $categoryId = (int) $service['category_id'];

        if (!empty($service['image'])) {
            $imagePath = __DIR__ . '/../../public/assets/uploads/services/' . $service['image'];

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $this->repository->delete($id);

        $this->repository->normalizeDisplayOrdersByCategory($categoryId);

        header('Location: /admin/services?category_id=' . $categoryId);
        exit;
    }
}
