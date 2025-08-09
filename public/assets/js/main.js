// Mostrar toasts persistentes después de redirecciones usando sessionStorage
(function(){
  if (!window.toastr) return;
  try {
    var toast = sessionStorage.getItem('toast');
    if (toast) {
      var data = JSON.parse(toast);
      // Configuración: 3.5s y con barra de progreso
      var prev = toastr.options.timeOut;
      var prevPos = toastr.options.positionClass;
      toastr.options.timeOut = 3500;
      toastr.options.progressBar = true;
      toastr.options.closeButton = true;
      toastr.options.positionClass = data.position || 'toast-top-right';
      var type = data.type || 'success';
      var msg = data.message || '';
      var title = data.title || '';
      toastr[type](msg, title);
      // Restaurar
      toastr.options.timeOut = prev || 1500;
      toastr.options.positionClass = prevPos || 'toast-top-right';
      sessionStorage.removeItem('toast');
    }
  } catch(e) {
    // no-op
  }
})();