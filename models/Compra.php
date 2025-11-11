<?php
require_once 'config/database.php';

class Compra {
    private $conn;
    private $table = "compras";
    private $tableDetalle = "detalle_compra";

    public $idCompra;
    public $idProveedor;
    public $idUsuario;
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
            // Insertar compra
            $query = "INSERT INTO " . $this->table . " 
                      (idProveedor, idUsuario, total, fecha, estado, observaciones) 
                      VALUES (:idProveedor, :idUsuario, :total, NOW(), :estado, :observaciones)";
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":idProveedor", $this->idProveedor);
            $stmt->bindParam(":idUsuario", $this->idUsuario);
            $stmt->bindParam(":total", $this->total);
            $stmt->bindParam(":estado", $this->estado);
            $stmt->bindParam(":observaciones", $this->observaciones);
            
            $stmt->execute();
            $idCompra = $this->conn->lastInsertId();
            
            // Insertar detalles
            foreach ($this->detalles as $detalle) {
                $queryDetalle = "INSERT INTO " . $this->tableDetalle . " 
                                 (idCompra, codProducto, cantidad, precioCompra, subtotal) 
                                 VALUES (:idCompra, :codProducto, :cantidad, :precioCompra, :subtotal)";
                
                $stmtDetalle = $this->conn->prepare($queryDetalle);
                
                $stmtDetalle->bindParam(":idCompra", $idCompra);
                $stmtDetalle->bindParam(":codProducto", $detalle['codProducto']);
                $stmtDetalle->bindParam(":cantidad", $detalle['cantidad']);
                $stmtDetalle->bindParam(":precioCompra", $detalle['precioCompra']);
                $stmtDetalle->bindParam(":subtotal", $detalle['subtotal']);
                
                $stmtDetalle->execute();
            }
            
            $this->conn->commit();
            return $idCompra;
            
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function getAll($fechaDesde = '', $fechaHasta = '', $idProveedor = '') {
        $query = "SELECT c.*, 
                         p.nombre as proveedor_nombre, p.contacto as proveedor_contacto,
                         u.nombre as usuario_nombre, u.apellidos as usuario_apellidos
                  FROM " . $this->table . " c
                  INNER JOIN proveedores p ON c.idProveedor = p.idProveedor
                  INNER JOIN usuarios u ON c.idUsuario = u.idUsuario
                  WHERE 1=1";
        
        if (!empty($fechaDesde)) {
            $query .= " AND DATE(c.fecha) >= :fechaDesde";
        }
        if (!empty($fechaHasta)) {
            $query .= " AND DATE(c.fecha) <= :fechaHasta";
        }
        if (!empty($idProveedor)) {
            $query .= " AND c.idProveedor = :idProveedor";
        }
        
        $query .= " ORDER BY c.fecha DESC, c.idCompra DESC";
        
        $stmt = $this->conn->prepare($query);
        
        if (!empty($fechaDesde)) {
            $stmt->bindParam(":fechaDesde", $fechaDesde);
        }
        if (!empty($fechaHasta)) {
            $stmt->bindParam(":fechaHasta", $fechaHasta);
        }
        if (!empty($idProveedor)) {
            $stmt->bindParam(":idProveedor", $idProveedor);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $query = "SELECT c.*, 
                         p.nombre as proveedor_nombre, p.contacto as proveedor_contacto, 
                         p.telefono as proveedor_telefono, p.direccion as proveedor_direccion,
                         u.nombre as usuario_nombre, u.apellidos as usuario_apellidos
                  FROM " . $this->table . " c
                  INNER JOIN proveedores p ON c.idProveedor = p.idProveedor
                  INNER JOIN usuarios u ON c.idUsuario = u.idUsuario
                  WHERE c.idCompra = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        $compra = $stmt->fetch();
        
        if ($compra) {
            // Obtener detalles
            $queryDetalle = "SELECT dc.*, pr.nombre as producto_nombre, m.abreviatura as medida_abrev
                            FROM " . $this->tableDetalle . " dc
                            INNER JOIN productos pr ON dc.codProducto = pr.codProducto
                            INNER JOIN medidas m ON pr.idMedida = m.idMedida
                            WHERE dc.idCompra = :idCompra";
            
            $stmtDetalle = $this->conn->prepare($queryDetalle);
            $stmtDetalle->bindParam(":idCompra", $id);
            $stmtDetalle->execute();
            
            $compra['detalles'] = $stmtDetalle->fetchAll();
        }
        
        return $compra;
    }

    public function anular($id) {
        $this->conn->beginTransaction();
        
        try {
            // Obtener detalles para revertir inventario
            $queryDetalle = "SELECT * FROM " . $this->tableDetalle . " WHERE idCompra = :id";
            $stmtDetalle = $this->conn->prepare($queryDetalle);
            $stmtDetalle->bindParam(":id", $id);
            $stmtDetalle->execute();
            $detalles = $stmtDetalle->fetchAll();
            
            // Eliminar detalles (trigger revertirá inventario)
            $queryDelete = "DELETE FROM " . $this->tableDetalle . " WHERE idCompra = :id";
            $stmtDelete = $this->conn->prepare($queryDelete);
            $stmtDelete->bindParam(":id", $id);
            $stmtDelete->execute();
            
            // Actualizar estado de compra
            $query = "UPDATE " . $this->table . " SET estado = 0 WHERE idCompra = :id";
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

