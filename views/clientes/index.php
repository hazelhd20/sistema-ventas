<?php
$pageTitle = "Clientes";
?>

<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Clientes</h2>
        <p class="text-gray-600">Gestión de clientes</p>
    </div>
    <button onclick="abrirModal('crear')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
        <i class="fas fa-plus"></i> Nuevo Cliente
    </button>
</div>

<!-- Búsqueda -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" action="<?php echo BASE_URL; ?>clientes" class="flex gap-4">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search ?? ''); ?>" 
               placeholder="Buscar por nombre, apellidos, teléfono, email, RFC o dirección..." 
               class="flex-1 px-4 py-2 border rounded">
        <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded">
            <i class="fas fa-search"></i> Buscar
        </button>
        <a href="<?php echo BASE_URL; ?>clientes" class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded">
            <i class="fas fa-times"></i> Limpiar
        </a>
    </form>
</div>

<!-- Tabla de clientes -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Teléfono</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php if (empty($clientes)): ?>
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No hay clientes registrados</td>
                </tr>
            <?php else: ?>
                <?php foreach ($clientes as $cliente): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $cliente['idCliente']; ?></td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <?php echo htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellidos']); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($cliente['telefono'] ?? '-'); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($cliente['email'] ?? '-'); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="abrirModal('editar', <?php echo htmlspecialchars(json_encode($cliente)); ?>)" 
                                    class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-edit"></i>
                            </button>
                            <a href="<?php echo BASE_URL; ?>clientes/delete/<?php echo $cliente['idCliente']; ?>" 
                               onclick="return confirmarEliminacion()" 
                               class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="modalCliente" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4" id="modalTitle">Nuevo Cliente</h3>
            <form id="formCliente" method="POST" action="">
                <input type="hidden" name="idCliente" id="idCliente">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nombre *</label>
                    <input type="text" name="nombre" id="nombre" required 
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Apellidos</label>
                    <input type="text" name="apellidos" id="apellidos" 
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                    <input type="text" name="telefono" id="telefono" 
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" 
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Dirección</label>
                    <textarea name="direccion" id="direccion" rows="2" 
                              class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2"></textarea>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">RFC</label>
                    <input type="text" name="rfc" id="rfc" 
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="cerrarModal()" 
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
function abrirModal(accion, cliente = null) {
    const modal = document.getElementById('modalCliente');
    const form = document.getElementById('formCliente');
    const title = document.getElementById('modalTitle');
    
    if (accion === 'crear') {
        title.textContent = 'Nuevo Cliente';
        form.action = '<?php echo BASE_URL; ?>clientes/create';
        form.reset();
        document.getElementById('idCliente').value = '';
    } else {
        title.textContent = 'Editar Cliente';
        form.action = '<?php echo BASE_URL; ?>clientes/update';
        document.getElementById('idCliente').value = cliente.idCliente;
        document.getElementById('nombre').value = cliente.nombre;
        document.getElementById('apellidos').value = cliente.apellidos || '';
        document.getElementById('telefono').value = cliente.telefono || '';
        document.getElementById('email').value = cliente.email || '';
        document.getElementById('direccion').value = cliente.direccion || '';
        document.getElementById('rfc').value = cliente.rfc || '';
    }
    
    modal.classList.remove('hidden');
}

function cerrarModal() {
    document.getElementById('modalCliente').classList.add('hidden');
}
</script>

