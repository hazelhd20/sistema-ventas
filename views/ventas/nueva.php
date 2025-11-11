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
            <h3 class="text-xl font-bold mb-4">Buscar Producto</h3>
            
            <div class="mb-4">
                <input type="text" id="buscarProducto" placeholder="Buscar por nombre o código de barras..." 
                       class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                       onkeyup="buscarProductos()">
            </div>
            
            <div id="resultadosProductos" class="max-h-96 overflow-y-auto">
                <!-- Los resultados se mostrarán aquí -->
            </div>
        </div>
        
        <!-- Búsqueda de cliente -->
        <div class="bg-white rounded-lg shadow p-6 mt-6">
            <h3 class="text-xl font-bold mb-4">Cliente (Opcional)</h3>
            
            <div class="mb-4">
                <input type="text" id="buscarCliente" placeholder="Buscar cliente por nombre o teléfono..." 
                       class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                       onkeyup="buscarClientes()">
            </div>
            
            <div id="resultadosClientes" class="max-h-40 overflow-y-auto">
                <!-- Los resultados se mostrarán aquí -->
            </div>
            
            <input type="hidden" id="idClienteSeleccionado" name="idCliente">
            <div id="clienteSeleccionado" class="mt-4 p-3 bg-blue-50 rounded hidden">
                <span id="nombreClienteSeleccionado"></span>
                <button type="button" onclick="quitarCliente()" class="ml-2 text-red-600">
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
                <div id="listaProductos" class="mb-4 max-h-64 overflow-y-auto">
                    <!-- Los productos se mostrarán aquí -->
                </div>
                
                <div class="border-t pt-4">
                    <div class="flex justify-between mb-2">
                        <span class="font-semibold">Subtotal:</span>
                        <span id="subtotal">$0.00</span>
                    </div>
                    <div class="flex justify-between mb-4 text-xl font-bold">
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
                                    class="w-full px-3 py-2 border rounded">
                                <option value="">Seleccione...</option>
                                <?php foreach ($formasPago as $fp): ?>
                                    <option value="<?php echo $fp['idFormaPago']; ?>"><?php echo htmlspecialchars($fp['nombre']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Observaciones</label>
                            <textarea name="observaciones" id="observaciones" rows="2" 
                                      class="w-full px-3 py-2 border rounded"></textarea>
                        </div>
                        
                        <button type="submit" id="btnFinalizar" 
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded">
                            <i class="fas fa-check"></i> Finalizar Venta
                        </button>
                    </form>
                    
                    <a href="<?php echo BASE_URL; ?>ventas" 
                       class="block mt-3 text-center text-gray-600 hover:text-gray-800">
                        Cancelar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let carrito = [];
let clienteSeleccionado = null;

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
                    <div class="p-3 border rounded hover:bg-gray-50 cursor-pointer" 
                         onclick="agregarAlCarrito(${JSON.stringify(producto).replace(/"/g, '&quot;')})">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-semibold">${producto.nombre}</p>
                                <p class="text-sm text-gray-600">
                                    Stock: ${producto.existencia} ${producto.medida_abrev} | 
                                    Precio: $${parseFloat(producto.precio).toFixed(2)}
                                </p>
                            </div>
                            <i class="fas fa-plus-circle text-blue-600 text-xl"></i>
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

function agregarAlCarrito(producto) {
    if (producto.existencia <= 0) {
        alert('No hay stock disponible de este producto');
        return;
    }
    
    const existente = carrito.find(p => p.codProducto === producto.codProducto);
    
    if (existente) {
        if (existente.cantidad >= producto.existencia) {
            alert('No hay suficiente stock disponible');
            return;
        }
        existente.cantidad++;
        existente.subtotal = existente.cantidad * existente.precio;
    } else {
        carrito.push({
            codProducto: producto.codProducto,
            nombre: producto.nombre,
            medida_abrev: producto.medida_abrev,
            precio: parseFloat(producto.precio),
            cantidad: 1,
            subtotal: parseFloat(producto.precio),
            existencia: producto.existencia
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
    
    if (nuevaCantidad > producto.existencia) {
        alert('No hay suficiente stock disponible');
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
        html += `
            <div class="flex justify-between items-center p-2 border-b">
                <div class="flex-1">
                    <p class="font-semibold text-sm">${producto.nombre}</p>
                    <p class="text-xs text-gray-600">$${producto.precio.toFixed(2)} x ${producto.cantidad} ${producto.medida_abrev}</p>
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
        alert('Debe agregar al menos un producto al carrito');
        return;
    }
    
    if (!document.getElementById('idFormaPago').value) {
        e.preventDefault();
        alert('Debe seleccionar una forma de pago');
        return;
    }
});
</script>

