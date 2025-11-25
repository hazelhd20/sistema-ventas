<?php
$pageTitle = "Nueva Compra";

$oldDetallesCompra = [];
if (isset($_SESSION['old']['detalles'])) {
    $decoded = json_decode($_SESSION['old']['detalles'], true);
    if (is_array($decoded)) {
        $oldDetallesCompra = $decoded;
    }
    unset($_SESSION['old']['detalles']);
}
$oldTotalCompra = $_SESSION['old']['total'] ?? null;
if (isset($_SESSION['old']['total'])) {
    unset($_SESSION['old']['total']);
}
$oldIdProveedor = old('idProveedor', '');
$oldObservaciones = old('observaciones', '');
?>

<div class="max-w-7xl mx-auto space-y-6">
    <div class="card flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <p class="text-sm text-gray-500 uppercase tracking-wide">Compras</p>
            <h2 class="text-2xl font-semibold text-gray-800">Nueva Compra</h2>
            <p class="text-gray-600">Registre una nueva compra a proveedor</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="pill bg-blue-pastel/70 text-gray-800">
                <i data-lucide="keyboard" class="h-4 w-4 mr-1"></i> Enter para agregar productos
            </span>
            <a href="<?php echo BASE_URL; ?>compras/index" class="btn-primary">
                <i data-lucide="history" class="h-4 w-4 mr-1"></i> Ver historial
            </a>
        </div>
    </div>

    <form id="formCompra" method="POST" action="<?php echo BASE_URL; ?>compras/create">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Panel izquierdo: Informacion y busqueda -->
            <div class="lg:col-span-2 space-y-6">
                <div class="card">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informacion de la Compra</h3>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Proveedor *</label>
                        <select name="idProveedor" id="idProveedor" required class="input-modern">
                            <option value="">Seleccione un proveedor...</option>
                            <?php foreach ($proveedores as $proveedor): ?>
                                <option value="<?php echo $proveedor['idProveedor']; ?>" <?php echo ($oldIdProveedor == $proveedor['idProveedor']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($proveedor['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Observaciones</label>
                        <textarea name="observaciones" id="observaciones" rows="3" class="input-modern"><?php echo htmlspecialchars($oldObservaciones); ?></textarea>
                    </div>
                </div>
                
                <div class="card">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Agregar Productos</h3>
                        <span class="text-xs text-gray-600 bg-gray-100 px-2 py-1 rounded">
                            <i data-lucide="keyboard" class="h-4 w-4 mr-1 inline"></i> Enter para agregar
                        </span>
                    </div>
                    
                    <div class="mb-4 relative">
                        <input type="text" id="buscarProducto" placeholder="Buscar producto por nombre o codigo (Enter para agregar)..." 
                               class="input-modern text-lg pl-4 pr-10"
                               onkeyup="buscarProductos()"
                               onkeydown="manejarTeclado(event)"
                               autofocus>
                        <i data-lucide="search" class="absolute right-3 top-3.5 text-gray-400 h-5 w-5"></i>
                    </div>
                    
                    <div id="resultadosProductos" class="max-h-64 overflow-y-auto overflow-x-hidden mb-4 space-y-2">
                        <!-- Los resultados se mostraran aqui -->
                    </div>
                    
                    <div id="mensajeBusqueda" class="hidden text-center text-gray-500 py-4">
                        <i data-lucide="search" class="h-6 w-6 mx-auto mb-2"></i>
                        <p>Escribe al menos 2 caracteres para buscar productos</p>
                    </div>
                </div>
            </div>
            
            <!-- Panel derecho: Resumen -->
            <div class="lg:col-span-1">
                <div class="card sticky top-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Resumen de Compra</h3>
                    
                    <div id="carritoVacio" class="text-center text-gray-500 py-8">
                        <i data-lucide="shopping-bag" class="h-10 w-10 mx-auto mb-2"></i>
                        <p>No hay productos agregados</p>
                    </div>
                    
                    <div id="carritoProductos" class="hidden">
                        <div id="listaProductos" class="mb-4 max-h-64 overflow-y-auto space-y-2">
                            <!-- Los productos se mostraran aqui -->
                        </div>
                        
                        <div class="border-t border-gray-200 pt-4 space-y-2">
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Subtotal</span>
                                <span id="subtotalCompra">$0.00</span>
                            </div>
                            <div class="flex justify-between text-lg font-semibold text-gray-900">
                                <span>Total</span>
                                <span id="totalCompra">$0.00</span>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-primary w-full mt-4">
                            <i data-lucide="check" class="h-5 w-5 mr-2"></i> Registrar Compra
                        </button>
                    </div>
                    <input type="hidden" name="detalles" id="detallesCompra">
                    <input type="hidden" name="total" id="totalCompraInput">
                </div>
            </div>
        </div>
    </form>
</div>

<script>
(function() {
    const baseUrl = '<?php echo BASE_URL; ?>';
    const input = document.getElementById('buscarProducto');
    const resultados = document.getElementById('resultadosProductos');
    const mensaje = document.getElementById('mensajeBusqueda');
    const lista = document.getElementById('listaProductos');
    const carritoVacio = document.getElementById('carritoVacio');
    const carritoProductos = document.getElementById('carritoProductos');
    const subtotalEl = document.getElementById('subtotalCompra');
    const totalEl = document.getElementById('totalCompra');
    const detallesInput = document.getElementById('detallesCompra');
    const totalInput = document.getElementById('totalCompraInput');
    const observacionesInput = document.getElementById('observaciones');
    const draftKey = 'compraDraft';
    let searchTimeout;
    let ultimoResultado = [];
    let carrito = [];
    const oldCarrito = <?php echo json_encode($oldDetallesCompra); ?>;
    const oldTotal = <?php echo json_encode($oldTotalCompra); ?>;

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
        const compra = Number(p.precioCompra ?? p.precio ?? 0);
        const venta = Number(p.precioVenta ?? p.precio ?? 0);
        const precioCompra = (typeof formatearMoneda === 'function')
            ? formatearMoneda(compra)
            : `$${compra.toFixed(2)}`;
        const precioVenta = (typeof formatearMoneda === 'function')
            ? formatearMoneda(venta)
            : `$${venta.toFixed(2)}`;
        const productoJson = JSON.stringify(p).replace(/"/g, '&quot;');
        const stockInfo = `${disponible} ${medida}`;
        const categoria = escapeHtml(p.categoria_nombre ?? '');
        const codigo = p.codigoBarras ? `<span class="text-xs text-gray-500 block">Codigo: ${escapeHtml(p.codigoBarras)}</span>` : '';
        const ventaInfo = venta > 0 ? `<div class="text-xs text-gray-500">Venta ref.: ${precioVenta}</div>` : '';

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
                    <div class="text-sm font-bold text-blue-600">${precioCompra}</div>
                    ${ventaInfo}
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
            const precio = Number(item.precioCompra ?? item.precio ?? 0);
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
                                   onchange="actualizarCantidadCompra(${idx}, this.value)">
                        </div>
                        <div class="flex items-center gap-2">
                            <label class="text-xs text-gray-500">Precio</label>
                            <input type="number" min="0" step="0.01" class="input-modern w-24 text-right" value="${precio.toFixed(2)}"
                                   onchange="actualizarPrecioCompra(${idx}, this.value)">
                        </div>
                        <div class="text-xs text-gray-500">Linea: ${typeof formatearMoneda === 'function' ? formatearMoneda(totalLinea) : '$' + totalLinea.toFixed(2)}</div>
                        <button type="button" class="btn-ghost px-2 py-1 text-red-700 text-xs"
                                onclick="eliminarProductoCompra(${idx})">
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
        const subtotal = carrito.reduce((acc, item) => acc + Number(item.precioCompra ?? item.precio ?? 0) * Number(item.cantidad ?? 1), 0);
        if (subtotalEl) subtotalEl.textContent = typeof formatearMoneda === 'function' ? formatearMoneda(subtotal) : `$${subtotal.toFixed(2)}`;
        if (totalEl) totalEl.textContent = typeof formatearMoneda === 'function' ? formatearMoneda(subtotal) : `$${subtotal.toFixed(2)}`;
        if (detallesInput) detallesInput.value = JSON.stringify(carrito.map(item => ({
            codProducto: item.codProducto,
            cantidad: Number(item.cantidad ?? 1),
            precioCompra: Number(item.precioCompra ?? item.precio ?? 0),
            subtotal: Number(item.precioCompra ?? item.precio ?? 0) * Number(item.cantidad ?? 1),
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
                precioCompra: Number(producto.precioCompra ?? producto.precio ?? 0),
                cantidad: 1,
                stock: producto.existencia ?? ''
            });
        }
        renderCarrito();
    }

    function actualizarCantidadCompra(idx, valor) {
        const cantidad = Math.max(1, Number(valor) || 1);
        if (carrito[idx]) {
            carrito[idx].cantidad = cantidad;
            renderCarrito();
        }
    }

    function actualizarPrecioCompra(idx, valor) {
        const precio = Math.max(0, Number(valor) || 0);
        if (carrito[idx]) {
            carrito[idx].precioCompra = precio;
            renderCarrito();
        }
    }

    function eliminarProductoCompra(idx) {
        carrito.splice(idx, 1);
        renderCarrito();
        guardarBorrador();
    }

    function guardarBorrador() {
        try {
            const data = {
                carrito,
                idProveedor: document.getElementById('idProveedor') ? document.getElementById('idProveedor').value : '',
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
                precioCompra: Number(item.precioCompra ?? item.precio ?? 0),
                cantidad: Number(item.cantidad ?? 1),
                stock: item.stock ?? ''
            }));
            renderCarrito();
        }

        const borrador = (!oldCarrito || oldCarrito.length === 0) ? cargarBorrador() : null;
        if (borrador && Array.isArray(borrador.carrito) && carrito.length === 0) {
            carrito = borrador.carrito.map(item => ({
                codProducto: item.codProducto,
                nombre: item.nombre || '',
                categoria: item.categoria || '',
                medida: item.medida || '',
                precioCompra: Number(item.precioCompra ?? item.precio ?? 0),
                cantidad: Number(item.cantidad ?? 1),
                stock: item.stock ?? ''
            }));
            renderCarrito();
        }

        if (borrador && borrador.idProveedor && document.getElementById('idProveedor') && document.getElementById('idProveedor').value === '') {
            document.getElementById('idProveedor').value = borrador.idProveedor;
        }

        if (borrador && borrador.observaciones && observacionesInput && observacionesInput.value === '') {
            observacionesInput.value = borrador.observaciones;
        }

        guardarBorrador();
    }

    window.buscarProductos = buscarProductos;
    window.manejarTeclado = manejarTeclado;
    window.agregarProductoDesdeBusqueda = agregarProductoDesdeBusqueda;
    window.actualizarCantidadCompra = actualizarCantidadCompra;
    window.actualizarPrecioCompra = actualizarPrecioCompra;
    window.eliminarProductoCompra = eliminarProductoCompra;

    if (input) {
        input.addEventListener('input', buscarProductos);
        if (input.value.trim().length >= 2) {
            buscarProductos();
        } else {
            mostrarMensajeInicial();
        }
    }

    const form = document.getElementById('formCompra');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (carrito.length === 0) {
                e.preventDefault();
                alert('Agrega al menos un producto.');
                return;
            }
            actualizarTotales();
        });
    }

    const proveedorSelect = document.getElementById('idProveedor');
    if (proveedorSelect) {
        proveedorSelect.addEventListener('change', guardarBorrador);
    }
    if (observacionesInput) {
        observacionesInput.addEventListener('input', guardarBorrador);
    }

    // Limpiar borrador si el servidor lo marcó (éxito previo)
    <?php if (!empty($_SESSION['compra_draft_clear'])): ?>
    try { localStorage.removeItem('compraDraft'); } catch (e) {}
    <?php unset($_SESSION['compra_draft_clear']); endif; ?>

    restaurarEstado();
})();
</script>
