<!-- jQuery local v3.7.1 -->
<script src="<?php echo APP_URL; ?>/public/assets/js/jquerry.min.js"></script>

<script>window.APP_URL = '<?php echo APP_URL; ?>';</script>

<!-- Nota: Cada vista debe incluir sus propios scripts específicos. -->

<!-- SweetAlert -->
<script src="<?php echo APP_URL; ?>/public/assets/js/sweetalert2.min.js"></script>

<!-- Toastr JS (sin integrity para evitar bloqueos por SRI) -->
<script src="<?php echo APP_URL; ?>/public/assets/js/toastr.min.js"></script>
<script>
  window.toastr && (toastr.options = {
    closeButton: true,
    progressBar: true,
    positionClass: 'toast-top-right',
    timeOut: 1500
  });
</script>

<!-- AJAX util (si aplica) -->
<script src="<?php echo APP_URL; ?>/public/assets/js/ajax.js"></script>

<!-- Auth (login/register) -->
<script src="<?php echo APP_URL; ?>/public/assets/js/auth.js"></script>

<!-- Sidebar responsive JS -->
<script src="<?php echo APP_URL; ?>/app/views/contents/sidebar.js"></script>

<!-- SPA Navigation -->
<script src="<?php echo APP_URL; ?>/public/assets/js/spa-navigation.js"></script>

<!-- Main -->
<script src="<?php echo APP_URL; ?>/public/assets/js/main.js"></script>

<!-- Tailwind -->
<script src="<?php echo APP_URL; ?>/public/assets/js/tailwind.min.js"></script>

<!-- Font Awesome JS local no requerido: removido (usamos CDN CSS en headers) -->
<script src="<?php echo APP_URL; ?>/public/assets/js/all.min.js"></script>

<!-- Redirigir a inicio en recarga de página -->
<script>
  (function(){
    try {
      var navType = 'navigate';
      var entries = (window.performance && performance.getEntriesByType) ? performance.getEntriesByType('navigation') : null;
      if (entries && entries[0] && entries[0].type){
        navType = entries[0].type; // 'reload' | 'navigate' | 'back_forward'
      } else if (window.performance && performance.navigation) {
        // legacy
        navType = (performance.navigation.type === 1) ? 'reload' : 'navigate';
      }
      if (navType === 'reload'){
        var url = new URL(window.location.href);
        var views = url.searchParams.get('views');
        var isHome = !views || views === 'index';
        if (!isHome){
          // ir a inicio sin parámetros
          window.location.replace('<?php echo APP_URL; ?>/');
        }
      }
    } catch(e) { /* noop */ }
  })();
  </script>
