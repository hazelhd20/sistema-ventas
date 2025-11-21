<?php
$pageTitle = "Dashboard";
$user = auth_user();
$today = date('d/m/Y');
?>

<div class="max-w-7xl mx-auto space-y-6">
    <div class="card hero-card p-6 md:p-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide">Bienvenido</p>
                <h2 class="text-2xl md:text-3xl font-semibold mt-1">Hola <?= e($user['name'] ?? ''); ?>, este es tu panel de hoy.</h2>
                <p class="text-sm mt-2 text-gray-700">Fecha: <?= $today ?> · <?= number_format($stats['productos']) ?> productos · <?= number_format($stats['ventas_hoy']) ?> ventas hoy</p>
                <div class="mt-3 flex flex-wrap gap-2">
                    <span class="pill bg-white/70">Stock bajo: <?= number_format($stats['stock_bajo']) ?></span>
                    <span class="pill bg-white/70">Compras: <?= number_format($stats['compras_hoy']) ?></span>
                    <span class="pill bg-white/70">Ventas $<?= number_format($stats['monto_hoy'], 2) ?></span>
                </div>
            </div>
            <div class="flex items-center gap-3 flex-wrap">
                <a href="<?= BASE_URL ?>ventas" class="btn-primary">
                    <i data-lucide="shopping-cart" class="h-4 w-4 mr-2"></i> Ir a ventas
                </a>
                <a href="<?= BASE_URL ?>productos" class="btn-ghost">
                    <i data-lucide="package" class="h-4 w-4 mr-2"></i> Ver productos
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="card stat-card">
            <div class="bg-blue-pastel p-3 rounded-lg">
                <i data-lucide="package" class="h-6 w-6 text-blue-700"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total productos</p>
                <p class="text-2xl font-semibold text-gray-800"><?= number_format($stats['productos']) ?></p>
            </div>
        </div>
        <div class="card stat-card">
            <div class="bg-pink-pastel p-3 rounded-lg">
                <i data-lucide="alert-triangle" class="h-6 w-6 text-pink-700"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Stock bajo</p>
                <p class="text-2xl font-semibold text-gray-800"><?= number_format($stats['stock_bajo']) ?></p>
            </div>
        </div>
        <div class="card stat-card">
            <div class="bg-green-pastel p-3 rounded-lg">
                <i data-lucide="credit-card" class="h-6 w-6 text-green-700"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Ventas hoy</p>
                <p class="text-2xl font-semibold text-gray-800"><?= number_format($stats['ventas_hoy']) ?></p>
                <p class="text-sm text-gray-500">$<?= number_format($stats['monto_hoy'], 2) ?></p>
            </div>
        </div>
        <div class="card stat-card">
            <div class="bg-peach-pastel p-3 rounded-lg">
                <i data-lucide="shopping-bag" class="h-6 w-6 text-orange-700"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Compras hoy</p>
                <p class="text-2xl font-semibold text-gray-800"><?= number_format($stats['compras_hoy']) ?></p>
                <p class="text-sm text-gray-500">$<?= number_format($stats['monto_compras_hoy'], 2) ?></p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Alertas y actividad</h3>
                <span class="pill">Hoy</span>
            </div>
            <?php if ($stats['stock_bajo'] > 0): ?>
                <div class="flex items-start bg-pink-pastel/50 border border-pink-200 rounded-lg p-3">
                    <i data-lucide="bell" class="h-5 w-5 text-pink-700 mr-2 mt-0.5"></i>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Tienes productos con stock bajo.</p>
                        <p class="text-sm text-gray-600">Revisa inventario para reponer antes de quedarte sin existencias.</p>
                    </div>
                </div>
            <?php else: ?>
                <div class="flex items-center text-sm text-gray-600">
                    <i data-lucide="check-circle" class="h-5 w-5 text-green-600 mr-2"></i>
                    Todo en orden, sin alertas de stock.
                </div>
            <?php endif; ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <a href="<?= BASE_URL ?>ventas" class="btn-ghost justify-between">
                    <span>Registrar venta</span>
                    <i data-lucide="arrow-right" class="h-4 w-4"></i>
                </a>
                <a href="<?= BASE_URL ?>compras" class="btn-ghost justify-between">
                    <span>Registrar compra</span>
                    <i data-lucide="arrow-right" class="h-4 w-4"></i>
                </a>
                <a href="<?= BASE_URL ?>productos" class="btn-ghost justify-between">
                    <span>Gestionar productos</span>
                    <i data-lucide="arrow-right" class="h-4 w-4"></i>
                </a>
                <a href="<?= BASE_URL ?>reportes" class="btn-ghost justify-between">
                    <span>Ver reportes</span>
                    <i data-lucide="arrow-right" class="h-4 w-4"></i>
                </a>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold text-gray-800">Resumen rapido</h3>
                <span class="pill bg-blue-pastel/60">Hoy</span>
            </div>
            <div class="table-shell">
                <table>
                    <thead>
                    <tr>
                        <th class="text-left">Indicador</th>
                        <th class="text-right">Valor</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="text-gray-700">Monto ventas</td>
                        <td class="text-right font-semibold">$<?= number_format($stats['monto_hoy'], 2) ?></td>
                    </tr>
                    <tr>
                        <td class="text-gray-700">Monto compras</td>
                        <td class="text-right font-semibold">$<?= number_format($stats['monto_compras_hoy'], 2) ?></td>
                    </tr>
                    <tr>
                        <td class="text-gray-700">Tickets vendidos</td>
                        <td class="text-right font-semibold"><?= number_format($stats['ventas_hoy']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-gray-700">Productos en stock bajo</td>
                        <td class="text-right font-semibold text-pink-700"><?= number_format($stats['stock_bajo']) ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
