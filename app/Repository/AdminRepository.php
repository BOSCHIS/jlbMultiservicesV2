<?php

class AdminRepository
{
    public function __construct(private mysqli $conn) {}

    public function findByUsername(string $username): ?array
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM administrator WHERE name_administrator = ?"
        );

        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();

        return $admin ?: null;
    }
}
