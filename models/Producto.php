<?php
require_once 'config/database.php';

class Producto {
    private $conn;
    private $table = "productos";

    public $codProducto;
    public $nombre;
    public $descripcion;
    public $idCategoria;
    public $idMedida;
    public $precio;
    public $existencia;
    public $stockMinimo;
    public $codigoBarras;
    public $estado;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll($search = '') {
        $query = "SELECT p.*, c.nombre as categoria_nombre, m.nombre as medida_nombre, m.abreviatura as medida_abrev
                  FROM " . $this->table . " p
                  INNER JOIN categorias c ON p.idCategoria = c.idCategoria
                  INNER JOIN medidas m ON p.idMedida = m.idMedida
                  WHERE p.estado = 1";
        
        if (!empty($search)) {
            $query .= " AND (p.nombre LIKE :search OR p.codigoBarras LIKE :search)";
        }
        
        $query .= " ORDER BY p.nombre";
        
        $stmt = $this->conn->prepare($query);
        
        if (!empty($search)) {
            $searchParam = "%$search%";
            $stmt->bindParam(":search", $searchParam);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $query = "SELECT p.*, c.nombre as categoria_nombre, m.nombre as medida_nombre, m.abreviatura as medida_abrev
                  FROM " . $this->table . " p
                  INNER JOIN categorias c ON p.idCategoria = c.idCategoria
                  INNER JOIN medidas m ON p.idMedida = m.idMedida
                  WHERE p.codProducto = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    public function search($term) {
        $query = "SELECT p.*, m.abreviatura as medida_abrev
                  FROM " . $this->table . " p
                  INNER JOIN medidas m ON p.idMedida = m.idMedida
                  WHERE p.estado = 1 
                  AND (p.nombre LIKE :term OR p.codigoBarras LIKE :term)
                  LIMIT 20";
        
        $stmt = $this->conn->prepare($query);
        $termParam = "%$term%";
        $stmt->bindParam(":term", $termParam);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  (nombre, descripcion, idCategoria, idMedida, precio, existencia, stockMinimo, codigoBarras, estado) 
                  VALUES (:nombre, :descripcion, :idCategoria, :idMedida, :precio, :existencia, :stockMinimo, :codigoBarras, :estado)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":idCategoria", $this->idCategoria);
        $stmt->bindParam(":idMedida", $this->idMedida);
        $stmt->bindParam(":precio", $this->precio);
        $stmt->bindParam(":existencia", $this->existencia);
        $stmt->bindParam(":stockMinimo", $this->stockMinimo);
        $stmt->bindParam(":codigoBarras", $this->codigoBarras);
        $stmt->bindParam(":estado", $this->estado);
        
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET nombre = :nombre, descripcion = :descripcion, idCategoria = :idCategoria, 
                      idMedida = :idMedida, precio = :precio, existencia = :existencia, 
                      stockMinimo = :stockMinimo, codigoBarras = :codigoBarras, estado = :estado 
                  WHERE codProducto = :codProducto";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":idCategoria", $this->idCategoria);
        $stmt->bindParam(":idMedida", $this->idMedida);
        $stmt->bindParam(":precio", $this->precio);
        $stmt->bindParam(":existencia", $this->existencia);
        $stmt->bindParam(":stockMinimo", $this->stockMinimo);
        $stmt->bindParam(":codigoBarras", $this->codigoBarras);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":codProducto", $this->codProducto);
        
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "UPDATE " . $this->table . " SET estado = 0 WHERE codProducto = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        
        return $stmt->execute();
    }

    public function getStockBajo() {
        $query = "SELECT p.*, c.nombre as categoria_nombre, m.abreviatura as medida_abrev
                  FROM " . $this->table . " p
                  INNER JOIN categorias c ON p.idCategoria = c.idCategoria
                  INNER JOIN medidas m ON p.idMedida = m.idMedida
                  WHERE p.estado = 1 AND p.existencia <= p.stockMinimo
                  ORDER BY p.existencia ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
}
?>

