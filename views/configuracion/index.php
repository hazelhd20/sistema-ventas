<?php
$pageTitle = "Configuracion";
$configDisabled = $configDisabled ?? false;
?>

<div class="max-w-7xl mx-auto space-y-6">
    <div class="card flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <p class="text-sm text-gray-500 uppercase tracking-wide">Configuracion</p>
            <h2 class="text-2xl font-semibold text-gray-800">Catalogos del sistema</h2>
            <p class="text-gray-600">Administra categorias, medidas y formas de pago</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Categorias -->
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Categorias</h3>
                <button onclick="abrirModalCategoria('crear')" class="btn-primary px-3 py-2">
                    <i data-lucide="plus" class="h-4 w-4 mr-1"></i> Nueva
                </button>
            </div>
            <div class="space-y-2">
                <?php foreach ($categorias as $cat): ?>
                    <div class="flex justify-between items-center p-3 border border-gray-200 rounded-lg">
                        <div>
                            <span class="text-sm font-medium text-gray-800"><?php echo htmlspecialchars($cat['nombre']); ?></span>
                            <span class="ml-2 text-xs <?php echo ((int)$cat['estado'] === 1) ? 'text-green-700' : 'text-gray-500'; ?>">
                                <?php echo ((int)$cat['estado'] === 1) ? 'Activo' : 'Inactivo'; ?>
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button onclick="abrirModalCategoria('editar', <?php echo htmlspecialchars(json_encode($cat)); ?>)"
                                    class="btn-ghost px-3 py-1">
                                <i data-lucide="edit" class="h-4 w-4 mr-1"></i> Editar
                            </button>
                            <?php if ((int)$cat['estado'] === 1): ?>
                                <form method="POST" action="<?php echo BASE_URL; ?>configuracion/categoria" class="inline">
                                    <input type="hidden" name="accion" value="desactivar">
                                    <input type="hidden" name="id" value="<?php echo $cat['idCategoria']; ?>">
                                    <button type="submit" onclick="return confirmarEliminacion('Desea desactivar esta categoria?')" class="btn-ghost px-3 py-1 text-red-700">
                                        <i data-lucide="ban" class="h-4 w-4 mr-1"></i> Desactivar
                                    </button>
                                </form>
                            <?php else: ?>
                                <form method="POST" action="<?php echo BASE_URL; ?>configuracion/categoria" class="inline">
                                    <input type="hidden" name="accion" value="activar">
                                    <input type="hidden" name="id" value="<?php echo $cat['idCategoria']; ?>">
                                    <button type="submit" class="btn-ghost px-3 py-1 text-green-700">
                                        <i data-lucide="check-circle" class="h-4 w-4 mr-1"></i> Activar
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Medidas -->
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Medidas</h3>
                <button onclick="abrirModalMedida('crear')" class="btn-primary px-3 py-2">
                    <i data-lucide="plus" class="h-4 w-4 mr-1"></i> Nueva
                </button>
            </div>
            <div class="space-y-2">
                <?php foreach ($medidas as $med): ?>
                    <div class="flex justify-between items-center p-3 border border-gray-200 rounded-lg">
                        <div>
                            <span class="text-sm font-medium text-gray-800"><?php echo htmlspecialchars($med['nombre'] . ' (' . $med['abreviatura'] . ')'); ?></span>
                            <span class="ml-2 text-xs <?php echo ((int)$med['estado'] === 1) ? 'text-green-700' : 'text-gray-500'; ?>">
                                <?php echo ((int)$med['estado'] === 1) ? 'Activo' : 'Inactivo'; ?>
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button onclick="abrirModalMedida('editar', <?php echo htmlspecialchars(json_encode($med)); ?>)"
                                    class="btn-ghost px-3 py-1">
                                <i data-lucide="edit" class="h-4 w-4 mr-1"></i> Editar
                            </button>
                            <?php if ((int)$med['estado'] === 1): ?>
                                <form method="POST" action="<?php echo BASE_URL; ?>configuracion/medida" class="inline">
                                    <input type="hidden" name="accion" value="desactivar">
                                    <input type="hidden" name="id" value="<?php echo $med['idMedida']; ?>">
                                    <button type="submit" onclick="return confirmarEliminacion('Desea desactivar esta medida?')" class="btn-ghost px-3 py-1 text-red-700">
                                        <i data-lucide="ban" class="h-4 w-4 mr-1"></i> Desactivar
                                    </button>
                                </form>
                            <?php else: ?>
                                <form method="POST" action="<?php echo BASE_URL; ?>configuracion/medida" class="inline">
                                    <input type="hidden" name="accion" value="activar">
                                    <input type="hidden" name="id" value="<?php echo $med['idMedida']; ?>">
                                    <button type="submit" class="btn-ghost px-3 py-1 text-green-700">
                                        <i data-lucide="check-circle" class="h-4 w-4 mr-1"></i> Activar
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Formas de Pago -->
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Formas de pago</h3>
                <button onclick="abrirModalFormaPago('crear')" class="btn-primary px-3 py-2">
                    <i data-lucide="plus" class="h-4 w-4 mr-1"></i> Nueva
                </button>
            </div>
            <div class="space-y-2">
                <?php foreach ($formasPago as $fp): ?>
                    <div class="flex justify-between items-center p-3 border border-gray-200 rounded-lg">
                        <div>
                            <span class="text-sm font-medium text-gray-800"><?php echo htmlspecialchars($fp['nombre']); ?></span>
                            <span class="ml-2 text-xs <?php echo ((int)$fp['estado'] === 1) ? 'text-green-700' : 'text-gray-500'; ?>">
                                <?php echo ((int)$fp['estado'] === 1) ? 'Activo' : 'Inactivo'; ?>
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button onclick="abrirModalFormaPago('editar', <?php echo htmlspecialchars(json_encode($fp)); ?>)"
                                    class="btn-ghost px-3 py-1">
                                <i data-lucide="edit" class="h-4 w-4 mr-1"></i> Editar
                            </button>
                            <?php if ((int)$fp['estado'] === 1): ?>
                                <form method="POST" action="<?php echo BASE_URL; ?>configuracion/formaPago" class="inline">
                                    <input type="hidden" name="accion" value="desactivar">
                                    <input type="hidden" name="id" value="<?php echo $fp['idFormaPago']; ?>">
                                    <button type="submit" onclick="return confirmarEliminacion('Desea desactivar esta forma de pago?')" class="btn-ghost px-3 py-1 text-red-700">
                                        <i data-lucide="ban" class="h-4 w-4 mr-1"></i> Desactivar
                                    </button>
                                </form>
                            <?php else: ?>
                                <form method="POST" action="<?php echo BASE_URL; ?>configuracion/formaPago" class="inline">
                                    <input type="hidden" name="accion" value="activar">
                                    <input type="hidden" name="id" value="<?php echo $fp['idFormaPago']; ?>">
                                    <button type="submit" class="btn-ghost px-3 py-1 text-green-700">
                                        <i data-lucide="check-circle" class="h-4 w-4 mr-1"></i> Activar
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script>
function abrirModalCategoria(accion, categoria = null) {
    const modal = document.getElementById('modalCategoria');
    document.getElementById('accionCategoria').value = accion;
    document.getElementById('formCategoria').action = '<?php echo BASE_URL; ?>configuracion/categoria';
    const title = document.getElementById('modalTitleCategoria');
    if (accion === 'crear') {
        title.textContent = 'Nueva Categoria';
        document.getElementById('idCategoria').value = '';
        document.getElementById('nombreCategoria').value = '';
    } else {
        title.textContent = 'Editar Categoria';
        document.getElementById('idCategoria').value = categoria.idCategoria;
        document.getElementById('nombreCategoria').value = categoria.nombre;
    }
    modal.classList.remove('hidden');
}
function cerrarModalCategoria() {
    document.getElementById('modalCategoria').classList.add('hidden');
}

