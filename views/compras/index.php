<?php
$pageTitle = "Historial de Compras";
?>

<div class="max-w-7xl mx-auto space-y-6">
    <div class="card flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <p class="text-sm text-gray-500 uppercase tracking-wide">Compras</p>
            <h2 class="text-2xl font-semibold text-gray-800">Historial de Compras</h2>
            <p class="text-gray-600">Consulta de compras registradas</p>
        </div>
        <a href="<?php echo BASE_URL; ?>compras/nueva" class="btn-primary">
            <i data-lucide="plus" class="h-4 w-4 mr-2"></i> Nueva compra
        </a>
    </div>

    <div class="card">
        <form method="GET" action="<?php echo BASE_URL; ?>compras" id="formFiltros" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i data-lucide="search" class="inline h-4 w-4 mr-1"></i> Busqueda rapida
                    </label>
                    <input type="text" name="search" id="searchInput" value="<?php echo htmlspecialchars($search ?? ''); ?>"
                           placeholder="Buscar por folio, proveedor, usuario..."
                           class="input-modern"
                           autocomplete="off">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i data-lucide="calendar" class="inline h-4 w-4 mr-1"></i> Fecha desde
                    </label>
                    <input type="date" name="fechaDesde" value="<?php echo htmlspecialchars($fechaDesde ?? ''); ?>"
                           class="input-modern">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i data-lucide="calendar" class="inline h-4 w-4 mr-1"></i> Fecha hasta
                    </label>
                    <input type="date" name="fechaHasta" value="<?php echo htmlspecialchars($fechaHasta ?? ''); ?>"
                           class="input-modern">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i data-lucide="truck" class="inline h-4 w-4 mr-1"></i> Proveedor
                    </label>
                    <select name="idProveedor" class="input-modern">
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
                        <i data-lucide="dollar-sign" class="inline h-4 w-4 mr-1"></i> Total minimo
                    </label>
                    <input type="number" name="totalMin" value="<?php echo htmlspecialchars($totalMin ?? ''); ?>"
                           placeholder="0.00" step="0.01" min="0"
                           class="input-modern">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i data-lucide="dollar-sign" class="inline h-4 w-4 mr-1"></i> Total maximo
                    </label>
                    <input type="number" name="totalMax" value="<?php echo htmlspecialchars($totalMax ?? ''); ?>"
                           placeholder="0.00" step="0.01" min="0"
                           class="input-modern">
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary">
                    <i data-lucide="search" class="h-4 w-4 mr-2"></i> Buscar
                </button>
                <a href="<?php echo BASE_URL; ?>compras" class="btn-ghost">
                    <i data-lucide="x" class="h-4 w-4 mr-2"></i> Limpiar
                </a>
            </div>
        </form>
    </div>

    <div class="card table-shell p-0">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Folio</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proveedor</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($compras)): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <i data-lucide="inbox" class="h-10 w-10 mx-auto mb-2 text-gray-400"></i>
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
                                    <?php echo date('d/m/Y', strtotime($compra['fecha'])); ?>
                                    <div class="text-xs text-gray-400">
                                        <?php echo date('H:i', strtotime($compra['fecha'])); ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <?php echo htmlspecialchars($compra['proveedor_nombre']); ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <?php echo htmlspecialchars($compra['usuario_nombre'] . ' ' . $compra['usuario_apellidos']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-bold text-green-600">
                                        $<?php echo number_format($compra['total'], 2); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($compra['estado'] == 1): ?>
                                        <span class="pill bg-green-pastel/70 text-gray-800">Activa</span>
                                    <?php else: ?>
                                        <span class="pill bg-pink-pastel/70 text-gray-800">Anulada</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" onclick="event.stopPropagation()">
                                    <div class="flex items-center gap-2">
                                        <a href="<?php echo BASE_URL; ?>compras/detalle/<?php echo $compra['idCompra']; ?>"
                                           class="btn-ghost px-3 py-2">
                                           <i data-lucide="eye" class="h-4 w-4 mr-1"></i> Ver
                                        </a>
                                        <a href="<?php echo BASE_URL; ?>compras/comprobante/<?php echo $compra['idCompra']; ?>"
                                           target="_blank"
                                           class="btn-ghost px-3 py-2">
                                           <i data-lucide="file-down" class="h-4 w-4 mr-1"></i> Comprobante
                                        </a>
                                        <?php if ($compra['estado'] == 1 && $_SESSION['user_rol'] == ROL_ADMIN): ?>
                                            <a href="<?php echo BASE_URL; ?>compras/anular/<?php echo $compra['idCompra']; ?>"
                                               class="btn-ghost px-3 py-2 text-red-700"
                                               onclick="return confirmarEliminacion('Seguro de anular esta compra?');">
                                               <i data-lucide="ban" class="h-4 w-4 mr-1"></i> Anular
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
</div>
