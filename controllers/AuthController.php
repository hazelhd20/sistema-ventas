<?php
require_once 'models/Usuario.php';

class AuthController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = $_POST['usuario'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (empty($usuario) || empty($password)) {
                $_SESSION['error'] = 'Usuario y contraseña son requeridos';
                header('Location: ' . BASE_URL . 'auth/login');
                exit;
            }
            
            $user = $this->usuarioModel->login($usuario, $password);
            
            if ($user) {
                $_SESSION['user_id'] = $user['idUsuario'];
                $_SESSION['user_nombre'] = $user['nombre'] . ' ' . $user['apellidos'];
                $_SESSION['user_rol'] = $user['idRol'];
                $_SESSION['user_rol_nombre'] = $user['rol_nombre'];
                $_SESSION['user_usuario'] = $user['usuario'];
                
                header('Location: ' . BASE_URL . 'home');
                exit;
            } else {
                $_SESSION['error'] = 'Usuario o contraseña incorrectos';
                header('Location: ' . BASE_URL . 'auth/login');
                exit;
            }
        } else {
            require_once 'views/auth/login.php';
        }
    }

    public function logout() {
        session_destroy();
        header('Location: ' . BASE_URL . 'auth/login');
        exit;
    }

    public function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'auth/login');
            exit;
        }
    }

    public function checkRole($allowedRoles) {
        $this->checkAuth();
        
        if (!in_array($_SESSION['user_rol'], $allowedRoles)) {
            $_SESSION['error'] = 'No tienes permisos para acceder a esta sección';
            header('Location: ' . BASE_URL . 'home');
            exit;
        }
    }
}
?>

