'use strict';
(function($){
  $(function(){
    const $form = $("form[action$='?views=register']");
    if(!$form.length) return;

    $form.on('submit', function(e){
      e.preventDefault();
      const $btn = $(this).find('button[type=submit]');
      $btn.prop('disabled', true);

      const payload = {
        action: 'register',
        nombres: $(this).find('[name="nombres"]').val(),
        apellidos: $(this).find('[name="apellidos"]').val(),
        usuario: $(this).find('[name="usuario"]').val(),
        contrasenia: $(this).find('[name="contrasenia"]').val(),
        confirm_contrasenia: $(this).find('[name="confirm_contrasenia"]').val(),
        rol: null
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
          if(window.Swal){
            Swal.fire({icon:'success', title:'Usuario creado', text:'Ahora puedes iniciar sesión'}).then(()=>{
              window.location.href = APP_URL + '?views=login';
            });
          } else {
            alert('Usuario creado');
            window.location.href = APP_URL + '?views=login';
          }
        } else {
          const msg = (resp && resp.message) || 'No se pudo registrar';
          if(window.Swal){ Swal.fire({icon:'error', title:'Ups...', text: msg}); }
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
