<?php
require_once 'controllers/AuthController.php';
require_once 'models/Venta.php';
require_once 'models/Producto.php';
require_once 'models/Cliente.php';
require_once 'config/database.php';

class VentaController {
    private $auth;
    private $ventaModel;
    private $productoModel;
    private $clienteModel;
    private $conn;

    public function __construct() {
        $this->auth = new AuthController();
        $this->auth->checkAuth();
        $this->auth->checkRole([ROL_ADMIN, ROL_VENDEDOR]);
        $this->ventaModel = new Venta();
        $this->productoModel = new Producto();
        $this->clienteModel = new Cliente();
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function index() {
        $fechaDesde = $_GET['fechaDesde'] ?? date('Y-m-01');
        $fechaHasta = $_GET['fechaHasta'] ?? date('Y-m-d');
        $idCliente = $_GET['idCliente'] ?? '';
        $search = $_GET['search'] ?? '';
        $idUsuario = $_GET['idUsuario'] ?? '';
        $idFormaPago = $_GET['idFormaPago'] ?? '';
        $totalMin = $_GET['totalMin'] ?? '';
        $totalMax = $_GET['totalMax'] ?? '';
        
        $ventas = $this->ventaModel->getAll($fechaDesde, $fechaHasta, $idCliente, $search, $idUsuario, $idFormaPago, $totalMin, $totalMax);
        
        // Obtener usuarios y formas de pago para los filtros
        $usuarios = $this->conn->query("SELECT * FROM usuarios WHERE estado = 1 ORDER BY nombre, apellidos")->fetchAll();
        $formasPago = $this->conn->query("SELECT * FROM forma_pago WHERE estado = 1 ORDER BY nombre")->fetchAll();
        
        $pageTitle = "Historial de Ventas";
        require_once 'views/layout/header.php';
        require_once 'views/ventas/index.php';
        require_once 'views/layout/footer.php';
    }

    public function search() {
        $fechaDesde = $_GET['fechaDesde'] ?? date('Y-m-01');
        $fechaHasta = $_GET['fechaHasta'] ?? date('Y-m-d');
        $idCliente = $_GET['idCliente'] ?? '';
        $search = trim($_GET['search'] ?? '');
        $idUsuario = $_GET['idUsuario'] ?? '';
        $idFormaPago = $_GET['idFormaPago'] ?? '';
        $totalMin = $_GET['totalMin'] ?? '';
        $totalMax = $_GET['totalMax'] ?? '';

        $ventas = $this->ventaModel->searchAjax($fechaDesde, $fechaHasta, $idCliente, $search, $idUsuario, $idFormaPago, $totalMin, $totalMax);

        header('Content-Type: application/json');
        echo json_encode($ventas);
        exit;
    }

    public function nueva() {
        $formasPago = $this->conn->query("SELECT * FROM forma_pago WHERE estado = 1 ORDER BY nombre")->fetchAll();
        
        $pageTitle = "Nueva Venta";
        require_once 'views/layout/header.php';
        require_once 'views/ventas/nueva.php';
        require_once 'views/layout/footer.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->ventaModel->idCliente = !empty($_POST['idCliente']) ? $_POST['idCliente'] : null;
            $this->ventaModel->idUsuario = $_SESSION['user_id'];
            $this->ventaModel->idFormaPago = $_POST['idFormaPago'];
            $this->ventaModel->total = $_POST['total'];
            $this->ventaModel->estado = 1;
            $this->ventaModel->observaciones = $_POST['observaciones'] ?? '';
            
            // Validar y procesar detalles
            $detalles = json_decode($_POST['detalles'] ?? '[]', true);
            if (!is_array($detalles) || count($detalles) === 0) {
                $_SESSION['old'] = $_POST;
                $_SESSION['error'] = 'Agrega al menos un producto a la venta';
                header('Location: ' . BASE_URL . 'ventas/nueva');
                exit;
            }

            // Sumar cantidades por producto para validar stock de forma segura
            $cantidadesPorProducto = [];
            foreach ($detalles as $detalle) {
                $codProducto = $detalle['codProducto'] ?? null;
                $cantidad = isset($detalle['cantidad']) ? (int) $detalle['cantidad'] : 0;
                if (!$codProducto || $cantidad <= 0) {
                    $_SESSION['old'] = $_POST;
                    $_SESSION['error'] = 'Los productos de la venta deben tener cantidad valida';
                    header('Location: ' . BASE_URL . 'ventas/nueva');
                    exit;
                }
                $cantidadesPorProducto[$codProducto] = ($cantidadesPorProducto[$codProducto] ?? 0) + $cantidad;
            }

            // Consultar existencias y validar stock disponible
            $placeholders = implode(',', array_fill(0, count($cantidadesPorProducto), '?'));
            $stmt = $this->conn->prepare("SELECT codProducto, nombre, existencia FROM productos WHERE codProducto IN ($placeholders) AND estado = 1");
            $stmt->execute(array_keys($cantidadesPorProducto));
            $stocks = [];
            foreach ($stmt->fetchAll() as $row) {
                $stocks[$row['codProducto']] = [
                    'nombre' => $row['nombre'],
                    'existencia' => (int) $row['existencia'],
                ];
            }

            foreach ($cantidadesPorProducto as $cod => $cantidadSolicitada) {
                if (!isset($stocks[$cod])) {
                    $_SESSION['old'] = $_POST;
                    $_SESSION['error'] = "El producto con codigo $cod no existe o esta inactivo";
                    header('Location: ' . BASE_URL . 'ventas/nueva');
                    exit;
                }
                if ($stocks[$cod]['existencia'] < $cantidadSolicitada) {
                    $_SESSION['old'] = $_POST;
                    $_SESSION['error'] = "Stock insuficiente para {$stocks[$cod]['nombre']} (disponible: {$stocks[$cod]['existencia']}, solicitado: $cantidadSolicitada)";
                    header('Location: ' . BASE_URL . 'ventas/nueva');
                    exit;
                }
            }

            $this->ventaModel->detalles = $detalles;
            
            $idVenta = $this->ventaModel->create();
            
            if ($idVenta) {
                unset($_SESSION['old']);
                $_SESSION['venta_draft_clear'] = true;
                $_SESSION['success'] = 'Venta registrada exitosamente';
                header('Location: ' . BASE_URL . 'ventas/detalle/' . $idVenta);
                exit;
            } else {
                $_SESSION['old'] = $_POST;
                $_SESSION['error'] = 'Error al registrar la venta';
                header('Location: ' . BASE_URL . 'ventas/nueva');
                exit;
            }
        }
    }

