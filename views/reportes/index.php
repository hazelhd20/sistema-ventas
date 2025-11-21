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
            <label class="block text-sm font-medium text-gray-700 mb-1">
                <i class="fas fa-calendar"></i> Fecha Desde
            </label>
            <input type="date" id="fechaDesde" value="<?php echo date('Y-m-01'); ?>" 
                   class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                <i class="fas fa-calendar"></i> Fecha Hasta
            </label>
            <input type="date" id="fechaHasta" value="<?php echo date('Y-m-d'); ?>" 
                   class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                <i class="fas fa-chart-line"></i> Tipo de Reporte
            </label>
            <select id="tipoReporte" class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="ventas">Ventas</option>
                <option value="compras">Compras</option>
            </select>
        </div>
        <div class="flex items-end">
            <button type="button" onclick="generarReporte()" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow hover:shadow-lg transition-all">
                <i class="fas fa-chart-bar"></i> Generar Reporte
            </button>
        </div>
    </form>
</div>

<!-- Resultados -->
<div id="resultadosReporte" class="bg-white rounded-lg shadow p-6">
    <div class="text-center text-gray-500 py-8">
        <i class="fas fa-chart-bar text-4xl mb-2"></i>
        <p>Seleccione los filtros y genere un reporte</p>
    </div>
</div>

<script>
function generarReporte() {
    const fechaDesde = document.getElementById('fechaDesde').value;
    const fechaHasta = document.getElementById('fechaHasta').value;
    const tipo = document.getElementById('tipoReporte').value;
    const resultados = document.getElementById('resultadosReporte');
    
    if (!fechaDesde || !fechaHasta) {
        alert('Por favor seleccione ambas fechas');
        return;
    }
    
    if (fechaDesde > fechaHasta) {
        alert('La fecha desde no puede ser mayor que la fecha hasta');
        return;
    }
    
    resultados.innerHTML = `
        <div class="text-center py-8">
            <i class="fas fa-spinner fa-spin text-4xl text-blue-600 mb-2"></i>
            <p class="text-gray-600">Generando reporte...</p>
        </div>
    `;
    
    fetch('<?php echo BASE_URL; ?>reportes/' + tipo + '?fechaDesde=' + fechaDesde + '&fechaHasta=' + fechaHasta)
        .then(response => response.json())
        .then(data => {
            let html = `
                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">
                        <i class="fas fa-chart-${tipo === 'ventas' ? 'line' : 'bar'}"></i> 
                        Reporte de ${tipo === 'ventas' ? 'Ventas' : 'Compras'}
                    </h3>
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <i class="fas fa-calendar"></i>
                        <span><strong>Periodo:</strong> ${formatearFecha(fechaDesde)} al ${formatearFecha(fechaHasta)}</span>
                    </div>
                </div>
            `;
            
            if (tipo === 'ventas') {
                html += generarTablaVentas(data.ventas || []);
            } else {
                html += generarTablaCompras(data.compras || []);
            }
            
            html += `
                <div class="mt-6 p-6 bg-gradient-to-r from-green-50 to-blue-50 rounded-lg border-2 border-green-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Total del Periodo</p>
                            <p class="text-3xl font-bold text-green-600">$${parseFloat(data.total || 0).toFixed(2)}</p>
                        </div>
                        <i class="fas fa-dollar-sign text-4xl text-green-400"></i>
                    </div>
                </div>
            `;
            
            resultados.innerHTML = html;
        })
        .catch(error => {
            resultados.innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-exclamation-circle text-4xl text-red-600 mb-2"></i>
                    <p class="text-red-600 font-semibold">Error al generar el reporte</p>
                    <p class="text-sm text-gray-500 mt-2">Por favor intente nuevamente</p>
                </div>
            `;
            console.error('Error:', error);
        });
}

function formatearFecha(fecha) {
    const date = new Date(fecha + 'T00:00:00');
    return date.toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' });
}

function generarTablaVentas(ventas) {
    if (ventas.length === 0) {
        return `
            <div class="text-center py-8 bg-gray-50 rounded-lg">
                <i class="fas fa-inbox text-4xl text-gray-400 mb-2"></i>
                <p class="text-gray-500">No hay ventas en el periodo seleccionado</p>
            </div>
        `;
    }
    
    let html = `
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Folio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
    `;
    
    ventas.forEach(venta => {
        html += `
            <tr class="hover:bg-blue-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm font-semibold text-blue-600">#${venta.idVenta}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${venta.fecha}</td>
                <td class="px-6 py-4 text-sm text-gray-900">
                    <i class="fas fa-user text-xs text-gray-400 mr-1"></i>
                    ${venta.cliente_nombre ? venta.cliente_nombre + ' ' + (venta.cliente_apellidos || '') : '<span class="text-gray-400">Público General</span>'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm font-bold text-green-600">$${parseFloat(venta.total).toFixed(2)}</span>
                </td>
            </tr>
        `;
    });
    
    html += `
                </tbody>
            </table>
        </div>
    `;
    return html;
}

function generarTablaCompras(compras) {
    if (compras.length === 0) {
        return `
            <div class="text-center py-8 bg-gray-50 rounded-lg">
                <i class="fas fa-inbox text-4xl text-gray-400 mb-2"></i>
                <p class="text-gray-500">No hay compras en el periodo seleccionado</p>
            </div>
        `;
    }
    
    let html = `
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Folio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proveedor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
    `;
    
    compras.forEach(compra => {
        html += `
            <tr class="hover:bg-blue-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm font-semibold text-blue-600">#${compra.idCompra}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${compra.fecha}</td>
                <td class="px-6 py-4 text-sm text-gray-900">
                    <i class="fas fa-truck text-xs text-gray-400 mr-1"></i>
                    ${compra.proveedor_nombre}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm font-bold text-green-600">$${parseFloat(compra.total).toFixed(2)}</span>
                </td>
            </tr>
        `;
    });
    
    html += `
                </tbody>
            </table>
        </div>
    `;
    return html;
}
</script>

