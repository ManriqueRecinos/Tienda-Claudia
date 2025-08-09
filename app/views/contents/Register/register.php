<?php
use app\controllers\Login\loginController;
if (!isset($_SESSION)) { session_start(); }
$regMsg = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ctrl = new loginController();
    $resp = $ctrl->procesar($_POST);
    if (!empty($resp['success'])) {
        $_SESSION['flash_success'] = 'Registro exitoso, ahora puedes iniciar sesión';
        header('Location: ' . APP_URL . '?views=login');
        exit;
    } else {
        $regMsg = $resp['message'] ?? 'Error al registrarse';
    }
}
?>
<!-- Register con tailwind -->
<div class="flex items-center justify-center min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-lg shadow-md">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Crear una cuenta
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Regístrate para acceder a nuestros servicios
            </p>
        </div>
        <?php if ($regMsg): ?>
            <div class="p-3 mb-4 text-sm text-red-700 bg-red-100 rounded" role="alert">
                <?php echo htmlspecialchars($regMsg, ENT_QUOTES, 'UTF-8'); ?>
            </div>

        <?php endif; ?>
        <form class="mt-8 space-y-6" action="<?php echo APP_URL; ?>?views=register" method="POST">
            <input type="hidden" name="action" value="register">
            <div class="rounded-md -space-y-px">
                <div class="mb-4">
                    <label for="nombres" class="block text-sm font-medium text-gray-700 mb-1">Nombres</label>
                    <input id="nombres" name="nombres" type="text" required class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Nombres">
                </div>
                <div class="mb-4">
                    <label for="apellidos" class="block text-sm font-medium text-gray-700 mb-1">Apellidos</label>
                    <input id="apellidos" name="apellidos" type="text" required class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Apellidos">
                </div>
                <div class="mb-4">
                    <label for="usuario" class="block text-sm font-medium text-gray-700 mb-1">Usuario</label>
                    <input id="usuario" name="usuario" type="text" autocomplete="username" required class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Usuario">
                </div>
                <div>
                    <label for="contrasenia" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                    <div class="relative">
                        <input id="contrasenia" name="contrasenia" type="password" autocomplete="new-password" required class="appearance-none rounded-md relative block w-full pr-10 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Contraseña">
                        <button type="button" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 toggle-password" data-target="#contrasenia" aria-label="Mostrar u ocultar contraseña">
                            <i class="fa-solid fa-eye-slash text-gray-500"></i>
                        </button>
                    </div>
                </div>
                <div>
                    <label for="confirm_contrasenia" class="block text-sm font-medium text-gray-700 mb-1">Confirmar contraseña</label>
                    <div class="relative">
                        <input id="confirm_contrasenia" name="confirm_contrasenia" type="password" autocomplete="new-password" required class="appearance-none rounded-md relative block w-full pr-10 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Confirmar contraseña">
                        <button type="button" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 toggle-password" data-target="#confirm_contrasenia" aria-label="Mostrar u ocultar contraseña">
                            <i class="fa-solid fa-eye-slash text-gray-500"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Registrarse
                </button>
            </div>
            <div class="text-center">
                <p class="text-sm text-gray-600">¿Ya tienes una cuenta? 
                    <a href="<?php echo APP_URL; ?>?views=login" class="font-medium text-indigo-600 hover:text-indigo-500">Iniciar sesión</a>
                </p>
            </div>
        </form>
        <div class="text-center mt-4">
            <a href="<?php echo APP_URL; ?>" class="text-sm text-gray-600 hover:text-gray-900">Volver al inicio</a>
        </div>
    </div>
</div>