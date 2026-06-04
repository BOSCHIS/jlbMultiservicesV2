<?php

class CompanyRepository
{
    public function __construct(private mysqli $conn) {}

    public function findAll(): array
    {
        $result = $this->conn->query(
            "SELECT *
            FROM company_content
            ORDER BY display_order ASC, id_company_content ASC"
        );

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function findActive(): array
    {
        $result = $this->conn->query(
            "SELECT *
            FROM company_content
            WHERE is_active = 1
            ORDER BY display_order ASC, id_company_content ASC"
        );

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->conn->prepare(
            "SELECT *
            FROM company_content
            WHERE id_company_content = ?"
        );

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc() ?: null;
    }

    public function create(
        string $title,
        string $content,
        ?string $image,
        int $displayOrder,
        int $isActive
    ): bool {
        $stmt = $this->conn->prepare(
            "INSERT INTO company_content
            (
                title,
                content,
                image,
                display_order,
                is_active
            )
            VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "sssii",
            $title,
            $content,
            $image,
            $displayOrder,
            $isActive
        );

        return $stmt->execute();
    }

    public function update(
        int $id,
        string $title,
        string $content,
        ?string $image,
        int $displayOrder,
        int $isActive
    ): bool {
        $stmt = $this->conn->prepare(
            "UPDATE company_content
            SET
                title = ?,
                content = ?,
                image = ?,
                display_order = ?,
                is_active = ?
            WHERE id_company_content = ?"
        );

        $stmt->bind_param(
            "sssiii",
            $title,
            $content,
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
            "DELETE FROM company_content
            WHERE id_company_content = ?"
        );

        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    public function shiftOrdersFrom(int $displayOrder): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE company_content
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
                "UPDATE company_content
                SET display_order = display_order + 1
                WHERE display_order >= ?
                AND display_order < ?
                AND id_company_content != ?"
            );

            $stmt->bind_param("iii", $newOrder, $oldOrder, $id);

            return $stmt->execute();
        }

        $stmt = $this->conn->prepare(
            "UPDATE company_content
            SET display_order = display_order - 1
            WHERE display_order <= ?
            AND display_order > ?
            AND id_company_content != ?"
        );

        $stmt->bind_param("iii", $newOrder, $oldOrder, $id);

        return $stmt->execute();
    }

    public function normalizeDisplayOrders(): bool
    {
        $result = $this->conn->query(
            "SELECT id_company_content
            FROM company_content
            ORDER BY display_order ASC, id_company_content ASC"
        );

        $contents = $result->fetch_all(MYSQLI_ASSOC);

        $order = 1;

        foreach ($contents as $content) {
            $stmt = $this->conn->prepare(
                "UPDATE company_content
                SET display_order = ?
                WHERE id_company_content = ?"
            );

            $id = (int) $content['id_company_content'];

            $stmt->bind_param("ii", $order, $id);
            $stmt->execute();

            $order++;
        }

        return true;
    }
}
