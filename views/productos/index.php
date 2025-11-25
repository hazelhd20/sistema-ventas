<?php
$pageTitle = "Productos";
?>

<div class="max-w-7xl mx-auto space-y-6">
    <div class="card flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <p class="text-sm text-gray-500 uppercase tracking-wide">Productos</p>
            <h2 class="text-2xl font-semibold text-gray-800">Gestion de inventario</h2>
            <p class="text-gray-600">Administra tu catalogo y existencias</p>
        </div>
        <button onclick="abrirModal('crear')" class="btn-primary">
            <i data-lucide="plus" class="h-4 w-4 mr-2"></i> Nuevo producto
        </button>
    </div>

    <div class="card">
        <form method="GET" action="<?php echo BASE_URL; ?>productos" id="formBusqueda" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <input type="text" name="search" id="searchInput" value="<?php echo htmlspecialchars($search ?? ''); ?>"
                       placeholder="Buscar por nombre, codigo de barras, descripcion, categoria o codigo..."
                       class="input-modern pl-10" autocomplete="off">
                <i data-lucide="search" class="absolute left-3 top-3 text-gray-400 h-5 w-5"></i>
            </div>
            <div class="flex items-center gap-2">
                <a href="<?php echo BASE_URL; ?>productos" class="btn-ghost">
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
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Codigo</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoria</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio compra</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio venta</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Existencia</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock Min.</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="productosTbody">
                    <?php if (empty($productos)): ?>
                        <tr>
                            <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                                <i data-lucide="inbox" class="h-10 w-10 mx-auto mb-2 text-gray-400"></i>
                                <p>No hay productos registrados</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($productos as $producto):
                            $stockBajo = $producto['existencia'] <= $producto['stockMinimo'];
                            $sinStock = $producto['existencia'] <= 0;
                        ?>
                            <tr class="hover:bg-blue-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-900"><?php echo $producto['codProducto']; ?></span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-semibold text-gray-900"><?php echo htmlspecialchars($producto['nombre']); ?></span>
                                        <?php if ($sinStock): ?>
                                            <i data-lucide="alert-octagon" class="h-4 w-4 text-red-500" title="Sin stock"></i>
                                        <?php elseif ($stockBajo): ?>
                                            <i data-lucide="alert-triangle" class="h-4 w-4 text-amber-500" title="Stock bajo"></i>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!empty($producto['codigoBarras'])): ?>
                                        <span class="text-xs text-gray-500 block">Codigo: <?php echo htmlspecialchars($producto['codigoBarras']); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <?php echo htmlspecialchars($producto['categoria_nombre']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-bold text-gray-800">$<?php echo number_format($producto['precioCompra'], 2); ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-bold text-blue-600">$<?php echo number_format($producto['precioVenta'], 2); ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="pill <?php echo $sinStock ? 'bg-pink-pastel/70' : ($stockBajo ? 'bg-peach-pastel/70' : 'bg-green-pastel/70'); ?> text-gray-800">
                                        <?php echo $producto['existencia'] . ' ' . $producto['medida_abrev']; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="text-xs"><?php echo $producto['stockMinimo']; ?> <?php echo $producto['medida_abrev']; ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <?php $productoActivo = (int)($producto['estado'] ?? 0) === 1; ?>
                                    <span
                                        class="<?php echo $productoActivo ? 'pill bg-green-pastel/70 text-gray-800' : 'pill bg-gray-200 text-gray-700'; ?>"
                                        data-estado-pill
                                        data-state="<?php echo $productoActivo ? '1' : '0'; ?>"
                                        data-class-activo="pill bg-green-pastel/70 text-gray-800"
                                        data-class-inactivo="pill bg-gray-200 text-gray-700">
                                        <?php echo $productoActivo ? 'Activo' : 'Inactivo'; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <button onclick="abrirModal('editar', <?php echo htmlspecialchars(json_encode($producto)); ?>)"
                                                class="btn-ghost px-3 py-2">
                                            <i data-lucide="edit" class="h-4 w-4 mr-1"></i> Editar
                                        </button>
                        <?php
                            $prodUrlActivar = BASE_URL . 'productos/activate/' . $producto['codProducto'];
                            $prodUrlDesactivar = BASE_URL . 'productos/delete/' . $producto['codProducto'];
                        ?>
                        <a href="<?php echo $productoActivo ? $prodUrlDesactivar : $prodUrlActivar; ?>"
                           class="btn-ghost px-3 py-2 <?php echo $productoActivo ? 'text-red-700' : 'text-green-700'; ?>"
                           data-ajax-toggle
                           data-entity="producto"
                           data-id="<?php echo $producto['codProducto']; ?>"
                           data-current-state="<?php echo $productoActivo ? '1' : '0'; ?>"
                           data-new-state="<?php echo $productoActivo ? '0' : '1'; ?>"
                           data-toggle-url="<?php echo $productoActivo ? $prodUrlDesactivar : $prodUrlActivar; ?>"
                           data-url-activar="<?php echo $prodUrlActivar; ?>"
                           data-url-desactivar="<?php echo $prodUrlDesactivar; ?>"
                           data-confirm-activar="Desea activar este producto?"
                           data-confirm-desactivar="Desea desactivar este producto?">
                            <i data-lucide="<?php echo $productoActivo ? 'ban' : 'check-circle'; ?>" class="h-4 w-4 mr-1"></i>
                            <span data-toggle-text><?php echo $productoActivo ? 'Desactivar' : 'Activar'; ?></span>
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
    const tbody = document.getElementById('productosTbody');
    const initialRows = tbody ? tbody.innerHTML : '';
    let searchTimeout;

    const emptyRow = `
        <tr>
            <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                <i data-lucide="inbox" class="h-10 w-10 mx-auto mb-2 text-gray-400"></i>
                <p>No hay productos registrados</p>
            </td>
        </tr>`;

    const loadingRow = `
        <tr>
            <td colspan="9" class="px-6 py-6 text-center text-gray-500">
                <i data-lucide="loader-2" class="h-5 w-5 inline animate-spin mr-2"></i>
                Buscando...
            </td>
        </tr>`;

    const errorRow = `
        <tr>
            <td colspan="9" class="px-6 py-6 text-center text-red-600">
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
        const existencia = Number(p.existencia ?? 0);
        const stockMin = Number(p.stockMinimo ?? 0);
        const sinStock = existencia <= 0;
        const stockBajo = existencia <= stockMin;
        const badgeClass = sinStock ? 'bg-pink-pastel/70' : (stockBajo ? 'bg-peach-pastel/70' : 'bg-green-pastel/70');
        const codigoLinea = p.codigoBarras ? `<span class="text-xs text-gray-500 block">Codigo: ${escapeHtml(p.codigoBarras)}</span>` : '';
        const iconoStock = sinStock
            ? '<i data-lucide="alert-octagon" class="h-4 w-4 text-red-500" title="Sin stock"></i>'
            : (stockBajo ? '<i data-lucide="alert-triangle" class="h-4 w-4 text-amber-500" title="Stock bajo"></i>' : '');
        const precioCompra = (typeof formatearMoneda === 'function')
            ? formatearMoneda(Number(p.precioCompra ?? 0))
            : `$${Number(p.precioCompra ?? 0).toFixed(2)}`;
        const precioVenta = (typeof formatearMoneda === 'function')
            ? formatearMoneda(Number(p.precioVenta ?? 0))
            : `$${Number(p.precioVenta ?? 0).toFixed(2)}`;
        const productoJson = JSON.stringify(p).replace(/"/g, '&quot;');
        const estadoLabel = Number(p.estado) === 1 ? 'Activo' : 'Inactivo';
        const estadoClass = Number(p.estado) === 1 ? 'pill bg-green-pastel/70 text-gray-800' : 'pill bg-gray-200 text-gray-700';
        const isActive = Number(p.estado) === 1;
        const urlActivar = `${baseUrl}productos/activate/${encodeURIComponent(p.codProducto)}`;
        const urlDesactivar = `${baseUrl}productos/delete/${encodeURIComponent(p.codProducto)}`;

        return `
            <tr class="hover:bg-blue-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm font-semibold text-gray-900">${escapeHtml(p.codProducto)}</span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-semibold text-gray-900">${escapeHtml(p.nombre)}</span>
                        ${iconoStock}
                    </div>
                    ${codigoLinea}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    ${escapeHtml(p.categoria_nombre)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm font-bold text-gray-800">${precioCompra}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm font-bold text-blue-600">${precioVenta}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="pill ${badgeClass} text-gray-800">
                        ${escapeHtml(existencia)} ${escapeHtml(p.medida_abrev)}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <span class="text-xs">${escapeHtml(stockMin)} ${escapeHtml(p.medida_abrev)}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <span class="${estadoClass}"
                          data-estado-pill
                          data-state="${isActive ? '1' : '0'}"
                          data-class-activo="pill bg-green-pastel/70 text-gray-800"
                          data-class-inactivo="pill bg-gray-200 text-gray-700">
                        ${estadoLabel}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex items-center gap-2">
                        <button onclick="abrirModal('editar', ${productoJson})"
                                class="btn-ghost px-3 py-2">
                            <i data-lucide="edit" class="h-4 w-4 mr-1"></i> Editar
                        </button>
                        <a href="${isActive ? urlDesactivar : urlActivar}"
                           class="btn-ghost px-3 py-2 ${isActive ? 'text-red-700' : 'text-green-700'}"
                           data-ajax-toggle
                           data-entity="producto"
                           data-id="${escapeHtml(p.codProducto)}"
                           data-current-state="${isActive ? '1' : '0'}"
                           data-new-state="${isActive ? '0' : '1'}"
                           data-toggle-url="${isActive ? urlDesactivar : urlActivar}"
                           data-url-activar="${urlActivar}"
                           data-url-desactivar="${urlDesactivar}"
                           data-confirm-activar="Desea activar este producto?"
                           data-confirm-desactivar="Desea desactivar este producto?">
                            <i data-lucide="${isActive ? 'ban' : 'check-circle'}" class="h-4 w-4 mr-1"></i>
                            <span data-toggle-text>${isActive ? 'Desactivar' : 'Activar'}</span>
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

        fetch(`${baseUrl}productos/search?term=${encodeURIComponent(value)}`)
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
<div id="modalProducto" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-16 mx-auto p-6 border w-11/12 max-w-5xl shadow-lg rounded-2xl bg-white">
        <div class="mt-3 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Nuevo Producto</h3>
                <button type="button" onclick="cerrarModal()" class="btn-ghost px-3 py-2">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </button>
            </div>
            <form id="formProducto" method="POST" action="">
                <input type="hidden" name="codProducto" id="codProducto">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nombre *</label>
                        <input type="text" name="nombre" id="nombre" required class="input-modern mt-1">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Codigo de barras</label>
                        <input type="text" name="codigoBarras" id="codigoBarras" class="input-modern mt-1">
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Descripcion</label>
                    <textarea name="descripcion" id="descripcion" rows="2" class="input-modern mt-1"></textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Categoria *</label>
                        <select name="idCategoria" id="idCategoria" required class="input-modern mt-1">
                            <option value="">Seleccione...</option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?php echo $cat['idCategoria']; ?>"><?php echo htmlspecialchars($cat['nombre']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Medida *</label>
                        <select name="idMedida" id="idMedida" required class="input-modern mt-1">
                            <option value="">Seleccione...</option>
                            <?php foreach ($medidas as $med): ?>
                                <option value="<?php echo $med['idMedida']; ?>"><?php echo htmlspecialchars($med['nombre'] . ' (' . $med['abreviatura'] . ')'); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Precio compra *</label>
                        <input type="number" name="precioCompra" id="precioCompra" step="0.01" min="0" value="0" required class="input-modern mt-1">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Precio venta *</label>
                        <input type="number" name="precioVenta" id="precioVenta" step="0.01" min="0" value="0" required class="input-modern mt-1">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Existencia</label>
                        <input type="number" name="existencia" id="existencia" min="0" value="0" class="input-modern mt-1">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Stock minimo</label>
                        <input type="number" name="stockMinimo" id="stockMinimo" min="0" value="10" class="input-modern mt-1">
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="cerrarModal()" class="btn-ghost">Cancelar</button>
                    <button type="submit" class="btn-primary">
                        <i data-lucide="save" class="h-4 w-4 mr-2"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function abrirModal(accion, producto = null) {
    const modal = document.getElementById('modalProducto');
    const form = document.getElementById('formProducto');
    const title = document.getElementById('modalTitle');
    
    if (accion === 'crear') {
        title.textContent = 'Nuevo Producto';
        form.action = '<?php echo BASE_URL; ?>productos/create';
        form.reset();
        document.getElementById('codProducto').value = '';
        document.getElementById('precioCompra').value = document.getElementById('precioCompra').defaultValue || 0;
        document.getElementById('precioVenta').value = document.getElementById('precioVenta').defaultValue || 0;
    } else {
        title.textContent = 'Editar Producto';
        form.action = '<?php echo BASE_URL; ?>productos/update';
        document.getElementById('codProducto').value = producto.codProducto;
        document.getElementById('nombre').value = producto.nombre;
        document.getElementById('descripcion').value = producto.descripcion || '';
        document.getElementById('idCategoria').value = producto.idCategoria;
        document.getElementById('idMedida').value = producto.idMedida;
        document.getElementById('precioCompra').value = producto.precioCompra ?? 0;
        document.getElementById('precioVenta').value = producto.precioVenta ?? 0;
        document.getElementById('existencia').value = producto.existencia;
        document.getElementById('stockMinimo').value = producto.stockMinimo;
        document.getElementById('codigoBarras').value = producto.codigoBarras || '';
    }
    
    modal.classList.remove('hidden');
}

function cerrarModal() {
    document.getElementById('modalProducto').classList.add('hidden');
}
</script>
