<?php
$pageTitle = "Nueva Compra";
?>

<div class="max-w-7xl mx-auto space-y-6">
    <div class="card flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <p class="text-sm text-gray-500 uppercase tracking-wide">Compras</p>
            <h2 class="text-2xl font-semibold text-gray-800">Nueva Compra</h2>
            <p class="text-gray-600">Registre una nueva compra a proveedor</p>
        </div>
        <span class="pill bg-blue-pastel/70 text-gray-800">
            <i data-lucide="keyboard" class="h-4 w-4 mr-1"></i> Enter para agregar productos
        </span>
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
                                <option value="<?php echo $proveedor['idProveedor']; ?>">
                                    <?php echo htmlspecialchars($proveedor['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Observaciones</label>
                        <textarea name="observaciones" id="observaciones" rows="3" class="input-modern"></textarea>
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
                </div>
            </div>
        </div>
    </form>
</div>
