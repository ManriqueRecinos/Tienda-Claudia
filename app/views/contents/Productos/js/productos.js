$(document).ready(function () {
  // Util
  const openModal = (id) => $(id).removeClass('hidden');
  const closeModal = (id) => $(id).addClass('hidden');

  // Abrir modal nuevo producto
  $(document).on('click', '#btn_nuevo_producto', function () {
    openModal('#modal_nuevo_producto');
    setTimeout(() => { $('#new_nombre_producto').trigger('focus'); }, 50);
  });

  // Cerrar modal nuevo producto
  $(document).on('click', '#modal_nuevo_producto_close, #modal_nuevo_producto_cancel', function () {
    closeModal('#modal_nuevo_producto');
  });

  // Cerrar al hacer click fuera del panel (incluye fondo oscuro)
  $(document).on('click', '#modal_nuevo_producto', function (e) {
    const $panel = $('.modal-panel');
    if (!$panel.is(e.target) && $panel.has(e.target).length === 0) {
      closeModal('#modal_nuevo_producto');
    }
  });

  // Cerrar con ESC
  $(document).on('keydown', function (e) {
    if (e.key === 'Escape') closeModal('#modal_nuevo_producto');
  });

  // Dinámica de atributos adicionales
  const $container = $('#additional_fields');
  const $containerEdit = $('#additional_fields_edit');
  let attrCount = 0;

  const slugify = (str) => (str || '')
    .toString()
    .normalize('NFD').replace(/[\u0300-\u036f]/g, '') // quitar acentos
    .toLowerCase()
    .trim()
    .replace(/[^a-z0-9_\-\s]/g, '')
    .replace(/\s+/g, '_');

  function addAttributeFieldTo($targetContainer, labelText = '', type = 'text') {
    const key = slugify(labelText) || `attr_${attrCount + 1}`;
    attrCount += 1;
    const idBase = `extra_${key}_${attrCount}`;

    let controlHtml = '';
    if (type === 'textarea') {
      controlHtml = `<textarea id="${idBase}" name="extras[${key}]" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" placeholder="Valor de ${labelText || key}"></textarea>`;
    } else if (type === 'number_int') {
      controlHtml = `<input id="${idBase}" name="extras[${key}]" type="number" step="1" inputmode="numeric" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" placeholder="0" />`;
    } else if (type === 'number_decimal') {
      controlHtml = `<input id="${idBase}" name="extras[${key}]" type="number" step="0.01" inputmode="decimal" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" placeholder="0.00" />`;
    } else {
      // default text (varchar)
      controlHtml = `<input id="${idBase}" name="extras[${key}]" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" placeholder="Valor de ${labelText || key}" />`;
    }

    const $field = $(
      `<div class="attr-field border rounded-md p-3 relative group bg-gray-50" data-key="${key}" data-type="${type}">
         <div class="absolute -top-2 -right-2 flex gap-1">
           <button type="button" class="edit-attr h-7 w-7 rounded-full bg-amber-500 text-white text-xs shadow hover:bg-amber-600" title="Editar">
             <i class="fa fa-pen"></i>
           </button>
           <button type="button" class="remove-attr h-7 w-7 rounded-full bg-red-600 text-white text-xs shadow hover:bg-red-700" title="Quitar">
             <i class="fa fa-times"></i>
           </button>
         </div>
         <label class="block text-xs text-gray-600 mb-1" for="${idBase}">${labelText || 'Atributo'}</label>
         ${controlHtml}
       </div>`
    );
    $targetContainer.append($field);
  }

  // Compatibilidad con código existente para el modal de nuevo producto
  function addAttributeField(labelText = '', type = 'text') {
    addAttributeFieldTo($container, labelText, type);
  }

  function detectFieldType($field) {
    const $input = $field.find('input, textarea').first();
    if ($input.is('textarea')) return 'textarea';
    if ($input.attr('type') === 'number') {
      const step = $input.attr('step');
      return step && step !== '1' ? 'number_decimal' : 'number_int';
    }
    return 'text';
  }

  function updateAttributeField($field, newLabel, newType) {
    const oldKey = $field.data('key');
    const currentValue = $field.find('input, textarea').val();
    const newKey = slugify(newLabel) || oldKey || `attr_${Date.now()}`;
    const idBase = `extra_${newKey}_${Date.now()}`;

    let controlHtml = '';
    if (newType === 'textarea') {
      controlHtml = `<textarea id="${idBase}" name="extras[${newKey}]" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" placeholder="Valor de ${newLabel || newKey}"></textarea>`;
    } else if (newType === 'number_int') {
      controlHtml = `<input id="${idBase}" name="extras[${newKey}]" type="number" step="1" inputmode="numeric" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" placeholder="0" />`;
    } else if (newType === 'number_decimal') {
      controlHtml = `<input id="${idBase}" name="extras[${newKey}]" type="number" step="0.01" inputmode="decimal" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" placeholder="0.00" />`;
    } else {
      controlHtml = `<input id="${idBase}" name="extras[${newKey}]" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" placeholder="Valor de ${newLabel || newKey}" />`;
    }

    $field.find('label').attr('for', idBase).text(newLabel || 'Atributo');
    $field.find('input, textarea').replaceWith(controlHtml);
    $field.find(`#${idBase}`).val(currentValue);
    $field.attr('data-key', newKey);
    $field.attr('data-type', newType);
  }

  // Botón + para agregar usando SweetAlert2
  $(document).on('click', '#btn_add_attr', function () {
    if (!window.Swal) {
      // Fallback mínimo si no está cargado SweetAlert2
      const name = window.prompt('Nombre del atributo');
      if (!name) return;
      addAttributeField(name, 'text');
      return;
    }

    Swal.fire({
      title: 'Nuevo atributo',
      html: `
        <div class="text-left space-y-3">
          <div>
            <label class="block text-xs text-gray-600 mb-1" for="attr_name">Nombre del atributo</label>
            <input id="attr_name" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" placeholder="Ej. Talla, Color" />
          </div>
          <div>
            <label class="block text-xs text-gray-600 mb-1" for="attr_type">Tipo de campo</label>
            <select id="attr_type" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
              <option value="text">Texto (varchar)</option>
              <option value="number_int">Número entero</option>
              <option value="number_decimal">Número decimal</option>
              <option value="textarea">Texto largo (textarea)</option>
            </select>
          </div>
        </div>
      `,
      focusConfirm: false,
      showCancelButton: true,
      confirmButtonText: 'Agregar',
      cancelButtonText: 'Cancelar',
      customClass: {
        popup: 'rounded-xl',
        confirmButton: 'bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-md',
        cancelButton: 'border px-4 py-2 rounded-md ml-2'
      },
      preConfirm: () => {
        const name = (document.getElementById('attr_name').value || '').trim();
        const type = document.getElementById('attr_type').value;
        if (!name) {
          Swal.showValidationMessage('Ingresa un nombre válido.');
          return false;
        }
        return { name, type };
      }
    }).then((result) => {
      if (result.isConfirmed && result.value) {
        addAttributeField(result.value.name, result.value.type);
      }
    });
  });

  // Botón + del modal de edición
  $(document).on('click', '#btn_add_attr_edit', function () {
    if (!window.Swal) {
      const name = window.prompt('Nombre del atributo');
      if (!name) return;
      addAttributeFieldTo($containerEdit, name, 'text');
      return;
    }

    Swal.fire({
      title: 'Nuevo atributo',
      html: `
        <div class="text-left space-y-3">
          <div>
            <label class="block text-xs text-gray-600 mb-1" for="attr_name">Nombre del atributo</label>
            <input id="attr_name" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" placeholder="Ej. Talla, Color" />
          </div>
          <div>
            <label class="block text-xs text-gray-600 mb-1" for="attr_type">Tipo de campo</label>
            <select id="attr_type" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
              <option value="text">Texto (varchar)</option>
              <option value="number_int">Número entero</option>
              <option value="number_decimal">Número decimal</option>
              <option value="textarea">Texto largo (textarea)</option>
            </select>
          </div>
        </div>
      `,
      focusConfirm: false,
      showCancelButton: true,
      confirmButtonText: 'Agregar',
      cancelButtonText: 'Cancelar',
      customClass: {
        popup: 'rounded-xl',
        confirmButton: 'bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-md',
        cancelButton: 'border px-4 py-2 rounded-md ml-2'
      },
      preConfirm: () => {
        const name = (document.getElementById('attr_name').value || '').trim();
        const type = document.getElementById('attr_type').value;
        if (!name) {
          Swal.showValidationMessage('Ingresa un nombre válido.');
          return false;
        }
        return { name, type };
      }
    }).then((result) => {
      if (result.isConfirmed && result.value) {
        addAttributeFieldTo($containerEdit, result.value.name, result.value.type);
      }
    });
  });

  // Abrir modal editar producto
  $(document).on('click', '#btn_editar_producto', function () {
    openModal('#modal_productos');
    setTimeout(() => { $('#nombre_producto').trigger('focus'); }, 50);
  });

  // Cerrar modal editar producto
  $(document).on('click', '#modal_productos_close, #modal_productos_cancel', function () {
    closeModal('#modal_productos');
  });

  // Cerrar al hacer click fuera del panel en modal editar
  $(document).on('click', '#modal_productos', function (e) {
    const $panel = $('.modal-panel');
    if (!$panel.is(e.target) && $panel.has(e.target).length === 0) {
      closeModal('#modal_productos');
    }
  });

  // Cerrar con ESC ambos modales
  $(document).on('keydown', function (e) {
    if (e.key === 'Escape') {
      closeModal('#modal_nuevo_producto');
      closeModal('#modal_productos');
    }
  });

  // Quitar campo individual (evitar que se cierre el modal)
  $(document).on('click', '.remove-attr', function (e) {
    e.preventDefault();
    e.stopPropagation();
    $(this).closest('.attr-field').remove();
  });

  // Editar campo individual
  $(document).on('click', '.edit-attr', function (e) {
    e.preventDefault();
    e.stopPropagation();
    const $field = $(this).closest('.attr-field');
    const currentLabel = $field.find('label').text().trim();
    const currentType = detectFieldType($field);

    if (!window.Swal) {
      // Fallback simple
      const name = window.prompt('Nuevo nombre del atributo', currentLabel);
      if (!name) return;
      updateAttributeField($field, name, currentType);
      return;
    }

    Swal.fire({
      title: 'Editar atributo',
      html: `
        <div class="text-left space-y-3">
          <div>
            <label class="block text-xs text-gray-600 mb-1" for="attr_name_edit">Nombre del atributo</label>
            <input id="attr_name_edit" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" value="${currentLabel}" />
          </div>
          <div>
            <label class="block text-xs text-gray-600 mb-1" for="attr_type_edit">Tipo de campo</label>
            <select id="attr_type_edit" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
              <option value="text">Texto (varchar)</option>
              <option value="number_int">Número entero</option>
              <option value="number_decimal">Número decimal</option>
              <option value="textarea">Texto largo (textarea)</option>
            </select>
          </div>
        </div>
      `,
      didOpen: () => {
        const sel = document.getElementById('attr_type_edit');
        if (sel) sel.value = currentType;
      },
      focusConfirm: false,
      showCancelButton: true,
      confirmButtonText: 'Guardar',
      cancelButtonText: 'Cancelar',
      customClass: {
        popup: 'rounded-xl',
        confirmButton: 'bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-md',
        cancelButton: 'border px-4 py-2 rounded-md ml-2'
      },
      preConfirm: () => {
        const name = (document.getElementById('attr_name_edit').value || '').trim();
        const type = document.getElementById('attr_type_edit').value;
        if (!name) {
          Swal.showValidationMessage('Ingresa un nombre válido.');
          return false;
        }
        return { name, type };
      }
    }).then((result) => {
      if (result.isConfirmed && result.value) {
        updateAttributeField($field, result.value.name, result.value.type);
      }
    });
  });

  // -----------------------------
  // Altura dinámica de la tabla de productos
  // -----------------------------
  function debounce(fn, wait){
    let t; return function(){ clearTimeout(t); t = setTimeout(() => fn.apply(this, arguments), wait||100); };
  }

  function ajustarAlturaTablaProductos(){
    // Usamos los mismos selectores que en el markup actual
    const $table = $('#tabla-usuarios');
    const $scroll = $table.closest('.overflow-auto');
    if (!$table.length || !$scroll.length) return;
    const $card = $('#usuarios-card');
    const $toolbarBottom = $('.usuarios-toolbar-bottom');

    const vh = window.innerHeight || document.documentElement.clientHeight;
    const scrollTop = $scroll.offset() ? $scroll.offset().top : ($card.offset() ? $card.offset().top : 0);
    const toolbarH = $toolbarBottom.length ? $toolbarBottom.outerHeight(true) : 0;
    const margin = 32; // seguridad para paddings/bordes

    let available = Math.floor(vh - scrollTop - toolbarH - margin);
    if (!isFinite(available)) return;
    available = Math.max(240, available);

    $scroll.css({ height: available + 'px', maxHeight: available + 'px', overflowY: 'auto', overflowX: 'hidden' });

    // Ajuste fino si aún hay overflow del documento
    const doc = document.documentElement;
    const overflow = (doc.scrollHeight - vh);
    if (overflow > 0){
      const newAvail = Math.max(200, available - overflow - 1);
      $scroll.css({ height: newAvail + 'px', maxHeight: newAvail + 'px' });
    }
  }

  // Invocaciones iniciales y en resize
  ajustarAlturaTablaProductos();
  setTimeout(ajustarAlturaTablaProductos, 50);
  setTimeout(ajustarAlturaTablaProductos, 250);
  $(window).on('resize', debounce(ajustarAlturaTablaProductos, 100));
});
