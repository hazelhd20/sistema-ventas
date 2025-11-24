<?php
$pageTitle = "Nueva Venta";

$oldDetallesVenta = [];
if (isset($_SESSION['old']['detalles'])) {
    $decoded = json_decode($_SESSION['old']['detalles'], true);
    if (is_array($decoded)) {
        $oldDetallesVenta = $decoded;
    }
    unset($_SESSION['old']['detalles']);
}
$oldTotalVenta = $_SESSION['old']['total'] ?? null;
if (isset($_SESSION['old']['total'])) {
    unset($_SESSION['old']['total']);
}
$oldIdCliente = old('idCliente', '');
$oldClienteNombre = old('clienteNombre', '');
$oldIdFormaPago = old('idFormaPago', '');
$oldObservaciones = old('observaciones', '');
?>

<form id="formVenta" method="POST" action="<?php echo BASE_URL; ?>ventas/create" class="max-w-7xl mx-auto space-y-6">
    <div class="card flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <p class="text-sm text-gray-500 uppercase tracking-wide">Ventas</p>
            <h2 class="text-2xl font-semibold text-gray-800">Nueva Venta</h2>
            <p class="text-gray-600">Registre una nueva venta</p>
        </div>
        <span class="pill bg-blue-pastel/70 text-gray-800">
            <i data-lucide="keyboard" class="h-4 w-4 mr-1"></i> Presiona Enter para agregar
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Panel izquierdo: Busqueda de productos y cliente -->
        <div class="lg:col-span-2 space-y-6">
            <div class="card">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Buscar producto</h3>
                </div>

                <div class="mb-4 relative">
                    <input type="text" id="buscarProducto" placeholder="Buscar por nombre o codigo de barras (Enter para agregar)..."
                           class="input-modern text-lg pl-4 pr-10"
                           onkeyup="buscarProductos()"
                           onkeydown="manejarTeclado(event)"
                           autofocus>
                    <i data-lucide="search" class="absolute right-3 top-3.5 text-gray-400 h-5 w-5"></i>
                </div>

                <div id="resultadosProductos" class="max-h-96 overflow-y-auto overflow-x-hidden space-y-2">
                    <!-- Los resultados se mostraran aqui -->
                </div>

                <div id="mensajeBusqueda" class="hidden text-center text-gray-500 py-4">
                    <i data-lucide="search" class="h-6 w-6 mx-auto mb-2"></i>
                    <p>Escribe al menos 2 caracteres para buscar productos</p>
                </div>
            </div>

            <div class="card">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Cliente (Opcional)</h3>

                <div class="mb-4 relative">
                    <input type="text" id="buscarCliente" placeholder="Buscar cliente por nombre o telefono..."
                           class="input-modern pl-4 pr-10"
                           onkeyup="buscarClientes()">
                    <i data-lucide="user" class="absolute right-3 top-3 text-gray-400 h-5 w-5"></i>
                </div>

                <div id="resultadosClientes" class="max-h-40 overflow-y-auto">
                    <!-- Los resultados se mostraran aqui -->
                </div>

                <input type="hidden" id="idClienteSeleccionado" name="idCliente" value="<?php echo htmlspecialchars($oldIdCliente); ?>">
                <input type="hidden" id="clienteNombreHidden" name="clienteNombre" value="<?php echo htmlspecialchars($oldClienteNombre); ?>">
                <div id="clienteSeleccionado" class="mt-4 p-3 bg-blue-pastel/50 rounded-lg border border-blue-200 hidden flex items-center justify-between">
                    <div class="flex items-center">
                        <i data-lucide="check-circle" class="h-4 w-4 text-blue-700 mr-2"></i>
                        <span id="nombreClienteSeleccionado" class="font-semibold text-blue-800"></span>
                    </div>
                    <button type="button" onclick="quitarCliente()" class="btn-ghost px-2 py-1 text-red-700">
                        <i data-lucide="x" class="h-4 w-4"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Panel derecho: Carrito de venta -->
        <div class="lg:col-span-1">
            <div class="card sticky top-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Carrito de Venta</h3>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Forma de pago *</label>
                    <select name="idFormaPago" id="idFormaPago" class="input-modern" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($formasPago as $fp): ?>
                            <option value="<?php echo $fp['idFormaPago']; ?>" <?php echo ($oldIdFormaPago == $fp['idFormaPago']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($fp['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                    <textarea name="observaciones" id="observaciones" rows="2" class="input-modern"><?php echo htmlspecialchars($oldObservaciones); ?></textarea>
                </div>

                <div id="carritoVacio" class="text-center text-gray-500 py-8">
                    <i data-lucide="shopping-cart" class="h-10 w-10 mx-auto mb-2"></i>
                    <p>El carrito esta vacio</p>
                </div>

                <div id="carritoProductos" class="hidden">
                    <div id="listaProductos" class="mb-4 max-h-64 overflow-y-auto space-y-2">
                        <!-- Los productos se mostraran aqui -->
                    </div>

                    <div class="border-t border-gray-200 pt-4 space-y-2">
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Subtotal</span>
                            <span id="subtotalVenta">$0.00</span>
                        </div>
                        <div class="flex justify-between text-lg font-semibold text-gray-900">
                            <span>Total</span>
                            <span id="totalVenta">$0.00</span>
                        </div>
                    </div>

                    <button type="button" onclick="procesarVenta()" class="btn-primary w-full mt-4">
                        <i data-lucide="check" class="h-5 w-5 mr-2"></i> Procesar Venta
                    </button>
                </div>
                <input type="hidden" name="detalles" id="detallesVenta">
                <input type="hidden" name="total" id="totalVentaInput">
            </div>
        </div>
    </div>
</form>

<script>
(function() {
    const baseUrl = '<?php echo BASE_URL; ?>';
    const input = document.getElementById('buscarProducto');
    const resultados = document.getElementById('resultadosProductos');
    const mensaje = document.getElementById('mensajeBusqueda');
    const lista = document.getElementById('listaProductos');
    const carritoVacio = document.getElementById('carritoVacio');
    const carritoProductos = document.getElementById('carritoProductos');
    const subtotalEl = document.getElementById('subtotalVenta');
    const totalEl = document.getElementById('totalVenta');
    const detallesInput = document.getElementById('detallesVenta');
    const totalInput = document.getElementById('totalVentaInput');
    const idFormaPago = document.getElementById('idFormaPago');
    const inputCliente = document.getElementById('buscarCliente');
    const resultadosClientes = document.getElementById('resultadosClientes');
    const idClienteSeleccionado = document.getElementById('idClienteSeleccionado');
    const clienteNombreHidden = document.getElementById('clienteNombreHidden');
    const clienteSeleccionado = document.getElementById('clienteSeleccionado');
    const nombreClienteSeleccionado = document.getElementById('nombreClienteSeleccionado');
    const observacionesInput = document.getElementById('observaciones');
    const draftKey = 'ventaDraft';
    let searchTimeout;
    let ultimoResultado = [];
    let carrito = [];
    let clientes = [];
    let clientesTimeout;
    const oldCarrito = <?php echo json_encode($oldDetallesVenta); ?>;
    const oldTotal = <?php echo json_encode($oldTotalVenta); ?>;
    const oldIdCliente = <?php echo json_encode($oldIdCliente); ?>;
    const oldClienteNombre = <?php echo json_encode($oldClienteNombre); ?>;

    const emptyState = `
        <div class="text-center text-gray-500 py-4">
            <i data-lucide="inbox" class="h-6 w-6 mx-auto mb-2"></i>
            <p>No se encontraron productos</p>
        </div>`;

    const loadingState = `
        <div class="text-center text-gray-500 py-4">
            <i data-lucide="loader-2" class="h-5 w-5 inline animate-spin mr-2"></i>
            Buscando...
        </div>`;

    const escapeHtml = (str) => {
        if (str === null || str === undefined) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    };

    function renderProducto(p) {
        const disponible = Number(p.existencia ?? 0);
        const medida = escapeHtml(p.medida_abrev ?? '');
        const precio = (typeof formatearMoneda === 'function')
            ? formatearMoneda(Number(p.precio ?? 0))
            : `$${Number(p.precio ?? 0).toFixed(2)}`;
        const productoJson = JSON.stringify(p).replace(/"/g, '&quot;');
        const stockInfo = `${disponible} ${medida}`;
        const categoria = escapeHtml(p.categoria_nombre ?? '');
        const codigo = p.codigoBarras ? `<span class="text-xs text-gray-500 block">Codigo: ${escapeHtml(p.codigoBarras)}</span>` : '';

        return `
            <div class="border border-gray-200 rounded-lg p-3 flex items-center justify-between hover:border-blue-200 hover:bg-blue-50 transition">
                <div>
                    <div class="flex items-center gap-2 text-sm font-semibold text-gray-900">
                        <i data-lucide="package" class="h-4 w-4 text-gray-400"></i>
                        ${escapeHtml(p.nombre ?? '')}
                    </div>
                    <div class="text-xs text-gray-500 mt-1">${categoria}</div>
                    ${codigo}
                    <div class="text-xs text-gray-500 mt-1">Stock: ${escapeHtml(stockInfo)}</div>
                </div>
                <div class="text-right space-y-2">
                    <div class="text-sm font-bold text-blue-600">${precio}</div>
                    <button type="button" class="btn-primary px-3 py-1 text-sm"
                            onclick="agregarProductoDesdeBusqueda(${productoJson})">
                        <i data-lucide="plus" class="h-4 w-4 mr-1"></i> Agregar
                    </button>
                </div>
            </div>
        `;
    }

    function mostrarMensajeInicial() {
        if (!mensaje) return;
        mensaje.classList.remove('hidden');
        if (resultados) resultados.innerHTML = '';
    }

    function ocultarMensaje() {
        if (mensaje) mensaje.classList.add('hidden');
    }

    function buscarProductos() {
        if (!input || !resultados) return;
        const term = input.value.trim();
        clearTimeout(searchTimeout);

        if (term.length === 0) {
            ocultarMensaje();
            resultados.innerHTML = '';
            ultimoResultado = [];
            return;
        }

        if (term.length < 2) {
            mostrarMensajeInicial();
            ultimoResultado = [];
            return;
        }

        resultados.innerHTML = loadingState;
        searchTimeout = setTimeout(() => {
            fetch(`${baseUrl}productos/search?term=${encodeURIComponent(term)}&soloActivos=1`)
                .then(res => res.ok ? res.json() : Promise.reject())
                .then(data => {
                    ultimoResultado = Array.isArray(data) ? data : [];
                    if (ultimoResultado.length === 0) {
                        resultados.innerHTML = emptyState;
                    } else {
                        resultados.innerHTML = ultimoResultado.map(renderProducto).join('');
                    }
                    ocultarMensaje();
                    if (window.lucide) lucide.createIcons();
                })
                .catch(() => {
                    resultados.innerHTML = `
                        <div class="text-center text-red-600 py-4">
                            <i data-lucide="alert-triangle" class="h-5 w-5 inline mr-2"></i>
                            No se pudo buscar productos
                        </div>`;
                    if (window.lucide) lucide.createIcons();
                });
        }, 250);
    }

    function manejarTeclado(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            if (ultimoResultado.length > 0) {
                if (typeof window.agregarProductoDesdeBusqueda === 'function') {
                    window.agregarProductoDesdeBusqueda(ultimoResultado[0]);
                }
            }
        }
    }

    function renderCarrito() {
        if (!lista || !carritoVacio || !carritoProductos) return;

        if (carrito.length === 0) {
            carritoVacio.classList.remove('hidden');
            carritoProductos.classList.add('hidden');
            lista.innerHTML = '';
            actualizarTotales();
            return;
        }

        carritoVacio.classList.add('hidden');
        carritoProductos.classList.remove('hidden');

        lista.innerHTML = carrito.map((item, idx) => {
            const precio = Number(item.precio ?? 0);
            const cantidad = Number(item.cantidad ?? 1);
            const totalLinea = precio * cantidad;
            return `
                <div class="border border-gray-200 rounded-lg p-3 flex items-center justify-between">
                    <div class="space-y-1">
                        <div class="text-sm font-semibold text-gray-900">${escapeHtml(item.nombre)}</div>
                        <div class="text-xs text-gray-500">${escapeHtml(item.categoria || '')}</div>
                        <div class="text-xs text-gray-500">Stock: ${escapeHtml(item.stock ?? '')} ${escapeHtml(item.medida || '')}</div>
                    </div>
                    <div class="text-right space-y-1">
                        <div class="flex items-center gap-2">
                            <label class="text-xs text-gray-500">Cant.</label>
                            <input type="number" min="1" class="input-modern w-20 text-right" value="${cantidad}"
                                   onchange="actualizarCantidadVenta(${idx}, this.value)">
                        </div>
                        <div class="text-sm font-bold text-blue-600">${formatearMoneda ? formatearMoneda(precio) : '$' + precio.toFixed(2)}</div>
                        <div class="text-xs text-gray-500">Linea: ${formatearMoneda ? formatearMoneda(totalLinea) : '$' + totalLinea.toFixed(2)}</div>
                        <button type="button" class="btn-ghost px-2 py-1 text-red-700 text-xs"
                                onclick="eliminarProductoVenta(${idx})">
                            <i data-lucide="trash" class="h-4 w-4 mr-1"></i> Quitar
                        </button>
                    </div>
                </div>
            `;
        }).join('');

        if (window.lucide) lucide.createIcons();
        actualizarTotales();
    }

    function actualizarTotales() {
        const subtotal = carrito.reduce((acc, item) => acc + Number(item.precio ?? 0) * Number(item.cantidad ?? 1), 0);
        if (subtotalEl) subtotalEl.textContent = formatearMoneda ? formatearMoneda(subtotal) : `$${subtotal.toFixed(2)}`;
        if (totalEl) totalEl.textContent = formatearMoneda ? formatearMoneda(subtotal) : `$${subtotal.toFixed(2)}`;
        if (detallesInput) detallesInput.value = JSON.stringify(carrito.map(item => ({
            codProducto: item.codProducto,
            cantidad: Number(item.cantidad ?? 1),
            precio: Number(item.precio ?? 0),
            subtotal: Number(item.precio ?? 0) * Number(item.cantidad ?? 1),
            nombre: item.nombre || '',
            categoria: item.categoria || '',
            medida: item.medida || '',
            stock: item.stock ?? ''
        })));
        if (totalInput) totalInput.value = subtotal.toFixed(2);
        guardarBorrador();
    }

    function agregarProductoDesdeBusqueda(producto) {
        if (!producto || !producto.codProducto) return;
        const existente = carrito.find(p => Number(p.codProducto) === Number(producto.codProducto));
        if (existente) {
            existente.cantidad = Number(existente.cantidad ?? 1) + 1;
        } else {
            carrito.push({
                codProducto: producto.codProducto,
                nombre: producto.nombre || '',
                categoria: producto.categoria_nombre || '',
                medida: producto.medida_abrev || '',
                precio: Number(producto.precio ?? 0),
                cantidad: 1,
                stock: producto.existencia ?? ''
            });
        }
        renderCarrito();
        guardarBorrador();
    }

    function actualizarCantidadVenta(idx, valor) {
        const cantidad = Math.max(1, Number(valor) || 1);
        if (carrito[idx]) {
            carrito[idx].cantidad = cantidad;
            renderCarrito();
            guardarBorrador();
        }
    }

    function eliminarProductoVenta(idx) {
        carrito.splice(idx, 1);
        renderCarrito();
        guardarBorrador();
    }

    function procesarVenta() {
        if (carrito.length === 0) {
            alert('Agrega al menos un producto.');
            return;
        }
        if (!idFormaPago || !idFormaPago.value) {
            alert('Selecciona una forma de pago.');
            return;
        }
        actualizarTotales();
        document.getElementById('formVenta').submit();
    }

    // Busqueda y seleccion de clientes
    const emptyClientes = `
        <div class="text-center text-gray-500 py-3 text-sm">
            <i data-lucide="user-x" class="h-5 w-5 mx-auto mb-2"></i>
            <p>Sin resultados</p>
        </div>`;

    const loadingClientes = `
        <div class="text-center text-gray-500 py-3 text-sm">
            <i data-lucide="loader-2" class="h-5 w-5 inline animate-spin mr-2"></i>
            Buscando...
        </div>`;

    function renderCliente(c) {
        const nombre = escapeHtml(`${c.nombre ?? ''} ${c.apellidos ?? ''}`.trim());
        const telefono = escapeHtml(c.telefono ?? '');
        const email = escapeHtml(c.email ?? '');
        const clienteJson = JSON.stringify(c).replace(/"/g, '&quot;');
        return `
            <button type="button" class="w-full text-left px-3 py-2 rounded-lg hover:bg-blue-50 border border-gray-100 flex items-center justify-between"
                    onclick="seleccionarCliente(${clienteJson})">
                <div>
                    <div class="text-sm font-semibold text-gray-800">${nombre || 'Sin nombre'}</div>
                    <div class="text-xs text-gray-500">${telefono}${telefono && email ? ' · ' : ''}${email}</div>
                </div>
                <i data-lucide="chevron-right" class="h-4 w-4 text-gray-400"></i>
            </button>
        `;
    }

    function buscarClientes() {
        if (!inputCliente || !resultadosClientes) return;
        const term = inputCliente.value.trim();
        clearTimeout(clientesTimeout);

        if (term.length < 2) {
            resultadosClientes.innerHTML = '';
            return;
        }

        resultadosClientes.innerHTML = loadingClientes;
        clientesTimeout = setTimeout(() => {
            fetch(`${baseUrl}clientes/search?term=${encodeURIComponent(term)}`)
                .then(res => res.ok ? res.json() : Promise.reject())
                .then(data => {
                    clientes = Array.isArray(data) ? data : [];
                    if (clientes.length === 0) {
                        resultadosClientes.innerHTML = emptyClientes;
                    } else {
                        resultadosClientes.innerHTML = clientes.map(renderCliente).join('');
                    }
                    if (window.lucide) lucide.createIcons();
                })
                .catch(() => {
                    resultadosClientes.innerHTML = `
                        <div class="text-center text-red-600 py-3 text-sm">
                            <i data-lucide="alert-triangle" class="h-5 w-5 inline mr-2"></i>
                            No se pudo buscar clientes
                        </div>`;
                    if (window.lucide) lucide.createIcons();
                });
        }, 250);
    }

    function seleccionarCliente(cliente) {
        if (!clienteSeleccionado || !idClienteSeleccionado || !nombreClienteSeleccionado) return;
        idClienteSeleccionado.value = cliente.idCliente || '';
        nombreClienteSeleccionado.textContent = `${cliente.nombre ?? ''} ${cliente.apellidos ?? ''}`.trim();
        if (clienteNombreHidden) {
            clienteNombreHidden.value = `${cliente.nombre ?? ''} ${cliente.apellidos ?? ''}`.trim();
        }
        clienteSeleccionado.classList.remove('hidden');
        resultadosClientes.innerHTML = '';
        if (inputCliente) inputCliente.value = '';
        guardarBorrador();
    }

    function quitarCliente() {
        if (idClienteSeleccionado) idClienteSeleccionado.value = '';
        if (nombreClienteSeleccionado) nombreClienteSeleccionado.textContent = '';
        if (clienteSeleccionado) clienteSeleccionado.classList.add('hidden');
        if (clienteNombreHidden) clienteNombreHidden.value = '';
        guardarBorrador();
    }

    function guardarBorrador() {
        try {
            const data = {
                carrito,
                idCliente: idClienteSeleccionado ? idClienteSeleccionado.value : '',
                clienteNombre: clienteNombreHidden ? clienteNombreHidden.value : '',
                idFormaPago: idFormaPago ? idFormaPago.value : '',
                observaciones: observacionesInput ? observacionesInput.value : '',
            };
            localStorage.setItem(draftKey, JSON.stringify(data));
        } catch (e) {
            // Ignorar errores de almacenamiento
        }
    }

    function cargarBorrador() {
        try {
            const raw = localStorage.getItem(draftKey);
            if (!raw) return null;
            return JSON.parse(raw);
        } catch (e) {
            return null;
        }
    }

    function restaurarEstado() {
        if (Array.isArray(oldCarrito) && oldCarrito.length > 0) {
            carrito = oldCarrito.map(item => ({
                codProducto: item.codProducto,
                nombre: item.nombre || '',
                categoria: item.categoria || '',
                medida: item.medida || '',
                precio: Number(item.precio ?? item.precioCompra ?? 0),
                cantidad: Number(item.cantidad ?? 1),
                stock: item.stock ?? ''
            }));
            renderCarrito();
        }

        const borrador = (!oldCarrito || oldCarrito.length === 0) ? cargarBorrador() : null;

        if ((!oldIdCliente || oldIdCliente === '') && borrador && borrador.idCliente) {
            idClienteSeleccionado.value = borrador.idCliente || '';
            if (clienteNombreHidden) clienteNombreHidden.value = borrador.clienteNombre || '';
            if (nombreClienteSeleccionado) nombreClienteSeleccionado.textContent = borrador.clienteNombre || 'Cliente seleccionado';
            if (clienteSeleccionado) clienteSeleccionado.classList.remove('hidden');
            if (inputCliente) inputCliente.value = '';
        } else if (oldIdCliente) {
            idClienteSeleccionado.value = oldIdCliente;
            if (clienteNombreHidden) clienteNombreHidden.value = oldClienteNombre || '';
            if (nombreClienteSeleccionado) nombreClienteSeleccionado.textContent = oldClienteNombre || 'Cliente seleccionado';
            if (clienteSeleccionado) clienteSeleccionado.classList.remove('hidden');
            if (inputCliente) inputCliente.value = '';
        }

        if (borrador && Array.isArray(borrador.carrito) && carrito.length === 0) {
            carrito = borrador.carrito.map(item => ({
                codProducto: item.codProducto,
                nombre: item.nombre || '',
                categoria: item.categoria || '',
                medida: item.medida || '',
                precio: Number(item.precio ?? item.precioCompra ?? 0),
                cantidad: Number(item.cantidad ?? 1),
                stock: item.stock ?? ''
            }));
            renderCarrito();
        }

        if (borrador && borrador.idFormaPago && (!idFormaPago.value || idFormaPago.value === '')) {
            idFormaPago.value = borrador.idFormaPago;
        }
        if (oldIdFormaPago) {
            idFormaPago.value = oldIdFormaPago;
        }

        if (borrador && borrador.observaciones && observacionesInput && observacionesInput.value === '') {
            observacionesInput.value = borrador.observaciones;
        }
        if (oldObservaciones && observacionesInput && observacionesInput.value === '') {
            observacionesInput.value = oldObservaciones;
        }

        guardarBorrador();
    }

    window.buscarProductos = buscarProductos;
    window.manejarTeclado = manejarTeclado;
    window.agregarProductoDesdeBusqueda = agregarProductoDesdeBusqueda;
    window.actualizarCantidadVenta = actualizarCantidadVenta;
    window.eliminarProductoVenta = eliminarProductoVenta;
    window.procesarVenta = procesarVenta;
    window.buscarClientes = buscarClientes;
    window.seleccionarCliente = seleccionarCliente;
    window.quitarCliente = quitarCliente;

    if (input) {
        input.addEventListener('input', buscarProductos);
        // Busqueda inicial si ya hay texto (por ejemplo, si se mantiene el estado)
        if (input.value.trim().length >= 2) {
            buscarProductos();
        } else {
            mostrarMensajeInicial();
        }
    }

    if (inputCliente) {
        inputCliente.addEventListener('input', buscarClientes);
    }

    if (idFormaPago) {
        idFormaPago.addEventListener('change', guardarBorrador);
    }
    if (observacionesInput) {
        observacionesInput.addEventListener('input', guardarBorrador);
    }

    // Limpiar borrador si el servidor solicitó limpieza (éxito previo)
    <?php if (!empty($_SESSION['venta_draft_clear'])): ?>
    try { localStorage.removeItem('ventaDraft'); } catch (e) {}
    <?php unset($_SESSION['venta_draft_clear']); endif; ?>

    restaurarEstado();
})();
</script>
