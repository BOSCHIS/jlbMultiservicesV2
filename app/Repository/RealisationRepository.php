<?php

class RealisationRepository
{
    public function __construct(private mysqli $conn) {}

    public function findAll(): array
    {
        $result = $this->conn->query(
            "SELECT *
            FROM realisation
            ORDER BY display_order ASC, id_realisation ASC"
        );

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function findActiveForHome(int $limit = 3): array
    {
        $stmt = $this->conn->prepare(
            "SELECT *
            FROM realisation
            WHERE is_active = 1
            AND image_before IS NOT NULL
            AND image_before != ''
            AND image_after IS NOT NULL
            AND image_after != ''
            ORDER BY display_order ASC, id_realisation ASC
            LIMIT ?"
        );

        $stmt->bind_param("i", $limit);

        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->conn->prepare(
            "SELECT *
            FROM realisation
            WHERE id_realisation = ?"
        );

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc() ?: null;
    }

    public function create(
        string $title,
        string $imageBefore,
        string $imageAfter,
        int $displayOrder,
        int $isActive
    ): bool {
        $stmt = $this->conn->prepare(
            "INSERT INTO realisation
            (
                title,
                image_before,
                image_after,
                display_order,
                is_active
            )
            VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "sssii",
            $title,
            $imageBefore,
            $imageAfter,
            $displayOrder,
            $isActive
        );

        return $stmt->execute();
    }

    public function update(
        int $id,
        string $title,
        string $imageBefore,
        string $imageAfter,
        int $displayOrder,
        int $isActive
    ): bool {
        $stmt = $this->conn->prepare(
            "UPDATE realisation
            SET
                title = ?,
                image_before = ?,
                image_after = ?,
                display_order = ?,
                is_active = ?
            WHERE id_realisation = ?"
        );

        $stmt->bind_param(
            "sssiii",
            $title,
            $imageBefore,
            $imageAfter,
            $displayOrder,
            $isActive,
            $id
        );

        return $stmt->execute();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare(
            "DELETE FROM realisation
            WHERE id_realisation = ?"
        );

        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    public function shiftOrdersFrom(int $displayOrder): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE realisation
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
                "UPDATE realisation
                SET display_order = display_order + 1
                WHERE display_order >= ?
                AND display_order < ?
                AND id_realisation != ?"
            );

            $stmt->bind_param("iii", $newOrder, $oldOrder, $id);

            return $stmt->execute();
        }

        $stmt = $this->conn->prepare(
            "UPDATE realisation
            SET display_order = display_order - 1
            WHERE display_order <= ?
            AND display_order > ?
            AND id_realisation != ?"
        );

        $stmt->bind_param("iii", $newOrder, $oldOrder, $id);

        return $stmt->execute();
    }

    public function normalizeDisplayOrders(): bool
    {
        $result = $this->conn->query(
            "SELECT id_realisation
            FROM realisation
            ORDER BY display_order ASC, id_realisation ASC"
        );

        $realisations = $result->fetch_all(MYSQLI_ASSOC);

        $order = 1;

        foreach ($realisations as $realisation) {
            $stmt = $this->conn->prepare(
                "UPDATE realisation
                SET display_order = ?
                WHERE id_realisation = ?"
            );

            $id = (int) $realisation['id_realisation'];

            $stmt->bind_param("ii", $order, $id);
            $stmt->execute();

            $order++;
        }

        return true;
    }
}
