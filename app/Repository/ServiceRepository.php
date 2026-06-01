<?php

class ServiceRepository
{
    public function __construct(private mysqli $conn) {}

    public function findAll(): array
    {
        $result = $this->conn->query(
            "SELECT *
        FROM service
        ORDER BY display_order ASC, id_service ASC"
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
        (title, slug, description_service, category_id, display_order, image)
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
    public function findAllCategories(): array
    {
        $result = $this->conn->query(
            "SELECT * FROM category ORDER BY name_category ASC"
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


    public function shiftOrdersFrom(int $displayOrder): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE service
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
                "UPDATE service
            SET display_order = display_order + 1
            WHERE display_order >= ?
            AND display_order < ?
            AND id_service != ?"
            );

            $stmt->bind_param("iii", $newOrder, $oldOrder, $id);

            return $stmt->execute();
        }

        $stmt = $this->conn->prepare(
            "UPDATE service
        SET display_order = display_order - 1
        WHERE display_order <= ?
        AND display_order > ?
        AND id_service != ?"
        );

        $stmt->bind_param("iii", $newOrder, $oldOrder, $id);

        return $stmt->execute();
    }

    public function normalizeDisplayOrders(): bool
    {
        $result = $this->conn->query(
            "SELECT id_service
        FROM service
        ORDER BY display_order ASC, id_service ASC"
        );

        $services = $result->fetch_all(MYSQLI_ASSOC);

        $order = 1;

        foreach ($services as $service) {
            $stmt = $this->conn->prepare(
                "UPDATE service
            SET display_order = ?
            WHERE id_service = ?"
            );

            $id = (int) $service['id_service'];

            $stmt->bind_param("ii", $order, $id);
            $stmt->execute();

            $order++;
        }

        return true;
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
}
