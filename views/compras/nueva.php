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
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold">Agregar Productos</h3>
                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                        <i class="fas fa-keyboard"></i> Presiona Enter para agregar
                    </span>
                </div>
                
                <div class="mb-4 relative">
                    <input type="text" id="buscarProducto" placeholder="Buscar producto por nombre o código (escribe y presiona Enter)..." 
                           class="w-full px-4 py-3 border-2 border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg"
                           onkeyup="buscarProductos()"
                           onkeydown="manejarTeclado(event)"
                           autofocus>
                    <i class="fas fa-search absolute right-3 top-3.5 text-gray-400"></i>
                </div>
                
                <div id="resultadosProductos" class="max-h-64 overflow-y-auto overflow-x-hidden mb-4 space-y-2">
                    <!-- Los resultados se mostrarán aquí -->
                </div>
                
                <div id="mensajeBusqueda" class="hidden text-center text-gray-500 py-4">
                    <i class="fas fa-search text-2xl mb-2"></i>
                    <p>Escribe al menos 2 caracteres para buscar productos</p>
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
                    <div id="listaProductos" class="mb-4 max-h-64 overflow-y-auto space-y-2">
                        <!-- Los productos se mostrarán aquí -->
                    </div>
                    
                    <div class="border-t pt-4">
                        <div class="flex justify-between mb-4 text-2xl font-bold text-green-600">
                            <span>Total:</span>
                            <span id="total">$0.00</span>
                        </div>
                        
                        <input type="hidden" name="total" id="totalForm">
                        <input type="hidden" name="detalles" id="detallesForm">
                        
                        <button type="submit" id="btnRegistrar" 
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                            <i class="fas fa-check-circle"></i> Registrar Compra
                        </button>
                        
                        <a href="<?php echo BASE_URL; ?>compras" 
                           class="block mt-3 text-center text-gray-600 hover:text-gray-800 transition-colors">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
let carrito = [];
let resultadosActuales = [];

function buscarProductos() {
    const termino = document.getElementById('buscarProducto').value.trim();
    const resultados = document.getElementById('resultadosProductos');
    const mensaje = document.getElementById('mensajeBusqueda');
    
    if (termino.length < 2) {
        resultados.innerHTML = '';
        mensaje.classList.remove('hidden');
        resultadosActuales = [];
        return;
    }
    
    mensaje.classList.add('hidden');
    
    fetch('<?php echo BASE_URL; ?>productos/search?term=' + encodeURIComponent(termino))
        .then(response => response.json())
        .then(data => {
            resultadosActuales = data;
            
            if (data.length === 0) {
                resultados.innerHTML = '<div class="text-center text-gray-500 py-8"><i class="fas fa-search text-3xl mb-2"></i><p>No se encontraron productos</p></div>';
                return;
            }
            
            let html = '<div class="space-y-2">';
            data.forEach((producto, index) => {
                html += `
                    <div class="p-4 border-2 border-gray-200 bg-white rounded-lg hover:shadow-md cursor-pointer transition-all duration-200" 
                         onclick="seleccionarProducto(${index})"
                         data-index="${index}">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1 flex-wrap">
                                    <p class="font-bold text-base sm:text-lg break-words">${producto.nombre}</p>
                                    <i class="fas fa-box text-sm text-gray-400 flex-shrink-0"></i>
                                </div>
                                ${producto.codigoBarras ? `
                                <div class="mb-2">
                                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                        <i class="fas fa-barcode text-xs mr-1"></i>Código: ${producto.codigoBarras}
                                    </span>
                                </div>
                                ` : ''}
                                <div class="flex flex-col sm:flex-row sm:gap-4 gap-1 text-sm text-gray-600 mb-3">
                                    <p class="whitespace-nowrap"><i class="fas fa-box text-xs"></i> Stock actual: <span class="font-semibold">${producto.existencia} ${producto.medida_abrev}</span></p>
                                    <p class="whitespace-nowrap"><i class="fas fa-dollar-sign text-xs"></i> Precio actual: <span class="font-semibold text-blue-600">$${parseFloat(producto.precio).toFixed(2)}</span></p>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <div class="flex-1 min-w-0">
                                        <label class="text-xs text-gray-600 block mb-1 whitespace-nowrap">Cantidad</label>
                                        <input type="number" id="cant_${index}" min="1" value="1" 
                                               class="w-full px-2 py-2 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               onclick="event.stopPropagation()"
                                               onchange="actualizarCantidadSeleccionada(${index})">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <label class="text-xs text-gray-600 block mb-1 whitespace-nowrap">Precio Compra</label>
                                        <input type="number" id="precio_${index}" step="0.01" min="0" 
                                               value="${producto.precio}" 
                                               class="w-full px-2 py-2 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               onclick="event.stopPropagation()">
                                    </div>
                                </div>
                            </div>
                            <button onclick="event.stopPropagation(); agregarAlCarrito(${JSON.stringify(producto).replace(/"/g, '&quot;')}, ${index})" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow hover:shadow-lg transition-all whitespace-nowrap flex-shrink-0 self-start sm:self-auto">
                                <i class="fas fa-plus"></i> Agregar
                            </button>
                        </div>
                    </div>
                `;
            });
            html += '</div>';
            resultados.innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            resultados.innerHTML = '<div class="text-center text-red-500 py-4"><i class="fas fa-exclamation-circle"></i> Error al buscar productos</div>';
        });
}

