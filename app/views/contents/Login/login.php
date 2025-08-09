<?php
use app\controllers\Login\loginController;
if (!isset($_SESSION)) { session_start(); }

// Flash success desde register
$flashSuccess = $_SESSION['flash_success'] ?? null;
if ($flashSuccess !== null) {
    unset($_SESSION['flash_success']);
}

$authMsg = null;
// Manejar POST (login) con controlador y redirección del lado servidor
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ctrl = new loginController();
    $resp = $ctrl->procesar($_POST);
    if (!empty($resp['success'])) {
        header('Location: ' . APP_URL . '?views=index');
        exit;
    } else {
        $authMsg = $resp['message'] ?? 'Error al iniciar sesión';
    }
}
?>
<!-- Login con tailwind -->
<div class="flex items-center justify-center min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-lg shadow-md">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Iniciar sesión
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Accede a tu cuenta
            </p>
        </div>
        <?php if (!empty($flashSuccess)): ?>
            <div class="p-3 mb-4 text-sm text-green-800 bg-green-100 rounded" role="alert">
                <?php echo htmlspecialchars($flashSuccess, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>
        <?php if ($authMsg): ?>
            <div class="p-3 mb-4 text-sm text-red-700 bg-red-100 rounded" role="alert">
                <?php echo htmlspecialchars($authMsg, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>
        <form class="mt-8 space-y-6" action="<?php echo APP_URL; ?>?views=login" method="POST">
            <input type="hidden" name="action" value="login">
            <div class="rounded-md -space-y-px">
                <div class="mb-4">
                    <label for="usuario" class="block text-sm font-medium text-gray-700 mb-1">Usuario</label>
                    <input id="usuario" name="usuario" type="text" autocomplete="username" required class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Usuario">
                </div>
                <div>
                    <label for="contrasenia" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                    <div class="relative">
                        <input id="contrasenia" name="contrasenia" type="password" autocomplete="current-password" required class="appearance-none rounded-md relative block w-full pr-10 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Contraseña">
                        <button type="button" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 toggle-password" data-target="#contrasenia" aria-label="Mostrar u ocultar contraseña">
                            <i class="fa-solid fa-eye-slash"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="remember-me" class="ml-2 block text-sm text-gray-900">
                        Recordarme
                    </label>
                </div>

                <div class="text-sm">
                    <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Iniciar sesión
                </button>
            </div>
        </form>
        <div class="text-center">
            <p class="text-sm text-gray-600">¿No tienes una cuenta? 
                <a href="<?php echo APP_URL; ?>?views=register" class="font-medium text-indigo-600 hover:text-indigo-500">Regístrate</a>
            </p>
            <a href="<?php echo APP_URL; ?>" class="text-sm text-gray-600 hover:text-gray-900">Volver al inicio</a>
        </div>
    </div>
</div>