<?php
if (!isset($_SESSION)) { session_start(); }
if (empty($_SESSION['id_usuario'])) {
    echo "<script>window.location.href='" . APP_URL . "?views=login';</script>";
    exit;
}

$user = [
    'nombres' => $_SESSION['nombres'] ?? '',
    'apellidos' => $_SESSION['apellidos'] ?? '',
    'usuario' => $_SESSION['usuario'] ?? '',
    'rol' => $_SESSION['rol'] ?? '',
];
?>

<div class="min-h-screen flex" style="background: var(--bg); color: var(--text);">
  <!-- Sidebar -->
  <aside class="w-64 bg-white border-r border-gray-200 hidden md:flex md:flex-col">
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
      <button type="button" data-action="logout" class="w-full inline-flex items-center justify-center px-4 py-2 btn-danger rounded hover:opacity-90">
        <i class="fa fa-sign-out mr-2"></i> Cerrar sesión
      </button>
    </div>
  </aside>

  <!-- Contenido principal -->
  <main class="flex-1">
    <!-- Topbar -->
    <div class="h-16 bg-white border-b flex items-center justify-between px-4">
      <div class="flex items-center space-x-3">
        <button class="md:hidden inline-flex items-center justify-center p-2 rounded hover:bg-gray-100" aria-label="Menu">
          <i class="fa fa-bars"></i>
        </button>
        <h1 class="text-lg font-semibold">Panel</h1>
      </div>
      <div class="flex items-center space-x-3">
        <span class="text-sm text-gray-600 hidden sm:inline">Hola, <?php echo htmlspecialchars($user['nombres'] . ' ' . $user['apellidos']); ?></span>
        <button type="button" data-action="logout" class="inline-flex items-center px-3 py-2 btn-danger rounded hover:opacity-90">
          <i class="fa fa-sign-out mr-2"></i>Salir
        </button>
      </div>
    </div>

    <div class="p-6">
      <div class="bg-white rounded shadow p-6">
        <h2 class="text-xl font-semibold mb-2">Bienvenido</h2>
        <p class="text-gray-600">Has iniciado sesión como <strong><?php echo htmlspecialchars($user['usuario']); ?></strong> (rol: <?php echo htmlspecialchars($user['rol']); ?>).</p>
        <p class="text-gray-600 mt-2">Los colores se basan en variables globales definidas en <code>public/assets/css/global.css</code>.</p>
      </div>
    </div>
  </main>
</div>
