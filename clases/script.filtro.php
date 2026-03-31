<!-- ===================== ESTILOS BITÁCORA TIMELINE + LOADER ===================== -->
<style>
/* Loader con animación */
.loader {
  border: 4px solid #f3f3f3;
  border-top: 4px solid #6a0dad;
  border-radius: 50%;
  width: 22px;
  height: 22px;
  animation: spin 1s linear infinite;
  display: inline-block;
  vertical-align: middle;
  margin-right: 8px;
}

@keyframes spin {
  0%   { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.msg-actualizando {
  font-weight: bold;
  font-size: 20px;
  color: #6a0dad;
  background: #f3e9fb;
  border-radius: 6px;
  padding: 6px 12px;
  display: inline-flex;
  align-items: center;
  box-shadow: 0px 2px 6px rgba(0,0,0,0.1);
}

/* ── Bitácora Timeline ── */
.badge-bitacora {
  font-size: 11px; font-weight: 500;
  padding: 3px 10px; border-radius: 20px; display: inline-block;
}
.badge-ingreso       { background:#E6F1FB; color:#0C447C; border:0.5px solid #B5D4F4; }
.badge-autorizacion  { background:#EAF3DE; color:#27500A; border:0.5px solid #C0DD97; }
.badge-actualizacion { background:#FAEEDA; color:#633806; border:0.5px solid #FAC775; }
.badge-pago          { background:#EAF3DE; color:#27500A; border:0.5px solid #C0DD97; }
.badge-cancelacion   { background:#FCEBEB; color:#501313; border:0.5px solid #F7C1C1; }
.badge-adjunto       { background:#F3E8FF; color:#5B21B6; border:0.5px solid #C4B5FD; }
.badge-rechazo       { background:#FEE2E2; color:#991B1B; border:0.5px solid #FCA5A5; }
.badge-default       { background:#f1f3f5; color:#444;    border:0.5px solid #dee2e6; }

.bitacora-timeline-wrap {
  max-height: 420px; overflow-y: auto; padding: 1.25rem 1.5rem;
}
.bitacora-dot {
  width: 34px; height: 34px; border-radius: 50%; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center;
  border-width: 2px; border-style: solid;
}
.bitacora-line {
  width: 1px; background: #dee2e6; flex: 1; margin: 4px 0; min-height: 28px;
}
.bitacora-avatar {
  width: 20px; height: 20px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 10px; font-weight: 500; flex-shrink: 0;
}
.bitacora-strip {
  background: #E6F1FB; border-bottom: 0.5px solid #B5D4F4;
  padding: .55rem 1.25rem; font-size: 12px; color: #185FA5;
  display: flex; gap: 1.5rem; flex-wrap: wrap;
}
.bitacora-strip b { color: #0C447C; }
</style>

<!-- ===================== MODAL BITÁCORA TIMELINE ===================== -->
<div class="modal fade" id="modalBitacoraPago" tabindex="-1" aria-labelledby="modalBitacoraPagoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg overflow-hidden">

      <!-- Header azul -->
      <div class="modal-header border-0 px-4 py-3 text-white" style="background:#185FA5;">
        <div class="d-flex align-items-center gap-2">
          <div class="rounded-circle d-flex align-items-center justify-content-center"
               style="width:34px;height:34px;background:rgba(255,255,255,.2);">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
              <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
            </svg>
          </div>
          <div>
            <h6 class="mb-0 fw-bold" id="modalBitacoraPagoLabel">Bitácora de movimientos</h6>
            <small class="opacity-75" id="bitacoraSubLabel">Cargando...</small>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- Strip info rápida -->
      <div id="bitacoraStrip" class="bitacora-strip" style="display:none;"></div>

      <!-- Body timeline -->
      <div class="modal-body p-0" id="bitacoraPagoBody" style="background:#f8fafc;">
        <div class="text-center py-4 text-muted">
          <span class="spinner-border spinner-border-sm me-2"></span>Cargando bitácora...
        </div>
      </div>

      <div class="modal-footer border-0 bg-white py-2">
        <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Cerrar</button>
      </div>

    </div>
  </div>
</div>

<!-- ===================== SCRIPT ===================== -->
<script type="text/javascript">

/* ─────────────────────────────────────────────────────────────────────
   FUNCIONES EXISTENTES (sin cambios)
   ───────────────────────────────────────────────────────────────────── */

function pasarpagado2(pasarpagado_id){
	var checkBox = document.getElementById("pasarpagado1a"+pasarpagado_id);
	var pasarpagado_text = checkBox.checked ? "si" : "no";
	$.ajax({
		url:'pagoproveedores/controladorPP.php',
		method:'POST',
		data:{pasarpagado_id:pasarpagado_id,pasarpagado_text:pasarpagado_text},
		beforeSend:function(){ $('#pasarpagado2').html('cargando'); },
		success:function(data){
			$('#pasarpagado2').html("<span>ACTUALIZADO</span>").fadeIn().delay(500).fadeOut();
			load(1);
			if(pasarpagado_text=='si'){ $('#color_pagado1a'+pasarpagado_id).css('background-color', '#ceffcc'); }
			if(pasarpagado_text=='no'){ $('#color_pagado1a'+pasarpagado_id).css('background-color', '#e9d8ee'); }
		}
	});
}

function STATUS_RESPONSABLE_EVENTO(RESPONSABLE_EVENTO_id){
	var checkBox = document.getElementById("STATUS_RESPONSABLE_EVENTO"+RESPONSABLE_EVENTO_id);
	var RESPONSABLE_text = checkBox.checked ? "si" : "no";
	$.ajax({
		url:'pagoproveedores/controladorPP.php',
		method:'POST',
		data:{RESPONSABLE_EVENTO_id:RESPONSABLE_EVENTO_id,RESPONSABLE_text:RESPONSABLE_text},
		beforeSend:function(){ $('#pasarpagado2').html('cargando'); },
		success:function(data){
			var result = data.split('^');
			$('#pasarpagado2').html("<span id='ACTUALIZADO'>"+result[0]+"</span>");
			if(result[1]=='si'){ $('#color_RESPONSABLE_EVENTO'+RESPONSABLE_EVENTO_id).css('background-color', '#ceffcc'); }
			if(result[1]=='no'){ $('#color_RESPONSABLE_EVENTO'+RESPONSABLE_EVENTO_id).css('background-color', '#e9d8ee'); }
		}
	});
}

function STATUS_AUDITORIA1(AUDITORIA1_id){
	var checkBox = document.getElementById("STATUS_AUDITORIA1"+AUDITORIA1_id);
	var AUDITORIA1_text = checkBox.checked ? "si" : "no";
	$.ajax({
		url:'pagoproveedores/controladorPP.php',
		method:'POST',
		data:{AUDITORIA1_id:AUDITORIA1_id,AUDITORIA1_text:AUDITORIA1_text},
		beforeSend:function(){ $('#STATUS_AUDITORIA1').html('cargando'); },
		success:function(data){
			$('#STATUS_AUDITORIA1').html("ACTUALIZADO").fadeIn().delay(1000).fadeOut();
			load(1);
			if(data.split('^')[1]=='si'){ $('#color_AUDITORIA1'+AUDITORIA1_id).css('background-color', '#ceffcc'); }
			if(data.split('^')[1]=='no'){ $('#color_AUDITORIA1'+AUDITORIA1_id).css('background-color', '#e9d8ee'); }
		}
	});
}

function STATUS_CHECKBOX(CHECKBOX_id, permisoModificar) {
	var checkBox = document.getElementById("STATUS_CHECKBOX" + CHECKBOX_id);
	var CHECKBOX_text = checkBox.checked ? "si" : "no";
	var newColor = checkBox.checked ? '#ceffcc' : '#e9d8ee';
	$('#color_CHECKBOX' + CHECKBOX_id).css('background-color', newColor);
	let monto = $('#montoOriginal_' + CHECKBOX_id).text().replace(/,/g, '');
	if (checkBox.checked && !permisoModificar) { setTimeout(() => { checkBox.disabled = true; }, 100); }
	if (checkBox.checked) {
		$('#valorCalculado_' + CHECKBOX_id).text('');
	} else {
		if (!isNaN(monto)) {
			let resultado = monto * 1.46;
			$('#valorCalculado_' + CHECKBOX_id).text('$' + resultado.toLocaleString('es-MX', {minimumFractionDigits:2,maximumFractionDigits:2}));
		} else { $('#valorCalculado_' + CHECKBOX_id).text('NaN'); }
	}
	$.ajax({
		url: 'pagoproveedores/controladorPP.php',
		method: 'POST',
		data: { CHECKBOX_id: CHECKBOX_id, CHECKBOX_text: CHECKBOX_text },
		beforeSend: function() { $('#ajax-notification').html('<div class="loader"></div> ⏳ ACTUALIZANDO...').fadeIn(); },
		success: function(data) {
			var result = data.split('^');
			$('#ajax-notification').html("✅ ACTUALIZADO").delay(1000).fadeOut();
			if (result[1] === 'si') {
				$('#color_CHECKBOX' + CHECKBOX_id).css('background-color', '#ceffcc');
				$('#valorCalculado_' + CHECKBOX_id).text('');
				if (!permisoModificar) { checkBox.disabled = true; }
			} else if (result[1] === 'no') {
				$('#color_CHECKBOX' + CHECKBOX_id).css('background-color', '#e9d8ee');
				if (!isNaN(monto)) {
					let resultado = monto * 1.46;
					$('#valorCalculado_' + CHECKBOX_id).text('$' + resultado.toLocaleString('es-MX', {minimumFractionDigits:2,maximumFractionDigits:2}));
				} else { $('#valorCalculado_' + CHECKBOX_id).text('NaN'); }
				checkBox.disabled = false;
			}
		},
		error: function() {
			checkBox.checked = !checkBox.checked;
			let originalColor = checkBox.checked ? '#ceffcc' : '#e9d8ee';
			$('#color_CHECKBOX' + CHECKBOX_id).css('background-color', originalColor);
			checkBox.disabled = false;
			$('#ajax-notification').html("❌ Error al actualizar").delay(2000).fadeOut();
		}
	});
	recalcularTotal();
}

function recalcularTotal() {
	let total = 0;
	$('[id^=valorCalculado_]').each(function() {
		let texto = $(this).text().replace(/[$,]/g, '');
		let valor = parseFloat(texto);
		if (!isNaN(valor)) { total += valor; }
	});
	$('#totalCalculado').text('$' + total.toLocaleString('es-MX', {minimumFractionDigits:2,maximumFractionDigits:2}));
}

function STATUS_AUDITORIA3(id){
	var $cb = $("#STATUS_AUDITORIA3" + id);
	var permGuardar   = ($cb.data("perm-guardar")   == 1);
	var permModificar = ($cb.data("perm-modificar") == 1);
	var valorPrevio   = String($cb.data("prev"));
	var valorNuevo    = $cb.is(":checked") ? "si" : "no";
	if(!permGuardar && !permModificar){ $cb.prop('checked', (valorPrevio === 'si')); showNotify("Sin permiso para modificar", false); return; }
	if(!permModificar && valorPrevio === 'si' && valorNuevo === 'no'){ $cb.prop('checked', true); showNotify("Solo puedes prender, no apagar", false); return; }
	$("#color_AUDITORIA3" + id).css('background-color', (valorNuevo === 'si') ? '#ceffcc' : '#e9d8ee');
	$.ajax({
		url: 'pagoproveedores/controladorPP.php',
		type: 'POST',
		data: { AUDITORIA3_id: id, AUDITORIA3_text: valorNuevo },
		beforeSend: function(){ $('#pasarpagado2').html('cargando...'); },
		success: function(resp){
			$cb.data("prev", valorNuevo);
			if(!permModificar && permGuardar && valorNuevo === 'si'){
				$cb.prop('disabled', true).css('cursor','not-allowed').attr('title','Autorizado (bloqueado)');
			}
			$('#pasarpagado2').html("<span>ACTUALIZADO</span>").fadeIn().delay(500).fadeOut();
			showNotify("Autorización actualizada ✅", true);
			load(1);
		},
		error: function(xhr){
			var volverSi = (valorPrevio === 'si');
			$cb.prop('checked', volverSi);
			$("#color_AUDITORIA3" + id).css('background-color', volverSi ? '#ceffcc' : '#e9d8ee');
			showNotify("❌ Error de conexión (" + xhr.status + ")", false);
		}
	});
}

function showNotify(msg, ok){
	$("#ajax-notification").stop(true,true).text(msg).css('background', ok ? '#4CAF50' : '#E53935').fadeIn(150).delay(1000).fadeOut(300);
}

function STATUS_SINXML(id){
	var $cb = $("#STATUS_SINXML" + id);
	var permGuardar2   = ($cb.data("perm-guardar2")   == 1);
	var permModificar2 = ($cb.data("perm-modificar2") == 1);
	var valorPrevio2   = String($cb.data("prev2"));
	var valorNuevo2    = $cb.is(":checked") ? "si" : "no";
	if(!permGuardar2 && !permModificar2){ $cb.prop('checked', (valorPrevio2 === 'si')); showNotify2("Sin permiso para modificar", false); return; }
	if(!permModificar2 && valorPrevio2 === 'si' && valorNuevo2 === 'no'){ $cb.prop('checked', true); showNotify2("Solo puedes prender, no apagar", false); return; }
	$("#color_SINXML" + id).css('background-color', (valorNuevo2 === 'si') ? '#ceffcc' : '#e9d8ee');
	$.ajax({
		url: 'pagoproveedores/controladorPP.php',
		type: 'POST',
		data: { SINXML_id: id, SINXML_text: valorNuevo2 },
		beforeSend: function(){ $('#pasarpagado2').html('cargando...'); },
		success: function(resp){
			$cb.data("prev2", valorNuevo2);
			if(!permModificar2 && permGuardar2 && valorNuevo2 === 'si'){
				$cb.prop('disabled', true).css('cursor','not-allowed').attr('title','Autorizado (bloqueado)');
			}
			$('#pasarpagado2').html("<span>ACTUALIZADO</span>").fadeIn().delay(500).fadeOut();
			showNotify2("Autorización actualizada ✅", true);
			load(1);
		},
		error: function(xhr){
			var volverSi = (valorPrevio2 === 'si');
			$cb.prop('checked', volverSi);
			$("#color_SINXML" + id).css('background-color', volverSi ? '#ceffcc' : '#e9d8ee');
			showNotify2("❌ Error de conexión (" + xhr.status + ")", false);
		}
	});
}

function showNotify2(msg, ok){
	$("#ajax-notification").stop(true,true).text(msg).css('background', ok ? '#4CAF50' : '#E53935').fadeIn(150).delay(1000).fadeOut(300);
}

function STATUS_AUDITORIA2(AUDITORIA2_id){
	var checkBox = document.getElementById("STATUS_AUDITORIA2"+AUDITORIA2_id);
	var AUDITORIA2_text = checkBox.checked ? "si" : "no";
	$.ajax({
		url:'pagoproveedores/controladorPP.php',
		method:'POST',
		data:{AUDITORIA2_id:AUDITORIA2_id,AUDITORIA2_text:AUDITORIA2_text},
		beforeSend:function(){ $('#pasarpagado2').html('cargando'); },
		success:function(data){
			var result = data.split('^');
			$('#pasarpagado2').html("Cargando...").fadeIn().delay(500).fadeOut();
			load(1);
			if(result[1]=='si'){ $('#color_AUDITORIA2'+AUDITORIA2_id).css('background-color', '#ceffcc'); }
			if(result[1]=='no'){ $('#color_AUDITORIA2'+AUDITORIA2_id).css('background-color', '#e9d8ee'); }
		}
	});
}

function STATUS_FINANZAS(FINANZAS_id){
	var checkBox = document.getElementById("STATUS_FINANZAS"+FINANZAS_id);
	var FINANZAS_text = checkBox.checked ? "si" : "no";
	$.ajax({
		url:'pagoproveedores/controladorPP.php',
		method:'POST',
		data:{FINANZAS_id:FINANZAS_id,FINANZAS_text:FINANZAS_text},
		beforeSend:function(){ $('#pasarpagado2').html('cargando'); },
		success:function(data){
			var result = data.split('^');
			$('#pasarpagado2').html("Cargando...").fadeIn().delay(500).fadeOut();
			load(1);
			if(result[1]=='si'){ $('#color_FINANZAS'+FINANZAS_id).css('background-color', '#ceffcc'); }
			if(result[1]=='no'){ $('#color_FINANZAS'+FINANZAS_id).css('background-color', '#e9d8ee'); }
		}
	});
}

function STATUS_RECHAZADO(RECHAZADO_id){
	var checkBox = document.getElementById("STATUS_RECHAZADO"+RECHAZADO_id);
	var $checkBox = $(checkBox);
	var RECHAZADO_text = checkBox.checked ? "si" : "no";
	if(RECHAZADO_text === 'no'){ $checkBox.data('forzarAgregarMotivo', 'si'); }
	else if(RECHAZADO_text === 'si' && $checkBox.data('forzarAgregarMotivo') !== 'si'){ $checkBox.removeData('forzarAgregarMotivo'); 
	}

	actualizarBotonesRechazo(RECHAZADO_id, RECHAZADO_text);
	actualizarEstadoVentasPorRechazo(RECHAZADO_id, RECHAZADO_text);
	load(obtenerPaginaActualFiltro());
	$.ajax({
		url:'pagoproveedores/controladorPP.php',
		method:'POST',
		data:{RECHAZADO_id:RECHAZADO_id,RECHAZADO_text:RECHAZADO_text},
		beforeSend:function(){ $('#pasarpagado2').html('cargando'); },
		success:function(data){
			var result = data.split('^');
			$('#pasarpagado2').html("Cargando...").fadeIn().delay(500).fadeOut();
			if(result[1]=='si') $('#color_RECHAZADO'+RECHAZADO_id).css('background-color', '#ceffcc');
			if(result[1]=='no') $('#color_RECHAZADO'+RECHAZADO_id).css('background-color', '#e9d8ee');
			if(result[1] == 'si' || result[1] == 'no'){
			if(result[1] == 'si' && $checkBox.data('forzarAgregarMotivo') !== 'si'){
					$checkBox.removeData('forzarAgregarMotivo');
				}
				actualizarBotonesRechazo(RECHAZADO_id, result[1]);
				actualizarEstadoVentasPorRechazo(RECHAZADO_id, result[1]);
			}
		}
	});
}


function actualizarEstadoVentasPorRechazo(RECHAZADO_id, statusRechazado){
	var $ventas = $('#STATUS_VENTAS'+RECHAZADO_id);
	if($ventas.length === 0){ return; }
	var permisoPrincipal = ($ventas.data('permiso-principal') === 'si');
	if(statusRechazado === 'si'){
		$ventas.prop('disabled', true)
			.css('cursor', 'not-allowed')
			.attr('title', 'No se puede autorizar por ventas: pago rechazado');
		return;
	}
	if($ventas.is(':checked')){
		$ventas.prop('disabled', true)
			.css('cursor', 'not-allowed')
			.attr('title', 'Ya autorizado por ventas');
		return;
	}
	if(permisoPrincipal){
		$ventas.prop('disabled', false)
			.css('cursor', 'pointer')
			.attr('title', '');
	} else {
		$ventas.prop('disabled', true)
			.css('cursor', 'not-allowed')
			.attr('title', 'Sin permiso principal para autorizar por ventas');
	}
}

function abrirFormularioRechazo(RECHAZADO_id){
	var motivoActual = $('#motivo_rechazo_'+RECHAZADO_id).val() || '';
	$('#modal_rechazo_id').val(RECHAZADO_id);
	configurarModalRechazo('editar', motivoActual, 'Captura el motivo y presiona Guardar.');
	$('#btn_guardar_rechazo_modal').off('click').on('click', function(){ guardarMotivoRechazoModal(); });
}

function guardarMotivoRechazoModal(){
	var RECHAZADO_id = $('#modal_rechazo_id').val();
	var motivo = ($('#modal_rechazo_texto').val() || '').trim();
	if(motivo === ''){ $('#modal_rechazo_mensaje').text('Debes capturar un motivo de rechazo.').css('color', '#b22222'); return; }
	$.ajax({
		url:'pagoproveedores/controladorPP.php',
		method:'POST',
		data:{RECHAZO_MOTIVO_id:RECHAZADO_id,RECHAZO_MOTIVO_text:motivo},
		success:function(resp){
			if(resp.indexOf('ok') !== -1){
				$('#motivo_rechazo_'+RECHAZADO_id).val(motivo);
				$('#STATUS_RECHAZADO'+RECHAZADO_id).removeData('forzarAgregarMotivo');
				actualizarBotonesRechazo(RECHAZADO_id);
				$('#modal_rechazo_mensaje').text('Motivo guardado correctamente.').css('color', '#228b22');
				setTimeout(function(){ cerrarModalRechazoPago(); }, 400);
			}else{
				$('#modal_rechazo_mensaje').text('No fue posible guardar el motivo.').css('color', '#b22222');
			}
		}
	});
}

function verMotivoRechazo(RECHAZADO_id){
	var motivoLocal = $('#motivo_rechazo_'+RECHAZADO_id).val() || '';
	$('#modal_rechazo_id').val(RECHAZADO_id);
	if(motivoLocal !== ''){ configurarModalRechazo('ver', motivoLocal, 'Consulta del motivo registrado.'); return; }
	$.ajax({
		url:'pagoproveedores/controladorPP.php',
		method:'POST',
		data:{RECHAZO_MOTIVO_VER_id:RECHAZADO_id},
		success:function(resp){
			var motivo = (resp || '').trim();
			if(motivo !== ''){ $('#motivo_rechazo_'+RECHAZADO_id).val(motivo); configurarModalRechazo('ver', motivo, 'Consulta del motivo registrado.'); }
			else{ configurarModalRechazo('ver', 'No hay motivo de rechazo registrado.', 'Consulta del motivo registrado.'); }
		}
	});
}

function configurarModalRechazo(modo, texto, mensaje){
	var esVer = (modo === 'ver');
	$('#modalRechazoPagoLabel').text(esVer ? 'Ver motivo del rechazo' : 'Agregar motivo del rechazo');
	$('#modal_rechazo_texto').val(texto || '').prop('readonly', esVer);
	$('#modal_rechazo_mensaje').text(mensaje || '').css('color', '#666');
	$('#btn_guardar_rechazo_modal').toggle(!esVer);
	mostrarModalRechazoPago();
}

function actualizarBotonesRechazo(RECHAZADO_id, statusRechazado){
	var statusActual = statusRechazado;
	if(typeof statusActual === 'undefined'){ statusActual = $('#STATUS_RECHAZADO'+RECHAZADO_id).is(':checked') ? 'si' : 'no'; }
	var motivo = ($('#motivo_rechazo_'+RECHAZADO_id).val() || '').trim();
	var forzarAgregarMotivo = ($('#STATUS_RECHAZADO'+RECHAZADO_id).data('forzarAgregarMotivo') === 'si');
	var mostrarVer = (statusActual === 'si' && motivo !== '');
	var mostrarAgregar = (statusActual === 'si' && (motivo === '' || forzarAgregarMotivo));
	if(forzarAgregarMotivo && statusActual === 'si'){ mostrarVer = false; }
	$('#agregar_rechazo_'+RECHAZADO_id).toggle(mostrarAgregar);
	$('#ver_rechazo_'+RECHAZADO_id).toggle(mostrarVer);
}

function mostrarModalRechazoPago(){
	if($('#modalRechazoPago').length === 0){ return; }
	if(typeof $('#modalRechazoPago').modal === 'function'){ $('#modalRechazoPago').modal('show'); }
	else { $('#modalRechazoPago').show(); }
}

function cerrarModalRechazoPago(){
	if($('#modalRechazoPago').length === 0){ return; }
	if(typeof $('#modalRechazoPago').modal === 'function'){ $('#modalRechazoPago').modal('hide'); }
	else { $('#modalRechazoPago').hide(); }
}

function STATUS_VENTAS(VENTAS_id){
	var checkBox = document.getElementById("STATUS_VENTAS"+VENTAS_id);
	var VENTAS_text = checkBox.checked ? "si" : "no";
	$.ajax({
		url:'pagoproveedores/controladorPP.php',
		method:'POST',
		data:{VENTAS_id:VENTAS_id,VENTAS_text:VENTAS_text},
		beforeSend:function(){ $('#pasarpagado2').html('cargando'); },
		success:function(data){
			var result = data.split('^');
			$('#pasarpagado2').html("Cargando...").fadeIn().delay(500).fadeOut();
			if(result[1]=='si'){
				$('#color_VENTAS'+VENTAS_id).css('background-color', '#ceffcc');
				$('#STATUS_RECHAZADO'+VENTAS_id).prop('checked', false).prop('disabled', true).css('cursor', 'not-allowed').attr('title', 'No se puede rechazar: autorizado por ventas');
				$('#agregar_rechazo_'+VENTAS_id).hide();
				$('#ver_rechazo_'+VENTAS_id).hide();
			}
			if(result[1]=='no'){
				$('#color_VENTAS'+VENTAS_id).css('background-color', '#e9d8ee');
				$('#STATUS_RECHAZADO'+VENTAS_id).prop('disabled', false).css('cursor', 'pointer').attr('title', '');
				actualizarBotonesRechazo(VENTAS_id);
			}
		}
	});
}

function obtenerPaginaActualFiltro(){
	var paginaActual = parseInt($('.pagination li.active a').first().text(), 10);
	if(isNaN(paginaActual) || paginaActual <= 0){ paginaActual = 1; }
	return paginaActual;
}

function pad(n){ return n < 10 ? '0'+n : n; }

function actualizarFechaHora(){
	const now = new Date();
	const fecha = pad(now.getDate()) + '-' + pad(now.getMonth() + 1) + '-' + now.getFullYear();
	const hora  = pad(now.getHours()) + ':' + pad(now.getMinutes()) + ':' + pad(now.getSeconds());
	if(document.getElementById('fecha')) document.getElementById('fecha').textContent = fecha;
	if(document.getElementById('hora'))  document.getElementById('hora').textContent  = hora;
	if(document.getElementById('FECHA_DE_LLENADO')){
		document.getElementById('FECHA_DE_LLENADO').value =
			now.getFullYear() + '-' + pad(now.getMonth() + 1) + '-' + pad(now.getDate()) + ' ' + hora;
	}
}
actualizarFechaHora();
setInterval(actualizarFechaHora, 1000);

function LIMPIAR(){
	var campos = ["UUID","metodoDePago","totalf","serie","folio","regimenE","UsoCFDI",
		"TImpuestosTrasladados","TImpuestosRetenidos","Version","tipoDeComprobante",
		"condicionesDePago","fechaTimbrado","nombreR","rfcR","Moneda","TipoCambio",
		"ValorUnitarioConcepto","DescripcionConcepto","ClaveUnidadConcepto",
		"ClaveProdServConcepto","CantidadConcepto","ImporteConcepto","UnidadConcepto",
		"TUA","TuaTotalCargos","Descuento","ID_RELACIONADO","IVA","IEPS",
		"NUMERO_CONSECUTIVO_PROVEE_2","NOMBRE_COMERCIAL_2","RAZON_SOCIAL_2",
		"RFC_PROVEEDOR_2","NUMERO_EVENTO_2","NOMBRE_EVENTO_2","MOTIVO_GASTO_2",
		"CONCEPTO_PROVEE_2","MONTO_TOTAL_COTIZACION_ADEUDO_2","MONTO_FACTURA_2",
		"MONTO_PROPINA_2","MONTO_DEPOSITAR_2","TIPO_DE_MONEDA_2","PFORMADE_PAGO_2",
		"ID_RELACIONADO_2","FECHA_DE_PAGO","FECHA_DE_PAGO2a","FECHA_A_DEPOSITAR_2",
		"STATUS_DE_PAGO_2","ACTIVO_FIJO_2","GASTO_FIJO_2","PAGAR_CADA_2","FECHA_PPAGO_2",
		"FECHA_TPROGRAPAGO_2","NUMERO_EVENTOFIJO_2","CLASI_GENERAL_2","SUB_GENERAL_2",
		"MONTO_DEPOSITADO_2","NUMERO_EVENTO1_2","CLASIFICACION_GENERAL_2",
		"CLASIFICACION_ESPECIFICA_2","PLACAS_VEHICULO_2","MONTO_DE_COMISION_2",
		"POLIZA_NUMERO_2","NOMBRE_DEL_EJECUTIVO_2","NOMBRE_DEL_AYUDO_2","OBSERVACIONES_2",
		"FECHA_DE_LLENADO_2","subTotal11","TIPO_CAMBIOP","TOTAL_ENPESOS",
		"IMPUESTO_HOSPEDAJE","propina","IVAXML","IEPSXML","P_TIPO_DE_MONEDA_1",
		"P_INSTITUCION_FINANCIERA_1","P_NUMERO_DE_CUENTA_DB_1","P_NUMERO_CLABE_1",
		"P_NUMERO_IBAN_1","P_NUMERO_CUENTA_SWIFT_1","FOTO_ESTADO_PROVEE",
		"ULTIMA_CARGA_DATOBANCA","TImpuestosRetenidos"];
	campos.forEach(function(id){ var el = document.getElementById(id); if(el) el.value = ''; });
	var vacio = document.getElementById("FECHA_DE_PAGO_VACIO");
	if(vacio) vacio.checked = false;
}

$(function() {
	const triggerSearch = () => load(1);
	$('#target2').on('keydown', 'thead input, thead select', function(event) {
		if (event.key === 'Enter' || event.which === 13) { event.preventDefault(); triggerSearch(); }
	});
	$('#target2').on('keydown', '#FECHA_DE_PAGO, #FECHA_DE_PAGO2a', function(event) {
		if (event.key === 'Enter' || event.which === 13) { event.preventDefault(); triggerSearch(); }
	});
	$('#target2').on('change', '#FECHA_DE_PAGO_VACIO', function () {
		$("#FECHA_DE_PAGO").val("");
		$("#FECHA_DE_PAGO2a").val("");
		triggerSearch();
	});
	load(1);
});

function load(page){
	var query=$("#NOMBRE_EVENTO").val();
	var DEPARTAMENTO2=$("#DEPARTAMENTO2WE").val();
	var NUMERO_CONSECUTIVO_PROVEE=$("#NUMERO_CONSECUTIVO_PROVEE_2").val();
	var NOMBRE_COMERCIAL=$("#NOMBRE_COMERCIAL_2").val();
	var VIATICOSOPRO=$("#VIATICOSOPRO_2").val();
	var RAZON_SOCIAL=$("#RAZON_SOCIAL_2").val();
	var RFC_PROVEEDOR=$("#RFC_PROVEEDOR_2").val();
	var NUMERO_EVENTO=$("#NUMERO_EVENTO_2").val();
	var NOMBRE_EVENTO=$("#NOMBRE_EVENTO_2").val();
	var MOTIVO_GASTO=$("#MOTIVO_GASTO_2").val();
	var CONCEPTO_PROVEE=$("#CONCEPTO_PROVEE_2").val();
	var MONTO_TOTAL_COTIZACION_ADEUDO=$("#MONTO_TOTAL_COTIZACION_ADEUDO_2").val();
	var MONTO_FACTURA=$("#MONTO_FACTURA_2").val();
	var MONTO_PROPINA=$("#MONTO_PROPINA_2").val();
	var MONTO_DEPOSITAR=$("#MONTO_DEPOSITAR_2").val();
	var MONTO_DEPOSITADO=$("#MONTO_DEPOSITADO_2").val();
	var TIPO_DE_MONEDA=$("#TIPO_DE_MONEDA_2").val();
	var PFORMADE_PAGO=$("#PFORMADE_PAGO_2").val();
	var FECHA_DE_PAGO=$("#FECHA_DE_PAGO").val();
	var FECHA_DE_PAGO2a=$("#FECHA_DE_PAGO2a").val();
	var FECHA_DE_PAGO_VACIO=$("#FECHA_DE_PAGO_VACIO").is(":checked") ? '1' : '';
	var FECHA_A_DEPOSITAR=$("#FECHA_A_DEPOSITAR_2").val();
	var STATUS_DE_PAGO=$("#STATUS_DE_PAGO_2").val();
	var ACTIVO_FIJO=$("#ACTIVO_FIJO_2").val();
	var GASTO_FIJO=$("#GASTO_FIJO_2").val();
	var PAGAR_CADA=$("#PAGAR_CADA_2").val();
	var FECHA_PPAGO=$("#FECHA_PPAGO_2").val();
	var FECHA_TPROGRAPAGO=$("#FECHA_TPROGRAPAGO_2").val();
	var NUMERO_EVENTOFIJO=$("#NUMERO_EVENTOFIJO_2").val();
	var CLASI_GENERAL=$("#CLASI_GENERAL_2").val();
	var SUB_GENERAL=$("#SUB_GENERAL_2").val();
	var NUMERO_EVENTO1=$("#NUMERO_EVENTO1_2").val();
	var CLASIFICACION_GENERAL=$("#CLASIFICACION_GENERAL_2").val();
	var CLASIFICACION_ESPECIFICA=$("#CLASIFICACION_ESPECIFICA_2").val();
	var PLACAS_VEHICULO=$("#PLACAS_VEHICULO_2").val();
	var MONTO_DE_COMISION=$("#MONTO_DE_COMISION_2").val();
	var POLIZA_NUMERO=$("#POLIZA_NUMERO_2").val();
	var NOMBRE_DEL_EJECUTIVO=$("#NOMBRE_DEL_EJECUTIVO_2").val();
	var NOMBRE_DEL_AYUDO=$("#NOMBRE_DEL_AYUDO_2").val();
	var OBSERVACIONES_2=$("#OBSERVACIONES_1_2").val();
	var FECHA_DE_LLENADO=$("#FECHA_DE_LLENADO_2").val();
	var hiddenpagoproveedores=$("#hiddenpagoproveedores_2").val();
	var RAZON_SOCIAL_orden=$("#RAZON_SOCIAL_orden").val();
	var RFC_PROVEEDOR_orden=$("#RFC_PROVEEDOR_orden").val();
	var MONTO_FACTURA_orden=$("#MONTO_FACTURA_orden").val();
	var TIPO_CAMBIOP=$("#TIPO_CAMBIOP").val();
	var TOTAL_ENPESOS=$("#TOTAL_ENPESOS").val();
	var IMPUESTO_HOSPEDAJE=$("#IMPUESTO_HOSPEDAJE").val();
	var ID_RELACIONADO=$("#ID_RELACIONADO").val();
	var IVA=$("#IVA_1").val();
	var IEPS=$("#IEPS").val();
	var TImpuestosRetenidosIVA=$("#TImpuestosRetenidosIVA_3").val();
	var TImpuestosRetenidosISR=$("#TImpuestosRetenidosISR_3").val();
	var descuentos=$("#descuentos_3").val();
	var NUMERO_EVENTO_orden=$("#NUMERO_EVENTO_orden").val();
	var UUID=$("#UUID_1").val();
	var metodoDePago=$("#metodoDePago_1").val();
	var totalf=$("#totalf_1").val();
	var serie=$("#serie_1").val();
	var folio=$("#folio_1").val();
	var regimenE=$("#regimenE_1").val();
	var UsoCFDI=$("#UsoCFDI_1").val();
	var TImpuestosTrasladados=$("#TImpuestosTrasladados_1").val();
	var TImpuestosRetenidos=$("#TImpuestosRetenidos_1").val();
	var Version=$("#Version_1").val();
	var tipoDeComprobante=$("#tipoDeComprobante_1").val();
	var condicionesDePago=$("#condicionesDePago_1").val();
	var fechaTimbrado=$("#fechaTimbrado_1").val();
	var nombreR=$("#nombreR_1").val();
	var rfcR=$("#rfcR_1").val();
	var Moneda=$("#Moneda_1").val();
	var TipoCambio=$("#TipoCambio_1").val();
	var ValorUnitarioConcepto=$("#ValorUnitarioConcepto_1").val();
	var DescripcionConcepto=$("#DescripcionConcepto_1").val();
	var ClaveUnidadConcepto=$("#ClaveUnidadConcepto_1").val();
	var ClaveProdServConcepto=$("#ClaveProdServConcepto_1").val();
	var CantidadConcepto=$("#CantidadConcepto_1").val();
	var ImporteConcepto=$("#ImporteConcepto_1").val();
	var UnidadConcepto=$("#UnidadConcepto_1").val();
	var TUA=$("#TUA_1").val();
	var TuaTotalCargos=$("#TuaTotalCargos_1").val();
	var Descuento=$("#Descuento_1").val();
	var subTotal=$("#subTotal11").val();
	var propina=$("#propina_1").val();
	var IVAXML=$("#IVAXML_1").val();
	var IEPSXML=$("#IEPSXML_1").val();
	var P_TIPO_DE_MONEDA_1=$("#P_TIPO_DE_MONEDA_1").val();
	var P_INSTITUCION_FINANCIERA_1=$("#P_INSTITUCION_FINANCIERA_1").val();
	var P_NUMERO_DE_CUENTA_DB_1=$("#P_NUMERO_DE_CUENTA_DB_1").val();
	var P_NUMERO_CLABE_1=$("#P_NUMERO_CLABE_1").val();
	var P_NUMERO_IBAN_1=$("#P_NUMERO_IBAN_1").val();
	var P_NUMERO_CUENTA_SWIFT_1=$("#P_NUMERO_CUENTA_SWIFT_2").val();
	var FOTO_ESTADO_PROVEE=$("#FOTO_ESTADO_PROVEE").val();
	var ULTIMA_CARGA_DATOBANCA=$("#ULTIMA_CARGA_DATOBANCA").val();
	var per_page=$("#per_page").val();

	var parametros = {
		"action":"ajax","page":page,"query":query,"per_page":per_page,
		"NUMERO_CONSECUTIVO_PROVEE":NUMERO_CONSECUTIVO_PROVEE,
		"NOMBRE_COMERCIAL":NOMBRE_COMERCIAL,"VIATICOSOPRO":VIATICOSOPRO,
		"RAZON_SOCIAL":RAZON_SOCIAL,"RFC_PROVEEDOR":RFC_PROVEEDOR,
		"NUMERO_EVENTO":NUMERO_EVENTO,"NOMBRE_EVENTO":NOMBRE_EVENTO,
		"MOTIVO_GASTO":MOTIVO_GASTO,"CONCEPTO_PROVEE":CONCEPTO_PROVEE,
		"MONTO_TOTAL_COTIZACION_ADEUDO":MONTO_TOTAL_COTIZACION_ADEUDO,
		"MONTO_FACTURA":MONTO_FACTURA,"MONTO_PROPINA":MONTO_PROPINA,
		"MONTO_DEPOSITAR":MONTO_DEPOSITAR,"TIPO_DE_MONEDA":TIPO_DE_MONEDA,
		"PFORMADE_PAGO":PFORMADE_PAGO,"FECHA_DE_PAGO":FECHA_DE_PAGO,
		"FECHA_DE_PAGO2a":FECHA_DE_PAGO2a,"FECHA_DE_PAGO_VACIO":FECHA_DE_PAGO_VACIO,
		"FECHA_A_DEPOSITAR":FECHA_A_DEPOSITAR,"STATUS_DE_PAGO":STATUS_DE_PAGO,
		"ACTIVO_FIJO":ACTIVO_FIJO,"GASTO_FIJO":GASTO_FIJO,"PAGAR_CADA":PAGAR_CADA,
		"FECHA_PPAGO":FECHA_PPAGO,"FECHA_TPROGRAPAGO":FECHA_TPROGRAPAGO,
		"NUMERO_EVENTOFIJO":NUMERO_EVENTOFIJO,"CLASI_GENERAL":CLASI_GENERAL,
		"SUB_GENERAL":SUB_GENERAL,"MONTO_DEPOSITADO":MONTO_DEPOSITADO,
		"NUMERO_EVENTO1":NUMERO_EVENTO1,"CLASIFICACION_GENERAL":CLASIFICACION_GENERAL,
		"CLASIFICACION_ESPECIFICA":CLASIFICACION_ESPECIFICA,"PLACAS_VEHICULO":PLACAS_VEHICULO,
		"MONTO_DE_COMISION":MONTO_DE_COMISION,"POLIZA_NUMERO":POLIZA_NUMERO,
		"NOMBRE_DEL_EJECUTIVO":NOMBRE_DEL_EJECUTIVO,"NOMBRE_DEL_AYUDO":NOMBRE_DEL_AYUDO,
		"OBSERVACIONES_2":OBSERVACIONES_2,"FECHA_DE_LLENADO":FECHA_DE_LLENADO,
		"hiddenpagoproveedores":hiddenpagoproveedores,
		"RAZON_SOCIAL_orden":RAZON_SOCIAL_orden,"RFC_PROVEEDOR_orden":RFC_PROVEEDOR_orden,
		"MONTO_FACTURA_orden":MONTO_FACTURA_orden,"NUMERO_EVENTO_orden":NUMERO_EVENTO_orden,
		"TIPO_CAMBIOP":TIPO_CAMBIOP,"TOTAL_ENPESOS":TOTAL_ENPESOS,
		"IMPUESTO_HOSPEDAJE":IMPUESTO_HOSPEDAJE,"ID_RELACIONADO":ID_RELACIONADO,
		"IEPS":IEPS,"IVA":IVA,"TImpuestosRetenidosIVA_3":TImpuestosRetenidosIVA,
		"TImpuestosRetenidosISR_3":TImpuestosRetenidosISR,"descuentos_3":descuentos,
		"UUID":UUID,"metodoDePago":metodoDePago,"totalf":totalf,"serie":serie,
		"folio":folio,"regimenE":regimenE,"UsoCFDI":UsoCFDI,
		"TImpuestosTrasladados":TImpuestosTrasladados,"TImpuestosRetenidos":TImpuestosRetenidos,
		"Version":Version,"tipoDeComprobante":tipoDeComprobante,
		"condicionesDePago":condicionesDePago,"fechaTimbrado":fechaTimbrado,
		"nombreR":nombreR,"rfcR":rfcR,"Moneda":Moneda,"TipoCambio":TipoCambio,
		"ValorUnitarioConcepto":ValorUnitarioConcepto,"DescripcionConcepto":DescripcionConcepto,
		"ClaveUnidadConcepto":ClaveUnidadConcepto,"ClaveProdServConcepto":ClaveProdServConcepto,
		"CantidadConcepto":CantidadConcepto,"ImporteConcepto":ImporteConcepto,
		"UnidadConcepto":UnidadConcepto,"TUA":TUA,"TuaTotalCargos":TuaTotalCargos,
		"Descuento":Descuento,"subTotal":subTotal,"propina":propina,
		"P_TIPO_DE_MONEDA_1":P_TIPO_DE_MONEDA_1,
		"P_INSTITUCION_FINANCIERA_1":P_INSTITUCION_FINANCIERA_1,
		"P_NUMERO_DE_CUENTA_DB_1":P_NUMERO_DE_CUENTA_DB_1,
		"P_NUMERO_CLABE_1":P_NUMERO_CLABE_1,"P_NUMERO_IBAN_1":P_NUMERO_IBAN_1,
		"P_NUMERO_CUENTA_SWIFT_1":P_NUMERO_CUENTA_SWIFT_1,
		"FOTO_ESTADO_PROVEE":FOTO_ESTADO_PROVEE,"ULTIMA_CARGA_DATOBANCA":ULTIMA_CARGA_DATOBANCA,
		"TImpuestosRetenidos_3":TImpuestosRetenidos,"DEPARTAMENTO2":DEPARTAMENTO2
	};

	$("#loader2").fadeIn('slow');
	$.ajax({
		url: 'ventasoperaciones/clases/controlador_filtro.php',
		type: 'POST',
		data: parametros,
		beforeSend: function(objeto) {
			$("#loader2").html('<div class="msg-actualizando"><span class="loader"></span> ⏳ ACTUALIZANDO...</div>').fadeIn();
			setTimeout(function() {
				$("#loader2").fadeOut('slow', function() { $(this).html(''); });
			}, 2000);
		},
		success: function (data) {
			$(".datos_ajax2").html(data).fadeIn('slow');
			$('.checkbox').each(function() {
				const id = $(this).data('id');
				if (localStorage.getItem('checkbox_' + id) === 'checked') {
					this.checked = true;
					this.closest('tr').style.filter = 'brightness(65%) sepia(100%) saturate(200%) hue-rotate(0deg)';
				}
			});
		}
	});
}

/* ─────────────────────────────────────────────────────────────────────
   BITÁCORA TIMELINE — HELPERS
   Reutiliza la misma lógica visual que pagoproveedores.
   La URL apunta a ventasoperaciones/clases/controlador_filtro.php
   que tiene el endpoint action=bitacora_pago.
   ───────────────────────────────────────────────────────────────────── */

function _bitacoraBadgeCfg(tipo) {
	var t = (tipo || '').toLowerCase();
	if (t.indexOf('ingres')   !== -1) return { cls:'badge-ingreso',       bg:'#E6F1FB', border:'#185FA5', iconPath:'M12 5v14M5 12l7-7 7 7' };
	if (t.indexOf('autori')   !== -1) return { cls:'badge-autorizacion',  bg:'#EAF3DE', border:'#3B6D11', iconPath:'M20 6L9 17l-5-5' };
	if (t.indexOf('actualiz') !== -1) return { cls:'badge-actualizacion', bg:'#FAEEDA', border:'#BA7517', iconPath:'M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4z' };
	if (t.indexOf('adjunto')  !== -1) return { cls:'badge-adjunto',       bg:'#F3E8FF', border:'#5B21B6', iconPath:'M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13' };
	if (t.indexOf('rechazo')  !== -1) return { cls:'badge-rechazo',       bg:'#FEE2E2', border:'#991B1B', iconPath:'M18 6L6 18M6 6l12 12' };
	if (t.indexOf('pago')     !== -1) return { cls:'badge-pago',          bg:'#EAF3DE', border:'#3B6D11', iconPath:'M12 1v22M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6' };
	if (t.indexOf('cancel')   !== -1) return { cls:'badge-cancelacion',   bg:'#FCEBEB', border:'#A32D2D', iconPath:'M18 6L6 18M6 6l12 12' };
	return                                   { cls:'badge-default',        bg:'#f1f3f5', border:'#adb5bd', iconPath:'M12 12m-4 0a4 4 0 108 0 4 4 0 10-8 0' };
}

function _bitacoraInitials(name) {
	if (!name || name === '-') return '?';
	return (name.trim().split(/\s+/).slice(0, 2).map(function(n){ return n[0]; }).join('')).toUpperCase();
}

function _bitacoraIcon(path) {
	return '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="' + path + '"/></svg>';
}

/* ── Click en botón BITÁCORA ── */
$(document).on('click', '.view_dataPAGOPROVEEbitacora', function () {
	var idSubetufactura = $(this).attr('id');

	// Resetear modal
	$('#bitacoraSubLabel').html('Solicitud <b>#...</b>');
	$('#bitacoraStrip').hide().html('');
	$('#bitacoraPagoBody').html(
		'<div class="text-center py-4 text-muted">' +
		'<span class="spinner-border spinner-border-sm me-2"></span>Cargando bitácora...</div>'
	);
	$('#modalBitacoraPago').modal('show');

	$.ajax({
		/* ── APUNTA AL CONTROLADOR DE VENTASOPERACIONES ── */
		url: 'ventasoperaciones/clases/controlador_filtro.php',
		method: 'POST',
		dataType: 'json',
		data: { action: 'bitacora_pago', idSubetufactura: idSubetufactura },

		success: function (data) {
			if (!data || data.length === 0) {
				$('#bitacoraSubLabel').html('Solicitud <b>#' + idSubetufactura + '</b>');
				$('#bitacoraPagoBody').html(
					'<div class="alert alert-light border m-3">No hay registros de bitácora para esta solicitud.</div>'
				);
				return;
			}

			/* Cabecera */
			var primerRegistro = data[0] || {};
			var numeroSolicitud = primerRegistro.NUMERO_CONSECUTIVO_PROVEE || primerRegistro.numero_consecutivo_provee || idSubetufactura;
			var tipoPago = '';
			for (var idx = 0; idx < data.length; idx++) {
				var tipoTmp = data[idx].VIATICOSOPRO || data[idx].viaticosopro || '';
				if (tipoTmp !== '') { tipoPago = tipoTmp; break; }
			}
			$('#bitacoraSubLabel').html('Solicitud <b>#' + numeroSolicitud + '</b>');

			/* Strip informativo */
			var strip = '';
			if (tipoPago) strip += '<span><b>Tipo:</b> ' + tipoPago + '</span>';
			if (strip !== '') $('#bitacoraStrip').html(strip).show();

			/* Timeline */
			var html = '<div class="bitacora-timeline-wrap"><div>';
			for (var i = 0; i < data.length; i++) {
				var d        = data[i];
				var cfg      = _bitacoraBadgeCfg(d.tipo_movimiento);
				var usuario  = d.nombre_quien_actualizo || d.nombre_quien_ingreso || '-';
				var isLast   = (i === data.length - 1);
				var initials = _bitacoraInitials(usuario);

				html +=
					'<div style="display:flex;gap:12px;">' +

						'<div style="display:flex;flex-direction:column;align-items:center;width:36px;">' +
							'<div class="bitacora-dot" style="background:' + cfg.bg + ';border-color:' + cfg.border + ';color:' + cfg.border + '">' +
								_bitacoraIcon(cfg.iconPath) +
							'</div>' +
							(!isLast ? '<div class="bitacora-line"></div>' : '') +
						'</div>' +

						'<div style="flex:1;padding-bottom:' + (isLast ? '0.25rem' : '1.1rem') + ';">' +
							'<div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;flex-wrap:wrap;">' +
								'<span class="badge-bitacora ' + cfg.cls + '">' + (d.tipo_movimiento || '-') + '</span>' +
								'<small style="color:#1b4f9c;font-weight:700;font-size:13px;">' + (d.fecha_hora || '-') + '</small>' +
							'</div>' +
							'<div style="font-size:13px;font-weight:500;margin-bottom:4px;color:#212529;">' + (d.detalle || '-') + '</div>' +
							'<div style="display:flex;align-items:center;gap:6px;margin-top:4px;">' +
								'<div class="bitacora-avatar" style="background:' + cfg.bg + ';color:' + cfg.border + ';">' + initials + '</div>' +
								'<small style="color:#6c757d;">' + usuario + '</small>' +
							'</div>' +
						'</div>' +

					'</div>';
			}
			html += '</div></div>';
			$('#bitacoraPagoBody').html(html);
		},

		error: function () {
			$('#bitacoraSubLabel').html('Solicitud <b>#' + idSubetufactura + '</b>');
			$('#bitacoraPagoBody').html(
				'<div class="alert alert-danger m-3">Error al consultar la bitácora. Intenta nuevamente.</div>'
			);
		}
	});
});

</script>
