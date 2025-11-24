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
            $this->usuarioModel->nombre = trim($_POST['nombre']);
            $this->usuarioModel->apellidos = trim($_POST['apellidos']);
            $this->usuarioModel->usuario = trim($_POST['usuario']);
            $this->usuarioModel->password = $_POST['password'];
            $email = trim($_POST['email'] ?? '');
            $this->usuarioModel->email = $email === '' ? null : $email;
            $this->usuarioModel->telefono = trim($_POST['telefono'] ?? '');
            $this->usuarioModel->estado = 1;
            
            if ($this->usuarioExiste('usuario', $this->usuarioModel->usuario)) {
                $_SESSION['error'] = 'El usuario ya existe';
            } elseif ($this->usuarioExiste('email', $this->usuarioModel->email)) {
                $_SESSION['error'] = 'El correo ya está registrado';
            } elseif ($this->usuarioModel->create()) {
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
            $this->usuarioModel->nombre = trim($_POST['nombre']);
            $this->usuarioModel->apellidos = trim($_POST['apellidos']);
            $this->usuarioModel->usuario = trim($_POST['usuario']);
            $email = trim($_POST['email'] ?? '');
            $this->usuarioModel->email = $email === '' ? null : $email;
            $this->usuarioModel->telefono = trim($_POST['telefono'] ?? '');
            $this->usuarioModel->estado = $_POST['estado'];
            
            if ($this->usuarioExiste('usuario', $this->usuarioModel->usuario, $this->usuarioModel->idUsuario)) {
                $_SESSION['error'] = 'El usuario ya existe';
            } elseif ($this->usuarioExiste('email', $this->usuarioModel->email, $this->usuarioModel->idUsuario)) {
                $_SESSION['error'] = 'El correo ya está registrado';
            } else {
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

    public function toggle() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'usuarios');
            exit;
        }

        $id = $_POST['idUsuario'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'Usuario no encontrado';
            header('Location: ' . BASE_URL . 'usuarios');
            exit;
        }

        $usuario = $this->usuarioModel->getById($id);
        if (!$usuario) {
            $_SESSION['error'] = 'Usuario no encontrado';
            header('Location: ' . BASE_URL . 'usuarios');
            exit;
        }

        $nuevoEstado = $usuario['estado'] == ESTADO_ACTIVO ? ESTADO_INACTIVO : ESTADO_ACTIVO;
        $this->usuarioModel->idUsuario = $id;
        $this->usuarioModel->estado = $nuevoEstado;

        if ($this->usuarioModel->toggleEstado()) {
            $_SESSION['success'] = 'Estado actualizado';
        } else {
            $_SESSION['error'] = 'No se pudo actualizar el estado';
        }

        header('Location: ' . BASE_URL . 'usuarios');
        exit;
    }

    private function usuarioExiste(string $campo, $valor, $excluirId = null): bool {
        $camposPermitidos = ['usuario', 'email'];
        if (!in_array($campo, $camposPermitidos, true)) {
            return false;
        }
        if ($valor === null || $valor === '') {
            return false;
        }

        $sql = "SELECT COUNT(*) AS total FROM usuarios WHERE {$campo} = ?";
        $params = [$valor];

        if ($excluirId !== null) {
            $sql .= " AND idUsuario <> ?";
            $params[] = $excluirId;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch();

        return isset($row['total']) ? (int) $row['total'] > 0 : false;
    }
}
?>

