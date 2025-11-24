<?php
require_once 'controllers/AuthController.php';
require_once 'config/database.php';

class ConfiguracionController {
    private $auth;
    private $conn;
    // Permite gestionar catalogos (no deshabilitado)
    private $configDisabled = false;

    public function __construct() {
        $this->auth = new AuthController();
        $this->auth->checkAuth();
        $this->auth->checkRole([ROL_ADMIN]);
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function index() {
        $categorias = $this->conn->query("SELECT * FROM categorias ORDER BY estado DESC, nombre")->fetchAll();
        $medidas = $this->conn->query("SELECT * FROM medidas ORDER BY estado DESC, nombre")->fetchAll();
        $formasPago = $this->conn->query("SELECT * FROM forma_pago ORDER BY estado DESC, nombre")->fetchAll();
        $configDisabled = $this->configDisabled;
        
        $pageTitle = "Configuracion";
        require_once 'views/layout/header.php';
        require_once 'views/configuracion/index.php';
        require_once 'views/layout/footer.php';
    }

    public function categoria() {
        if ($this->configDisabled) {
            $_SESSION['error'] = 'El modulo de categorias esta deshabilitado';
            header('Location: ' . BASE_URL . 'configuracion');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accion = $_POST['accion'] ?? '';
            $id = $_POST['id'] ?? null;
            $nombre = trim($_POST['nombre'] ?? '');
            $descripcion = $_POST['descripcion'] ?? '';

            if ($accion === 'crear') {
                if ($this->nombreExiste('categorias', 'idCategoria', $nombre)) {
                    $_SESSION['error'] = 'La categoria ya existe';
                    header('Location: ' . BASE_URL . 'configuracion');
                    exit;
                }
                $stmt = $this->conn->prepare("INSERT INTO categorias (nombre, descripcion, estado) VALUES (?, ?, 1)");
                $stmt->execute([$nombre, $descripcion]);
                $_SESSION['success'] = 'Categoria creada exitosamente';
            } elseif ($accion === 'editar' && $id) {
                if ($this->nombreExiste('categorias', 'idCategoria', $nombre, $id)) {
                    $_SESSION['error'] = 'La categoria ya existe';
                    header('Location: ' . BASE_URL . 'configuracion');
                    exit;
                }
                $stmt = $this->conn->prepare("UPDATE categorias SET nombre = ?, descripcion = ? WHERE idCategoria = ?");
                $stmt->execute([$nombre, $descripcion, $id]);
                $_SESSION['success'] = 'Categoria actualizada exitosamente';
            } elseif ($accion === 'desactivar' && $id) {
                $stmt = $this->conn->prepare("UPDATE categorias SET estado = 0 WHERE idCategoria = ?");
                $stmt->execute([$id]);
                $_SESSION['success'] = 'Categoria desactivada';
            } elseif ($accion === 'activar' && $id) {
                $stmt = $this->conn->prepare("UPDATE categorias SET estado = 1 WHERE idCategoria = ?");
                $stmt->execute([$id]);
                $_SESSION['success'] = 'Categoria activada';
            }
        }
        
        header('Location: ' . BASE_URL . 'configuracion');
        exit;
    }

    public function medida() {
        if ($this->configDisabled) {
            $_SESSION['error'] = 'El modulo de medidas esta deshabilitado';
            header('Location: ' . BASE_URL . 'configuracion');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accion = $_POST['accion'] ?? '';
            $id = $_POST['id'] ?? null;
            $nombre = trim($_POST['nombre'] ?? '');
            $abreviatura = trim($_POST['abreviatura'] ?? '');

            if ($accion === 'crear') {
                if ($this->nombreExiste('medidas', 'idMedida', $nombre)) {
                    $_SESSION['error'] = 'La medida ya existe';
                    header('Location: ' . BASE_URL . 'configuracion');
                    exit;
                }
                $stmt = $this->conn->prepare("INSERT INTO medidas (nombre, abreviatura, estado) VALUES (?, ?, 1)");
                $stmt->execute([$nombre, $abreviatura]);
                $_SESSION['success'] = 'Medida creada exitosamente';
            } elseif ($accion === 'editar' && $id) {
                if ($this->nombreExiste('medidas', 'idMedida', $nombre, $id)) {
                    $_SESSION['error'] = 'La medida ya existe';
                    header('Location: ' . BASE_URL . 'configuracion');
                    exit;
                }
                $stmt = $this->conn->prepare("UPDATE medidas SET nombre = ?, abreviatura = ? WHERE idMedida = ?");
                $stmt->execute([$nombre, $abreviatura, $id]);
                $_SESSION['success'] = 'Medida actualizada exitosamente';
            } elseif ($accion === 'desactivar' && $id) {
                $stmt = $this->conn->prepare("UPDATE medidas SET estado = 0 WHERE idMedida = ?");
                $stmt->execute([$id]);
                $_SESSION['success'] = 'Medida desactivada';
            } elseif ($accion === 'activar' && $id) {
                $stmt = $this->conn->prepare("UPDATE medidas SET estado = 1 WHERE idMedida = ?");
                $stmt->execute([$id]);
                $_SESSION['success'] = 'Medida activada';
            }
        }
        
        header('Location: ' . BASE_URL . 'configuracion');
        exit;
    }

    public function formaPago() {
        if ($this->configDisabled) {
            $_SESSION['error'] = 'El modulo de formas de pago esta deshabilitado';
            header('Location: ' . BASE_URL . 'configuracion');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accion = $_POST['accion'] ?? '';
            $id = $_POST['id'] ?? null;
            $nombre = trim($_POST['nombre'] ?? '');
            $descripcion = $_POST['descripcion'] ?? '';

            if ($accion === 'crear') {
                if ($this->nombreExiste('forma_pago', 'idFormaPago', $nombre)) {
                    $_SESSION['error'] = 'La forma de pago ya existe';
                    header('Location: ' . BASE_URL . 'configuracion');
                    exit;
                }
                $stmt = $this->conn->prepare("INSERT INTO forma_pago (nombre, descripcion, estado) VALUES (?, ?, 1)");
                $stmt->execute([$nombre, $descripcion]);
                $_SESSION['success'] = 'Forma de pago creada exitosamente';
            } elseif ($accion === 'editar' && $id) {
                if ($this->nombreExiste('forma_pago', 'idFormaPago', $nombre, $id)) {
                    $_SESSION['error'] = 'La forma de pago ya existe';
                    header('Location: ' . BASE_URL . 'configuracion');
                    exit;
                }
                $stmt = $this->conn->prepare("UPDATE forma_pago SET nombre = ?, descripcion = ? WHERE idFormaPago = ?");
                $stmt->execute([$nombre, $descripcion, $id]);
                $_SESSION['success'] = 'Forma de pago actualizada exitosamente';
            } elseif ($accion === 'desactivar' && $id) {
                $stmt = $this->conn->prepare("UPDATE forma_pago SET estado = 0 WHERE idFormaPago = ?");
                $stmt->execute([$id]);
                $_SESSION['success'] = 'Forma de pago desactivada';
            } elseif ($accion === 'activar' && $id) {
                $stmt = $this->conn->prepare("UPDATE forma_pago SET estado = 1 WHERE idFormaPago = ?");
                $stmt->execute([$id]);
                $_SESSION['success'] = 'Forma de pago activada';
            }
        }
        
        header('Location: ' . BASE_URL . 'configuracion');
        exit;
    }

    private function nombreExiste(string $tabla, string $columnaId, string $nombre, $excluirId = null): bool {
        $sql = "SELECT COUNT(*) AS total FROM {$tabla} WHERE LOWER(nombre) = LOWER(?)";
        $params = [$nombre];

        if ($excluirId !== null) {
            $sql .= " AND {$columnaId} <> ?";
            $params[] = $excluirId;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $resultado = $stmt->fetch();

        return isset($resultado['total']) ? (int) $resultado['total'] > 0 : false;
    }
}
?>
