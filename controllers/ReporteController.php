<?php
require_once 'controllers/AuthController.php';
require_once 'config/database.php';

class ReporteController {
    private $auth;
    private $conn;

    public function __construct() {
        $this->auth = new AuthController();
        $this->auth->checkAuth();
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function index() {
        $fechaDesde = $_GET['fechaDesde'] ?? date('Y-m-01');
        $fechaHasta = $_GET['fechaHasta'] ?? date('Y-m-d');
        $tipo = $_GET['tipo'] ?? 'ventas';
        
        $pageTitle = "Reportes";
        require_once 'views/layout/header.php';
        require_once 'views/reportes/index.php';
        require_once 'views/layout/footer.php';
    }

    public function ventas() {
        $fechaDesde = $_GET['fechaDesde'] ?? date('Y-m-01');
        $fechaHasta = $_GET['fechaHasta'] ?? date('Y-m-d');
        
        $query = "SELECT v.*, 
                         c.nombre as cliente_nombre, c.apellidos as cliente_apellidos,
                         u.nombre as usuario_nombre, u.apellidos as usuario_apellidos,
                         fp.nombre as forma_pago_nombre
                  FROM ventas v
                  LEFT JOIN clientes c ON v.idCliente = c.idCliente
                  INNER JOIN usuarios u ON v.idUsuario = u.idUsuario
                  INNER JOIN forma_pago fp ON v.idFormaPago = fp.idFormaPago
                  WHERE DATE(v.fecha) >= :fechaDesde AND DATE(v.fecha) <= :fechaHasta AND v.estado = 1
                  ORDER BY v.fecha DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":fechaDesde", $fechaDesde);
        $stmt->bindParam(":fechaHasta", $fechaHasta);
        $stmt->execute();
        $ventas = $stmt->fetchAll();
        
        $total = array_sum(array_column($ventas, 'total'));
        
        header('Content-Type: application/json');
        echo json_encode(['ventas' => $ventas, 'total' => $total]);
    }

    public function compras() {
        $fechaDesde = $_GET['fechaDesde'] ?? date('Y-m-01');
        $fechaHasta = $_GET['fechaHasta'] ?? date('Y-m-d');
        
        $query = "SELECT c.*, 
                         p.nombre as proveedor_nombre,
                         u.nombre as usuario_nombre, u.apellidos as usuario_apellidos
                  FROM compras c
                  INNER JOIN proveedores p ON c.idProveedor = p.idProveedor
                  INNER JOIN usuarios u ON c.idUsuario = u.idUsuario
                  WHERE DATE(c.fecha) >= :fechaDesde AND DATE(c.fecha) <= :fechaHasta AND c.estado = 1
                  ORDER BY c.fecha DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":fechaDesde", $fechaDesde);
        $stmt->bindParam(":fechaHasta", $fechaHasta);
        $stmt->execute();
        $compras = $stmt->fetchAll();
        
        $total = array_sum(array_column($compras, 'total'));
        
        header('Content-Type: application/json');
        echo json_encode(['compras' => $compras, 'total' => $total]);
    }
}
?>

