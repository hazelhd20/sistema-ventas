<?php
/**
 * Funciones de ayuda para las vistas del nuevo diseño.
 */

if (!function_exists('base_url')) {
    function base_url(string $path = ''): string
    {
        $base = rtrim(BASE_URL, '/');
        $trimmedPath = ltrim($path, '/');
        return $trimmedPath === '' ? $base . '/' : $base . '/' . $trimmedPath;
    }
}

if (!function_exists('asset_url')) {
    function asset_url(string $path = ''): string
    {
        return base_url($path);
    }
}

if (!function_exists('config')) {
    function config(string $key, $default = null)
    {
        $map = [
            'app.name' => SITE_NAME,
            'app.url' => BASE_URL,
        ];

        return $map[$key] ?? $default;
    }
}

if (!function_exists('e')) {
    function e($value, $default = ''): string
    {
        $safeValue = $value ?? $default;
        return htmlspecialchars((string) $safeValue, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('auth_user')) {
    function auth_user(): ?array
    {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }

        $roleId = $_SESSION['user_rol'] ?? null;
        $roleMap = [
            ROL_ADMIN => 'admin',
            ROL_ENCARGADO => 'encargado',
            ROL_VENDEDOR => 'vendedor',
        ];

        return [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_nombre'] ?? ($_SESSION['user_usuario'] ?? 'Usuario'),
            'role' => $roleMap[$roleId] ?? 'usuario',
            'username' => $_SESSION['user_usuario'] ?? '',
        ];
    }
}

if (!function_exists('current_path')) {
    function current_path(): string
    {
        $uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
        $basePath = parse_url(BASE_URL, PHP_URL_PATH) ?? '/';
        if ($basePath !== '/' && strpos($uriPath, $basePath) === 0) {
            $uriPath = substr($uriPath, strlen($basePath));
            $uriPath = $uriPath === '' ? '/' : '/' . ltrim($uriPath, '/');
        }
        return $uriPath;
    }
}

if (!function_exists('flash')) {
    function flash(string $key, $default = null)
    {
        if (!isset($_SESSION[$key])) {
            return $default;
        }

        $value = $_SESSION[$key];
        unset($_SESSION[$key]);
        return $value;
    }
}

if (!function_exists('old')) {
    function old(string $key, $default = '')
    {
        if (isset($_SESSION['old'][$key])) {
            $value = $_SESSION['old'][$key];
        } elseif (isset($_POST[$key])) {
            $value = $_POST[$key];
        } else {
            $value = $default;
        }

        if (isset($_SESSION['old'][$key])) {
            unset($_SESSION['old'][$key]);
        }

        return $value;
    }
}
