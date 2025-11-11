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
            $this->productoModel->nombre = $_POST['nombre'];
            $this->productoModel->descripcion = $_POST['descripcion'] ?? '';
            $this->productoModel->idCategoria = $_POST['idCategoria'];
            $this->productoModel->idMedida = $_POST['idMedida'];
            $this->productoModel->precio = $_POST['precio'];
            $this->productoModel->existencia = $_POST['existencia'] ?? 0;
            $this->productoModel->stockMinimo = $_POST['stockMinimo'] ?? 10;
            $this->productoModel->codigoBarras = $_POST['codigoBarras'] ?? '';
            $this->productoModel->estado = 1;
            
            if ($this->productoModel->create()) {
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
            $this->productoModel->nombre = $_POST['nombre'];
            $this->productoModel->descripcion = $_POST['descripcion'] ?? '';
            $this->productoModel->idCategoria = $_POST['idCategoria'];
            $this->productoModel->idMedida = $_POST['idMedida'];
            $this->productoModel->precio = $_POST['precio'];
            $this->productoModel->existencia = $_POST['existencia'];
            $this->productoModel->stockMinimo = $_POST['stockMinimo'];
            $this->productoModel->codigoBarras = $_POST['codigoBarras'] ?? '';
            $this->productoModel->estado = $_POST['estado'];
            
            if ($this->productoModel->update()) {
                $_SESSION['success'] = 'Producto actualizado exitosamente';
            } else {
                $_SESSION['error'] = 'Error al actualizar el producto';
            }
            
            header('Location: ' . BASE_URL . 'productos');
            exit;
        }
    }

    public function delete($id) {
        if ($this->productoModel->delete($id)) {
            $_SESSION['success'] = 'Producto eliminado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar el producto';
        }
        
        header('Location: ' . BASE_URL . 'productos');
        exit;
    }

    public function search() {
        $term = $_GET['term'] ?? '';
        $productos = $this->productoModel->search($term);
        header('Content-Type: application/json');
        echo json_encode($productos);
    }
}
?>

