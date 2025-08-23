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
  <div id="modal-usuario" class="fixed inset-0 z-50 hidden" aria-hidden="true">
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="relative z-10 min-h-full flex items-center justify-center p-4">
      <div class="w-full max-w-xl bg-white rounded-xl shadow-xl overflow-hidden modal-panel">
        <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50">
          <h3 class="text-lg font-semibold flex items-center gap-2">
            <i class="fa fa-user text-emerald-600"></i>
            Editar usuario
          </h3>
          <button type="button" id="modal-usuario-close" class="h-9 w-9 inline-flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-500 hover:text-gray-700">
            <i class="fa fa-times"></i>
          </button>
        </div>
        <form id="form-usuario" class="p-5 space-y-4 max-h-[80vh] overflow-y-auto">
          <input type="hidden" id="form-id_usuario" name="id_usuario">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label for="form-usuario-username" class="block text-xs text-gray-600 mb-1">Usuario</label>
              <input id="form-usuario-username" name="usuario" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
            </div>
            <div>
              <label for="form-usuario-nombres" class="block text-xs text-gray-600 mb-1">Nombres</label>
              <input id="form-usuario-nombres" name="nombres" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
            </div>
            <div>
              <label for="form-usuario-apellidos" class="block text-xs text-gray-600 mb-1">Apellidos</label>
              <input id="form-usuario-apellidos" name="apellidos" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
            </div>
            <div>
              <label for="form-usuario-rol" class="block text-xs text-gray-600 mb-1">Rol</label>
              <select id="form-usuario-rol" name="id_rol" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                <option value="">Cargando roles...</option>
              </select>
            </div>
            <div class="sm:col-span-2">
              <label for="form-usuario-pass" class="block text-xs text-gray-600 mb-1">Nueva contraseña (opcional)</label>
              <div class="relative">
                <input id="form-usuario-pass" name="contrasenia" type="password" class="w-full border border-gray-300 rounded-md px-3 py-2 pr-10 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" placeholder="Dejar vacío para no cambiar" />
                <button type="button" id="toggle-pass" class="absolute inset-y-0 right-2 px-2 text-gray-500 hover:text-gray-700">
                  <i id="toggle-pass-icon" class="fa fa-eye-slash"></i>
                </button>
              </div>
            </div>
          </div>
          <div class="pt-2 flex items-center justify-end gap-2">
            <button type="button" id="modal-usuario-cancel" class="px-3 py-2 rounded-md border text-sm hover:bg-gray-50">Cancelar</button>
            <button type="submit" class="px-3 py-2 rounded-md bg-emerald-600 text-white text-sm hover:bg-emerald-700">Guardar cambios</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Nuevo Usuario -->
  <div id="modal-nuevo-usuario" class="fixed inset-0 z-50 hidden" aria-hidden="true">
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="relative z-10 min-h-full flex items-center justify-center p-4">
      <div class="w-full max-w-xl bg-white rounded-xl shadow-xl overflow-hidden modal-panel">
        <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50">
          <h3 class="text-lg font-semibold flex items-center gap-2">
            <i class="fa fa-user-plus text-emerald-600"></i>
            Nuevo usuario
          </h3>
          <button type="button" id="modal-nuevo-close" class="h-9 w-9 inline-flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-500 hover:text-gray-700">
            <i class="fa fa-times"></i>
          </button>
        </div>
        <form id="form-nuevo-usuario" class="p-5 space-y-4 max-h-[80vh] overflow-y-auto">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label for="new-usuario-username" class="block text-xs text-gray-600 mb-1">Usuario</label>
              <input id="new-usuario-username" name="usuario" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" required />
            </div>
            <div>
              <label for="new-usuario-nombres" class="block text-xs text-gray-600 mb-1">Nombres</label>
              <input id="new-usuario-nombres" name="nombres" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" required />
            </div>
            <div>
              <label for="new-usuario-apellidos" class="block text-xs text-gray-600 mb-1">Apellidos</label>
              <input id="new-usuario-apellidos" name="apellidos" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" required />
            </div>
            <div>
              <label for="new-usuario-rol" class="block text-xs text-gray-600 mb-1">Rol</label>
              <select id="new-usuario-rol" name="id_rol" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" required>
                <option value="">Cargando roles...</option>
              </select>
            </div>
            <div class="sm:col-span-2">
              <label for="new-usuario-pass" class="block text-xs text-gray-600 mb-1">Contraseña</label>
              <div class="relative">
                <input id="new-usuario-pass" name="contrasenia" type="password" class="w-full border border-gray-300 rounded-md px-3 py-2 pr-10 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" required />
                <button type="button" id="toggle-pass-new" class="absolute inset-y-0 right-2 px-2 text-gray-500 hover:text-gray-700">
                  <i id="toggle-pass-icon-new" class="fa fa-eye-slash"></i>
                </button>
              </div>
            </div>
          </div>
          <div class="pt-2 flex items-center justify-end gap-2">
            <button type="button" id="modal-nuevo-cancel" class="px-3 py-2 rounded-md border text-sm hover:bg-gray-50">Cancelar</button>
            <button type="submit" class="px-3 py-2 rounded-md bg-emerald-600 text-white text-sm hover:bg-emerald-700">Crear</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div id="usuarios-card" class="bg-white rounded shadow p-4 sm:p-6 flex flex-col gap-4">
    <div class="usuarios-header flex items-center justify-between">
      <h2 class="text-lg sm:text-xl font-semibold flex items-center">
        <i class="fa fa-users text-green-600 mr-2"></i>Usuarios
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
                <th class="px-3 py-2 text-left text-[11px] sm:text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                <th data-sort="nombre" class="px-3 py-2 text-left text-[11px] sm:text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">Nombre <i class="fa fa-sort text-gray-400 ml-1"></i></th>
                <th class="px-3 py-2 text-left text-[11px] sm:text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                <th data-sort="estado" class="px-3 py-2 text-left text-[11px] sm:text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">Estado <i class="fa fa-sort text-gray-400 ml-1"></i></th>
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
          <button id="btn-nuevo" class="inline-flex items-center px-3 py-2 rounded bg-emerald-600 text-white hover:bg-emerald-700">
            <i class="fa fa-user-plus mr-2"></i>Nuevo Usuario
          </button>
          <button id="btn-editar" class="inline-flex items-center px-3 py-2 rounded bg-sky-600 text-white hover:bg-sky-700 disabled:opacity-70">
            <i class="fa fa-edit mr-2"></i>Editar
          </button>
          <button id="btn-estado" class="inline-flex items-center px-3 py-2 rounded bg-amber-600 text-white hover:bg-amber-700 disabled:opacity-70">
            <i class="fa fa-power-off mr-2"></i>Cambiar estado
          </button>
          <button id="btn-eliminar" class="inline-flex items-center px-3 py-2 rounded bg-red-600 text-white hover:bg-red-700 disabled:opacity-70">
            <i class="fa fa-trash mr-2"></i>Eliminar
          </button>
        </div>
      </div>
    <?php else: ?>
      <p class="text-gray-600">Inicia sesión para ver y gestionar usuarios del sistema.</p>
    <?php endif; ?>
  </div> 