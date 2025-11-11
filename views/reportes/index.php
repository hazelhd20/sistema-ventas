<?php
$pageTitle = "Reportes";
?>

<div class="mb-6">
    <h2 class="text-3xl font-bold text-gray-800">Reportes</h2>
    <p class="text-gray-600">Generación de reportes del sistema</p>
</div>

<!-- Filtros -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <form id="formReportes" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Desde</label>
            <input type="date" id="fechaDesde" value="<?php echo date('Y-m-01'); ?>" 
                   class="w-full px-3 py-2 border rounded">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Hasta</label>
            <input type="date" id="fechaHasta" value="<?php echo date('Y-m-d'); ?>" 
                   class="w-full px-3 py-2 border rounded">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Reporte</label>
            <select id="tipoReporte" class="w-full px-3 py-2 border rounded">
                <option value="ventas">Ventas</option>
                <option value="compras">Compras</option>
            </select>
        </div>
        <div class="flex items-end">
            <button type="button" onclick="generarReporte()" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                <i class="fas fa-chart-bar"></i> Generar Reporte
            </button>
        </div>
    </form>
</div>

<!-- Resultados -->
<div id="resultadosReporte" class="bg-white rounded-lg shadow p-6">
    <p class="text-gray-500 text-center">Seleccione los filtros y genere un reporte</p>
</div>

<script>
function generarReporte() {
    const fechaDesde = document.getElementById('fechaDesde').value;
    const fechaHasta = document.getElementById('fechaHasta').value;
    const tipo = document.getElementById('tipoReporte').value;
    const resultados = document.getElementById('resultadosReporte');
    
    resultados.innerHTML = '<p class="text-center">Cargando...</p>';
    
    fetch('<?php echo BASE_URL; ?>reportes/' + tipo + '?fechaDesde=' + fechaDesde + '&fechaHasta=' + fechaHasta)
        .then(response => response.json())
        .then(data => {
            let html = '<h3 class="text-xl font-bold mb-4">Reporte de ' + (tipo === 'ventas' ? 'Ventas' : 'Compras') + '</h3>';
            html += '<p class="mb-4"><strong>Periodo:</strong> ' + fechaDesde + ' al ' + fechaHasta + '</p>';
            
            if (tipo === 'ventas') {
                html += generarTablaVentas(data.ventas);
            } else {
                html += generarTablaCompras(data.compras);
            }
            
            html += '<div class="mt-6 p-4 bg-green-50 rounded"><p class="text-lg font-bold">Total: $' + parseFloat(data.total).toFixed(2) + '</p></div>';
            
            resultados.innerHTML = html;
        })
        .catch(error => {
            resultados.innerHTML = '<p class="text-red-600">Error al generar el reporte</p>';
            console.error('Error:', error);
        });
}

function generarTablaVentas(ventas) {
    if (ventas.length === 0) {
        return '<p class="text-gray-500">No hay ventas en el periodo seleccionado</p>';
    }
    
    let html = '<table class="min-w-full divide-y divide-gray-200"><thead class="bg-gray-50"><tr>';
    html += '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Folio</th>';
    html += '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>';
    html += '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>';
    html += '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>';
    html += '</tr></thead><tbody class="bg-white divide-y divide-gray-200">';
    
    ventas.forEach(venta => {
        html += '<tr>';
        html += '<td class="px-6 py-4 text-sm">#' + venta.idVenta + '</td>';
        html += '<td class="px-6 py-4 text-sm">' + venta.fecha + '</td>';
        html += '<td class="px-6 py-4 text-sm">' + (venta.cliente_nombre ? venta.cliente_nombre + ' ' + (venta.cliente_apellidos || '') : 'Público General') + '</td>';
        html += '<td class="px-6 py-4 text-sm font-semibold">$' + parseFloat(venta.total).toFixed(2) + '</td>';
        html += '</tr>';
    });
    
    html += '</tbody></table>';
    return html;
}

function generarTablaCompras(compras) {
    if (compras.length === 0) {
        return '<p class="text-gray-500">No hay compras en el periodo seleccionado</p>';
    }
    
    let html = '<table class="min-w-full divide-y divide-gray-200"><thead class="bg-gray-50"><tr>';
    html += '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Folio</th>';
    html += '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>';
    html += '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Proveedor</th>';
    html += '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>';
    html += '</tr></thead><tbody class="bg-white divide-y divide-gray-200">';
    
    compras.forEach(compra => {
        html += '<tr>';
        html += '<td class="px-6 py-4 text-sm">#' + compra.idCompra + '</td>';
        html += '<td class="px-6 py-4 text-sm">' + compra.fecha + '</td>';
        html += '<td class="px-6 py-4 text-sm">' + compra.proveedor_nombre + '</td>';
        html += '<td class="px-6 py-4 text-sm font-semibold">$' + parseFloat(compra.total).toFixed(2) + '</td>';
        html += '</tr>';
    });
    
    html += '</tbody></table>';
    return html;
}
</script>

