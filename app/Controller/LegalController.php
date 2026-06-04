<?php

class LegalController
{
    public function mentions(): void
    {
        require_once __DIR__ . '/../../views/legal/mentions.php';
    }

    public function terms(): void
    {
        require_once __DIR__ . '/../../views/legal/terms.php';
    }

    public function privacy(): void
    {
        require_once __DIR__ . '/../../views/legal/privacy.php';
    }
}
