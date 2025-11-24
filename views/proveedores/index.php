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
                <tbody class="bg-white divide-y divide-gray-200" id="proveedoresTbody">
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
(function() {
    const baseUrl = '<?php echo BASE_URL; ?>';
    const searchInput = document.getElementById('searchInput');
    const formBusqueda = document.getElementById('formBusqueda');
    const tbody = document.getElementById('proveedoresTbody');
    const initialRows = tbody ? tbody.innerHTML : '';
    let searchTimeout;

    const emptyRow = `
        <tr>
            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                <i data-lucide="inbox" class="h-10 w-10 mx-auto mb-2 text-gray-400"></i>
                <p>No hay proveedores registrados</p>
            </td>
        </tr>`;

    const loadingRow = `
        <tr>
            <td colspan="6" class="px-6 py-6 text-center text-gray-500">
                <i data-lucide="loader-2" class="h-5 w-5 inline animate-spin mr-2"></i>
                Buscando...
            </td>
        </tr>`;

    const errorRow = `
        <tr>
            <td colspan="6" class="px-6 py-6 text-center text-red-600">
                <i data-lucide="alert-triangle" class="h-5 w-5 inline mr-2"></i>
                No se pudo cargar la búsqueda
            </td>
        </tr>`;

    const escapeHtml = (str) => {
        if (str === null || str === undefined) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    };

    const renderRow = (p) => {
        const rfcLinea = p.rfc ? `<span class="text-xs text-gray-500 block">RFC: ${escapeHtml(p.rfc)}</span>` : '';
        const contacto = p.contacto
            ? `<i data-lucide="user" class="h-4 w-4 inline text-gray-400 mr-1"></i>${escapeHtml(p.contacto)}`
            : '<span class="text-gray-400">-</span>';
        const telefono = p.telefono
            ? `<i data-lucide="phone" class="h-4 w-4 inline text-gray-400 mr-1"></i>${escapeHtml(p.telefono)}`
            : '<span class="text-gray-400">-</span>';
        const email = p.email
            ? `<i data-lucide="mail" class="h-4 w-4 inline text-gray-400 mr-1"></i>${escapeHtml(p.email)}`
            : '<span class="text-gray-400">-</span>';
        const proveedorJson = JSON.stringify(p).replace(/"/g, '&quot;');

        return `
            <tr class="hover:bg-blue-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm font-semibold text-gray-900">#${escapeHtml(p.idProveedor)}</span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                        <i data-lucide="truck" class="h-4 w-4 text-gray-400"></i>
                        <span class="text-sm font-semibold text-gray-900">
                            ${escapeHtml(p.nombre)}
                        </span>
                    </div>
                    ${rfcLinea}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    ${contacto}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    ${telefono}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    ${email}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex items-center gap-2">
                        <button onclick='abrirModal("editar", ${proveedorJson})' class="btn-ghost px-3 py-2">
                            <i data-lucide="edit" class="h-4 w-4 mr-1"></i> Editar
                        </button>
                        <a href="${baseUrl}proveedores/delete/${encodeURIComponent(p.idProveedor)}"
                           class="btn-ghost px-3 py-2 text-red-700"
                           onclick="return confirmarEliminacion('Seguro de eliminar este proveedor?');">
                            <i data-lucide="trash" class="h-4 w-4 mr-1"></i> Eliminar
                        </a>
                    </div>
                </td>
            </tr>
        `;
    };

    function focusSearchInput() {
        if (!searchInput) return;
        const len = searchInput.value.length;
        searchInput.focus();
        searchInput.setSelectionRange(len, len);
    }

    function handleSearch() {
        if (!searchInput || !tbody) return;
        const value = searchInput.value.trim();

        if (value.length === 0) {
            tbody.innerHTML = initialRows;
            if (window.lucide) lucide.createIcons();
            return;
        }
        if (value.length < 2) {
            return;
        }

        tbody.innerHTML = loadingRow;

        fetch(`${baseUrl}proveedores/search?term=${encodeURIComponent(value)}`)
            .then(res => res.ok ? res.json() : Promise.reject())
            .then(data => {
                if (!Array.isArray(data) || data.length === 0) {
                    tbody.innerHTML = emptyRow;
                } else {
                    tbody.innerHTML = data.map(renderRow).join('');
                }
                if (window.lucide) lucide.createIcons();
            })
            .catch(() => {
                tbody.innerHTML = errorRow;
            });
    }

    searchInput?.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(handleSearch, 300);
    });

    formBusqueda?.addEventListener('submit', function(e) {
        e.preventDefault();
        handleSearch();
    });

    focusSearchInput();

    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
            e.preventDefault();
            focusSearchInput();
        }
    });

    if (searchInput && searchInput.value.trim().length >= 2) {
        handleSearch();
    }
})();
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
