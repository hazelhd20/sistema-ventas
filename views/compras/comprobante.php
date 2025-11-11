<?php
// Generar comprobante en formato HTML para impresión
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Compra #<?php echo $compra['idCompra']; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body class="p-8">
    <div class="max-w-md mx-auto bg-white border-2 border-gray-300 p-6">
        <!-- Encabezado -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold">EL MERCADITO</h1>
            <p class="text-sm text-gray-600">Calle 21 entre 10 y 12, Acanceh, Yucatán</p>
            <p class="text-sm text-gray-600">Tel: (999) 123-4567</p>
        </div>
        
        <div class="border-t border-b border-gray-300 py-4 mb-4">
            <h2 class="text-lg font-bold mb-3">COMPROBANTE DE COMPRA</h2>
            <div class="flex justify-between text-sm">
                <span class="font-semibold">Folio:</span>
                <span>#<?php echo $compra['idCompra']; ?></span>
            </div>
            <div class="flex justify-between text-sm mt-1">
                <span class="font-semibold">Fecha:</span>
                <span><?php echo date('d/m/Y H:i:s', strtotime($compra['fecha'])); ?></span>
            </div>
            <div class="flex justify-between text-sm mt-1">
                <span class="font-semibold">Proveedor:</span>
                <span><?php echo htmlspecialchars($compra['proveedor_nombre']); ?></span>
            </div>
            <div class="flex justify-between text-sm mt-1">
                <span class="font-semibold">Usuario:</span>
                <span><?php echo htmlspecialchars($compra['usuario_nombre'] . ' ' . $compra['usuario_apellidos']); ?></span>
            </div>
        </div>
        
        <!-- Productos -->
        <div class="mb-4">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-300">
                        <th class="text-left py-2">Producto</th>
                        <th class="text-center py-2">Cant.</th>
                        <th class="text-right py-2">Precio</th>
                        <th class="text-right py-2">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($compra['detalles'] as $detalle): ?>
                        <tr class="border-b border-gray-200">
                            <td class="py-2"><?php echo htmlspecialchars($detalle['producto_nombre']); ?></td>
                            <td class="text-center py-2"><?php echo $detalle['cantidad'] . ' ' . $detalle['medida_abrev']; ?></td>
                            <td class="text-right py-2">$<?php echo number_format($detalle['precioCompra'], 2); ?></td>
                            <td class="text-right py-2">$<?php echo number_format($detalle['subtotal'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Total -->
        <div class="border-t-2 border-gray-300 pt-4 mb-4">
            <div class="flex justify-between text-xl font-bold">
                <span>TOTAL:</span>
                <span>$<?php echo number_format($compra['total'], 2); ?></span>
            </div>
        </div>
        
        <!-- Pie -->
        <div class="text-center text-xs text-gray-600 border-t border-gray-300 pt-4">
            <p>Comprobante de compra</p>
            <p class="mt-2"><?php echo date('d/m/Y H:i:s'); ?></p>
        </div>
    </div>
    
    <div class="text-center mt-4 no-print">
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
            <i class="fas fa-print"></i> Imprimir
        </button>
        <button onclick="window.close()" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded ml-2">
            Cerrar
        </button>
    </div>
</body>
</html>

