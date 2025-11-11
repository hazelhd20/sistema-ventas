<?php
require_once 'controllers/VentaController.php';

class VentasController extends VentaController {
    // Esta clase extiende VentaController para mantener compatibilidad
    // con el sistema de enrutamiento que busca "VentasController" cuando
    // la URL es "ventas"
}
?>

