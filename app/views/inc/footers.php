<!-- jQuery local v3.7.1 -->
<script src="<?php echo APP_URL; ?>/public/assets/js/jquerry.min.js"></script>

<script>window.APP_URL = '<?php echo APP_URL; ?>';</script>

<!-- JS por vista (después de jQuery) -->
<?php $view = $_GET['views'] ?? ''; ?>
<?php if ($view === 'login'): ?>
  <script src="<?php echo APP_URL; ?>/app/views/contents/Login/js/login.js"></script>
<?php elseif ($view === 'register'): ?>
  <script src="<?php echo APP_URL; ?>/app/views/contents/Register/js/register.js"></script>
<?php endif; ?>

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

<!-- Main -->
<script src="<?php echo APP_URL; ?>/public/assets/js/main.js"></script>

<!-- Tailwind -->
<script src="<?php echo APP_URL; ?>/public/assets/js/tailwind.min.js"></script>

<!-- Font Awesome JS local no requerido: removido (usamos CDN CSS en headers) -->
<script src="<?php echo APP_URL; ?>/public/assets/js/all.min.js"></script>

