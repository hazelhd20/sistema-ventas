<?php
require_once 'config/database.php';

class Venta {
    private $conn;
    private $table = "ventas";
    private $tableDetalle = "detalle_venta";

    public $idVenta;
    public $idCliente;
    public $idUsuario;
    public $idFormaPago;
    public $total;
    public $fecha;
    public $estado;
    public $observaciones;
    public $detalles = [];

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create() {
        $this->conn->beginTransaction();
        
        try {
            // Insertar venta
            $query = "INSERT INTO " . $this->table . " 
                      (idCliente, idUsuario, idFormaPago, total, fecha, estado, observaciones) 
                      VALUES (:idCliente, :idUsuario, :idFormaPago, :total, NOW(), :estado, :observaciones)";
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":idCliente", $this->idCliente);
            $stmt->bindParam(":idUsuario", $this->idUsuario);
            $stmt->bindParam(":idFormaPago", $this->idFormaPago);
            $stmt->bindParam(":total", $this->total);
            $stmt->bindParam(":estado", $this->estado);
            $stmt->bindParam(":observaciones", $this->observaciones);
            
            $stmt->execute();
            $idVenta = $this->conn->lastInsertId();
            
            // Insertar detalles
            foreach ($this->detalles as $detalle) {
                $queryDetalle = "INSERT INTO " . $this->tableDetalle . " 
                                 (idVenta, codProducto, cantidad, precio, subtotal) 
                                 VALUES (:idVenta, :codProducto, :cantidad, :precio, :subtotal)";
                
                $stmtDetalle = $this->conn->prepare($queryDetalle);
                
                $stmtDetalle->bindParam(":idVenta", $idVenta);
                $stmtDetalle->bindParam(":codProducto", $detalle['codProducto']);
                $stmtDetalle->bindParam(":cantidad", $detalle['cantidad']);
                $stmtDetalle->bindParam(":precio", $detalle['precio']);
                $stmtDetalle->bindParam(":subtotal", $detalle['subtotal']);
                
                $stmtDetalle->execute();
            }
            
            $this->conn->commit();
            return $idVenta;
            
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function getAll($fechaDesde = '', $fechaHasta = '', $idCliente = '') {
        $query = "SELECT v.*, 
                         c.nombre as cliente_nombre, c.apellidos as cliente_apellidos,
                         u.nombre as usuario_nombre, u.apellidos as usuario_apellidos,
                         fp.nombre as forma_pago_nombre
                  FROM " . $this->table . " v
                  LEFT JOIN clientes c ON v.idCliente = c.idCliente
                  INNER JOIN usuarios u ON v.idUsuario = u.idUsuario
                  INNER JOIN forma_pago fp ON v.idFormaPago = fp.idFormaPago
                  WHERE 1=1";
        
        if (!empty($fechaDesde)) {
            $query .= " AND DATE(v.fecha) >= :fechaDesde";
        }
        if (!empty($fechaHasta)) {
            $query .= " AND DATE(v.fecha) <= :fechaHasta";
        }
        if (!empty($idCliente)) {
            $query .= " AND v.idCliente = :idCliente";
        }
        
        $query .= " ORDER BY v.fecha DESC, v.idVenta DESC";
        
        $stmt = $this->conn->prepare($query);
        
        if (!empty($fechaDesde)) {
            $stmt->bindParam(":fechaDesde", $fechaDesde);
        }
        if (!empty($fechaHasta)) {
            $stmt->bindParam(":fechaHasta", $fechaHasta);
        }
        if (!empty($idCliente)) {
            $stmt->bindParam(":idCliente", $idCliente);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $query = "SELECT v.*, 
                         c.nombre as cliente_nombre, c.apellidos as cliente_apellidos, c.telefono as cliente_telefono,
                         u.nombre as usuario_nombre, u.apellidos as usuario_apellidos,
                         fp.nombre as forma_pago_nombre
                  FROM " . $this->table . " v
                  LEFT JOIN clientes c ON v.idCliente = c.idCliente
                  INNER JOIN usuarios u ON v.idUsuario = u.idUsuario
                  INNER JOIN forma_pago fp ON v.idFormaPago = fp.idFormaPago
                  WHERE v.idVenta = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        $venta = $stmt->fetch();
        
        if ($venta) {
            // Obtener detalles
            $queryDetalle = "SELECT dv.*, p.nombre as producto_nombre, m.abreviatura as medida_abrev
                            FROM " . $this->tableDetalle . " dv
                            INNER JOIN productos p ON dv.codProducto = p.codProducto
                            INNER JOIN medidas m ON p.idMedida = m.idMedida
                            WHERE dv.idVenta = :idVenta";
            
            $stmtDetalle = $this->conn->prepare($queryDetalle);
            $stmtDetalle->bindParam(":idVenta", $id);
            $stmtDetalle->execute();
            
            $venta['detalles'] = $stmtDetalle->fetchAll();
        }
        
        return $venta;
    }

    public function anular($id) {
        $this->conn->beginTransaction();
        
        try {
            // Obtener detalles para revertir inventario
            $queryDetalle = "SELECT * FROM " . $this->tableDetalle . " WHERE idVenta = :id";
            $stmtDetalle = $this->conn->prepare($queryDetalle);
            $stmtDetalle->bindParam(":id", $id);
            $stmtDetalle->execute();
            $detalles = $stmtDetalle->fetchAll();
            
            // Eliminar detalles (trigger revertirá inventario)
            $queryDelete = "DELETE FROM " . $this->tableDetalle . " WHERE idVenta = :id";
            $stmtDelete = $this->conn->prepare($queryDelete);
            $stmtDelete->bindParam(":id", $id);
            $stmtDelete->execute();
            
            // Actualizar estado de venta
            $query = "UPDATE " . $this->table . " SET estado = 0 WHERE idVenta = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
}
?>

