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
    public $precioCompra;
    public $precioVenta;
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
                  WHERE 1=1";
        
        if (!empty($search)) {
            $query .= " AND (CONCAT(COALESCE(p.nombre, ''), ' ', COALESCE(p.codigoBarras, ''), ' ', 
                        COALESCE(p.descripcion, ''), ' ', COALESCE(c.nombre, ''), ' ', 
                        COALESCE(CAST(p.codProducto AS CHAR), '')) LIKE :search)";
        }
        
        $query .= " ORDER BY p.nombre";
        
        $stmt = $this->conn->prepare($query);
        
        if (!empty($search)) {
            $searchParam = "%$search%";
            $stmt->bindValue(":search", $searchParam, PDO::PARAM_STR);
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

    public function search($term, bool $soloActivos = false) {
        $query = "SELECT p.*, m.abreviatura as medida_abrev, c.nombre as categoria_nombre
                  FROM " . $this->table . " p
                  INNER JOIN medidas m ON p.idMedida = m.idMedida
                  INNER JOIN categorias c ON p.idCategoria = c.idCategoria
                  WHERE (CONCAT(COALESCE(p.nombre, ''), ' ', COALESCE(p.codigoBarras, ''), ' ', 
                       COALESCE(p.descripcion, ''), ' ', COALESCE(c.nombre, ''), ' ', 
                       COALESCE(CAST(p.codProducto AS CHAR), '')) LIKE :term)";

        if ($soloActivos) {
            $query .= " AND p.estado = 1";
        }

        $query .= " ORDER BY p.nombre
                    LIMIT 20";
        
        $stmt = $this->conn->prepare($query);
        $termParam = "%$term%";
        $stmt->bindValue(":term", $termParam, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  (nombre, descripcion, idCategoria, idMedida, precioCompra, precioVenta, existencia, stockMinimo, codigoBarras, estado) 
                  VALUES (:nombre, :descripcion, :idCategoria, :idMedida, :precioCompra, :precioVenta, :existencia, :stockMinimo, :codigoBarras, :estado)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":idCategoria", $this->idCategoria);
        $stmt->bindParam(":idMedida", $this->idMedida);
        $stmt->bindParam(":precioCompra", $this->precioCompra);
        $stmt->bindParam(":precioVenta", $this->precioVenta);
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
                      idMedida = :idMedida, precioCompra = :precioCompra, precioVenta = :precioVenta, existencia = :existencia, 
                      stockMinimo = :stockMinimo, codigoBarras = :codigoBarras, estado = :estado 
                  WHERE codProducto = :codProducto";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":idCategoria", $this->idCategoria);
        $stmt->bindParam(":idMedida", $this->idMedida);
        $stmt->bindParam(":precioCompra", $this->precioCompra);
        $stmt->bindParam(":precioVenta", $this->precioVenta);
        $stmt->bindParam(":existencia", $this->existencia);
        $stmt->bindParam(":stockMinimo", $this->stockMinimo);
        $stmt->bindParam(":codigoBarras", $this->codigoBarras);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":codProducto", $this->codProducto);
        
        return $stmt->execute();
    }

    public function delete($id) {
        return $this->setEstado($id, 0);
    }

    public function setEstado($id, int $estado) {
        $query = "UPDATE " . $this->table . " SET estado = :estado WHERE codProducto = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":estado", $estado, PDO::PARAM_INT);
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

