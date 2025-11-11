<?php
require_once 'controllers/AuthController.php';
require_once 'models/Usuario.php';
require_once 'config/database.php';

class UsuarioController {
    private $auth;
    private $usuarioModel;
    private $conn;

    public function __construct() {
        $this->auth = new AuthController();
        $this->auth->checkAuth();
        $this->auth->checkRole([ROL_ADMIN]);
        $this->usuarioModel = new Usuario();
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function index() {
        $usuarios = $this->usuarioModel->getAll();
        $roles = $this->conn->query("SELECT * FROM roles ORDER BY nombre")->fetchAll();
        
        $pageTitle = "Usuarios";
        require_once 'views/layout/header.php';
        require_once 'views/usuarios/index.php';
        require_once 'views/layout/footer.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->usuarioModel->idRol = $_POST['idRol'];
            $this->usuarioModel->nombre = $_POST['nombre'];
            $this->usuarioModel->apellidos = $_POST['apellidos'];
            $this->usuarioModel->usuario = $_POST['usuario'];
            $this->usuarioModel->password = $_POST['password'];
            $this->usuarioModel->email = $_POST['email'] ?? '';
            $this->usuarioModel->telefono = $_POST['telefono'] ?? '';
            $this->usuarioModel->estado = 1;
            
            if ($this->usuarioModel->create()) {
                $_SESSION['success'] = 'Usuario creado exitosamente';
            } else {
                $_SESSION['error'] = 'Error al crear el usuario';
            }
            
            header('Location: ' . BASE_URL . 'usuarios');
            exit;
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->usuarioModel->idUsuario = $_POST['idUsuario'];
            $this->usuarioModel->idRol = $_POST['idRol'];
            $this->usuarioModel->nombre = $_POST['nombre'];
            $this->usuarioModel->apellidos = $_POST['apellidos'];
            $this->usuarioModel->usuario = $_POST['usuario'];
            $this->usuarioModel->email = $_POST['email'] ?? '';
            $this->usuarioModel->telefono = $_POST['telefono'] ?? '';
            $this->usuarioModel->estado = $_POST['estado'];
            
            // Solo actualizar contraseña si se proporciona
            if (!empty($_POST['password'])) {
                $this->usuarioModel->password = $_POST['password'];
                // Necesitamos actualizar la contraseña por separado
                $query = "UPDATE usuarios SET password = :password WHERE idUsuario = :id";
                $stmt = $this->conn->prepare($query);
                $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $stmt->bindParam(":password", $hashedPassword);
                $stmt->bindParam(":id", $_POST['idUsuario']);
                $stmt->execute();
            }
            
            if ($this->usuarioModel->update()) {
                $_SESSION['success'] = 'Usuario actualizado exitosamente';
            } else {
                $_SESSION['error'] = 'Error al actualizar el usuario';
            }
            
            header('Location: ' . BASE_URL . 'usuarios');
            exit;
        }
    }

    public function delete($id) {
        if ($this->usuarioModel->delete($id)) {
            $_SESSION['success'] = 'Usuario eliminado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar el usuario';
        }
        
        header('Location: ' . BASE_URL . 'usuarios');
        exit;
    }
}
?>