function seleccionarProducto(index) {
    if (resultadosActuales[index]) {
        const cantidadInput = document.getElementById('cant_' + index);
        const precioInput = document.getElementById('precio_' + index);
        const cantidad = cantidadInput ? parseInt(cantidadInput.value) : 1;
        const precio = precioInput ? parseFloat(precioInput.value) : resultadosActuales[index].precio;
        agregarAlCarrito(resultadosActuales[index], index, cantidad, precio);
    }
}

function actualizarCantidadSeleccionada(index) {
    // Función para actualizar la cantidad antes de agregar
}

function manejarTeclado(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        const termino = document.getElementById('buscarProducto').value.trim();
        
        if (termino.length >= 2 && resultadosActuales.length > 0) {
            // Agregar el primer resultado
            seleccionarProducto(0);
        } else if (termino.length >= 2) {
            // Si hay término pero no resultados, buscar
            buscarProductos();
        }
    } else if (event.key === 'Escape') {
        document.getElementById('buscarProducto').value = '';
        document.getElementById('resultadosProductos').innerHTML = '';
        document.getElementById('mensajeBusqueda').classList.remove('hidden');
        resultadosActuales = [];
    }
}

function agregarAlCarrito(producto, index = null, cantidad = null, precioCompra = null) {
    // Obtener valores del input si existen
    let cantidadFinal = cantidad;
    let precioFinal = precioCompra;
    
    if (index !== null) {
        const cantidadInput = document.getElementById('cant_' + index);
        const precioInput = document.getElementById('precio_' + index);
        if (cantidadInput) cantidadFinal = parseInt(cantidadInput.value) || cantidad || 1;
        if (precioInput) precioFinal = parseFloat(precioInput.value) || precioCompra || producto.precio;
    } else {
        cantidadFinal = cantidad || parseInt(document.getElementById('cant_' + producto.codProducto)?.value) || 1;
        precioFinal = precioCompra || parseFloat(document.getElementById('precio_' + producto.codProducto)?.value) || producto.precio;
    }
    
    if (cantidadFinal <= 0 || precioFinal <= 0) {
        mostrarNotificacion('La cantidad y el precio deben ser mayores a cero', 'error');
        return;
    }
    
    const existente = carrito.find(p => p.codProducto === producto.codProducto);
    
    if (existente) {
        existente.cantidad += cantidadFinal;
        existente.precioCompra = precioFinal;
        existente.subtotal = existente.cantidad * existente.precioCompra;
    } else {
        carrito.push({
            codProducto: producto.codProducto,
            nombre: producto.nombre,
            medida_abrev: producto.medida_abrev,
            precioCompra: precioFinal,
            cantidad: cantidadFinal,
            subtotal: cantidadFinal * precioFinal
        });
    }
    
    actualizarCarrito();
    mostrarNotificacion(`Producto agregado: ${producto.nombre}`, 'success');
    
    // Limpiar búsqueda y enfocar de nuevo
    document.getElementById('buscarProducto').value = '';
    document.getElementById('resultadosProductos').innerHTML = '';
    document.getElementById('mensajeBusqueda').classList.remove('hidden');
    resultadosActuales = [];
    document.getElementById('buscarProducto').focus();
}

