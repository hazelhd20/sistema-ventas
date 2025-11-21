<?php
$pageTitle = "Proveedores";
?>

<div class="max-w-7xl mx-auto space-y-6">
    <div class="card flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <p class="text-sm text-gray-500 uppercase tracking-wide">Proveedores</p>
            <h2 class="text-2xl font-semibold text-gray-800">Gestion de proveedores</h2>
            <p class="text-gray-600">Administra tus proveedores y contactos</p>
        </div>
        <button onclick="abrirModal('crear')" class="btn-primary">
            <i data-lucide="plus" class="h-4 w-4 mr-2"></i> Nuevo proveedor
        </button>
    </div>

    <div class="card">
        <form method="GET" action="<?php echo BASE_URL; ?>proveedores" id="formBusqueda" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <input type="text" name="search" id="searchInput" value="<?php echo htmlspecialchars($search ?? ''); ?>"
                       placeholder="Buscar por nombre, contacto, telefono, email o RFC..."
                       class="input-modern pl-10"
                       autocomplete="off">
                <i data-lucide="search" class="absolute left-3 top-3 text-gray-400 h-5 w-5"></i>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="btn-primary">
                    <i data-lucide="search" class="h-4 w-4 mr-2"></i> Buscar
                </button>
                <a href="<?php echo BASE_URL; ?>proveedores" class="btn-ghost">
                    <i data-lucide="x" class="h-4 w-4 mr-2"></i> Limpiar
                </a>
            </div>
        </form>
    </div>

    <div class="card table-shell p-0">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contacto</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telefono</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($proveedores)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <i data-lucide="inbox" class="h-10 w-10 mx-auto mb-2 text-gray-400"></i>
                                <p>No hay proveedores registrados</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($proveedores as $proveedor): ?>
                            <tr class="hover:bg-blue-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-900">#<?php echo $proveedor['idProveedor']; ?></span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="truck" class="h-4 w-4 text-gray-400"></i>
                                        <span class="text-sm font-semibold text-gray-900">
                                            <?php echo htmlspecialchars($proveedor['nombre']); ?>
                                        </span>
                                    </div>
                                    <?php if (!empty($proveedor['rfc'])): ?>
                                        <span class="text-xs text-gray-500 block">RFC: <?php echo htmlspecialchars($proveedor['rfc']); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <?php if (!empty($proveedor['contacto'])): ?>
                                        <i data-lucide="user" class="h-4 w-4 inline text-gray-400 mr-1"></i>
                                        <?php echo htmlspecialchars($proveedor['contacto']); ?>
                                    <?php else: ?>
                                        <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <?php if (!empty($proveedor['telefono'])): ?>
                                        <i data-lucide="phone" class="h-4 w-4 inline text-gray-400 mr-1"></i>
                                        <?php echo htmlspecialchars($proveedor['telefono']); ?>
                                    <?php else: ?>
                                        <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <?php if (!empty($proveedor['email'])): ?>
                                        <i data-lucide="mail" class="h-4 w-4 inline text-gray-400 mr-1"></i>
                                        <?php echo htmlspecialchars($proveedor['email']); ?>
                                    <?php else: ?>
                                        <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <button onclick='abrirModal("editar", <?php echo json_encode($proveedor); ?>)' class="btn-ghost px-3 py-2">
                                            <i data-lucide="edit" class="h-4 w-4 mr-1"></i> Editar
                                        </button>
                                        <a href="<?php echo BASE_URL; ?>proveedores/delete/<?php echo $proveedor['idProveedor']; ?>"
                                           class="btn-ghost px-3 py-2 text-red-700"
                                           onclick="return confirmarEliminacion('Seguro de eliminar este proveedor?');">
                                            <i data-lucide="trash" class="h-4 w-4 mr-1"></i> Eliminar
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
let searchTimeout;
document.getElementById('searchInput')?.addEventListener('input', function(e) {
    clearTimeout(searchTimeout);
    const value = e.target.value.trim();
    if (value.length >= 2 || value.length === 0) {
        searchTimeout = setTimeout(() => {
            if (value.length >= 2 || value.length === 0) {
                document.getElementById('formBusqueda').submit();
            }
        }, 500);
    }
});

document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
        e.preventDefault();
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.focus();
            searchInput.select();
        }
    }
});
</script>

<!-- Modal -->
<div id="modalProveedor" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-16 mx-auto p-6 border w-11/12 max-w-5xl shadow-lg rounded-2xl bg-white">
        <div class="mt-3 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Nuevo Proveedor</h3>
                <button type="button" onclick="cerrarModal()" class="btn-ghost px-3 py-2">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </button>
            </div>
            <form id="formProveedor" method="POST" action="">
                <input type="hidden" name="idProveedor" id="idProveedor">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nombre *</label>
                        <input type="text" name="nombre" id="nombre" required class="input-modern mt-1">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Contacto</label>
                        <input type="text" name="contacto" id="contacto" class="input-modern mt-1">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Telefono</label>
                        <input type="text" name="telefono" id="telefono" class="input-modern mt-1">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" class="input-modern mt-1">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Direccion</label>
                        <textarea name="direccion" id="direccion" rows="2" class="input-modern mt-1"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">RFC</label>
                        <input type="text" name="rfc" id="rfc" class="input-modern mt-1">
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="cerrarModal()" class="btn-ghost">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-primary">
                        <i data-lucide="save" class="h-4 w-4 mr-2"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function abrirModal(accion, proveedor = null) {
    const modal = document.getElementById('modalProveedor');
    const form = document.getElementById('formProveedor');
    const title = document.getElementById('modalTitle');

    if (accion === 'crear') {
        title.textContent = 'Nuevo Proveedor';
        form.action = '<?php echo BASE_URL; ?>proveedores/create';
        form.reset();
        document.getElementById('idProveedor').value = '';
    } else {
        title.textContent = 'Editar Proveedor';
        form.action = '<?php echo BASE_URL; ?>proveedores/update';
        document.getElementById('idProveedor').value = proveedor.idProveedor;
        document.getElementById('nombre').value = proveedor.nombre;
        document.getElementById('contacto').value = proveedor.contacto || '';
        document.getElementById('telefono').value = proveedor.telefono || '';
        document.getElementById('email').value = proveedor.email || '';
        document.getElementById('direccion').value = proveedor.direccion || '';
        document.getElementById('rfc').value = proveedor.rfc || '';
    }

    modal.classList.remove('hidden');
}

function cerrarModal() {
    document.getElementById('modalProveedor').classList.add('hidden');
}
</script>
