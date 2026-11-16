<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use RuntimeException;

final class Database
{
    public static function connect(string $configPath): PDO
    {
        if (!is_file($configPath)) {
            throw new RuntimeException('Configuration unavailable.');
        }

        $config = require $configPath;
        $database = is_array($config) ? ($config['database'] ?? null) : null;
        if (!is_array($database)) {
            throw new RuntimeException('Invalid configuration.');
        }

        foreach (['host', 'name', 'username', 'password'] as $key) {
            if (!isset($database[$key]) || !is_string($database[$key])) {
                throw new RuntimeException('Invalid configuration.');
            }
        }
        if (!preg_match('/\A[a-zA-Z0-9._-]+\z/', $database['host'])
            || !preg_match('/\A[a-zA-Z0-9_]+\z/', $database['name'])
            || $database['username'] === ''
            || !isset($database['port'])
            || !is_int($database['port'])
            || $database['port'] < 1
            || $database['port'] > 65535
        ) {
            throw new RuntimeException('Invalid configuration.');
        }

        $pdo = new PDO(
            sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
                $database['host'],
                $database['port'],
                $database['name']
            ),
            $database['username'],
            $database['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
        $pdo->exec("SET time_zone = '+00:00'");

        return $pdo;
    }
}
