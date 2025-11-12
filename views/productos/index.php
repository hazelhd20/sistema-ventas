<?php
$pageTitle = "Productos";
?>

<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Productos</h2>
        <p class="text-gray-600">Gestión de inventario</p>
    </div>
    <button onclick="abrirModal('crear')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
        <i class="fas fa-plus"></i> Nuevo Producto
    </button>
</div>

<!-- Búsqueda -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" action="<?php echo BASE_URL; ?>productos" class="flex gap-4">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search ?? ''); ?>" 
               placeholder="Buscar por nombre, código de barras, descripción, categoría o código..." 
               class="flex-1 px-4 py-2 border rounded">
        <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded">
            <i class="fas fa-search"></i> Buscar
        </button>
        <a href="<?php echo BASE_URL; ?>productos" class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded">
            <i class="fas fa-times"></i> Limpiar
        </a>
    </form>
</div>

<!-- Tabla de productos -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Código</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Categoría</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Existencia</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock Mín.</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php if (empty($productos)): ?>
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No hay productos registrados</td>
                </tr>
            <?php else: ?>
                <?php foreach ($productos as $producto): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $producto['codProducto']; ?></td>
                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($producto['nombre']); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($producto['categoria_nombre']); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$<?php echo number_format($producto['precio'], 2); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded <?php echo $producto['existencia'] <= $producto['stockMinimo'] ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'; ?>">
                                <?php echo $producto['existencia'] . ' ' . $producto['medida_abrev']; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $producto['stockMinimo']; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="abrirModal('editar', <?php echo htmlspecialchars(json_encode($producto)); ?>)" 
                                    class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-edit"></i>
                            </button>
                            <a href="<?php echo BASE_URL; ?>productos/delete/<?php echo $producto['codProducto']; ?>" 
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
<div id="modalProducto" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-6 border w-11/12 max-w-5xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-6" id="modalTitle">Nuevo Producto</h3>
            <form id="formProducto" method="POST" action="">
                <input type="hidden" name="codProducto" id="codProducto">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nombre *</label>
                        <input type="text" name="nombre" id="nombre" required 
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Código de Barras</label>
                        <input type="text" name="codigoBarras" id="codigoBarras" 
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="2" 
                              class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2"></textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Categoría *</label>
                        <select name="idCategoria" id="idCategoria" required 
                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                            <option value="">Seleccione...</option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?php echo $cat['idCategoria']; ?>"><?php echo htmlspecialchars($cat['nombre']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Medida *</label>
                        <select name="idMedida" id="idMedida" required 
                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
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
                        <input type="number" name="precio" id="precio" step="0.01" min="0" required 
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Existencia</label>
                        <input type="number" name="existencia" id="existencia" min="0" value="0" 
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Stock Mínimo</label>
                        <input type="number" name="stockMinimo" id="stockMinimo" min="0" value="10" 
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
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

