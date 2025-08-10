<div class="bg-white rounded shadow p-6">
  <h2 class="text-xl font-semibold mb-2">Bienvenido a TiendaClaudia</h2>
  <?php if ($isLogged): ?>
    <p class="text-gray-600">Has iniciado sesión como <strong><?php echo htmlspecialchars($user['usuario']); ?></strong> (rol: <?php echo htmlspecialchars($user['id_rol'] == '2' ? 'Administrador' : ($user['id_rol'] == '1' ? 'Empleado' : 'Usuario')); ?>).</p>
    <p class="text-gray-600 mt-2">Explora las opciones del menú para gestionar productos<?php echo ($_SESSION['id_rol'] ?? '') == '2' ? ', usuarios' : ''; ?> y ventas.</p>
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

<div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
  <div class="bg-white rounded shadow p-6">
    <h3 class="text-lg font-semibold mb-2">
      <i class="fa fa-box text-blue-600 mr-2"></i>Productos
    </h3>
    <p class="text-gray-600">Gestiona el inventario y catálogo de productos.</p>
    <a href="<?php echo APP_URL; ?>?views=productos" class="mt-3 inline-block text-blue-600 hover:underline">Ver productos →</a>
  </div>
  
  <?php if ($isLogged): ?>
    <?php if (($_SESSION['id_rol'] ?? '') == '2'): ?>
    <div class="bg-white rounded shadow p-6">
      <h3 class="text-lg font-semibold mb-2">
        <i class="fa fa-users text-green-600 mr-2"></i>Usuarios
      </h3>
      <p class="text-gray-600">Administra usuarios y permisos del sistema.</p>
      <a href="<?php echo APP_URL; ?>?views=usuarios" class="mt-3 inline-block text-blue-600 hover:underline">Ver usuarios →</a>
    </div>
    <?php endif; ?>
    
    <div class="bg-white rounded shadow p-6">
      <h3 class="text-lg font-semibold mb-2">
        <i class="fa fa-shopping-cart text-purple-600 mr-2"></i>Ventas
      </h3>
      <p class="text-gray-600">Registra y consulta las ventas realizadas.</p>
      <a href="<?php echo APP_URL; ?>?views=ventas" class="mt-3 inline-block text-blue-600 hover:underline">Ver ventas →</a>
    </div>
  <?php endif; ?>
</div>
