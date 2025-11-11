<?php
// Configuración general del sistema
define('BASE_URL', 'http://localhost/sistema-ventas/');
define('SITE_NAME', 'El Mercadito - Sistema de Gestión');

// Configuración de sesión
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Cambiar a 1 en producción con HTTPS

// Zona horaria
date_default_timezone_set('America/Merida');

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Rutas de archivos
define('ROOT_PATH', dirname(__DIR__) . '/');
define('CONTROLLERS_PATH', ROOT_PATH . 'controllers/');
define('MODELS_PATH', ROOT_PATH . 'models/');
define('VIEWS_PATH', ROOT_PATH . 'views/');
define('UPLOADS_PATH', ROOT_PATH . 'uploads/');

// Roles de usuario
define('ROL_ADMIN', 1);
define('ROL_ENCARGADO', 2);
define('ROL_VENDEDOR', 3);

// Estados
define('ESTADO_ACTIVO', 1);
define('ESTADO_INACTIVO', 0);
define('ESTADO_ANULADO', 0);
?>

