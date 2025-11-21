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
    <form method="GET" action="<?php echo BASE_URL; ?>compras" id="formFiltros" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="fas fa-search"></i> Búsqueda Rápida
                </label>
                <input type="text" name="search" id="searchInput" value="<?php echo htmlspecialchars($search ?? ''); ?>" 
                       placeholder="Buscar por folio, proveedor, usuario..." 
                       class="w-full px-4 py-2 border-2 border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       autocomplete="off">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="fas fa-calendar"></i> Fecha Desde
                </label>
                <input type="date" name="fechaDesde" value="<?php echo htmlspecialchars($fechaDesde ?? ''); ?>" 
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="fas fa-calendar"></i> Fecha Hasta
                </label>
                <input type="date" name="fechaHasta" value="<?php echo htmlspecialchars($fechaHasta ?? ''); ?>" 
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="fas fa-truck"></i> Proveedor
                </label>
                <select name="idProveedor" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
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
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="fas fa-dollar-sign"></i> Total Mínimo
                </label>
                <input type="number" name="totalMin" value="<?php echo htmlspecialchars($totalMin ?? ''); ?>" 
                       placeholder="0.00" step="0.01" min="0" 
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="fas fa-dollar-sign"></i> Total Máximo
                </label>
                <input type="number" name="totalMax" value="<?php echo htmlspecialchars($totalMax ?? ''); ?>" 
                       placeholder="0.00" step="0.01" min="0" 
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow hover:shadow-lg transition-all">
                <i class="fas fa-search"></i> Buscar
            </button>
            <a href="<?php echo BASE_URL; ?>compras" class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded-lg transition-all">
                <i class="fas fa-times"></i> Limpiar
            </a>
        </div>
    </form>
</div>

<!-- Tabla de compras -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Folio</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proveedor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($compras)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>No hay compras registradas</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($compras as $compra): ?>
                        <tr class="hover:bg-blue-50 transition-colors cursor-pointer" onclick="window.location.href='<?php echo BASE_URL; ?>compras/detalle/<?php echo $compra['idCompra']; ?>'">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-bold text-blue-600">#<?php echo $compra['idCompra']; ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <i class="fas fa-calendar text-xs mr-1"></i>
                                <?php echo date('d/m/Y', strtotime($compra['fecha'])); ?>
                                <br>
                                <span class="text-xs text-gray-400">
                                    <i class="fas fa-clock text-xs"></i>
                                    <?php echo date('H:i', strtotime($compra['fecha'])); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <i class="fas fa-truck text-xs text-gray-400 mr-1"></i>
                                <?php echo htmlspecialchars($compra['proveedor_nombre']); ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <i class="fas fa-user text-xs text-gray-400 mr-1"></i>
                                <?php echo htmlspecialchars($compra['usuario_nombre'] . ' ' . $compra['usuario_apellidos']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-bold text-green-600">
                                    $<?php echo number_format($compra['total'], 2); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($compra['estado'] == 1): ?>
                                    <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800 font-semibold">
                                        <i class="fas fa-check-circle"></i> Activa
                                    </span>
                                <?php else: ?>
                                    <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-800 font-semibold">
                                        <i class="fas fa-ban"></i> Anulada
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" onclick="event.stopPropagation()">
                                <div class="flex items-center gap-2">
                                    <a href="<?php echo BASE_URL; ?>compras/detalle/<?php echo $compra['idCompra']; ?>" 
                                       class="text-blue-600 hover:text-blue-900 p-2 hover:bg-blue-100 rounded-lg transition-all"
                                       title="Ver detalle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo BASE_URL; ?>compras/comprobante/<?php echo $compra['idCompra']; ?>" 
                                       target="_blank" 
                                       class="text-green-600 hover:text-green-900 p-2 hover:bg-green-100 rounded-lg transition-all"
                                       title="Ver comprobante PDF">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                    <?php if ($compra['estado'] == 1 && $_SESSION['user_rol'] == ROL_ADMIN): ?>
                                        <a href="<?php echo BASE_URL; ?>compras/anular/<?php echo $compra['idCompra']; ?>" 
                                           onclick="return confirmarEliminacion('¿Está seguro de anular esta compra?')" 
                                           class="text-red-600 hover:text-red-900 p-2 hover:bg-red-100 rounded-lg transition-all"
                                           title="Anular compra">
                                            <i class="fas fa-ban"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// Búsqueda rápida con debounce
let searchTimeout;
document.getElementById('searchInput')?.addEventListener('input', function(e) {
    clearTimeout(searchTimeout);
    const value = e.target.value.trim();
    
    if (value.length >= 2 || value.length === 0) {
        searchTimeout = setTimeout(() => {
            if (value.length >= 2 || value.length === 0) {
                document.getElementById('formFiltros').submit();
            }
        }, 500);
    }
});

// Enfocar búsqueda con Ctrl+F
document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
        e.preventDefault();
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.focus();
            searchInput.select();
        }
    }
});
</script>

