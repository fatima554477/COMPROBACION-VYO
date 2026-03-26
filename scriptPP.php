<?php
/**
 * Autor: Sandor Matamoros
 * Programer: Fatima Arellano
 * Propietario: EPC

 */
?>

<!-- ===================== MODALES ===================== -->

<!-- Modal: Detalles pequeño (14) -->
<div id="dataModal14" class="modal fade">
  <div class="modal-dialog" style="width:80% !important; max-width:100% !important;">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Detalles</h4>
      </div>
      <div class="modal-body" id="personal_detalles14"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Detalles secundario (antes duplicado como add_data_Modal) -->
<div id="add_data_Modal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Detalles</h4>
      </div>
      <div class="modal-body" id="personal_detalles2"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Detalles personal (antes tercer add_data_Modal — ID renombrado) -->
<div id="add_data_Modal_personal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Detalles</h4>
      </div>
      <div class="modal-body" id="personal"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Fullscreen principal -->
<div id="dataModal" class="modal fade">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Detalles</h4>
      </div>
      <div class="modal-body" id="personal_detalles"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Confirmar borrado -->
<div id="dataModal3" class="modal fade">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Confirmación</h4>
      </div>
      <div class="modal-body" id="personal_detalles3">
        ¿ESTÁS SEGURO DE BORRAR ESTE REGISTRO?
      </div>
      <div class="modal-footer">
        <button id="btnYes" class="btn confirm">SI BORRAR</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal: EFECTIVO (ID corregido — antes tenía id duplicado en modal y body) -->
<div id="modalEFECTIVO" class="modal fade">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Detalles</h4>
      </div>
      <div class="modal-body" id="bodyEFECTIVO">
        ¿ESTÁS SEGURO DE BORRAR ESTE REGISTRO?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Arrastrable (dataModal4) -->
<div id="dataModal4" class="modal fade">
  <div class="modal-dialog" style="width:80% !important; max-width:100% !important;">
    <div class="modal-content">
      <div class="modal-header" style="cursor:move;">
        <h4 class="modal-title">Detalles</h4>
      </div>
      <div class="modal-body" id="personal_detalles4">Contenido...</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
      <div id="dragArrow" style="cursor:move; text-align:center; font-size:22px; padding:5px; background:#f1f1f1;">⬍</div>
    </div>
  </div>
</div>


<!-- ===================== SCRIPTS ===================== -->
<script>

/* -------------------------------------------------------
   MODAL ARRASTRABLE
------------------------------------------------------- */
document.addEventListener("DOMContentLoaded", function () {
  const modal  = document.querySelector("#dataModal4 .modal-dialog");
  const header = document.querySelector("#dataModal4 .modal-header");
  const arrow  = document.getElementById("dragArrow");

  let offsetX = 0, offsetY = 0, isDragging = false;

  function startDrag(e) {
    isDragging = true;
    offsetX = e.clientX - modal.offsetLeft;
    offsetY = e.clientY - modal.offsetTop;
    document.addEventListener("mousemove", moveModal);
    document.addEventListener("mouseup", stopDrag);
  }

  function moveModal(e) {
    if (!isDragging) return;
    modal.style.margin   = "0";
    modal.style.position = "absolute";
    modal.style.left     = (e.clientX - offsetX) + "px";
    modal.style.top      = (e.clientY - offsetY) + "px";
  }

  function stopDrag() {
    isDragging = false;
    document.removeEventListener("mousemove", moveModal);
    document.removeEventListener("mouseup", stopDrag);
  }

  if (header) header.addEventListener("mousedown", startDrag);
  if (arrow)  arrow.addEventListener("mousedown", startDrag);
});


/* -------------------------------------------------------
   VERIFICAR FORMA DE PAGO (solo alerta si es diferente a '04')
------------------------------------------------------- */
function verificarFormaDePago() {
  var selectFP = document.getElementById('PFORMADE_PAGO');
  if (!selectFP || selectFP.value === '') return; // No existe o aún vacío
  if (selectFP.value !== '04') {
    alert('LA FORMA DE PAGO DE TU FACTURA ES DIFERENTE A 04 (TARJETA DE CREDITO) PEDIR REFACTURACIÓN');
  }
}
document.addEventListener('DOMContentLoaded', verificarFormaDePago);


