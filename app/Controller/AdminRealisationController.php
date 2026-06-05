<?php

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Repository/RealisationRepository.php';

class AdminRealisationController
{
    private RealisationRepository $repository;

    public function __construct()
    {
        $conn = Database::connect();

        $this->repository = new RealisationRepository($conn);
    }

    private function checkAdmin(): void
    {
        if (empty($_SESSION['admin'])) {
            header('Location: /admin/login');
            exit;
        }
    }

    private function uploadRealisationImage(array $file, string $prefix): ?string
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

        $uploadDirectory = __DIR__ . '/../../public/assets/uploads/realisations/';

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

        $fileName = uniqid($prefix . '_', true) . '.' . $extension;
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

        $realisations = $this->repository->findAll();

        require_once __DIR__ . '/../../views/admin/realisations/index.php';
    }

    public function create(): void
    {
        $this->checkAdmin();

        require_once __DIR__ . '/../../views/admin/realisations/create.php';
    }

    public function store(): void
    {
        $this->checkAdmin();


        $title = trim($_POST['title'] ?? '');
        $displayOrder = (int) ($_POST['display_order'] ?? 1);
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if ($displayOrder < 1) {
            $displayOrder = 1;
        }

        $imageBefore = null;
        $imageAfter = null;

        if (isset($_FILES['image_before'])) {
            $imageBefore = $this->uploadRealisationImage($_FILES['image_before'], 'before');
        }

        if (isset($_FILES['image_after'])) {
            $imageAfter = $this->uploadRealisationImage($_FILES['image_after'], 'after');
        }

        if (empty($title) || empty($imageBefore) || empty($imageAfter)) {
            $_SESSION['admin_error'] = "Le titre, l’image avant et l’image après sont obligatoires.";
            header('Location: /admin/realisations/create');
            exit;
        }

        $this->repository->shiftOrdersFrom($displayOrder);

        $this->repository->create(
            $title,
            $imageBefore,
            $imageAfter,
            $displayOrder,
            $isActive
        );

        $this->repository->normalizeDisplayOrders();

        $_SESSION['admin_success'] = "La réalisation a bien été ajoutée.";

        header('Location: /admin/realisations');
        exit;
    }

    public function edit(): void
    {
        $this->checkAdmin();

        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            header('Location: /admin/realisations');
            exit;
        }

        $realisation = $this->repository->findById($id);

        if (!$realisation) {
            header('Location: /admin/realisations');
            exit;
        }

        require_once __DIR__ . '/../../views/admin/realisations/edit.php';
    }

    public function update(): void
    {
        $this->checkAdmin();

        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            header('Location: /admin/realisations');
            exit;
        }

        $realisation = $this->repository->findById($id);

        if (!$realisation) {
            header('Location: /admin/realisations');
            exit;
        }

        $title = trim($_POST['title'] ?? '');
        $displayOrder = (int) ($_POST['display_order'] ?? 1);
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if ($displayOrder < 1) {
            $displayOrder = 1;
        }

        if (empty($title)) {
            $_SESSION['admin_error'] = "Le titre est obligatoire.";
            header('Location: /admin/realisations/edit?id=' . $id);
            exit;
        }

        $oldOrder = (int) $realisation['display_order'];

        $imageBefore = $realisation['image_before'];
        $imageAfter = $realisation['image_after'];

        $uploadDirectory = __DIR__ . '/../../public/assets/uploads/realisations/';

        if (isset($_FILES['image_before']) && !empty($_FILES['image_before']['name'])) {
            $newImageBefore = $this->uploadRealisationImage($_FILES['image_before'], 'before');

            if ($newImageBefore !== null) {
                $oldImagePath = $uploadDirectory . $imageBefore;

                if (!empty($imageBefore) && file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }

                $imageBefore = $newImageBefore;
            }
        }

        if (isset($_FILES['image_after']) && !empty($_FILES['image_after']['name'])) {
            $newImageAfter = $this->uploadRealisationImage($_FILES['image_after'], 'after');

            if ($newImageAfter !== null) {
                $oldImagePath = $uploadDirectory . $imageAfter;

                if (!empty($imageAfter) && file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }

                $imageAfter = $newImageAfter;
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
            $imageBefore,
            $imageAfter,
            $displayOrder,
            $isActive
        );

        $this->repository->normalizeDisplayOrders();

        $_SESSION['admin_success'] = "La réalisation a bien été modifiée.";

        header('Location: /admin/realisations');
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
            header('Location: /admin/realisations');
            exit;
        }

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            header('Location: /admin/realisations');
            exit;
        }

        $realisation = $this->repository->findById($id);

        if ($realisation) {
            $uploadDirectory = __DIR__ . '/../../public/assets/uploads/realisations/';

            $beforePath = $uploadDirectory . $realisation['image_before'];
            $afterPath = $uploadDirectory . $realisation['image_after'];

            if (!empty($realisation['image_before']) && file_exists($beforePath)) {
                unlink($beforePath);
            }

            if (!empty($realisation['image_after']) && file_exists($afterPath)) {
                unlink($afterPath);
            }
        }

        $this->repository->delete($id);

        $this->repository->normalizeDisplayOrders();

        $_SESSION['admin_success'] = "La réalisation a bien été supprimée.";

        header('Location: /admin/realisations');
        exit;
    }
}
