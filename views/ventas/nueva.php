<?php
$pageTitle = "Nueva Venta";
?>

<div class="mb-6">
    <h2 class="text-3xl font-bold text-gray-800">Nueva Venta</h2>
    <p class="text-gray-600">Registre una nueva venta</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Panel izquierdo: Búsqueda de productos -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold">Buscar Producto</h3>
                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                    <i class="fas fa-keyboard"></i> Presiona Enter para agregar
                </span>
            </div>
            
            <div class="mb-4 relative">
                <input type="text" id="buscarProducto" placeholder="Buscar por nombre o código de barras (escribe y presiona Enter)..." 
                       class="w-full px-4 py-3 border-2 border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg"
                       onkeyup="buscarProductos()" 
                       onkeydown="manejarTeclado(event)"
                       autofocus>
                <i class="fas fa-search absolute right-3 top-3.5 text-gray-400"></i>
            </div>
            
            <div id="resultadosProductos" class="max-h-96 overflow-y-auto overflow-x-hidden space-y-2">
                <!-- Los resultados se mostrarán aquí -->
            </div>
            
            <div id="mensajeBusqueda" class="hidden text-center text-gray-500 py-4">
                <i class="fas fa-search text-2xl mb-2"></i>
                <p>Escribe al menos 2 caracteres para buscar productos</p>
            </div>
        </div>
        
        <!-- Búsqueda de cliente -->
        <div class="bg-white rounded-lg shadow p-6 mt-6">
            <h3 class="text-xl font-bold mb-4">Cliente (Opcional)</h3>
            
            <div class="mb-4 relative">
                <input type="text" id="buscarCliente" placeholder="Buscar cliente por nombre o teléfono..." 
                       class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                       onkeyup="buscarClientes()">
                <i class="fas fa-user absolute right-3 top-2.5 text-gray-400"></i>
            </div>
            
            <div id="resultadosClientes" class="max-h-40 overflow-y-auto">
                <!-- Los resultados se mostrarán aquí -->
            </div>
            
            <input type="hidden" id="idClienteSeleccionado" name="idCliente">
            <div id="clienteSeleccionado" class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200 hidden flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-blue-600 mr-2"></i>
                    <span id="nombreClienteSeleccionado" class="font-semibold text-blue-800"></span>
                </div>
                <button type="button" onclick="quitarCliente()" class="text-red-600 hover:text-red-800 hover:bg-red-50 px-2 py-1 rounded">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Panel derecho: Carrito de venta -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow p-6 sticky top-6">
            <h3 class="text-xl font-bold mb-4">Carrito de Venta</h3>
            
            <div id="carritoVacio" class="text-center text-gray-500 py-8">
                <i class="fas fa-shopping-cart text-4xl mb-2"></i>
                <p>El carrito está vacío</p>
            </div>
            
            <div id="carritoProductos" class="hidden">
                <div id="listaProductos" class="mb-4 max-h-64 overflow-y-auto space-y-2">
                    <!-- Los productos se mostrarán aquí -->
                </div>
                
                <div class="border-t pt-4">
                    <div class="flex justify-between mb-2 text-sm">
                        <span class="font-semibold text-gray-600">Subtotal:</span>
                        <span id="subtotal" class="font-semibold">$0.00</span>
                    </div>
                    <div class="flex justify-between mb-4 text-2xl font-bold text-green-600">
                        <span>Total:</span>
                        <span id="total">$0.00</span>
                    </div>
                    
                    <form id="formVenta" method="POST" action="<?php echo BASE_URL; ?>ventas/create">
                        <input type="hidden" name="idCliente" id="idClienteForm">
                        <input type="hidden" name="total" id="totalForm">
                        <input type="hidden" name="detalles" id="detallesForm">
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Forma de Pago *</label>
                            <select name="idFormaPago" id="idFormaPago" required 
                                    class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Seleccione...</option>
                                <?php foreach ($formasPago as $fp): ?>
                                    <option value="<?php echo $fp['idFormaPago']; ?>"><?php echo htmlspecialchars($fp['nombre']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Observaciones</label>
                            <textarea name="observaciones" id="observaciones" rows="2" 
                                      class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>
                        
                        <button type="submit" id="btnFinalizar" 
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                            <i class="fas fa-check-circle"></i> Finalizar Venta
                        </button>
                    </form>
                    
                    <a href="<?php echo BASE_URL; ?>ventas" 
                       class="block mt-3 text-center text-gray-600 hover:text-gray-800 transition-colors">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let carrito = [];
let clienteSeleccionado = null;

let productoSeleccionado = null;
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
                const stockBajo = producto.existencia <= 5;
                const sinStock = producto.existencia <= 0;
                const stockClass = sinStock ? 'bg-red-50 border-red-300' : stockBajo ? 'bg-yellow-50 border-yellow-300' : 'bg-white border-gray-200';
                const stockIcon = sinStock ? 'fa-exclamation-circle text-red-600' : stockBajo ? 'fa-exclamation-triangle text-yellow-600' : 'fa-check-circle text-green-600';
                
                html += `
                    <div class="p-4 border-2 ${stockClass} rounded-lg hover:shadow-md cursor-pointer transition-all duration-200" 
                         onclick="seleccionarProducto(${index})"
                         data-index="${index}">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1 flex-wrap">
                                    <p class="font-bold text-base sm:text-lg break-words">${producto.nombre}</p>
                                    <i class="fas ${stockIcon} text-sm flex-shrink-0" title="${sinStock ? 'Sin stock' : stockBajo ? 'Stock bajo' : 'Stock disponible'}"></i>
                                </div>
                                ${producto.codigoBarras ? `
                                <div class="mb-2">
                                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                        <i class="fas fa-barcode text-xs mr-1"></i>Código: ${producto.codigoBarras}
                                    </span>
                                </div>
                                ` : ''}
                                <div class="flex flex-col sm:flex-row sm:gap-4 gap-1 text-sm text-gray-600">
                                    <p class="whitespace-nowrap"><i class="fas fa-box text-xs"></i> Stock: <span class="font-semibold ${sinStock ? 'text-red-600' : stockBajo ? 'text-yellow-600' : 'text-green-600'}">${producto.existencia} ${producto.medida_abrev}</span></p>
                                    <p class="whitespace-nowrap"><i class="fas fa-dollar-sign text-xs"></i> Precio: <span class="font-semibold text-blue-600">$${parseFloat(producto.precio).toFixed(2)}</span></p>
                                </div>
                                ${!sinStock ? `
                                <div class="mt-2 flex items-center gap-2 flex-wrap">
                                    <label class="text-xs text-gray-600 whitespace-nowrap">Cantidad:</label>
                                    <input type="number" id="cant_${index}" min="1" max="${producto.existencia}" value="1" 
                                           class="w-20 px-2 py-1 border rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 flex-shrink-0"
                                           onclick="event.stopPropagation()"
                                           onchange="actualizarCantidadSeleccionada(${index})">
                                </div>
                                ` : '<p class="text-red-600 text-sm mt-2"><i class="fas fa-ban"></i> Sin stock disponible</p>'}
                            </div>
                            ${!sinStock ? `
                            <button onclick="event.stopPropagation(); agregarAlCarrito(${JSON.stringify(producto).replace(/"/g, '&quot;')}, ${index})" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow hover:shadow-lg transition-all whitespace-nowrap flex-shrink-0 self-start sm:self-auto">
                                <i class="fas fa-plus"></i> Agregar
                            </button>
                            ` : ''}
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
    if (resultadosActuales[index] && resultadosActuales[index].existencia > 0) {
        const cantidadInput = document.getElementById('cant_' + index);
        const cantidad = cantidadInput ? parseInt(cantidadInput.value) : 1;
        agregarAlCarrito(resultadosActuales[index], index, cantidad);
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
            // Agregar el primer resultado disponible
            const productoDisponible = resultadosActuales.find(p => p.existencia > 0);
            if (productoDisponible) {
                const index = resultadosActuales.indexOf(productoDisponible);
                seleccionarProducto(index);
            } else {
                alert('No hay productos disponibles con stock');
            }
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

function buscarClientes() {
    const termino = document.getElementById('buscarCliente').value;
    const resultados = document.getElementById('resultadosClientes');
    
    if (termino.length < 2) {
        resultados.innerHTML = '';
        return;
    }
    
    fetch('<?php echo BASE_URL; ?>clientes/search?term=' + encodeURIComponent(termino))
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                resultados.innerHTML = '<p class="text-gray-500 p-2 text-sm">No se encontraron clientes</p>';
                return;
            }
            
            let html = '<div class="space-y-1">';
            data.forEach(cliente => {
                const nombreCompleto = cliente.nombre + ' ' + (cliente.apellidos || '');
                html += `
                    <div class="p-2 border rounded hover:bg-gray-50 cursor-pointer text-sm" 
                         onclick="seleccionarCliente(${JSON.stringify(cliente).replace(/"/g, '&quot;')})">
                        ${nombreCompleto} ${cliente.telefono ? ' - ' + cliente.telefono : ''}
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

