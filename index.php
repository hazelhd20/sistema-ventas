<?php
require_once 'config/config.php';
require_once 'config/helpers.php';
require_once 'config/database.php';

// Autocargar clases
spl_autoload_register(function ($class) {
    $paths = [
        'controllers/' . $class . '.php',
        'models/' . $class . '.php',
        'config/' . $class . '.php',
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            break;
        }
    }
});

// Obtener la ruta solicitada
$url = isset($_GET['url']) ? $_GET['url'] : 'home';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Normalizar rutas (plural -> controlador base)
$requestedRoute = isset($url[0]) && $url[0] !== '' ? strtolower($url[0]) : 'home';
$routeMap = [
    'productos' => 'ProductoController',
    'clientes' => 'ClienteController',
    'proveedores' => 'ProveedorController',
    'compras' => 'CompraController',
    'ventas' => 'VentaController',
    'reportes' => 'ReporteController',
    'usuarios' => 'UsuarioController',
];

// Determinar controlador y metodo
$controller = $routeMap[$requestedRoute] ?? ucfirst($requestedRoute) . 'Controller';
$method = isset($url[1]) && $url[1] !== '' ? $url[1] : 'index';
$params = array_slice($url, 2);

// Verificar si el controlador existe
$controllerFile = CONTROLLERS_PATH . $controller . '.php';
if (file_exists($controllerFile)) {
    require_once $controllerFile;

    if (class_exists($controller)) {
        $controllerInstance = new $controller();

        if (method_exists($controllerInstance, $method)) {
            call_user_func_array([$controllerInstance, $method], $params);
        } else {
            require_once 'views/errors/404.php';
        }
    } else {
        require_once 'views/errors/404.php';
    }
} else {
    require_once 'views/errors/404.php';
}
?>
