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
                <button type="submit" class="btn-primary">
                    <i data-lucide="search" class="h-4 w-4 mr-2"></i> Buscar
                </button>
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
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Existencia</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock Min.</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($productos)): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
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
                                    <span class="text-sm font-bold text-blue-600">$<?php echo number_format($producto['precio'], 2); ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="pill <?php echo $sinStock ? 'bg-pink-pastel/70' : ($stockBajo ? 'bg-peach-pastel/70' : 'bg-green-pastel/70'); ?> text-gray-800">
                                        <?php echo $producto['existencia'] . ' ' . $producto['medida_abrev']; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="text-xs"><?php echo $producto['stockMinimo']; ?> <?php echo $producto['medida_abrev']; ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <button onclick="abrirModal('editar', <?php echo htmlspecialchars(json_encode($producto)); ?>)"
                                                class="btn-ghost px-3 py-2">
                                            <i data-lucide="edit" class="h-4 w-4 mr-1"></i> Editar
                                        </button>
                                        <a href="<?php echo BASE_URL; ?>productos/delete/<?php echo $producto['codProducto']; ?>"
                                           onclick="return confirmarEliminacion()"
                                           class="btn-ghost px-3 py-2 text-red-700">
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
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Precio *</label>
                        <input type="number" name="precio" id="precio" step="0.01" min="0" required class="input-modern mt-1">
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
    } else {
        title.textContent = 'Editar Producto';
        form.action = '<?php echo BASE_URL; ?>productos/update';
        document.getElementById('codProducto').value = producto.codProducto;
        document.getElementById('nombre').value = producto.nombre;
        document.getElementById('descripcion').value = producto.descripcion || '';
        document.getElementById('idCategoria').value = producto.idCategoria;
        document.getElementById('idMedida').value = producto.idMedida;
        document.getElementById('precio').value = producto.precio;
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
