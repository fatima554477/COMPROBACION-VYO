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
		"NOMBRE_EVENTO","NUMERO_CONSECUTIVO_PROVEE_1","RAZON_SOCIAL_1",
		"RFC_PROVEEDOR_1","NUMERO_EVENTO_1","NOMBRE_EVENTO_1",
		"MOTIVO_GASTO_1","CONCEPTO_PROVEE_1","MONTO_TOTAL_COTIZACION_ADEUDO_1",
		"MONTO_FACTURA_1","MONTO_PROPINA_1","MONTO_DEPOSITAR_1",
		"TIPO_DE_MONEDA_1","PFORMADE_PAGO_1","FECHA_A_DEPOSITAR_1",
		"STATUS_DE_PAGO_1","BANCO_ORIGEN_1","ACTIVO_FIJO_1","GASTO_FIJO_1",
		"PAGAR_CADA_1","FECHA_PPAGO_1","FECHA_TPROGRAPAGO_1","NUMERO_EVENTOFIJO_1",
		"CLASI_GENERAL_1","SUB_GENERAL_1","MONTO_DE_COMISION_1","POLIZA_NUMERO_1",
		"NOMBRE_DEL_EJECUTIVO_1","NOMBRE_DEL_AYUDO_1","OBSERVACIONES_1_1",
		"FECHA_DE_LLENADO_1","ADJUNTAR_COTIZACION_1","NOMBRE_COMERCIAL_1",
		"IVA_1","EJECUTIVOTARJETA_1","TIPO_CAMBIOP","TOTAL_ENPESOS",
		"IMPUESTO_HOSPEDAJE","TImpuestosRetenidosIVA_5","TImpuestosRetenidosISR_5",
		"descuentos_5","UUID","metodoDePago","total","serie","folio","regimenE",
		"UsoCFDI","TImpuestosTrasladados","TImpuestosRetenidos","Version",
		"tipoDeComprobante","condicionesDePago","fechaTimbrado","nombreR","rfcR",
		"Moneda","TipoCambio","ValorUnitarioConcepto","DescripcionConcepto",
		"ClaveUnidad","ClaveProdServ","Cantidad","ImporteConcepto","UnidadConcepto",
		"TUA","TuaTotalCargos","Descuento","propina","DEPARTAMENTO2WE"
	];
	filtros.forEach(function(id){
		var el = document.getElementById(id);
		if(el){ el.value = ''; }
	});
	load(1);
}

$(function() {
	const triggerSearch = () => load(1);
	$('#target2').on('keydown', 'thead input, thead select', function(event) {
		if (event.key === 'Enter' || event.which === 13) {
			event.preventDefault();
			triggerSearch();
		}
	});
	load(1);
});

