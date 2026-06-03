<?php

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Repository/CategoryRepository.php';

class AdminCategoryController
{
    private CategoryRepository $repository;

    public function __construct()
    {
        $conn = Database::connect();

        $this->repository = new CategoryRepository($conn);
    }

    private function checkAdmin(): void
    {
        if (empty($_SESSION['admin'])) {
            header('Location: /admin/login');
            exit;
        }
    }

    private function uploadCategoryImage(array $file): ?string
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

        $uploadDirectory = __DIR__ . '/../../public/assets/media/';

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

        $fileName = uniqid('category_', true) . '.' . $extension;
        $destination = $uploadDirectory . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return null;
        }

        return $fileName;
    }

    public function index(): void
    {
        $this->checkAdmin();

        if (empty($_SESSION['admin_csrf_token'])) {
            $_SESSION['admin_csrf_token'] = bin2hex(random_bytes(32));
        }

        $categories = $this->repository->findAll();

        require_once __DIR__ . '/../../views/admin/categories/index.php';
    }

    public function create(): void
    {
        $this->checkAdmin();

        require_once __DIR__ . '/../../views/admin/categories/create.php';
    }

    public function store(): void
    {
        $this->checkAdmin();

        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $displayOrder = (int) ($_POST['display_order'] ?? 1);
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if ($displayOrder < 1) {
            $displayOrder = 1;
        }

        $imageName = null;

        if (isset($_FILES['image'])) {
            $imageName = $this->uploadCategoryImage($_FILES['image']);
        }
        $this->repository->shiftOrdersFrom($displayOrder);

        $this->repository->create(
            $name,
            $slug,
            $description,
            $imageName,
            $displayOrder,
            $isActive
        );

        $this->repository->normalizeDisplayOrders();

        header('Location: /admin/categories');
        exit;
    }

    public function edit(): void
    {
        $this->checkAdmin();

        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            header('Location: /admin/categories');
            exit;
        }

        $category = $this->repository->findById($id);

        if (!$category) {
            header('Location: /admin/categories');
            exit;
        }

        require_once __DIR__ . '/../../views/admin/categories/edit.php';
    }

    public function update(): void
    {
        $this->checkAdmin();

        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            header('Location: /admin/categories');
            exit;
        }

        $category = $this->repository->findById($id);

        if (!$category) {
            header('Location: /admin/categories');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $displayOrder = (int) ($_POST['display_order'] ?? 1);
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if ($displayOrder < 1) {
            $displayOrder = 1;
        }

        $imageName = $category['image_category'];
        $oldOrder = (int) $category['display_order'];

        if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
            $newImageName = $this->uploadCategoryImage($_FILES['image']);

            if ($newImageName !== null) {
                $uploadDirectory = __DIR__ . '/../../public/assets/media/';

                if (!empty($category['image_category'])) {
                    $oldImagePath = $uploadDirectory . $category['image_category'];

                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $imageName = $newImageName;
            }
        }

        $this->repository->shiftOrdersForUpdate(
            $id,
            $oldOrder,
            $displayOrder
        );

        $this->repository->update(
            $id,
            $name,
            $slug,
            $description,
            $imageName,
            $displayOrder,
            $isActive
        );

        $this->repository->normalizeDisplayOrders();

        header('Location: /admin/categories');
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
            header('Location: /admin/categories');
            exit;
        }

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            header('Location: /admin/categories');
            exit;
        }

        $category = $this->repository->findById($id);

        if (!$category) {
            header('Location: /admin/categories');
            exit;
        }

        $servicesCount = $this->repository->countServicesByCategoryId($id);

        if ($servicesCount > 0) {
            $_SESSION['admin_error'] = "Impossible de supprimer cette catégorie : elle contient encore des prestations.";
            header('Location: /admin/categories');
            exit;
        }

        if (!empty($category['image_category'])) {
            $imagePath = __DIR__ . '/../../public/assets/media/' . $category['image_category'];

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $this->repository->delete($id);

        $this->repository->normalizeDisplayOrders();

        $_SESSION['admin_success'] = "La catégorie a bien été supprimée.";

        header('Location: /admin/categories');
        exit;
    }
}
