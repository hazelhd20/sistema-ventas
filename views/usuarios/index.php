<?php
$pageTitle = "Usuarios";
?>

<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Usuarios</h2>
        <p class="text-gray-600">Gestión de usuarios del sistema</p>
    </div>
    <button onclick="abrirModal('crear')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
        <i class="fas fa-plus"></i> Nuevo Usuario
    </button>
</div>

<!-- Tabla de usuarios -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuario</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rol</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php if (empty($usuarios)): ?>
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No hay usuarios registrados</td>
                </tr>
            <?php else: ?>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $usuario['idUsuario']; ?></td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellidos']); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($usuario['usuario']); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($usuario['email'] ?? '-'); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($usuario['rol_nombre']); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if ($usuario['estado'] == 1): ?>
                                <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Activo</span>
                            <?php else: ?>
                                <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="abrirModal('editar', <?php echo htmlspecialchars(json_encode($usuario)); ?>)" 
                                    class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-edit"></i>
                            </button>
                            <a href="<?php echo BASE_URL; ?>usuarios/delete/<?php echo $usuario['idUsuario']; ?>" 
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
<div id="modalUsuario" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4" id="modalTitle">Nuevo Usuario</h3>
            <form id="formUsuario" method="POST" action="">
                <input type="hidden" name="idUsuario" id="idUsuario">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Rol *</label>
                    <select name="idRol" id="idRol" required 
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                        <option value="">Seleccione...</option>
                        <?php foreach ($roles as $rol): ?>
                            <option value="<?php echo $rol['idRol']; ?>"><?php echo htmlspecialchars($rol['nombre']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nombre *</label>
                    <input type="text" name="nombre" id="nombre" required 
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Apellidos *</label>
                    <input type="text" name="apellidos" id="apellidos" required 
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Usuario *</label>
                    <input type="text" name="usuario" id="usuario" required 
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Contraseña <span id="passwordRequired">*</span></label>
                    <input type="password" name="password" id="password" 
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    <p class="text-xs text-gray-500 mt-1" id="passwordHint">Dejar en blanco para mantener la actual</p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" 
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                    <input type="text" name="telefono" id="telefono" 
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                
                <div class="mb-4" id="estadoDiv">
                    <label class="block text-sm font-medium text-gray-700">Estado</label>
                    <select name="estado" id="estado" 
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
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
function abrirModal(accion, usuario = null) {
    const modal = document.getElementById('modalUsuario');
    const form = document.getElementById('formUsuario');
    const title = document.getElementById('modalTitle');
    const passwordRequired = document.getElementById('passwordRequired');
    const passwordHint = document.getElementById('passwordHint');
    const estadoDiv = document.getElementById('estadoDiv');
    
    if (accion === 'crear') {
        title.textContent = 'Nuevo Usuario';
        form.action = '<?php echo BASE_URL; ?>usuarios/create';
        form.reset();
        document.getElementById('idUsuario').value = '';
        passwordRequired.style.display = 'inline';
        passwordHint.style.display = 'none';
        estadoDiv.style.display = 'none';
        document.getElementById('password').required = true;
    } else {
        title.textContent = 'Editar Usuario';
        form.action = '<?php echo BASE_URL; ?>usuarios/update';
        document.getElementById('idUsuario').value = usuario.idUsuario;
        document.getElementById('idRol').value = usuario.idRol;
        document.getElementById('nombre').value = usuario.nombre;
        document.getElementById('apellidos').value = usuario.apellidos;
        document.getElementById('usuario').value = usuario.usuario;
        document.getElementById('email').value = usuario.email || '';
        document.getElementById('telefono').value = usuario.telefono || '';
        document.getElementById('estado').value = usuario.estado;
        passwordRequired.style.display = 'none';
        passwordHint.style.display = 'block';
        estadoDiv.style.display = 'block';
        document.getElementById('password').required = false;
    }
    
    modal.classList.remove('hidden');
}

function cerrarModal() {
    document.getElementById('modalUsuario').classList.add('hidden');
}
</script>

