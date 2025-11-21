<?php
require_once 'models/Usuario.php';

class AuthController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = trim($_POST['usuario'] ?? '');
            $password = $_POST['password'] ?? '';

            // Recordar el usuario ingresado para volver a mostrarlo en el formulario
            $_SESSION['old'] = ['usuario' => $usuario];

            if ($usuario === '' || $password === '') {
                $_SESSION['error'] = 'Usuario y contrasena son requeridos';
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
                unset($_SESSION['old']);

                header('Location: ' . BASE_URL . 'home');
                exit;
            }

            $_SESSION['error'] = 'Usuario o contrasena incorrectos';
            header('Location: ' . BASE_URL . 'auth/login');
            exit;
        }

        require_once 'views/auth/login.php';
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
            $_SESSION['error'] = 'No tienes permisos para acceder a esta seccion';
            header('Location: ' . BASE_URL . 'home');
            exit;
        }
    }
}
?>
