<?php
require_once 'controllers/ProductoController.php';

class ProductosController extends ProductoController {
    // Esta clase extiende ProductoController para mantener compatibilidad
    // con el sistema de enrutamiento que busca "ProductosController" cuando
    // la URL es "productos"
}
?>

