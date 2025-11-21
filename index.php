<?php
require_once 'config/config.php';
require_once 'config/helpers.php';
require_once 'config/database.php';

// Autocargar clases
spl_autoload_register(function($class) {
    $paths = [
        'controllers/' . $class . '.php',
        'models/' . $class . '.php',
        'config/' . $class . '.php'
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            break;
        }
    }
});

// Obtener la URL
$url = isset($_GET['url']) ? $_GET['url'] : 'home';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Determinar controlador y método
$controller = isset($url[0]) && $url[0] != '' ? ucfirst($url[0]) . 'Controller' : 'HomeController';
$method = isset($url[1]) && $url[1] != '' ? $url[1] : 'index';
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

