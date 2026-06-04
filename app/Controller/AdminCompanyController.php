<?php

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Repository/CompanyRepository.php';

class AdminCompanyController
{
    private CompanyRepository $repository;

    public function __construct()
    {
        $conn = Database::connect();

        $this->repository = new CompanyRepository($conn);
    }

    private function checkAdmin(): void
    {
        if (empty($_SESSION['admin'])) {
            header('Location: /admin/login');
            exit;
        }
    }

    private function uploadCompanyImage(array $file): ?string
    {
        if (
            empty($file['name'])
            || $file['error'] !== UPLOAD_ERR_OK
        ) {
            return null;
        }

        $maxSize = 8 * 1024 * 1024; // 8 Mo

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

        $fileName = uniqid('company_', true) . '.' . $extension;
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

        $companyContents = $this->repository->findAll();

        require_once __DIR__ . '/../../views/admin/company/index.php';
    }

    public function create(): void
    {
        $this->checkAdmin();

        require_once __DIR__ . '/../../views/admin/company/create.php';
    }

    public function store(): void
    {
        $this->checkAdmin();

        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $displayOrder = (int) ($_POST['display_order'] ?? 1);
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if ($displayOrder < 1) {
            $displayOrder = 1;
        }

        $imageName = null;

        if (isset($_FILES['image'])) {
            $imageName = $this->uploadCompanyImage($_FILES['image']);
        }

        $this->repository->shiftOrdersFrom($displayOrder);

        $this->repository->create(
            $title,
            $content,
            $imageName,
            $displayOrder,
            $isActive
        );

        $this->repository->normalizeDisplayOrders();

        header('Location: /admin/entreprise');
        exit;
    }

    public function edit(): void
    {
        $this->checkAdmin();

        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            header('Location: /admin/entreprise');
            exit;
        }

        $companyContent = $this->repository->findById($id);

        if (!$companyContent) {
            header('Location: /admin/entreprise');
            exit;
        }

        require_once __DIR__ . '/../../views/admin/company/edit.php';
    }

    public function update(): void
    {
        $this->checkAdmin();

        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            header('Location: /admin/entreprise');
            exit;
        }

        $companyContent = $this->repository->findById($id);

        if (!$companyContent) {
            header('Location: /admin/entreprise');
            exit;
        }

        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $displayOrder = (int) ($_POST['display_order'] ?? 1);
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if ($displayOrder < 1) {
            $displayOrder = 1;
        }

        $oldOrder = (int) $companyContent['display_order'];
        $imageName = $companyContent['image'];

        if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
            $newImageName = $this->uploadCompanyImage($_FILES['image']);

            if ($newImageName !== null) {
                $uploadDirectory = __DIR__ . '/../../public/assets/media/';

                if (!empty($companyContent['image']) && $companyContent['image'] !== 'local.webp') {
                    $oldImagePath = $uploadDirectory . $companyContent['image'];

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
            $title,
            $content,
            $imageName,
            $displayOrder,
            $isActive
        );

        $this->repository->normalizeDisplayOrders();

        header('Location: /admin/entreprise');
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
            header('Location: /admin/entreprise');
            exit;
        }

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            header('Location: /admin/entreprise');
            exit;
        }

        $companyContent = $this->repository->findById($id);

        if ($companyContent && !empty($companyContent['image'])) {
            $imagePath = __DIR__ . '/../../public/assets/media/' . $companyContent['image'];

            if (
                file_exists($imagePath)
                && $companyContent['image'] !== 'local.webp'
            ) {
                unlink($imagePath);
            }
        }

        $this->repository->delete($id);

        $this->repository->normalizeDisplayOrders();

        header('Location: /admin/entreprise');
        exit;
    }
}
