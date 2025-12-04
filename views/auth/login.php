<?php
$appName = config('app.name', 'Sistema de inventarios');
$error = flash('error');
$usuario = e(old('usuario', ''));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesion - <?= $appName ?></title>
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
</head>
<body class="min-h-screen font-sans bg-gradient-to-br from-blue-pastel via-peach-pastel to-pink-pastel flex items-center justify-center">
<div class="absolute inset-0 pointer-events-none">
    <div class="absolute -top-24 -left-24 h-64 w-64 rounded-full bg-white/40 blur-3xl"></div>
    <div class="absolute bottom-0 right-10 h-72 w-72 rounded-full bg-white/30 blur-3xl"></div>
    <div class="absolute top-1/3 left-1/2 transform -translate-x-1/2 h-40 w-40 rounded-full bg-blue-pastel/40 blur-2xl"></div>
    <div class="absolute bottom-10 left-16 h-24 w-24 rounded-full bg-green-pastel/50 blur-xl"></div>
</div>

<div class="relative w-full max-w-5xl grid grid-cols-1 lg:grid-cols-2 gap-6 items-center px-4 sm:px-6 lg:px-10">
    <div class="hidden lg:block">
        <div class="bg-white/60 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/50 p-10">
            <div class="flex items-center space-x-3 mb-6">
                <div class="h-12 w-12 rounded-full bg-blue-pastel flex items-center justify-center text-gray-800 font-bold shadow-sm">
                    <span>SV</span>
                </div>
                <div>
                    <p class="text-sm text-gray-600 tracking-wide">Bienvenido al</p>
                    <h1 class="text-2xl font-semibold text-gray-800"><?= $appName ?></h1>
                </div>
            </div>
            
            <div class="mt-6 pt-6 border-t border-gray-300/50">
                <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center space-x-2">
                    <i data-lucide="users" class="h-4 w-4"></i>
                    <span>Usuarios por defecto</span>
                </h3>
                <div class="space-y-2 text-xs">
                    <div class="bg-white/60 rounded-lg p-3 border border-gray-200/50">
                        <div class="font-medium text-gray-800 mb-1">Administrador</div>
                        <div class="text-gray-600 space-y-1">
                            <div><span class="font-medium">Usuario:</span> admin</div>
                            <div><span class="font-medium">Contraseña:</span> admin123</div>
                        </div>
                    </div>
                    <div class="bg-white/60 rounded-lg p-3 border border-gray-200/50">
                        <div class="font-medium text-gray-800 mb-1">Encargado</div>
                        <div class="text-gray-600 space-y-1">
                            <div><span class="font-medium">Usuario:</span> encargado</div>
                            <div><span class="font-medium">Contraseña:</span> encargado123</div>
                        </div>
                    </div>
                    <div class="bg-white/60 rounded-lg p-3 border border-gray-200/50">
                        <div class="font-medium text-gray-800 mb-1">Vendedor</div>
                        <div class="text-gray-600 space-y-1">
                            <div><span class="font-medium">Usuario:</span> vendedor</div>
                            <div><span class="font-medium">Contraseña:</span> vendedor123</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="relative">
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/60 p-8 sm:p-10">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 rounded-full bg-pink-pastel flex items-center justify-center text-gray-800 font-bold shadow-sm">
                        <span>SV</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Acceso</p>
                        <h2 class="text-xl font-semibold text-gray-900">Iniciar sesion</h2>
                    </div>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-pastel text-gray-800 border border-white/60 shadow-sm">
                    El Mercadito
                </span>
            </div>

            <form class="space-y-5" action="<?= base_url('auth/login') ?>" method="POST">
                <?php if ($error): ?>
                    <div class="flex items-start p-3 bg-pink-pastel/60 rounded-lg text-gray-800 border border-pink-200">
                        <i data-lucide="alert-circle" class="h-5 w-5 text-pink-700 mr-2 mt-0.5"></i>
                        <p class="text-sm leading-relaxed"><?= e($error) ?></p>
                    </div>
                <?php endif; ?>

                <div class="space-y-2">
                    <label for="usuario" class="text-sm font-medium text-gray-700">Usuario</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="user" class="h-5 w-5 text-gray-400"></i>
                        </div>
                        <input id="usuario" name="usuario" type="text" required value="<?= $usuario ?>"
                               class="block w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-blue-pastel focus:border-blue-300 text-gray-900"
                               placeholder="admin">
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="password" class="text-sm font-medium text-gray-700">Contrasena</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="lock" class="h-5 w-5 text-gray-400"></i>
                        </div>
                        <input id="password" name="password" type="password" required
                               class="block w-full pl-11 pr-11 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-blue-pastel focus:border-blue-300 text-gray-900"
                               placeholder="********">
                        <button type="button" id="toggle-login-password" class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-gray-700">
                            <i data-lucide="eye" class="h-5 w-5"></i>
                        </button>
                    </div>
                </div>

                <button type="submit"
                        class="group w-full flex justify-center py-3 px-4 border border-transparent text-sm font-semibold rounded-xl text-gray-900 bg-blue-pastel hover:bg-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-300 transition items-center space-x-2">
                    <i data-lucide="log-in" class="h-5 w-5"></i>
                    <span>Entrar al sistema</span>
                </button>
            </form>
            
            <!-- Información de usuarios por defecto (visible en móvil) -->
            <div class="lg:hidden mt-6 pt-6 border-t border-gray-300/50">
                <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center space-x-2">
                    <i data-lucide="users" class="h-4 w-4"></i>
                    <span>Usuarios por defecto</span>
                </h3>
                <div class="space-y-2 text-xs">
                    <div class="bg-white/60 rounded-lg p-3 border border-gray-200/50">
                        <div class="font-medium text-gray-800 mb-1">Administrador</div>
                        <div class="text-gray-600 space-y-1">
                            <div><span class="font-medium">Usuario:</span> admin</div>
                            <div><span class="font-medium">Contraseña:</span> admin123</div>
                        </div>
                    </div>
                    <div class="bg-white/60 rounded-lg p-3 border border-gray-200/50">
                        <div class="font-medium text-gray-800 mb-1">Encargado</div>
                        <div class="text-gray-600 space-y-1">
                            <div><span class="font-medium">Usuario:</span> encargado</div>
                            <div><span class="font-medium">Contraseña:</span> encargado123</div>
                        </div>
                    </div>
                    <div class="bg-white/60 rounded-lg p-3 border border-gray-200/50">
                        <div class="font-medium text-gray-800 mb-1">Vendedor</div>
                        <div class="text-gray-600 space-y-1">
                            <div><span class="font-medium">Usuario:</span> vendedor</div>
                            <div><span class="font-medium">Contraseña:</span> vendedor123</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    if (window.lucide) { lucide.createIcons(); }
    (function() {
        const toggle = document.getElementById('toggle-login-password');
        const input = document.getElementById('password');
        if (!toggle || !input) return;
        toggle.addEventListener('click', () => {
            const isHidden = input.getAttribute('type') === 'password';
            input.setAttribute('type', isHidden ? 'text' : 'password');
            const icon = toggle.querySelector('i');
            if (icon) {
                icon.setAttribute('data-lucide', isHidden ? 'eye-off' : 'eye');
                if (window.lucide) { lucide.createIcons(); }
            }
        });
    })();
</script>
</body>
</html>
