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

<!-- Modal Categoría -->
<div id="modalCategoria" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-6 border w-11/12 max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-6" id="modalTitleCategoria">Nueva Categoría</h3>
            <form id="formCategoria" method="POST" action="">
                <input type="hidden" name="accion" id="accionCategoria">
                <input type="hidden" name="id" id="idCategoria">
                <input type="hidden" name="descripcion" value="">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nombre *</label>
                    <input type="text" name="nombre" id="nombreCategoria" required 
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="cerrarModalCategoria()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Medida -->
<div id="modalMedida" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-6 border w-11/12 max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-6" id="modalTitleMedida">Nueva Medida</h3>
            <form id="formMedida" method="POST" action="">
                <input type="hidden" name="accion" id="accionMedida">
                <input type="hidden" name="id" id="idMedida">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nombre *</label>
                    <input type="text" name="nombre" id="nombreMedida" required 
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Abreviatura *</label>
                    <input type="text" name="abreviatura" id="abreviaturaMedida" required 
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="cerrarModalMedida()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Forma de Pago -->
<div id="modalFormaPago" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-6 border w-11/12 max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-6" id="modalTitleFormaPago">Nueva Forma de Pago</h3>
            <form id="formFormaPago" method="POST" action="">
                <input type="hidden" name="accion" id="accionFormaPago">
                <input type="hidden" name="id" id="idFormaPago">
                <input type="hidden" name="descripcion" value="">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nombre *</label>
                    <input type="text" name="nombre" id="nombreFormaPago" required 
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="cerrarModalFormaPago()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function abrirModalCategoria(accion, item = null) {
    const modal = document.getElementById('modalCategoria');
    const form = document.getElementById('formCategoria');
    const title = document.getElementById('modalTitleCategoria');
    
    if (accion === 'crear') {
        title.textContent = 'Nueva Categoría';
        form.action = '<?php echo BASE_URL; ?>configuracion/categoria';
        document.getElementById('accionCategoria').value = 'crear';
        document.getElementById('idCategoria').value = '';
        document.getElementById('nombreCategoria').value = '';
    } else {
        title.textContent = 'Editar Categoría';
        form.action = '<?php echo BASE_URL; ?>configuracion/categoria';
        document.getElementById('accionCategoria').value = 'editar';
        document.getElementById('idCategoria').value = item.idCategoria;
        document.getElementById('nombreCategoria').value = item.nombre;
    }
    
    modal.classList.remove('hidden');
}

function cerrarModalCategoria() {
    document.getElementById('modalCategoria').classList.add('hidden');
}

function abrirModalMedida(accion, item = null) {
    const modal = document.getElementById('modalMedida');
    const form = document.getElementById('formMedida');
    const title = document.getElementById('modalTitleMedida');
    
    if (accion === 'crear') {
        title.textContent = 'Nueva Medida';
        form.action = '<?php echo BASE_URL; ?>configuracion/medida';
        document.getElementById('accionMedida').value = 'crear';
        document.getElementById('idMedida').value = '';
        document.getElementById('nombreMedida').value = '';
        document.getElementById('abreviaturaMedida').value = '';
    } else {
        title.textContent = 'Editar Medida';
        form.action = '<?php echo BASE_URL; ?>configuracion/medida';
        document.getElementById('accionMedida').value = 'editar';
        document.getElementById('idMedida').value = item.idMedida;
        document.getElementById('nombreMedida').value = item.nombre;
        document.getElementById('abreviaturaMedida').value = item.abreviatura;
    }
    
    modal.classList.remove('hidden');
}

function cerrarModalMedida() {
    document.getElementById('modalMedida').classList.add('hidden');
}

function abrirModalFormaPago(accion, item = null) {
    const modal = document.getElementById('modalFormaPago');
    const form = document.getElementById('formFormaPago');
    const title = document.getElementById('modalTitleFormaPago');
    
    if (accion === 'crear') {
        title.textContent = 'Nueva Forma de Pago';
        form.action = '<?php echo BASE_URL; ?>configuracion/formaPago';
        document.getElementById('accionFormaPago').value = 'crear';
        document.getElementById('idFormaPago').value = '';
        document.getElementById('nombreFormaPago').value = '';
    } else {
        title.textContent = 'Editar Forma de Pago';
        form.action = '<?php echo BASE_URL; ?>configuracion/formaPago';
        document.getElementById('accionFormaPago').value = 'editar';
        document.getElementById('idFormaPago').value = item.idFormaPago;
        document.getElementById('nombreFormaPago').value = item.nombre;
    }
    
    modal.classList.remove('hidden');
}

function cerrarModalFormaPago() {
    document.getElementById('modalFormaPago').classList.add('hidden');
}

// Cerrar modal al hacer clic fuera de él
window.onclick = function(event) {
    const modalCategoria = document.getElementById('modalCategoria');
    const modalMedida = document.getElementById('modalMedida');
    const modalFormaPago = document.getElementById('modalFormaPago');
    
    if (event.target == modalCategoria) {
        cerrarModalCategoria();
    }
    if (event.target == modalMedida) {
        cerrarModalMedida();
    }
    if (event.target == modalFormaPago) {
        cerrarModalFormaPago();
    }
}
</script>

