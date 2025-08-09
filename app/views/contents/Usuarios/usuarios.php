<?php if (!$isLogged): ?>
  <div class="bg-yellow-50 border border-yellow-200 rounded p-4 mb-6">
    <p class="text-yellow-800">
      <i class="fa fa-lock mr-2"></i>
      Esta sección requiere autenticación. <a href="<?php echo APP_URL; ?>?views=login" class="font-medium hover:underline">Inicia sesión</a> para continuar.
    </p>
  </div>
<?php endif; ?>

<div class="bg-white rounded shadow p-6">
  <h2 class="text-xl font-semibold mb-4">
    <i class="fa fa-users text-green-600 mr-2"></i>Usuarios
  </h2>
  
  <?php if ($isLogged): ?>
    <div class="mb-4">
      <button class="inline-flex items-center px-4 py-2 btn-primary rounded hover:opacity-90">
        <i class="fa fa-user-plus mr-2"></i>Nuevo Usuario
      </button>
    </div>
    
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($user['usuario']); ?></td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($user['nombres'] . ' ' . $user['apellidos']); ?></td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($user['rol'] ?: 'Usuario'); ?></td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">Editar</a>
              <a href="#" class="text-red-600 hover:text-red-900">Eliminar</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <p class="text-gray-600">Inicia sesión para ver y gestionar usuarios del sistema.</p>
  <?php endif; ?>
</div>