function agregarAlCarrito(producto, index = null, cantidad = null) {
    if (producto.existencia <= 0) {
        mostrarNotificacion('No hay stock disponible de este producto', 'error');
        return;
    }
    
    // Obtener cantidad del input si existe, sino usar la cantidad pasada o 1
    let cantidadFinal = cantidad;
    if (index !== null) {
        const cantidadInput = document.getElementById('cant_' + index);
        if (cantidadInput) {
            cantidadFinal = parseInt(cantidadInput.value) || 1;
        }
    }
    cantidadFinal = cantidadFinal || 1;
    
    if (cantidadFinal <= 0 || cantidadFinal > producto.existencia) {
        mostrarNotificacion('La cantidad debe ser mayor a 0 y no exceder el stock disponible', 'error');
        return;
    }
    
    const existente = carrito.find(p => p.codProducto === producto.codProducto);
    
    if (existente) {
        const nuevaCantidad = existente.cantidad + cantidadFinal;
        if (nuevaCantidad > producto.existencia) {
            mostrarNotificacion('No hay suficiente stock disponible', 'error');
            return;
        }
        existente.cantidad = nuevaCantidad;
        existente.subtotal = existente.cantidad * existente.precio;
    } else {
        carrito.push({
            codProducto: producto.codProducto,
            nombre: producto.nombre,
            medida_abrev: producto.medida_abrev,
            precio: parseFloat(producto.precio),
            cantidad: cantidadFinal,
            subtotal: parseFloat(producto.precio) * cantidadFinal,
            existencia: producto.existencia
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
    
    if (nuevaCantidad > producto.existencia) {
        mostrarNotificacion('No hay suficiente stock disponible', 'error');
        return;
    }
    
    producto.cantidad = nuevaCantidad;
    producto.subtotal = producto.cantidad * producto.precio;
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
    let subtotal = 0;
    
    carrito.forEach((producto, index) => {
        subtotal += producto.subtotal;
        const stockRestante = producto.existencia - producto.cantidad;
        const stockBajo = stockRestante <= 5;
        
        html += `
            <div class="flex justify-between items-center p-3 border-b border-gray-200 hover:bg-gray-50 rounded-lg transition-colors">
                <div class="flex-1">
                    <p class="font-semibold text-sm text-gray-900">${producto.nombre}</p>
                    <div class="flex items-center gap-3 mt-1">
                        <p class="text-xs text-gray-600">$${producto.precio.toFixed(2)} x ${producto.cantidad} ${producto.medida_abrev}</p>
                        ${stockBajo && stockRestante > 0 ? `<span class="text-xs text-yellow-600"><i class="fas fa-exclamation-triangle"></i> Quedan ${stockRestante}</span>` : ''}
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <button onclick="actualizarCantidad(${index}, -1)" 
                            class="bg-gray-200 hover:bg-gray-300 px-2.5 py-1.5 rounded-lg transition-colors">
                        <i class="fas fa-minus text-xs"></i>
                    </button>
                    <span class="w-10 text-center font-semibold">${producto.cantidad}</span>
                    <button onclick="actualizarCantidad(${index}, 1)" 
                            class="bg-gray-200 hover:bg-gray-300 px-2.5 py-1.5 rounded-lg transition-colors"
                            ${producto.cantidad >= producto.existencia ? 'disabled class="opacity-50 cursor-not-allowed"' : ''}>
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
    
    document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
    document.getElementById('total').textContent = '$' + subtotal.toFixed(2);
    document.getElementById('totalForm').value = subtotal;
    document.getElementById('detallesForm').value = JSON.stringify(carrito);
}

function seleccionarCliente(cliente) {
    clienteSeleccionado = cliente;
    document.getElementById('idClienteSeleccionado').value = cliente.idCliente;
    document.getElementById('idClienteForm').value = cliente.idCliente;
    document.getElementById('nombreClienteSeleccionado').textContent = 
        cliente.nombre + ' ' + (cliente.apellidos || '');
    document.getElementById('clienteSeleccionado').classList.remove('hidden');
    document.getElementById('buscarCliente').value = '';
    document.getElementById('resultadosClientes').innerHTML = '';
}

function quitarCliente() {
    clienteSeleccionado = null;
    document.getElementById('idClienteSeleccionado').value = '';
    document.getElementById('idClienteForm').value = '';
    document.getElementById('clienteSeleccionado').classList.add('hidden');
}

document.getElementById('formVenta').addEventListener('submit', function(e) {
    if (carrito.length === 0) {
        e.preventDefault();
        mostrarNotificacion('Debe agregar al menos un producto al carrito', 'error');
        return;
    }
    
    if (!document.getElementById('idFormaPago').value) {
        e.preventDefault();
        mostrarNotificacion('Debe seleccionar una forma de pago', 'error');
        document.getElementById('idFormaPago').focus();
        return;
    }
});

// Enfocar el campo de búsqueda al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('buscarProducto').focus();
    document.getElementById('mensajeBusqueda').classList.remove('hidden');
});
</script>

