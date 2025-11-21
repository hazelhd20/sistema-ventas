<?php
$pageTitle = "Historial de Ventas";
?>

<div class="max-w-7xl mx-auto space-y-6">
    <div class="card flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <p class="text-sm text-gray-500 uppercase tracking-wide">Ventas</p>
            <h2 class="text-2xl font-semibold text-gray-800">Historial de Ventas</h2>
            <p class="text-gray-600">Consulta de ventas registradas</p>
        </div>
        <a href="<?php echo BASE_URL; ?>ventas/nueva" class="btn-primary">
            <i data-lucide="plus" class="h-4 w-4 mr-2"></i> Nueva venta
        </a>
    </div>

    <div class="card">
        <form method="GET" action="<?php echo BASE_URL; ?>ventas" id="formFiltros" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i data-lucide="search" class="inline h-4 w-4 mr-1"></i> Busqueda rapida
                    </label>
                    <input type="text" name="search" id="searchInput" value="<?php echo htmlspecialchars($search ?? ''); ?>"
                           placeholder="Buscar por folio, cliente, vendedor o forma de pago..."
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
                        <i data-lucide="user" class="inline h-4 w-4 mr-1"></i> Cliente (ID)
                    </label>
                    <input type="text" name="idCliente" value="<?php echo htmlspecialchars($idCliente ?? ''); ?>"
                           placeholder="ID Cliente" class="input-modern">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i data-lucide="user-check" class="inline h-4 w-4 mr-1"></i> Vendedor
                    </label>
                    <select name="idUsuario" class="input-modern">
                        <option value="">Todos</option>
                        <?php foreach ($usuarios ?? [] as $usuario): ?>
                            <option value="<?php echo $usuario['idUsuario']; ?>"
                                <?php echo (isset($idUsuario) && $idUsuario == $usuario['idUsuario']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellidos']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i data-lucide="credit-card" class="inline h-4 w-4 mr-1"></i> Forma de pago
                    </label>
                    <select name="idFormaPago" class="input-modern">
                        <option value="">Todas</option>
                        <?php foreach ($formasPago ?? [] as $fp): ?>
                            <option value="<?php echo $fp['idFormaPago']; ?>"
                                <?php echo (isset($idFormaPago) && $idFormaPago == $fp['idFormaPago']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($fp['nombre']); ?>
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
                <a href="<?php echo BASE_URL; ?>ventas" class="btn-ghost">
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
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendedor</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Forma de pago</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($ventas)): ?>
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                <i data-lucide="inbox" class="h-10 w-10 mx-auto mb-2 text-gray-400"></i>
                                <p>No hay ventas registradas</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($ventas as $venta): ?>
                            <tr class="hover:bg-blue-50 transition-colors cursor-pointer" onclick="window.location.href='<?php echo BASE_URL; ?>ventas/detalle/<?php echo $venta['idVenta']; ?>'">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-bold text-blue-600">#<?php echo $venta['idVenta']; ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <?php echo date('d/m/Y', strtotime($venta['fecha'])); ?>
                                    <div class="text-xs text-gray-400">
                                        <?php echo date('H:i', strtotime($venta['fecha'])); ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <?php echo $venta['cliente_nombre'] ? htmlspecialchars($venta['cliente_nombre']) : 'General'; ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <?php echo htmlspecialchars($venta['usuario_nombre'] . ' ' . $venta['usuario_apellidos']); ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <?php echo htmlspecialchars($venta['forma_pago_nombre'] ?? ''); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-bold text-green-600">
                                        $<?php echo number_format($venta['total'], 2); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($venta['estado'] == 1): ?>
                                        <span class="pill bg-green-pastel/70 text-gray-800">Activa</span>
                                    <?php else: ?>
                                        <span class="pill bg-pink-pastel/70 text-gray-800">Anulada</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" onclick="event.stopPropagation()">
                                    <div class="flex items-center gap-2">
                                        <a href="<?php echo BASE_URL; ?>ventas/detalle/<?php echo $venta['idVenta']; ?>"
                                           class="btn-ghost px-3 py-2">
                                            <i data-lucide="eye" class="h-4 w-4 mr-1"></i> Ver
                                        </a>
                                        <a href="<?php echo BASE_URL; ?>ventas/ticket/<?php echo $venta['idVenta']; ?>"
                                           target="_blank"
                                           class="btn-ghost px-3 py-2">
                                            <i data-lucide="printer" class="h-4 w-4 mr-1"></i> Ticket
                                        </a>
                                        <?php if ($venta['estado'] == 1 && $_SESSION['user_rol'] == ROL_ADMIN): ?>
                                            <a href="<?php echo BASE_URL; ?>ventas/anular/<?php echo $venta['idVenta']; ?>"
                                               class="btn-ghost px-3 py-2 text-red-700"
                                               onclick="return confirmarEliminacion('Seguro de anular esta venta?');">
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
