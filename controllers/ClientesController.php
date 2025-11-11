<?php
require_once 'controllers/ClienteController.php';

class ClientesController extends ClienteController {
    // Esta clase extiende ClienteController para mantener compatibilidad
    // con el sistema de enrutamiento que busca "ClientesController" cuando
    // la URL es "clientes"
}
?>