/* -------------------------------------------------------
   CARGA DE ARCHIVOS (DRAG & DROP + FILE EXPLORER)
------------------------------------------------------- */
var fileobj;

function upload_file(e, name) {
  e.preventDefault();
  fileobj = e.dataTransfer.files[0];
  ajax_file_upload1(fileobj, name);
}

function file_explorer(name) {
  document.getElementsByName(name)[0].click();
  document.getElementsByName(name)[0].onchange = function () {
    fileobj = document.getElementsByName(name)[0].files[0];
    ajax_file_upload1(fileobj, name);
  };
}

function ajax_file_upload1(file_obj, nombre) {
  if (!file_obj) return;

  var form_data = new FormData();
  form_data.append(nombre, file_obj);

  $.ajax({
    type: 'POST',
    url: 'comprobaciones/controladorPP.php',
    contentType: false,
    processData: false,
    data: form_data,
 beforeSend: function () {
      $('#1' + nombre).html('<p style="color:green;"><span class="spinner-border spinner-border-sm"></span>&nbsp;Cargando archivo...</p>');
      $('#mensajeADJUNTOCOL').html('<p style="color:green;"><span class="spinner-border spinner-border-sm"></span>&nbsp;Cargando archivo...</p>');
    },
    success: function (response) {
      var resp = $.trim(response);

      if (resp === '3') {
        $('#1' + nombre).html('<p style="color:red;">UUID PREVIAMENTE CARGADO.</p>');
        $('#' + nombre).val('');

      } else if (resp === 'El archivo debe estar en formato XML.') {
        $('#1' + nombre).html('<p style="color:red;">' + resp + '</p>');
        $('#' + nombre).val('');

      } else {
        $('#' + nombre).val(response);
                $('#1' + nombre).html('<p style="color:green;">✅ ¡Archivo cargado con éxito!</p>');
        $('#mensajeADJUNTOCOL').html('<p style="color:green;">✅ ¡Actualizado!</p>');

        recargarElemento('#2ADJUNTAR_FACTURA_XML');

        if (nombre === 'ADJUNTAR_FACTURA_XML') {
          var camposXML = [
            '#RAZON_SOCIAL2', '#RFC_PROVEEDOR2', '#CONCEPTO_PROVEE2',
            '#TIPO_DE_MONEDA2', '#FECHA_DE_PAGO2', '#NUMERO_CONSECUTIVO_PROVEE2',
            '#2MONTO_FACTURA', '#2MONTO_DEPOSITAR', '#2PFORMADE_PAGO',
            '#2IVA', '#2TImpuestosRetenidosIVA', '#2TImpuestosRetenidosISR', '#2descuentos'
          ];
          camposXML.forEach(recargarElemento);
        }

        recargarElemento('#2' + nombre);
        recargarElemento('#resettabla');
      }
    }
  });
}


/* -------------------------------------------------------
   HELPER: recarga un elemento por selector
------------------------------------------------------- */
function recargarElemento(selector) {
  $(selector).load(location.href + ' ' + selector);
}


/* -------------------------------------------------------
   FORMATEO DE MONTOS CON COMAS
------------------------------------------------------- */
function comasainput(name) {
  var el    = document.getElementsByName(name)[0];
  var clean = el.value.replace(/,/g, '');
  el.value  = clean.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}


/* -------------------------------------------------------
   FECHA DE LLENADO AUTOMÁTICA
------------------------------------------------------- */
function actualizarFechaDeLlenado() {
  var fechaInput = document.querySelector('input[name="FECHA_DE_LLENADO"]');
  if (!fechaInput) return;
  var now = new Date();
  var pad = function (v) { return v.toString().padStart(2, '0'); };
  fechaInput.value = pad(now.getDate()) + '-' + pad(now.getMonth() + 1) + '-' + now.getFullYear()
    + ' ' + pad(now.getHours()) + ':' + pad(now.getMinutes()) + ':' + pad(now.getSeconds());
}


/* -------------------------------------------------------
   FUNCIÓN CENTRALIZADA: Guardar + recargar en target2
   MÉTODO: sessionStorage flag → location.reload(true)
   - sessionStorage persiste entre recargas de la misma pestaña
   - location.reload(true) fuerza recarga desde servidor (no caché)
------------------------------------------------------- */
function guardarYIrATarget2() {
  try {
    sessionStorage.setItem('irATarget', '2');
  } catch(e) { /* Safari privado puede bloquear sessionStorage */ }
  location.reload(true);
}

