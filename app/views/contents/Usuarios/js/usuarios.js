// Función principal que se puede llamar múltiples veces
function initUsuarios() {
    // Variables globales
    let isEditing = false;
    let editingId = null;
    
    // Limpiar eventos previos para evitar duplicados
    $('#btn-nuevo-usuario').off('click');
    $('#btn-cerrar-modal, #btn-cancelar').off('click');
    $('#modal-usuario').off('click');
    $('#form-usuario').off('submit');
    
    // Cargar usuarios al iniciar
    cargarUsuarios();
    
    // Event listeners
    $('#btn-nuevo-usuario').click(function() {
        abrirModal();
    });
    
    $('#btn-cerrar-modal, #btn-cancelar').click(function() {
        cerrarModal();
    });
    
    $('#modal-usuario').click(function(e) {
        if (e.target === this) {
            cerrarModal();
        }
    });
    
    $('#form-usuario').submit(function(e) {
        e.preventDefault();
        guardarUsuario();
    });
    
    // Funciones principales
    function cargarUsuarios() {
        $.ajax({
            url: APP_URL + '/app/ajax/usuarios.php',
            type: 'POST',
            data: { action: 'listar' },
            dataType: 'json'
        })
        .done(function(resp) {
            if (resp.success) {
                renderizarTabla(resp.data);
            } else {
                toastr.error(resp.message || 'Error al cargar usuarios');
            }
        })
        .fail(function() {
            toastr.error('Error de conexión al cargar usuarios');
        });
    }
    
    function renderizarTabla(usuarios) {
        const tbody = $('#usuarios-tbody');
        tbody.empty();
        
        if (!usuarios || usuarios.length === 0) {
            tbody.append('<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No hay usuarios registrados</td></tr>');
            return;
        }
        
        usuarios.forEach(function(usuario) {
            const rol = getRolText(usuario.rol);
            const fecha = new Date(usuario.fecha_creacion).toLocaleDateString();
            
            const row = `
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${usuario.id_usuario}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${escapeHtml(usuario.usuario)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${escapeHtml(usuario.nombres + ' ' + usuario.apellidos)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${rol}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${fecha}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="editarUsuario(${usuario.id_usuario})" class="text-blue-600 hover:text-blue-900 mr-3">
                            <i class="fa fa-edit mr-1"></i>Editar
                        </button>
                        <button onclick="eliminarUsuario(${usuario.id_usuario}, '${escapeHtml(usuario.usuario)}')" class="text-red-600 hover:text-red-900">
                            <i class="fa fa-trash mr-1"></i>Eliminar
                        </button>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
    }
    
    function abrirModal(usuario = null) {
        isEditing = !!usuario;
        editingId = usuario ? usuario.id_usuario : null;
        
        $('#modal-titulo').text(isEditing ? 'Editar Usuario' : 'Nuevo Usuario');
        
        if (isEditing) {
            $('#usuario-id').val(usuario.id_usuario);
            $('#usuario-nombres').val(usuario.nombres);
            $('#usuario-apellidos').val(usuario.apellidos);
            $('#usuario-usuario').val(usuario.usuario);
            $('#usuario-rol').val(usuario.rol || '');
            $('#usuario-contrasenia').removeAttr('required');
            $('#usuario-confirm-contrasenia').removeAttr('required');
        } else {
            $('#form-usuario')[0].reset();
            $('#usuario-contrasenia').attr('required', true);
            $('#usuario-confirm-contrasenia').attr('required', true);
        }
        
        $('#modal-usuario').removeClass('hidden');
    }
    
    function cerrarModal() {
        $('#modal-usuario').addClass('hidden');
        $('#form-usuario')[0].reset();
        isEditing = false;
        editingId = null;
    }
    
    function guardarUsuario() {
        const formData = $('#form-usuario').serialize();
        const action = isEditing ? 'actualizar' : 'crear';
        
        $.ajax({
            url: APP_URL + '/app/ajax/usuarios.php',
            type: 'POST',
            data: formData + '&action=' + action,
            dataType: 'json'
        })
        .done(function(resp) {
            if (resp.success) {
                toastr.success(resp.message || (isEditing ? 'Usuario actualizado' : 'Usuario creado'));
                cerrarModal();
                cargarUsuarios();
            } else {
                toastr.error(resp.message || 'Error al guardar usuario');
            }
        })
        .fail(function() {
            toastr.error('Error de conexión al guardar usuario');
        });
    }
    
    // Funciones globales para botones de tabla
    window.editarUsuario = function(id) {
        $.ajax({
            url: APP_URL + '/app/ajax/usuarios.php',
            type: 'POST',
            data: { action: 'obtener', id: id },
            dataType: 'json'
        })
        .done(function(resp) {
            if (resp.success && resp.data) {
                abrirModal(resp.data);
            } else {
                toastr.error(resp.message || 'Error al cargar usuario');
            }
        })
        .fail(function() {
            toastr.error('Error de conexión al cargar usuario');
        });
    };
    
    window.eliminarUsuario = function(id, usuario) {
        if (confirm(`¿Estás seguro de eliminar al usuario "${usuario}"?`)) {
            $.ajax({
                url: APP_URL + '/app/ajax/usuarios.php',
                type: 'POST',
                data: { action: 'eliminar', id: id },
                dataType: 'json'
            })
            .done(function(resp) {
                if (resp.success) {
                    toastr.success(resp.message || 'Usuario eliminado');
                    cargarUsuarios();
                } else {
                    toastr.error(resp.message || 'Error al eliminar usuario');
                }
            })
            .fail(function() {
                toastr.error('Error de conexión al eliminar usuario');
            });
        }
    };
    
    // Utilidades
    function getRolText(rol) {
        switch(rol) {
            case '1': return 'Empleado';
            case '2': return 'Administrador';
            default: return 'Usuario Normal';
        }
    }
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Inicializar cuando el DOM esté listo
$(document).ready(function() {
    initUsuarios();
});

// Hacer la función disponible globalmente para SPA
window.cargarUsuarios = function() {
    if (typeof initUsuarios === 'function') {
        initUsuarios();
    }
};