function mostrarNotificacion(mensaje, tipo = 'info') {
    const notificacion = document.createElement('div');
    notificacion.className = `fixed top-20 right-4 z-50 px-4 py-3 rounded-lg shadow-lg transform transition-all duration-300 ${
        tipo === 'success' ? 'bg-green-500 text-white' : 
        tipo === 'error' ? 'bg-red-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    notificacion.innerHTML = `
        <div class="flex items-center gap-2">
            <i class="fas ${tipo === 'success' ? 'fa-check-circle' : tipo === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i>
            <span>${mensaje}</span>
        </div>
    `;
    document.body.appendChild(notificacion);
    
    setTimeout(() => {
        notificacion.style.opacity = '0';
        notificacion.style.transform = 'translateX(100%)';
        setTimeout(() => notificacion.remove(), 300);
    }, 2000);
}

function eliminarDelCarrito(index) {
    if (confirm('¿Eliminar este producto del carrito?')) {
        const producto = carrito[index];
        carrito.splice(index, 1);
        actualizarCarrito();
        mostrarNotificacion(`Producto eliminado: ${producto.nombre}`, 'info');
    }
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
            <div class="flex justify-between items-center p-3 border-b border-gray-200 hover:bg-gray-50 rounded-lg transition-colors">
                <div class="flex-1">
                    <p class="font-semibold text-sm text-gray-900">${producto.nombre}</p>
                    <p class="text-xs text-gray-600 mt-1">$${producto.precioCompra.toFixed(2)} x ${producto.cantidad} ${producto.medida_abrev}</p>
                </div>
                <div class="flex items-center space-x-2">
                    <button onclick="actualizarCantidad(${index}, -1)" 
                            class="bg-gray-200 hover:bg-gray-300 px-2.5 py-1.5 rounded-lg transition-colors">
                        <i class="fas fa-minus text-xs"></i>
                    </button>
                    <span class="w-10 text-center font-semibold">${producto.cantidad}</span>
                    <button onclick="actualizarCantidad(${index}, 1)" 
                            class="bg-gray-200 hover:bg-gray-300 px-2.5 py-1.5 rounded-lg transition-colors">
                        <i class="fas fa-plus text-xs"></i>
                    </button>
                    <button onclick="eliminarDelCarrito(${index})" 
                            class="text-red-600 hover:text-red-800 hover:bg-red-50 ml-2 px-2 py-1 rounded-lg transition-colors"
                            title="Eliminar">
                        <i class="fas fa-trash text-sm"></i>
                    </button>
                </div>
                <div class="ml-4 font-bold text-green-600 min-w-[80px] text-right">
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
        mostrarNotificacion('Debe agregar al menos un producto', 'error');
        return;
    }
    
    if (!document.getElementById('idProveedor').value) {
        e.preventDefault();
        mostrarNotificacion('Debe seleccionar un proveedor', 'error');
        document.getElementById('idProveedor').focus();
        return;
    }
});

// Enfocar el campo de búsqueda al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('buscarProducto').focus();
    document.getElementById('mensajeBusqueda').classList.remove('hidden');
});
</script>

