<?php
if (!isset($_SESSION)) { session_start(); }
$isLogged = !empty($_SESSION['id_usuario']);
$user = [
    'nombres' => $_SESSION['nombres'] ?? '',
    'apellidos' => $_SESSION['apellidos'] ?? '',
    'usuario' => $_SESSION['usuario'] ?? '',
    'id_rol' => $_SESSION['id_rol'] ?? '',
];
?>

<div class="min-h-screen flex" style="background: var(--bg); color: var(--text); font-family: 'Lexend', system-ui, sans-serif;">
  <!-- Mobile menu overlay -->
  <div id="mobile-menu-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden"></div>
  
  <!-- Sidebar -->
  <aside id="sidebar" class="w-64 fixed md:relative inset-y-0 left-0 z-50 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out md:flex md:flex-col shadow-xl" style="background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);">
    <!-- Header con logo -->
    <div class="h-16 flex items-center justify-center px-4">
      <span class="text-xl font-bold text-white"><?php echo APP_NAME; ?></span>
    </div>
    
    <!-- Información del usuario (si está logueado) -->
    <?php if ($isLogged): ?>
      <div class="px-4 py-3 bg-slate-800/50 mx-3 rounded-lg mb-4">
        <div class="flex items-center space-x-3">
          <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
            <i class="fa fa-user text-white text-sm"></i>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-white truncate">
              <?php echo htmlspecialchars($user['nombres'] . ' ' . $user['apellidos']); ?>
            </p>
            <p class="text-xs text-slate-300 truncate">
              @<?php echo htmlspecialchars($user['usuario']); ?>
            </p>
          </div>
        </div>
      </div>
    <?php endif; ?>
    
    <!-- Navegación -->
    <nav class="flex-1 px-3 space-y-1">
      <a href="<?php echo APP_URL; ?>?views=index" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg text-slate-300 hover:text-white hover:bg-slate-700/50 transition-all duration-200">
        <i class="fa fa-home mr-3 text-slate-400 group-hover:text-white"></i>
        Inicio
      </a>
      <?php if ($isLogged): ?>
        <?php if (($_SESSION['id_rol'] ?? '') == '2'): ?>
          <a href="<?php echo APP_URL; ?>?views=usuarios" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg text-slate-300 hover:text-white hover:bg-slate-700/50 transition-all duration-200">
            <i class="fa fa-users mr-3 text-slate-400 group-hover:text-white"></i>
            Usuarios
          </a>
        <?php endif; ?>
      <?php endif; ?>
      <?php if ($isLogged): ?>
        <?php if (($_SESSION['id_rol'] ?? '') == '2'): ?>
          <a href="<?php echo APP_URL; ?>?views=productos" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg text-slate-300 hover:text-white hover:bg-slate-700/50 transition-all duration-200">
            <i class="fa fa-box mr-3 text-slate-400 group-hover:text-white"></i>
            Productos
          </a>
        <?php endif; ?>
      <?php endif; ?>
    </nav>
    
    <!-- Footer del sidebar -->
    <div class="p-3">
      <?php if ($isLogged): ?>
        <button type="button" data-action="logout" class="w-full flex items-center justify-center px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
          <i class="fa fa-sign-out mr-2"></i>
          Cerrar sesión
        </button>
      <?php else: ?>
        <a href="<?php echo APP_URL; ?>?views=login" class="w-full flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
          <i class="fa fa-sign-in mr-2"></i>
          Iniciar sesión
        </a>
      <?php endif; ?>
    </div>
  </aside>

  <!-- Contenido principal -->
  <main class="flex-1">
    <!-- Topbar -->
    <div class="h-16 bg-slate-700 flex items-center justify-between px-4">
      <div class="flex items-center space-x-3">
        <button id="mobile-menu-btn" class="md:hidden inline-flex items-center justify-center p-2 rounded hover:bg-slate-700" aria-label="Menu">
          <i class="fa fa-bars"></i>
        </button>
        <h1 id="page-title" class="text-lg font-semibold text-white"><?php echo ucfirst($GLOBALS['currentView'] ?? 'Panel'); ?></h1>
      </div>
      <div class="flex items-center space-x-3">
        <!-- Botón de login removido - ahora está en el sidebar -->
      </div>
    </div>

    <!-- Área de contenido dinámico -->
    <div id="main-content" class="p-6">
      <?php 
      // Aquí se carga el contenido específico de cada vista
      $viewContent = $GLOBALS['viewContent'] ?? null;
      if ($viewContent && is_file($viewContent)) {
        include $viewContent;
      } else {
        echo '<div class="bg-white rounded shadow p-6"><p class="text-gray-500">Contenido no encontrado.</p></div>';
      }
      ?>
    </div>
  </main>
</div>
