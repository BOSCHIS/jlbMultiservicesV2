<?php

class ServiceRepository
{
    public function __construct(private mysqli $conn) {}

    public function findAll(): array
    {
        $result = $this->conn->query(
            "SELECT *
            FROM service
            ORDER BY id_service DESC"
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
        ?string $image
    ): bool {
        $stmt = $this->conn->prepare(
            "INSERT INTO service (title, slug, description_service, category_id, image)
        VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->bind_param("sssis", $title, $slug, $description, $categoryId, $image);

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

    public function update(
        int $id,
        int $categoryId,
        string $slug,
        string $title,
        string $description,
        ?string $image
    ): bool {
        $stmt = $this->conn->prepare(
            "UPDATE service
        SET 
            category_id = ?,
            slug = ?,
            title = ?,
            description_service = ?,
            image = ?
        WHERE id_service = ?"
        );

        $stmt->bind_param(
            "issssi",
            $categoryId,
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
