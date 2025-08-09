<!-- jQuery (CDN) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

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

<!-- AJAX util (si aplica) -->
<script src="<?php echo APP_URL; ?>/public/assets/js/ajax.js"></script>

<!-- Auth (login/register) -->
<script src="<?php echo APP_URL; ?>/public/assets/js/auth.js"></script>

<!-- Main -->
<script src="<?php echo APP_URL; ?>/public/assets/js/main.js"></script>

<!-- Tailwind -->
<script src="<?php echo APP_URL; ?>/public/assets/js/tailwind.min.js"></script>

<!-- Font Awesome JS local no requerido: removido (usamos CDN CSS en headers) -->
