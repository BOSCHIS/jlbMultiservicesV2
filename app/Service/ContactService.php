<?php

require_once __DIR__ . '/../Entity/Contact.php';
require_once __DIR__ . '/../Repository/ContactRepository.php';
require_once __DIR__ . '/MailService.php';

class ContactService
{
    public function __construct(private ContactRepository $repository) {}

    public function process(array $data): array
    {
        if (
            empty($data['csrf_token'])
            || empty($_SESSION['csrf_token'])
            || !hash_equals($_SESSION['csrf_token'], $data['csrf_token'])
        ) {
            return [
                'success' => false,
                'message' => 'Requête invalide.'
            ];
        }

        if (!empty($data['website'] ?? '')) {
            return [
                'success' => false,
                'message' => 'Requête invalide.'
            ];
        }

        if (empty($data['cgu'])) {
            return [
                'success' => false,
                'message' => 'Vous devez accepter le traitement de vos données personnelles.'
            ];
        }

        $name = trim(strip_tags($data['name'] ?? ''));
        $address = trim(strip_tags($data['address'] ?? ''));
        $telephone = trim(strip_tags($data['tel'] ?? ''));
        $email = filter_var(trim($data['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $message = trim(strip_tags($data['message'] ?? ''));
        $serviceRequested = trim(strip_tags($data['service_requested'] ?? ''));

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

        if (!preg_match("/^[a-zA-ZÀ-ÿ\s'\-]+$/u", $name)) {
            return [
                'success' => false,
                'message' => 'Le nom contient des caractères non autorisés.'
            ];
        }

        if (!empty($address) && strlen($address) > 90) {
            return [
                'success' => false,
                'message' => 'L’adresse ne doit pas dépasser 90 caractères.'
            ];
        }

        if (!empty($telephone) && strlen($telephone) > 20) {
            return [
                'success' => false,
                'message' => 'Le téléphone ne doit pas dépasser 20 caractères.'
            ];
        }

        if (!empty($telephone) && !preg_match('/^[0-9\s\+\.\-]{10,20}$/', $telephone)) {
            return [
                'success' => false,
                'message' => 'Le numéro de téléphone est invalide.'
            ];
        }

        if (!empty($serviceRequested) && strlen($serviceRequested) > 120) {
            return [
                'success' => false,
                'message' => 'La prestation demandée est invalide.'
            ];
        }

        if (strlen($message) < 10 || strlen($message) > 1000) {
            return [
                'success' => false,
                'message' => 'Le message doit contenir entre 10 et 1000 caractères.'
            ];
        }

        $contact = new Contact(
            $name,
            $email,
            $message,
            $address,
            $telephone,
            $serviceRequested
        );

        if (!$this->repository->save($contact)) {
            return [
                'success' => false,
                'message' => 'Erreur lors de l’enregistrement de votre message.'
            ];
        }

        try {
            $mailService = new MailService();
            $mailService->sendContactEmail($contact);
        } catch (Throwable $e) {
        }

        return [
            'success' => true,
            'message' => 'Votre message a été envoyé avec succès.'
        ];
    }
}
