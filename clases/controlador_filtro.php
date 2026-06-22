<?php

/**
 	--------------------------
	
	Programer: Fatima Arellano
	Propietario: EPC
	fecha sandor: 
    fecha fatis : 02/06/2024

	----------------------------
*/

if(!isset($_SESSION)) { 
    session_start(); 
}

define("__ROOT6__", dirname(__FILE__));
$action = (isset($_POST["action"]) && $_POST["action"] != NULL) ? $_POST["action"] : "";
if($action == "bitacora_pago"){
    require(__ROOT6__."/class.filtro.php");
    $database = new orders();
    $idComprobacion = isset($_POST['idSubetufactura']) ? intval($_POST['idSubetufactura']) : 0;

    header('Content-Type: application/json; charset=utf-8');

    if($idComprobacion <= 0){
        echo json_encode(array());
        exit;
    }

    // TEMPORAL: captura errores
    $resultado = $database->Listado_bitacora_pagoproveedor_array($idComprobacion);
    echo json_encode($resultado);
    exit;
}
if($action == "ajax"){

	require(__ROOT6__."/class.filtro.php");
	$database = new orders();
	$usuarioActual = isset($_SESSION['idem']) ? $_SESSION['idem'] : '';
	$eventosAutorizadosVentas = array_flip($database->puedeAutorizarVentas($usuarioActual));

	$query = isset($_POST["query"]) ? $_POST["query"] : "";

	$DEPARTAMENTO = !empty($_POST["DEPARTAMENTO2"]) ? $_POST["DEPARTAMENTO2"] : "DEFAULT";
	$nombreTabla = "SELECT * FROM `08comprobacionesVYOfiltroDes`, 08altaeventosfiltroPLA WHERE 08comprobacionesVYOfiltroDes.id = 08altaeventosfiltroPLA.idRelacion";
	$altaeventos = "comprobacionesVYO";

	$NUMERO_CONSECUTIVO_PROVEE = isset($_POST["NUMERO_CONSECUTIVO_PROVEE"]) ? $_POST["NUMERO_CONSECUTIVO_PROVEE"] : ""; 
	$RAZON_SOCIAL = isset($_POST["RAZON_SOCIAL"]) ? $_POST["RAZON_SOCIAL"] : ""; 
	$NUMERO_EVENTO = isset($_POST["NUMERO_EVENTO"]) ? $_POST["NUMERO_EVENTO"] : ""; 
	$RFC_PROVEEDOR = isset($_POST["RFC_PROVEEDOR"]) ? $_POST["RFC_PROVEEDOR"] : ""; 
	$NOMBRE_EVENTO = isset($_POST["NOMBRE_EVENTO"]) ? $_POST["NOMBRE_EVENTO"] : ""; 
	$MOTIVO_GASTO = isset($_POST["MOTIVO_GASTO"]) ? $_POST["MOTIVO_GASTO"] : ""; 
	$CONCEPTO_PROVEE = isset($_POST["CONCEPTO_PROVEE"]) ? $_POST["CONCEPTO_PROVEE"] : ""; 
	$MONTO_TOTAL_COTIZACION_ADEUDO = isset($_POST["MONTO_TOTAL_COTIZACION_ADEUDO"]) ? $_POST["MONTO_TOTAL_COTIZACION_ADEUDO"] : ""; 
	$MONTO_FACTURA = isset($_POST["MONTO_FACTURA"]) ? $_POST["MONTO_FACTURA"] : ""; 
	$ADJUNTAR_FACTURA_XML_VACIO = isset($_POST["ADJUNTAR_FACTURA_XML_VACIO"]) ? trim($_POST["ADJUNTAR_FACTURA_XML_VACIO"]) : "";
	$EJECUTIVOTARJETA = isset($_POST["EJECUTIVOTARJETA_1"]) ? $_POST["EJECUTIVOTARJETA_1"] : ""; 
	$MONTO_PROPINA = isset($_POST["MONTO_PROPINA"]) ? $_POST["MONTO_PROPINA"] : ""; 
	$MONTO_DEPOSITAR = isset($_POST["MONTO_DEPOSITAR"]) ? $_POST["MONTO_DEPOSITAR"] : ""; 
	$TIPO_DE_MONEDA = isset($_POST["TIPO_DE_MONEDA"]) ? $_POST["TIPO_DE_MONEDA"] : ""; 
	$PFORMADE_PAGO = isset($_POST["PFORMADE_PAGO"]) ? $_POST["PFORMADE_PAGO"] : ""; 
	$FECHA_A_DEPOSITAR = isset($_POST["FECHA_A_DEPOSITAR"]) ? $_POST["FECHA_A_DEPOSITAR"] : ""; 
	$STATUS_DE_PAGO = isset($_POST["STATUS_DE_PAGO"]) ? $_POST["STATUS_DE_PAGO"] : ""; 
	$BANCO_ORIGEN = isset($_POST["BANCO_ORIGEN"]) ? $_POST["BANCO_ORIGEN"] : ""; 
	$ACTIVO_FIJO = isset($_POST["ACTIVO_FIJO"]) ? $_POST["ACTIVO_FIJO"] : ""; 
	$GASTO_FIJO = isset($_POST["GASTO_FIJO"]) ? $_POST["GASTO_FIJO"] : ""; 
	$PAGAR_CADA = isset($_POST["PAGAR_CADA"]) ? $_POST["PAGAR_CADA"] : ""; 
	$FECHA_PPAGO = isset($_POST["FECHA_PPAGO"]) ? $_POST["FECHA_PPAGO"] : ""; 
	$FECHA_TPROGRAPAGO = isset($_POST["FECHA_TPROGRAPAGO"]) ? $_POST["FECHA_TPROGRAPAGO"] : ""; 
	$NUMERO_EVENTOFIJO = isset($_POST["NUMERO_EVENTOFIJO"]) ? $_POST["NUMERO_EVENTOFIJO"] : ""; 
	$CLASI_GENERAL = isset($_POST["CLASI_GENERAL"]) ? $_POST["CLASI_GENERAL"] : ""; 
	$SUB_GENERAL = isset($_POST["SUB_GENERAL"]) ? $_POST["SUB_GENERAL"] : ""; 
	$MONTO_DE_COMISION = isset($_POST["MONTO_DE_COMISION"]) ? $_POST["MONTO_DE_COMISION"] : ""; 
	$POLIZA_NUMERO = isset($_POST["POLIZA_NUMERO"]) ? $_POST["POLIZA_NUMERO"] : ""; 
	$NOMBRE_DEL_EJECUTIVO = isset($_POST["NOMBRE_DEL_EJECUTIVO"]) ? $_POST["NOMBRE_DEL_EJECUTIVO"] : ""; 
	$NOMBRE_DEL_AYUDO = isset($_POST["NOMBRE_DEL_AYUDO"]) ? $_POST["NOMBRE_DEL_AYUDO"] : ""; 
	$OBSERVACIONES_1 = isset($_POST["OBSERVACIONES_1"]) ? $_POST["OBSERVACIONES_1"] : ""; 
	$FECHA_DE_LLENADO = isset($_POST["FECHA_DE_LLENADO"]) ? $_POST["FECHA_DE_LLENADO"] : ""; 
	$TIPO_CAMBIOP = isset($_POST["TIPO_CAMBIOP"]) ? $_POST["TIPO_CAMBIOP"] : ""; 
	$TOTAL_ENPESOS = isset($_POST["TOTAL_ENPESOS"]) ? $_POST["TOTAL_ENPESOS"] : ""; 
	$IMPUESTO_HOSPEDAJE = isset($_POST["IMPUESTO_HOSPEDAJE"]) ? $_POST["IMPUESTO_HOSPEDAJE"] : ""; 
	$IVA = isset($_POST["IVA"]) ? $_POST["IVA"] : "";
	$EJECUTIVOTARJETA = isset($_POST["EJECUTIVOTARJETA"]) ? $_POST["EJECUTIVOTARJETA"] : "";
	$NOMBRE_COMERCIAL = isset($_POST["NOMBRE_COMERCIAL"]) ? $_POST["NOMBRE_COMERCIAL"] : "";
	$TImpuestosRetenidosIVA = isset($_POST["TImpuestosRetenidosIVA_5"]) ? $_POST["TImpuestosRetenidosIVA_5"] : ""; 
	$TImpuestosRetenidosISR = isset($_POST["TImpuestosRetenidosISR_5"]) ? $_POST["TImpuestosRetenidosISR_5"] : ""; 
	$descuentos = isset($_POST["descuentos_5"]) ? $_POST["descuentos_5"] : ""; 
	$UUID = isset($_POST["UUID"]) ? trim($_POST["UUID"]) : ""; 
	$metodoDePago = isset($_POST["metodoDePago"]) ? trim($_POST["metodoDePago"]) : ""; 
	$total = isset($_POST["total"]) ? trim($_POST["total"]) : ""; 
	$serie = isset($_POST["serie"]) ? trim($_POST["serie"]) : ""; 
	$folio = isset($_POST["folio"]) ? trim($_POST["folio"]) : ""; 
	$regimenE = isset($_POST["regimenE"]) ? trim($_POST["regimenE"]) : ""; 
	$UsoCFDI = isset($_POST["UsoCFDI"]) ? trim($_POST["UsoCFDI"]) : ""; 
	$TImpuestosTrasladados = isset($_POST["TImpuestosTrasladados"]) ? trim($_POST["TImpuestosTrasladados"]) : ""; 
	$TImpuestosRetenidos = isset($_POST["TImpuestosRetenidos"]) ? trim($_POST["TImpuestosRetenidos"]) : ""; 
	$Version = isset($_POST["Version"]) ? trim($_POST["Version"]) : ""; 
	$tipoDeComprobante = isset($_POST["tipoDeComprobante"]) ? trim($_POST["tipoDeComprobante"]) : ""; 
	$condicionesDePago = isset($_POST["condicionesDePago"]) ? trim($_POST["condicionesDePago"]) : ""; 
	$fechaTimbrado = isset($_POST["fechaTimbrado"]) ? trim($_POST["fechaTimbrado"]) : ""; 
	$nombreR = isset($_POST["nombreR"]) ? trim($_POST["nombreR"]) : ""; 
	$rfcR = isset($_POST["rfcR"]) ? trim($_POST["rfcR"]) : ""; 
	$Moneda = isset($_POST["Moneda"]) ? trim($_POST["Moneda"]) : ""; 
	$TipoCambio = isset($_POST["TipoCambio"]) ? trim($_POST["TipoCambio"]) : ""; 
	$ValorUnitarioConcepto = isset($_POST["ValorUnitarioConcepto"]) ? trim($_POST["ValorUnitarioConcepto"]) : ""; 
	$DescripcionConcepto = isset($_POST["DescripcionConcepto"]) ? trim($_POST["DescripcionConcepto"]) : ""; 
	$ClaveUnidad = isset($_POST["ClaveUnidad"]) ? trim($_POST["ClaveUnidad"]) : ""; 
	$ClaveProdServ = isset($_POST["ClaveProdServ"]) ? trim($_POST["ClaveProdServ"]) : ""; 
	$Cantidad = isset($_POST["Cantidad"]) ? trim($_POST["Cantidad"]) : ""; 
	$ImporteConcepto = isset($_POST["ImporteConcepto"]) ? trim($_POST["ImporteConcepto"]) : ""; 
	$UnidadConcepto = isset($_POST["UnidadConcepto"]) ? trim($_POST["UnidadConcepto"]) : ""; 
	$TUA = isset($_POST["TUA"]) ? trim($_POST["TUA"]) : ""; 
	$TuaTotalCargos = isset($_POST["TuaTotalCargos"]) ? trim($_POST["TuaTotalCargos"]) : ""; 
	$Descuento = isset($_POST["Descuento"]) ? trim($_POST["Descuento"]) : "";
	$subTotal = isset($_POST["subTotal"]) ? trim($_POST["subTotal"]) : ""; 
	$propina = isset($_POST["propina"]) ? trim($_POST["propina"]) : ""; 
	$ADJUNTAR_COTIZACION_1_1 = isset($_POST["ADJUNTAR_COTIZACION_1_1"]) ? trim($_POST["ADJUNTAR_COTIZACION_1_1"]) : ""; 

	if(isset($_SESSION['num_evento']) && $_SESSION['num_evento']){
		$NUMERO_EVENTO = $_SESSION['num_evento'];
	}
	if(isset($_POST['NUMERO_EVENTO']) && $_POST['NUMERO_EVENTO']){
		$NUMERO_EVENTO = $_POST['NUMERO_EVENTO'];	
	}

	$per_page = intval($_POST["per_page"]);
	$campos = "*";
	$page = (isset($_POST["page"]) && !empty($_POST["page"])) ? $_POST["page"] : 1;
	$adjacents = 4;
	$offset = ($page - 1) * $per_page;

	$search = array(
		"NUMERO_CONSECUTIVO_PROVEE" => $NUMERO_CONSECUTIVO_PROVEE,
		"RAZON_SOCIAL" => $RAZON_SOCIAL,
		"NUMERO_EVENTO" => $NUMERO_EVENTO,
		"RFC_PROVEEDOR" => $RFC_PROVEEDOR,
		"NOMBRE_EVENTO" => $NOMBRE_EVENTO,
		"MOTIVO_GASTO" => $MOTIVO_GASTO,
		"CONCEPTO_PROVEE" => $CONCEPTO_PROVEE,
		"MONTO_TOTAL_COTIZACION_ADEUDO" => $MONTO_TOTAL_COTIZACION_ADEUDO,
		"MONTO_FACTURA" => $MONTO_FACTURA,
		"MONTO_PROPINA" => $MONTO_PROPINA,
		"MONTO_DEPOSITAR" => $MONTO_DEPOSITAR,
		"ADJUNTAR_FACTURA_XML_VACIO" => $ADJUNTAR_FACTURA_XML_VACIO,
		"TIPO_DE_MONEDA" => $TIPO_DE_MONEDA,
		"PFORMADE_PAGO" => $PFORMADE_PAGO,
		"FECHA_A_DEPOSITAR" => $FECHA_A_DEPOSITAR,
		"STATUS_DE_PAGO" => $STATUS_DE_PAGO,
		"EJECUTIVOTARJETA" => $EJECUTIVOTARJETA,
		"BANCO_ORIGEN" => $BANCO_ORIGEN,
		"ACTIVO_FIJO" => $ACTIVO_FIJO,
		"GASTO_FIJO" => $GASTO_FIJO,
		"PAGAR_CADA" => $PAGAR_CADA,
		"FECHA_PPAGO" => $FECHA_PPAGO,
		"FECHA_TPROGRAPAGO" => $FECHA_TPROGRAPAGO,
		"NUMERO_EVENTOFIJO" => $NUMERO_EVENTOFIJO,
		"CLASI_GENERAL" => $CLASI_GENERAL,
		"SUB_GENERAL" => $SUB_GENERAL,
		"MONTO_DE_COMISION" => $MONTO_DE_COMISION,
		"POLIZA_NUMERO" => $POLIZA_NUMERO,
		"NOMBRE_DEL_EJECUTIVO" => $NOMBRE_DEL_EJECUTIVO,
		"NOMBRE_DEL_AYUDO" => $NOMBRE_DEL_AYUDO,
		"OBSERVACIONES_1" => $OBSERVACIONES_1,
		"FECHA_DE_LLENADO" => $FECHA_DE_LLENADO,
		"ADJUNTAR_COTIZACION" => $ADJUNTAR_COTIZACION_1_1,
		"TIPO_CAMBIOP" => $TIPO_CAMBIOP,
		"TOTAL_ENPESOS" => $TOTAL_ENPESOS,
		"IMPUESTO_HOSPEDAJE" => $IMPUESTO_HOSPEDAJE,
		"IVA" => $IVA,
		"TImpuestosRetenidosIVA" => $TImpuestosRetenidosIVA,
		"TImpuestosRetenidosISR" => $TImpuestosRetenidosISR,
		"descuentos" => $descuentos,
		"NOMBRE_COMERCIAL" => $NOMBRE_COMERCIAL,
		"UUID" => $UUID,
		"total" => $total,
		"metodoDePago" => $metodoDePago,
		"serie" => $serie,
		"folio" => $folio,
		"regimenE" => $regimenE,
		"UsoCFDI" => $UsoCFDI,
		"TImpuestosTrasladados" => $TImpuestosTrasladados,
		"TImpuestosRetenidos" => $TImpuestosRetenidos,
		"Version" => $Version,
		"tipoDeComprobante" => $tipoDeComprobante,
		"condicionesDePago" => $condicionesDePago,
		"fechaTimbrado" => $fechaTimbrado,
		"nombreR" => $nombreR,
		"rfcR" => $rfcR,
		"Moneda" => $Moneda,
		"TipoCambio" => $TipoCambio,
		"ValorUnitarioConcepto" => $ValorUnitarioConcepto,
		"DescripcionConcepto" => $DescripcionConcepto,
		"ClaveUnidad" => $ClaveUnidad,
		"ClaveProdServ" => $ClaveProdServ,
		"Cantidad" => $Cantidad,
		"ImporteConcepto" => $ImporteConcepto,
		"UnidadConcepto" => $UnidadConcepto,
		"TUA" => $TUA,
		"TuaTotalCargos" => $TuaTotalCargos,
		"Descuento" => $Descuento,
		"subTotal" => $subTotal,
		"propina" => $propina,
		"per_page" => $per_page,
		"query" => $query,
		"offset" => $offset
	);

	$datos = $database->getData($tables, $campos, $search);
	$countAll = $database->getCounter();
	$row = $countAll;

	if ($row > 0){ $numrows = $countAll; } else { $numrows = 0; }
	$total_pages = ceil($numrows / $per_page);


	$p_rechazo_ver          = $database->variablespermisos('', 'rechazo_pagoCOM2', 'ver') == 'si';
	$p_rechazo_guardar      = $database->variablespermisos('', 'rechazo_pagoCOM2', 'guardar') == 'si';
	$p_rechazo_mod          = $database->variablespermisos('', 'rechazo_pagoCOM2', 'modificar') == 'si';
	$p_direccion_ver        = $database->variablespermisos('', 'DIRECCIONCOM2', 'ver') == 'si';
	$p_direccion_mod        = $database->variablespermisos('', 'DIRECCIONCOM2', 'modificar') == 'si';
	$p_auditoria_ver        = $database->variablespermisos('', 'AUDITORIACOM2', 'ver') == 'si';
	$p_auditoria_mod        = $database->variablespermisos('', 'AUDITORIACOM2', 'modificar') == 'si';
	$p_contabilidad_ver     = $database->variablespermisos('', 'CONTABILIDADCOM2', 'ver') == 'si';
	$p_contabilidad_mod     = $database->variablespermisos('', 'CONTABILIDADCOM2', 'modificar') == 'si';
	$p_campos_comvyo_ver    = $database->variablespermisos('', 'CAMPOS_COMVYO', 'ver') == 'si';
	$p_sincuarenta_ver      = $database->variablespermisos('', 'sincuarentaCOMVYO', 'ver') == 'si';
	$p_sincuarenta_mod      = $database->variablespermisos('', 'sincuarentaCOMVYO', 'modificar') == 'si';
	$p_comvyo_mod           = $database->variablespermisos('', 'comprobacionVYO', 'modificar') == 'si';
	$p_comvyo_borrar        = $database->variablespermisos('', 'comprobacionVYO', 'borrar') == 'si';
	$p_subirfactura_ver     = $database->variablespermisos('', 'SUBIRFACTURACOMVYO', 'ver') == 'si';
	$p_totales_ver          = $database->variablespermisos('', 'TOTALES_COMVYO', 'ver') == 'si';
	?>

	<div class="clearfix">
		<?php 
			echo "<div class='hint-text'> ".$numrows." registros</div>";
			require __ROOT6__."/pagination.php";
			$pagination = new Pagination($page, $total_pages, $adjacents);
			echo $pagination->paginate();
		?>
    </div>
	<div class="table-responsive">
	<style>
    thead tr:first-child th {
        position: sticky;
        top: 0;
        background: #c9e8e8;
        z-index: 10;
    }
    thead tr:nth-child(2) td {
        position: sticky;
        top: 60px;
        background: #e2f2f2;
        z-index: 9;
    }
</style>
<div style="max-height: 600px; overflow-y: auto;">
	<table class="table table-striped table-bordered">	
		<thead>
            <tr>
<th style="background:#c9e8e8"></th>
<th style="background:#c9e8e8">ID # </th>
<th style="background:#c9e8e8">SOLICITANTE</th>
<th style="background:#c9e8e8;text-align:center">VENTAS Y<br> OPERACIONES</th>
<th style="background:#c9e8e8">DIRECCIÓN </th>
<th style="background:#c9e8e8">AUDITORÍA</th>
<th style="background:#c9e8e8">CONTABILIDAD</th>
<?php if ($p_rechazo_ver) { ?>
<th style="background:#c9e8e8">RECHAZADO</th>
<?php } ?>

<?php if($database->plantilla_filtro($nombreTabla,"FECHA_DE_LLENADO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">FECHA Y HORA <br> DE LLENADO</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"ADJUNTAR_FACTURA_XML",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">FACTURA XML</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"ADJUNTAR_FACTURA_PDF",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">FACTURA PDF</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"NOMBRE_COMERCIAL",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">NOMBRE COMERCIAL</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"RAZON_SOCIAL",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">RAZÓN SOCIAL</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"RFC_PROVEEDOR",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">RFC PROVEEDOR</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"NUMERO_EVENTO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">NÚMERO EVENTO</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"NOMBRE_EVENTO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">NOMBRE DEL EVENTO</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"MOTIVO_GASTO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">MOTIVO DEL GASTO</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"CONCEPTO_PROVEE",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">CONCEPTO DE LA FACTURA</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"MONTO_FACTURA",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">SUBTOTAL</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"IVA",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">IVA</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"TImpuestosRetenidosIVA",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">IMPUESTOS RETENIDOS IVA</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"TImpuestosRetenidosISR",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">IMPUESTOS RETENIDOS ISR</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"MONTO_PROPINA",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">MONTO DE LA PROPINA O SERVICIO<br> NO INCLUIDO EN LA FACTURA</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"IMPUESTO_HOSPEDAJE",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center"> IMPUESTO SOBRE HOSPEDAJE MÁS<br> EL IMPUESTO DE SANEAMIENTO</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"descuentos",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">DESCUENTO</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"MONTO_DEPOSITAR",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">TOTAL</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"MATCH",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">MATCH CON <br>ESTADO DE CUENTA</th><th style="background:#c9e8e8;text-align:center">STATUS DE<br>COMPROBACIÓN</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"TIPO_DE_MONEDA",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">TIPO DE MONEDA O DIVISA</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"TIPO_CAMBIOP",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">TIPO DE CAMBIO</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"TOTAL_ENPESOS",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">TOTAL DE LA CONVERSIÓN</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"PFORMADE_PAGO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#f48a81;text-align:center">FORMA DE PAGO</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"FECHA_A_DEPOSITAR",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#f48a81;text-align:center">FECHA DE CARGO EN TDC</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"STATUS_DE_PAGO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#f48a81;text-align:center">STATUS DE PAGO</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"ADJUNTAR_COTIZACION",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#f48a81;text-align:center">COTIZACIÓN O  REPORTE</th><?php } ?>

<?php if($p_campos_comvyo_ver){ ?>
<?php if($database->plantilla_filtro($nombreTabla,"ACTIVO_FIJO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">ACTIVO FIJO</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"GASTO_FIJO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">GASTO FIJO</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"PAGAR_CADA",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">PAGAR CADA</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"FECHA_PPAGO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">FECHA DE PROGRAMACIÓN DE PAGO</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"FECHA_TPROGRAPAGO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">FECHA DE TERMINACIÓN DE<br> LA PROGRAMACIÓN:</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"NUMERO_EVENTOFIJO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">NÚMERO DE EVENTO FIJO</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"CLASI_GENERAL",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">CLASIFICACIÓN GENERAL</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"SUB_GENERAL",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">SUB CLASIFICACIÓN GENERAL</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"COMPLEMENTOS_PAGO_PDF",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">COMPLEMENTOS DE PAGO PDF</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"COMPLEMENTOS_PAGO_XML",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">COMPLEMENTOS DE PAGO XML</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"CANCELACIONES_PDF",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">ADJUNTAR CANCELACIONES PDF</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"CANCELACIONES_XML",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">ADJUNTAR CANCELACIONES XML</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"ADJUNTAR_FACTURA_DE_COMISION_PDF",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">ADJUNTAR FACTURA DE COMISIÓN DESCONTADA PDF</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"ADJUNTAR_FACTURA_DE_COMISION_XML",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">ADJUNTAR FACTURA DE COMISIÓN DESCONTADA XML</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"CALCULO_DE_COMISION",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">ADJUNTAR CALCULO DE COMISIÓN </th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"MONTO_DE_COMISION",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">MONTO DE COMISIÓN:</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"COMPROBANTE_DE_DEVOLUCION",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">ADJUNTAR COMPROBANTE DE DEVOLUCIÓN DE DINERO A EPC</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"NOTA_DE_CREDITO_COMPRA",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">ADJUNTAR NOTA DE CREDITO DE COMPRA</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"POLIZA_NUMERO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">PÓLIZA NÚMERO</th><?php } ?>
<?php } ?>

<?php if($database->plantilla_filtro($nombreTabla,"EJECUTIVOTARJETA",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">NOMBRE DEL EJECUTIVO<br> TITULAR DE LA TARJETA</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"BANCO_ORIGEN",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">INSTITUCIÓN BANCARIA</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"NOMBRE_DEL_EJECUTIVO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">NOMBRE DEL EJECUTIVO<br> QUE REALIZÓ LA COMPRA</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"NOMBRE_DEL_AYUDO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">NOMBRE DEL EJECUTIVO<br> QUE INGRESO ESTÁ FACTURA</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"OBSERVACIONES_1",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center;width:700px;min-width:700px;max-width:700px;">OBSERVACIONES 1</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"ADJUNTAR_ARCHIVO_1",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#c9e8e8;text-align:center">ADJUNTAR ARCHIVO RELACIONADO<br> CON ESTA FACTURA </th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"NOMBRE_RECEPTOR",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#f9f3a1;text-align:center">NOMBRE RECEPTOR</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"RFC_RECEPTOR",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#f9f3a1;text-align:center">RFC RECEPTOR</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"REGIMEN_FISCAL",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#f9f3a1;text-align:center">REGÍMEN FISCAL</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"UUID",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#f9f3a1;text-align:center">UUID:</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"FOLIO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#f9f3a1;text-align:center">FOLIO</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"SERIE",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#f9f3a1;text-align:center">SERIE</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"CLAVE_UNIDAD",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#f9f3a1;text-align:center">CLAVE DE UNIDAD:</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"CANTIDAD",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#f9f3a1;text-align:center">CANTIDAD</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"CLAVE_PODUCTO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#f9f3a1;text-align:center">CLAVE DE PRODUCTO O SERVICIO</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"DESCRIPCION",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#f9f3a1;text-align:center">DESCRIPCIÓN</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"MonedaF",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#f9f3a1;text-align:center">MONEDA:</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"TIPO_CAMBIO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#f9f3a1;text-align:center">TIPO DE CAMBIO:</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"USO_CFDI",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#f9f3a1;text-align:center">USO DE CFDI</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"metodoDePago",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#f9f3a1;text-align:center">METODO DE PAGO:</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"CONDICIONES_PAGO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#f9f3a1;text-align:center">CONDICIONES DE PAGO:</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"TIPO_COMPROBANTE",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#f9f3a1;text-align:center">TIPO DE COMPROBANTE:</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"VERSION",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#f9f3a1;text-align:center">VERSIÓN:</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"FECHA_TIMBRADO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#f9f3a1;text-align:center">FECHA DE TIMBRADO:</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"subTotal",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#f9f3a1;text-align:center">SUBTOTAL</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"propina",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#f9f3a1;text-align:center">SERVICIO, PROPINA,ISH Y SANAMIENTO</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"Descuento",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#f9f3a1;text-align:center">Descuento</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"TOTAL_IMPUESTOS_TRASLADADOS",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#f9f3a1;text-align:center">IVA</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"TOTAL_IMPUESTOS_RETENIDOS",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#f9f3a1;text-align:center">TOTAL DE IMPUESTOS RETENIDOS</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"TUA",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#f9f3a1;text-align:center">TUA:</th><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"total",$altaeventos,$DEPARTAMENTO)=="si"){ ?><th style="background:#f9f3a1;text-align:center">TOTAL:</th><?php } ?>

<th style="background:#f16c4f;text-align:center">46% PERDIDA DE COSTO FISCAL</th>
<?php if($p_sincuarenta_ver){ ?><th style="background:#c6eaaa;text-align:center">SIN 46%</th><?php } ?>
<th style="background:#c9e8e8;text-align:center"></th>
<th style="background:#c9e8e8;text-align:center"></th>
<th style="background:#c9e8e8;text-align:center"></th>
            </tr>
            <tr>
<td style="background:#c9e8e8"></td>
<td style="background:#c9e8e8"></td>
<td style="background:#c9e8e8"></td>
<td style="background:#c9e8e8"></td>
<td style="background:#c9e8e8"></td>
<td style="background:#c9e8e8"></td>
<td style="background:#c9e8e8"></td>
<?php if ($p_rechazo_ver) { ?><td style="background:#c9e8e8"></td><?php } ?>

<?php if($database->plantilla_filtro($nombreTabla,"FECHA_DE_LLENADO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="FECHA_DE_LLENADO_1" value="<?php echo $FECHA_DE_LLENADO; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"ADJUNTAR_FACTURA_XML",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8;text-align:center">
		<div class="form-check" style="margin:0; white-space:nowrap;">
			<input class="form-check-input" type="checkbox" value="si" id="ADJUNTAR_FACTURA_XML_VACIO" <?php if($ADJUNTAR_FACTURA_XML_VACIO==='si'){echo 'checked';} ?>>
			<label class="form-check-label" for="ADJUNTAR_FACTURA_XML_VACIO">VACÍOS</label>
		</div>
	</div></td>
<?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"ADJUNTAR_FACTURA_PDF",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="ADJUNTAR_FACTURA_PDF_1" value="<?php echo $ADJUNTAR_FACTURA_PDF; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"NOMBRE_COMERCIAL",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="NOMBRE_COMERCIAL_1" value="<?php echo $NOMBRE_COMERCIAL; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"RAZON_SOCIAL",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="RAZON_SOCIAL_1" value="<?php echo $RAZON_SOCIAL; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"RFC_PROVEEDOR",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="RFC_PROVEEDOR_1" value="<?php echo $RFC_PROVEEDOR; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"NUMERO_EVENTO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="NUMERO_EVENTO_1" value="<?php echo $NUMERO_EVENTO; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"NOMBRE_EVENTO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="NOMBRE_EVENTO_1" value="<?php echo $NOMBRE_EVENTO; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"MOTIVO_GASTO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="MOTIVO_GASTO_1" value="<?php echo $MOTIVO_GASTO; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"CONCEPTO_PROVEE",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="CONCEPTO_PROVEE_1" value="<?php echo $CONCEPTO_PROVEE; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"MONTO_FACTURA",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="MONTO_FACTURA_1" value="<?php echo $MONTO_FACTURA; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"IVA",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="IVA_1" value="<?php echo $IVA; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"TImpuestosRetenidosIVA",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="TImpuestosRetenidosIVA_5" value="<?php echo $TImpuestosRetenidosIVA; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"TImpuestosRetenidosISR",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="TImpuestosRetenidosISR_5" value="<?php echo $TImpuestosRetenidosISR; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"MONTO_PROPINA",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="MONTO_PROPINA_1" value="<?php echo $MONTO_PROPINA; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"IMPUESTO_HOSPEDAJE",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="IMPUESTO_HOSPEDAJE_1" value="<?php echo $IMPUESTO_HOSPEDAJE; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"descuentos",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="descuentos_5" value="<?php echo $descuentos; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"MONTO_DEPOSITAR",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="MONTO_DEPOSITAR_1" value="<?php echo $MONTO_DEPOSITAR; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"MATCH",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="MATCH_1" value="<?php echo $MATCH; ?>"></td><td style="background:#c9e8e8"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"TIPO_DE_MONEDA",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="TIPO_DE_MONEDA_1" value="<?php echo $TIPO_DE_MONEDA; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"TIPO_CAMBIOP",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="TIPO_CAMBIOP_1" value="<?php echo $TIPO_CAMBIOP; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"TOTAL_ENPESOS",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="TOTAL_ENPESOS_1" value="<?php echo $TOTAL_ENPESOS; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"PFORMADE_PAGO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#f48a81"><input type="text" class="form-control" id="PFORMADE_PAGO_1" value="<?php echo $PFORMADE_PAGO; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"FECHA_A_DEPOSITAR",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#f48a81;text-align:center"><input type="date" class="form-control" id="FECHA_A_DEPOSITAR_1" value="<?php echo $FECHA_A_DEPOSITAR; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"STATUS_DE_PAGO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#f48a81;text-align:center"><input type="text" class="form-control" id="STATUS_DE_PAGO_1" value="<?php echo $STATUS_DE_PAGO; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"ADJUNTAR_COTIZACION",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#f48a81;text-align:center"><input type="text" class="form-control" id="ADJUNTAR_COTIZACION_1_1" value="<?php echo $ADJUNTAR_COTIZACION_1_1; ?>"></td><?php } ?>

<?php if($p_campos_comvyo_ver){ ?>
<?php if($database->plantilla_filtro($nombreTabla,"ACTIVO_FIJO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="ACTIVO_FIJO_1" value="<?php echo $ACTIVO_FIJO; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"GASTO_FIJO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="GASTO_FIJO_1" value="<?php echo $GASTO_FIJO; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"PAGAR_CADA",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="PAGAR_CADA_1" value="<?php echo $PAGAR_CADA; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"FECHA_PPAGO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="date" class="form-control" id="FECHA_PPAGO_1" value="<?php echo $FECHA_PPAGO; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"FECHA_TPROGRAPAGO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="date" class="form-control" id="FECHA_TPROGRAPAGO_1" value="<?php echo $FECHA_TPROGRAPAGO; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"NUMERO_EVENTOFIJO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="NUMERO_EVENTOFIJO_1" value="<?php echo $NUMERO_EVENTOFIJO; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"CLASI_GENERAL",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="CLASI_GENERAL_1" value="<?php echo $CLASI_GENERAL; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"SUB_GENERAL",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="SUB_GENERAL_1" value="<?php echo $SUB_GENERAL; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"COMPLEMENTOS_PAGO_PDF",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="COMPLEMENTOS_PAGO_PDF_1" value="<?php echo $COMPLEMENTOS_PAGO_PDF; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"COMPLEMENTOS_PAGO_XML",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="COMPLEMENTOS_PAGO_XML_1" value="<?php echo $COMPLEMENTOS_PAGO_XML; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"CANCELACIONES_PDF",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="CANCELACIONES_PDF_1" value="<?php echo $CANCELACIONES_PDF; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"CANCELACIONES_XML",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="CANCELACIONES_XML_1" value="<?php echo $CANCELACIONES_XML; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"ADJUNTAR_FACTURA_DE_COMISION_PDF",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="ADJUNTAR_FACTURA_DE_COMISION_PDF_1" value="<?php echo $ADJUNTAR_FACTURA_DE_COMISION_PDF; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"ADJUNTAR_FACTURA_DE_COMISION_XML",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="ADJUNTAR_FACTURA_DE_COMISION_XML_1" value="<?php echo $ADJUNTAR_FACTURA_DE_COMISION_XML; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"CALCULO_DE_COMISION",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="CALCULO_DE_COMISION_1" value="<?php echo $CALCULO_DE_COMISION; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"MONTO_DE_COMISION",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="MONTO_DE_COMISION_1" value="<?php echo $MONTO_DE_COMISION; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"COMPROBANTE_DE_DEVOLUCION",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="COMPROBANTE_DE_DEVOLUCION_1" value="<?php echo $COMPROBANTE_DE_DEVOLUCION; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"NOTA_DE_CREDITO_COMPRA",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="NOTA_DE_CREDITO_COMPRA_1" value="<?php echo $NOTA_DE_CREDITO_COMPRA; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"POLIZA_NUMERO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="POLIZA_NUMERO_1" value="<?php echo $POLIZA_NUMERO; ?>"></td><?php } ?>
<?php } ?>

<?php if($database->plantilla_filtro($nombreTabla,"EJECUTIVOTARJETA",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="EJECUTIVOTARJETA_1" value="<?php echo $EJECUTIVOTARJETA; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"BANCO_ORIGEN",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="BANCO_ORIGEN_1" value="<?php echo $BANCO_ORIGEN; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"NOMBRE_DEL_EJECUTIVO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="NOMBRE_DEL_EJECUTIVO_1" value="<?php echo $NOMBRE_DEL_EJECUTIVO; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"NOMBRE_DEL_AYUDO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8"><input type="text" class="form-control" id="NOMBRE_DEL_AYUDO_1" value="<?php echo $NOMBRE_DEL_EJECUTIVO; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"OBSERVACIONES_1",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8;width:700px;min-width:700px;max-width:700px;"><input type="text" class="form-control" id="OBSERVACIONES_1_2" value="<?php echo $OBSERVACIONES_1; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"ADJUNTAR_ARCHIVO_1",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#c9e8e8;"><input type="text" class="form-control" id="ADJUNTAR_ARCHIVO_1" value="<?php echo $ADJUNTAR_ARCHIVO_1; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"NOMBRE_RECEPTOR",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#f9f3a1;text-align:center"><input type="text" class="form-control" id="nombreR" value="<?php echo $nombreR; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"RFC_RECEPTOR",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#f9f3a1;text-align:center"><input type="text" class="form-control" id="rfcR" value="<?php echo $rfcR; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"REGIMEN_FISCAL",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#f9f3a1;text-align:center"><input type="text" class="form-control" id="regimenE" value="<?php echo $regimenE; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"UUID",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#f9f3a1;text-align:center"><input type="text" class="form-control" id="UUID" value="<?php echo $UUID; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"FOLIO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#f9f3a1;text-align:center"><input type="text" class="form-control" id="folio" value="<?php echo $folio; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"SERIE",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#f9f3a1;text-align:center"><input type="text" class="form-control" id="serie" value="<?php echo $serie; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"CLAVE_UNIDAD",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#f9f3a1;text-align:center"><input type="text" class="form-control" id="ClaveUnidadConcepto" value="<?php echo $ClaveUnidadConcepto; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"CANTIDAD",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#f9f3a1;text-align:center"><input type="text" class="form-control" id="CantidadConcepto" value="<?php echo $CantidadConcepto; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"CLAVE_PODUCTO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#f9f3a1;text-align:center"><input type="text" class="form-control" id="ClaveProdServConcepto" value="<?php echo $ClaveProdServConcepto; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"DESCRIPCION",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#f9f3a1;text-align:center"><input type="text" class="form-control" id="DescripcionConcepto" value="<?php echo $DescripcionConcepto; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"MonedaF",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#f9f3a1;text-align:center"><input type="text" class="form-control" id="Moneda" value="<?php echo $Moneda; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"TIPO_CAMBIO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#f9f3a1;text-align:center"><input type="text" class="form-control" id="TipoCambio" value="<?php echo $TipoCambio; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"USO_CFDI",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#f9f3a1;text-align:center"><input type="text" class="form-control" id="UsoCFDI" value="<?php echo $UsoCFDI; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"metodoDePago",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#f9f3a1;text-align:center"><input type="text" class="form-control" id="metodoDePago" value="<?php echo $metodoDePago; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"CONDICIONES_PAGO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#f9f3a1;text-align:center"><input type="text" class="form-control" id="condicionesDePago" value="<?php echo $condicionesDePago; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"TIPO_COMPROBANTE",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#f9f3a1;text-align:center"><input type="text" class="form-control" id="tipoDeComprobante" value="<?php echo $tipoDeComprobante; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"VERSION",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#f9f3a1;text-align:center"><input type="text" class="form-control" id="Version" value="<?php echo $Version; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"FECHA_TIMBRADO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#f9f3a1;text-align:center"><input type="text" class="form-control" id="fechaTimbrado" value="<?php echo $fechaTimbrado; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"SUBTOTAL",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#f9f3a1;text-align:center"><input type="text" class="form-control" id="subTotal" value="<?php echo $subTotal; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"propina",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#f9f3a1;text-align:center"><input type="text" class="form-control" id="propina" value="<?php echo $propina; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"Descuento",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#f9f3a1;text-align:center"><input type="text" class="form-control" id="Descuento" value="<?php echo $Descuento; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"TOTAL_IMPUESTOS_TRASLADADOS",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#f9f3a1;text-align:center"><input type="text" class="form-control" id="TImpuestosTrasladados" value="<?php echo $TImpuestosTrasladados; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"TOTAL_IMPUESTOS_RETENIDOS",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#f9f3a1;text-align:center"><input type="text" class="form-control" id="TImpuestosRetenidos" value="<?php echo $TImpuestosRetenidos; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"TUA",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#f9f3a1;text-align:center"><input type="text" class="form-control" id="TUA" value="<?php echo $TUA; ?>"></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"total",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="background:#f9f3a1;text-align:center"><input type="text" class="form-control" id="total" value="<?php echo $total; ?>"></td><?php } ?>
<td style="background:#f16c4f;text-align:center"><input type="text" class="form-control" id="PorfaltaDeFactura" value="<?php echo $PorfaltaDeFactura; ?>"></td>
<?php if($p_sincuarenta_ver){ ?><td style="background:#c6eaaa;text-align:center"></td><?php } ?>
		<td style="background:#c9e8e8"></td>
		<td style="background:#c9e8e8"></td>
		<td style="background:#c9e8e8"></td>
            </tr>			
        </thead>
		<?php if ($numrows<0){ ?>
		</table>
		<?php } else { ?>		
        <tbody>
		<?php
$finales = 0;
$totales = 'no';
foreach ($datos as $key => $row) {
    $colspan = 0;
    $colspan2 = 0;
    $fondo_existe_xml = "";
    $fondo_existe_xml2 = "";
    $supropinamashospedaje = 0;

// Consultar si existe complemento de pago XML para este registro
$queryComplemento = $database->Listado_subefacturaDOCTOS($row['07COMPROBACIONid']);
$tieneComplementoXML = false;
while ($rowDoc = mysqli_fetch_array($queryComplemento)) {
    if (!empty($rowDoc['COMPLEMENTOS_PAGO_XML'])) {
        $tieneComplementoXML = true;
        break;
    }
}

// Ahora sí evaluar el color
if (isset($row['STATUS_DE_PAGO']) && $row['STATUS_DE_PAGO'] == 'RECHAZADO') {
    $fondo_existe_xml = "style='background-color: #ff0000'";
    $fondo_existe_xml2 = "style='background-color: #ff0000'";
} 
else if (isset($row['PFORMADE_PAGO']) && $row['PFORMADE_PAGO'] != '04') {
    if ($tieneComplementoXML) {
        $fondo_existe_xml = "style='background-color: #ffffff'";
        $fondo_existe_xml2 = "style='background-color: #ffffff'";
    } else {
        $fondo_existe_xml = "style='background-color: #ffb6c1'";
        $fondo_existe_xml2 = "style='background-color: #ffb6c1'";
    }
}
else if (!empty($row['ClaveProdServ'])) {
    $fondo_existe_xml = "style='background-color: #ffffff'";
    $fondo_existe_xml2 = "style='background-color: #ffffff'";
}
else if (empty($row['ClaveProdServ'])) {
    $fondo_existe_xml2 = "style='background-color: #fdfe87'";
    $fondo_existe_xml = "style='background-color: #fdfe87'";
}
else {
    $fondo_existe_xml = "";
    $fondo_existe_xml2 = "";
}
?>
<tr <?php echo $fondo_existe_xml2; ?>>
<td>
    <input type="checkbox" class="checkbox" data-id="<?php echo $row['07COMPROBACIONid']; ?>" 
           style="transform: scale(1.1); cursor: pointer;" 
           onchange="
               const fila = this.closest('tr');
               const id = this.getAttribute('data-id');
               if (this.checked) {
                   fila.style.filter = 'brightness(65%) sepia(100%) saturate(200%) hue-rotate(0deg)';
                   localStorage.setItem('checkbox_' + id, 'checked');
               } else {
                   fila.style.filter = 'none';
                   localStorage.removeItem('checkbox_' + id);
               }">
</td>
<td <?php echo $fondo_existe_xml; ?>><?php echo $row['07COMPROBACIONid']; $colspan += 1; ?></td>

<?php
if (!function_exists('renderDocumentLinks')) {
	function renderDocumentLinks($rawValue) {
		if (!isset($rawValue) || trim((string)$rawValue) === '') return '';
		$links = '';
		$rawValue = html_entity_decode((string)$rawValue);
		$chunks = preg_split('/\s*,\s*/', $rawValue, -1, PREG_SPLIT_NO_EMPTY);
		$files = [];
		$currentFile = '';
		foreach ($chunks as $chunk) {
			$chunk = trim($chunk);
			if ($chunk === '') continue;
			$currentFile = $currentFile === '' ? $chunk : $currentFile . ',' . $chunk;
			if (preg_match('/\.[a-z0-9]{2,6}(?:[?#].*)?$/i', $currentFile) === 1) {
				$files[] = $currentFile;
				$currentFile = '';
			}
		}
		if ($currentFile !== '') $files[] = $currentFile;
		if (empty($files)) $files[] = trim($rawValue);
		foreach ($files as $file) {
			$file = trim((string)$file);
			if ($file === '') continue;
			if (preg_match('#^https?://#i', $file) === 1) {
				$filePath = $file;
			} else {
				$isAbsolutePath = strpos($file, '/') === 0;
				$fileNormalizado = ltrim($file, '/');
				if (stripos($fileNormalizado, 'includes/archivos/') === 0) {
					$filePath = $fileNormalizado;
				} else if ($isAbsolutePath) {
					$filePath = $fileNormalizado;
				} else {
					$filePath = 'includes/archivos/' . $fileNormalizado;
				}
				$partesPath = array_map('rawurlencode', explode('/', $filePath));
				$filePath = implode('/', $partesPath);
				if ($isAbsolutePath) $filePath = '/' . $filePath;
			}
			$links .= '<a href="' . $filePath . '" target="_blank">Ver!</a><br/>';
		}
		return $links;
	}
}

if (!function_exists('renderLastDocumentLink')) {
	function renderLastDocumentLink($rawValue) {
		if (!isset($rawValue) || trim((string)$rawValue) === '') return '';
		$rawValue = html_entity_decode((string)$rawValue);
		$chunks = preg_split('/\s*,\s*/', $rawValue, -1, PREG_SPLIT_NO_EMPTY);
		$files = [];
		$currentFile = '';
		foreach ($chunks as $chunk) {
			$chunk = trim($chunk);
			if ($chunk === '') continue;
			$currentFile = $currentFile === '' ? $chunk : $currentFile . ',' . $chunk;
			if (preg_match('/\.[a-z0-9]{2,6}(?:[?#].*)?$/i', $currentFile) === 1) {
				$files[] = $currentFile;
				$currentFile = '';
			}
		}
		if ($currentFile !== '') $files[] = $currentFile;
		if (empty($files)) $files[] = trim($rawValue);

		$file = trim((string)end($files));
		if ($file === '') return '';

		if (preg_match('#^https?://#i', $file) === 1) {
			$filePath = $file;
		} else {
			$isAbsolutePath = strpos($file, '/') === 0;
			$fileNormalizado = ltrim($file, '/');
			if (stripos($fileNormalizado, 'includes/archivos/') === 0) {
				$filePath = $fileNormalizado;
			} else if ($isAbsolutePath) {
				$filePath = $fileNormalizado;
			} else {
				$filePath = 'includes/archivos/' . $fileNormalizado;
			}
			$partesPath = array_map('rawurlencode', explode('/', $filePath));
			$filePath = implode('/', $partesPath);
			if ($isAbsolutePath) $filePath = '/' . $filePath;
		}
		return '<a href="' . $filePath . '" target="_blank">Ver!</a>';
	}
}

$ADJUNTAR_FACTURA_PDF = ''; $ADJUNTAR_FACTURA_XML = ''; $ADJUNTAR_COTIZACION = ''; $CONPROBANTE_TRANSFERENCIA = '';
$ADJUNTAR_ARCHIVO_1 = ''; $COMPLEMENTOS_PAGO_PDF = ''; $COMPLEMENTOS_PAGO_XML = ''; $CANCELACIONES_PDF = '';
$CANCELACIONES_XML = ''; $ADJUNTAR_FACTURA_DE_COMISION_PDF = ''; $ADJUNTAR_FACTURA_DE_COMISION_XML = '';
$CALCULO_DE_COMISION = ''; $COMPROBANTE_DE_DEVOLUCION = ''; $NOTA_DE_CREDITO_COMPRA = ''; $FOTO_ESTADO_PROVEE11 = '';

$querycontrasDOCTOS = $database->Listado_subefacturaDOCTOS($row['07COMPROBACIONid']);
while ($rowDOCTOS = mysqli_fetch_array($querycontrasDOCTOS)) {
	$tmpPDF = renderLastDocumentLink($rowDOCTOS["ADJUNTAR_FACTURA_PDF"]);
	if ($tmpPDF !== '') $ADJUNTAR_FACTURA_PDF = $tmpPDF;

	$tmpXML = renderLastDocumentLink($rowDOCTOS["ADJUNTAR_FACTURA_XML"]);
	if ($tmpXML !== '') $ADJUNTAR_FACTURA_XML = $tmpXML;
	$ADJUNTAR_COTIZACION             .= renderDocumentLinks($rowDOCTOS["ADJUNTAR_COTIZACION"]);
	$CONPROBANTE_TRANSFERENCIA       .= renderDocumentLinks($rowDOCTOS["CONPROBANTE_TRANSFERENCIA"]);
	$COMPLEMENTOS_PAGO_PDF           .= renderDocumentLinks($rowDOCTOS["COMPLEMENTOS_PAGO_PDF"]);
	$COMPLEMENTOS_PAGO_XML           .= renderDocumentLinks($rowDOCTOS["COMPLEMENTOS_PAGO_XML"]);
	$CANCELACIONES_PDF               .= renderDocumentLinks($rowDOCTOS["CANCELACIONES_PDF"]);
	$CANCELACIONES_XML               .= renderDocumentLinks($rowDOCTOS["CANCELACIONES_XML"]);
	$ADJUNTAR_FACTURA_DE_COMISION_PDF .= renderDocumentLinks($rowDOCTOS["ADJUNTAR_FACTURA_DE_COMISION_PDF"]);
	$ADJUNTAR_FACTURA_DE_COMISION_XML .= renderDocumentLinks($rowDOCTOS["ADJUNTAR_FACTURA_DE_COMISION_XML"]);
	$CALCULO_DE_COMISION             .= renderDocumentLinks($rowDOCTOS["CALCULO_DE_COMISION"]);
	$COMPROBANTE_DE_DEVOLUCION       .= renderDocumentLinks($rowDOCTOS["COMPROBANTE_DE_DEVOLUCION"]);
	$NOTA_DE_CREDITO_COMPRA          .= renderDocumentLinks($rowDOCTOS["NOTA_DE_CREDITO_COMPRA"]);
	$FOTO_ESTADO_PROVEE11            .= renderDocumentLinks($rowDOCTOS["FOTO_ESTADO_PROVEE11"]);
	$ADJUNTAR_ARCHIVO_1              .= renderDocumentLinks($rowDOCTOS["ADJUNTAR_ARCHIVO_1"]);
}
?>

<td style="text-align:center; background:#ceffcc">
<input type="checkbox" style="width:30PX;" checked="checked" disabled="disabled" class="form-check-input" 
    id="STATUS_RESPONSABLE_EVENTO<?php echo $row["07COMPROBACIONid"]; ?>" 
    name="STATUS_RESPONSABLE_EVENTO<?php echo $row["07COMPROBACIONid"]; ?>" 
    value="<?php echo $row["07COMPROBACIONid"]; ?>" 
    onclick="STATUS_RESPONSABLE_EVENTO(<?php echo $row["07COMPROBACIONid"]; ?>)" 
    <?php if($row["STATUS_RESPONSABLE_EVENTO"]=='si'){ echo "checked"; } $colspan += 1; ?>/>
</td>

<?php
$statusRechazado = isset($row["STATUS_RECHAZADO"]) ? $row["STATUS_RECHAZADO"] : 'no';
$numeroEventoRegistro = isset($row["NUMERO_EVENTO"]) ? strtoupper(trim((string)$row["NUMERO_EVENTO"])) : '';
$tienePermisoVenta = $numeroEventoRegistro !== '' && isset($eventosAutorizadosVentas[$numeroEventoRegistro]);
?>
<!-- VENTAS -->
<td style="text-align:center; background:<?php echo ($row["STATUS_VENTAS"] == 'si') ? '#ceffcc' : '#e9d8ee'; ?>;"
	id="color_VENTAS<?php echo $row["07COMPROBACIONid"]; ?>">
	<input type="checkbox" style="width:30px;" class="form-check-input"
		id="STATUS_VENTAS<?php echo $row["07COMPROBACIONid"]; ?>"
		name="STATUS_VENTAS<?php echo $row["07COMPROBACIONid"]; ?>"
		value="<?php echo $row["07COMPROBACIONid"]; ?>"
		data-permiso-principal="<?php echo $tienePermisoVenta ? 'si' : 'no'; ?>"
		onclick="STATUS_VENTAS(<?php echo $row["07COMPROBACIONid"]; ?>)"
		<?php
		$atributosVentas = [];
		if ($row["STATUS_VENTAS"] === 'si') {
			$atributosVentas[] = 'checked';
			$atributosVentas[] = 'disabled';
		} else {
			if ($statusRechazado === 'si') {
				$atributosVentas[] = 'disabled';
				$atributosVentas[] = 'style="cursor:not-allowed;"';
				$atributosVentas[] = 'title="No se puede autorizar por ventas: pago rechazado"';
			} elseif (!$tienePermisoVenta) {
				$atributosVentas[] = 'disabled';
			}
		}
		echo implode(' ', $atributosVentas);
		?> />
	<?php $colspan += 1; ?>

</td>

<td style="text-align:center; background:<?php echo ($row["STATUS_FINANZAS"] == 'si') ? '#ceffcc' : '#e9d8ee'; ?>;" id="color_FINANZAS<?php echo $row["07COMPROBACIONid"]; ?>">
    <input type="checkbox" style="width:30px;" class="form-check-input"
        id="STATUS_FINANZAS<?php echo $row["07COMPROBACIONid"]; ?>"
        name="STATUS_FINANZAS<?php echo $row["07COMPROBACIONid"]; ?>"
        value="<?php echo $row["07COMPROBACIONid"]; ?>"
       <?php
        // OPTIMIZADO: usar variables precalculadas
        if ($row["STATUS_FINANZAS"] == 'si') {
            echo $p_direccion_mod
                ? 'checked onclick="STATUS_FINANZAS('.$row["07COMPROBACIONid"].')"'
                : 'checked disabled style="cursor:not-allowed;" title="Sin permiso para modificar"';
        } else {
            echo $p_direccion_ver
                ? 'onclick="STATUS_FINANZAS('.$row["07COMPROBACIONid"].')"'
                : 'disabled style="cursor:not-allowed;" title="Sin permiso para modificar"';
        }
        ?>
    />
    <?php $colspan += 1; ?>
</td>

<td style="text-align:center; background:<?php echo ($row["STATUS_AUDITORIA2"] == 'si') ? '#ceffcc' : '#e9d8ee'; ?>;" id="color_AUDITORIA2<?php echo $row["07COMPROBACIONid"]; ?>">
    <input type="checkbox" style="width:30px; cursor:pointer;" class="form-check-input"
        id="STATUS_AUDITORIA2<?php echo $row["07COMPROBACIONid"]; ?>"
        name="STATUS_AUDITORIA2<?php echo $row["07COMPROBACIONid"]; ?>"
        value="<?php echo $row["07COMPROBACIONid"]; ?>"
        <?php
        // OPTIMIZADO: usar variables precalculadas
        if ($row["STATUS_AUDITORIA2"] == 'si') {
            echo $p_auditoria_mod
                ? 'checked onclick="STATUS_AUDITORIA2('.$row["07COMPROBACIONid"].')"'
                : 'checked disabled style="cursor:not-allowed;" title="Ya autorizado"';
        } else {
            echo $p_auditoria_ver
                ? 'onclick="STATUS_AUDITORIA2('.$row["07COMPROBACIONid"].'); this.disabled=true; this.style.cursor=\'not-allowed\';"'
                : 'disabled style="cursor:not-allowed;" title="Sin permiso para modificar"';
        }
        ?>
    />
    <?php $colspan += 1; ?>
</td>

<td style="text-align:center; background:<?php echo ($row["STATUS_AUDITORIA3"] == 'si') ? '#ceffcc' : '#e9d8ee'; ?>;" id="color_AUDITORIA2<?php echo $row["07COMPROBACIONid"]; ?>">
    <input type="checkbox" style="width:30px; cursor:pointer;" class="form-check-input"
        id="STATUS_AUDITORIA3<?php echo $row["07COMPROBACIONid"]; ?>"
        name="STATUS_AUDITORIA3<?php echo $row["07COMPROBACIONid"]; ?>"
        value="<?php echo $row["07COMPROBACIONid"]; ?>"
        <?php
        // OPTIMIZADO: usar variables precalculadas
        if ($row["STATUS_AUDITORIA3"] == 'si') {
            echo $p_contabilidad_mod
                ? 'checked onclick="STATUS_AUDITORIA3('.$row["07COMPROBACIONid"].')"'
                : 'checked disabled style="cursor:not-allowed;" title="Ya autorizado"';
        } else {
            echo $p_contabilidad_ver
                ? 'onclick="STATUS_AUDITORIA3('.$row["07COMPROBACIONid"].'); this.disabled=true; this.style.cursor=\'not-allowed\';"'
                : 'disabled style="cursor:not-allowed;" title="Sin permiso para modificar"';
        }
        ?>
    />
    <?php $colspan += 1; ?>
</td>

<?php if ($p_rechazo_ver) { ?>
<td style="text-align:center; background:
	<?php echo ($statusRechazado == 'si') ? '#ceffcc' : '#e9d8ee'; ?>;"

 id="color_RECHAZADO<?php echo $row["07COMPROBACIONid"]; ?>">
    <?php
        $motivoRechazo = $database->obtener_motivo_rechazo($row["07COMPROBACIONid"]);
        $statusVentasAutorizado = isset($row["STATUS_VENTAS"]) && $row["STATUS_VENTAS"] == 'si';
        $mostrarAgregarRechazo = ($statusRechazado == 'si' && $motivoRechazo == '');
        $mostrarVerRechazo = ($statusRechazado == 'si' && $motivoRechazo != '');
        // OPTIMIZADO: usar variables precalculadas
        $permisoguardarRechazo = $p_rechazo_guardar;
        $permisomodificarRechazo = $p_rechazo_mod;
    ?>
    <input type="hidden" id="motivo_rechazo_<?php echo $row["07COMPROBACIONid"]; ?>" value="<?php echo htmlspecialchars($motivoRechazo, ENT_QUOTES, 'UTF-8'); ?>" />
    <input type="checkbox" style="width:30px; cursor:pointer;" class="form-check-input"
        id="STATUS_RECHAZADO<?php echo $row["07COMPROBACIONid"]; ?>"
        name="STATUS_RECHAZADO<?php echo $row["07COMPROBACIONid"]; ?>"
        value="<?php echo $row["07COMPROBACIONid"]; ?>"
        <?php
        if ($statusRechazado == 'si') {
            echo $permisomodificarRechazo
                ? 'checked onclick="STATUS_RECHAZADO('.$row["07COMPROBACIONid"].')" title="Pago rechazado"'
                : 'checked disabled style="cursor:not-allowed;" title="Pago rechazado"';
        } elseif ($statusVentasAutorizado) {
            echo 'disabled style="cursor:not-allowed;" title="No se puede rechazar: autorizado por ventas"';
        } else {
            echo ($permisoguardarRechazo || $permisomodificarRechazo)
                ? 'onclick="STATUS_RECHAZADO('.$row["07COMPROBACIONid"].')"'
                : 'disabled style="cursor:not-allowed;" title="Sin permiso para modificar"';
        }
        ?>
    />
    <?php if($permisoguardarRechazo || $permisomodificarRechazo){ ?>
    <button type="button" title="agregar!" id="agregar_rechazo_<?php echo $row['07COMPROBACIONid']; ?>"
            data-rechazo-id="<?php echo $row['07COMPROBACIONid']; ?>"
            style="border:none;background:transparent;cursor:pointer;color:#007bff;font-size:14px;<?php echo $mostrarAgregarRechazo ? '' : 'display:none;'; ?>"
            onclick="abrirFormularioRechazo(<?php echo $row['07COMPROBACIONid']; ?>)">agregar <br>motivo</button>
    <?php } ?>
    <button type="button" title="Ver motivo" id="ver_rechazo_<?php echo $row['07COMPROBACIONid']; ?>"
        data-rechazo-id="<?php echo $row['07COMPROBACIONid']; ?>"
        style="border:none;background:transparent;cursor:pointer;color:#28a745;font-size:16px;<?php echo $mostrarVerRechazo ? '' : 'display:none;'; ?>"
        onclick="verMotivoRechazo(<?php echo $row['07COMPROBACIONid']; ?>)">ver</button>
    <?php $colspan += 1; ?>
</td>
<?php } ?>

<?php  
if ($database->plantilla_filtro($nombreTabla,"FECHA_DE_LLENADO",$altaeventos,$DEPARTAMENTO)=="si") {
    $fechaHora = $row['FECHA_DE_LLENADO'];
    $fecha = date('d-m-Y', strtotime($fechaHora));
    $hora  = date('H:i:s', strtotime($fechaHora));
?>
<td style="text-align:center;">
    <?php echo $fecha; ?> 
    &nbsp;<span style="color:#2542C4; font-weight:bold;">
        <?php echo $hora; ?>
    </span>
</td>
<?php $colspan += 1; } ?>

<?php if($database->plantilla_filtro($nombreTabla,"ADJUNTAR_FACTURA_XML",$altaeventos,$DEPARTAMENTO)=="si"){ $colspan += 1; ?><td style="text-align:center"><?php echo $ADJUNTAR_FACTURA_XML; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"ADJUNTAR_FACTURA_PDF",$altaeventos,$DEPARTAMENTO)=="si"){ $colspan += 1; ?><td style="text-align:center"><?php echo $ADJUNTAR_FACTURA_PDF; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"NOMBRE_COMERCIAL",$altaeventos,$DEPARTAMENTO)=="si"){ $colspan += 1; ?><td style="text-align:center"><?php echo $row['NOMBRE_COMERCIAL'];?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"RAZON_SOCIAL",$altaeventos,$DEPARTAMENTO)=="si"){ $colspan += 1; ?><td style="text-align:center"><?php echo $row['RAZON_SOCIAL'];?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"RFC_PROVEEDOR",$altaeventos,$DEPARTAMENTO)=="si"){ $colspan += 1; ?><td style="text-align:center"><?php echo $row['RFC_PROVEEDOR'];?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"NUMERO_EVENTO",$altaeventos,$DEPARTAMENTO)=="si"){ $colspan += 1; ?><td style="text-align:center"><?php echo $row['NUMERO_EVENTO'];?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"NOMBRE_EVENTO",$altaeventos,$DEPARTAMENTO)=="si"){ $colspan += 1; ?><td style="text-align:center"><?php echo $row['NOMBRE_EVENTO'];?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"MOTIVO_GASTO",$altaeventos,$DEPARTAMENTO)=="si"){ $colspan += 1; ?><td style="text-align:center"><?php echo $row['MOTIVO_GASTO'];?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"CONCEPTO_PROVEE",$altaeventos,$DEPARTAMENTO)=="si"){ $colspan += 1; ?><td style="text-align:center"><?php echo $row['CONCEPTO_PROVEE'];?></td><?php } ?>

<?php $colspan2 = $colspan; ?>

<?php if($database->plantilla_filtro($nombreTabla,"MONTO_FACTURA",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center">$<?php echo number_format($row['MONTO_FACTURA'],2,'.',','); $totales='si'; $MONTO_FACTURA12 += $row['MONTO_FACTURA']; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"IVA",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo number_format($row['IVA'],2,'.',','); $totales='si'; $IVA12 += $row['IVA']; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"TImpuestosRetenidosIVA",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo number_format($row['TImpuestosRetenidosIVA'],2,'.',','); $totales='si'; $TImpuestosRetenidosIVA12 += $row['TImpuestosRetenidosIVA']; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"TImpuestosRetenidosISR",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo number_format($row['TImpuestosRetenidosISR'],2,'.',','); $totales='si'; $TImpuestosRetenidosISR12 += $row['TImpuestosRetenidosISR']; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"MONTO_PROPINA",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center">$<?php echo number_format($row['MONTO_PROPINA'],2,'.',','); $totales='si'; $MONTO_PROPINA12 += $row['MONTO_PROPINA']; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"IMPUESTO_HOSPEDAJE",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center">$<?php echo number_format($row['IMPUESTO_HOSPEDAJE'],2,'.',','); $totales='si'; $IMPUESTO_HOSPEDAJE12 += $row['IMPUESTO_HOSPEDAJE']; $colspan2 += 1; $supropinamashospedaje = $row['MONTO_PROPINA'] + $row['IMPUESTO_HOSPEDAJE']; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"descuentos",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center">$<?php echo number_format($row['descuentos'],2,'.',','); $totales='si'; $descuentos12 += $row['descuentos']; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"MONTO_DEPOSITAR",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center">$<?php echo number_format($row['MONTO_DEPOSITAR'],2,'.',','); $totales='si'; $MONTO_DEPOSITAR12 += $row['MONTO_DEPOSITAR']; $colspan2 += 1; ?></td><?php } ?>

<?php if($database->plantilla_filtro($nombreTabla,"MATCH",$altaeventos,$DEPARTAMENTO)=="si"){ ?>
<td style="text-align:center" class="dropdown">
<input style="text-align:center" class="btn btn-success dropdown-toggle" value="MATCH" type="button" data-bs-toggle="dropdown" aria-expanded="false">
<ul class="dropdown-menu">
<li style="background-color:#edccf3; cursor:pointer;" name="view" value="COMPROBAR" id="<?php echo $row["07COMPROBACIONid"]; ?>" class="dropdown-item view_MATCH2filtroinbursa"><a><?php echo $row["07COMPROBACIONid"]; ?> COMPROBAR ESTADO DE CUENTA INBURSA</a></li>
<li style="background-color:#ccd9f3; cursor:pointer;" name="view" value="COMPROBAR" id="<?php echo $row["07COMPROBACIONid"]; ?>" class="dropdown-item view_MATCH2filtrobbva"><a><?php echo $row["07COMPROBACIONid"]; ?> COMPROBAR ESTADO DE CUENTA BBVA</a></li>
<li style="background-color:#edccf3; cursor:pointer;" name="view" value="COMPROBAR" id="<?php echo $row["07COMPROBACIONid"]; ?>" class="dropdown-item view_MATCH2filtroAMEX"><a><?php echo $row["07COMPROBACIONid"]; ?> COMPROBAR ESTADO DE CUENTA AMERICAN EXPRESS</a></li>
<li style="background-color:#edccf3; cursor:pointer;" name="view" value="COMPROBAR" id="<?php echo $row["07COMPROBACIONid"]; ?>" class="dropdown-item view_MATCH2filtroSIVALE"><a><?php echo $row["07COMPROBACIONid"]; ?> COMPROBAR ESTADO DE CUENTA SÍ VALE</a></li>
<li style="background-color:#ccd9f3"><a class="dropdown-item" href="MATCHESTADO.php" target="_blank">LINK A MATCH CON ESTADO DE CUENTA </a></li>
</td>
<td><input type="checkbox" style="width:30%;color:red" class="form-check-input" <?php echo $database->validaexistematch2COMPROBACIONtodos($row["07COMPROBACIONid"],'TARJETABBVA'); ?> disabled /></td>
<?php $colspan2 += 1; $colspan2 += 1; } ?>

<?php if($database->plantilla_filtro($nombreTabla,"TIPO_DE_MONEDA",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $row['TIPO_DE_MONEDA']; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"TIPO_CAMBIOP",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center">$<?php echo number_format($row['TIPO_CAMBIOP'],2,'.',','); $totales='si'; $TIPO_CAMBIOP12 += $row['TIPO_CAMBIOP']; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"TOTAL_ENPESOS",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center">$<?php echo number_format($row['TOTAL_ENPESOS'],2,'.',','); $totales='si'; $TOTAL_ENPESOS12 += $row['TOTAL_ENPESOS']; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"PFORMADE_PAGO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $row['PFORMADE_PAGO']; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"FECHA_A_DEPOSITAR",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $row['FECHA_A_DEPOSITAR']; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"STATUS_DE_PAGO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $row['STATUS_DE_PAGO']; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"ADJUNTAR_COTIZACION",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $ADJUNTAR_COTIZACION; $colspan2 += 1; ?></td><?php } ?>

<?php if($p_campos_comvyo_ver){ ?>
<?php if($database->plantilla_filtro($nombreTabla,"ACTIVO_FIJO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $row['ACTIVO_FIJO']; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"GASTO_FIJO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $row['GASTO_FIJO']; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"PAGAR_CADA",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $row['PAGAR_CADA']; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"FECHA_PPAGO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $row['FECHA_PPAGO']; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"FECHA_TPROGRAPAGO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $row['FECHA_TPROGRAPAGO']; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"NUMERO_EVENTOFIJO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $row['NUMERO_EVENTOFIJO']; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"CLASI_GENERAL",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $row['CLASI_GENERAL']; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"SUB_GENERAL",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $row['SUB_GENERAL']; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"COMPLEMENTOS_PAGO_PDF",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $COMPLEMENTOS_PAGO_PDF; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"COMPLEMENTOS_PAGO_XML",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $COMPLEMENTOS_PAGO_XML; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"CANCELACIONES_PDF",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $CANCELACIONES_PDF; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"CANCELACIONES_XML",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $CANCELACIONES_XML; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"ADJUNTAR_FACTURA_DE_COMISION_PDF",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $ADJUNTAR_FACTURA_DE_COMISION_PDF; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"ADJUNTAR_FACTURA_DE_COMISION_XML",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $ADJUNTAR_FACTURA_DE_COMISION_XML; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"CALCULO_DE_COMISION",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $CALCULO_DE_COMISION; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"MONTO_DE_COMISION",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $row['MONTO_DE_COMISION']; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"COMPROBANTE_DE_DEVOLUCION",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $COMPROBANTE_DE_DEVOLUCION; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"NOTA_DE_CREDITO_COMPRA",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $NOTA_DE_CREDITO_COMPRA; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"POLIZA_NUMERO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $row['POLIZA_NUMERO']; $colspan2 += 1; ?></td><?php } ?>
<?php } ?>

<?php  
if ($database->plantilla_filtro($nombreTabla, "EJECUTIVOTARJETA", $altaeventos, $DEPARTAMENTO) == "si") { 
    $idEjecutivo = isset($row['EJECUTIVOTARJETA']) ? trim($row['EJECUTIVOTARJETA']) : '';
    $nombreEjecutivo = $database->nombreCompletoPorID($idEjecutivo);
    $color = ($nombreEjecutivo == 'SIN INFORMACIÓN') ? 'color:#bfbfbf;' : '';
    echo '<td style="text-align:center; '.$color.'">'.$nombreEjecutivo.'</td>';
    $colspan2 += 1;
}
?>

<?php if($database->plantilla_filtro($nombreTabla,"BANCO_ORIGEN",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $row['BANCO_ORIGEN']; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"NOMBRE_DEL_EJECUTIVO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $row['NOMBRE_DEL_EJECUTIVO']; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"NOMBRE_DEL_AYUDO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $row['NOMBRE_DEL_AYUDO']; $colspan2 += 1; ?></td><?php } ?>

<?php if($database->plantilla_filtro($nombreTabla,"OBSERVACIONES_1",$altaeventos,$DEPARTAMENTO)=="si"){ ?>
<td style="text-align:left;width:700px;min-width:700px;max-width:700px;">
    <div style="width:700px; min-width:700px; max-width:700px; white-space:normal; word-break:break-word;"><?php echo htmlspecialchars($row['OBSERVACIONES_1']); ?></div>
</td><?php } ?>

<?php if($database->plantilla_filtro($nombreTabla,"ADJUNTAR_ARCHIVO_1",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $ADJUNTAR_ARCHIVO_1; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"NOMBRE_RECEPTOR",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $row['nombreR']; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"RFC_RECEPTOR",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $row['rfcR']; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"REGIMEN_FISCAL",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $row['regimenE']; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"UUID",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $row['UUID']; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"FOLIO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $row['folio']; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"SERIE",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $row['serie']; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"CLAVE_UNIDAD",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $row['ClaveUnidad']; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"CANTIDAD",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo number_format($row['Cantidad'],2,'.',','); $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"CLAVE_PODUCTO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $row['ClaveProdServ']; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"DESCRIPCION",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $row['CONCEPTO_PROVEE']; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"MonedaF",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $row['Moneda']; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"TIPO_CAMBIO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo number_format($row['TipoCambio'],2,'.',','); $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"USO_CFDI",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $row['UsoCFDI']; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"metodoDePago",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $row['metodoDePago']; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"CONDICIONES_PAGO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $row['condicionesDePago']; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"TIPO_COMPROBANTE",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $row['tipoDeComprobante']; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"VERSION",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $row['Version']; $colspan2 += 1; ?></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"FECHA_TIMBRADO",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><?php echo $row['fechaTimbrado']; $colspan2 += 1; ?></td><?php } ?>

<?php if($database->plantilla_filtro($nombreTabla,"SUBTOTAL",$altaeventos,$DEPARTAMENTO)=="si"){ ?>
<td style="text-align:center">$<?php 
$subTotal123 = isset($row['subTotal'])?$row['subTotal']:'';
$MONTO_FACTURA123 = isset($row['MONTO_FACTURA'])?$row['MONTO_FACTURA']:'';
if ($subTotal123 > 0) { $MONTO_FACTURAxm = number_format($subTotal123,2,'.',','); $MONTO_FACTURAxm2 = $subTotal123; }
else { $MONTO_FACTURAxm = number_format($MONTO_FACTURA123,2,'.',','); $MONTO_FACTURAxm2 = $MONTO_FACTURA123; }
$subTotal12 += $MONTO_FACTURAxm2;
echo $MONTO_FACTURAxm;
$totales2 = 'si';
?></td><?php } ?>

<?php if($database->plantilla_filtro($nombreTabla,"propina",$altaeventos,$DEPARTAMENTO)=="si"){ ?>
<td style="text-align:center"><?php echo number_format($row['propina'] + $supropinamashospedaje,2,'.',','); $propina12 += $row['propina'] + $supropinamashospedaje; $totales2='si'; ?></td><?php } ?>

<?php if($database->plantilla_filtro($nombreTabla,"Descuento",$altaeventos,$DEPARTAMENTO)=="si"){ ?>
<td style="text-align:center"><?php echo number_format($row['Descuento'],2,'.',','); $Descuento12 += $row['Descuento']; $totales2='si'; ?></td><?php } ?>

<?php if($database->plantilla_filtro($nombreTabla,"TOTAL_IMPUESTOS_TRASLADADOS",$altaeventos,$DEPARTAMENTO)=="si"){ ?>
<td style="text-align:center"><?php echo number_format($row['TImpuestosTrasladados'],2,'.',','); $TImpuestosTrasladados12 += $row['TImpuestosTrasladados']; $totales2='si'; ?></td><?php } ?>

<?php if($database->plantilla_filtro($nombreTabla,"TOTAL_IMPUESTOS_RETENIDOS",$altaeventos,$DEPARTAMENTO)=="si"){ ?>
<td style="text-align:center"><?php echo number_format($row['TImpuestosRetenidos'],2,'.',','); $TImpuestosRetenidos12 += $row['TImpuestosRetenidos']; $totales2='si'; ?></td><?php } ?>

<?php if($database->plantilla_filtro($nombreTabla,"TUA",$altaeventos,$DEPARTAMENTO)=="si"){ ?>
<td style="text-align:center"><?php echo number_format($row['TUA'],2,'.',','); $TUA12 += $row['TUA']; $TUA2='si'; ?></td><?php } ?>

<?php if($database->plantilla_filtro($nombreTabla,"total",$altaeventos,$DEPARTAMENTO)=="si"){ ?>
<td style="text-align:center" id="montoOriginal_<?php echo $row['07COMPROBACIONid']; ?>"><?php 
$STATUS_RECHAZADO = isset($row['STATUS_RECHAZADO']) ? $row['STATUS_RECHAZADO'] : '';
if (isset($STATUS_RECHAZADO) && $STATUS_RECHAZADO == "si") {
    $porfalta  = '0.00';
    $porfalta2 = 0;
    $totalf12 += 0;
    echo $porfalta;
    $totales2 = 'si';
} else {
    $total123           = isset($row['total'])           ? $row['total']           : '';
    $MONTO_DEPOSITAR123 = isset($row['MONTO_DEPOSITAR']) ? $row['MONTO_DEPOSITAR'] : '';

    if ($total123 > 0) { 
        $porfalta  = number_format($total123, 2, '.', ','); 
        $porfalta2 = $total123; 
    } else { 
        $porfalta  = number_format($MONTO_DEPOSITAR123, 2, '.', ','); 
        $porfalta2 = $MONTO_DEPOSITAR123; 
    }

    $totalf12 += $porfalta2;
    echo $porfalta;
    $totales2 = 'si';
}
?></td><?php } ?>

<td style="text-align:center" id="valorCalculado_<?php echo $row['07COMPROBACIONid']; ?>">
    <?php 
        if (($row['STATUS_CHECKBOX'] === 'no' || $row['STATUS_CHECKBOX'] === null) && strlen(trim($row['UUID'])) < 1) {
            $valorCalculado = $porfalta2 * 1.46;
            echo number_format($valorCalculado, 2, '.', ',');
            $PorfaltaDeFactura12 += $valorCalculado;
        }
    ?>
</td>

<?php if($p_sincuarenta_ver){ ?>   
<td style="text-align:center; background:<?php 
    if(strlen($row['UUID']) < 1) {
        echo ($row["STATUS_CHECKBOX"]=='si') ? '#ceffcc' : '#e9d8ee';
    } else { echo '#f0f0f0'; }
?>" id="color_CHECKBOX<?php echo $row["07COMPROBACIONid"]; ?>">
    <span id="buscanumero<?php echo $row["07COMPROBACIONid"]; ?>">
        <?php if(strlen($row['UUID']) < 1): ?>
            <?php $disabled = ($row["STATUS_CHECKBOX"] == 'si' && !$p_sincuarenta_mod) ? 'disabled' : ''; ?>
            <input type="checkbox" style="width:30PX;" class="form-check-input"
                id="STATUS_CHECKBOX<?php echo $row["07COMPROBACIONid"]; ?>"
                name="STATUS_CHECKBOX<?php echo $row["07COMPROBACIONid"]; ?>"
                value="<?php echo $row["07COMPROBACIONid"]; ?>"
                onclick="STATUS_CHECKBOX(<?php echo $row['07COMPROBACIONid']; ?>, <?php echo $p_sincuarenta_mod ? 'true' : 'false'; ?>)"
                <?php if($row["STATUS_CHECKBOX"]=='si') echo "checked"; ?>
                <?php echo $disabled; ?>
            />
        <?php else: ?>
            <span style="color:#999;">CON XML </span>
        <?php endif; ?>
    </span>
</td>
<?php } ?>

<div id="ajax-notification" style="position:fixed; top:20px; right:20px; padding:15px; background:#4CAF50; color:white; border-radius:5px; display:none; z-index:1000;"></div>
<td >

<input type="button" name="view_bitacora" value="BITÁCORA" id="<?php echo $row['07COMPROBACIONid']; ?>" class="btn btn-outline-primary btn-xs view_dataPAGOPROVEEbitacora" />

</td>
<td>
<?php if($p_comvyo_mod){ ?>
<input type="button" name="view" value="MODIFICAR" id="<?php echo $row['07COMPROBACIONid']; ?>" class="btn btn-info btn-xs view_dataPAGOPROVEEmodifica" />
<?php } ?>
<?php  
$UUID_fila = isset($row['UUID']) ? trim($row['UUID']) : '';
$STATUS_VENTAS_fila = isset($row['STATUS_VENTAS']) ? trim($row['STATUS_VENTAS']) : '';

// NULL/vacío = siempre MODIFICAR; 'no' = depende del UUID
$esNulo = ($STATUS_VENTAS_fila === '');
$esNo   = ($STATUS_VENTAS_fila === 'no');

if ($p_subirfactura_ver) {
    if ($esNulo) { ?>
        <input type="button" name="view" value="MODIFICAR"
               id="<?php echo $row['07COMPROBACIONid']; ?>"
               class="btn btn-info btn-xs view_dataSUBIRCOM" />
    <?php } elseif ($esNo) {
        if ($UUID_fila != '') { ?>
            <input type="button" name="view" value="MODIFICAR"
                   id="<?php echo $row['07COMPROBACIONid']; ?>"
                   class="btn btn-info btn-xs view_dataSUBIRCOM" />
        <?php } else { ?>
            <input type="button" name="view" value="SUBIR FACTURA"
                   id="<?php echo $row['07COMPROBACIONid']; ?>"
                   class="btn btn-info btn-xs view_dataSUBIRCOM" />
        <?php }
    }
}
?>
</td>
<td>
<?php if($p_comvyo_borrar){ ?>
<input type="button" name="view2" value="BORRAR" id="<?php echo $row['07COMPROBACIONid']; ?>" class="btn btn-info btn-xs view_dataSBborrar" />
<?php } ?>
</td>			
</tr>

<?php $finales++; } ?>

<?php if($p_totales_ver){ ?>
<tr>
<?php if($totales == 'si'){ ?><td style="text-align:right; padding-right:45px;" colspan="<?php echo $colspan +1; ?>"><strong style="font-size:16px">TOTALES</strong></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"MONTO_FACTURA",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><strong style="font-size:16px">$<?php echo number_format($MONTO_FACTURA12,2,'.',','); ?></strong></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"IVA",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><strong style="font-size:16px">$<?php echo number_format($IVA12,2,'.',','); ?></strong></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"TImpuestosRetenidosIVA",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><strong style="font-size:16px">$<?php echo number_format($TImpuestosRetenidosIVA12,2,'.',','); ?></strong></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"TImpuestosRetenidosISR",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><strong style="font-size:16px">$<?php echo number_format($TImpuestosRetenidosISR12,2,'.',','); ?></strong></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"MONTO_PROPINA",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><strong style="font-size:16px">$<?php echo number_format($MONTO_PROPINA12,2,'.',','); ?></strong></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"IMPUESTO_HOSPEDAJE",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><strong style="font-size:16px">$<?php echo number_format($IMPUESTO_HOSPEDAJE12,2,'.',','); ?></strong></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"descuentos",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><strong style="font-size:16px">$<?php echo number_format($descuentos12,2,'.',','); ?></strong></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"MONTO_DEPOSITAR",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><strong style="font-size:16px">$<?php echo number_format($MONTO_DEPOSITAR12,2,'.',','); ?></strong></td><?php } ?>
</tr>
<?php } ?>

<?php if($p_totales_ver){ ?>
<tr>
<?php if($totales2 == 'si'){ ?><td style="text-align:right; padding-right:45px;" colspan="<?php echo $colspan2+2; ?>"><strong style="font-size:16px">TOTALES XML</strong></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"SUBTOTAL",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><strong style="font-size:16px">$<?php echo number_format($subTotal12,2,'.',','); ?></strong></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"propina",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><strong style="font-size:16px">$<?php echo number_format($propina12,2,'.',','); ?></strong></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"Descuento",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><strong style="font-size:16px">$<?php echo number_format($Descuento12,2,'.',','); ?></strong></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"TOTAL_IMPUESTOS_TRASLADADOS",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><strong style="font-size:16px">$<?php echo number_format($TImpuestosTrasladados12,2,'.',','); ?></strong></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"TOTAL_IMPUESTOS_RETENIDOS",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><strong style="font-size:16px">$<?php echo number_format($TImpuestosRetenidos12,2,'.',','); ?></strong></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"TUA",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><strong style="font-size:16px">$<?php echo number_format($TUA12,2,'.',','); ?></strong></td><?php } ?>
<?php if($database->plantilla_filtro($nombreTabla,"total",$altaeventos,$DEPARTAMENTO)=="si"){ ?><td style="text-align:center"><strong style="font-size:16px">$<?php echo number_format($totalf12,2,'.',','); ?></strong></td><?php } ?>
<td style="text-align:center"><strong style="font-size:16px" id="totalCalculado">$<?php echo number_format($PorfaltaDeFactura12,2,'.',','); ?></strong></td>
</tr>
<?php } ?>

		</tbody>
		</table>
		</div>
		<div class="clearfix">
			<?php 
				$inicios=$offset+1;
				$finales+=$inicios -1;
				echo '<div class="hint-text">Mostrando '.$inicios.' al '.$finales.' de '.$numrows.' registros</div>';
				echo $pagination->paginate();
			?>
        </div>
	<?php
	}
}
?>
