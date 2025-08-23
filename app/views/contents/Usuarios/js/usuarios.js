if (typeof USU_ROWS === 'undefined') { var USU_ROWS = []; } else { USU_ROWS = USU_ROWS || []; }
if (typeof CURRENT_PAGE === 'undefined') { var CURRENT_PAGE = 1; }
if (typeof PAGE_SIZE === 'undefined') { var PAGE_SIZE = 15; } // 0 = all
if (typeof SEARCH_QUERY === 'undefined') { var SEARCH_QUERY = ''; }
if (typeof SORT_FIELD === 'undefined') { var SORT_FIELD = null; } // 'nombre' | 'estado'
if (typeof SORT_DIR === 'undefined') { var SORT_DIR = 'asc'; } // 'asc' | 'desc'
if (typeof SELECTED_ID === 'undefined') { var SELECTED_ID = null; }
// Caches en memoria
if (typeof ROLES_CACHE === 'undefined') { var ROLES_CACHE = null; } // array de roles
if (typeof USER_CACHE === 'undefined') { var USER_CACHE = {}; } // { [id_usuario]: usuarioDetallado }

$(document).ready(function(){
    // Inicializar selector de página si existe
    const $pageSize = $('#page-size');
    if ($pageSize.length){
        {
            const v = parseInt($pageSize.val(), 10);
            PAGE_SIZE = Number.isNaN(v) ? 15 : v;
        }

// Cargar roles en el select del modal
function cargarRolesEnSelect(selectedId, fallbackName){
    const $sel = $('#form-usuario-rol');
    if (!$sel.length) return;
    // Si ya tenemos cache, usarlo
    if (Array.isArray(ROLES_CACHE)){
        llenarSelectRoles($sel, ROLES_CACHE, selectedId, fallbackName);
        return;
    }
    // Estado de cargando y pedir al backend, luego cachear
    $sel.prop('disabled', true).empty().append('<option value="">Cargando roles...</option>');
    $.ajax({
        url: APP_URL + '/app/controllers/Usuario/usuarioController.php',
        type: 'POST',
        dataType: 'json',
        data: { action: 'roles' }
    }).done(function(resp){
        const ok = !!(resp && (resp.success === true || resp.success === 1));
        ROLES_CACHE = ok ? (resp.datos || resp.data || []) : [];
        llenarSelectRoles($sel, ROLES_CACHE, selectedId, fallbackName);
    }).fail(function(){
        $sel.empty().append('<option value="">Error al cargar roles</option>').prop('disabled', true);
        toastr.error('No se pudieron cargar los roles');
    });
}

function llenarSelectRoles($sel, rows, selectedId, fallbackName){
    $sel.empty();
    if (!Array.isArray(rows) || rows.length === 0){
        $sel.append('<option value="">Sin roles</option>');
        $sel.prop('disabled', true);
        return;
    }
    rows.forEach(function(r){
        const id = String(r.id_rol);
        const name = r.nom_rol || r.rol || ('Rol ' + id);
        const opt = $('<option>').val(id).text(name);
        $sel.append(opt);
    });
    $sel.prop('disabled', false);
    if (selectedId){
        $sel.val(String(selectedId));
        if ($sel.val() === null && fallbackName){
            const match = $sel.find('option').filter(function(){
                return $(this).text() === String(fallbackName);
            }).first();
            if (match.length) $sel.val(match.val());
        }
    }
}

function abrirModalUsuario(u){
    // Campos base
    $('#form-id_usuario').val(u.id_usuario || '');
    $('#form-usuario-username').val(u.usuario || '');
    $('#form-usuario-nombres').val(u.nombres || '');
    $('#form-usuario-apellidos').val(u.apellidos || '');
    // Limpiar password y estado del icono
    const $pass = $('#form-usuario-pass');
    if ($pass.length){
        $pass.val('');
        $pass.attr('type', 'password');
        $('#toggle-pass-icon').removeClass('fa-eye').addClass('fa-eye-slash');
    }
    // Cargar roles en el select y preseleccionar el rol del usuario
    const idRol = (u.id_rol !== undefined && u.id_rol !== null) ? String(u.id_rol) : '';
    const nomRol = u.nom_rol || u.rol || '';
    cargarRolesEnSelect(idRol, nomRol);
    // Mostrar modal
    $('#modal-usuario').removeClass('hidden');
}

function cerrarModalUsuario(){
    $('#modal-usuario').addClass('hidden');
}

function notificarDesdeRespuesta(resp, fallback){
    const ok = !!(resp && (resp.success === true || resp.success === 1));
    const msg = (resp && (resp.message || resp.mensaje)) || fallback || '';
    if (ok) toastr.success(msg || 'Operación realizada correctamente');
    else toastr.error(msg || 'Ocurrió un error');
}

// Toastr de carga persistente con spinner
function mostrarToastCargando(mensaje){
    const html = `<span><i class="fa fa-spinner fa-spin mr-2"></i>${mensaje || 'Espere por favor...'}</span>`;
    return toastr.info(html, '', {
        timeOut: 0,
        extendedTimeOut: 0,
        tapToDismiss: false,
        closeButton: false,
        progressBar: false,
        escapeHtml: false,
        newestOnTop: true
    });
}

// Obtener el nombre de rol por id desde el cache de roles
function obtenerNombreRolPorId(idRol){
    const sid = String(idRol);
    if (!Array.isArray(ROLES_CACHE)) return '';
    const r = ROLES_CACHE.find(x => String(x.id_rol) === sid);
    return r ? (r.nom_rol || r.rol || '') : '';
}
        $pageSize.on('change', function(){
            const v = parseInt($(this).val(), 10);
            PAGE_SIZE = Number.isNaN(v) ? 15 : v; // 0 (Todos) se respeta
            CURRENT_PAGE = 1;
            renderizarUsuarios();
        });
    }

    // Delegación de eventos para paginación
    $(document).on('click', '#paginacion-usuarios button[data-page]', function(){
        const p = parseInt($(this).attr('data-page'), 10);
        if (!isNaN(p)){
            CURRENT_PAGE = p;
            renderizarUsuarios();
        }
    });

    // Búsqueda en tiempo real
    $(document).on('input', '#usuarios-search', function(){
        SEARCH_QUERY = ($(this).val() || '').toString().trim().toLowerCase();
        CURRENT_PAGE = 1;
        renderizarUsuarios();
    });

    // Ordenar por columnas clickeables
    $(document).on('click', '#tabla-usuarios thead th[data-sort]', function(){
        const field = $(this).data('sort');
        if (!field) return;
        if (SORT_FIELD === field){
            SORT_DIR = (SORT_DIR === 'asc') ? 'desc' : 'asc';
        } else {
            SORT_FIELD = field;
            SORT_DIR = 'asc';
        }
        CURRENT_PAGE = 1;
        renderizarUsuarios();
    });

    // Selección de filas
    $(document).on('click', '#tabla-usuarios tbody tr.row-user', function(){
        const id = $(this).data('id');
        if (SELECTED_ID === id){
            // Toggle off
            SELECTED_ID = null;
            console.log('Selección eliminada.');
        } else {
            SELECTED_ID = id;
            const u = buscarUsuarioPorId(SELECTED_ID);
            if (u){
                console.log('Fila seleccionada:', { id: SELECTED_ID, usuario: u });
            } else {
                console.log('Fila seleccionada:', { id: SELECTED_ID });
            }
        }
        resaltarSeleccion();
        actualizarBotonesAccion();
    });

    // Acción de botones cuando no hay selección
    $(document).on('click', '#btn-editar, #btn-estado, #btn-eliminar', function(e){
        const $btn = $(this);
        const isDisabledVisually = $btn.data('disabled') === true || $btn.attr('aria-disabled') === 'true';
        if (!SELECTED_ID || isDisabledVisually){
            e.preventDefault();
            e.stopPropagation();
            let msg = 'Seleccione un usuario antes de continuar.';
            const id = $btn.attr('id');
            if (id === 'btn-editar') msg = 'Para editar debe seleccionar un usuario';
            else if (id === 'btn-estado') msg = 'Para cambiar estado debe seleccionar un usuario';
            else if (id === 'btn-eliminar') msg = 'Para eliminar debe seleccionar un usuario';
            toastr.info(msg);
            return false;
        }
        // Si hay selección, aquí continuarían las acciones reales
        return true;
    });

    // Abrir modal de edición con cache-first y refresh en background
    $(document).on('click', '#btn-editar', function(e){
        if (!SELECTED_ID) return;
        const id = String(SELECTED_ID);
        // Si hay cache detallado, abrir al instante
        if (USER_CACHE[id]){
            abrirModalUsuario(USER_CACHE[id]);
        } else {
            // Si no, intentar con la fila básica mientras llega el detalle
            const uLocal = buscarUsuarioPorId(id);
            if (uLocal){ abrirModalUsuario(uLocal); }
        }
        // Traer siempre del backend y actualizar cache/ UI
        $.ajax({
            url: APP_URL + '/app/controllers/Usuario/usuarioController.php',
            type: 'POST',
            dataType: 'json',
            data: { action: 'obtener_usuario', id_usuario: id }
        }).done(function(resp){
            if (resp && (resp.success === true || resp.success === 1)){
                const u = resp.data || resp.datos || resp.usuario || null;
                if (u){
                    USER_CACHE[id] = u;
                    // Si el modal sigue abierto para este usuario, refrescar campos principales (sin tocar password)
                    const currentId = $('#form-id_usuario').val();
                    if (String(currentId) === String(id)){
                        $('#form-usuario-username').val(u.usuario || '');
                        $('#form-usuario-nombres').val(u.nombres || '');
                        $('#form-usuario-apellidos').val(u.apellidos || '');
                        const idRol = (u.id_rol !== undefined && u.id_rol !== null) ? String(u.id_rol) : '';
                        const nomRol = u.nom_rol || u.rol || '';
                        cargarRolesEnSelect(idRol, nomRol);
                    }
                }
            }
        }).fail(function(){
            // Silencio: ya abrimos con cache/local si existía
        });
    });

    // Cambiar estado (toggle) del usuario seleccionado
    $(document).on('click', '#btn-estado', async function(){
        if (!SELECTED_ID) return;
        const u = buscarUsuarioPorId(SELECTED_ID);
        if (!u){ toastr.error('No se encontró el usuario seleccionado'); return; }
        const current = normalizarBool(u.estado);
        const next = !current;
        const accionText = next ? 'activar' : 'desactivar';
        const confirm = await Swal.fire({
            title: `¿${accionText.charAt(0).toUpperCase() + accionText.slice(1)} usuario?`,
            text: `Usuario: "${(u.usuario||'').toString()}" será ${next ? 'activado' : 'desactivado'}.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: `Sí, ${accionText}`,
            cancelButtonText: 'Cancelar'
        });
        if (!confirm.isConfirmed) return;
        const tCarga = mostrarToastCargando('Actualizando estado, espere por favor...');
        
        $.ajax({
            url: APP_URL + '/app/controllers/Usuario/usuarioController.php',
            type: 'POST',
            dataType: 'json',
            data: { action: 'cambiar_estado', id_usuario: SELECTED_ID, estado: next }
        }).done(function(resp){
            if (tCarga) toastr.clear(tCarga);
            notificarDesdeRespuesta(resp, `Usuario ${(u.usuario||'')} ${next ? 'activado' : 'desactivado'}`);
            if (resp && (resp.success === true || resp.success === 1)){
                // Actualizar estado en memoria y re-renderizar
                const idx = Array.isArray(USU_ROWS) ? USU_ROWS.findIndex(x => String(x.id_usuario) === String(SELECTED_ID)) : -1;
                if (idx !== -1){
                    const old = USU_ROWS[idx];
                    USU_ROWS[idx] = Object.assign({}, old, { estado: next, ult_modificacion: new Date().toISOString() });
                }
                renderizarUsuarios();
            }
        }).fail(function(){
            if (tCarga) toastr.clear(tCarga);
            toastr.error('Error de conexión al cambiar estado');
        });
    });


    // Confirmar eliminar usuario
    $(document).on('click', '#btn-eliminar', async function(){
        if (!SELECTED_ID) return;
        const u = buscarUsuarioPorId(SELECTED_ID);
        if (!u){ toastr.error('No se encontró el usuario seleccionado'); return; }
        const res = await Swal.fire({
            title: '¿Eliminar usuario?',
            text: `Esta acción no se puede deshacer. Usuario: "${(u.usuario||'').toString()}"`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        });
        if (!res.isConfirmed) return;
        const tCarga = mostrarToastCargando('Eliminando usuario, espere por favor...');
        $.ajax({
            url: APP_URL + '/app/controllers/Usuario/usuarioController.php',
            type: 'POST',
            dataType: 'json',
            data: { action: 'eliminar_usuario', id_usuario: SELECTED_ID }
        }).done(function(resp){
            if (tCarga) toastr.clear(tCarga);
            notificarDesdeRespuesta(resp, 'Usuario eliminado');
            if (resp && (resp.success === true || resp.success === 1)){
                // Eliminar en memoria y re-renderizar sin pedir al servidor
                const idx = Array.isArray(USU_ROWS) ? USU_ROWS.findIndex(x => String(x.id_usuario) === String(SELECTED_ID)) : -1;
                if (idx !== -1){ USU_ROWS.splice(idx, 1); }
                SELECTED_ID = null;
                renderizarUsuarios();
            }
        }).fail(function(){
            if (tCarga) toastr.clear(tCarga);
            toastr.error('Error de conexión al eliminar usuario');
        });
    });

    // Cerrar modal
    $(document).on('click', '#modal-usuario-close, #modal-usuario-cancel', function(){
        cerrarModalUsuario();
    });

    // Toggle visibilidad contraseña
    $(document).on('click', '#toggle-pass', function(){
        const $pass = $('#form-usuario-pass');
        if (!$pass.length) return;
        const isPassword = $pass.attr('type') === 'password';
        $pass.attr('type', isPassword ? 'text' : 'password');
        const $icon = $('#toggle-pass-icon');
        if (isPassword){
            $icon.removeClass('fa-eye-slash').addClass('fa-eye');
        } else {
            $icon.removeClass('fa-eye').addClass('fa-eye-slash');
        }
    });

    // Guardar cambios en modal
    $(document).on('submit', '#form-usuario', function(e){
        e.preventDefault();
        const payload = {
            action: 'actualizar_usuario',
            id_usuario: $('#form-id_usuario').val(),
            usuario: $('#form-usuario-username').val(),
            nombres: $('#form-usuario-nombres').val(),
            apellidos: $('#form-usuario-apellidos').val(),
            id_rol: $('#form-usuario-rol').val()
        };
        // Enviar contraseña solo si se ingresó una nueva
        const passVal = ($('#form-usuario-pass').val() || '').toString();
        if (passVal.trim() !== ''){
            payload.contrasenia = passVal;
        }
        const tCarga = mostrarToastCargando('Guardando cambios, espere por favor...');
        $.ajax({
            url: APP_URL + '/app/controllers/Usuario/usuarioController.php',
            type: 'POST',
            dataType: 'json',
            data: payload
        }).done(function(resp){
            if (tCarga) toastr.clear(tCarga);
            notificarDesdeRespuesta(resp, 'Usuario actualizado');
            if (resp && (resp.success === true || resp.success === 1)){
                const id = String(payload.id_usuario);
                // Actualizar cache detallado
                if (!USER_CACHE[id]) USER_CACHE[id] = {};
                USER_CACHE[id] = Object.assign({}, USER_CACHE[id], {
                    id_usuario: id,
                    usuario: payload.usuario,
                    nombres: payload.nombres,
                    apellidos: payload.apellidos,
                    id_rol: payload.id_rol
                });
                // Actualizar fila en listado si existe
                const idx = Array.isArray(USU_ROWS) ? USU_ROWS.findIndex(x => String(x.id_usuario) === id) : -1;
                if (idx !== -1){
                    const old = USU_ROWS[idx];
                    const nomRol = obtenerNombreRolPorId(payload.id_rol) || old.nom_rol || old.rol || '';
                    USU_ROWS[idx] = Object.assign({}, old, {
                        usuario: payload.usuario,
                        nombres: payload.nombres,
                        apellidos: payload.apellidos,
                        id_rol: payload.id_rol,
                        nom_rol: nomRol,
                        ult_modificacion: new Date().toISOString()
                    });
                }
                renderizarUsuarios();
                cerrarModalUsuario();
            }
        }).fail(function(){
            if (tCarga) toastr.clear(tCarga);
            toastr.error('Error de conexión al actualizar usuario');
        });
    });

    // -----------------------------
    // Modal: Nuevo Usuario
    // -----------------------------
    function cargarRolesEnSelectNuevo(){
        const $sel = $('#new-usuario-rol');
        if (!$sel.length) return;
        if (Array.isArray(ROLES_CACHE)){
            llenarSelectRoles($sel, ROLES_CACHE);
            return;
        }
        $sel.prop('disabled', true).empty().append('<option value="">Cargando roles...</option>');
        $.ajax({
            url: APP_URL + '/app/controllers/Usuario/usuarioController.php',
            type: 'POST',
            dataType: 'json',
            data: { action: 'roles' }
        }).done(function(resp){
            const ok = !!(resp && (resp.success === true || resp.success === 1));
            ROLES_CACHE = ok ? (resp.datos || resp.data || []) : [];
            llenarSelectRoles($sel, ROLES_CACHE);
        }).fail(function(){
            $sel.empty().append('<option value="">Error al cargar roles</option>').prop('disabled', true);
            toastr.error('No se pudieron cargar los roles');
        });
    }

    function abrirModalNuevoUsuario(){
        $('#new-usuario-username').val('');
        $('#new-usuario-nombres').val('');
        $('#new-usuario-apellidos').val('');
        const $pass = $('#new-usuario-pass');
        if ($pass.length){
            $pass.val('');
            $pass.attr('type', 'password');
            $('#toggle-pass-icon-new').removeClass('fa-eye').addClass('fa-eye-slash');
        }
        cargarRolesEnSelectNuevo();
        $('#modal-nuevo-usuario').removeClass('hidden');
    }

    function cerrarModalNuevoUsuario(){
        $('#modal-nuevo-usuario').addClass('hidden');
    }

    // Abrir desde botón de toolbar existente #btn-nuevo
    $(document).on('click', '#btn-nuevo', function(){
        abrirModalNuevoUsuario();
    });

    // Cerrar modal nuevo usuario
    $(document).on('click', '#modal-nuevo-close, #modal-nuevo-cancel', function(){
        cerrarModalNuevoUsuario();
    });

    // Toggle visibilidad contraseña (nuevo)
    $(document).on('click', '#toggle-pass-new', function(){
        const $pass = $('#new-usuario-pass');
        if (!$pass.length) return;
        const isPassword = $pass.attr('type') === 'password';
        $pass.attr('type', isPassword ? 'text' : 'password');
        const $icon = $('#toggle-pass-icon-new');
        if (isPassword){ $icon.removeClass('fa-eye-slash').addClass('fa-eye'); }
        else { $icon.removeClass('fa-eye').addClass('fa-eye-slash'); }
    });

    // Enviar formulario de creación
    $(document).on('submit', '#form-nuevo-usuario', function(e){
        e.preventDefault();
        const payload = {
            action: 'crear_usuario',
            usuario: ($('#new-usuario-username').val() || '').toString(),
            nombres: ($('#new-usuario-nombres').val() || '').toString(),
            apellidos: ($('#new-usuario-apellidos').val() || '').toString(),
            contrasenia: ($('#new-usuario-pass').val() || '').toString(),
            id_rol: ($('#new-usuario-rol').val() || '').toString()
        };
        const tCarga = mostrarToastCargando('Creando usuario, espere por favor...');
        $.ajax({
            url: APP_URL + '/app/controllers/Usuario/usuarioController.php',
            type: 'POST',
            dataType: 'json',
            data: payload
        }).done(function(resp){
            if (tCarga) toastr.clear(tCarga);
            notificarDesdeRespuesta(resp, 'Usuario creado');
            if (resp && (resp.success === true || resp.success === 1)){
                obtener_usuarios();
                cerrarModalNuevoUsuario();
            }
        }).fail(function(){
            if (tCarga) toastr.clear(tCarga);
            toastr.error('Error de conexión al crear usuario');
        });
    });

    // Construir la tabla y UI (totales/paginador) incluso sin datos iniciales
    renderizarUsuarios();
    obtener_usuarios();
});

function resaltarSeleccion(){
    const $rows = $('#tabla-usuarios tbody tr.row-user');
    // ... (rest of the code remains the same)
    // limpiar estilos previos
    $rows.removeClass('row-selected bg-emerald-100 border-l-4 border-emerald-500 bg-blue-100 border-l-8 border-blue-500');
    $rows.removeClass('hover:bg-emerald-50').addClass('hover:bg-blue-50');
    $rows.find('td').removeClass('font-semibold text-blue-900');
    if (SELECTED_ID != null){
        const $sel = $rows.filter(function(){ return String($(this).data('id')) === String(SELECTED_ID); });
        $sel.addClass('row-selected bg-blue-100 border-l-8 border-blue-500');
        $sel.find('td').addClass('font-semibold text-blue-900');
    }
}

function actualizarBotonesAccion(){
    const hasSel = SELECTED_ID != null;
    const $btnEditar = $('#btn-editar');
    const $btnEstado = $('#btn-estado');
    const $btnEliminar = $('#btn-eliminar');
    const toggle = ($btn, titleWhenDisabled)=>{
        if (!$btn.length) return;
        if (hasSel){
            $btn.removeClass('opacity-50 cursor-not-allowed').attr('aria-disabled', 'false').data('disabled', false).removeAttr('title');
        } else {
            $btn.addClass('opacity-50 cursor-not-allowed').attr('aria-disabled', 'true').data('disabled', true).attr('title', titleWhenDisabled || 'Seleccione un usuario');
        }
    };
    toggle($btnEditar, 'Para editar debe seleccionar un usuario');
    toggle($btnEstado, 'Para cambiar estado debe seleccionar un usuario');
    toggle($btnEliminar, 'Para eliminar debe seleccionar un usuario');
}

function actualizarIconosOrden(){
    const $ths = $('#tabla-usuarios thead th[data-sort]');
    $ths.each(function(){
        const field = $(this).data('sort');
        const $icon = $(this).find('i.fa');
        if (!$icon.length) return;
        if (SORT_FIELD !== field){
            $icon.removeClass('fa-sort-up fa-sort-down').addClass('fa-sort').removeClass('text-gray-600').addClass('text-gray-400');
        } else {
            $icon.removeClass('fa-sort');
            if (SORT_DIR === 'asc'){
                $icon.removeClass('fa-sort-down').addClass('fa-sort-up').removeClass('text-gray-400').addClass('text-gray-600');
            } else {
                $icon.removeClass('fa-sort-up').addClass('fa-sort-down').removeClass('text-gray-400').addClass('text-gray-600');
            }
        }
    });
}

function buscarUsuarioPorId(id){
    const sid = String(id);
    if (!Array.isArray(USU_ROWS)) return null;
    return USU_ROWS.find(u => String(u.id_usuario) === sid) || null;
}

function obtener_usuarios(){
    mostrarCargaTabla();
    $.ajax({
        url: APP_URL + '/app/controllers/Usuario/usuarioController.php',
        type: 'POST',
        data: { action: 'obtener_usuarios' },
        dataType: 'json'
    })
    .done(function(resp){
        if(resp && (resp.success || resp.success === true)){
            USU_ROWS = resp.datos || resp.data || [];
            CURRENT_PAGE = 1;
            renderizarUsuarios();
            ocultarCargaTabla();
        } else {
            const msg = (resp && resp.message) ? resp.message : 'Error al cargar usuarios';
            toastr.error(msg);
            mostrarErrorTabla(msg);
            ocultarCargaTabla();
        }
    })
    .fail(function() {
        const msg = 'Error de conexión al cargar usuarios';
        toastr.error(msg);
        mostrarErrorTabla(msg);
        ocultarCargaTabla();
    });
}

function renderizarTabla(usuarios){
    const $tbody = '#tabla-usuarios tbody';
    const $tb = $('#tabla-usuarios tbody');
    if (!$tb.length) return;
    $tb.empty();

    if (!Array.isArray(usuarios) || usuarios.length === 0){
        $tb.append('<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No hay usuarios registrados</td></tr>');
        return;
    }

    usuarios.forEach(function(us, idx){
        const id = escapar(us.id_usuario);
        const usuario = escapar(us.usuario);
        const nombreMatch = escapar(((us.nombres||'') + ' ' + (us.apellidos||'')).trim());
        const nomRol = escapar(us.nom_rol || us.rol || '');
        const estadoBool = normalizarBool(us.estado);
        const estadoText = estadoBool ? 'Activo' : 'Inactivo';
        const fechaCreacion = formatearFecha(us.fecha_creacion || us.fecha || '');
        const fechaModificacion = formatearFecha(us.ult_modificacion || '');

        const evenClass = (idx % 2 === 1) ? 'bg-gray-50' : '';
        const selectedClass = (String(SELECTED_ID) === String(id)) ? 'row-selected bg-blue-100 border-l-8 border-blue-500' : '';
        const tr = `
            <tr class="row-user ${evenClass} ${selectedClass} cursor-pointer hover:bg-blue-50" data-id="${id}">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${usuario}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${nombreMatch}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${nomRol}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm ${estadoBool ? 'text-green-700' : 'text-red-700'}">${estadoText}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${fechaCreacion}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${fechaModificacion}</td>
            </tr>`;
        $tb.append(tr);
    });

    // Aplicar highlight a la selección vigente
    resaltarSeleccion();
}

function mostrarCargaTabla(){
    const $table = $('#tabla-usuarios');
    if (!$table.length) return;
    const $scroll = $table.closest('.overflow-auto');
    if ($scroll.length){
        if (!$scroll.find('.usuarios-loading').length){
            // Ensure positioning for overlay
            if ($scroll.css('position') === 'static') { $scroll.css('position', 'relative'); }
            $scroll.append(
                '<div class="usuarios-loading absolute inset-0 flex items-center justify-center bg-white/70 backdrop-blur-sm z-20">'+
                  '<div class="text-gray-600 text-sm sm:text-base">'+
                    '<i class="fa fa-spinner fa-spin mr-2"></i>'+
                    '<span>Cargando usuarios...</span>'+
                  '</div>'+
                '</div>'
            );
        }
    }
    // No vaciar el tbody para mantener la estructura visible mientras carga
}

function ocultarCargaTabla(){
    const $table = $('#tabla-usuarios');
    if (!$table.length) return;
    const $scroll = $table.closest('.overflow-auto');
    $scroll.find('.usuarios-loading').remove();
}

function mostrarErrorTabla(message){
    const $tbody = $('#tabla-usuarios tbody');
    if (!$tbody.length) return;
    $tbody.empty().append(
        '<tr>'+
          '<td colspan="6" class="px-6 py-6 text-center text-red-600">'+
            '<i class="fa fa-circle-exclamation mr-2"></i>' + escapar(message) +
          '</td>'+
        '</tr>'
    );
}

function renderizarUsuarios(){
    // 1) Filtrar por búsqueda
    const rows = Array.isArray(USU_ROWS) ? USU_ROWS.slice() : [];
    const filtered = (!SEARCH_QUERY)
        ? rows
        : rows.filter(us => {
            const usuario = (us.usuario || '').toString().toLowerCase();
            const nombre = (((us.nombres||'') + ' ' + (us.apellidos||'')).trim()).toLowerCase();
            const rol = (us.nom_rol || us.rol || '').toString().toLowerCase();
            const estadoBool = normalizarBool(us.estado);
            const estado = (estadoBool ? 'activo' : 'inactivo');
            return usuario.includes(SEARCH_QUERY)
                || nombre.includes(SEARCH_QUERY)
                || rol.includes(SEARCH_QUERY)
                || estado.includes(SEARCH_QUERY);
        });

    // 2) Ordenar si aplica
    if (SORT_FIELD){
        const dir = (SORT_DIR === 'desc') ? -1 : 1;
        filtered.sort((a, b) => {
            let va = '', vb = '';
            if (SORT_FIELD === 'nombre'){
                va = (((a.nombres||'') + ' ' + (a.apellidos||'')).trim()).toLowerCase();
                vb = (((b.nombres||'') + ' ' + (b.apellidos||'')).trim()).toLowerCase();
            } else if (SORT_FIELD === 'estado'){
                // Activo primero si asc (activo < inactivo), invertido si desc
                const ea = normalizarBool(a.estado) ? 0 : 1;
                const eb = normalizarBool(b.estado) ? 0 : 1;
                if (ea < eb) return -1 * dir;
                if (ea > eb) return 1 * dir;
                // tie-breaker por nombre
                va = (((a.nombres||'') + ' ' + (a.apellidos||'')).trim()).toLowerCase();
                vb = (((b.nombres||'') + ' ' + (b.apellidos||'')).trim()).toLowerCase();
                if (va < vb) return -1;
                if (va > vb) return 1;
                return 0;
            }
            if (va < vb) return -1 * dir;
            if (va > vb) return 1 * dir;
            return 0;
        });
    }

    // 3) Paginación
    const total = filtered.length;
    const $total = $('#usuarios-total');
    const $pager = $('#paginacion-usuarios');

    if (total === 0){
        if ($total.length) $total.text('No hay usuarios');
        if ($pager.length) $pager.empty();
        renderizarTabla([]);
        actualizarIconosOrden();
        SELECTED_ID = null;
        actualizarBotonesAccion();
        return;
    }

    if (PAGE_SIZE === 0){
        // Mostrar todos
        renderizarTabla(filtered);
        if ($total.length) $total.text(`Mostrando 1–${total} de ${total} usuarios`);
        if ($pager.length) $pager.empty();
        actualizarIconosOrden();
        // Mantener selección si aún existe en datos filtrados; si no, limpiar
        if (!filtered.some(u => String(u.id_usuario) === String(SELECTED_ID))){
            SELECTED_ID = null;
        }
        actualizarBotonesAccion();
        return;
    }

    const totalPages = Math.max(1, Math.ceil(total / PAGE_SIZE));
    if (CURRENT_PAGE > totalPages) CURRENT_PAGE = totalPages;
    if (CURRENT_PAGE < 1) CURRENT_PAGE = 1;

    const startIdx = (CURRENT_PAGE - 1) * PAGE_SIZE;
    const endIdx = Math.min(startIdx + PAGE_SIZE, total);
    const slice = filtered.slice(startIdx, endIdx);

    renderizarTabla(slice);

    if ($total.length) $total.text(`Mostrando ${startIdx + 1}–${endIdx} de ${total} usuarios`);
    if ($pager.length) construirPaginador($pager, totalPages, CURRENT_PAGE);
    actualizarIconosOrden();

    // Mantener selección si el id sigue visible en esta página; si no, limpiar visual y botones
    if (!filtered.some(u => String(u.id_usuario) === String(SELECTED_ID))){
        SELECTED_ID = null;
    }
    actualizarBotonesAccion();
}

function construirPaginador($container, totalPages, current){
    $container.empty();
    if (totalPages <= 1){
        return;
    }
    // Primero y Anterior
    $container.append(`<button type="button" class="px-2 py-1 text-xs border rounded ${current === 1 ? 'opacity-50 cursor-not-allowed' : ''}" data-page="1" ${current === 1 ? 'disabled' : ''}>«</button>`);
    $container.append(`<button type="button" class="px-2 py-1 text-xs border rounded ${current === 1 ? 'opacity-50 cursor-not-allowed' : ''}" data-page="${Math.max(1, current-1)}" ${current === 1 ? 'disabled' : ''}>‹</button>`);

    // Tres números contiguos: ej. 1 2 3, 2 3 4, etc.
    let start = Math.max(1, current - 1);
    let end = start + 2;
    if (end > totalPages){
        end = totalPages;
        start = Math.max(1, end - 2);
    }
    for (let p = start; p <= end; p++){
        const active = p === current;
        $container.append(`<button type="button" class="px-2 py-1 text-xs border rounded ${active ? 'bg-gray-200 font-semibold' : ''}" data-page="${p}">${p}</button>`);
    }

    // Siguiente y Último
    $container.append(`<button type="button" class="px-2 py-1 text-xs border rounded ${current === totalPages ? 'opacity-50 cursor-not-allowed' : ''}" data-page="${Math.min(totalPages, current+1)}" ${current === totalPages ? 'disabled' : ''}>›</button>`);
    $container.append(`<button type="button" class="px-2 py-1 text-xs border rounded ${current === totalPages ? 'opacity-50 cursor-not-allowed' : ''}" data-page="${totalPages}" ${current === totalPages ? 'disabled' : ''}>»</button>`);
}

function mostrarCargaTabla(){
    const $table = $('#tabla-usuarios');
    if (!$table.length) return;
    const $scroll = $table.closest('.overflow-auto');
    if ($scroll.length){
        if (!$scroll.find('.usuarios-loading').length){
            // Ensure positioning for overlay
            if ($scroll.css('position') === 'static') { $scroll.css('position', 'relative'); }
            $scroll.append(
                '<div class="usuarios-loading absolute inset-0 flex items-center justify-center bg-white/70 backdrop-blur-sm z-20">'+
                  '<div class="text-gray-600 text-sm sm:text-base">'+
                    '<i class="fa fa-spinner fa-spin mr-2"></i>'+
                    '<span>Cargando usuarios...</span>'+
                  '</div>'+
                '</div>'
            );
        }
    }
    // Clear tbody content to avoid stale rows while loading
    const $tbody = $table.find('tbody');
    if ($tbody.length) { $tbody.empty(); }
}

function ocultarCargaTabla(){
    const $table = $('#tabla-usuarios');
    if (!$table.length) return;
    const $scroll = $table.closest('.overflow-auto');
    $scroll.find('.usuarios-loading').remove();
}

function mostrarErrorTabla(message){
    const $tbody = $('#tabla-usuarios tbody');
    if (!$tbody.length) return;
    $tbody.empty().append(
        '<tr>'+
          '<td colspan="6" class="px-6 py-6 text-center text-red-600">'+
            '<i class="fa fa-circle-exclamation mr-2"></i>' + escapar(message) +
          '</td>'+
        '</tr>'
    );
}

function escapar(v){
    if (v === null || v === undefined) return '';
    return String(v)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function normalizarBool(val){
    if (val === null || val === undefined) return false;
    if (typeof val === 'boolean') return val;
    // Números
    if (val === 1 || val === 1.0) return true;
    if (val === 0 || val === 0.0) return false;
    // Cadenas
    const s = String(val).trim().toLowerCase();
    // Verdadero
    if ([
        '1','t','true','si','sí','on','yes','y','s','activo','act','habilitado','enabled'
    ].includes(s)) return true;
    // Falso
    if ([
        '0','f','false','no','off','inactivo','desactivado','disabled'
    ].includes(s)) return false;
}

function formatearFecha(v){
if (!v) return '';
const d = new Date(v);
if (isNaN(d.getTime())) return escapar(v);
const dd = String(d.getDate()).padStart(2,'0');
const mm = String(d.getMonth()+1).padStart(2,'0');
const yyyy = d.getFullYear();
const HH = String(d.getHours()).padStart(2,'0');
const MM = String(d.getMinutes()).padStart(2,'0');
return `${dd}/${mm}/${yyyy} ${HH}:${MM}`;
}