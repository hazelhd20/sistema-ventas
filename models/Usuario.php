<?php
require_once 'config/database.php';

class Usuario {
    private $conn;
    private $table = "usuarios";

    public $idUsuario;
    public $idRol;
    public $nombre;
    public $apellidos;
    public $email;
    public $usuario;
    public $password;
    public $telefono;
    public $estado;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function login($usuario, $password) {
        $query = "SELECT u.*, r.nombre as rol_nombre 
                  FROM " . $this->table . " u 
                  INNER JOIN roles r ON u.idRol = r.idRol 
                  WHERE u.usuario = :usuario AND u.estado = 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":usuario", $usuario);
        $stmt->execute();
        
        $row = $stmt->fetch();
        
        if ($row && password_verify($password, $row['password'])) {
            return $row;
        }
        
        return false;
    }

    public function getAll() {
        $query = "SELECT u.*, r.nombre as rol_nombre 
                  FROM " . $this->table . " u 
                  INNER JOIN roles r ON u.idRol = r.idRol 
                  ORDER BY u.nombre";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $query = "SELECT u.*, r.nombre as rol_nombre 
                  FROM " . $this->table . " u 
                  INNER JOIN roles r ON u.idRol = r.idRol 
                  WHERE u.idUsuario = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  (idRol, nombre, apellidos, email, usuario, password, telefono, estado) 
                  VALUES (:idRol, :nombre, :apellidos, :email, :usuario, :password, :telefono, :estado)";
        
        $stmt = $this->conn->prepare($query);
        
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
        
        $stmt->bindParam(":idRol", $this->idRol);
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":apellidos", $this->apellidos);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":usuario", $this->usuario);
        $stmt->bindParam(":password", $hashedPassword);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":estado", $this->estado);
        
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET idRol = :idRol, nombre = :nombre, apellidos = :apellidos, 
                      email = :email, usuario = :usuario, telefono = :telefono, estado = :estado 
                  WHERE idUsuario = :idUsuario";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":idRol", $this->idRol);
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":apellidos", $this->apellidos);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":usuario", $this->usuario);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":idUsuario", $this->idUsuario);
        
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "UPDATE " . $this->table . " SET estado = 0 WHERE idUsuario = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        
        return $stmt->execute();
    }
}
?>