function abrirModalMedida(accion, medida = null) {
    const modal = document.getElementById('modalMedida');
    document.getElementById('accionMedida').value = accion;
    document.getElementById('formMedida').action = '<?php echo BASE_URL; ?>configuracion/medida';
    const title = document.getElementById('modalTitleMedida');
    if (accion === 'crear') {
        title.textContent = 'Nueva Medida';
        document.getElementById('idMedida').value = '';
        document.getElementById('nombreMedida').value = '';
        document.getElementById('abreviaturaMedida').value = '';
    } else {
        title.textContent = 'Editar Medida';
        document.getElementById('idMedida').value = medida.idMedida;
        document.getElementById('nombreMedida').value = medida.nombre;
        document.getElementById('abreviaturaMedida').value = medida.abreviatura;
    }
    modal.classList.remove('hidden');
}
function cerrarModalMedida() {
    document.getElementById('modalMedida').classList.add('hidden');
}

function abrirModalFormaPago(accion, forma = null) {
    const modal = document.getElementById('modalFormaPago');
    document.getElementById('accionFormaPago').value = accion;
    document.getElementById('formFormaPago').action = '<?php echo BASE_URL; ?>configuracion/formaPago';
    const title = document.getElementById('modalTitleFormaPago');
    if (accion === 'crear') {
        title.textContent = 'Nueva Forma de Pago';
        document.getElementById('idFormaPago').value = '';
        document.getElementById('nombreFormaPago').value = '';
        document.getElementById('descripcionFormaPago').value = '';
    } else {
        title.textContent = 'Editar Forma de Pago';
        document.getElementById('idFormaPago').value = forma.idFormaPago;
        document.getElementById('nombreFormaPago').value = forma.nombre;
        document.getElementById('descripcionFormaPago').value = forma.descripcion || '';
    }
    modal.classList.remove('hidden');
}
function cerrarModalFormaPago() {
    document.getElementById('modalFormaPago').classList.add('hidden');
}
</script>

