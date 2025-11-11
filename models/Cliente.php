<?php
require_once 'config/database.php';

class Cliente {
    private $conn;
    private $table = "clientes";

    public $idCliente;
    public $nombre;
    public $apellidos;
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
        $query = "SELECT * FROM " . $this->table . " WHERE estado = 1";
        
        if (!empty($search)) {
            $query .= " AND (nombre LIKE :search OR apellidos LIKE :search OR telefono LIKE :search)";
        }
        
        $query .= " ORDER BY nombre, apellidos";
        
        $stmt = $this->conn->prepare($query);
        
        if (!empty($search)) {
            $searchParam = "%$search%";
            $stmt->bindParam(":search", $searchParam);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE idCliente = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    public function search($term) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE estado = 1 
                  AND (nombre LIKE :term OR apellidos LIKE :term OR telefono LIKE :term)
                  LIMIT 20";
        
        $stmt = $this->conn->prepare($query);
        $termParam = "%$term%";
        $stmt->bindParam(":term", $termParam);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  (nombre, apellidos, telefono, email, direccion, rfc, estado) 
                  VALUES (:nombre, :apellidos, :telefono, :email, :direccion, :rfc, :estado)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":apellidos", $this->apellidos);
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
                  SET nombre = :nombre, apellidos = :apellidos, telefono = :telefono, 
                      email = :email, direccion = :direccion, rfc = :rfc, estado = :estado 
                  WHERE idCliente = :idCliente";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":apellidos", $this->apellidos);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":direccion", $this->direccion);
        $stmt->bindParam(":rfc", $this->rfc);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":idCliente", $this->idCliente);
        
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "UPDATE " . $this->table . " SET estado = 0 WHERE idCliente = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        
        return $stmt->execute();
    }
}
?>

