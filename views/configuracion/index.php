<?php
$pageTitle = "Configuración";
?>

<div class="mb-6">
    <h2 class="text-3xl font-bold text-gray-800">Configuración</h2>
    <p class="text-gray-600">Gestión de catálogos del sistema</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Categorías -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-bold mb-4">Categorías</h3>
        <button onclick="abrirModalCategoria('crear')" class="mb-4 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
            <i class="fas fa-plus"></i> Nueva
        </button>
        <div class="space-y-2">
            <?php foreach ($categorias as $cat): ?>
                <div class="flex justify-between items-center p-2 border rounded">
                    <span><?php echo htmlspecialchars($cat['nombre']); ?></span>
                    <div>
                        <button onclick="abrirModalCategoria('editar', <?php echo htmlspecialchars(json_encode($cat)); ?>)" 
                                class="text-blue-600 mr-2">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form method="POST" action="<?php echo BASE_URL; ?>configuracion/categoria" class="inline">
                            <input type="hidden" name="accion" value="eliminar">
                            <input type="hidden" name="id" value="<?php echo $cat['idCategoria']; ?>">
                            <button type="submit" onclick="return confirmarEliminacion()" class="text-red-600">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Medidas -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-bold mb-4">Medidas</h3>
        <button onclick="abrirModalMedida('crear')" class="mb-4 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
            <i class="fas fa-plus"></i> Nueva
        </button>
        <div class="space-y-2">
            <?php foreach ($medidas as $med): ?>
                <div class="flex justify-between items-center p-2 border rounded">
                    <span><?php echo htmlspecialchars($med['nombre'] . ' (' . $med['abreviatura'] . ')'); ?></span>
                    <div>
                        <button onclick="abrirModalMedida('editar', <?php echo htmlspecialchars(json_encode($med)); ?>)" 
                                class="text-blue-600 mr-2">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form method="POST" action="<?php echo BASE_URL; ?>configuracion/medida" class="inline">
                            <input type="hidden" name="accion" value="eliminar">
                            <input type="hidden" name="id" value="<?php echo $med['idMedida']; ?>">
                            <button type="submit" onclick="return confirmarEliminacion()" class="text-red-600">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Formas de Pago -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-bold mb-4">Formas de Pago</h3>
        <button onclick="abrirModalFormaPago('crear')" class="mb-4 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
            <i class="fas fa-plus"></i> Nueva
        </button>
        <div class="space-y-2">
            <?php foreach ($formasPago as $fp): ?>
                <div class="flex justify-between items-center p-2 border rounded">
                    <span><?php echo htmlspecialchars($fp['nombre']); ?></span>
                    <div>
                        <button onclick="abrirModalFormaPago('editar', <?php echo htmlspecialchars(json_encode($fp)); ?>)" 
                                class="text-blue-600 mr-2">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form method="POST" action="<?php echo BASE_URL; ?>configuracion/formaPago" class="inline">
                            <input type="hidden" name="accion" value="eliminar">
                            <input type="hidden" name="id" value="<?php echo $fp['idFormaPago']; ?>">
                            <button type="submit" onclick="return confirmarEliminacion()" class="text-red-600">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Modales (simplificados) -->
<script>
function abrirModalCategoria(accion, item = null) {
    const nombre = accion === 'crear' ? prompt('Nombre de la categoría:') : prompt('Nombre de la categoría:', item.nombre);
    if (!nombre) return;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?php echo BASE_URL; ?>configuracion/categoria';
    form.innerHTML = `
        <input type="hidden" name="accion" value="${accion}">
        ${accion === 'editar' ? '<input type="hidden" name="id" value="' + item.idCategoria + '">' : ''}
        <input type="hidden" name="nombre" value="${nombre}">
        <input type="hidden" name="descripcion" value="">
    `;
    document.body.appendChild(form);
    form.submit();
}

function abrirModalMedida(accion, item = null) {
    const nombre = accion === 'crear' ? prompt('Nombre de la medida:') : prompt('Nombre de la medida:', item.nombre);
    if (!nombre) return;
    const abrev = accion === 'crear' ? prompt('Abreviatura:') : prompt('Abreviatura:', item.abreviatura);
    if (!abrev) return;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?php echo BASE_URL; ?>configuracion/medida';
    form.innerHTML = `
        <input type="hidden" name="accion" value="${accion}">
        ${accion === 'editar' ? '<input type="hidden" name="id" value="' + item.idMedida + '">' : ''}
        <input type="hidden" name="nombre" value="${nombre}">
        <input type="hidden" name="abreviatura" value="${abrev}">
    `;
    document.body.appendChild(form);
    form.submit();
}

function abrirModalFormaPago(accion, item = null) {
    const nombre = accion === 'crear' ? prompt('Nombre de la forma de pago:') : prompt('Nombre de la forma de pago:', item.nombre);
    if (!nombre) return;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?php echo BASE_URL; ?>configuracion/formaPago';
    form.innerHTML = `
        <input type="hidden" name="accion" value="${accion}">
        ${accion === 'editar' ? '<input type="hidden" name="id" value="' + item.idFormaPago + '">' : ''}
        <input type="hidden" name="nombre" value="${nombre}">
        <input type="hidden" name="descripcion" value="">
    `;
    document.body.appendChild(form);
    form.submit();
}
</script>

