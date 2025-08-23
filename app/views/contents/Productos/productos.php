<?php if (!$isLogged): ?>
    <div class="bg-yellow-50 border border-yellow-200 rounded p-4 mb-6">
      <p class="text-yellow-800">
        <i class="fa fa-lock mr-2"></i>
        Esta sección requiere autenticación.
        <a href="<?php echo APP_URL; ?>?views=login" class="font-medium hover:underline">Inicia sesión</a> para continuar.
      </p>
    </div>

  <?php endif; ?>

  <!-- Modal Edición de Usuario (fuera del condicional, disponible al estar logueado) -->
  <div id="modal_productos" class="fixed inset-0 z-50 hidden" aria-hidden="true">
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="relative z-10 min-h-full flex items-center justify-center p-4">
      <div class="w-full max-w-2xl bg-white rounded-xl shadow-xl overflow-hidden modal-panel">
        <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50">
          <h3 class="text-lg font-semibold flex items-center gap-2">
            <i class="fa fa-box text-sky-600"></i>
            Editar producto
          </h3>
          <button type="button" id="modal_productos_close" class="h-9 w-9 inline-flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-500 hover:text-gray-700">
            <i class="fa fa-times"></i>
          </button>
        </div>
        <form id="form_productos" class="p-5 space-y-5">
          <!-- Datos principales -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label for="nombre_producto" class="block text-xs text-gray-600 mb-1">Nombre</label>
              <input id="nombre_producto" name="nombre_producto" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:outline-none" />
            </div>
            <div>
              <label for="categoria_producto" class="block text-xs text-gray-600 mb-1">Categoría</label>
              <select id="categoria_producto" name="categoria_producto" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:outline-none">
                <option value="">Cargando categorías...</option>
              </select>
            </div>
            <div>
              <label for="stock_producto" class="block text-xs text-gray-600 mb-1">Stock</label>
              <input id="stock_producto" name="stock_producto" type="number" min="0" step="1" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:outline-none" />
            </div>
            <div>
              <label for="costo_producto" class="block text-xs text-gray-600 mb-1">$ Costo</label>
              <input id="costo_producto" name="costo_producto" type="number" min="0" step="0.01" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:outline-none" />
            </div>
            <div>
              <label for="venta_producto" class="block text-xs text-gray-600 mb-1">$ Venta</label>
              <input id="venta_producto" name="venta_producto" type="number" min="0" step="0.01" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:outline-none" />
            </div>
          </div>

          <!-- Atributos adicionales -->
          <div class="border-t border-gray-100 pt-4">
            <div class="flex items-center justify-between mb-3">
              <h4 class="text-sm font-semibold text-gray-700">Atributos adicionales</h4>
              <button type="button" id="btn_add_attr_edit" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md bg-sky-600 text-white text-sm hover:bg-sky-700">
                <i class="fa fa-plus"></i> Agregar
              </button>
            </div>
            <div id="additional_fields_edit" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <!-- Campos dinámicos de edición -->
            </div>
            <p class="mt-2 text-xs text-gray-500">Ejemplos: Talla, Color, Material. Cada atributo se enviará como extras[nombre_atributo].</p>
          </div>

          <div class="pt-2 flex items-center justify-end gap-2">
            <button type="button" id="modal_productos_cancel" class="px-3 py-2 rounded-md border text-sm hover:bg-gray-50">Cancelar</button>
            <button type="submit" class="px-3 py-2 rounded-md bg-sky-600 text-white text-sm hover:bg-sky-700">Guardar cambios</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Nuevo Usuario -->
  <div id="modal_nuevo_producto" class="fixed inset-0 z-50 hidden" aria-hidden="true">
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="relative z-10 min-h-full flex items-center justify-center p-4">
      <div class="w-full max-w-2xl bg-white rounded-xl shadow-xl overflow-hidden modal-panel">
        <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50">
          <h3 class="text-lg font-semibold flex items-center gap-2">
            <i class="fa fa-box text-emerald-600"></i>
            Nuevo producto
          </h3>
          <button type="button" id="modal_nuevo_producto_close" class="h-9 w-9 inline-flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-500 hover:text-gray-700">
            <i class="fa fa-times"></i>
          </button>
        </div>
        <form id="form_nuevo_producto" class="p-5 space-y-5">
          <!-- Datos principales -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label for="new_nombre_producto" class="block text-xs text-gray-600 mb-1">Nombre</label>
              <input id="new_nombre_producto" name="nombre_producto" type="text" placeholder="Ej. Camiseta básica" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
            </div>
            <div>
              <label for="new_categoria_producto" class="block text-xs text-gray-600 mb-1">Categoría</label>
              <select id="new_categoria_producto" name="categoria_producto" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                <option value="">Cargando categorías...</option>
              </select>
            </div>
            <div>
              <label for="new_stock_producto" class="block text-xs text-gray-600 mb-1">Stock</label>
              <input id="new_stock_producto" name="stock_producto" type="number" min="0" step="1" placeholder="0" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
            </div>
            <div>
              <label for="new_costo_producto" class="block text-xs text-gray-600 mb-1">$ Costo</label>
              <input id="new_costo_producto" name="costo_producto" type="number" min="0" step="0.01" placeholder="0.00" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
            </div>
            <div>
              <label for="new_venta_producto" class="block text-xs text-gray-600 mb-1">$ Venta</label>
              <input id="new_venta_producto" name="venta_producto" type="number" min="0" step="0.01" placeholder="0.00" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
            </div>
          </div>

          <!-- Atributos adicionales -->
          <div class="border-t border-gray-100 pt-4">
            <div class="flex items-center justify-between mb-3">
              <h4 class="text-sm font-semibold text-gray-700">Atributos adicionales</h4>
              <button type="button" id="btn_add_attr" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md bg-emerald-600 text-white text-sm hover:bg-emerald-700">
                <i class="fa fa-plus"></i> Agregar
              </button>
            </div>
            <div id="additional_fields" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <!-- Campos dinámicos se insertarán aquí -->
            </div>
            <p class="mt-2 text-xs text-gray-500">Ejemplos: Talla, Color, Material. Cada atributo se enviará como extras[nombre_atributo].</p>
          </div>

          <div class="pt-2 flex items-center justify-end gap-2">
            <button type="button" id="modal_nuevo_producto_cancel" class="px-3 py-2 rounded-md border text-sm hover:bg-gray-50">Cancelar</button>
            <button type="submit" class="px-3 py-2 rounded-md bg-sky-600 text-white text-sm hover:bg-sky-700">Guardar producto</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div id="usuarios-card" class="bg-white rounded shadow p-4 sm:p-6 flex flex-col gap-4">
    <div class="usuarios-header flex items-center justify-between">
      <h2 class="text-lg sm:text-xl font-semibold flex items-center">
        <i class="fa fa-users text-green-600 mr-2"></i>Productos
      </h2>
      <div class="w-full max-w-xs">
        <input id="usuarios-search" type="search" placeholder="Buscar..." class="w-full text-sm border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500" />
      </div>
    </div>

    <?php if ($isLogged): ?>
      <div class="flex items-center justify-between">
        <div id="usuarios-total" class="text-xs sm:text-sm text-gray-500"></div>
      </div>

      <div class="flex-1 min-h-0">
        <div class="h-full w-full overflow-auto rounded border border-gray-200">
          <table id="tabla-usuarios" class="min-w-full divide-y divide-gray-200 table-auto">
            <thead class="bg-gray-50 sticky top-0 z-10">
              <tr>
                <th class="px-3 py-2 text-left text-[11px] sm:text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                <th data-sort="nombre" class="px-3 py-2 text-left text-[11px] sm:text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">Categoría<i class="fa fa-sort text-gray-400 ml-1"></i></th>
                <th class="px-3 py-2 text-left text-[11px] sm:text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                <th data-sort="estado" class="px-3 py-2 text-left text-[11px] sm:text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">Costo<i class="fa fa-sort text-gray-400 ml-1"></i></th>
                <th data-sort="estado" class="px-3 py-2 text-left text-[11px] sm:text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">Venta<i class="fa fa-sort text-gray-400 ml-1"></i></th>
                <th class="px-3 py-2 text-left text-[11px] sm:text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha de Creación</th>
                <th class="px-3 py-2 text-left text-[11px] sm:text-xs font-medium text-gray-500 uppercase tracking-wider">Última Modificación</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100"></tbody>
          </table>
        </div>
      </div>

      <div class="usuarios-toolbar-bottom pt-2 mt-1 flex flex-wrap items-center justify-between gap-3 sticky bottom-0 z-10 bg-white/95 backdrop-blur border-t border-gray-200">
        <div class="flex items-center gap-4 flex-1 min-w-0">
          <div class="flex items-center gap-2 flex-wrap">
            <label for="page-size" class="text-xs sm:text-sm text-gray-600">Mostrar:</label>
            <select id="page-size" class="text-xs sm:text-sm border border-gray-300 rounded px-2 py-1">
              <option value="5">5</option>
              <option value="15" selected>15</option>
              <option value="30">30</option>
              <option value="45">45</option>
              <option value="60">60</option>
              <option value="0">Todos</option>
            </select>
            <div id="paginacion-usuarios" class="flex items-center gap-1"></div>
          </div>
        </div>
        <div class="flex flex-wrap gap-2">
          <button id="btn_nuevo_producto" class="inline-flex items-center px-3 py-2 rounded bg-emerald-600 text-white hover:bg-emerald-700">
            <i class="fa fa-user-plus mr-2"></i>Nuevo Producto
          </button>
          <button id="btn_editar_producto" class="inline-flex items-center px-3 py-2 rounded bg-sky-600 text-white hover:bg-sky-700 disabled:opacity-70">
            <i class="fa fa-edit mr-2"></i>Editar
          </button>
          <button id="btn_eliminar_producto" class="inline-flex items-center px-3 py-2 rounded bg-red-600 text-white hover:bg-red-700 disabled:opacity-70">
            <i class="fa fa-trash mr-2"></i>Eliminar
          </button>
        </div>
      </div>
    <?php else: ?>
      <p class="text-gray-600">Inicia sesión para ver y gestionar usuarios del sistema.</p>
    <?php endif; ?>
  </div> 

  <!-- Scripts específicos de esta vista -->
  <script src="<?php echo APP_URL; ?>/app/views/contents/Productos/js/productos.js"></script>