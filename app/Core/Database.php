<?php

class Database
{
    public static function connect(): mysqli
    {
        $config = parse_ini_file(__DIR__ . '/../../.env');

        $conn = new mysqli(
            $config['DATABASE_HOST'],
            $config['DATABASE_USERNAME'],
            $config['DATABASE_PASSWORD'],
            $config['DATABASE_NAME']
        );

        if ($conn->connect_error) {
            die('Erreur de connexion à la base de données');
        }

        $conn->set_charset('utf8mb4');

        return $conn;
    }
}