<!-- Modal Categoria -->
<div id="modalCategoria" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-16 mx-auto p-6 border w-11/12 max-w-md shadow-lg rounded-2xl bg-white">
        <div class="mt-3 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900" id="modalTitleCategoria">Nueva Categoria</h3>
                <button type="button" onclick="cerrarModalCategoria()" class="btn-ghost px-3 py-2">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </button>
            </div>
            <form id="formCategoria" method="POST" action="">
                <input type="hidden" name="accion" id="accionCategoria">
                <input type="hidden" name="id" id="idCategoria">
                <input type="hidden" name="descripcion" value="">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nombre *</label>
                    <input type="text" name="nombre" id="nombreCategoria" required class="input-modern mt-1">
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="cerrarModalCategoria()" class="btn-ghost">Cancelar</button>
                    <button type="submit" class="btn-primary">
                        <i data-lucide="save" class="h-4 w-4 mr-1"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Medida -->
<div id="modalMedida" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-16 mx-auto p-6 border w-11/12 max-w-md shadow-lg rounded-2xl bg-white">
        <div class="mt-3 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900" id="modalTitleMedida">Nueva Medida</h3>
                <button type="button" onclick="cerrarModalMedida()" class="btn-ghost px-3 py-2">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </button>
            </div>
            <form id="formMedida" method="POST" action="">
                <input type="hidden" name="accion" id="accionMedida">
                <input type="hidden" name="id" id="idMedida">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nombre *</label>
                    <input type="text" name="nombre" id="nombreMedida" required class="input-modern mt-1">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Abreviatura *</label>
                    <input type="text" name="abreviatura" id="abreviaturaMedida" required class="input-modern mt-1">
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="cerrarModalMedida()" class="btn-ghost">Cancelar</button>
                    <button type="submit" class="btn-primary">
                        <i data-lucide="save" class="h-4 w-4 mr-1"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Forma de Pago -->
<div id="modalFormaPago" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-16 mx-auto p-6 border w-11/12 max-w-md shadow-lg rounded-2xl bg-white">
        <div class="mt-3 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900" id="modalTitleFormaPago">Nueva Forma de Pago</h3>
                <button type="button" onclick="cerrarModalFormaPago()" class="btn-ghost px-3 py-2">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </button>
            </div>
            <form id="formFormaPago" method="POST" action="">
                <input type="hidden" name="accion" id="accionFormaPago">
                <input type="hidden" name="id" id="idFormaPago">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nombre *</label>
                    <input type="text" name="nombre" id="nombreFormaPago" required class="input-modern mt-1">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Descripcion</label>
                    <textarea name="descripcion" id="descripcionFormaPago" rows="2" class="input-modern mt-1"></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="cerrarModalFormaPago()" class="btn-ghost">Cancelar</button>
                    <button type="submit" class="btn-primary">
                        <i data-lucide="save" class="h-4 w-4 mr-1"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
