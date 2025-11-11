<?php
$pageTitle = "Inicio";
?>

<div class="mb-6">
    <h2 class="text-3xl font-bold text-gray-800">Dashboard</h2>
    <p class="text-gray-600">Bienvenido al sistema de gestión de El Mercadito</p>
</div>

<!-- Estadísticas -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Productos -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total Productos</p>
                <p class="text-3xl font-bold text-gray-800"><?php echo number_format($stats['productos']); ?></p>
            </div>
            <div class="bg-blue-100 rounded-full p-4">
                <i class="fas fa-box text-blue-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Stock Bajo -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Stock Bajo</p>
                <p class="text-3xl font-bold text-orange-600"><?php echo number_format($stats['stock_bajo']); ?></p>
            </div>
            <div class="bg-orange-100 rounded-full p-4">
                <i class="fas fa-exclamation-triangle text-orange-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Ventas del Día -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Ventas Hoy</p>
                <p class="text-3xl font-bold text-green-600"><?php echo number_format($stats['ventas_hoy']); ?></p>
                <p class="text-sm text-gray-500">$<?php echo number_format($stats['monto_hoy'], 2); ?></p>
            </div>
            <div class="bg-green-100 rounded-full p-4">
                <i class="fas fa-shopping-cart text-green-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Compras del Día -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Compras Hoy</p>
                <p class="text-3xl font-bold text-purple-600"><?php echo number_format($stats['compras_hoy']); ?></p>
                <p class="text-sm text-gray-500">$<?php echo number_format($stats['monto_compras_hoy'], 2); ?></p>
            </div>
            <div class="bg-purple-100 rounded-full p-4">
                <i class="fas fa-shopping-bag text-purple-600 text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Accesos Rápidos -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <?php if (in_array($_SESSION['user_rol'], [ROL_ADMIN, ROL_VENDEDOR])): ?>
    <a href="<?php echo BASE_URL; ?>ventas/nueva" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition duration-200">
        <div class="flex items-center space-x-4">
            <div class="bg-blue-100 rounded-full p-4">
                <i class="fas fa-cash-register text-blue-600 text-3xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-800">Nueva Venta</h3>
                <p class="text-gray-600">Registrar una nueva venta</p>
            </div>
        </div>
    </a>
    <?php endif; ?>

    <?php if (in_array($_SESSION['user_rol'], [ROL_ADMIN, ROL_ENCARGADO])): ?>
    <a href="<?php echo BASE_URL; ?>compras/nueva" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition duration-200">
        <div class="flex items-center space-x-4">
            <div class="bg-green-100 rounded-full p-4">
                <i class="fas fa-cart-plus text-green-600 text-3xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-800">Nueva Compra</h3>
                <p class="text-gray-600">Registrar compra a proveedor</p>
            </div>
        </div>
    </a>
    <?php endif; ?>

    <a href="<?php echo BASE_URL; ?>productos" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition duration-200">
        <div class="flex items-center space-x-4">
            <div class="bg-orange-100 rounded-full p-4">
                <i class="fas fa-boxes text-orange-600 text-3xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-800">Productos</h3>
                <p class="text-gray-600">Gestionar inventario</p>
            </div>
        </div>
    </a>
</div>

