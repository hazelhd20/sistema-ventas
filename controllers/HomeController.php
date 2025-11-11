<?php
require_once 'controllers/AuthController.php';

class HomeController {
    private $auth;

    public function __construct() {
        $this->auth = new AuthController();
        $this->auth->checkAuth();
    }

    public function index() {
        require_once 'config/database.php';
        $db = new Database();
        $conn = $db->getConnection();

        // Estadísticas rápidas
        $stats = [];
        
        // Total de productos
        $stmt = $conn->query("SELECT COUNT(*) as total FROM productos WHERE estado = 1");
        $stats['productos'] = $stmt->fetch()['total'];
        
        // Productos con stock bajo
        $stmt = $conn->query("SELECT COUNT(*) as total FROM productos WHERE existencia <= stockMinimo AND estado = 1");
        $stats['stock_bajo'] = $stmt->fetch()['total'];
        
        // Ventas del día
        $stmt = $conn->query("SELECT COUNT(*) as total, COALESCE(SUM(total), 0) as monto 
                              FROM ventas 
                              WHERE DATE(fecha) = CURDATE() AND estado = 1");
        $ventas_hoy = $stmt->fetch();
        $stats['ventas_hoy'] = $ventas_hoy['total'];
        $stats['monto_hoy'] = $ventas_hoy['monto'];
        
        // Compras del día
        $stmt = $conn->query("SELECT COUNT(*) as total, COALESCE(SUM(total), 0) as monto 
                              FROM compras 
                              WHERE DATE(fecha) = CURDATE() AND estado = 1");
        $compras_hoy = $stmt->fetch();
        $stats['compras_hoy'] = $compras_hoy['total'];
        $stats['monto_compras_hoy'] = $compras_hoy['monto'];

        require_once 'views/layout/header.php';
        require_once 'views/home/index.php';
        require_once 'views/layout/footer.php';
    }
}
?>

