<div class="bg-white rounded shadow p-6">
  <h2 class="text-xl font-semibold mb-4">
    <i class="fa fa-box text-blue-600 mr-2"></i>Productos
  </h2>
  
  <?php if ($isLogged): ?>
    <div class="mb-4">
      <button class="inline-flex items-center px-4 py-2 btn-primary rounded hover:opacity-90">
        <i class="fa fa-plus mr-2"></i>Nuevo Producto
      </button>
    </div>
  <?php endif; ?>
  
  <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
          <?php if ($isLogged): ?>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
          <?php endif; ?>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        <tr>
          <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm font-medium text-gray-900">Producto Demo</div>
            <div class="text-sm text-gray-500">Descripción del producto</div>
          </td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$25.00</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">15 unidades</td>
          <?php if ($isLogged): ?>
          <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">Editar</a>
            <a href="#" class="text-red-600 hover:text-red-900">Eliminar</a>
          </td>
          <?php endif; ?>
        </tr>
      </tbody>
    </table>
  </div>
  
  <?php if (!$isLogged): ?>
    <div class="mt-4 p-4 bg-blue-50 rounded">
      <p class="text-blue-800">
        <i class="fa fa-info-circle mr-2"></i>
        <a href="<?php echo APP_URL; ?>?views=login" class="font-medium hover:underline">Inicia sesión</a> para gestionar productos y realizar ventas.
      </p>
    </div>
  <?php endif; ?>
</div>
