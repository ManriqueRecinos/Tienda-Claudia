'use strict';
(function($){
  $(function(){
    const $form = $("form[action$='?views=login']");
    if(!$form.length) return;

    $form.on('submit', function(e){
      e.preventDefault();
      const $btn = $(this).find('button[type=submit]');
      $btn.prop('disabled', true);

      const payload = {
        action: 'login',
        usuario: $(this).find('[name="usuario"]').val(),
        contrasenia: $(this).find('[name="contrasenia"]').val()
      };

      $.ajax({
        url: APP_URL + '/app/ajax/auth.php',
        type: 'POST',
        data: payload,
        dataType: 'json',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
      })
      .done(function(resp){
        if(resp && resp.success){
          var u = payload.usuario || '';
          try {
            sessionStorage.setItem('toast', JSON.stringify({
              type: 'success',
              title: 'Bienvenido',
              message: u ? ('Ingreso exitoso, ' + u) : 'Ingreso exitoso',
              position: 'toast-top-right'
            }));
          } catch(e){}
          window.location.href = APP_URL + '?views=index';
        } else {
          const msg = (resp && resp.message) || 'Usuario o contraseña inválidos';
          if(window.toastr){ toastr.error(msg); }
          else { alert(msg); }
        }
      })
      .fail(function(){
        if(window.Swal){ Swal.fire({icon:'error', title:'Error', text:'No se pudo conectar con el servidor'});} 
        else { alert('No se pudo conectar con el servidor'); }
      })
      .always(function(){ $btn.prop('disabled', false); });
    });
  });
})(jQuery);
