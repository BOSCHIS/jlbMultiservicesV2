<?php

require_once __DIR__ . '/../Entity/Contact.php';

class ContactRepository
{
    public function __construct(private mysqli $conn) {}

    public function save(Contact $contact): bool
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO contact
            (
                name_contact,
                address_contact,
                telephone_contact,
                service_requested,
                email_contact,
                message_contact
            )
            VALUES (?, ?, ?, ?, ?, ?)"
        );

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "ssssss",
            $contact->name,
            $contact->address,
            $contact->telephone,
            $contact->serviceRequested,
            $contact->email,
            $contact->message
        );

        return $stmt->execute();
    }

    public function findAll(): array
    {
        $result = $this->conn->query(
            "SELECT *
            FROM contact
            ORDER BY id_contact DESC"
        );

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare(
            "DELETE FROM contact
            WHERE id_contact = ?"
        );

        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }
}
