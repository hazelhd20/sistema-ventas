<?php
require_once 'controllers/AuthController.php';
require_once 'models/Cliente.php';

class ClienteController {
    private $auth;
    private $clienteModel;

    public function __construct() {
        $this->auth = new AuthController();
        $this->auth->checkAuth();
        $this->clienteModel = new Cliente();
    }

    public function index() {
        $search = $_GET['search'] ?? '';
        $clientes = $this->clienteModel->getAll($search);
        
        $pageTitle = "Clientes";
        require_once 'views/layout/header.php';
        require_once 'views/clientes/index.php';
        require_once 'views/layout/footer.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->clienteModel->nombre = $_POST['nombre'];
            $this->clienteModel->apellidos = $_POST['apellidos'] ?? '';
            $this->clienteModel->telefono = $_POST['telefono'] ?? '';
            $this->clienteModel->email = $_POST['email'] ?? '';
            $this->clienteModel->direccion = $_POST['direccion'] ?? '';
            $this->clienteModel->rfc = $_POST['rfc'] ?? '';
            $this->clienteModel->estado = 1;
            
            if ($this->clienteModel->create()) {
                $_SESSION['success'] = 'Cliente creado exitosamente';
            } else {
                $_SESSION['error'] = 'Error al crear el cliente';
            }
            
            header('Location: ' . BASE_URL . 'clientes');
            exit;
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->clienteModel->idCliente = $_POST['idCliente'];
            $this->clienteModel->nombre = $_POST['nombre'];
            $this->clienteModel->apellidos = $_POST['apellidos'] ?? '';
            $this->clienteModel->telefono = $_POST['telefono'] ?? '';
            $this->clienteModel->email = $_POST['email'] ?? '';
            $this->clienteModel->direccion = $_POST['direccion'] ?? '';
            $this->clienteModel->rfc = $_POST['rfc'] ?? '';
            $this->clienteModel->estado = $_POST['estado'];
            
            if ($this->clienteModel->update()) {
                $_SESSION['success'] = 'Cliente actualizado exitosamente';
            } else {
                $_SESSION['error'] = 'Error al actualizar el cliente';
            }
            
            header('Location: ' . BASE_URL . 'clientes');
            exit;
        }
    }

    public function delete($id) {
        if ($this->clienteModel->delete($id)) {
            $_SESSION['success'] = 'Cliente eliminado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar el cliente';
        }
        
        header('Location: ' . BASE_URL . 'clientes');
        exit;
    }

    public function search() {
        $term = $_GET['term'] ?? '';
        $clientes = $this->clienteModel->search($term);
        header('Content-Type: application/json');
        echo json_encode($clientes);
    }
}
?>

