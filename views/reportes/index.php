<?php
$pageTitle = "Reportes";
?>

<div class="max-w-7xl mx-auto space-y-6">
    <div class="card flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <p class="text-sm text-gray-500 uppercase tracking-wide">Reportes</p>
            <h2 class="text-2xl font-semibold text-gray-800">Generacion de reportes</h2>
            <p class="text-gray-600">Consulta ventas o compras por rango de fechas</p>
        </div>
    </div>

    <div class="card">
        <form id="formReportes" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <i data-lucide="calendar" class="inline h-4 w-4 mr-1"></i> Fecha desde
                </label>
                <input type="date" id="fechaDesde" value="<?php echo date('Y-m-01'); ?>" class="input-modern">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <i data-lucide="calendar" class="inline h-4 w-4 mr-1"></i> Fecha hasta
                </label>
                <input type="date" id="fechaHasta" value="<?php echo date('Y-m-d'); ?>" class="input-modern">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <i data-lucide="bar-chart" class="inline h-4 w-4 mr-1"></i> Tipo de reporte
                </label>
                <select id="tipoReporte" class="input-modern">
                    <option value="ventas">Ventas</option>
                    <option value="compras">Compras</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="button" onclick="generarReporte()" class="btn-primary w-full">
                    <i data-lucide="chart-bar-big" class="h-4 w-4 mr-2"></i> Generar reporte
                </button>
            </div>
        </form>
    </div>

    <div id="resultadosReporte" class="card">
        <div class="text-center text-gray-500 py-8">
            <i data-lucide="bar-chart-3" class="h-10 w-10 mx-auto mb-2"></i>
            <p>Seleccione los filtros y genere un reporte</p>
        </div>
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
                <h3 class="text-xl font-semibold text-gray-800 mb-4">
                    Reporte de ${tipo.charAt(0).toUpperCase() + tipo.slice(1)} (${fechaDesde} a ${fechaHasta})
                </h3>
            `;
            
            if (data.length === 0) {
                html += `
                    <div class="text-center text-gray-500 py-8">
                        <i class="fas fa-inbox text-4xl mb-2"></i>
                        <p>No se encontraron resultados</p>
                    </div>
                `;
            } else {
                const total = data.reduce((acc, item) => acc + parseFloat(item.total), 0);
                html += `
                    <div class="mb-4 flex items-center justify-between">
                        <span class="pill bg-green-pastel/70 text-gray-800">Total registros: ${data.length}</span>
                        <span class="pill bg-blue-pastel/70 text-gray-800">Monto total: $${total.toFixed(2)}</span>
                    </div>
                    <div class="table-shell">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Folio</th>
                                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>`;
                if (tipo === 'ventas') {
                    html += `<th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>`;
                } else {
                    html += `<th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proveedor</th>`;
                }
                html += `
                                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                `;
                
                data.forEach(item => {
                    html += `
                        <tr>
                            <td class="px-4 py-3 text-sm font-semibold text-blue-600">#${item.id}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">${item.fecha}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">${item.usuario}</td>
                    `;
                    if (tipo === 'ventas') {
                        html += `<td class="px-4 py-3 text-sm text-gray-700">${item.cliente || 'General'}</td>`;
                    } else {
                        html += `<td class="px-4 py-3 text-sm text-gray-700">${item.proveedor}</td>`;
                    }
                    html += `
                            <td class="px-4 py-3 text-sm font-semibold text-green-700">$${parseFloat(item.total).toFixed(2)}</td>
                        </tr>
                    `;
                });
                
                html += `
                            </tbody>
                        </table>
                    </div>
                `;
            }
            
            resultados.innerHTML = '<div class="card space-y-4">' + html + '</div>';
        })
        .catch(() => {
            resultados.innerHTML = `
                <div class="text-center text-red-600 py-8">
                    <i class="fas fa-exclamation-triangle text-4xl mb-2"></i>
                    <p>Error al generar el reporte</p>
                </div>
            `;
        });
}
</script>