// Al cargar la página: si hay flag en sessionStorage, activa target2
document.addEventListener('DOMContentLoaded', function () {
  var targetPendiente = null;
  try {
    targetPendiente = sessionStorage.getItem('irATarget');
    if (targetPendiente) sessionStorage.removeItem('irATarget'); // limpiar inmediatamente
  } catch(e) {}

  if (targetPendiente) {
    // Pequeño delay para asegurar que jQuery y el DOM están listos
    setTimeout(function () {
      activarTarget(parseInt(targetPendiente, 10));
      // Scroll suave al target
      var el = document.getElementById('target' + targetPendiente);
      if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }, 150);
  }
});


/* -------------------------------------------------------
   SHOW/HIDE TARGETS — REEMPLAZA LOS 47 BLOQUES REPETIDOS
   Targets: 1..47 + 'VIDEO'
------------------------------------------------------- */
function activarTarget(num) {
  var allTargets = [];
  for (var i = 1; i <= 47; i++) allTargets.push(i);
  allTargets.push('VIDEO');

  // Oculta todos
  allTargets.forEach(function (t) { $('#target' + t).hide('linear'); });

  // Muestra solo el solicitado
  if (num !== null) {
    $('#target' + num).show('swing');
    // target2 = filtro VYO: disparar load(1) para mostrar datos
    if (num === 2 && typeof load === 'function') {
      setTimeout(function () { load(1); }, 100);
    }
  }
}

