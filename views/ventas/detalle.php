<?php
$pageTitle = "Detalle de Venta";
?>

<div class="mb-6">
    <a href="<?php echo BASE_URL; ?>ventas" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
        <i class="fas fa-arrow-left"></i> Volver al historial
    </a>
    <h2 class="text-3xl font-bold text-gray-800">Detalle de Venta #<?php echo $venta['idVenta']; ?></h2>
</div>

<div class="bg-white rounded-lg shadow p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="text-lg font-semibold mb-4">Información de la Venta</h3>
            <div class="space-y-2">
                <p><span class="font-semibold">Folio:</span> #<?php echo $venta['idVenta']; ?></p>
                <p><span class="font-semibold">Fecha:</span> <?php echo date('d/m/Y H:i:s', strtotime($venta['fecha'])); ?></p>
                <p><span class="font-semibold">Cliente:</span> 
                    <?php 
                    if ($venta['cliente_nombre']) {
                        echo htmlspecialchars($venta['cliente_nombre'] . ' ' . ($venta['cliente_apellidos'] ?? ''));
                    } else {
                        echo 'Público General';
                    }
                    ?>
                </p>
                <p><span class="font-semibold">Vendedor:</span> <?php echo htmlspecialchars($venta['usuario_nombre'] . ' ' . $venta['usuario_apellidos']); ?></p>
                <p><span class="font-semibold">Forma de Pago:</span> <?php echo htmlspecialchars($venta['forma_pago_nombre']); ?></p>
                <p><span class="font-semibold">Estado:</span> 
                    <?php if ($venta['estado'] == 1): ?>
                        <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Activa</span>
                    <?php else: ?>
                        <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">Anulada</span>
                    <?php endif; ?>
                </p>
            </div>
        </div>
        <div>
            <h3 class="text-lg font-semibold mb-4">Total</h3>
            <div class="text-3xl font-bold text-green-600">
                $<?php echo number_format($venta['total'], 2); ?>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold mb-4">Productos Vendidos</h3>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cantidad</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Precio Unit.</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
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
    
    <div class="mt-6 flex space-x-4">
        <a href="<?php echo BASE_URL; ?>ventas/ticket/<?php echo $venta['idVenta']; ?>" 
           target="_blank" 
           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
            <i class="fas fa-file-pdf"></i> Imprimir Ticket
        </a>
        <?php if ($venta['estado'] == 1 && $_SESSION['user_rol'] == ROL_ADMIN): ?>
            <a href="<?php echo BASE_URL; ?>ventas/anular/<?php echo $venta['idVenta']; ?>" 
               onclick="return confirmarEliminacion('¿Está seguro de anular esta venta?')" 
               class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                <i class="fas fa-ban"></i> Anular Venta
            </a>
        <?php endif; ?>
    </div>
</div>

