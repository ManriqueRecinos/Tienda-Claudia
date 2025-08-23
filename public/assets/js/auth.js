'use strict';
(function(factory){
  if (typeof window === 'undefined') return;
  if (typeof window.jQuery === 'undefined') return; // salir silenciosamente si no hay jQuery
  factory(window.jQuery);
})(function ($) {
  // Esperar a que el DOM esté listo
  $(function () {
    // (Login handler movido a public/assets/js/login.js)

    // Toggle mostrar/ocultar contraseña en inputs que tengan botón .toggle-password
    $(document).on('click', '.toggle-password', function () {
      const target = $(this).data('target');
      const $input = $(target);
      if (!$input.length) return;
      const type = $input.attr('type') === 'password' ? 'text' : 'password';
      $input.attr('type', type);
      // Alternar icono
      const $icon = $(this).find('i');
      if (type === 'text') {
        // Visible -> ojo abierto
        $icon.removeClass('fa-eye-slash').addClass('fa-eye');
      } else {
        // Oculto -> ojo cerrado
        $icon.removeClass('fa-eye').addClass('fa-eye-slash');
      }
    });

    // Logout
    $(document).on('click', '[data-action="logout"]', function (e) {
      e.preventDefault();
      const $btn = $(this);
      $btn.prop('disabled', true);
      $.ajax({
        url: APP_URL + '/app/ajax/auth.php',
        type: 'POST',
        dataType: 'json',
        data: { action: 'logout' },
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
      })
        .done(function () {
          window.location.href = APP_URL + '?views=login';
        })
        .fail(function () {
          if (window.Swal) {
            Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo cerrar sesión' });
          } else {
            alert('No se pudo cerrar sesión');
          }
        })
        .always(function () { $btn.prop('disabled', false); });
    });
  });
});
