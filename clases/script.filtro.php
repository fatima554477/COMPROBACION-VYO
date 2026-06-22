<style>
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
.badge-bitacora { font-size: 11px; font-weight: 500; padding: 3px 10px; border-radius: 20px; display: inline-block; }
.badge-ingreso       { background:#E6F1FB; color:#0C447C; border:0.5px solid #B5D4F4; }
.badge-autorizacion  { background:#EAF3DE; color:#27500A; border:0.5px solid #C0DD97; }
.badge-actualizacion { background:#FAEEDA; color:#633806; border:0.5px solid #FAC775; }
.badge-pago          { background:#EAF3DE; color:#27500A; border:0.5px solid #C0DD97; }
.badge-cancelacion   { background:#FCEBEB; color:#501313; border:0.5px solid #F7C1C1; }
.badge-adjunto       { background:#F3E8FF; color:#5B21B6; border:0.5px solid #C4B5FD; }
.badge-rechazo       { background:#FEE2E2; color:#991B1B; border:0.5px solid #FCA5A5; }
.badge-default       { background:#f1f3f5; color:#444; border:0.5px solid #dee2e6; }
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

#modalBitacoraPago .modal-body { background: #f8fafc; }
</style>

<script type="text/javascript">

function pasarpagado2(pasarpagado_id){
	var checkBox = document.getElementById("pasarpagado1a"+pasarpagado_id);
	var pasarpagado_text = checkBox.checked ? "si" : "no";
	$.ajax({
		url:'comprobaciones/controladorPP.php',
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
		url:'comprobaciones/controladorPP.php',
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
		url:'comprobaciones/controladorPP.php',
		method:'POST',
		data:{AUDITORIA1_id:AUDITORIA1_id,AUDITORIA1_text:AUDITORIA1_text},
		beforeSend:function(){ $('#STATUS_AUDITORIA1').html('cargando'); },
		success:function(data){
			var result = data.split('^');
			$('#STATUS_AUDITORIA1').html("ACTUALIZADO").fadeIn().delay(1000).fadeOut();
			load(1);
			if(result[1]=='si'){ $('#color_AUDITORIA1'+AUDITORIA1_id).css('background-color', '#ceffcc'); }
			if(result[1]=='no'){ $('#color_AUDITORIA1'+AUDITORIA1_id).css('background-color', '#e9d8ee'); }
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
		url: 'comprobaciones/controladorPP.php',
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

function STATUS_AUDITORIA2(AUDITORIA2_id){
	var checkBox = document.getElementById("STATUS_AUDITORIA2"+AUDITORIA2_id);
	var AUDITORIA2_text = checkBox.checked ? "si" : "no";
	$.ajax({
		url:'comprobaciones/controladorPP.php',
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

function STATUS_AUDITORIA3(AUDITORIA3_id){
	var checkBox = document.getElementById("STATUS_AUDITORIA3"+AUDITORIA3_id);
	var AUDITORIA3_text = checkBox.checked ? "si" : "no";
	$.ajax({
		url:'comprobaciones/controladorPP.php',
		method:'POST',
		data:{AUDITORIA3_id:AUDITORIA3_id,AUDITORIA3_text:AUDITORIA3_text},
		beforeSend:function(){ $('#pasarpagado2').html('cargando'); },
		success:function(data){
			var result = data.split('^');
			$('#pasarpagado2').html("Cargando...").fadeIn().delay(500).fadeOut();
			load(1);
			if(result[1]=='si'){ $('#color_AUDITORIA3'+AUDITORIA3_id).css('background-color', '#ceffcc'); }
			if(result[1]=='no'){ $('#color_AUDITORIA3'+AUDITORIA3_id).css('background-color', '#e9d8ee'); }
		}
	});
}

function STATUS_FINANZAS(FINANZAS_id){
	var checkBox = document.getElementById("STATUS_FINANZAS"+FINANZAS_id);
	var FINANZAS_text = checkBox.checked ? "si" : "no";
	$.ajax({
		url:'comprobaciones/controladorPP.php',
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
	var $checkBox = $("#STATUS_RECHAZADO"+RECHAZADO_id);
	if($checkBox.length === 0){ return; }
	var checkBox = $checkBox.get(0);
	var estadoAnterior = $checkBox.data('estadoAnterior') || (checkBox.checked ? 'si' : 'no');
	var RECHAZADO_text = checkBox.checked ? "si" : "no";
	if(RECHAZADO_text === 'no'){ $checkBox.data('forzarAgregarMotivo', 'si'); }
	else if(RECHAZADO_text === 'si' && $checkBox.data('forzarAgregarMotivo') !== 'si'){ $checkBox.removeData('forzarAgregarMotivo'); }
	actualizarBotonesRechazo(RECHAZADO_id, RECHAZADO_text);
	$.ajax({
		url:'comprobaciones/controladorPP.php',
		method:'POST',
		data:{RECHAZADO_id:RECHAZADO_id,RECHAZADO_text:RECHAZADO_text},
		beforeSend:function(){ $('#pasarpagado2').html('cargando'); },
		success:function(data){
			var result = (data || '').trim().split('^');
			$('#pasarpagado2').html("Cargando...").fadeIn().delay(500).fadeOut();
			load(1);
			if(result[1] == 'si' || result[1] == 'no'){
				$checkBox.data('estadoAnterior', result[1]);
				if(result[1] == 'si' && $checkBox.data('forzarAgregarMotivo') !== 'si'){ $checkBox.removeData('forzarAgregarMotivo'); }
				actualizarBotonesRechazo(RECHAZADO_id, result[1]);
			} else {
				checkBox.checked = (estadoAnterior === 'si');
				actualizarBotonesRechazo(RECHAZADO_id, estadoAnterior);
			}
		},
		error:function(){
			checkBox.checked = (estadoAnterior === 'si');
			actualizarBotonesRechazo(RECHAZADO_id, estadoAnterior);
		}
	});
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
		url:'comprobaciones/controladorPP.php',
		method:'POST',
		data:{RECHAZO_MOTIVO_id:RECHAZADO_id,RECHAZO_MOTIVO_text:motivo},
		success:function(resp){
			if(resp.indexOf('ok') !== -1){
				$('#motivo_rechazo_'+RECHAZADO_id).val(motivo);
				$('#STATUS_RECHAZADO'+RECHAZADO_id).removeData('forzarAgregarMotivo');
				actualizarBotonesRechazo(RECHAZADO_id);
				$('#modal_rechazo_mensaje').text('Motivo guardado correctamente.').css('color', '#228b22');
				setTimeout(function(){ cerrarModalRechazoPago(); }, 400);
			} else {
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
		url:'comprobaciones/controladorPP.php',
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
		url:'comprobaciones/controladorPP.php',
		method:'POST',
		data:{VENTAS_id:VENTAS_id,VENTAS_text:VENTAS_text},
		beforeSend:function(){ $('#pasarpagado2').html('cargando'); },
		success:function(data){
			var result = data.split('^');
			$('#pasarpagado2').html("Cargando...").fadeIn().delay(500).fadeOut();
			load(1);
			if(result[1]=='si'){
				$('#color_VENTAS'+VENTAS_id).css('background-color', '#ceffcc');
				$('#STATUS_RECHAZADO'+VENTAS_id).prop('disabled', true).css('cursor', 'not-allowed').attr('title', 'No se puede rechazar: autorizado por ventas');
				actualizarBotonesRechazo(VENTAS_id);
			}
			if(result[1]=='no'){
				$('#color_VENTAS'+VENTAS_id).css('background-color', '#e9d8ee');
				$('#STATUS_RECHAZADO'+VENTAS_id).prop('disabled', false).css('cursor', 'pointer').attr('title', '');
				actualizarBotonesRechazo(VENTAS_id);
			}
		}
	});
}

function LIMPIAR_FILTRO(){
	var filtros = [
		"NUMERO_CONSECUTIVO_PROVEE_1","RAZON_SOCIAL_1","RFC_PROVEEDOR_1",
		"NUMERO_EVENTO_1","EJECUTIVOTARJETA_1","NOMBRE_EVENTO_1",
		"MOTIVO_GASTO_1","CONCEPTO_PROVEE_1","MONTO_TOTAL_COTIZACION_ADEUDO_1",
		"MONTO_FACTURA_1","MONTO_PROPINA_1","MONTO_DEPOSITAR_1",
		"TIPO_DE_MONEDA_1","PFORMADE_PAGO_1","STATUS_DE_PAGO_1",
		"BANCO_ORIGEN1AA","ACTIVO_FIJO_1","GASTO_FIJO_1","PAGAR_CADA_1",
		"FECHA_PPAGO_1","FECHA_TPROGRAPAGO_1","NUMERO_EVENTOFIJO_1",
		"CLASI_GENERAL_1","SUB_GENERAL_1","MONTO_DE_COMISION_1",
		"POLIZA_NUMERO_1","NOMBRE_DEL_EJECUTIVO_1","NOMBRE_DEL_AYUDO_1",
		"OBSERVACIONES_1_1_1","FECHA_DE_LLENADO_1","ADJUNTAR_COTIZACION_1_1",
		"TIPO_CAMBIOP_1","TOTAL_ENPESOS","IMPUESTO_HOSPEDAJE",
		"NOMBRE_COMERCIAL_1","IVA_1","TImpuestosRetenidosIVA_5",
		"TImpuestosRetenidosISR_5","descuentos_5",
		"UUID","metodoDePago","total","serie","folio","regimenE","UsoCFDI",
		"TImpuestosTrasladados","TImpuestosRetenidos","Version",
		"tipoDeComprobante","condicionesDePago","fechaTimbrado",
		"nombreR","rfcR","Moneda","TipoCambio","ValorUnitarioConcepto",
		"DescripcionConcepto","ClaveUnidad","ClaveProdServ","Cantidad",
		"ImporteConcepto","UnidadConcepto","TUA","TuaTotalCargos",
		"Descuento","propina",
		"FECHA_A_DEPOSITAR_DESDE","FECHA_A_DEPOSITAR_HASTA",
		"FECHA_INICIO_EVENTO","FECHA_FINAL_EVENTO",
		"DEPARTAMENTO2WE","NOMBRE_EVENTO"
	];
	filtros.forEach(function(id){
		var el = document.getElementById(id);
		if(el){ el.value = ''; }
	});
	
	$("#ADJUNTAR_FACTURA_XML_VACIO").prop("checked", false);
	load(1);
}

$(function() {
	const triggerSearch = () => load(1);
	$('#target3').on('keydown', 'thead input, thead select', function(event) {
		if (event.key === 'Enter' || event.which === 13) {
			event.preventDefault();
			triggerSearch();
		}
	});
	load(1);
});

function load(page){
	var getVal = id => {
		const value = $("#" + id).val();
		return typeof value === 'string' ? value.trim() : '';
	};
	var query = $("#NOMBRE_EVENTO").val();
	var DEPARTAMENTO2 = getVal("DEPARTAMENTO2WE");
	var NUMERO_CONSECUTIVO_PROVEE = getVal("NUMERO_CONSECUTIVO_PROVEE_1");
	var RAZON_SOCIAL = getVal("RAZON_SOCIAL_1");
	var RFC_PROVEEDOR = getVal("RFC_PROVEEDOR_1");
	var NUMERO_EVENTO = getVal("NUMERO_EVENTO_1");
	var EJECUTIVOTARJETA = getVal("EJECUTIVOTARJETA_1");
	var NOMBRE_EVENTO = getVal("NOMBRE_EVENTO_1");
	var FECHA_INICIO_EVENTO = $("#FECHA_INICIO_EVENTO").val();
	var FECHA_FINAL_EVENTO = $("#FECHA_FINAL_EVENTO").val();
	var PorfaltaDeFactura = $("#PorfaltaDeFactura").val();
	var MOTIVO_GASTO = getVal("MOTIVO_GASTO_1");
	var CONCEPTO_PROVEE = getVal("CONCEPTO_PROVEE_1");
	var MONTO_TOTAL_COTIZACION_ADEUDO = getVal("MONTO_TOTAL_COTIZACION_ADEUDO_1");
    var ADJUNTAR_FACTURA_XML_VACIO=$("#ADJUNTAR_FACTURA_XML_VACIO").is(":checked") ? 'si' : '';
	var MONTO_FACTURA = getVal("MONTO_FACTURA_1");
	var MONTO_PROPINA = getVal("MONTO_PROPINA_1");
	var MONTO_DEPOSITAR = getVal("MONTO_DEPOSITAR_1");
	var TIPO_DE_MONEDA = getVal("TIPO_DE_MONEDA_1");
	var PFORMADE_PAGO = getVal("PFORMADE_PAGO_1");
	var STATUS_DE_PAGO = getVal("STATUS_DE_PAGO_1");
	var BANCO_ORIGEN = getVal("BANCO_ORIGEN1AA");
	var ACTIVO_FIJO = getVal("ACTIVO_FIJO_1");
	var GASTO_FIJO = getVal("GASTO_FIJO_1");
	var PAGAR_CADA = getVal("PAGAR_CADA_1");
	var FECHA_PPAGO = getVal("FECHA_PPAGO_1");
	var FECHA_TPROGRAPAGO = getVal("FECHA_TPROGRAPAGO_1");
	var NUMERO_EVENTOFIJO = getVal("NUMERO_EVENTOFIJO_1");
	var CLASI_GENERAL = getVal("CLASI_GENERAL_1");
	var SUB_GENERAL = getVal("SUB_GENERAL_1");
	var MONTO_DE_COMISION = getVal("MONTO_DE_COMISION_1");
	var POLIZA_NUMERO = getVal("POLIZA_NUMERO_1");
	var NOMBRE_DEL_EJECUTIVO = getVal("NOMBRE_DEL_EJECUTIVO_1");
	var NOMBRE_DEL_AYUDO = getVal("NOMBRE_DEL_AYUDO_1");
	var OBSERVACIONES_1 = getVal("OBSERVACIONES_1_1_1");
	var FECHA_DE_LLENADO = getVal("FECHA_DE_LLENADO_1");
	var ADJUNTAR_COTIZACION_1_1 = getVal("ADJUNTAR_COTIZACION_1_1");
	var TIPO_CAMBIOP = getVal("TIPO_CAMBIOP_1");
	var TOTAL_ENPESOS = getVal("TOTAL_ENPESOS");
	var IMPUESTO_HOSPEDAJE = getVal("IMPUESTO_HOSPEDAJE");
	var NOMBRE_COMERCIAL = getVal("NOMBRE_COMERCIAL_1");
	var IVA = getVal("IVA_1");
	var TImpuestosRetenidosIVA = getVal("TImpuestosRetenidosIVA_5");
	var TImpuestosRetenidosISR = getVal("TImpuestosRetenidosISR_5");
	var descuentos = getVal("descuentos_5");
	var UUID = getVal("UUID");
	var metodoDePago = getVal("metodoDePago");
	var total = getVal("total");
	var serie = getVal("serie");
	var folio = getVal("folio");
	var regimenE = getVal("regimenE");
	var UsoCFDI = getVal("UsoCFDI");
	var TImpuestosTrasladados = getVal("TImpuestosTrasladados");
	var TImpuestosRetenidos = getVal("TImpuestosRetenidos");
	var Version = getVal("Version");
	var tipoDeComprobante = getVal("tipoDeComprobante");
	var condicionesDePago = getVal("condicionesDePago");
	var fechaTimbrado = getVal("fechaTimbrado");
	var nombreR = getVal("nombreR");
	var rfcR = getVal("rfcR");
	var Moneda = getVal("Moneda");
	var TipoCambio = getVal("TipoCambio");
	var ValorUnitarioConcepto = getVal("ValorUnitarioConcepto");
	var DescripcionConcepto = getVal("DescripcionConcepto");
	var ClaveUnidad = getVal("ClaveUnidad");
	var ClaveProdServ = getVal("ClaveProdServ");
	var Cantidad = getVal("Cantidad");
	var ImporteConcepto = getVal("ImporteConcepto");
	var UnidadConcepto = getVal("UnidadConcepto");
	var TUA = getVal("TUA");
	var TuaTotalCargos = getVal("TuaTotalCargos");
	var Descuento = getVal("Descuento");
	var propina = getVal("propina");
	var FECHA_A_DEPOSITAR_DESDE = getVal("FECHA_A_DEPOSITAR_DESDE");
	var FECHA_A_DEPOSITAR_HASTA = getVal("FECHA_A_DEPOSITAR_HASTA");
	var per_page = getVal("per_page");

	var parametros = {
		"action": "ajax", "page": page, 'query': query, 'per_page': per_page,
		'NUMERO_CONSECUTIVO_PROVEE': NUMERO_CONSECUTIVO_PROVEE,
		'RAZON_SOCIAL': RAZON_SOCIAL, 'RFC_PROVEEDOR': RFC_PROVEEDOR,
		'NUMERO_EVENTO': NUMERO_EVENTO, 'EJECUTIVOTARJETA': EJECUTIVOTARJETA,
		'NOMBRE_EVENTO': NOMBRE_EVENTO, 'MOTIVO_GASTO': MOTIVO_GASTO,
		'CONCEPTO_PROVEE': CONCEPTO_PROVEE,
		'MONTO_TOTAL_COTIZACION_ADEUDO': MONTO_TOTAL_COTIZACION_ADEUDO,
		'MONTO_FACTURA': MONTO_FACTURA, 'MONTO_PROPINA': MONTO_PROPINA,
		'MONTO_DEPOSITAR': MONTO_DEPOSITAR, 'TIPO_DE_MONEDA': TIPO_DE_MONEDA,
		'PFORMADE_PAGO': PFORMADE_PAGO,
		'PorfaltaDeFactura': PorfaltaDeFactura,
		'FECHA_A_DEPOSITAR_DESDE': FECHA_A_DEPOSITAR_DESDE,
		'FECHA_A_DEPOSITAR_HASTA': FECHA_A_DEPOSITAR_HASTA,
		'STATUS_DE_PAGO': STATUS_DE_PAGO, 'BANCO_ORIGEN': BANCO_ORIGEN,
		'ACTIVO_FIJO': ACTIVO_FIJO, 'GASTO_FIJO': GASTO_FIJO,
		'PAGAR_CADA': PAGAR_CADA, 'FECHA_PPAGO': FECHA_PPAGO,
		'FECHA_TPROGRAPAGO': FECHA_TPROGRAPAGO, 'NUMERO_EVENTOFIJO': NUMERO_EVENTOFIJO,
		'CLASI_GENERAL': CLASI_GENERAL, 'SUB_GENERAL': SUB_GENERAL,
		'MONTO_DE_COMISION': MONTO_DE_COMISION, 'POLIZA_NUMERO': POLIZA_NUMERO,
		'FECHA_FINAL_EVENTO': FECHA_FINAL_EVENTO, 'FECHA_INICIO_EVENTO': FECHA_INICIO_EVENTO,
		'NOMBRE_DEL_EJECUTIVO': NOMBRE_DEL_EJECUTIVO, 'NOMBRE_DEL_AYUDO': NOMBRE_DEL_AYUDO,
		'OBSERVACIONES_1': OBSERVACIONES_1, 'FECHA_DE_LLENADO': FECHA_DE_LLENADO,
		'ADJUNTAR_COTIZACION_1_1': ADJUNTAR_COTIZACION_1_1, 'TIPO_CAMBIOP': TIPO_CAMBIOP,
		'IVA': IVA, 'TOTAL_ENPESOS': TOTAL_ENPESOS, 'IMPUESTO_HOSPEDAJE': IMPUESTO_HOSPEDAJE,
		'TImpuestosRetenidosIVA_5': TImpuestosRetenidosIVA,
		'ADJUNTAR_FACTURA_XML_VACIO':ADJUNTAR_FACTURA_XML_VACIO,
		'TImpuestosRetenidosISR_5': TImpuestosRetenidosISR,
		'descuentos_5': descuentos, 'NOMBRE_COMERCIAL': NOMBRE_COMERCIAL,
		'UUID': UUID, 'metodoDePago': metodoDePago, 'total': total,
		'serie': serie, 'folio': folio, 'regimenE': regimenE, 'UsoCFDI': UsoCFDI,
		'TImpuestosTrasladados': TImpuestosTrasladados, 'TImpuestosRetenidos': TImpuestosRetenidos,
		'Version': Version, 'tipoDeComprobante': tipoDeComprobante,
		'condicionesDePago': condicionesDePago, 'fechaTimbrado': fechaTimbrado,
		'nombreR': nombreR, 'rfcR': rfcR, 'Moneda': Moneda, 'TipoCambio': TipoCambio,
		'ValorUnitarioConcepto': ValorUnitarioConcepto, 'DescripcionConcepto': DescripcionConcepto,
		'ClaveUnidad': ClaveUnidad, 'ClaveProdServ': ClaveProdServ, 'Cantidad': Cantidad,
		'ImporteConcepto': ImporteConcepto, 'UnidadConcepto': UnidadConcepto,
		'TUA': TUA, 'TuaTotalCargos': TuaTotalCargos, 'Descuento': Descuento,
		'propina': propina, 'DEPARTAMENTO2': DEPARTAMENTO2
	};

	$.ajax({
		url: 'comprobacionesVYO/clases/controlador_filtro.php',
		type: 'POST',
		data: parametros,
		beforeSend: function(){
			$("#loader5").stop(true, true);
			$("#loader5").html(
				'<div class="msg-actualizando"><span class="loader5"></span> ⏳ ACTUALIZANDO...</div>'
			).fadeIn();
		},
		success: function(data){
			$(".datos_ajax").html(data).fadeIn('slow');
			$("#loader5").html('<div class="msg-actualizando">✅ ACTUALIZADO</div>');
			$('.checkbox').each(function() {
				const id = $(this).data('id');
				if (localStorage.getItem('checkbox_' + id) === 'checked') {
					this.checked = true;
					this.closest('tr').style.filter = 'brightness(65%) sepia(100%) saturate(200%) hue-rotate(0deg)';
				}
			});
			if (typeof lastCheckboxID !== 'undefined' && lastCheckboxID !== null) {
				setTimeout(function () {
					let el = document.getElementById("STATUS_CHECKBOX" + lastCheckboxID);
					if (el) {
						el.scrollIntoView({ behavior: "smooth", block: "center" });
						lastCheckboxID = null;
					}
				}, 500);
			}
		},
		error: function(xhr, status){
			if (status !== 'abort') {
				$("#loader5").html('<div class="msg-actualizando">❌ Error al actualizar</div>');
			}
		},
		complete: function(){
			$("#loader5").delay(700).fadeOut("slow", function(){ $(this).html(""); });
		}
	});
}
/* terminaB1*/

/* terminaB1*/

function _bitacoraBadgeCfg(tipo) {
	var t = (tipo || '').toLowerCase();
	if (t.indexOf('ingres') !== -1) return { cls:'badge-ingreso', bg:'#E6F1FB', border:'#185FA5', iconPath:'M12 5v14M5 12l7-7 7 7' };
	if (t.indexOf('autori') !== -1) return { cls:'badge-autorizacion', bg:'#EAF3DE', border:'#3B6D11', iconPath:'M20 6L9 17l-5-5' };
	if (t.indexOf('actualiz') !== -1) return { cls:'badge-actualizacion', bg:'#FAEEDA', border:'#BA7517', iconPath:'M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4z' };
	if (t.indexOf('adjunto')  !== -1) return { cls:'badge-adjunto',       bg:'#F3E8FF', border:'#5B21B6', iconPath:'M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48' };
	if (t.indexOf('rechazo') !== -1) return { cls:'badge-rechazo', bg:'#FEE2E2', border:'#991B1B', iconPath:'M18 6L6 18M6 6l12 12' };
	return { cls:'badge-default', bg:'#f1f3f5', border:'#adb5bd', iconPath:'M12 12m-4 0a4 4 0 108 0 4 4 0 10-8 0' };
}
function _bitacoraInitials(name){ if(!name||name==='-') return '?'; return (name.trim().split(/\s+/).slice(0,2).map(function(n){ return n[0]; }).join('')).toUpperCase(); }
function _bitacoraIcon(path){ return '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="' + path + '"/></svg>'; }
$(document).on('click', '.view_dataPAGOPROVEEbitacora', function () {
	var idSubetufactura = $(this).attr('id');
	$('#bitacoraSubLabel').html('Solicitud <b>#...</b>'); $('#bitacoraStrip').hide().html('');
	$('#bitacoraPagoBody').html('<div class="text-center py-4 text-muted"><span class="spinner-border spinner-border-sm me-2"></span>Cargando bitácora...</div>');
	$('#modalBitacoraPago').modal('show');
	$.ajax({
		url: 'comprobaciones/clases/controlador_filtro.php', method: 'POST', dataType: 'json',
		data: { action: 'bitacora_pago', idSubetufactura: idSubetufactura },
		success: function (data) {
			if (!data || data.length === 0) { $('#bitacoraSubLabel').html('ID <b>#' + idSubetufactura + '</b>'); $('#bitacoraPagoBody').html('<div class="alert alert-light border m-3">No hay registros de bitácora para esta solicitud.</div>'); return; }
			var numeroSolicitud = (data[0].NUMERO_CONSECUTIVO_PROVEE || idSubetufactura); $('#bitacoraSubLabel').html('ID <b>#' + numeroSolicitud + '</b>');
			var strip = ''; if (data[0].proveedor) { strip += '<span><b>Nombre:</b> ' + data[0].proveedor + '</span>'; } if (data[0].monto) { strip += '<span><b>Monto:</b> $' + data[0].monto + '</span>'; } if (data[0].evento) { strip += '<span><b>Evento:</b> ' + data[0].evento + '</span>'; } if (strip !== '') { $('#bitacoraStrip').html(strip).show(); }
			var html = '<div class="bitacora-timeline-wrap"><div>';
			for (var i = 0; i < data.length; i++) {
				var d=data[i], cfg=_bitacoraBadgeCfg(d.tipo_movimiento), usuario=d.nombre_quien_actualizo||d.nombre_quien_ingreso||'-', isLast=(i===data.length-1), initials=_bitacoraInitials(usuario);
				html += '<div style="display:flex;gap:12px;"><div style="display:flex;flex-direction:column;align-items:center;width:36px;"><div class="bitacora-dot" style="background:' + cfg.bg + ';border-color:' + cfg.border + ';color:' + cfg.border + '">' + _bitacoraIcon(cfg.iconPath) + '</div>' + (!isLast ? '<div class="bitacora-line"></div>' : '') + '</div><div style="flex:1;padding-bottom:' + (isLast ? '0.25rem' : '1.1rem') + ';"><div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;flex-wrap:wrap;"><span class="badge-bitacora ' + cfg.cls + '">' + (d.tipo_movimiento || '-') + '</span><small style="color:#1b4f9c;font-weight:700;font-size:13px;">' + (d.fecha_hora || '-') + '</small></div><div style="font-size:13px;font-weight:500;margin-bottom:4px;color:#212529;">' + (d.detalle || '-') + '</div><div style="display:flex;align-items:center;gap:6px;margin-top:4px;"><div class="bitacora-avatar" style="background:' + cfg.bg + ';color:' + cfg.border + ';">' + initials + '</div><small style="color:#6c757d;">' + usuario + '</small></div></div></div>';
			}
			$('#bitacoraPagoBody').html(html + '</div></div>');
		},
		error: function () { $('#bitacoraSubLabel').html('id. <b>#' + idSubetufactura + '</b>'); $('#bitacoraPagoBody').html('<div class="alert alert-danger m-3">Error al consultar la bitácora. Intenta nuevamente.</div>'); }
	});
});

</script>
<div class="modal fade" id="modalBitacoraPago" tabindex="-1" aria-labelledby="modalBitacoraPagoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg overflow-hidden">
      <div class="modal-header border-0 px-4 py-3 text-white" style="background:#185FA5;">
        <div class="d-flex align-items-center gap-2">
          <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:34px;height:34px;background:rgba(255,255,255,.2);">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          </div>
          <div><h6 class="mb-0 fw-bold" id="modalBitacoraPagoLabel">Bitácora de movimientos</h6><small class="opacity-75" id="bitacoraSubLabel">Cargando...</small></div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div id="bitacoraStrip" class="bitacora-strip" style="display:none;"></div>
      <div class="modal-body p-0" id="bitacoraPagoBody" style="background:#f8fafc;"><div class="text-center py-4 text-muted"><span class="spinner-border spinner-border-sm me-2"></span>Cargando bitácora...</div></div>
      <div class="modal-footer border-0 bg-white py-2"><button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Cerrar</button></div>
    </div>
  </div>
</div>