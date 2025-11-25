<?php
$pageTitle = "Detalle de Compra";
?>

<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="<?php echo BASE_URL; ?>compras/nueva" class="btn-ghost px-3 py-2">
            <i data-lucide="arrow-left" class="h-4 w-4 mr-1"></i> Realizar otra compra
        </a>
        <h2 class="text-2xl font-semibold text-gray-800">Compra #<?php echo $compra['idCompra']; ?></h2>
    </div>

    <div class="card">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Informacion de la Compra</h3>
                <p><span class="font-semibold">Folio:</span> #<?php echo $compra['idCompra']; ?></p>
                <p><span class="font-semibold">Fecha:</span> <?php echo date('d/m/Y H:i:s', strtotime($compra['fecha'])); ?></p>
                <p><span class="font-semibold">Proveedor:</span> <?php echo htmlspecialchars($compra['proveedor_nombre']); ?></p>
                <p><span class="font-semibold">Contacto:</span> <?php echo htmlspecialchars($compra['proveedor_contacto'] ?? '-'); ?></p>
                <p><span class="font-semibold">Telefono:</span> <?php echo htmlspecialchars($compra['proveedor_telefono'] ?? '-'); ?></p>
                <p><span class="font-semibold">Usuario:</span> <?php echo htmlspecialchars($compra['usuario_nombre'] . ' ' . $compra['usuario_apellidos']); ?></p>
                <p><span class="font-semibold">Estado:</span>
                    <?php if ($compra['estado'] == 1): ?>
                        <span class="pill bg-green-pastel/70 text-gray-800">Activa</span>
                    <?php else: ?>
                        <span class="pill bg-pink-pastel/70 text-gray-800">Anulada</span>
                    <?php endif; ?>
                </p>
            </div>
            <div class="flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Total</h3>
                    <div class="text-3xl font-bold text-green-600">
                        $<?php echo number_format($compra['total'], 2); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <h3 class="text-lg font-semibold mb-4">Productos Comprados</h3>
        <div class="table-shell">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase">Cantidad</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase">Precio Compra</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($compra['detalles'] as $detalle): ?>
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($detalle['producto_nombre']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo $detalle['cantidad'] . ' ' . $detalle['medida_abrev']; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                $<?php echo number_format($detalle['precioCompra'], 2); ?>
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
                            $<?php echo number_format($compra['total'], 2); ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="mt-6 flex flex-wrap gap-3">
            <a href="<?php echo BASE_URL; ?>compras/comprobante/<?php echo $compra['idCompra']; ?>" 
               target="_blank" 
               class="btn-primary">
                <i data-lucide="file-down" class="h-4 w-4 mr-2"></i> Comprobante
            </a>
            <?php if ($compra['estado'] == 1 && $_SESSION['user_rol'] == ROL_ADMIN): ?>
                <a href="<?php echo BASE_URL; ?>compras/anular/<?php echo $compra['idCompra']; ?>" 
                   onclick="return confirmarEliminacion('Seguro de anular esta compra?')" 
                   class="btn-ghost text-red-700">
                    <i data-lucide="ban" class="h-4 w-4 mr-2"></i> Anular Compra
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
