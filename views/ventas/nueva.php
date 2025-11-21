<?php
$pageTitle = "Nueva Venta";
?>

<div class="max-w-7xl mx-auto space-y-6">
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
                
                <input type="hidden" id="idClienteSeleccionado" name="idCliente">
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
            </div>
        </div>
    </div>
</div>
