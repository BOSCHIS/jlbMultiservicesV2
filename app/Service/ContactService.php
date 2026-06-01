<?php

require_once __DIR__ . '/../Entity/Contact.php';
require_once __DIR__ . '/../Repository/ContactRepository.php';

class ContactService
{
    public function __construct(private ContactRepository $repository) {}

    public function process(array $data): array
    {
        $name = trim(strip_tags($data['name'] ?? ''));
        $address = trim(strip_tags($data['address'] ?? ''));
        $telephone = trim(strip_tags($data['tel'] ?? ''));
        $email = filter_var(trim($data['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $message = trim(strip_tags($data['message'] ?? ''));

        if (empty($name) || empty($email) || empty($message)) {
            return [
                'success' => false,
                'message' => 'Les champs obligatoires doivent être remplis.'
            ];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'success' => false,
                'message' => 'Format d’email invalide.'
            ];
        }

        if (strlen($name) < 2 || strlen($name) > 40) {
            return [
                'success' => false,
                'message' => 'Le nom doit contenir entre 2 et 40 caractères.'
            ];
        }

        if (strlen($message) < 10 || strlen($message) > 1000) {
            return [
                'success' => false,
                'message' => 'Le message doit contenir entre 10 et 1000 caractères.'
            ];
        }

        $contact = new Contact($name, $email, $message, $address, $telephone);

        if (!$this->repository->save($contact)) {
            return [
                'success' => false,
                'message' => 'Erreur lors de l’enregistrement de votre message.'
            ];
        }

        return [
            'success' => true,
            'message' => 'Votre message a été envoyé avec succès.'
        ];
    }
}
