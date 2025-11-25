<?php
$pageTitle = "Usuarios";
?>

<div class="max-w-7xl mx-auto space-y-6">
    <div class="card flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <p class="text-sm text-gray-500 uppercase tracking-wide">Usuarios</p>
            <h2 class="text-2xl font-semibold text-gray-800">Gestion de usuarios</h2>
            <p class="text-gray-600">Control de accesos y roles</p>
        </div>
        <button onclick="abrirModal('crear')" class="btn-primary">
            <i data-lucide="plus" class="h-4 w-4 mr-2"></i> Nuevo usuario
        </button>
    </div>

    <div class="card table-shell p-0">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($usuarios)): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <i data-lucide="inbox" class="h-10 w-10 mx-auto mb-2 text-gray-400"></i>
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
                                        <i data-lucide="user" class="h-4 w-4 text-gray-400"></i>
                                        <span class="text-sm font-semibold text-gray-900">
                                            <?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellidos']); ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <i data-lucide="badge-check" class="h-4 w-4 inline text-gray-400 mr-1"></i>
                                    <?php echo htmlspecialchars($usuario['usuario']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <?php if (!empty($usuario['email'])): ?>
                                        <i data-lucide="mail" class="h-4 w-4 inline text-gray-400 mr-1"></i>
                                        <?php echo htmlspecialchars($usuario['email']); ?>
                                    <?php else: ?>
                                        <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <span class="pill bg-blue-pastel/60 text-gray-800">
                                        <?php echo htmlspecialchars($usuario['rol_nombre']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php $usuarioActivo = (int)$usuario['estado'] === 1; ?>
                                    <span
                                        class="<?php echo $usuarioActivo ? 'pill bg-green-pastel/70 text-gray-800' : 'pill bg-pink-pastel/70 text-gray-800'; ?>"
                                        data-estado-pill
                                        data-state="<?php echo $usuarioActivo ? '1' : '0'; ?>"
                                        data-class-activo="pill bg-green-pastel/70 text-gray-800"
                                        data-class-inactivo="pill bg-pink-pastel/70 text-gray-800">
                                        <?php echo $usuarioActivo ? 'Activo' : 'Inactivo'; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <button onclick='abrirModal("editar", <?php echo json_encode($usuario); ?>)' class="btn-ghost px-3 py-2">
                                            <i data-lucide="edit" class="h-4 w-4 mr-1"></i> Editar
                                        </button>
                                        <form action="<?php echo BASE_URL; ?>usuarios/toggle"
                                              method="POST"
                                              data-ajax-toggle
                                              data-entity="usuario"
                                              data-id="<?php echo $usuario['idUsuario']; ?>"
                                              data-current-state="<?php echo $usuarioActivo ? '1' : '0'; ?>"
                                              data-new-state="<?php echo $usuarioActivo ? '0' : '1'; ?>"
                                              data-toggle-url="<?php echo BASE_URL; ?>usuarios/toggle"
                                              data-url-activar="<?php echo BASE_URL; ?>usuarios/toggle"
                                              data-url-desactivar="<?php echo BASE_URL; ?>usuarios/toggle"
                                              data-confirm-activar="Desea activar este usuario?"
                                              data-confirm-desactivar="Desea desactivar este usuario?">
                                            <input type="hidden" name="idUsuario" value="<?php echo $usuario['idUsuario']; ?>">
                                            <button type="submit" class="btn-ghost px-3 py-2" data-ajax-toggle-trigger>
                                                <i data-lucide="<?php echo $usuarioActivo ? 'ban' : 'check-circle'; ?>" class="h-4 w-4 mr-1"></i>
                                                <span data-toggle-text><?php echo $usuarioActivo ? 'Desactivar' : 'Activar'; ?></span>
                                            </button>
                                        </form>
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

<!-- Modal -->
<div id="modalUsuario" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-16 mx-auto p-6 border w-11/12 max-w-4xl shadow-lg rounded-2xl bg-white">
        <div class="mt-3 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Nuevo Usuario</h3>
                <button type="button" onclick="cerrarModal()" class="btn-ghost px-3 py-2">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </button>
            </div>
            <form id="formUsuario" method="POST" action="">
                <input type="hidden" name="idUsuario" id="idUsuario">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nombre *</label>
                        <input type="text" name="nombre" id="nombre" required class="input-modern mt-1">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Apellidos</label>
                        <input type="text" name="apellidos" id="apellidos" class="input-modern mt-1">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Usuario *</label>
                        <input type="text" name="usuario" id="usuario" required class="input-modern mt-1">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" class="input-modern mt-1">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Telefono</label>
                        <input type="text" name="telefono" id="telefono" class="input-modern mt-1">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Rol *</label>
                        <select name="idRol" id="idRol" required class="input-modern mt-1">
                            <?php foreach ($roles as $rol): ?>
                                <option value="<?php echo $rol['idRol']; ?>"><?php echo htmlspecialchars($rol['nombre']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Contrasena <span id="passwordRequired" class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password" id="password" class="input-modern mt-1" required>
                        <p id="passwordHint" class="text-xs text-gray-500 mt-1 hidden">Dejar en blanco para no cambiar.</p>
                    </div>
                    <div id="estadoDiv" class="hidden">
                        <label class="block text-sm font-medium text-gray-700">Estado</label>
                        <select name="estado" id="estado" class="input-modern mt-1">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
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
