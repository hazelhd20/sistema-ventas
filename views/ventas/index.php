<?php
$pageTitle = "Historial de Ventas";
?>

<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Historial de Ventas</h2>
        <p class="text-gray-600">Consulta de ventas registradas</p>
    </div>
    <a href="<?php echo BASE_URL; ?>ventas/nueva" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
        <i class="fas fa-plus"></i> Nueva Venta
    </a>
</div>

<!-- Filtros -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" action="<?php echo BASE_URL; ?>ventas" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Desde</label>
            <input type="date" name="fechaDesde" value="<?php echo htmlspecialchars($fechaDesde ?? ''); ?>" 
                   class="w-full px-3 py-2 border rounded">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Hasta</label>
            <input type="date" name="fechaHasta" value="<?php echo htmlspecialchars($fechaHasta ?? ''); ?>" 
                   class="w-full px-3 py-2 border rounded">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
            <input type="text" name="idCliente" value="<?php echo htmlspecialchars($idCliente ?? ''); ?>" 
                   placeholder="ID Cliente" class="w-full px-3 py-2 border rounded">
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                <i class="fas fa-search"></i> Buscar
            </button>
        </div>
    </form>
</div>

<!-- Tabla de ventas -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Folio</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vendedor</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Forma de Pago</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php if (empty($ventas)): ?>
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">No hay ventas registradas</td>
                </tr>
            <?php else: ?>
                <?php foreach ($ventas as $venta): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">#<?php echo $venta['idVenta']; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo date('d/m/Y H:i', strtotime($venta['fecha'])); ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <?php 
                            if ($venta['cliente_nombre']) {
                                echo htmlspecialchars($venta['cliente_nombre'] . ' ' . ($venta['cliente_apellidos'] ?? ''));
                            } else {
                                echo 'Público General';
                            }
                            ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <?php echo htmlspecialchars($venta['usuario_nombre'] . ' ' . $venta['usuario_apellidos']); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo htmlspecialchars($venta['forma_pago_nombre']); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            $<?php echo number_format($venta['total'], 2); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if ($venta['estado'] == 1): ?>
                                <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Activa</span>
                            <?php else: ?>
                                <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">Anulada</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="<?php echo BASE_URL; ?>ventas/detalle/<?php echo $venta['idVenta']; ?>" 
                               class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="<?php echo BASE_URL; ?>ventas/ticket/<?php echo $venta['idVenta']; ?>" 
                               target="_blank" class="text-green-600 hover:text-green-900 mr-3">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                            <?php if ($venta['estado'] == 1 && $_SESSION['user_rol'] == ROL_ADMIN): ?>
                                <a href="<?php echo BASE_URL; ?>ventas/anular/<?php echo $venta['idVenta']; ?>" 
                                   onclick="return confirmarEliminacion('¿Está seguro de anular esta venta?')" 
                                   class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-ban"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

