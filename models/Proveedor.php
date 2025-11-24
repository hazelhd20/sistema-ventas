<?php
require_once 'config/database.php';

class Proveedor {
    private $conn;
    private $table = "proveedores";

    public $idProveedor;
    public $nombre;
    public $contacto;
    public $telefono;
    public $email;
    public $direccion;
    public $rfc;
    public $estado;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll($search = '') {
        $query = "SELECT * FROM " . $this->table . " WHERE 1=1";
        
        if (!empty($search)) {
            $query .= " AND (CONCAT(COALESCE(nombre, ''), ' ', COALESCE(contacto, ''), ' ', 
                        COALESCE(telefono, ''), ' ', COALESCE(email, ''), ' ', 
                        COALESCE(rfc, '')) LIKE :search)";
        }
        
        $query .= " ORDER BY nombre";
        
        $stmt = $this->conn->prepare($query);
        
        if (!empty($search)) {
            $searchParam = "%$search%";
            $stmt->bindValue(":search", $searchParam, PDO::PARAM_STR);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE idProveedor = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    public function search($term) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE (CONCAT(COALESCE(nombre, ''), ' ', COALESCE(contacto, ''), ' ', 
                       COALESCE(telefono, ''), ' ', COALESCE(email, ''), ' ', 
                       COALESCE(rfc, '')) LIKE :term)
                  ORDER BY nombre
                  LIMIT 20";
        
        $stmt = $this->conn->prepare($query);
        $termParam = "%$term%";
        $stmt->bindValue(":term", $termParam, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  (nombre, contacto, telefono, email, direccion, rfc, estado) 
                  VALUES (:nombre, :contacto, :telefono, :email, :direccion, :rfc, :estado)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":contacto", $this->contacto);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":direccion", $this->direccion);
        $stmt->bindParam(":rfc", $this->rfc);
        $stmt->bindParam(":estado", $this->estado);
        
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET nombre = :nombre, contacto = :contacto, telefono = :telefono, 
                      email = :email, direccion = :direccion, rfc = :rfc, estado = :estado 
                  WHERE idProveedor = :idProveedor";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":contacto", $this->contacto);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":direccion", $this->direccion);
        $stmt->bindParam(":rfc", $this->rfc);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":idProveedor", $this->idProveedor);
        
        return $stmt->execute();
    }

    public function delete($id) {
        return $this->setEstado($id, 0);
    }

    public function setEstado($id, int $estado) {
        $query = "UPDATE " . $this->table . " SET estado = :estado WHERE idProveedor = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":estado", $estado, PDO::PARAM_INT);
        $stmt->bindParam(":id", $id);

        return $stmt->execute();
    }
}
?>

