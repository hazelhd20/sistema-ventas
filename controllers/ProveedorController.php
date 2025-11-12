<?php
require_once 'controllers/AuthController.php';
require_once 'models/Proveedor.php';

class ProveedorController {
    private $auth;
    private $proveedorModel;

    public function __construct() {
        $this->auth = new AuthController();
        $this->auth->checkAuth();
        $this->auth->checkRole([ROL_ADMIN, ROL_ENCARGADO]);
        $this->proveedorModel = new Proveedor();
    }

    public function index() {
        $search = $_GET['search'] ?? '';
        $proveedores = $this->proveedorModel->getAll($search);
        
        $pageTitle = "Proveedores";
        require_once 'views/layout/header.php';
        require_once 'views/proveedores/index.php';
        require_once 'views/layout/footer.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->proveedorModel->nombre = $_POST['nombre'];
            $this->proveedorModel->contacto = $_POST['contacto'] ?? '';
            $this->proveedorModel->telefono = $_POST['telefono'] ?? '';
            $this->proveedorModel->email = $_POST['email'] ?? '';
            $this->proveedorModel->direccion = $_POST['direccion'] ?? '';
            $this->proveedorModel->rfc = $_POST['rfc'] ?? '';
            $this->proveedorModel->estado = 1;
            
            if ($this->proveedorModel->create()) {
                $_SESSION['success'] = 'Proveedor creado exitosamente';
            } else {
                $_SESSION['error'] = 'Error al crear el proveedor';
            }
            
            header('Location: ' . BASE_URL . 'proveedores');
            exit;
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->proveedorModel->idProveedor = $_POST['idProveedor'];
            $this->proveedorModel->nombre = $_POST['nombre'];
            $this->proveedorModel->contacto = $_POST['contacto'] ?? '';
            $this->proveedorModel->telefono = $_POST['telefono'] ?? '';
            $this->proveedorModel->email = $_POST['email'] ?? '';
            $this->proveedorModel->direccion = $_POST['direccion'] ?? '';
            $this->proveedorModel->rfc = $_POST['rfc'] ?? '';
            $this->proveedorModel->estado = $_POST['estado'];
            
            if ($this->proveedorModel->update()) {
                $_SESSION['success'] = 'Proveedor actualizado exitosamente';
            } else {
                $_SESSION['error'] = 'Error al actualizar el proveedor';
            }
            
            header('Location: ' . BASE_URL . 'proveedores');
            exit;
        }
    }

    public function delete($id) {
        if ($this->proveedorModel->delete($id)) {
            $_SESSION['success'] = 'Proveedor eliminado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar el proveedor';
        }
        
        header('Location: ' . BASE_URL . 'proveedores');
        exit;
    }

    public function search() {
        $term = $_GET['term'] ?? '';
        $proveedores = $this->proveedorModel->search($term);
        header('Content-Type: application/json');
        echo json_encode($proveedores);
    }
}
?>

