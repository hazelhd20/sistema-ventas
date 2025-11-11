<?php
$pageTitle = "Nueva Compra";
?>

<div class="mb-6">
    <h2 class="text-3xl font-bold text-gray-800">Nueva Compra</h2>
    <p class="text-gray-600">Registre una nueva compra a proveedor</p>
</div>

<form id="formCompra" method="POST" action="<?php echo BASE_URL; ?>compras/create">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Panel izquierdo: Información de compra -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-xl font-bold mb-4">Información de la Compra</h3>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Proveedor *</label>
                    <select name="idProveedor" id="idProveedor" required 
                            class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Seleccione un proveedor...</option>
                        <?php foreach ($proveedores as $proveedor): ?>
                            <option value="<?php echo $proveedor['idProveedor']; ?>">
                                <?php echo htmlspecialchars($proveedor['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Observaciones</label>
                    <textarea name="observaciones" id="observaciones" rows="3" 
                              class="w-full px-4 py-2 border rounded"></textarea>
                </div>
            </div>
            
            <!-- Búsqueda de productos -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-bold mb-4">Agregar Productos</h3>
                
                <div class="mb-4">
                    <input type="text" id="buscarProducto" placeholder="Buscar producto por nombre o código..." 
                           class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                           onkeyup="buscarProductos()">
                </div>
                
                <div id="resultadosProductos" class="max-h-64 overflow-y-auto mb-4">
                    <!-- Los resultados se mostrarán aquí -->
                </div>
            </div>
        </div>
        
        <!-- Panel derecho: Resumen -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6 sticky top-6">
                <h3 class="text-xl font-bold mb-4">Resumen de Compra</h3>
                
                <div id="carritoVacio" class="text-center text-gray-500 py-8">
                    <i class="fas fa-shopping-bag text-4xl mb-2"></i>
                    <p>No hay productos agregados</p>
                </div>
                
                <div id="carritoProductos" class="hidden">
                    <div id="listaProductos" class="mb-4 max-h-64 overflow-y-auto">
                        <!-- Los productos se mostrarán aquí -->
                    </div>
                    
                    <div class="border-t pt-4">
                        <div class="flex justify-between mb-4 text-xl font-bold">
                            <span>Total:</span>
                            <span id="total">$0.00</span>
                        </div>
                        
                        <input type="hidden" name="total" id="totalForm">
                        <input type="hidden" name="detalles" id="detallesForm">
                        
                        <button type="submit" id="btnRegistrar" 
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded">
                            <i class="fas fa-check"></i> Registrar Compra
                        </button>
                        
                        <a href="<?php echo BASE_URL; ?>compras" 
                           class="block mt-3 text-center text-gray-600 hover:text-gray-800">
                            Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
let carrito = [];

function buscarProductos() {
    const termino = document.getElementById('buscarProducto').value;
    const resultados = document.getElementById('resultadosProductos');
    
    if (termino.length < 2) {
        resultados.innerHTML = '';
        return;
    }
    
    fetch('<?php echo BASE_URL; ?>productos/search?term=' + encodeURIComponent(termino))
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                resultados.innerHTML = '<p class="text-gray-500 p-4">No se encontraron productos</p>';
                return;
            }
            
            let html = '<div class="space-y-2">';
            data.forEach(producto => {
                html += `
                    <div class="p-3 border rounded hover:bg-gray-50">
                        <div class="flex justify-between items-center mb-2">
                            <div>
                                <p class="font-semibold">${producto.nombre}</p>
                                <p class="text-sm text-gray-600">Stock actual: ${producto.existencia} ${producto.medida_abrev}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <div>
                                <label class="text-xs text-gray-600">Cantidad</label>
                                <input type="number" id="cant_${producto.codProducto}" min="1" value="1" 
                                       class="w-full px-2 py-1 border rounded text-sm">
                            </div>
                            <div>
                                <label class="text-xs text-gray-600">Precio Compra</label>
                                <input type="number" id="precio_${producto.codProducto}" step="0.01" min="0" 
                                       value="${producto.precio}" 
                                       class="w-full px-2 py-1 border rounded text-sm">
                            </div>
                            <div class="flex items-end">
                                <button onclick="agregarAlCarrito(${JSON.stringify(producto).replace(/"/g, '&quot;')})" 
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                    <i class="fas fa-plus"></i> Agregar
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });
            html += '</div>';
            resultados.innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function agregarAlCarrito(producto) {
    const cantidad = parseInt(document.getElementById('cant_' + producto.codProducto).value);
    const precioCompra = parseFloat(document.getElementById('precio_' + producto.codProducto).value);
    
    if (cantidad <= 0 || precioCompra <= 0) {
        alert('La cantidad y el precio deben ser mayores a cero');
        return;
    }
    
    const existente = carrito.find(p => p.codProducto === producto.codProducto);
    
    if (existente) {
        existente.cantidad += cantidad;
        existente.precioCompra = precioCompra;
        existente.subtotal = existente.cantidad * existente.precioCompra;
    } else {
        carrito.push({
            codProducto: producto.codProducto,
            nombre: producto.nombre,
            medida_abrev: producto.medida_abrev,
            precioCompra: precioCompra,
            cantidad: cantidad,
            subtotal: cantidad * precioCompra
        });
    }
    
    actualizarCarrito();
    document.getElementById('buscarProducto').value = '';
    document.getElementById('resultadosProductos').innerHTML = '';
}

function eliminarDelCarrito(index) {
    carrito.splice(index, 1);
    actualizarCarrito();
}

function actualizarCantidad(index, cambio) {
    const producto = carrito[index];
    const nuevaCantidad = producto.cantidad + cambio;
    
    if (nuevaCantidad < 1) {
        eliminarDelCarrito(index);
        return;
    }
    
    producto.cantidad = nuevaCantidad;
    producto.subtotal = producto.cantidad * producto.precioCompra;
    actualizarCarrito();
}

function actualizarCarrito() {
    const carritoVacio = document.getElementById('carritoVacio');
    const carritoProductos = document.getElementById('carritoProductos');
    const listaProductos = document.getElementById('listaProductos');
    
    if (carrito.length === 0) {
        carritoVacio.classList.remove('hidden');
        carritoProductos.classList.add('hidden');
        return;
    }
    
    carritoVacio.classList.add('hidden');
    carritoProductos.classList.remove('hidden');
    
    let html = '';
    let total = 0;
    
    carrito.forEach((producto, index) => {
        total += producto.subtotal;
        html += `
            <div class="flex justify-between items-center p-2 border-b">
                <div class="flex-1">
                    <p class="font-semibold text-sm">${producto.nombre}</p>
                    <p class="text-xs text-gray-600">$${producto.precioCompra.toFixed(2)} x ${producto.cantidad} ${producto.medida_abrev}</p>
                </div>
                <div class="flex items-center space-x-2">
                    <button onclick="actualizarCantidad(${index}, -1)" 
                            class="bg-gray-200 hover:bg-gray-300 px-2 py-1 rounded">
                        <i class="fas fa-minus text-xs"></i>
                    </button>
                    <span class="w-8 text-center">${producto.cantidad}</span>
                    <button onclick="actualizarCantidad(${index}, 1)" 
                            class="bg-gray-200 hover:bg-gray-300 px-2 py-1 rounded">
                        <i class="fas fa-plus text-xs"></i>
                    </button>
                    <button onclick="eliminarDelCarrito(${index})" 
                            class="text-red-600 hover:text-red-800 ml-2">
                        <i class="fas fa-trash text-xs"></i>
                    </button>
                </div>
                <div class="ml-4 font-semibold">
                    $${producto.subtotal.toFixed(2)}
                </div>
            </div>
        `;
    });
    
    listaProductos.innerHTML = html;
    
    document.getElementById('total').textContent = '$' + total.toFixed(2);
    document.getElementById('totalForm').value = total;
    document.getElementById('detallesForm').value = JSON.stringify(carrito);
}

document.getElementById('formCompra').addEventListener('submit', function(e) {
    if (carrito.length === 0) {
        e.preventDefault();
        alert('Debe agregar al menos un producto');
        return;
    }
    
    if (!document.getElementById('idProveedor').value) {
        e.preventDefault();
        alert('Debe seleccionar un proveedor');
        return;
    }
});
</script>