$(document).ready(function () {

  /* Inicialización: solo target1 visible */
  activarTarget(1);

  /* Mostrar/Ocultar individuales — generados dinámicamente */
  var allNums = [];
  for (var n = 1; n <= 47; n++) allNums.push(n);
  allNums.push('VIDEO');

  allNums.forEach(function (num) {
    $('#mostrar' + num).on('click', function () {
      $('#target' + num).show('swing');
      // target2 = filtro comprobación VYO: recargar datos al mostrarse
      if (num === 2 && typeof load === 'function') { load(1); }
    });
    $('#ocultar' + num).on('click', function () { $('#target' + num).hide('linear'); });
  });
  // Excepción: mostrar303 controla target33 (como estaba en el original)
  $('#mostrar303').off('click').on('click', function () { $('#target33').show('swing'); });

  /* Mostrar / Ocultar TODOS */
  function toggleTodos(accion) {
    allNums.forEach(function (n) { $('#target' + n)[accion](accion === 'show' ? 'swing' : 'linear'); });
  }
  $('#mostrartodos,  #mostrartodos2').on('click',  function () { toggleTodos('show'); });
  $('#ocultartodos, #ocultartodos2').on('click', function () { toggleTodos('hide'); });


  /* ---------------------------------------------------
     CHECKBOX: monto a pagar
  --------------------------------------------------- */
  window.myFunction = function (montoapagar_id) {
    var checkBox = document.getElementById('montoapagar' + montoapagar_id);
    if (!checkBox) return;
    $.ajax({
      url: 'pagoproveedores/fetch_pagesPP.php',
      method: 'POST',
      data: { montoapagar_id: montoapagar_id, montoapagar_text: checkBox.checked ? 'enter' : 'none' },
      beforeSend: function () { $('#mensajemontoapagar').html('cargando...'); },
      success: function () {
        recargarElemento('#montoapagartotal');
        recargarElemento('#montoapagartotal2');
      }
    });
  };


  /* ---------------------------------------------------
     CHECKBOX: pasar a pagado
  --------------------------------------------------- */
  window.pasarpagado = function (pasarpagado_id) {
    var checkBox = document.getElementById('pasarpagado1a' + pasarpagado_id);
    if (!checkBox) return;
    $.ajax({
      url: 'comprobaciones/controladorPP.php',
      method: 'POST',
      data: { pasarpagado_id: pasarpagado_id, pasarpagado_text: checkBox.checked ? 'si' : 'no' },
      beforeSend: function () { $('#pasarpagado').html('cargando...'); },
      success: function (data) { $('#pasarpagado').html('<span id="ACTUALIZADO">' + data + '</span>'); }
    });
  };


  /* ---------------------------------------------------
     HELPER: limpia el formulario de pago a proveedores
     y recarga los elementos dinámicos (previews de archivos,
     campos poblados desde el XML, totales)
  --------------------------------------------------- */
  function limpiarFormularioPago() {
    // 1. Reset nativo del formulario (limpia inputs, selects, textareas, file inputs)
    var form = document.getElementById('pagoaproveedoresform');
    if (form) form.reset();

    // 2. Campos de texto populados desde PHP/XML — limpiar con .val('')
    var camposVacios = [
      '#RAZON_SOCIAL', '#CONCEPTO_PROVEE', '#RFC_PROVEEDOR',
      '#TIPO_DE_MONEDA', '#FECHA_DE_PAGO', '#NUMERO_CONSECUTIVO_PROVEE',
      '#ADJUNTAR_FACTURA_XML', '#ADJUNTAR_FACTURA_PDF',
      '#PFORMADE_PAGO', '#2MONTO_FACTURA', '#2MONTO_DEPOSITAR', '#2ADJUNTAR_FACTURA_PDF'
    ];
    camposVacios.forEach(function(id) { $(id).val(''); });

    // 3. Previews de archivos — limpiar HTML (mensajes "Cargando...", links "Ver!")
    var previews = [
      '#1ADJUNTAR_FACTURA_XML',   // preview XML
      '#1ADJUNTAR_FACTURA_PDF',   // preview PDF
      '#1ADJUNTAR_COTIZACION',    // preview cotización
      '#1CONPROBANTE_TRANSFERENCIA', // preview comprobante
      '#1ADJUNTAR_ARCHIVO_1',     // preview archivo extra
      '#mensajeADJUNTOCOL'
    ];
    previews.forEach(function(id) { $(id).html(''); });

    // 4. Recargar elementos dinámicos desde el servidor (campos dobles #2..., totales)
    var elementosRecargar = [
      '#CONCEPTO_PROVEE2',
      '#2ADJUNTAR_FACTURA_XML', '#ADJUNTAR_FACTURA_XML',
      '#ADJUNTAR_FACTURA_PDF',  '#1ADJUNTAR_FACTURA_PDF',
      '#IMPUESTO_HOSPEDAJE', '#MONTO_PROPINA', '#IVA',
      '#2ADJUNTAR_FACTURA_PDF', '#2ADJUNTAR_COTIZACION',
      '#2CONPROBANTE_TRANSFERENCIA', '#2ADJUNTAR_ARCHIVO_1',
      '#NUMERO_CONSECUTIVO_PROVEE2',
      '#2MONTO_FACTURA', '#2MONTO_DEPOSITAR', '#2IVA', '#2PFORMADE_PAGO',
      '#2TImpuestosRetenidosIVA', '#TImpuestosRetenidosIVA',
      '#2TImpuestosRetenidosISR', '#TImpuestosRetenidosISR',
      '#2descuentos', '#descuentos',
      '#RAZON_SOCIAL2', '#RFC_PROVEEDOR2',
      '#TIPO_DE_MONEDA2', '#FECHA_DE_PAGO2'
    ];
    elementosRecargar.forEach(recargarElemento);
  }

  /* ---------------------------------------------------
     ENVIAR PAGO PROVEEDORES
  --------------------------------------------------- */
  $('#enviarPAGOPROVEEDORES').on('click', function () {
    actualizarFechaDeLlenado();
    var formData = new FormData($('#pagoaproveedoresform')[0]);

    $.ajax({
      url: 'comprobaciones/controladorPP.php',
      type: 'POST',
      dataType: 'html',
      data: formData,
      cache: false,
      contentType: false,
      processData: false
    }).done(function (data) {
      if ($.trim(data) === 'Ingresado' || $.trim(data) === 'Actualizado') {
        // ✅ Mensaje de confirmación
        $('#mensajepagoproveedores').html('<span id="ACTUALIZADO">' + data + '</span>');

        // ✅ Limpiar formulario y archivos
        limpiarFormularioPago();

        // ✅ Actualizar tabla y totales sin recargar página
        $('#resettabla').load(location.href + ' #resettabla');
        $('#reset_totales').load(location.href + ' #reset_totales');

        // ✅ Ir a target2
        activarTarget(2);
        var el = document.getElementById('target2');
        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
      } else {
        $('#mensajepagoproveedores').html(data);
      }
    }).fail(function () {
      console.error('[enviarPAGOPROVEEDORES] Error en la petición AJAX.');
    });
  });


  /* ---------------------------------------------------
     BORRAR DOCUMENTO (SBborrar2) — comportamiento original
  --------------------------------------------------- */
  $(document).on('click', '.view_dataSBborrar2', function () {
    var borra_id_sb = $(this).attr('id');
    $('#dataModal3').modal('show');

    $('#btnYes').off('click').on('click', function () {
      $.ajax({
        url: 'comprobaciones/controladorPP.php',
        method: 'POST',
        data: { borra_id_sb: borra_id_sb, borrasbdoc: 'borrasbdoc' },
        beforeSend: function () { $('#mensajepagoproveedores').html('cargando...'); },
        success: function (data) {
          $('#dataModal3').modal('hide');
          $('#mensajepagoproveedores').html('<span id="ACTUALIZADO">' + data + '</span>');
          // Recarga solo la fila afectada y su par (comportamiento original)
          $('#' + borra_id_sb).load(location.href + ' #' + borra_id_sb);
          $('#A' + borra_id_sb).load(location.href + ' #A' + borra_id_sb);
        }
      });
    });
  });


  /* ---------------------------------------------------
     BORRAR PAGO PROVEEDOR (SBborrar) — comportamiento original
  --------------------------------------------------- */
  $(document).on('click', '.view_dataSBborrar', function () {
    var borra_id_PAGOP = $(this).attr('id');
    $('#dataModal3').modal('show');

    $('#btnYes').off('click').on('click', function () {
      $.ajax({
        url: 'comprobaciones/controladorPP.php',
        method: 'POST',
        data: { borra_id_PAGOP: borra_id_PAGOP, borrapagoaproveedores: 'borrapagoaproveedores' },
        beforeSend: function () { $('#mensajepagoproveedores').html('cargando...'); },
        success: function (data) {
          $('#dataModal3').modal('hide');
          $('#mensajepagoproveedores').html('<span id="ACTUALIZADO">' + data + '</span>');
          // Recarga totales y tabla (comportamiento original)
          $('#reset_totales').load(location.href + ' #reset_totales');
          load(1);
        }
      });
    });
  });


  /* ---------------------------------------------------
     VER / MODIFICAR REGISTROS (modales de detalle)
  --------------------------------------------------- */
  $(document).on('click', '.view_dataPAGOPROVEEmodifica', function () {
    var personal_id = $(this).attr('id');
    $.ajax({
      url: 'comprobaciones/VistaPreviapagoproveedor.php',
      method: 'POST',
      data: { personal_id: personal_id },
      beforeSend: function () { $('#mensajepagoproveedores').html('cargando...'); },
      success: function (data) {
        $('#personal_detalles').html(data);
        $('#dataModal').modal('toggle');
        recargarElemento('#reset_totales');
      }
    });
  });

  $(document).on('click', '.view_dataSUBIRCOM', function () {
    var personal_id = $(this).attr('id');
    $.ajax({
      url: 'comprobaciones/VistaPreviapagoproveedor2.php',
      method: 'POST',
      data: { personal_id: personal_id },
      beforeSend: function () { $('#mensajepagoproveedores').html('cargando...'); },
      success: function (data) {
        $('#personal_detalles').html(data);
        $('#dataModal').modal('toggle');
        recargarElemento('#reset_totales');
      }
    });
  });


  /* ---------------------------------------------------
     FILTROS DE ESTADO (SOLICITADO / APROBADO / etc.)
     Centralizado para evitar repetición
  --------------------------------------------------- */
  var filtrosEstado = ['SOLICITADO', 'APROBADO', 'RECHAZADO', 'PAGADO', 'BORRAR'];
  filtrosEstado.forEach(function (estado) {
    $(document).on('click', '.' + estado, function () {
      var payload = {};
      payload[estado] = estado;
      $.ajax({
        url: 'pagoproveedores/fetch_pagesPP.php',
        method: 'POST',
        data: payload,
        beforeSend: function () { $('#mensajeSUBIRFACTURA').html('cargando...'); },
        success: function () { /* recarga dinámica de resultados aquí si aplica */ }
      });
    });
  });


  /* ---------------------------------------------------
     BÚSQUEDA
  --------------------------------------------------- */
  $('#clickbuscar').on('click', function () {
    var formData = new FormData($('#buscaform')[0]);
    $.ajax({
      url: 'pagoproveedores/fetch_pagesPP.php',
      type: 'POST',
      dataType: 'html',
      data: formData,
      cache: false,
      contentType: false,
      processData: false
    }).done(function () {
      // Aquí puedes actualizar los resultados de búsqueda
    }).fail(function () {
      console.error('[clickbuscar] Error en búsqueda.');
    });
  });


  /* ---------------------------------------------------
     DATOS BANCARIOS 1
  --------------------------------------------------- */
  $('#enviarDATOSBANCARIOS1').on('click', function () {
    var formData = new FormData($('#DATOSBANCARIOS1form')[0]);
    $.ajax({
      url: 'comprobaciones/controladorPP.php',
      type: 'POST',
      dataType: 'html',
      data: formData,
      cache: false,
      contentType: false,
      processData: false
    }).done(function (data) {
      if ($.trim(data) === 'Ingresado' || $.trim(data) === 'Actualizado') {
        $('#mensajeDATOSBANCARIOS1').html('<span id="ACTUALIZADO">' + data + '</span>');
        recargarElemento('#resetBancario1p');
      } else {
        $('#mensajeDATOSBANCARIOS1').html(data);
      }
    }).fail(function () {
      console.error('[enviarDATOSBANCARIOS1] Error en la petición.');
    });
  });

  $(document).on('click', '.view_dataNUEVO', function () {
    var personal_id = $(this).attr('id');
    $.ajax({
      url: 'pagoproveedores/VistaPreviaDatosBancario1.php',
      method: 'POST',
      data: { personal_id: personal_id },
      beforeSend: function () { $('#mensajepagoproveedores').html('cargando...'); },
      success: function (data) {
        $('#personal_detalles2').html(data);
        $('#dataModal').modal('toggle');
      }
    });
  });

  $(document).on('click', '.view_data_bancario1p_modifica', function () {
    var personal_id = $(this).attr('id');
    $.ajax({
      url: 'pagoproveedores/VistaPreviaDatosBancario1.php',
      method: 'POST',
      data: { personal_id: personal_id },
      beforeSend: function () { $('#mensajeDATOSBANCARIOS1').html('cargando...'); },
      success: function (data) {
        $('#personal_detalles').html(data);
        $('#dataModal').modal('toggle');
      }
    });
  });

  $(document).on('click', '.view_databancario1borrar', function () {
    var borra_id_bancaP = $(this).attr('id');
    $('#dataModal3').modal('show');

    $('#btnYes').off('click').on('click', function () {
      $.ajax({
        url: 'comprobaciones/controladorPP.php',
        method: 'POST',
        data: { borra_id_bancaP: borra_id_bancaP, borra_datos_bancario1: 'borra_datos_bancario1' },
        beforeSend: function () { $('#mensajeREFERENCIAS').html('cargando...'); },
        success: function (data) {
          $('#dataModal3').modal('hide');
          $('#mensajeDATOSBANCARIOS1').html('<span id="ACTUALIZADO">' + data + '</span>');
          $('#resettabla').load(location.href + ' #resettabla');
          $('#reset_totales').load(location.href + ' #reset_totales');
          activarTarget(2);
          var el = document.getElementById('target2');
          if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      });
    });
  });


  /* ---------------------------------------------------
     MATCH: INBURSA / BBVA / AMEX / SANTANDER
     Centralizado en un helper
  --------------------------------------------------- */
  function bindMatch(selector, url) {
    $(document).on('click', selector, function () {
      var personal_id = $(this).attr('id');
      $.ajax({
        url: url,
        method: 'POST',
        data: { personal_id: personal_id },
        beforeSend: function () { $('#mensajeDATOSBANCARIOS1').html('cargando...'); },
        success: function (data) {
          $('#personal_detalles14').html(data);
          $('#dataModal14').modal('toggle');
        }
      });
    });
  }

  bindMatch('.view_MATCH2filtroinbursa', 'comprobacionesVYO/VistaPreviamatchinbursa.php');
  bindMatch('.view_MATCH2filtrobbva',    'comprobacionesVYO/VistaPreviamatchBBVA.php');
  bindMatch('.view_MATCH2filtroAMEX',   'comprobacionesVYO/VistaPreviamatchAMEX.php');
  bindMatch('.view_MATCH2filtroSIVALE', 'comprobacionesVYO/VistaPreviamatchSANTANDER.php');

}); // END $(document).ready
</script>
