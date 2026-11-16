<?php
declare(strict_types=1);

/**
 * AHAMED DRAFTED — Session Authentication, CSRF & Visibility Guard
 */

namespace AhamedDrafted\Config;

class Auth {
    public static function startSession(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params([
                'lifetime' => 0,
                'path'     => '/',
                'httponly' => true,
                'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
                'samesite' => 'Lax',
            ]);
            session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    public static function isAdminAuthenticated(): bool {
        self::startSession();
        return isset($_SESSION['user_role']) && in_array($_SESSION['user_role'], ['owner', 'editor'], true);
    }

    public static function requireAdminApi(): void {
        if (!self::isAdminAuthenticated()) {
            http_response_code(401);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'status'  => 'error',
                'code'    => 'UNAUTHORIZED',
                'message' => 'Administrative session required to mutate archive records.'
            ]);
            exit;
        }
    }

    public static function verifyCsrfToken(?string $token): bool {
        self::startSession();
        return is_string($token) && hash_equals($_SESSION['csrf_token'] ?? '', $token);
    }

    public static function canViewRecord(array $record): bool {
        $visibility = $record['visibility'] ?? 'public';
        $status     = $record['status'] ?? 'published';
        if (self::isAdminAuthenticated()) {
            return true;
        }
        return $visibility === 'public' && $status === 'published';
    }
}
