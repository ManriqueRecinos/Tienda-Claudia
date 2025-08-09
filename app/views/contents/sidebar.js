// Sidebar responsivo para móviles
(function() {
  function initSidebar() {
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('mobile-menu-overlay');
    
    if (!mobileMenuBtn || !sidebar || !overlay) return;
    
    // Abrir sidebar en móvil
    function openSidebar() {
      sidebar.classList.remove('-translate-x-full');
      overlay.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }
    
    // Cerrar sidebar en móvil
    function closeSidebar() {
      sidebar.classList.add('-translate-x-full');
      overlay.classList.add('hidden');
      document.body.style.overflow = '';
    }
    
    // Toggle del botón hamburguesa
    mobileMenuBtn.addEventListener('click', function(e) {
      e.preventDefault();
      if (sidebar.classList.contains('-translate-x-full')) {
        openSidebar();
      } else {
        closeSidebar();
      }
    });
    
    // Cerrar al hacer click en overlay
    overlay.addEventListener('click', closeSidebar);
    
    // Cerrar con tecla Escape
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeSidebar();
      }
    });
    
    // Cerrar sidebar al redimensionar a desktop
    window.addEventListener('resize', function() {
      if (window.innerWidth >= 768) { // md breakpoint
        closeSidebar();
      }
    });
  }
  
  // Inicializar cuando el DOM esté listo
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSidebar);
  } else {
    initSidebar();
  }
})();
