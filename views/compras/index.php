<?php
$pageTitle = "Historial de Compras";
?>

<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Historial de Compras</h2>
        <p class="text-gray-600">Consulta de compras registradas</p>
    </div>
    <a href="<?php echo BASE_URL; ?>compras/nueva" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
        <i class="fas fa-plus"></i> Nueva Compra
    </a>
</div>

<!-- Filtros -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" action="<?php echo BASE_URL; ?>compras" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Búsqueda General</label>
                <input type="text" name="search" value="<?php echo htmlspecialchars($search ?? ''); ?>" 
                       placeholder="Folio, proveedor o usuario..." 
                       class="w-full px-3 py-2 border rounded">
            </div>
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
                <label class="block text-sm font-medium text-gray-700 mb-1">Proveedor</label>
                <select name="idProveedor" class="w-full px-3 py-2 border rounded">
                    <option value="">Todos</option>
                    <?php foreach ($proveedores ?? [] as $prov): ?>
                        <option value="<?php echo $prov['idProveedor']; ?>" 
                                <?php echo (isset($idProveedor) && $idProveedor == $prov['idProveedor']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($prov['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Total Mínimo</label>
                <input type="number" name="totalMin" value="<?php echo htmlspecialchars($totalMin ?? ''); ?>" 
                       placeholder="0.00" step="0.01" min="0" 
                       class="w-full px-3 py-2 border rounded">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Total Máximo</label>
                <input type="number" name="totalMax" value="<?php echo htmlspecialchars($totalMax ?? ''); ?>" 
                       placeholder="0.00" step="0.01" min="0" 
                       class="w-full px-3 py-2 border rounded">
            </div>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
                <i class="fas fa-search"></i> Buscar
            </button>
            <a href="<?php echo BASE_URL; ?>compras" class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded">
                <i class="fas fa-times"></i> Limpiar
            </a>
        </div>
    </form>
</div>

<!-- Tabla de compras -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Folio</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Proveedor</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuario</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php if (empty($compras)): ?>
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No hay compras registradas</td>
                </tr>
            <?php else: ?>
                <?php foreach ($compras as $compra): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">#<?php echo $compra['idCompra']; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo date('d/m/Y H:i', strtotime($compra['fecha'])); ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <?php echo htmlspecialchars($compra['proveedor_nombre']); ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <?php echo htmlspecialchars($compra['usuario_nombre'] . ' ' . $compra['usuario_apellidos']); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            $<?php echo number_format($compra['total'], 2); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if ($compra['estado'] == 1): ?>
                                <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Activa</span>
                            <?php else: ?>
                                <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">Anulada</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="<?php echo BASE_URL; ?>compras/detalle/<?php echo $compra['idCompra']; ?>" 
                               class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="<?php echo BASE_URL; ?>compras/comprobante/<?php echo $compra['idCompra']; ?>" 
                               target="_blank" class="text-green-600 hover:text-green-900 mr-3">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                            <?php if ($compra['estado'] == 1 && $_SESSION['user_rol'] == ROL_ADMIN): ?>
                                <a href="<?php echo BASE_URL; ?>compras/anular/<?php echo $compra['idCompra']; ?>" 
                                   onclick="return confirmarEliminacion('¿Está seguro de anular esta compra?')" 
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

