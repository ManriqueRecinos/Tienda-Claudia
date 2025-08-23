'use strict';

(function($) {
    $(function() {
        // Variables globales
        let currentView = 'index';
        let isLoading = false;
        
        // Interceptar clicks en enlaces del sidebar
        $(document).on('click', 'nav a[href*="?views="]', function(e) {
            e.preventDefault();
            
            if (isLoading) return;
            
            const href = $(this).attr('href');
            const viewMatch = href.match(/views=([^&]+)/);
            
            if (!viewMatch) return;
            
            const view = viewMatch[1];
            loadView(view);
        });
        
        // Función para cargar vista dinámicamente
        function loadView(view) {
            if (isLoading || view === currentView) return;

            // Si salimos de Usuarios, limpiar su estado para evitar bugs de selección
            if (currentView === 'usuarios' && typeof window.usuariosCleanup === 'function') {
                try { window.usuariosCleanup(); } catch (e) {}
            }

            isLoading = true;
            showLoading();
            
            // Actualizar estado activo en sidebar
            updateSidebarActive(view);
            
            $.ajax({
                url: APP_URL + '/app/ajax/views.php',
                type: 'GET',
                data: { view: view },
                dataType: 'json'
            })
            .done(function(resp) {
                if (resp.success) {
                    // Actualizar contenido con animación
                    $('#main-content').html(resp.content).addClass('content-fade-in');
                    
                    // Actualizar título en topbar
                    $('#page-title').text(resp.title);
                    
                    // Actualizar URL sin recargar
                    updateURL(view);
                    
                    // Actualizar vista actual
                    currentView = view;
                    
                    // Ejecutar scripts específicos de la vista si existen
                    executeViewScripts(view);
                    
                    // Remover clase de animación después de completarse
                    setTimeout(() => {
                        $('#main-content').removeClass('content-fade-in');
                    }, 300);
                    
                } else {
                    showError(resp.message || 'Error al cargar la vista');
                }
            })
            .fail(function() {
                showError('Error de conexión al cargar la vista');
            })
            .always(function() {
                isLoading = false;
                hideLoading();
            });
        }
        
        // Mostrar indicador de carga
        function showLoading() {
            $('#main-content').addClass('opacity-50 pointer-events-none');
            if (!$('#loading-indicator').length) {
                $('#main-content').prepend(`
                    <div id="loading-indicator" class="fixed inset-0 bg-white bg-opacity-75 flex items-center justify-center z-50">
                        <div class="flex items-center space-x-2">
                            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                            <span class="text-gray-600">Cargando...</span>
                        </div>
                    </div>
                `);
            }
        }
        
        // Ocultar indicador de carga
        function hideLoading() {
            $('#main-content').removeClass('opacity-50 pointer-events-none');
            $('#loading-indicator').remove();
        }
        
        // Actualizar estado activo en sidebar
        function updateSidebarActive(view) {
            $('nav a').removeClass('active');
            $(`nav a[href*="views=${view}"]`).addClass('active');
        }
        
        // Actualizar URL sin recargar página
        function updateURL(view) {
            const newURL = `${APP_URL}?views=${view}`;
            history.pushState({ view: view }, '', newURL);
        }
        
        // Manejar botón atrás del navegador
        window.addEventListener('popstate', function(e) {
            if (e.state && e.state.view) {
                loadView(e.state.view);
            }
        });
        
        // Ejecutar scripts específicos de cada vista
        function executeViewScripts(view) {
            // Para usuarios, reinicializar el CRUD
            if (view === 'usuarios') {
                // Cargar el script de usuarios si no está cargado
                if (!window.cargarUsuarios) {
                    $.getScript(APP_URL + '/app/views/contents/Usuarios/js/usuarios.js')
                        .done(function() {
                            setTimeout(() => {
                                if (typeof window.cargarUsuarios === 'function') {
                                    window.cargarUsuarios();
                                }
                            }, 100);
                        });
                } else {
                    setTimeout(() => {
                        window.cargarUsuarios();
                    }, 100);
                }
            }
        }
        
        // Mostrar error
        function showError(message) {
            if (window.toastr) {
                toastr.error(message);
            } else {
                alert(message);
            }
        }
        
        // Inicializar vista actual basada en URL
        function initCurrentView() {
            const urlParams = new URLSearchParams(window.location.search);
            const view = urlParams.get('views') || 'index';
            currentView = view;
            updateSidebarActive(view);
            
            // Establecer estado inicial para history
            history.replaceState({ view: view }, '', window.location.href);
        }
        
        // Inicializar
        initCurrentView();
    });
})(jQuery);
