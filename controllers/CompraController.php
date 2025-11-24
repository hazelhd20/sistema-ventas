<?php
require_once 'controllers/AuthController.php';
require_once 'models/Compra.php';
require_once 'models/Producto.php';
require_once 'models/Proveedor.php';
require_once 'config/database.php';

class CompraController {
    private $auth;
    private $compraModel;
    private $productoModel;
    private $proveedorModel;
    private $conn;

    public function __construct() {
        $this->auth = new AuthController();
        $this->auth->checkAuth();
        $this->auth->checkRole([ROL_ADMIN, ROL_ENCARGADO]);
        $this->compraModel = new Compra();
        $this->productoModel = new Producto();
        $this->proveedorModel = new Proveedor();
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function index() {
        $fechaDesde = $_GET['fechaDesde'] ?? date('Y-m-01');
        $fechaHasta = $_GET['fechaHasta'] ?? date('Y-m-d');
        $idProveedor = $_GET['idProveedor'] ?? '';
        $search = $_GET['search'] ?? '';
        $totalMin = $_GET['totalMin'] ?? '';
        $totalMax = $_GET['totalMax'] ?? '';
        
        $compras = $this->compraModel->getAll($fechaDesde, $fechaHasta, $idProveedor, $search, $totalMin, $totalMax);
        
        // Obtener proveedores para el filtro
        $proveedores = $this->proveedorModel->getAll();
        
        $pageTitle = "Historial de Compras";
        require_once 'views/layout/header.php';
        require_once 'views/compras/index.php';
        require_once 'views/layout/footer.php';
    }

    public function search() {
        $fechaDesde = $_GET['fechaDesde'] ?? date('Y-m-01');
        $fechaHasta = $_GET['fechaHasta'] ?? date('Y-m-d');
        $idProveedor = $_GET['idProveedor'] ?? '';
        $search = trim($_GET['search'] ?? '');
        $totalMin = $_GET['totalMin'] ?? '';
        $totalMax = $_GET['totalMax'] ?? '';

        $compras = $this->compraModel->searchAjax($fechaDesde, $fechaHasta, $idProveedor, $search, $totalMin, $totalMax);

        header('Content-Type: application/json');
        echo json_encode($compras);
        exit;
    }

    public function nueva() {
        $proveedores = $this->proveedorModel->getAll();
        
        $pageTitle = "Nueva Compra";
        require_once 'views/layout/header.php';
        require_once 'views/compras/nueva.php';
        require_once 'views/layout/footer.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->compraModel->idProveedor = $_POST['idProveedor'];
            $this->compraModel->idUsuario = $_SESSION['user_id'];
            $this->compraModel->total = $_POST['total'];
            $this->compraModel->estado = 1;
            $this->compraModel->observaciones = $_POST['observaciones'] ?? '';
            
            // Validar y procesar detalles
            $detalles = json_decode($_POST['detalles'] ?? '[]', true);
            if (!is_array($detalles) || count($detalles) === 0) {
                $_SESSION['error'] = 'Agrega al menos un producto a la compra';
                header('Location: ' . BASE_URL . 'compras/nueva');
                exit;
            }

            foreach ($detalles as $detalle) {
                $codProducto = $detalle['codProducto'] ?? null;
                $cantidad = isset($detalle['cantidad']) ? (int) $detalle['cantidad'] : 0;
                $precio = isset($detalle['precioCompra']) ? (float) $detalle['precioCompra'] : -1;

                if (!$codProducto || $cantidad <= 0 || $precio < 0) {
                    $_SESSION['error'] = 'Cada producto de la compra debe tener codigo, cantidad y precio validos';
                    header('Location: ' . BASE_URL . 'compras/nueva');
                    exit;
                }
            }

            $this->compraModel->detalles = $detalles;
            
            $idCompra = $this->compraModel->create();
            
            if ($idCompra) {
                $_SESSION['success'] = 'Compra registrada exitosamente';
                header('Location: ' . BASE_URL . 'compras/detalle/' . $idCompra);
                exit;
            } else {
                $_SESSION['error'] = 'Error al registrar la compra';
                header('Location: ' . BASE_URL . 'compras/nueva');
                exit;
            }
        }
    }

    public function detalle($id) {
        $compra = $this->compraModel->getById($id);
        
        if (!$compra) {
            $_SESSION['error'] = 'Compra no encontrada';
            header('Location: ' . BASE_URL . 'compras');
            exit;
        }
        
        $pageTitle = "Detalle de Compra #" . $id;
        require_once 'views/layout/header.php';
        require_once 'views/compras/detalle.php';
        require_once 'views/layout/footer.php';
    }

    public function anular($id) {
        if ($_SESSION['user_rol'] != ROL_ADMIN) {
            $_SESSION['error'] = 'Solo los administradores pueden anular compras';
            header('Location: ' . BASE_URL . 'compras');
            exit;
        }
        
        if ($this->compraModel->anular($id)) {
            $_SESSION['success'] = 'Compra anulada exitosamente';
        } else {
            $_SESSION['error'] = 'Error al anular la compra';
        }
        
        header('Location: ' . BASE_URL . 'compras');
        exit;
    }

    public function comprobante($id) {
        $compra = $this->compraModel->getById($id);
        
        if (!$compra) {
            $_SESSION['error'] = 'Compra no encontrada';
            header('Location: ' . BASE_URL . 'compras');
            exit;
        }
        
        require_once 'views/compras/comprobante.php';
    }
}
?>

