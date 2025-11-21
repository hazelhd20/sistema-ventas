<?php
$pageTitle = "Usuarios";
?>

<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Usuarios</h2>
        <p class="text-gray-600">Gestión de usuarios del sistema</p>
    </div>
    <button onclick="abrirModal('crear')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow hover:shadow-lg transition-all">
        <i class="fas fa-plus"></i> Nuevo Usuario
    </button>
</div>

<!-- Tabla de usuarios -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($usuarios)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>No hay usuarios registrados</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr class="hover:bg-blue-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-semibold text-gray-900">#<?php echo $usuario['idUsuario']; ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-user text-xs text-gray-400"></i>
                                    <span class="text-sm font-semibold text-gray-900">
                                        <?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellidos']); ?>
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <i class="fas fa-user-circle text-xs text-gray-400 mr-1"></i>
                                <?php echo htmlspecialchars($usuario['usuario']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <?php if (!empty($usuario['email'])): ?>
                                    <i class="fas fa-envelope text-xs text-gray-400 mr-1"></i>
                                    <?php echo htmlspecialchars($usuario['email']); ?>
                                <?php else: ?>
                                    <span class="text-gray-400">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-800 font-semibold">
                                    <i class="fas fa-user-tag text-xs mr-1"></i>
                                    <?php echo htmlspecialchars($usuario['rol_nombre']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($usuario['estado'] == 1): ?>
                                    <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800 font-semibold">
                                        <i class="fas fa-check-circle"></i> Activo
                                    </span>
                                <?php else: ?>
                                    <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-800 font-semibold">
                                        <i class="fas fa-ban"></i> Inactivo
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <button onclick="abrirModal('editar', <?php echo htmlspecialchars(json_encode($usuario)); ?>)" 
                                            class="text-blue-600 hover:text-blue-900 p-2 hover:bg-blue-100 rounded-lg transition-all"
                                            title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="<?php echo BASE_URL; ?>usuarios/delete/<?php echo $usuario['idUsuario']; ?>" 
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

<!-- Modal -->
<div id="modalUsuario" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-6 border w-11/12 max-w-5xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-6" id="modalTitle">Nuevo Usuario</h3>
            <form id="formUsuario" method="POST" action="">
                <input type="hidden" name="idUsuario" id="idUsuario">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Rol *</label>
                        <select name="idRol" id="idRol" required 
                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                            <option value="">Seleccione...</option>
                            <?php foreach ($roles as $rol): ?>
                                <option value="<?php echo $rol['idRol']; ?>"><?php echo htmlspecialchars($rol['nombre']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Usuario *</label>
                        <input type="text" name="usuario" id="usuario" required 
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nombre *</label>
                        <input type="text" name="nombre" id="nombre" required 
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Apellidos *</label>
                        <input type="text" name="apellidos" id="apellidos" required 
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Contraseña <span id="passwordRequired">*</span></label>
                        <input type="password" name="password" id="password" 
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                        <p class="text-xs text-gray-500 mt-1" id="passwordHint">Dejar en blanco para mantener la actual</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" 
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                        <input type="text" name="telefono" id="telefono" 
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    
                    <div id="estadoDiv">
                        <label class="block text-sm font-medium text-gray-700">Estado</label>
                        <select name="estado" id="estado" 
                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
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

