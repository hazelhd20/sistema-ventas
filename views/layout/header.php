<?php
require_once dirname(__DIR__) . '/../config/config.php';
require_once dirname(__DIR__) . '/../config/helpers.php';
require_once dirname(__DIR__) . '/../config/database.php';

$current = $_GET['url'] ?? 'home';
$user = auth_user();
$userName = $user['name'] ?? 'Invitado';
$userRoleId = $_SESSION['user_rol'] ?? null;
$userRoleLabel = $_SESSION['user_rol_nombre'] ?? ($user['role'] ?? '');

$navLinks = [
    ['url' => 'home', 'icon' => 'home', 'label' => 'Inicio', 'roles' => [ROL_ADMIN, ROL_ENCARGADO, ROL_VENDEDOR]],
    ['url' => 'ventas', 'icon' => 'shopping-cart', 'label' => 'Ventas', 'roles' => [ROL_ADMIN, ROL_VENDEDOR]],
    ['url' => 'compras', 'icon' => 'shopping-bag', 'label' => 'Compras', 'roles' => [ROL_ADMIN, ROL_ENCARGADO]],
    ['url' => 'productos', 'icon' => 'package', 'label' => 'Productos', 'roles' => [ROL_ADMIN, ROL_ENCARGADO, ROL_VENDEDOR]],
    ['url' => 'clientes', 'icon' => 'users', 'label' => 'Clientes', 'roles' => [ROL_ADMIN, ROL_ENCARGADO, ROL_VENDEDOR]],
    ['url' => 'proveedores', 'icon' => 'truck', 'label' => 'Proveedores', 'roles' => [ROL_ADMIN, ROL_ENCARGADO]],
    ['url' => 'reportes', 'icon' => 'bar-chart-2', 'label' => 'Reportes', 'roles' => [ROL_ADMIN, ROL_ENCARGADO, ROL_VENDEDOR]],
    ['url' => 'usuarios', 'icon' => 'shield', 'label' => 'Usuarios', 'roles' => [ROL_ADMIN]],
    ['url' => 'configuracion', 'icon' => 'settings', 'label' => 'Configuracion', 'roles' => [ROL_ADMIN]],
];

$lowStockCount = 0;
try {
    $db = new Database();
    $conn = $db->getConnection();
    if ($conn) {
        $stmt = $conn->query("SELECT COUNT(*) AS total FROM productos WHERE existencia <= stockMinimo AND estado = 1");
        $row = $stmt ? $stmt->fetch() : ['total' => 0];
        $lowStockCount = (int) ($row['total'] ?? 0);
    }
} catch (Exception $e) {
    $lowStockCount = 0;
}

$avatarInitials = strtoupper(substr($userName, 0, 1));
$pageTitle = isset($pageTitle) ? $pageTitle : 'Panel';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> - <?= e(SITE_NAME) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'pink-pastel': '#F7C6D0',
                        'blue-pastel': '#A8D8EA',
                        'green-pastel': '#B8E0D2',
                        'peach-pastel': '#FFD8B5',
                    },
                    fontFamily: {
                        sans: ['Poppins', 'ui-sans-serif', 'system-ui'],
                    },
                },
            },
        };
    </script>
    <link rel="stylesheet" href="<?= asset_url('styles.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans h-screen overflow-hidden">
<div class="flex h-full bg-gray-50 overflow-hidden">
    <aside class="bg-white shadow-lg w-20 md:w-24 flex flex-col items-center py-6 h-screen sticky top-0">
        <div class="mb-8 h-12 w-12 rounded-full bg-blue-pastel flex items-center justify-center text-gray-800 font-bold shadow-sm">
            <span>SV</span>
        </div>
        <?php foreach ($navLinks as $item): ?>
            <?php
            if (!in_array($userRoleId, $item['roles'], true)) {
                continue;
            }
            $isActive = strpos($current, $item['url']) === 0;
            ?>
            <a href="<?= base_url($item['url']) ?>"
               class="sidebar-icon group <?= $isActive ? 'bg-gray-100 text-gray-900' : '' ?>">
                <i data-lucide="<?= e($item['icon']) ?>"></i>
                <span class="sidebar-tooltip group-hover:scale-100 scale-0">
                    <?= e($item['label']) ?>
                </span>
            </a>
        <?php endforeach; ?>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white shadow-sm z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Panel</p>
                        <h1 class="text-xl font-semibold text-gray-800"><?= e($pageTitle) ?></h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <button class="p-2 rounded-full text-gray-600 hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-pastel">
                                <span class="sr-only">Ver notificaciones</span>
                                <i data-lucide="bell" class="h-6 w-6"></i>
                                <?php if ($lowStockCount > 0): ?>
                                    <span class="absolute -top-1 -right-1 block h-4 w-4 rounded-full bg-pink-pastel text-[10px] text-center font-bold text-gray-800">
                                        <?= $lowStockCount ?>
                                    </span>
                                <?php endif; ?>
                            </button>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="h-9 w-9 rounded-full bg-blue-pastel flex items-center justify-center text-gray-800 font-semibold">
                                <?= $avatarInitials ?>
                            </div>
                            <div class="leading-tight">
                                <div class="text-sm font-medium text-gray-800"><?= e($userName) ?></div>
                                <div class="text-xs text-gray-500"><?= e($userRoleLabel) ?></div>
                            </div>
                        </div>
                        <a href="<?= base_url('auth/logout') ?>" class="p-2 rounded-full text-gray-600 hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-pastel" title="Cerrar sesion">
                            <i data-lucide="log-out" class="h-5 w-5"></i>
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto px-4 sm:px-6 lg:px-8 py-6">
            <?php include dirname(__DIR__) . '/partials/flash.php'; ?>
