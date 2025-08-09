<?php
if (!isset($_SESSION)) { session_start(); }
$isLogged = !empty($_SESSION['id_usuario']);
$user = [
    'nombres' => $_SESSION['nombres'] ?? '',
    'apellidos' => $_SESSION['apellidos'] ?? '',
    'usuario' => $_SESSION['usuario'] ?? '',
    'rol' => $_SESSION['rol'] ?? '',
];
?>

<div class="min-h-screen flex" style="background: var(--bg); color: var(--text);">
  <!-- Mobile menu overlay -->
  <div id="mobile-menu-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden"></div>
  
  <!-- Sidebar -->
  <aside id="sidebar" class="w-64 bg-white border-r border-gray-200 fixed md:relative inset-y-0 left-0 z-50 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out md:flex md:flex-col">
    <div class="h-16 flex items-center justify-center border-b">
      <span class="text-lg font-semibold">TiendaClaudia</span>
    </div>
    <nav class="flex-1 p-4 space-y-2">
      <a href="<?php echo APP_URL; ?>?views=index" class="block px-3 py-2 rounded hover:bg-gray-100 text-gray-700">
        <i class="fa fa-home mr-2"></i>Inicio
      </a>
      <a href="#" class="block px-3 py-2 rounded hover:bg-gray-100 text-gray-700">
        <i class="fa fa-box mr-2"></i>Productos
      </a>
      <a href="#" class="block px-3 py-2 rounded hover:bg-gray-100 text-gray-700">
        <i class="fa fa-users mr-2"></i>Usuarios
      </a>
    </nav>
    <div class="p-4 border-t">
      <?php if ($isLogged): ?>
        <button type="button" data-action="logout" class="w-full inline-flex items-center justify-center px-4 py-2 btn-danger rounded hover:opacity-90">
          <i class="fa fa-sign-out mr-2"></i> Cerrar sesión
        </button>
      <?php else: ?>
        <a href="<?php echo APP_URL; ?>?views=login" class="w-full inline-flex items-center justify-center px-4 py-2 btn-primary rounded hover:opacity-90 text-center">
          <i class="fa fa-sign-in mr-2"></i> Iniciar sesión
        </a>
      <?php endif; ?>
    </div>
  </aside>

  <!-- Contenido principal -->
  <main class="flex-1">
    <!-- Topbar -->
    <div class="h-16 bg-white border-b flex items-center justify-between px-4">
      <div class="flex items-center space-x-3">
        <button id="mobile-menu-btn" class="md:hidden inline-flex items-center justify-center p-2 rounded hover:bg-gray-100" aria-label="Menu">
          <i class="fa fa-bars"></i>
        </button>
        <h1 class="text-lg font-semibold">Panel</h1>
      </div>
      <div class="flex items-center space-x-3">
        <?php if ($isLogged): ?>
          <span class="text-sm text-gray-600 hidden sm:inline">Hola, <?php echo htmlspecialchars($user['nombres'] . ' ' . $user['apellidos']); ?></span>
          <button type="button" data-action="logout" class="inline-flex items-center px-3 py-2 btn-danger rounded hover:opacity-90">
            <i class="fa fa-sign-out mr-2"></i>Salir
          </button>
        <?php else: ?>
          <a href="<?php echo APP_URL; ?>?views=login" class="inline-flex items-center px-3 py-2 btn-primary rounded hover:opacity-90">
            <i class="fa fa-sign-in mr-2"></i>Iniciar sesión
          </a>
        <?php endif; ?>
      </div>
    </div>

    <div class="p-6">
      <div class="bg-white rounded shadow p-6">
        <h2 class="text-xl font-semibold mb-2">Bienvenido a TiendaClaudia</h2>
        <?php if ($isLogged): ?>
          <p class="text-gray-600">Has iniciado sesión como <strong><?php echo htmlspecialchars($user['usuario']); ?></strong> (rol: <?php echo htmlspecialchars($user['rol']); ?>).</p>
          <p class="text-gray-600 mt-2">Explora las opciones del menú para gestionar productos y usuarios.</p>
        <?php else: ?>
          <p class="text-gray-600">Explora nuestra tienda y productos. Para acceder a funciones completas, <a href="<?php echo APP_URL; ?>?views=login" class="text-blue-600 hover:underline">inicia sesión</a>.</p>
          <div class="mt-4 space-x-3">
            <a href="<?php echo APP_URL; ?>?views=login" class="inline-flex items-center px-4 py-2 btn-primary rounded hover:opacity-90">
              <i class="fa fa-sign-in mr-2"></i>Iniciar sesión
            </a>
            <a href="<?php echo APP_URL; ?>?views=register" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
              <i class="fa fa-user-plus mr-2"></i>Registrarse
            </a>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </main>
</div>
