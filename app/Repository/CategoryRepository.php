<?php

class CategoryRepository
{
    public function __construct(private mysqli $conn) {}

    public function findAll(): array
    {
        $result = $this->conn->query(
            "SELECT *
            FROM category
            ORDER BY display_order ASC, id_category ASC"
        );

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function findActive(): array
    {
        $result = $this->conn->query(
            "SELECT *
            FROM category
            WHERE is_active = 1
            ORDER BY display_order ASC, id_category ASC"
        );

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->conn->prepare(
            "SELECT *
            FROM category
            WHERE id_category = ?"
        );

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc() ?: null;
    }

    public function create(
        string $name,
        string $slug,
        string $description,
        ?string $image,
        int $displayOrder,
        int $isActive
    ): bool {
        $stmt = $this->conn->prepare(
            "INSERT INTO category
            (
                name_category,
                slug_category,
                description_category,
                image_category,
                display_order,
                is_active
            )
            VALUES (?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssssii",
            $name,
            $slug,
            $description,
            $image,
            $displayOrder,
            $isActive
        );

        return $stmt->execute();
    }

    public function update(
        int $id,
        string $name,
        string $slug,
        string $description,
        ?string $image,
        int $displayOrder,
        int $isActive
    ): bool {
        $stmt = $this->conn->prepare(
            "UPDATE category
            SET
                name_category = ?,
                slug_category = ?,
                description_category = ?,
                image_category = ?,
                display_order = ?,
                is_active = ?
            WHERE id_category = ?"
        );

        $stmt->bind_param(
            "ssssiii",
            $name,
            $slug,
            $description,
            $image,
            $displayOrder,
            $isActive,
            $id
        );

        return $stmt->execute();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare(
            "DELETE FROM category
            WHERE id_category = ?"
        );

        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    public function shiftOrdersFrom(int $displayOrder): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE category
        SET display_order = display_order + 1
        WHERE display_order >= ?"
        );

        $stmt->bind_param("i", $displayOrder);

        return $stmt->execute();
    }

    public function shiftOrdersForUpdate(int $id, int $oldOrder, int $newOrder): bool
    {
        if ($newOrder === $oldOrder) {
            return true;
        }

        if ($newOrder < $oldOrder) {
            $stmt = $this->conn->prepare(
                "UPDATE category
            SET display_order = display_order + 1
            WHERE display_order >= ?
            AND display_order < ?
            AND id_category != ?"
            );

            $stmt->bind_param("iii", $newOrder, $oldOrder, $id);

            return $stmt->execute();
        }

        $stmt = $this->conn->prepare(
            "UPDATE category
        SET display_order = display_order - 1
        WHERE display_order <= ?
        AND display_order > ?
        AND id_category != ?"
        );

        $stmt->bind_param("iii", $newOrder, $oldOrder, $id);

        return $stmt->execute();
    }

    public function normalizeDisplayOrders(): bool
    {
        $result = $this->conn->query(
            "SELECT id_category
        FROM category
        ORDER BY display_order ASC, id_category ASC"
        );

        $categories = $result->fetch_all(MYSQLI_ASSOC);

        $order = 1;

        foreach ($categories as $category) {
            $stmt = $this->conn->prepare(
                "UPDATE category
            SET display_order = ?
            WHERE id_category = ?"
            );

            $id = (int) $category['id_category'];

            $stmt->bind_param("ii", $order, $id);
            $stmt->execute();

            $order++;
        }

        return true;
    }

    public function countServicesByCategoryId(int $categoryId): int
    {
        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) AS total
        FROM service
        WHERE category_id = ?"
        );

        $stmt->bind_param("i", $categoryId);

        $stmt->execute();

        $result = $stmt->get_result();

        $row = $result->fetch_assoc();

        return (int) $row['total'];
    }
}
