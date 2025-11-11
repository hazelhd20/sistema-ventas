<?php
require_once dirname(__DIR__) . '/../config/config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?><?php echo SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-store text-2xl"></i>
                    <h1 class="text-xl font-bold">El Mercadito</h1>
                </div>
                <div class="flex items-center space-x-6">
                    <span class="text-sm">
                        <i class="fas fa-user"></i> <?php echo $_SESSION['user_nombre']; ?>
                        <span class="ml-2 px-2 py-1 bg-blue-700 rounded text-xs"><?php echo $_SESSION['user_rol_nombre']; ?></span>
                    </span>
                    <a href="<?php echo BASE_URL; ?>auth/logout" class="hover:text-blue-200">
                        <i class="fas fa-sign-out-alt"></i> Salir
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg min-h-screen">
            <nav class="p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="<?php echo BASE_URL; ?>home" class="flex items-center space-x-2 p-3 rounded hover:bg-blue-50 <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php' && (!isset($_GET['url']) || $_GET['url'] == 'home')) ? 'bg-blue-100 text-blue-600' : 'text-gray-700'; ?>">
                            <i class="fas fa-home w-5"></i>
                            <span>Inicio</span>
                        </a>
                    </li>
                    
                    <?php if (in_array($_SESSION['user_rol'], [ROL_ADMIN, ROL_VENDEDOR])): ?>
                    <li>
                        <a href="<?php echo BASE_URL; ?>ventas" class="flex items-center space-x-2 p-3 rounded hover:bg-blue-50 <?php echo (isset($_GET['url']) && strpos($_GET['url'], 'ventas') !== false) ? 'bg-blue-100 text-blue-600' : 'text-gray-700'; ?>">
                            <i class="fas fa-shopping-cart w-5"></i>
                            <span>Ventas</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if (in_array($_SESSION['user_rol'], [ROL_ADMIN, ROL_ENCARGADO])): ?>
                    <li>
                        <a href="<?php echo BASE_URL; ?>compras" class="flex items-center space-x-2 p-3 rounded hover:bg-blue-50 <?php echo (isset($_GET['url']) && strpos($_GET['url'], 'compras') !== false) ? 'bg-blue-100 text-blue-600' : 'text-gray-700'; ?>">
                            <i class="fas fa-shopping-bag w-5"></i>
                            <span>Compras</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <li>
                        <a href="<?php echo BASE_URL; ?>productos" class="flex items-center space-x-2 p-3 rounded hover:bg-blue-50 <?php echo (isset($_GET['url']) && strpos($_GET['url'], 'productos') !== false) ? 'bg-blue-100 text-blue-600' : 'text-gray-700'; ?>">
                            <i class="fas fa-box w-5"></i>
                            <span>Productos</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="<?php echo BASE_URL; ?>clientes" class="flex items-center space-x-2 p-3 rounded hover:bg-blue-50 <?php echo (isset($_GET['url']) && strpos($_GET['url'], 'clientes') !== false) ? 'bg-blue-100 text-blue-600' : 'text-gray-700'; ?>">
                            <i class="fas fa-users w-5"></i>
                            <span>Clientes</span>
                        </a>
                    </li>
                    
                    <?php if (in_array($_SESSION['user_rol'], [ROL_ADMIN, ROL_ENCARGADO])): ?>
                    <li>
                        <a href="<?php echo BASE_URL; ?>proveedores" class="flex items-center space-x-2 p-3 rounded hover:bg-blue-50 <?php echo (isset($_GET['url']) && strpos($_GET['url'], 'proveedores') !== false) ? 'bg-blue-100 text-blue-600' : 'text-gray-700'; ?>">
                            <i class="fas fa-truck w-5"></i>
                            <span>Proveedores</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <li>
                        <a href="<?php echo BASE_URL; ?>reportes" class="flex items-center space-x-2 p-3 rounded hover:bg-blue-50 <?php echo (isset($_GET['url']) && strpos($_GET['url'], 'reportes') !== false) ? 'bg-blue-100 text-blue-600' : 'text-gray-700'; ?>">
                            <i class="fas fa-chart-bar w-5"></i>
                            <span>Reportes</span>
                        </a>
                    </li>
                    
                    <?php if ($_SESSION['user_rol'] == ROL_ADMIN): ?>
                    <li>
                        <a href="<?php echo BASE_URL; ?>usuarios" class="flex items-center space-x-2 p-3 rounded hover:bg-blue-50 <?php echo (isset($_GET['url']) && strpos($_GET['url'], 'usuarios') !== false) ? 'bg-blue-100 text-blue-600' : 'text-gray-700'; ?>">
                            <i class="fas fa-user-cog w-5"></i>
                            <span>Usuarios</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>configuracion" class="flex items-center space-x-2 p-3 rounded hover:bg-blue-50 <?php echo (isset($_GET['url']) && strpos($_GET['url'], 'configuracion') !== false) ? 'bg-blue-100 text-blue-600' : 'text-gray-700'; ?>">
                            <i class="fas fa-cog w-5"></i>
                            <span>Configuración</span>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

