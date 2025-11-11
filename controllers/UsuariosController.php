<?php
require_once 'controllers/UsuarioController.php';

class UsuariosController extends UsuarioController {
    // Esta clase extiende UsuarioController para mantener compatibilidad
    // con el sistema de enrutamiento que busca "UsuariosController" cuando
    // la URL es "usuarios"
}
?>

