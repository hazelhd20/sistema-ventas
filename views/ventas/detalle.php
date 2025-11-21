<?php
$pageTitle = "Detalle de Venta";
?>

<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="<?php echo BASE_URL; ?>ventas" class="btn-ghost px-3 py-2">
            <i data-lucide="arrow-left" class="h-4 w-4 mr-1"></i> Volver al historial
        </a>
        <h2 class="text-2xl font-semibold text-gray-800">Venta #<?php echo $venta['idVenta']; ?></h2>
    </div>

    <div class="card">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Informacion</h3>
                <p><span class="font-semibold">Folio:</span> #<?php echo $venta['idVenta']; ?></p>
                <p><span class="font-semibold">Fecha:</span> <?php echo date('d/m/Y H:i:s', strtotime($venta['fecha'])); ?></p>
                <p><span class="font-semibold">Cliente:</span>
                    <?php 
                    if ($venta['cliente_nombre']) {
                        echo htmlspecialchars($venta['cliente_nombre'] . ' ' . ($venta['cliente_apellidos'] ?? ''));
                    } else {
                        echo 'Publico General';
                    }
                    ?>
                </p>
                <p><span class="font-semibold">Vendedor:</span> <?php echo htmlspecialchars($venta['usuario_nombre'] . ' ' . $venta['usuario_apellidos']); ?></p>
                <p><span class="font-semibold">Forma de pago:</span> <?php echo htmlspecialchars($venta['forma_pago_nombre']); ?></p>
                <p><span class="font-semibold">Estado:</span>
                    <?php if ($venta['estado'] == 1): ?>
                        <span class="pill bg-green-pastel/70 text-gray-800">Activa</span>
                    <?php else: ?>
                        <span class="pill bg-pink-pastel/70 text-gray-800">Anulada</span>
                    <?php endif; ?>
                </p>
            </div>
            <div class="flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Total</h3>
                    <div class="text-3xl font-bold text-green-600">$<?php echo number_format($venta['total'], 2); ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <h3 class="text-lg font-semibold mb-4">Productos vendidos</h3>
        <div class="table-shell">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Unit.</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($venta['detalles'] as $detalle): ?>
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($detalle['producto_nombre']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo $detalle['cantidad'] . ' ' . $detalle['medida_abrev']; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                $<?php echo number_format($detalle['precio'], 2); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                $<?php echo number_format($detalle['subtotal'], 2); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right font-semibold">Total:</td>
                        <td class="px-6 py-4 whitespace-nowrap text-lg font-bold text-green-600">
                            $<?php echo number_format($venta['total'], 2); ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="mt-6 flex flex-wrap gap-3">
            <a href="<?php echo BASE_URL; ?>ventas/ticket/<?php echo $venta['idVenta']; ?>" 
               target="_blank" 
               class="btn-primary">
                <i data-lucide="printer" class="h-4 w-4 mr-2"></i> Imprimir Ticket
            </a>
            <?php if ($venta['estado'] == 1 && $_SESSION['user_rol'] == ROL_ADMIN): ?>
                <a href="<?php echo BASE_URL; ?>ventas/anular/<?php echo $venta['idVenta']; ?>" 
                   onclick="return confirmarEliminacion('Seguro de anular esta venta?')" 
                   class="btn-ghost text-red-700">
                    <i data-lucide="ban" class="h-4 w-4 mr-2"></i> Anular Venta
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
