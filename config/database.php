<?php
declare(strict_types=1);

namespace AhamedDrafted\Config;

use PDO;
use PDOException;
use RuntimeException;

function load_environment(string $file): void {
    if (!is_file($file) || !is_readable($file)) return;
    foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) continue;
        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim(trim($value), "\"'");
        if ($key !== '' && getenv($key) === false) putenv($key . '=' . $value);
    }
}

load_environment(dirname(__DIR__) . '/.env');

final class Database {
    private static ?PDO $connection = null;

    public static function getConnection(): PDO {
        if (self::$connection instanceof PDO) return self::$connection;
        $host = getenv('DB_HOST') ?: '';
        $name = getenv('DB_NAME') ?: '';
        $user = getenv('DB_USER') ?: '';
        $pass = getenv('DB_PASS') ?: '';
        $charset = getenv('DB_CHARSET') ?: 'utf8mb4';

        if ($host === '' || $name === '' || $user === '') {
            throw new RuntimeException('Database configuration is incomplete. Set DB_HOST, DB_NAME, DB_USER and DB_PASS in the private .env file.');
        }

        $dsn = "mysql:host={$host};dbname={$name};charset={$charset}";
        try {
            self::$connection = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            error_log('Ahamed Drafted database connection failed: ' . $e->getMessage());
            throw new RuntimeException('The website database connection could not be established.');
        }
        return self::$connection;
    }
}