    public function detalle($id) {
        $venta = $this->ventaModel->getById($id);
        
        if (!$venta) {
            $_SESSION['error'] = 'Venta no encontrada';
            header('Location: ' . BASE_URL . 'ventas/index');
            exit;
        }
        
        $pageTitle = "Detalle de Venta #" . $id;
        require_once 'views/layout/header.php';
        require_once 'views/ventas/detalle.php';
        require_once 'views/layout/footer.php';
    }

    public function anular($id) {
        if ($_SESSION['user_rol'] != ROL_ADMIN) {
            $_SESSION['error'] = 'Solo los administradores pueden anular ventas';
            header('Location: ' . BASE_URL . 'ventas/index');
            exit;
        }
        
        if ($this->ventaModel->anular($id)) {
            $_SESSION['success'] = 'Venta anulada exitosamente';
        } else {
            $_SESSION['error'] = 'Error al anular la venta';
        }
        
        header('Location: ' . BASE_URL . 'ventas/index');
        exit;
    }

    public function ticket($id) {
        $venta = $this->ventaModel->getById($id);
        
        if (!$venta) {
            $_SESSION['error'] = 'Venta no encontrada';
            header('Location: ' . BASE_URL . 'ventas/index');
            exit;
        }
        
        require_once 'views/ventas/ticket.php';
    }
}
?>

