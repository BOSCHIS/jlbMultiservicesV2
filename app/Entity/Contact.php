<?php

class Contact
{
    public function __construct(
        public string $name,
        public string $email,
        public string $message,
        public string $address = '',
        public string $telephone = '',
        public string $serviceRequested = ''
    ) {}
}
