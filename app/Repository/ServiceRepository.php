<?php

class ServiceRepository
{
    public function __construct(private mysqli $conn) {}

    public function findAll(): array
    {
        return $this->findAllForAdmin();
    }

    public function findAllForAdmin(?int $categoryId = null): array
    {
        if ($categoryId !== null) {
            $stmt = $this->conn->prepare(
                "SELECT service.*, category.name_category
                FROM service
                LEFT JOIN category
                    ON service.category_id = category.id_category
                WHERE service.category_id = ?
                ORDER BY service.display_order ASC, service.id_service ASC"
            );

            $stmt->bind_param("i", $categoryId);

            $stmt->execute();

            $result = $stmt->get_result();

            return $result->fetch_all(MYSQLI_ASSOC);
        }

        $result = $this->conn->query(
            "SELECT service.*, category.name_category
            FROM service
            LEFT JOIN category
                ON service.category_id = category.id_category
            ORDER BY category.display_order ASC, category.id_category ASC, service.display_order ASC, service.id_service ASC"
        );

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->conn->prepare(
            "SELECT *
            FROM service
            WHERE id_service = ?"
        );

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc() ?: null;
    }

    public function slugExists(string $slug, ?int $excludeId = null): bool
    {
        if ($excludeId !== null) {
            $stmt = $this->conn->prepare(
                "SELECT id_service
            FROM service
            WHERE slug = ?
            AND id_service != ?"
            );

            $stmt->bind_param("si", $slug, $excludeId);
        } else {
            $stmt = $this->conn->prepare(
                "SELECT id_service
            FROM service
            WHERE slug = ?"
            );

            $stmt->bind_param("s", $slug);
        }

        $stmt->execute();

        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    public function create(
        string $title,
        string $slug,
        string $description,
        int $categoryId,
        int $displayOrder,
        ?string $image
    ): bool {
        $stmt = $this->conn->prepare(
            "INSERT INTO service
            (
                title,
                slug,
                description_service,
                category_id,
                display_order,
                image
            )
            VALUES (?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "sssiis",
            $title,
            $slug,
            $description,
            $categoryId,
            $displayOrder,
            $image
        );

        return $stmt->execute();
    }

    public function generateUniqueSlug(string $slug, ?int $excludeId = null): string
    {
        $baseSlug = trim($slug);

        if ($baseSlug === '') {
            $baseSlug = 'prestation';
        }

        $uniqueSlug = $baseSlug;
        $counter = 2;

        while ($this->slugExists($uniqueSlug, $excludeId)) {
            $uniqueSlug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $uniqueSlug;
    }

    public function update(
        int $id,
        int $categoryId,
        int $displayOrder,
        string $slug,
        string $title,
        string $description,
        ?string $image
    ): bool {
        $stmt = $this->conn->prepare(
            "UPDATE service
            SET
                category_id = ?,
                display_order = ?,
                slug = ?,
                title = ?,
                description_service = ?,
                image = ?
            WHERE id_service = ?"
        );

        $stmt->bind_param(
            "iissssi",
            $categoryId,
            $displayOrder,
            $slug,
            $title,
            $description,
            $image,
            $id
        );

        return $stmt->execute();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare(
            "DELETE FROM service
            WHERE id_service = ?"
        );

        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    public function findAllCategories(): array
    {
        $result = $this->conn->query(
            "SELECT *
            FROM category
            ORDER BY display_order ASC, id_category ASC"
        );

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->conn->prepare(
            "SELECT *
            FROM service
            WHERE slug = ?"
        );

        $stmt->bind_param("s", $slug);

        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc() ?: null;
    }

    public function findCategoryBySlug(string $slug): ?array
    {
        $stmt = $this->conn->prepare(
            "SELECT *
            FROM category
            WHERE slug_category = ?"
        );

        $stmt->bind_param("s", $slug);

        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc() ?: null;
    }

    public function findByCategorySlug(string $slug): array
    {
        $stmt = $this->conn->prepare(
            "SELECT service.*
            FROM service
            INNER JOIN category
                ON service.category_id = category.id_category
            WHERE category.slug_category = ?
            ORDER BY service.display_order ASC, service.id_service ASC"
        );

        $stmt->bind_param("s", $slug);

        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function findActiveCategories(): array
    {
        $result = $this->conn->query(
            "SELECT *
            FROM category
            WHERE is_active = 1
            ORDER BY display_order ASC, id_category ASC"
        );

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function shiftOrdersFrom(int $categoryId, int $displayOrder): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE service
            SET display_order = display_order + 1
            WHERE category_id = ?
            AND display_order >= ?"
        );

        $stmt->bind_param("ii", $categoryId, $displayOrder);

        return $stmt->execute();
    }

    public function shiftOrdersForUpdate(
        int $id,
        int $categoryId,
        int $oldOrder,
        int $newOrder
    ): bool {
        if ($newOrder === $oldOrder) {
            return true;
        }

        if ($newOrder < $oldOrder) {
            $stmt = $this->conn->prepare(
                "UPDATE service
                SET display_order = display_order + 1
                WHERE category_id = ?
                AND display_order >= ?
                AND display_order < ?
                AND id_service != ?"
            );

            $stmt->bind_param("iiii", $categoryId, $newOrder, $oldOrder, $id);

            return $stmt->execute();
        }

        $stmt = $this->conn->prepare(
            "UPDATE service
            SET display_order = display_order - 1
            WHERE category_id = ?
            AND display_order <= ?
            AND display_order > ?
            AND id_service != ?"
        );

        $stmt->bind_param("iiii", $categoryId, $newOrder, $oldOrder, $id);

        return $stmt->execute();
    }

    public function normalizeDisplayOrdersByCategory(int $categoryId): bool
    {
        $stmt = $this->conn->prepare(
            "SELECT id_service
            FROM service
            WHERE category_id = ?
            ORDER BY display_order ASC, id_service ASC"
        );

        $stmt->bind_param("i", $categoryId);

        $stmt->execute();

        $result = $stmt->get_result();

        $services = $result->fetch_all(MYSQLI_ASSOC);

        $order = 1;

        foreach ($services as $service) {
            $updateStmt = $this->conn->prepare(
                "UPDATE service
                SET display_order = ?
                WHERE id_service = ?"
            );

            $id = (int) $service['id_service'];

            $updateStmt->bind_param("ii", $order, $id);
            $updateStmt->execute();

            $order++;
        }

        return true;
    }

    public function normalizeDisplayOrders(): bool
    {
        $categories = $this->findAllCategories();

        foreach ($categories as $category) {
            $this->normalizeDisplayOrdersByCategory((int) $category['id_category']);
        }

        return true;
    }
}