function load(page){
	var query = $("#NOMBRE_EVENTO").val();
	var DEPARTAMENTO2 = $("#DEPARTAMENTO2WE").val();
	var NUMERO_CONSECUTIVO_PROVEE = $("#NUMERO_CONSECUTIVO_PROVEE_1").val();
	var RAZON_SOCIAL = $("#RAZON_SOCIAL_1").val();
	var RFC_PROVEEDOR = ($("#RFC_PROVEEDOR_1").val() || "").trim();
	var NUMERO_EVENTO = $("#NUMERO_EVENTO_1").val();
	var NOMBRE_EVENTO = $("#NOMBRE_EVENTO_1").val();
	var MOTIVO_GASTO = $("#MOTIVO_GASTO_1").val();
	var CONCEPTO_PROVEE = $("#CONCEPTO_PROVEE_1").val();
	var MONTO_TOTAL_COTIZACION_ADEUDO = $("#MONTO_TOTAL_COTIZACION_ADEUDO_1").val();
	var MONTO_FACTURA = $("#MONTO_FACTURA_1").val();
	var MONTO_PROPINA = $("#MONTO_PROPINA_1").val();
	var MONTO_DEPOSITAR = $("#MONTO_DEPOSITAR_1").val();
	var TIPO_DE_MONEDA = $("#TIPO_DE_MONEDA_1").val();
	var PFORMADE_PAGO = $("#PFORMADE_PAGO_1").val();
	var FECHA_A_DEPOSITAR = $("#FECHA_A_DEPOSITAR_1").val();
	var STATUS_DE_PAGO = $("#STATUS_DE_PAGO_1").val();
	var BANCO_ORIGEN = ($("#BANCO_ORIGEN_1").val() || "").trim();
	var ACTIVO_FIJO = $("#ACTIVO_FIJO_1").val();
	var GASTO_FIJO = $("#GASTO_FIJO_1").val();
	var PAGAR_CADA = $("#PAGAR_CADA_1").val();
	var FECHA_PPAGO = $("#FECHA_PPAGO_1").val();
	var FECHA_TPROGRAPAGO = $("#FECHA_TPROGRAPAGO_1").val();
	var NUMERO_EVENTOFIJO = $("#NUMERO_EVENTOFIJO_1").val();
	var CLASI_GENERAL = $("#CLASI_GENERAL_1").val();
	var SUB_GENERAL = $("#SUB_GENERAL_1").val();
	var MONTO_DE_COMISION = $("#MONTO_DE_COMISION_1").val();
	var POLIZA_NUMERO = $("#POLIZA_NUMERO_1").val();
	var NOMBRE_DEL_EJECUTIVO = $("#NOMBRE_DEL_EJECUTIVO_1").val();
	var NOMBRE_DEL_AYUDO = $("#NOMBRE_DEL_AYUDO_1").val();
	var OBSERVACIONES_1 = $("#OBSERVACIONES_1_1").val();
	var FECHA_DE_LLENADO = $("#FECHA_DE_LLENADO_1").val();
	var ADJUNTAR_COTIZACION_1_1 = $("#ADJUNTAR_COTIZACION_1").val();
	var NOMBRE_COMERCIAL = $("#NOMBRE_COMERCIAL_1").val();
	var TIPO_CAMBIOP = $("#TIPO_CAMBIOP").val();
	var TOTAL_ENPESOS = $("#TOTAL_ENPESOS").val();
	var IMPUESTO_HOSPEDAJE = $("#IMPUESTO_HOSPEDAJE").val();
	var EJECUTIVOTARJETA = $("#EJECUTIVOTARJETA_1").val();
	var IVA = ($("#IVA_1").val() || "").replace(/,/g, "").trim();
	var TImpuestosRetenidosIVA = $("#TImpuestosRetenidosIVA_5").val();
	var TImpuestosRetenidosISR = $("#TImpuestosRetenidosISR_5").val();
	var descuentos = $("#descuentos_5").val();
	var UUID = $("#UUID").val();
	var metodoDePago = $("#metodoDePago").val();
	var total = $("#total").val();
	var serie = $("#serie").val();
	var folio = $("#folio").val();
	var regimenE = $("#regimenE").val();
	var UsoCFDI = $("#UsoCFDI").val();
	var TImpuestosTrasladados = $("#TImpuestosTrasladados").val();
	var TImpuestosRetenidos = $("#TImpuestosRetenidos").val();
	var Version = $("#Version").val();
	var tipoDeComprobante = $("#tipoDeComprobante").val();
	var condicionesDePago = $("#condicionesDePago").val();
	var fechaTimbrado = $("#fechaTimbrado").val();
	var nombreR = $("#nombreR").val();
	var rfcR = $("#rfcR").val();
	var Moneda = $("#Moneda").val();
	var TipoCambio = $("#TipoCambio").val();
	var ValorUnitarioConcepto = $("#ValorUnitarioConcepto").val();
	var DescripcionConcepto = $("#DescripcionConcepto").val();
	var ClaveUnidad = $("#ClaveUnidad").val();
	var ClaveProdServ = $("#ClaveProdServ").val();
	var Cantidad = $("#Cantidad").val();
	var ImporteConcepto = $("#ImporteConcepto").val();
	var UnidadConcepto = $("#UnidadConcepto").val();
	var TUA = $("#TUA").val();
	var TuaTotalCargos = $("#TuaTotalCargos").val();
	var Descuento = $("#Descuento").val();
	var propina = $("#propina").val();
	var per_page = $("#per_pageVYO").val();

	var parametros = {
		"action":"ajax", "page":page, 'query':query, 'per_page':per_page,
		'NUMERO_CONSECUTIVO_PROVEE':NUMERO_CONSECUTIVO_PROVEE,
		'RAZON_SOCIAL':RAZON_SOCIAL, 'RFC_PROVEEDOR':RFC_PROVEEDOR,
		'NUMERO_EVENTO':NUMERO_EVENTO, 'NOMBRE_EVENTO':NOMBRE_EVENTO,
		'MOTIVO_GASTO':MOTIVO_GASTO, 'CONCEPTO_PROVEE':CONCEPTO_PROVEE,
		'MONTO_TOTAL_COTIZACION_ADEUDO':MONTO_TOTAL_COTIZACION_ADEUDO,
		'MONTO_FACTURA':MONTO_FACTURA, 'MONTO_PROPINA':MONTO_PROPINA,
		'MONTO_DEPOSITAR':MONTO_DEPOSITAR, 'TIPO_DE_MONEDA':TIPO_DE_MONEDA,
		'PFORMADE_PAGO':PFORMADE_PAGO, 'FECHA_A_DEPOSITAR':FECHA_A_DEPOSITAR,
		'STATUS_DE_PAGO':STATUS_DE_PAGO, 'BANCO_ORIGEN':BANCO_ORIGEN,
		'ACTIVO_FIJO':ACTIVO_FIJO, 'GASTO_FIJO':GASTO_FIJO,
		'PAGAR_CADA':PAGAR_CADA, 'FECHA_PPAGO':FECHA_PPAGO,
		'FECHA_TPROGRAPAGO':FECHA_TPROGRAPAGO, 'NUMERO_EVENTOFIJO':NUMERO_EVENTOFIJO,
		'CLASI_GENERAL':CLASI_GENERAL, 'SUB_GENERAL':SUB_GENERAL,
		'MONTO_DE_COMISION':MONTO_DE_COMISION, 'POLIZA_NUMERO':POLIZA_NUMERO,
		'NOMBRE_DEL_EJECUTIVO':NOMBRE_DEL_EJECUTIVO, 'NOMBRE_DEL_AYUDO':NOMBRE_DEL_AYUDO,
		'OBSERVACIONES_1':OBSERVACIONES_1, 'FECHA_DE_LLENADO':FECHA_DE_LLENADO,
		'ADJUNTAR_COTIZACION_1_1':ADJUNTAR_COTIZACION_1_1,
		'TIPO_CAMBIOP':TIPO_CAMBIOP, 'TOTAL_ENPESOS':TOTAL_ENPESOS,
		'IMPUESTO_HOSPEDAJE':IMPUESTO_HOSPEDAJE, 'EJECUTIVOTARJETA':EJECUTIVOTARJETA,
		'NOMBRE_COMERCIAL':NOMBRE_COMERCIAL, 'IVA':IVA,
		'TImpuestosRetenidosIVA_5':TImpuestosRetenidosIVA,
		'TImpuestosRetenidosISR_5':TImpuestosRetenidosISR,
		'descuentos_5':descuentos,
		'UUID':UUID, 'metodoDePago':metodoDePago, 'total':total,
		'serie':serie, 'folio':folio, 'regimenE':regimenE, 'UsoCFDI':UsoCFDI,
		'TImpuestosTrasladados':TImpuestosTrasladados, 'TImpuestosRetenidos':TImpuestosRetenidos,
		'Version':Version, 'tipoDeComprobante':tipoDeComprobante,
		'condicionesDePago':condicionesDePago, 'fechaTimbrado':fechaTimbrado,
		'nombreR':nombreR, 'rfcR':rfcR, 'Moneda':Moneda, 'TipoCambio':TipoCambio,
		'ValorUnitarioConcepto':ValorUnitarioConcepto, 'DescripcionConcepto':DescripcionConcepto,
		'ClaveUnidad':ClaveUnidad, 'ClaveProdServ':ClaveProdServ, 'Cantidad':Cantidad,
		'ImporteConcepto':ImporteConcepto, 'UnidadConcepto':UnidadConcepto,
		'TUA':TUA, 'TuaTotalCargos':TuaTotalCargos, 'Descuento':Descuento,
		'propina':propina, 'DEPARTAMENTO2':DEPARTAMENTO2
	};

	$.ajax({
		url: 'comprobacionesVYO/clases/controlador_filtro.php',
		type: 'POST',
		data: parametros,
		beforeSend: function(){
			$("#loader5").stop(true, true);
			$("#loader5").html(
				'<div class="msg-actualizando"><span class="loader"></span> ⏳ ACTUALIZANDO...</div>'
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

</script>