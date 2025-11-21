<?php
$pageTitle = "Proveedores";
?>

<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Proveedores</h2>
        <p class="text-gray-600">Gestión de proveedores</p>
    </div>
    <button onclick="abrirModal('crear')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow hover:shadow-lg transition-all">
        <i class="fas fa-plus"></i> Nuevo Proveedor
    </button>
</div>

<!-- Búsqueda -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" action="<?php echo BASE_URL; ?>proveedores" id="formBusqueda" class="flex gap-4">
        <div class="flex-1 relative">
            <input type="text" name="search" id="searchInput" value="<?php echo htmlspecialchars($search ?? ''); ?>" 
                   placeholder="Buscar por nombre, contacto, teléfono, email o RFC..." 
                   class="w-full px-4 py-2 pl-10 border-2 border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                   autocomplete="off">
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow hover:shadow-lg transition-all">
            <i class="fas fa-search"></i> Buscar
        </button>
        <a href="<?php echo BASE_URL; ?>proveedores" class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded-lg transition-all">
            <i class="fas fa-times"></i> Limpiar
        </a>
    </form>
</div>

<!-- Tabla de proveedores -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contacto</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teléfono</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($proveedores)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
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
                                    <i class="fas fa-truck text-xs text-gray-400"></i>
                                    <span class="text-sm font-semibold text-gray-900"><?php echo htmlspecialchars($proveedor['nombre']); ?></span>
                                </div>
                                <?php if (!empty($proveedor['rfc'])): ?>
                                    <span class="text-xs text-gray-500">RFC: <?php echo htmlspecialchars($proveedor['rfc']); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <?php if (!empty($proveedor['contacto'])): ?>
                                    <i class="fas fa-user text-xs text-gray-400 mr-1"></i>
                                    <?php echo htmlspecialchars($proveedor['contacto']); ?>
                                <?php else: ?>
                                    <span class="text-gray-400">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <?php if (!empty($proveedor['telefono'])): ?>
                                    <i class="fas fa-phone text-xs text-gray-400 mr-1"></i>
                                    <?php echo htmlspecialchars($proveedor['telefono']); ?>
                                <?php else: ?>
                                    <span class="text-gray-400">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <?php if (!empty($proveedor['email'])): ?>
                                    <i class="fas fa-envelope text-xs text-gray-400 mr-1"></i>
                                    <?php echo htmlspecialchars($proveedor['email']); ?>
                                <?php else: ?>
                                    <span class="text-gray-400">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <button onclick="abrirModal('editar', <?php echo htmlspecialchars(json_encode($proveedor)); ?>)" 
                                            class="text-blue-600 hover:text-blue-900 p-2 hover:bg-blue-100 rounded-lg transition-all"
                                            title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="<?php echo BASE_URL; ?>proveedores/delete/<?php echo $proveedor['idProveedor']; ?>" 
                                       onclick="return confirmarEliminacion()" 
                                       class="text-red-600 hover:text-red-900 p-2 hover:bg-red-100 rounded-lg transition-all"
                                       title="Eliminar">
                                        <i class="fas fa-trash"></i>
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

<script>
// Búsqueda rápida con debounce
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

// Enfocar búsqueda con Ctrl+F
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
    <div class="relative top-20 mx-auto p-6 border w-11/12 max-w-5xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-6" id="modalTitle">Nuevo Proveedor</h3>
            <form id="formProveedor" method="POST" action="">
                <input type="hidden" name="idProveedor" id="idProveedor">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nombre *</label>
                        <input type="text" name="nombre" id="nombre" required 
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Contacto</label>
                        <input type="text" name="contacto" id="contacto" 
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                        <input type="text" name="telefono" id="telefono" 
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" 
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Dirección</label>
                        <textarea name="direccion" id="direccion" rows="2" 
                                  class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2"></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">RFC</label>
                        <input type="text" name="rfc" id="rfc" 
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="cerrarModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-all">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow hover:shadow-lg transition-all">
                        <i class="fas fa-save"></i> Guardar
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

