<?php
require_once 'controllers/AuthController.php';
require_once 'models/Producto.php';
require_once 'config/database.php';

class ProductoController {
    private $auth;
    private $productoModel;
    private $conn;

    public function __construct() {
        $this->auth = new AuthController();
        $this->auth->checkAuth();
        $this->productoModel = new Producto();
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function index() {
        $search = $_GET['search'] ?? '';
        $productos = $this->productoModel->getAll($search);
        
        // Obtener categorías y medidas para el formulario
        $categorias = $this->conn->query("SELECT * FROM categorias WHERE estado = 1 ORDER BY nombre")->fetchAll();
        $medidas = $this->conn->query("SELECT * FROM medidas WHERE estado = 1 ORDER BY nombre")->fetchAll();
        
        $pageTitle = "Productos";
        require_once 'views/layout/header.php';
        require_once 'views/productos/index.php';
        require_once 'views/layout/footer.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->productoModel->nombre = trim($_POST['nombre']);
            $this->productoModel->descripcion = $_POST['descripcion'] ?? '';
            $this->productoModel->idCategoria = $_POST['idCategoria'];
            $this->productoModel->idMedida = $_POST['idMedida'];
            $this->productoModel->precio = $_POST['precio'];
            $this->productoModel->existencia = $_POST['existencia'] ?? 0;
            $this->productoModel->stockMinimo = $_POST['stockMinimo'] ?? 10;
            $codigoBarras = trim($_POST['codigoBarras'] ?? '');
            $this->productoModel->codigoBarras = $codigoBarras === '' ? null : $codigoBarras;
            $this->productoModel->estado = 1;
            
            if ($this->codigoBarrasExiste($this->productoModel->codigoBarras)) {
                $_SESSION['error'] = 'El código de barras ya está registrado';
            } elseif ($this->productoModel->create()) {
                $_SESSION['success'] = 'Producto creado exitosamente';
            } else {
                $_SESSION['error'] = 'Error al crear el producto';
            }
            
            header('Location: ' . BASE_URL . 'productos');
            exit;
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->productoModel->codProducto = $_POST['codProducto'];
            $this->productoModel->nombre = trim($_POST['nombre']);
            $this->productoModel->descripcion = $_POST['descripcion'] ?? '';
            $this->productoModel->idCategoria = $_POST['idCategoria'];
            $this->productoModel->idMedida = $_POST['idMedida'];
            $this->productoModel->precio = $_POST['precio'];
            $this->productoModel->existencia = $_POST['existencia'];
            $this->productoModel->stockMinimo = $_POST['stockMinimo'];
            $codigoBarras = trim($_POST['codigoBarras'] ?? '');
            $this->productoModel->codigoBarras = $codigoBarras === '' ? null : $codigoBarras;
            // Si no se envia estado desde el formulario, mantener activo por defecto
            $this->productoModel->estado = $_POST['estado'] ?? 1;
            
            if ($this->codigoBarrasExiste($this->productoModel->codigoBarras, $this->productoModel->codProducto)) {
                $_SESSION['error'] = 'El código de barras ya está registrado';
            } elseif ($this->productoModel->update()) {
                $_SESSION['success'] = 'Producto actualizado exitosamente';
            } else {
                $_SESSION['error'] = 'Error al actualizar el producto';
            }
            
            header('Location: ' . BASE_URL . 'productos');
            exit;
        }
    }

    public function delete($id) {
        if ($this->productoModel->setEstado($id, 0)) {
            $_SESSION['success'] = 'Producto desactivado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al desactivar el producto';
        }
        
        header('Location: ' . BASE_URL . 'productos');
        exit;
    }

    public function activate($id) {
        if ($this->productoModel->setEstado($id, 1)) {
            $_SESSION['success'] = 'Producto activado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al activar el producto';
        }

        header('Location: ' . BASE_URL . 'productos');
        exit;
    }

    public function search() {
        $term = $_GET['term'] ?? '';
        $soloActivos = isset($_GET['soloActivos']) ? (int) $_GET['soloActivos'] === 1 : false;
        $productos = $this->productoModel->search($term, $soloActivos);
        header('Content-Type: application/json');
        echo json_encode($productos);
    }

    private function codigoBarrasExiste($codigoBarras, $excluirId = null): bool {
        if ($codigoBarras === null || $codigoBarras === '') {
            return false;
        }

        $sql = "SELECT COUNT(*) AS total FROM productos WHERE codigoBarras = ?";
        $params = [$codigoBarras];

        if ($excluirId !== null) {
            $sql .= " AND codProducto <> ?";
            $params[] = $excluirId;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch();

        return isset($row['total']) ? (int) $row['total'] > 0 : false;
    }
}
?>

