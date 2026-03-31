<?php



/*
clase EPC INNOVA
CREADO : 10/mayo/2023
fecha sandor:
feha fatima:  23/03/2023


*/
	define('__ROOT3__', dirname(dirname(__FILE__)));
	require __ROOT3__."/includes/class.epcinn.php";	
	
	
	class accesoclase extends colaboradores{

	private function inicializar_tablas_auxiliares() {
		$flagKey = '__tablas_comprobacion_inicializadas__';
		if (!empty($_SESSION[$flagKey])) {
			return;
		}

		$conn = $this->db();

		mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `07COMPROBACION_BITACORA` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`id_comprobacion` int(11) NOT NULL DEFAULT 0,
			`tipo_movimiento` varchar(50) NOT NULL,
			`detalle` text,
			`fecha_hora` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			`nombre_quien_ingreso` varchar(255) DEFAULT NULL,
			`nombre_quien_actualizo` varchar(255) DEFAULT NULL,
			PRIMARY KEY (`id`),
			KEY `idx_id_comprobacion` (`id_comprobacion`),
			KEY `idx_fecha_hora` (`fecha_hora`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

		mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `07COMPROBACION_RECHAZOS` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`id_comprobacion` int(11) NOT NULL,
			`motivo_rechazo` text,
			`usuario_registro` varchar(255) DEFAULT NULL,
			`fecha_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (`id`),
			UNIQUE KEY `uniq_comprobacion` (`id_comprobacion`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

		$_SESSION[$flagKey] = true;
	}

	private function nombre_usuario_bitacora() {
		foreach (array('NOMBREUSUARIO', 'nombreusuario', 'usuario') as $key) {
			if (!empty($_SESSION[$key])) { return $_SESSION[$key]; }
		}
		if (!empty($_SESSION['idem'])) { return 'ID:' . $_SESSION['idem']; }
		return 'SIN_USUARIO';
	}

	private function registrar_bitacora($conn, $idComprobacion, $tipoMovimiento, $detalle, $nombreQuienIngreso = '', $nombreQuienActualizo = '') {
		$this->inicializar_tablas_auxiliares();

		$idComprobacion = intval($idComprobacion);
		$tipoMovimiento = mysqli_real_escape_string($conn, $tipoMovimiento);
		$detalle = mysqli_real_escape_string($conn, $detalle);
		$nombreQuienIngreso = mysqli_real_escape_string($conn, $nombreQuienIngreso);
		$nombreQuienActualizo = mysqli_real_escape_string($conn, $nombreQuienActualizo);

		mysqli_query($conn, "INSERT INTO 07COMPROBACION_BITACORA
			(id_comprobacion, tipo_movimiento, detalle, fecha_hora, nombre_quien_ingreso, nombre_quien_actualizo)
			VALUES
			('".$idComprobacion."', '".$tipoMovimiento."', '".$detalle."', NOW(), '".$nombreQuienIngreso."', '".$nombreQuienActualizo."')");
	}

private function etiqueta_bitacora_campo($campo) {
    $etiquetas = array(
        'STATUS_RESPONSABLE_EVENTO' => 'ESTATUS RESPONSABLE DEL EVENTO',
        'STATUS_DE_PAGO'            => 'ESTATUS DE PAGO',
        'STATUS_AUDITORIA3'         => 'CHECK BOX VoBo CxP',
        'STATUS_CHECKBOX'           => 'SE QUITO EL 46% PERDIDA FISCAL',
        'STATUS_AUDITORIA2'         => 'AUTORIZACIÓN POR AUDITORÍA',
        'STATUS_RECHAZADO'          => 'PAGO RECHAZADO',
        'STATUS_FINANZAS'           => 'AUTORIZACIÓN POR DIRECCIÓN',
        'STATUS_VENTAS'             => 'AUTORIZACIÓN POR VENTAS',
        'MONTO_DEPOSITAR'           => 'TOTAL A PAGAR',
        'FECHA_A_DEPOSITAR'         => 'FECHA DE CARGO EN TDC',
        'FECHA_DE_PAGO'             => 'FECHA DE PROGRAMACIÓN DEL PAGO',
        'PFORMADE_PAGO'             => 'FORMA DE PAGO',
        'NUMERO_EVENTO'             => 'NÚMERO DE EVENTO',
        'NOMBRE_EVENTO'             => 'NOMBRE DEL EVENTO',
        'NOMBRE_COMERCIAL'          => 'NOMBRE COMERCIAL',
        'RAZON_SOCIAL'              => 'RAZÓN SOCIAL',
        'RFC_PROVEEDOR'             => 'RFC DEL PROVEEDOR',
        'MOTIVO_GASTO'              => 'MOTIVO DEL GASTO',
        'CONCEPTO_PROVEE'           => 'CONCEPTO DE LA FACTURA',
        'MONTO_TOTAL_COTIZACION_ADEUDO' => 'COTIZACIÓN',
        'MONTO_PROPINA'             => 'MONTO DE PROPINA O SERVICIO',
        'MONTO_FACTURA'             => 'SUB TOTAL',
        'TIPO_DE_MONEDA'            => 'TIPO DE MONEDA',
        'BANCO_ORIGEN'              => 'INSTITUCIÓN BANCARIA',
        'MONTO_DEPOSITADO'          => 'MONTO DEPOSITADO',
        'CLASIFICACION_GENERAL'     => 'CLASIFICACIÓN GENERAL',
        'CLASIFICACION_ESPECIFICA'  => 'CLASIFICACIÓN ESPECÍFICA',
        'MONTO_DE_COMISION'         => 'MONTO DE COMISIÓN',
        'POLIZA_NUMERO'             => 'NÚMERO DE PÓLIZA',
        'NOMBRE_DEL_EJECUTIVO'      => 'NOMBRE DEL EJECUTIVO QUE REALIZÓ LA COMPRA',
        'NOMBRE_DEL_AYUDO'          => 'NOMBRE DEL EJECUTIVO QUE INGRESÓ LA FACTURA',
        'OBSERVACIONES_1'           => 'OBSERVACIONES',
        'TIPO_CAMBIOP'              => 'TIPO DE CAMBIO',
        'TOTAL_ENPESOS'             => 'TOTAL EN PESOS',
        'IMPUESTO_HOSPEDAJE'        => 'IMPUESTO DE HOSPEDAJE',
        'TImpuestosRetenidosIVA'    => 'IVA RETENIDO',
        'TImpuestosRetenidosISR'    => 'ISR RETENIDO',
        'descuentos'                => 'DESCUENTOS',
        'IVA'                       => 'IVA',
        'ACTIVO_FIJO'               => 'ACTIVO FIJO',
        'GASTO_FIJO'                => 'GASTO FIJO',
        'PAGAR_CADA'                => 'PAGAR CADA',
        'FECHA_PPAGO'               => 'FECHA DE PROGRAMACIÓN DE PAGO',
        'FECHA_TPROGRAPAGO'         => 'FECHA DE TERMINACIÓN DE LA PROGRAMACIÓN',
        'EJECUTIVOTARJETA'          => 'EJECUTIVO TITULAR DE LA TARJETA',
        'NUMERO_EVENTOFIJO'         => 'NÚMERO DE EVENTO FIJO',
        'SUB_GENERAL'               => 'SUB CLASIFICACIÓN GENERAL',
        'CLASI_GENERAL'             => 'CLASIFICACIÓN GENERAL',
    );
	

	return isset($etiquetas[$campo]) ? $etiquetas[$campo] : str_replace('_', ' ', $campo);
	}

	public function registrar_bitacora_adjuntos($idComprobacion, $tipoAdjunto, $nombreArchivo) {
		$idComprobacion = intval($idComprobacion);
		$tipoAdjunto = trim((string)$tipoAdjunto);
		$nombreArchivo = trim((string)$nombreArchivo);

		if ($idComprobacion <= 0 || $tipoAdjunto === '' || $nombreArchivo === '') {
			return false;
		}

		$conn = $this->db();
		$detalle = 'Se adjuntó archivo '.$tipoAdjunto.': "'.$nombreArchivo.'".';
		$this->registrar_bitacora($conn, $idComprobacion, 'ADJUNTO', $detalle, '', $this->nombre_usuario_bitacora());
		return true;
	}

	public function var_altaeventos(){
		$conn = $this->db();
		$variablequery = "select * from 04altaeventos where id = '".$_SESSION['idevento']."' ";
		$arrayquery = mysqli_query($conn,$variablequery);
		return $row = mysqli_fetch_array($arrayquery, MYSQLI_ASSOC);		
	}

public function tarjeta(){
    $conn = $this->db();

    $resultado = '';
    $idem = isset($_SESSION['idem']) ? $_SESSION['idem'] : '';

    if ($idem === '') {
        return $resultado;
    }

    $sql = "SELECT TBANCO
            FROM 01Tempresarial
            WHERE idRelacion = '".mysqli_real_escape_string($conn, $idem)."'
              AND TBANCO IS NOT NULL
              AND TRIM(TBANCO) <> ''
            ORDER BY id DESC
            LIMIT 1";

    if ($q = mysqli_query($conn, $sql)) {
        if ($row = mysqli_fetch_assoc($q)) {
            $resultado = trim($row['TBANCO']);
        }
    }

    return $resultado;
}




	public function buscarnumero($filtro){
		$conn = $this->db();
		$variable = "select * from 04altaeventos where NUMERO_EVENTO like '%".$filtro."%' ";
$variablequery = mysqli_query($conn,$variable);
		while($row = mysqli_fetch_array($variablequery, MYSQLI_ASSOC)){
			$resultado [] = $row['NUMERO_EVENTO'];
		}
		return $resultado;
		
	}

	public function buscarnumero2($filtro){
		$conn = $this->db();
		$variable = "select * from 04altaeventos where NUMERO_EVENTO like '%".$filtro."%' ";
$variablequery = mysqli_query($conn,$variable);
		while($row = mysqli_fetch_array($variablequery, MYSQLI_ASSOC)){
		
			$resultado[] = ['id'=>$row['NUMERO_EVENTO'],'text'=>$row['NUMERO_EVENTO']];
		}
		return $resultado;
		
	}

	public function ultimopago($filtro){
		$conn = $this->db();
		$variable = "select * from 07COMPROBACION where NUMERO_EVENTO = '".$filtro."' ";
		$resultado = 0;
		$variablequery = mysqli_query($conn,$variable);
		while($row = mysqli_fetch_array($variablequery, MYSQLI_ASSOC)){
			$resultado += $row['MONTO_DEPOSITADO'];
		}
		
		$resultado2 = 'sssssssssssssss';
		return $resultado;

	}


	public function buscarnombre($filtro){
		$conn = $this->db();
		$variable = "select * from 04altaeventos where NUMERO_EVENTO = '".$filtro."' ";
		$variablequery = mysqli_query($conn,$variable);
		while($row = mysqli_fetch_array($variablequery, MYSQLI_ASSOC)){
			$resultado=$row['NOMBRE_EVENTO'];
		}
		return $resultado;
		
	}
	

public function solocargartemp($archivo)
{
    $nombre_carpeta = __ROOT2__.'/includes/archivos';
    $nombretemp    = $_FILES[$archivo]["tmp_name"];
    $nombrearchivo = basename($_FILES[$archivo]["name"]);
    $extension     = explode('.', $nombrearchivo);
    $cuenta        = count($extension) - 1;
    $ext           = strtolower($extension[$cuenta]);

    $extensionesPermitidas = array('pdf','gif','jpeg','jpg','png','mp4','docx','doc','xml');
    if(!in_array($ext, $extensionesPermitidas)){
        return "2";
    }

    // ✅ Nombre único para evitar sobreescribir archivos de otros registros
    $nombrebase  = pathinfo($nombrearchivo, PATHINFO_FILENAME);
    $nuevonombre = $nombrebase . '_' . uniqid() . '.' . $ext;

    if(move_uploaded_file($nombretemp, $nombre_carpeta.'/'.$nuevonombre)){
        chmod($nombre_carpeta.'/'.$nuevonombre, 0755);
        return trim($nuevonombre);
    }
    return "1";
}



	public function variable_DIRECCIONP1(){
		$conn = $this->db();
		$variablequery = "select * from 02direccionproveedor1 where idRelacion = '".$_SESSION['idCG']."' ";
		$arrayquery = mysqli_query($conn,$variablequery);
		return $row = mysqli_fetch_array($arrayquery, MYSQLI_ASSOC);		
	}


	public function variable_SUBETUFACTURA(){
		$conn = $this->db();
		$variablequery = "select * from 07COMPROBACIONDOCT where idRelacion = '".$_SESSION['idCG']."' and idTemporal = 'si' and (ADJUNTAR_FACTURA_XML is not null or ADJUNTAR_FACTURA_XML <> '') order by id desc ";
		$arrayquery = mysqli_query($conn,$variablequery);
		return $row = mysqli_fetch_array($arrayquery, MYSQLI_ASSOC);		
	}

public function variable_SUBETUFACTURA2($id12){
    $conn = $this->db();
    $id12 = mysqli_real_escape_string($conn, (string)$id12);

    $idCGsesion = isset($_SESSION['idCG']) ? mysqli_real_escape_string($conn, $_SESSION['idCG']) : '';
    
        // Usar $id12 cuando esté disponible; la sesión solo como fallback
    $whereIdCG = ($id12 != '' && $id12 != '0') ? " AND idRelacion = '".$id12."' " : (($idCGsesion != '') ? " AND idRelacion = '".$idCGsesion."' " : '');

    
    $variablequery = "SELECT * FROM 07COMPROBACIONDOCT 
                      WHERE idTemporal = 'si' 
                      ".$whereIdCG."
                      AND (ADJUNTAR_FACTURA_XML IS NOT NULL AND ADJUNTAR_FACTURA_XML <> '') 
                      ORDER BY id DESC";
    $arrayquery = mysqli_query($conn, $variablequery);
    return $row = mysqli_fetch_array($arrayquery, MYSQLI_ASSOC);
}

	public function revisar_pagoproveedor(){
		$conn = $this->db();
		echo $var1 = 'select id from 07COMPROBACION where idRelacion =  "'.$_SESSION['idCG'].'" ';
		$query = mysqli_query($conn,$var1) or die('P44'.mysqli_error($conn));
		$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
		return $row['id'];
	}

public function revisar_pagoproveedor2($id){
		$conn = $this->db();
		$var1 = 'select id from 07COMPROBACION where id =  "'.$id.'" ';
		$query = mysqli_query($conn,$var1) or die('P44'.mysqli_error($conn));
		$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
		return $row['id'];
	}

	public function xml_factura_guardado($idComprobacion, $idUsuario){
		$conn = $this->db();
		$idComprobacion = mysqli_real_escape_string($conn, (string)$idComprobacion);
		$idUsuario = mysqli_real_escape_string($conn, (string)$idUsuario);

		$var = "SELECT ADJUNTAR_FACTURA_XML
		FROM 07COMPROBACIONDOCT
		WHERE
			(
				(idRelacion = '".$idComprobacion."' AND (idTemporal = '".$idComprobacion."' OR idTemporal = '' OR idTemporal IS NULL))
				OR
				(idRelacion = '".$idUsuario."' AND idTemporal = 'si')
			)
			AND ADJUNTAR_FACTURA_XML IS NOT NULL
			AND ADJUNTAR_FACTURA_XML <> ''
		ORDER BY id DESC
		LIMIT 1";

		$query = mysqli_query($conn, $var);
		$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
		$nombreXml = isset($row['ADJUNTAR_FACTURA_XML']) ? trim((string)$row['ADJUNTAR_FACTURA_XML']) : '';

		if($nombreXml == ''){
			return '';
		}

		$rutaXml = __ROOT3__.'/includes/archivos/'.$nombreXml;
		if(!file_exists($rutaXml)){
			return '';
		}

		return $nombreXml;
	}
	


	public function busca_07XML($ultimo_id){
	$conn = $this->db();		
	$variablequery = "select * from 07XML where ultimo_id = '".$ultimo_id."' "; 
	$arrayquery = mysqli_query($conn,$variablequery);
	return $row = mysqli_fetch_array($arrayquery, MYSQLI_ASSOC);
	}

	public function busca_07XML2($ultimo_id,$tabla){
	$conn = $this->db();		
	$variablequery = "select * from ".$tabla." where ultimo_id = '".$ultimo_id."' "; 
	$arrayquery = mysqli_query($conn,$variablequery);
	return $row = mysqli_fetch_array($arrayquery, MYSQLI_ASSOC);
	}

public function ActualizaxmlDB($FechaTimbrado, $tipoDeComprobante, 
		$metodoDePago, $formaDePago, $condicionesDePago, $subTotal, 
		$TipoCambio, $Moneda, $total, $serie, 
		$folio, $LugarExpedicion, $rfcE, $nombreE, 
		$regimenE, $rfcR, $nombreR, $UsoCFDI, 
		$DomicilioFiscalReceptor, $RegimenFiscalReceptor, $UUID, $TImpuestosRetenidos, 
		$TImpuestosTrasladados, $session, $ultimo_id, $TuaTotalCargos, $TUA, $Descuento, $Propina, $conn,  $actualiza){
	$valores_xml = array(
		'FechaTimbrado', 'tipoDeComprobante', 'metodoDePago', 'formaDePago',
		'condicionesDePago', 'subTotal', 'TipoCambio', 'Moneda', 'total',
		'serie', 'folio', 'LugarExpedicion', 'rfcE', 'nombreE', 'regimenE',
		'rfcR', 'nombreR', 'UsoCFDI', 'DomicilioFiscalReceptor',
		'RegimenFiscalReceptor', 'UUID', 'TImpuestosRetenidos',
		'TImpuestosTrasladados', 'session', 'ultimo_id', 'TuaTotalCargos',
		'TUA', 'Descuento', 'Propina'
	);
	foreach($valores_xml as $campo_xml){
		$$campo_xml = mysqli_real_escape_string($conn, (string)$$campo_xml);
	}

	$var3 = "update `07XML` set 
	`Version` = 'no', 
	`fechaTimbrado` = '".$FechaTimbrado."', 
	`tipoDeComprobante` = '".$tipoDeComprobante."', 
	`metodoDePago` = '".$metodoDePago."', 
	`formaDePago` = '".$formaDePago."', 
	`condicionesDePago` = '".$condicionesDePago."', 
	`subTotal` = '".$subTotal."', 
	`TipoCambio` = '".$TipoCambio."', 
	`Moneda` = '".$Moneda."', 
	`total` = '".$total."', 
	`serie` = '".$serie."', 
	`folio` = '".$folio."', 
	`LugarExpedicion` = '".$LugarExpedicion."', 
	`rfcE` = '".$rfcE."', 
	`nombreE` = '".$nombreE."', 
	`regimenE` = '".$regimenE."', 
	`rfcR` = '".$rfcR."', 
	`nombreR` = '".$nombreR."', 
	`UsoCFDI` = '".$UsoCFDI."', 
	`DomicilioFiscalReceptor` = '".$DomicilioFiscalReceptor."', 
	`RegimenFiscalReceptor` = '".$RegimenFiscalReceptor."', 
	`UUID` = '".$UUID."',
	`TuaTotalCargos` = '".$TuaTotalCargos."',
	`TUA` = '".$TUA."',	
	`Propina` = '".$Propina."',	
	`Descuento` = '".$Descuento."',	
	`TImpuestosRetenidos` = '".$TImpuestosRetenidos."', 
	`TImpuestosTrasladados` = '".$TImpuestosTrasladados."' 
	where
	`ultimo_id` = '".$ultimo_id."';  ";

	$var4 = "INSERT INTO `07XML` (
	`id`, `Version`, `fechaTimbrado`, `tipoDeComprobante`, 
	`metodoDePago`, `formaDePago`, `condicionesDePago`, `subTotal`, 
	`TipoCambio`, `Moneda`, `total`, `serie`, 
	`folio`, `LugarExpedicion`, `rfcE`, `nombreE`, 
	`regimenE`, `rfcR`, `nombreR`, `UsoCFDI`, 
	`DomicilioFiscalReceptor`, `RegimenFiscalReceptor`, `UUID`, `TImpuestosRetenidos`, 
	`TImpuestosTrasladados`, `idRelacion`, `ultimo_id`, `TuaTotalCargos`,Descuento, `TUA`, `Propina`) VALUES (
	'', 'no', '".$FechaTimbrado."', '".$tipoDeComprobante."', 
	'".$metodoDePago."', '".$formaDePago."', '".$condicionesDePago."', '".$subTotal."', 
	'".$TipoCambio."', '".$Moneda."', '".$total."', '".$serie."', 
	'".$folio."', '".$LugarExpedicion."', '".$rfcE."', '".$nombreE."', 
	'".$regimenE."', '".$rfcR."', '".$nombreR."', '".$UsoCFDI."', 
	'".$DomicilioFiscalReceptor."', '".$RegimenFiscalReceptor."', '".$UUID."', '".$TImpuestosRetenidos."', 
	'".$TImpuestosTrasladados."', '".$session."', '".$ultimo_id."', '".$TuaTotalCargos."', '".$Descuento."', '".$TUA."', '".$Propina."'
	);  ";

$row = $this->busca_07XML($ultimo_id);
if($actualiza=='true'){
if($row['ultimo_id']==0 or $row['ultimo_id']==''){
	mysqli_query($conn,$var4) or die('P350'.mysqli_error($conn));
}else{
	mysqli_query($conn,$var3) or die('P352'.mysqli_error($conn));
}
}
		
	}



	public function guardarxmlDB2($ultimo_id,$session,$tabla, $url){
	$conn = $this->db();
	$conexion2 = new herramientas();   
	
		if( file_exists($url) ){
		$regreso = $conexion2->lectorxml($url);
		
		$Version = $regreso['Version'];
		$sello = $regreso['selo'];
		$Certificado = $regreso['Certificado'];
		$noCertificado = $regreso['noCertificado'];
		$fecha = $regreso['fecha'];
		$tipoDeComprobante = $regreso['tipoDeComprobante'];
		$metodoDePago = $regreso['metodoDePago'];
		$formaDePago = $regreso['formaDePago'];
		$condicionesDePago = $regreso['condicionesDePago'];
		$subTotal = $regreso['subTotal'];
		$TipoCambio = $regreso['TipoCambio'];
		$Moneda = $regreso['Moneda'];
		$Descuento = $regreso['Descuento'];
		$total = $regreso['total'];
		$serie = $regreso['serie'];
		$folio = $regreso['folio'];
		$LugarExpedicion = $regreso['LugarExpedicion'];
		$DescripcionConcepto = $regreso['DescripcionConcepto'];
		
		$rfcE = $regreso['rfcE'];					
		$nombreE = $regreso['nombreE'];	
		$regimenE = $regreso['regimenE'];
		
		$rfcR = $regreso['rfcR'];
		$nombreR = $regreso['nombreR'];
		$UsoCFDI = $regreso['UsoCFDI'];
		$DomicilioFiscalReceptor = $regreso['DomicilioFiscalReceptor'];
		$RegimenFiscalReceptor = $regreso['RegimenFiscalReceptor'];
		
		$UUID = $regreso['UUID'];
		$selloCFD = $regreso['selloCFD'];
		$noCertificadoSAT = $regreso['noCertificadoSAT'];	
		$FechaTimbrado = $regreso['FechaTimbrado'];
		$RfcProvCertif = $regreso['RfcProvCertif'];	
		$TImpuestosRetenidos = $regreso['TImpuestosRetenidos'];
		$TImpuestosTrasladados = $regreso['TImpuestosTrasladados'];

		$Cantidad = $regreso['Cantidad'];
		$ValorUnitario = $regreso['ValorUnitario'];
		$Importe = $regreso['Importe'];
		$ClaveProdServ = $regreso['ClaveProdServ'];
		$Unidad = $regreso['Unidad'];
		$Descripcion = $regreso['Descripcion'];
		$ClaveUnidad = $regreso['ClaveUnidad'];
			$NoIdentificacion = $regreso['NoIdentificacion'];
			$ObjetoImp = $regreso['ObjetoImp'];

			$valores_xml = array(
				'Version', 'FechaTimbrado', 'tipoDeComprobante', 'metodoDePago',
				'formaDePago', 'condicionesDePago', 'subTotal', 'TipoCambio',
				'Moneda', 'total', 'serie', 'folio', 'LugarExpedicion', 'rfcE',
				'nombreE', 'regimenE', 'rfcR', 'nombreR', 'UsoCFDI',
				'DomicilioFiscalReceptor', 'RegimenFiscalReceptor', 'UUID',
				'TImpuestosRetenidos', 'TImpuestosTrasladados', 'TuaTotalCargos',
				'Descuento', 'TUA', 'Propina', 'Cantidad', 'ValorUnitario',
				'Importe', 'ClaveProdServ', 'Unidad', 'Descripcion', 'ClaveUnidad',
				'NoIdentificacion', 'session', 'ultimo_id'
			);

			foreach($valores_xml as $campo_xml){
				$$campo_xml = mysqli_real_escape_string($conn, (string)$$campo_xml);
			}

			$var3 = "update ".$tabla." set 
		`Version` = '".$Version."', 
		`fechaTimbrado` = '".$FechaTimbrado."', 
		`tipoDeComprobante` = '".$tipoDeComprobante."', 
		`metodoDePago` = '".$metodoDePago."', 
		`formaDePago` = '".$formaDePago."', 
		`condicionesDePago` = '".$condicionesDePago."', 
		`subTotal` = '".$subTotal."', 
		`TipoCambio` = '".$TipoCambio."', 
		`Moneda` = '".$Moneda."', 
		`total` = '".$total."', 
		`serie` = '".$serie."', 
		`folio` = '".$folio."', 
		`LugarExpedicion` = '".$LugarExpedicion."', 
		`rfcE` = '".$rfcE."', 
		`nombreE` = '".$nombreE."', 
		`regimenE` = '".$regimenE."', 
		`rfcR` = '".$rfcR."', 
		`nombreR` = '".$nombreR."', 
		`UsoCFDI` = '".$UsoCFDI."', 
		`DomicilioFiscalReceptor` = '".$DomicilioFiscalReceptor."', 
		`RegimenFiscalReceptor` = '".$RegimenFiscalReceptor."', 
		`UUID` = '".$UUID."',
		`TuaTotalCargos` = '".$TuaTotalCargos."', /*aaa*/
		`TUA` = '".$TUA."',	
		`Propina` = '".$Propina."',	
		`Descuento` = '".$Descuento."',
		
		Cantidad = '".$Cantidad."',
		ValorUnitarioConcepto = '".$ValorUnitario."',
		ImporteConcepto = '".$Importe."',
		ClaveProdServ = '".$ClaveProdServ."',
		UnidadConcepto = '".$Unidad."',
		DescripcionConcepto = '".$Descripcion."',
		ClaveUnidad = '".$ClaveUnidad."',
		NoIdentificacionConcepto = '".$NoIdentificacion."',

		`TImpuestosRetenidos` = '".$TImpuestosRetenidos."', 
		`TImpuestosTrasladados` = '".$TImpuestosTrasladados."' 
		where
		`ultimo_id` = '".$ultimo_id."';  ";

		$var4 = "INSERT INTO ".$tabla." (
		`id`, `Version`, `fechaTimbrado`, `tipoDeComprobante`, 
		`metodoDePago`, `formaDePago`, `condicionesDePago`, `subTotal`, 
		`TipoCambio`, `Moneda`, `total`, `serie`, 
		`folio`, `LugarExpedicion`, `rfcE`, `nombreE`, 
		`regimenE`, `rfcR`, `nombreR`, `UsoCFDI`, 
		`DomicilioFiscalReceptor`, `RegimenFiscalReceptor`, `UUID`, `TImpuestosRetenidos`, 
		`TImpuestosTrasladados`, `idRelacion`, `ultimo_id`, `TuaTotalCargos`,Descuento, `TUA`, `Propina`, 
		
		
		Cantidad , ValorUnitarioConcepto, ImporteConcepto, ClaveProdServ, UnidadConcepto, DescripcionConcepto, ClaveUnidad, NoIdentificacionConcepto 
		
		
		
		) VALUES (
		'', '".$Version."', '".$FechaTimbrado."', '".$tipoDeComprobante."', 
		'".$metodoDePago."', '".$formaDePago."', '".$condicionesDePago."', '".$subTotal."', 
		'".$TipoCambio."', '".$Moneda."', '".$total."', '".$serie."', 
		'".$folio."', '".$LugarExpedicion."', '".$rfcE."', '".$nombreE."', 
		'".$regimenE."', '".$rfcR."', '".$nombreR."', '".$UsoCFDI."', 
		'".$DomicilioFiscalReceptor."', '".$RegimenFiscalReceptor."', '".$UUID."', '".$TImpuestosRetenidos."', 
		'".$TImpuestosTrasladados."', '".$session."', '".$ultimo_id."', '".$TuaTotalCargos."', 
		'".$Descuento."', '".$TUA."', '".$Propina."',
		'".$Cantidad."', '".$ValorUnitario."', '".$Importe."', '".$ClaveProdServ."', '".$Unidad."', '".$Descripcion."', '".$ClaveUnidad."', '".$NoIdentificacion."'
		
		
		);  ";
//print_r($regreso);
			$row = $this->busca_07XML2($ultimo_id,$tabla);
			//if($actualiza=='true'){
				if($row['ultimo_id']==0 or $row['ultimo_id']==''){
					mysqli_query($conn,$var4) or die('P350'.mysqli_error($conn));
					echo "Ingresado";					
				}else{
					mysqli_query($conn,$var3) or die('P352'.mysqli_error($conn));
					echo "Actualizado";
				}
			//}	
		}
	}

	public function listado3(){
		$conn = $this->db();

		$var = 'select *,02usuarios.id AS IDDD from 02usuarios left join 02direccionproveedor1 on 02usuarios.id = 02direccionproveedor1.idRelacion order by nommbrerazon asc';		
		RETURN $query = mysqli_query($conn,$var);

		
	}
	
	public function verificar_rfc($conn,$RFC_PROVEEDOR){
		 $queryrfc = "SELECT * FROM 02direccionproveedor1 WHERE P_RFC_MTDP = '".$RFC_PROVEEDOR."' ";
		$arrayquery = mysqli_query($conn,$queryrfc);
		$row = mysqli_fetch_array($arrayquery, MYSQLI_ASSOC);
		return $row['id'];
	}

      public function verificar_usuario($conn,$nommbrerazon){
                $queryrfc = "SELECT * FROM 02direccionproveedor1 WHERE P_NOMBRE_FISCAL_RS_EMPRESA = '".$nommbrerazon."' ";
                $arrayquery = mysqli_query($conn,$queryrfc);
                $row = mysqli_fetch_array($arrayquery, MYSQLI_ASSOC);
                return $row['id'];
        }



        public function verificar_usuario_comercial($conn,$nommbrerazon){
                $queryrfc = "SELECT * FROM 02direccionproveedor1 WHERE P_NOMBRE_COMERCIAL_EMPRESA = '".$nommbrerazon."' ";
                $arrayquery = mysqli_query($conn,$queryrfc);
                $row = mysqli_fetch_array($arrayquery, MYSQLI_ASSOC);
                return $row['id'];
        }

public function ingresar_usuario($conn,$nommbrerazon){
		$nommbrerazon = mysqli_real_escape_string($conn, $nommbrerazon);
		 $queryrfc = "insert into 02direccionproveedor1 (P_NOMBRE_FISCAL_RS_EMPRESA) values ('".$nommbrerazon."'); ";
		$arrayquery = mysqli_query($conn,$queryrfc) or die('P160'.mysqli_error($conn));
		RETURN $idwebc = mysqli_insert_id($conn);
	}			

	public function ingresar_rfc($conn,$RFC_PROVEEDOR,$idwebc){
		 $queryrfc = "UPDATE 02direccionproveedor1
		SET P_RFC_MTDP = '".$RFC_PROVEEDOR."', idRelacion = '".$idwebc."' WHERE id = '".$idwebc."' ";
		$arrayquery = mysqli_query($conn,$queryrfc);
		return $row = mysqli_fetch_array($arrayquery, MYSQLI_ASSOC);
	}



	
public function PAGOPRO ($NUMERO_CONSECUTIVO_PROVEE , $NOMBRE_COMERCIAL , $RAZON_SOCIAL , $RFC_PROVEEDOR , $NUMERO_EVENTO ,$NOMBRE_EVENTO, $MOTIVO_GASTO , $CONCEPTO_PROVEE , $MONTO_TOTAL_COTIZACION_ADEUDO , $MONTO_DEPOSITAR , $MONTO_PROPINA , $FECHA_AUTORIZACION_RESPONSABLE , $FECHA_AUTORIZACION_AUDITORIA , $FECHA_DE_LLENADO , $MONTO_FACTURA , $TIPO_DE_MONEDA , $PFORMADE_PAGO,$FECHA_DE_PAGO , $FECHA_A_DEPOSITAR , $STATUS_DE_PAGO ,$ACTIVO_FIJO, $GASTO_FIJO,$PAGAR_CADA,$FECHA_PPAGO,$FECHA_TPROGRAPAGO,$NUMERO_EVENTOFIJO,$CLASI_GENERAL,$SUB_GENERAL,$BANCO_ORIGEN , $MONTO_DEPOSITADO , $CLASIFICACION_GENERAL , $CLASIFICACION_ESPECIFICA , $PLACAS_VEHICULO , $MONTO_DE_COMISION , $POLIZA_NUMERO , $EJECUTIVOTARJETA,$NOMBRE_DEL_EJECUTIVO , $NOMBRE_DEL_AYUDO,$OBSERVACIONES_1, $TIPO_CAMBIOP,  $TOTAL_ENPESOS,$IMPUESTO_HOSPEDAJE,$IVA,$TImpuestosRetenidosIVA,$TImpuestosRetenidosISR,$descuentos, $ENVIARPAGOprovee,$hiddenpagoproveedores,$IPpagoprovee,
	$FechaTimbrado, $tipoDeComprobante, 
		$metodoDePago, $formaDePago, $condicionesDePago, $subTotal, 
		$TipoCambio, $Moneda, $total, $serie, 
		$folio, $LugarExpedicion, $rfcE, $nombreE, 
		$regimenE, $rfcR, $nombreR, $UsoCFDI, 
		$DomicilioFiscalReceptor, $RegimenFiscalReceptor, $UUID, $TImpuestosRetenidos, 
		$TImpuestosTrasladados, $TuaTotalCargos, $Descuento,$Propina, $TUA,$actualiza,$DescripcionConcepto,$Cantidad,$ClaveUnidad,$ClaveProdServ)
{
		$conn = $this->db();
//IPpagoprovee
		$MONTO_TOTAL_COTIZACION_ADEUDO = str_replace(',','',$MONTO_TOTAL_COTIZACION_ADEUDO);
		$MONTO_DEPOSITAR = str_replace(',','',$MONTO_DEPOSITAR);
		$MONTO_FACTURA = str_replace(',','',$MONTO_FACTURA);		
		$MONTO_PROPINA = str_replace(',','',$MONTO_PROPINA);
		$MONTO_DEPOSITADO = str_replace(',','',$MONTO_DEPOSITADO);
		$MONTO_DE_COMISION = str_replace(',','',$MONTO_DE_COMISION);
		$PENDIENTE_PAGO = str_replace(',','',$PENDIENTE_PAGO);	
		$PENDIENTE_PAGO = str_replace(',','',$PENDIENTE_PAGO);	
	    $TOTAL_ENPESOS = str_replace(',','',$TOTAL_ENPESOS);		
		$TIPO_CAMBIOP = str_replace(',','',$TIPO_CAMBIOP);		
		$IVA = str_replace(',','',$IVA);		
	$TImpuestosRetenidosIVA = str_replace(',','',$TImpuestosRetenidosIVA);		
		$TImpuestosRetenidosISR = str_replace(',','',$TImpuestosRetenidosISR);		
		$descuentos = str_replace(',','',$descuentos);		

		$escapedFields = array(
			'NOMBRE_COMERCIAL', 'RAZON_SOCIAL', 'RFC_PROVEEDOR', 'NOMBRE_EVENTO', 'MOTIVO_GASTO',
			'CONCEPTO_PROVEE', 'TIPO_DE_MONEDA', 'PFORMADE_PAGO', 'STATUS_DE_PAGO', 'ACTIVO_FIJO',
			'GASTO_FIJO', 'PAGAR_CADA', 'CLASI_GENERAL', 'SUB_GENERAL', 'BANCO_ORIGEN',
			'CLASIFICACION_GENERAL', 'CLASIFICACION_ESPECIFICA', 'PLACAS_VEHICULO', 'POLIZA_NUMERO',
			'EJECUTIVOTARJETA', 'NOMBRE_DEL_EJECUTIVO', 'NOMBRE_DEL_AYUDO', 'OBSERVACIONES_1',
			'hiddenpagoproveedores'
		);

		foreach ($escapedFields as $escapedField) {
			$$escapedField = mysqli_real_escape_string($conn, $$escapedField);
		}

		$NOMBRE_COMERCIALvar = "SELECT * FROM `02direccionproveedor1` where idRelacion = '".$NOMBRE_COMERCIAL."' ";
		$query_NOMBRE_COMERCIAL = mysqli_query($conn,$NOMBRE_COMERCIALvar) or die('P160'.mysqli_error($conn));
		$row_NOMBRE_COMERCIAL = mysqli_fetch_array($query_NOMBRE_COMERCIAL, MYSQLI_ASSOC);
		$NOMBRE_COMERCIAL2 = $row_NOMBRE_COMERCIAL['P_NOMBRE_COMERCIAL_EMPRESA'];
		
		/*if( $this->verificar_rfc($conn,$RFC_PROVEEDOR) ==''){
			$idwebc = $this->ingresar_usuario($conn,$RAZON_SOCIAL);
			$this->ingresar_rfc($conn,$RFC_PROVEEDOR,$idwebc);
			$this->ingresar_02direccionproveedor1($conn,$idwebc);
		}*/
		
		if( $this->verificar_rfc($conn,$RFC_PROVEEDOR)!=''){
			$session = $this->verificar_rfc($conn,$RFC_PROVEEDOR);
		}elseif($this->verificar_usuario_comercial($conn,$NOMBRE_COMERCIAL2)!=''){
			$session = $this->verificar_usuario_comercial($conn,$NOMBRE_COMERCIAL2);		
		}else{$session = 1;}
		
		$existe = $this->revisar_pagoproveedor2($IPpagoprovee);		
		$usuarioBitacora = $this->nombre_usuario_bitacora();
		$valoresPreviosBitacora = array();
		if(intval($IPpagoprovee) > 0){
			$queryPrevio = mysqli_query($conn, "SELECT NUMERO_EVENTO,NOMBRE_EVENTO,RAZON_SOCIAL,RFC_PROVEEDOR,MONTO_DEPOSITAR,PFORMADE_PAGO,FECHA_A_DEPOSITAR,STATUS_DE_PAGO FROM 07COMPROBACION WHERE id = '".intval($IPpagoprovee)."' LIMIT 1");
			if($queryPrevio){
				$valoresPreviosBitacora = mysqli_fetch_array($queryPrevio, MYSQLI_ASSOC);
			}
		}		



		//$existe2 = $this->revisar_pagoproveedor2($IPpagoprovee);		
		//$existe = $this->revisar_pagoproveedor();		
		//$session = isset($_SESSION['idPROV'])?$_SESSION['idPROV']:$idwebc;		
		     

		
		if($session != ''){
			//ADJUNTAR_FACTURA_XML FECHA_DE_LLENADO
		$var1 = "update 07COMPROBACION set
		NUMERO_CONSECUTIVO_PROVEE = '".$NUMERO_CONSECUTIVO_PROVEE."' , NOMBRE_COMERCIAL = '".$NOMBRE_COMERCIAL."' , RAZON_SOCIAL = '".$RAZON_SOCIAL."' , RFC_PROVEEDOR = '".$RFC_PROVEEDOR."' , NUMERO_EVENTO = '".$NUMERO_EVENTO."' , NOMBRE_EVENTO = '".$NOMBRE_EVENTO."' , MOTIVO_GASTO = '".$MOTIVO_GASTO."' , CONCEPTO_PROVEE = '".$CONCEPTO_PROVEE."' , MONTO_TOTAL_COTIZACION_ADEUDO = '".$MONTO_TOTAL_COTIZACION_ADEUDO."' , MONTO_DEPOSITAR = '".$MONTO_DEPOSITAR."' , MONTO_PROPINA = '".$MONTO_PROPINA."' , FECHA_AUTORIZACION_RESPONSABLE = '".$FECHA_AUTORIZACION_RESPONSABLE."' , FECHA_AUTORIZACION_AUDITORIA = '".$FECHA_AUTORIZACION_AUDITORIA."' , MONTO_FACTURA = '".$MONTO_FACTURA."' , TIPO_DE_MONEDA = '".$TIPO_DE_MONEDA."' , PFORMADE_PAGO = '".$PFORMADE_PAGO."' , FECHA_DE_PAGO = '".$FECHA_DE_PAGO."' , FECHA_A_DEPOSITAR = '".$FECHA_A_DEPOSITAR."' , STATUS_DE_PAGO = '".$STATUS_DE_PAGO."' , ACTIVO_FIJO = '".$ACTIVO_FIJO."' , GASTO_FIJO = '".$GASTO_FIJO."' , PAGAR_CADA = '".$PAGAR_CADA."' , FECHA_PPAGO = '".$FECHA_PPAGO."' , FECHA_TPROGRAPAGO = '".$FECHA_TPROGRAPAGO."' , NUMERO_EVENTOFIJO = '".$NUMERO_EVENTOFIJO."' , CLASI_GENERAL = '".$CLASI_GENERAL."' , SUB_GENERAL = '".$SUB_GENERAL."' , BANCO_ORIGEN = '".$BANCO_ORIGEN."' , MONTO_DEPOSITADO = '".$MONTO_DEPOSITADO."' , CLASIFICACION_GENERAL = '".$CLASIFICACION_GENERAL."' , CLASIFICACION_ESPECIFICA = '".$CLASIFICACION_ESPECIFICA."' , PLACAS_VEHICULO = '".$PLACAS_VEHICULO."' , MONTO_DE_COMISION = '".$MONTO_DE_COMISION."' , POLIZA_NUMERO = '".$POLIZA_NUMERO."' , NOMBRE_DEL_EJECUTIVO = '".$NOMBRE_DEL_EJECUTIVO."' , NOMBRE_DEL_AYUDO = '".$NOMBRE_DEL_AYUDO."' , OBSERVACIONES_1 = '".$OBSERVACIONES_1."' , TIPO_CAMBIOP = '".$TIPO_CAMBIOP."' , TOTAL_ENPESOS = '".$TOTAL_ENPESOS."' , EJECUTIVOTARJETA = '".$EJECUTIVOTARJETA."' , IMPUESTO_HOSPEDAJE = '".$IMPUESTO_HOSPEDAJE."' , TImpuestosRetenidosIVA = '".$TImpuestosRetenidosIVA."' , TImpuestosRetenidosISR = '".$TImpuestosRetenidosISR."' , descuentos = '".$descuentos."' , IVA = '".$IVA."' where id = '".$existe."' ; ";
		
		
		$var2 = "insert into 07COMPROBACION ( 
		NUMERO_CONSECUTIVO_PROVEE, 
		NOMBRE_COMERCIAL, 
		RAZON_SOCIAL, 
		RFC_PROVEEDOR, 
		NUMERO_EVENTO,
		NOMBRE_EVENTO,
		MOTIVO_GASTO, 
		CONCEPTO_PROVEE, 
		MONTO_TOTAL_COTIZACION_ADEUDO, 
		MONTO_DEPOSITAR, 
		MONTO_PROPINA,
		FECHA_AUTORIZACION_RESPONSABLE,
		FECHA_AUTORIZACION_AUDITORIA, 
		FECHA_DE_LLENADO, 
		MONTO_FACTURA, 
		TIPO_DE_MONEDA, 
		PFORMADE_PAGO,
		FECHA_DE_PAGO, 
		FECHA_A_DEPOSITAR, 
		STATUS_DE_PAGO,
		ACTIVO_FIJO,
		GASTO_FIJO,
		PAGAR_CADA,
		FECHA_PPAGO,
		FECHA_TPROGRAPAGO,
		NUMERO_EVENTOFIJO,
		CLASI_GENERAL,
		SUB_GENERAL,		
		BANCO_ORIGEN, 
		MONTO_DEPOSITADO, 
		CLASIFICACION_GENERAL, 
		CLASIFICACION_ESPECIFICA, 
		PLACAS_VEHICULO, 
		MONTO_DE_COMISION, 
		POLIZA_NUMERO, 
		NOMBRE_DEL_EJECUTIVO, 
		NOMBRE_DEL_AYUDO, 
		OBSERVACIONES_1,
		TIPO_CAMBIOP,
		TOTAL_ENPESOS,
		EJECUTIVOTARJETA,
		IMPUESTO_HOSPEDAJE,		
		IVA,		
		TImpuestosRetenidosIVA,		
		TImpuestosRetenidosISR,		
		descuentos,		
		hiddenpagoproveedores, 
		idRelacion) values ( 
		'".$NUMERO_CONSECUTIVO_PROVEE."' , 
		'".$NOMBRE_COMERCIAL."' , 
		'".$RAZON_SOCIAL."' , 
		'".$RFC_PROVEEDOR."' , 
		'".$NUMERO_EVENTO."' , 
		'".$NOMBRE_EVENTO."' , 
		'".$MOTIVO_GASTO."' , 
		'".$CONCEPTO_PROVEE."' , 
		'".$MONTO_TOTAL_COTIZACION_ADEUDO."' , 
		'".$MONTO_DEPOSITAR."' , 
		'".$MONTO_PROPINA."' , 	
		'".$FECHA_AUTORIZACION_RESPONSABLE."' , 
		'".$FECHA_AUTORIZACION_AUDITORIA."' , 
		'".$FECHA_DE_LLENADO."' , 
		'".$MONTO_FACTURA."' , 
		'".$TIPO_DE_MONEDA."' , 
		'".$PFORMADE_PAGO."' , 
		'".$FECHA_DE_PAGO."' , 
		'".$FECHA_A_DEPOSITAR."' , 
		'".$STATUS_DE_PAGO."' , 		
		'".$ACTIVO_FIJO."' , 
		'".$GASTO_FIJO."' , 
		'".$PAGAR_CADA."' , 
		'".$FECHA_PPAGO."' , 
		'".$FECHA_TPROGRAPAGO."' , 
		'".$NUMERO_EVENTOFIJO."' , 
		'".$CLASI_GENERAL."' , 
		'".$SUB_GENERAL."' , 		
		'".$BANCO_ORIGEN."' , 
		'".$MONTO_DEPOSITADO."' ,
		'".$CLASIFICACION_GENERAL."' , 
		'".$CLASIFICACION_ESPECIFICA."' , 
		'".$PLACAS_VEHICULO."' , 
		'".$MONTO_DE_COMISION."' , 
		'".$POLIZA_NUMERO."' , 
		'".$NOMBRE_DEL_EJECUTIVO."' , 
		'".$NOMBRE_DEL_AYUDO."' , 
		'".$OBSERVACIONES_1."',
		'".$TIPO_CAMBIOP."',
		'".$TOTAL_ENPESOS."',
		'".$EJECUTIVOTARJETA."',
		'".$IMPUESTO_HOSPEDAJE."',
		'".$IVA."',
		'".$TImpuestosRetenidosIVA."',
		'".$TImpuestosRetenidosISR."',
		'".$descuentos."',
		'".$hiddenpagoproveedores."' ,
		'".$session."' );  ";			


if($ENVIARPAGOprovee=='ENVIARPAGOprovee'){

    // 1. PRIMERO obtener valores previos ANTES de cualquier UPDATE
    $queryPrevioCompleto = mysqli_query($conn, "SELECT * FROM 07COMPROBACION WHERE id = '".intval($IPpagoprovee)."' LIMIT 1");
    $valoresPreviosCompletos = array();
    if($queryPrevioCompleto){
        $valoresPreviosCompletos = mysqli_fetch_array($queryPrevioCompleto, MYSQLI_ASSOC);
    }

    // 2. Actualizar XML
    $this->ActualizaxmlDB($FechaTimbrado, $tipoDeComprobante, 
    $metodoDePago, $formaDePago, $condicionesDePago, $subTotal, 
    $TipoCambio, $Moneda, $total, $serie, 
    $folio, $LugarExpedicion, $rfcE, $nombreE, 
    $regimenE, $rfcR, $nombreR, $UsoCFDI, 
    $DomicilioFiscalReceptor, $RegimenFiscalReceptor, $UUID, $TImpuestosRetenidos, 
    $TImpuestosTrasladados, $session, $existe, $TuaTotalCargos, $TUA, $Descuento, $Propina, $conn, $actualiza);

    // 3. Ejecutar el UPDATE principal
    mysqli_query($conn,$var1) or die('P15622'.mysqli_error($conn));

    // 4. Detectar cambios y registrar bitácora
    $cambiosDetectados = array();
    $mapCampos = array(
        'NUMERO_EVENTO'          => $NUMERO_EVENTO,
        'NOMBRE_EVENTO'          => $NOMBRE_EVENTO,
        'RAZON_SOCIAL'           => $RAZON_SOCIAL,
        'RFC_PROVEEDOR'          => $RFC_PROVEEDOR,
        'MONTO_DEPOSITAR'        => $MONTO_DEPOSITAR,
        'PFORMADE_PAGO'          => $PFORMADE_PAGO,
        'FECHA_A_DEPOSITAR'      => $FECHA_A_DEPOSITAR,
        'STATUS_DE_PAGO'         => $STATUS_DE_PAGO,
        'NOMBRE_COMERCIAL'       => $NOMBRE_COMERCIAL,
        'MONTO_FACTURA'          => $MONTO_FACTURA,
        'IVA'                    => $IVA,
        'MONTO_PROPINA'          => $MONTO_PROPINA,
        'IMPUESTO_HOSPEDAJE'     => $IMPUESTO_HOSPEDAJE,
        'CONCEPTO_PROVEE'        => $CONCEPTO_PROVEE,
        'MOTIVO_GASTO'           => $MOTIVO_GASTO,
        'TIPO_DE_MONEDA'         => $TIPO_DE_MONEDA,
        'TIPO_CAMBIOP'           => $TIPO_CAMBIOP,
        'TOTAL_ENPESOS'          => $TOTAL_ENPESOS,
        'BANCO_ORIGEN'           => $BANCO_ORIGEN,
        'CLASI_GENERAL'          => $CLASI_GENERAL,
        'SUB_GENERAL'            => $SUB_GENERAL,
        'POLIZA_NUMERO'          => $POLIZA_NUMERO,
        'OBSERVACIONES_1'        => $OBSERVACIONES_1,
        'EJECUTIVOTARJETA'       => $EJECUTIVOTARJETA,
        'NOMBRE_DEL_EJECUTIVO'   => $NOMBRE_DEL_EJECUTIVO,
        'NOMBRE_DEL_AYUDO'       => $NOMBRE_DEL_AYUDO,
        'ACTIVO_FIJO'            => $ACTIVO_FIJO,
        'GASTO_FIJO'             => $GASTO_FIJO,
        'PAGAR_CADA'             => $PAGAR_CADA,
        'FECHA_PPAGO'            => $FECHA_PPAGO,
        'FECHA_TPROGRAPAGO'      => $FECHA_TPROGRAPAGO,
        'MONTO_DE_COMISION'      => $MONTO_DE_COMISION,
        'TImpuestosRetenidosIVA' => $TImpuestosRetenidosIVA,
        'TImpuestosRetenidosISR' => $TImpuestosRetenidosISR,
        'descuentos'             => $descuentos,
    );

    foreach($mapCampos as $campo => $valorNuevo){
        $valorViejo = isset($valoresPreviosCompletos[$campo]) ? trim((string)$valoresPreviosCompletos[$campo]) : '';
        $valorNuevoNorm = trim((string)$valorNuevo);
        if($valorViejo !== $valorNuevoNorm){
            $cambiosDetectados[] = $this->etiqueta_bitacora_campo($campo).': "'.$valorViejo.'" → "'.$valorNuevoNorm.'"';
        }
    }

    if(count($cambiosDetectados) > 0){
        $this->registrar_bitacora($conn, $IPpagoprovee, 'ACTUALIZACION', 'Se actualizó: '.implode(' | ', $cambiosDetectados), '', $usuarioBitacora);
    } else {
        $this->registrar_bitacora($conn, $IPpagoprovee, 'ACTUALIZACION', 'Se guardó la comprobación sin cambios detectados.', '', $usuarioBitacora);
    }

    return "Actualizado";
}

		
		else{
			//insert into
		mysqli_query($conn,$var2) or die('P16022'.mysqli_error($conn));
		$ultimo_id ='';		
		$ultimo_id = mysqli_insert_id($conn);

		$regresourl = $this->variable_SUBETUFACTURA2($session);
		$url = __ROOT3__.'/includes/archivos/'.$regresourl['ADJUNTAR_FACTURA_XML'];
		
		ob_start();
		$this->guardarxmlDB2($ultimo_id,$_SESSION['idCG'],'07XML',$url);
		ob_end_clean();
		
		$var3 = "UPDATE 07COMPROBACIONDOCT SET idTemporal ='".$ultimo_id."' where idRelacion = '".$_SESSION['idCG']."' and idTemporal ='si' "; 	
		
         mysqli_query($conn,$var3);	
		$this->registrar_bitacora($conn, $ultimo_id, 'INGRESO', 'Registro ingresado desde el módulo COMPROBACIÓN DE GASTOS.', $usuarioBitacora, '');
		return "Ingresado";
		}
		}
			
        }
    




	
		public function ACTUALIZA_RECHAZADO($idComprobacion, $estatusRechazado){

		$conn = $this->db();

		$session = isset($_SESSION['idem'])?$_SESSION['idem']:'';

		if($session != ''){

			$valorAnterior = $this->valor_actual_campo_comprobacion($conn, $idComprobacion, 'STATUS_RECHAZADO');
			$valorAnteriorStatusPago = $this->valor_actual_campo_comprobacion($conn, $idComprobacion, 'STATUS_DE_PAGO');

			$camposActualizar = "STATUS_RECHAZADO = '".$estatusRechazado."'";
            $camposActualizar = "STATUS_RECHAZADO = '".$estatusRechazado."'";
                 if($estatusRechazado === 'si'){
            $camposActualizar .= ", STATUS_DE_PAGO = 'RECHAZADO'";
             } elseif($estatusRechazado === 'no'){
            $camposActualizar .= ", STATUS_DE_PAGO = 'SOLICITADO'";
            }

			$var1 = "update 07COMPROBACION SET ".$camposActualizar." WHERE id = '".$idComprobacion."'";

	mysqli_query($conn,$var1) or die('P156'.mysqli_error($conn));


			$this->registrar_cambio_estado_detallado($conn, $idComprobacion, 'STATUS_RECHAZADO', $valorAnterior, $estatusRechazado);
			if($estatusRechazado === 'si' && $valorAnteriorStatusPago !== 'RECHAZADO'){
				$this->registrar_cambio_estado_detallado($conn, $idComprobacion, 'STATUS_DE_PAGO', $valorAnteriorStatusPago, 'RECHAZADO');
			}

			return "Actualizado^".$estatusRechazado;

		}else{

			echo "NO HAY UN PROVEEDOR SELECCIONADO";

		}

	}


	private function valor_actual_campo_comprobacion($conn, $idComprobacion, $campo){



		$camposPermitidos = array(
			'STATUS_RESPONSABLE_EVENTO', 'STATUS_DE_PAGO', 'STATUS_AUDITORIA3',
			'STATUS_CHECKBOX', 'STATUS_AUDITORIA2', 'STATUS_RECHAZADO',
			'STATUS_FINANZAS', 'STATUS_VENTAS'
		);

		if(!in_array($campo, $camposPermitidos, true)){

			return '';

		}



		$idSeguro = mysqli_real_escape_string($conn, $idComprobacion);

		$query = "SELECT ".$campo." AS valor FROM 07COMPROBACION WHERE id = '".$idSeguro."' LIMIT 1";

		$resultado = mysqli_query($conn, $query);

		if($resultado && ($row = mysqli_fetch_assoc($resultado))){

			return isset($row['valor']) ? $row['valor'] : '';

		}



		return '';

	}



	private function registrar_cambio_estado_detallado($conn, $idComprobacion, $campo, $valorAnterior, $valorNuevo, $descripcion = ''){
		$detalle = 'Se actualizó '.$this->etiqueta_bitacora_campo($campo).' de "'.$valorAnterior.'" a "'.$valorNuevo.'".';
		if($descripcion != ''){
			$detalle .= ' '.$descripcion;
		}
		$this->registrar_bitacora($conn, $idComprobacion, 'ACTUALIZACION', $detalle, '', $this->nombre_usuario_bitacora());
		return true;
	}

	private function crear_tabla_rechazos_si_no_existe($conn){


		$this->inicializar_tablas_auxiliares();



	}



public function guardar_motivo_rechazo($idComprobacion, $motivoRechazo){
    $conn = $this->db();
    $session = isset($_SESSION['idem'])?$_SESSION['idem']:'';
    if($session == ''){
        return "Sesion_invalida";
    }

    $idComprobacion = intval($idComprobacion);
    $motivoRechazo = trim($motivoRechazo);
    if($idComprobacion <= 0 || $motivoRechazo == ''){
        return "Datos_invalidos";
    }

    $this->crear_tabla_rechazos_si_no_existe($conn);
    $motivoEscapado = mysqli_real_escape_string($conn, $motivoRechazo);
     $usuarioEscapado = mysqli_real_escape_string($conn, $this->nombre_usuario_bitacora());

    $insert = "INSERT INTO 07COMPROBACION_RECHAZOS (id_comprobacion, motivo_rechazo, usuario_registro, fecha_registro)
    VALUES ('".$idComprobacion."', '".$motivoEscapado."', '".$usuarioEscapado."', NOW())
    ON DUPLICATE KEY UPDATE motivo_rechazo = VALUES(motivo_rechazo), usuario_registro = VALUES(usuario_registro), fecha_registro = NOW()";
    
    mysqli_query($conn, $insert) or die('P156'.mysqli_error($conn));
    $this->registrar_bitacora($conn, $idComprobacion, 'RECHAZO', 'Se registró motivo de rechazo: "'.$motivoRechazo.'".', '', $this->nombre_usuario_bitacora());
    return "ok";
}
	
	
	
	public function obtener_motivo_rechazo($idComprobacion){

		$conn = $this->db();

		$idComprobacion = intval($idComprobacion);

		if($idComprobacion <= 0){

			return '';

		}



		$this->crear_tabla_rechazos_si_no_existe($conn);

		$query = mysqli_query($conn, "SELECT motivo_rechazo FROM 07COMPROBACION_RECHAZOS WHERE id_comprobacion = '".$idComprobacion."' LIMIT 1");

		if($query){

			$row = mysqli_fetch_array($query, MYSQLI_ASSOC);

			if($row && isset($row['motivo_rechazo'])){

				return $row['motivo_rechazo'];

			}
		}
		return '';

	}
	
	

	public function ACTUALIZA_RESPONSABLE_EVENTO (
	$RESPONSABLE_EVENTO_id , $RESPONSABLE_text ){
	
		$conn = $this->db();
		$session = isset($_SESSION['idem'])?$_SESSION['idem']:'';    
		if($session != ''){
			/*if($pasarpagado_text=='si'){
				$STATUS_DE_PAGO = 'PAGADO';
			}else{
				$STATUS_DE_PAGO = 'SOLICITADO';				
			}*/
	$valorAnterior = $this->valor_actual_campo_comprobacion($conn, $RESPONSABLE_EVENTO_id, 'STATUS_RESPONSABLE_EVENTO');
		$var1 = "update 07COMPROBACION SET STATUS_RESPONSABLE_EVENTO = '".$RESPONSABLE_text."' WHERE id = '".$RESPONSABLE_EVENTO_id."'  ";	
	
		//if($pasarpagado_text=='si'){
		mysqli_query($conn,$var1) or die('P156'.mysqli_error($conn));
		$this->registrar_cambio_estado_detallado($conn, $RESPONSABLE_EVENTO_id, 'STATUS_RESPONSABLE_EVENTO', $valorAnterior, $RESPONSABLE_text);
		return "Actualizado^".$RESPONSABLE_text;
		//}
			
        }else{
		echo "NO HAY UN PROVEEDOR SELECCIONADO";	
		}
    }
	
	
	public function PASARPAGADOACTUALIZAR (
	$pasarpagado_id , $pasarpagado_text ){
	
		$conn = $this->db();
		$session = isset($_SESSION['idem'])?$_SESSION['idem']:'';    
		if($session != ''){
			if($pasarpagado_text=='si'){
				$STATUS_DE_PAGO = 'PAGADO';
			}else{
				$STATUS_DE_PAGO = 'SOLICITADO';				
			}
	$valorAnterior = $this->valor_actual_campo_comprobacion($conn, $pasarpagado_id, 'STATUS_DE_PAGO');
		$var1 = "update 07COMPROBACION SET STATUS_DE_PAGO = '".$STATUS_DE_PAGO."' WHERE id = '".$pasarpagado_id."'  ";	
	
		//if($pasarpagado_text=='si'){
		mysqli_query($conn,$var1) or die('P156'.mysqli_error($conn));
		$this->registrar_cambio_estado_detallado($conn, $pasarpagado_id, 'STATUS_DE_PAGO', $valorAnterior, $STATUS_DE_PAGO);
		return "Actualizado";
		//}
			
        }else{
		echo "NO HAY UN PROVEEDOR SELECCIONADO";	
		}
    }



	public function ACTUALIZA_AUDITORIA1 (
	$AUDITORIA1_id , $AUDITORIA1_text ){
	
		$conn = $this->db();
		$session = isset($_SESSION['idem'])?$_SESSION['idem']:'';    
		if($session != ''){
			if($AUDITORIA1_text=='si'){
				$STATUS_DE_PAGO = 'APROBADO';
			}else{
				$STATUS_DE_PAGO = 'SOLICITADO';				
			}
	$valorAnterior = $this->valor_actual_campo_comprobacion($conn, $AUDITORIA1_id, 'STATUS_DE_PAGO');
		$var1 = "update 07COMPROBACION SET STATUS_DE_PAGO = '".$STATUS_DE_PAGO."' WHERE id = '".$AUDITORIA1_id."'  ";	
	
		
		mysqli_query($conn,$var1) or die('P156'.mysqli_error($conn));
		$this->registrar_cambio_estado_detallado($conn, $AUDITORIA1_id, 'STATUS_DE_PAGO', $valorAnterior, $STATUS_DE_PAGO);
		return "Actualizado";
		
			
        }else{
		echo "NO HAY UN PROVEEDOR SELECCIONADO";	
		}
    }


	public function ACTUALIZA_AUDITORIA2 (
	$RESPONSABLE_EVENTO_id , $RESPONSABLE_text ){
	
		$conn = $this->db();
		$session = isset($_SESSION['idem'])?$_SESSION['idem']:'';    
		if($session != ''){
			/*if($pasarpagado_text=='si'){
				$STATUS_DE_PAGO = 'PAGADO';
			}else{
				$STATUS_DE_PAGO = 'SOLICITADO';				
			}*/
		 $valorAnterior = $this->valor_actual_campo_comprobacion($conn, $RESPONSABLE_EVENTO_id, 'STATUS_AUDITORIA2');
		 $var1 = "update 07COMPROBACION SET STATUS_AUDITORIA2 = '".$RESPONSABLE_text."' WHERE id = '".$RESPONSABLE_EVENTO_id."'  ";	
	
		//if($pasarpagado_text=='si'){
		mysqli_query($conn,$var1) or die('P156'.mysqli_error($conn));
		$this->registrar_cambio_estado_detallado($conn, $RESPONSABLE_EVENTO_id, 'STATUS_AUDITORIA2', $valorAnterior, $RESPONSABLE_text);
		return "Actualizado^".$RESPONSABLE_text;
		//}
			
        }else{
		echo "NO HAY UN PROVEEDOR SELECCIONADO";	
		}
    }
	public function ACTUALIZA_AUDITORIA3 (
	$AUDITORIA3_id , $AUDITORIA3_text ){
	
		$conn = $this->db();
		$session = isset($_SESSION['idem'])?$_SESSION['idem']:'';    
		if($session != ''){
		
	 $valorAnterior = $this->valor_actual_campo_comprobacion($conn, $AUDITORIA3_id, 'STATUS_AUDITORIA3');
		 $var1 = "update 07COMPROBACION SET STATUS_AUDITORIA3 = '".$AUDITORIA3_text."' WHERE id = '".$AUDITORIA3_id."'  ";	
	
		//if($pasarpagado_text=='si'){
		mysqli_query($conn,$var1) or die('P156'.mysqli_error($conn));
		$this->registrar_cambio_estado_detallado($conn, $AUDITORIA3_id, 'STATUS_AUDITORIA3', $valorAnterior, $AUDITORIA3_text);
		return "Actualizado^".$AUDITORIA3_text;
		//}
			
        }else{
		echo "NO HAY UN PROVEEDOR SELECCIONADO";	
		}
    }
	
	
	
	public function ACTUALIZA_FINANZAS (
	$RESPONSABLE_EVENTO_id , $RESPONSABLE_text ){
	
		$conn = $this->db();
		$session = isset($_SESSION['idem'])?$_SESSION['idem']:'';    
		if($session != ''){
			/*if($pasarpagado_text=='si'){
				$STATUS_DE_PAGO = 'PAGADO';
			}else{
				$STATUS_DE_PAGO = 'SOLICITADO';				
			}*/
		 $valorAnterior = $this->valor_actual_campo_comprobacion($conn, $RESPONSABLE_EVENTO_id, 'STATUS_FINANZAS');
		 $var1 = "update 07COMPROBACION SET STATUS_FINANZAS = '".$RESPONSABLE_text."' WHERE id = '".$RESPONSABLE_EVENTO_id."'  ";	
	
		//if($pasarpagado_text=='si'){
		mysqli_query($conn,$var1) or die('P156'.mysqli_error($conn));
		$this->registrar_cambio_estado_detallado($conn, $RESPONSABLE_EVENTO_id, 'STATUS_FINANZAS', $valorAnterior, $RESPONSABLE_text);
		return "Actualizado^".$RESPONSABLE_text;
		//}
			
        }else{
		echo "NO HAY UN PROVEEDOR SELECCIONADO";	
		}
    }

	public function ACTUALIZA_VENTAS (
	$RESPONSABLE_EVENTO_id , $RESPONSABLE_text ){
	
		$conn = $this->db();
		$session = isset($_SESSION['idem'])?$_SESSION['idem']:'';    
		if($session != ''){
			/*if($pasarpagado_text=='si'){
				$STATUS_DE_PAGO = 'PAGADO';
			}else{
				$STATUS_DE_PAGO = 'SOLICITADO';				
			}*/
		 $valorAnterior = $this->valor_actual_campo_comprobacion($conn, $RESPONSABLE_EVENTO_id, 'STATUS_VENTAS');
		 $var1 = "update 07COMPROBACION SET STATUS_VENTAS = '".$RESPONSABLE_text."' WHERE id = '".$RESPONSABLE_EVENTO_id."'  ";	
	
		//if($pasarpagado_text=='si'){
		mysqli_query($conn,$var1) or die('P156'.mysqli_error($conn));
		$this->registrar_cambio_estado_detallado($conn, $RESPONSABLE_EVENTO_id, 'STATUS_VENTAS', $valorAnterior, $RESPONSABLE_text);
		return "Actualizado^".$RESPONSABLE_text;
		//}
			
        }else{
		echo "NO HAY UN PROVEEDOR SELECCIONADO";	
		}
    }

		public function ACTUALIZA_CHECKBOX (
	    $CHECKBOX_id , $CHECKBOX_text ){
	
		$conn = $this->db();
		$session = isset($_SESSION['idem'])?$_SESSION['idem']:'';    
		if($session != ''){

	    $valorAnterior = $this->valor_actual_campo_comprobacion($conn, $CHECKBOX_id, 'STATUS_CHECKBOX');
		$var1 = "update 07COMPROBACION SET STATUS_CHECKBOX = '".$CHECKBOX_text."' WHERE id = '".$CHECKBOX_id."'  ";	
	
		
		mysqli_query($conn,$var1) or die('P156'.mysqli_error($conn));
		$this->registrar_cambio_estado_detallado($conn, $CHECKBOX_id, 'STATUS_CHECKBOX', $valorAnterior, $CHECKBOX_text);
		return "Actualizado";
		
			
        }else{
		echo "NO HAY UN PROVEEDOR SELECCIONADO";	
		}
    }

	public function borrapagoaproveedores($id){ 
		$conn = $this->db();
	
		$var1 = "DELETE FROM 07COMPROBACION where id = '".$id."' "; 
		mysqli_query($conn,$var1) or die('P44'.mysqli_error($conn));
		
		$var2 = "DELETE FROM `07XML` WHERE `ultimo_id` = '".$id."' ";
		mysqli_query($conn,$var2) or die('P44'.mysqli_error($conn));
		
		$var3 = "DELETE FROM `07COMPROBACIONDOCT` WHERE `idTemporal` = '".$id."' ";
		mysqli_query($conn,$var3) or die('P44'.mysqli_error($conn));	
		
$var4 = "DELETE FROM `07COMPROBACION_BITACORA` WHERE `id_comprobacion` = '".$id."' ";
		mysqli_query($conn,$var4) or die('P44'.mysqli_error($conn));	
		
		ECHO "ELEMENTO BORRADO";
		

	}
	
   public function borrar_xmls($ruta,$id,$nombrearchivo,$tabla1,$tabla2){
                $conn = $this->db();
                //`07COMPROBACIONDOCT` WHERE `idTemporal`
                // `07XML` ORDER BY `07XML`.`ultimo_id`
		$var1 = "delete FROM ".$tabla1." WHERE `ultimo_id` = '".$id."' ";
		mysqli_query($conn,$var1);

		$var2 = "SELECT * FROM ".$tabla2." WHERE 
		`idTemporal` = '".$id."' and 
		ADJUNTAR_FACTURA_XML <> '".$nombrearchivo."' and ADJUNTAR_FACTURA_XML <> '' ";
		$QUERYVAR2 = mysqli_query($conn,$var2) or die('P44'.mysqli_error($conn));
		while($row = mysqli_fetch_array($QUERYVAR2, MYSQLI_ASSOC)){
			if( file_exists($ruta.''.$row['ADJUNTAR_FACTURA_XML']) ){
			UNLINK($ruta.''.$row['ADJUNTAR_FACTURA_XML']);
			}
		}
 $var3 = "DELETE FROM ".$tabla2." WHERE `idTemporal` = '".$id."'and
                ADJUNTAR_FACTURA_XML <> '".$nombrearchivo."' and ADJUNTAR_FACTURA_XML <>'' ";
                mysqli_query($conn,$var3) or die('P44'.mysqli_error($conn));
        }

        public function limpiar_historial_factura_xml($idRelacion, $ruta){
                $conn = $this->db();

                if($idRelacion == ''){
                        return;
                }

                $rutaArchivos = rtrim($ruta, '/').'/';

                $consultaArchivos = "select ADJUNTAR_FACTURA_XML from 07COMPROBACIONDOCT where idRelacion = '".$idRelacion."' and idTemporal = 'si' and (ADJUNTAR_FACTURA_XML is not null and ADJUNTAR_FACTURA_XML <> '')";
                $resultados = mysqli_query($conn,$consultaArchivos);

                while($row = mysqli_fetch_array($resultados, MYSQLI_ASSOC)){
                        $nombreArchivo = trim($row['ADJUNTAR_FACTURA_XML']);
                        if($nombreArchivo != ''){
                                $rutaCompleta = $rutaArchivos.$nombreArchivo;
                                if(file_exists($rutaCompleta)){
                                        unlink($rutaCompleta);
                                }
                        }
                }

                $var3 = "DELETE FROM 07COMPROBACIONDOCT WHERE idRelacion = '".$idRelacion."' and idTemporal = 'si' and (ADJUNTAR_FACTURA_XML is not null and ADJUNTAR_FACTURA_XML <> '')";
                mysqli_query($conn,$var3) or die('P44'.mysqli_error($conn));
        }

    public function select_02XML(){
    $conn = $this->db();
    $variablequery = "select id from 07COMPROBACION order by id desc ";
    $arrayquery = mysqli_query($conn,$variablequery);
    $row = mysqli_fetch_array($arrayquery, MYSQLI_ASSOC);
	return $row['id'];	
}

	public function VALIDA02XMLUUID($uuid){
$conn = $this->db(); 
$variablequery = "select id,UUID from 07XML where UUID = '".$uuid."' "; 
$arrayquery = mysqli_query($conn,$variablequery);
$row = mysqli_fetch_array($arrayquery, MYSQLI_ASSOC);
if($row['id']==0 or $row['id']==''){
	return 'S';
}else{
	return $row['id'];	
}
}







public function Listado_pagoproveedor(){ $conn = $this->db(); $variablequery = "select * from 07COMPROBACION where idRelacion = '".$_SESSION['idCG']."' order by id desc "; return $arrayquery = mysqli_query($conn,$variablequery); } 




 

      public function Listado_pagoproveedor2($id){ 
	$conn = $this->db(); 
	$variablequery = "select * from 07COMPROBACION where id = '".$id."' "; 
	return $arrayquery = mysqli_query($conn,$variablequery); 
	}

	public function Listado_bitacora_pagoproveedor_array($idComprobacion){
		$conn = $this->db();
		$idComprobacion = intval($idComprobacion);
		$this->inicializar_tablas_auxiliares();

		$query = mysqli_query($conn, "SELECT b.tipo_movimiento, b.detalle, b.fecha_hora,
			b.nombre_quien_ingreso, b.nombre_quien_actualizo,
			s.NUMERO_CONSECUTIVO_PROVEE, s.RAZON_SOCIAL AS proveedor, s.MONTO_DEPOSITAR AS monto, s.NOMBRE_EVENTO AS evento
			FROM 07COMPROBACION_BITACORA b
			LEFT JOIN 07COMPROBACION s ON s.id = b.id_comprobacion
			WHERE b.id_comprobacion = '".$idComprobacion."'
			ORDER BY b.id DESC");

		$resultado = array();
		if($query){
			while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
				if(!empty($row['fecha_hora'])){
					$fecha = DateTime::createFromFormat('Y-m-d H:i:s', $row['fecha_hora'], new DateTimeZone('UTC'));
					if($fecha){
						$fecha->setTimezone(new DateTimeZone('America/Mexico_City'));
						$row['fecha_hora'] = $fecha->format('d/m/Y H:i:s');
					}
				}
				$resultado[] = $row;
			}
		}
		return $resultado;
	}

    public function Listado_subefacturaDOCTOS($ID) {
    $conn = $this->db();
   $ID = mysqli_real_escape_string($conn, (string)$ID);



    $idRelacionComprobacion = '';

    $qComprobacion = "SELECT idRelacion

                      FROM 07COMPROBACION

                      WHERE id = '".$ID."'

                      LIMIT 1";

                    
   $rComprobacion = mysqli_query($conn, $qComprobacion);

    if ($rComprobacion && ($rowComprobacion = mysqli_fetch_assoc($rComprobacion))) {

        $idRelacionComprobacion = mysqli_real_escape_string($conn, (string)$rowComprobacion['idRelacion']);

    }
    
   $idCGsesion = isset($_SESSION['idCG']) ? mysqli_real_escape_string($conn, (string)$_SESSION['idCG']) : '';

    $idRelacionVincular = '';



    if ($idRelacionComprobacion != '') {

        $idRelacionVincular = $idRelacionComprobacion;

    } elseif ($idCGsesion != '') {

        $idRelacionVincular = $idCGsesion;

    }



    if ($idRelacionVincular != '') {

        $fix = "UPDATE 07COMPROBACIONDOCT

                SET idTemporal = '".$ID."'

                WHERE idRelacion = '".$idRelacionVincular."'

                  AND idTemporal = 'si'";

        mysqli_query($conn, $fix);

    }



    $condiciones = array();

    $condiciones[] = "idTemporal = '".$ID."'";



    if ($idRelacionVincular != '') {

        $condiciones[] = "(idRelacion = '".$idRelacionVincular."' AND idTemporal = 'si')";

    }



    $variablequery = "SELECT * FROM 07COMPROBACIONDOCT

                      WHERE (".implode(' OR ', $condiciones).")

                      ORDER BY id DESC";
    return mysqli_query($conn, $variablequery);
}

    	public function tiene_adjunto_factura($campo, $idRelacion) {
		$conn = $this->db();
		$campo = preg_replace('/[^a-zA-Z0-9_]/', '', $campo);
		$idRelacion = mysqli_real_escape_string($conn, (string)$idRelacion);
		$query = "SELECT COUNT(*) as total FROM 07COMPROBACIONDOCT
		          WHERE idRelacion = '".$idRelacion."'
		          AND idTemporal = 'si'
		          AND ".$campo." IS NOT NULL
		          AND ".$campo." <> ''";
		$result = mysqli_query($conn, $query);
		if (!$result) return false;
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		return intval($row['total']) > 0;
	}

    public function Listado_subefacturadocto($ADJUNTAR_COTIZACION){

	$conn = $this->db();
	
	$CIERRE_TOTAL11= strtotime('-1 hours', strtotime(date("Y-m-d")));
	$nuevafecha2 = date ( 'Y-m-d' , $CIERRE_TOTAL11 );

	$variablequeryborra = "DELETE FROM 07COMPROBACIONDOCT WHERE `fechaingreso` <= '".$nuevafecha2."' and idRelacion = '".$_SESSION['idCG']."' and idTemporal = 'si'  ";
	mysqli_query($conn,$variablequeryborra);

	$variablequery = "select id,".$ADJUNTAR_COTIZACION.",fechaingreso from 07COMPROBACIONDOCT where idRelacion = '".$_SESSION['idCG']."' and idTemporal = 'si' and (".$ADJUNTAR_COTIZACION." is not null or ".$ADJUNTAR_COTIZACION." <> '') ORDER BY id DESC "; 
	return $arrayquery = mysqli_query($conn,$variablequery); 
	}
	
  public function delete_subefacturadocto2($id){ $conn = $this->db();

    $query = "SELECT idTemporal, ADJUNTAR_FACTURA_XML FROM 07COMPROBACIONDOCT WHERE id = '".$id."' ";
    $resultado = mysqli_query($conn,$query);
    $row = mysqli_fetch_array($resultado, MYSQLI_ASSOC);

    if ($row && $row['ADJUNTAR_FACTURA_XML'] != '') {
        $variablequery = "DELETE FROM 07XML WHERE ultimo_id = '".$row['idTemporal']."' ";
        mysqli_query($conn,$variablequery);


    }

    $variablequery = "delete from 07COMPROBACIONDOCT where id = '".$id."' ";
    return $arrayquery = mysqli_query($conn,$variablequery);

}

   public function delete_subefactura2nombre($nombre){ $conn = $this->db(); 
   $variablequery = "delete from 07COMPROBACIONDOCT where ADJUNTAR_FACTURA_XML = '".$nombre."' ";
   mysqli_query($conn,$variablequery); 

}






/* DATOS BANCARIOS 1 */ 


	public function variable_DATOSBANCARIOS1(){
		$conn = $this->db();
		$variablequery = "select * from 02DATOSBANCARIOS1 where idRelacion = '".$_SESSION['idCG']."' ";
		$arrayquery = mysqli_query($conn,$variablequery);
		return $row = mysqli_fetch_array($arrayquery, MYSQLI_ASSOC);		
	}

	public function revisar_DATOSBANCARIOS1(){
		$conn = $this->db();
		$var1 = 'select id from 02DATOSBANCARIOS1 where idRelacion =  "'.$_SESSION['idCG'].'" ';
		$query = mysqli_query($conn,$var1) or die('P44'.mysqli_error($conn));
		$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
		return $row['id'];
	}

	public function enviarDATOSBANCARIOS12 (
	$P_TIPO_DE_MONEDA_1 , $P_INSTITUCION_FINANCIERA_1 , $P_NUMERO_DE_CUENTA_DB_1 , $P_NUMERO_CLABE_1 , 
	$P_NUMERO_DE_SUCURSAL_1 , $P_NUMERO_IBAN_1 , $P_NUMERO_CUENTA_SWIFT_1,$FOTO_ESTADO_PROVEE,$ULTIMA_CARGA_DATOBANCA, $ENVIARRdatosbancario1p,$IPdatosbancario1p ){
	
		$conn = $this->db();
		$existe = $this->revisar_DATOSBANCARIOS1();
		$session = isset($_SESSION['idCG'])?$_SESSION['idCG']:'';    
		if($session != ''){
			
		$var1 = "update 02DATOSBANCARIOS1 set P_TIPO_DE_MONEDA_1 = '".$P_TIPO_DE_MONEDA_1."' , P_INSTITUCION_FINANCIERA_1 = '".$P_INSTITUCION_FINANCIERA_1."' , P_NUMERO_DE_CUENTA_DB_1 = '".$P_NUMERO_DE_CUENTA_DB_1."' , P_NUMERO_CLABE_1 = '".$P_NUMERO_CLABE_1."' , P_NUMERO_DE_SUCURSAL_1 = '".$P_NUMERO_DE_SUCURSAL_1."' , P_NUMERO_IBAN_1 = '".$P_NUMERO_IBAN_1."' , P_NUMERO_CUENTA_SWIFT_1 = '".$P_NUMERO_CUENTA_SWIFT_1."' ,ULTIMA_CARGA_DATOBANCA = '".$ULTIMA_CARGA_DATOBANCA."'  where id = '".$IPdatosbancario1p."' ; ";
		
		
		$var2 = "insert into 02DATOSBANCARIOS1 (P_TIPO_DE_MONEDA_1, P_INSTITUCION_FINANCIERA_1, P_NUMERO_DE_CUENTA_DB_1, P_NUMERO_CLABE_1, P_NUMERO_DE_SUCURSAL_1, P_NUMERO_IBAN_1, P_NUMERO_CUENTA_SWIFT_1,FOTO_ESTADO_PROVEE, ULTIMA_CARGA_DATOBANCA, idRelacion) values ( '".$P_TIPO_DE_MONEDA_1."' , '".$P_INSTITUCION_FINANCIERA_1."' , '".$P_NUMERO_DE_CUENTA_DB_1."' , '".$P_NUMERO_CLABE_1."' , '".$P_NUMERO_DE_SUCURSAL_1."' , '".$P_NUMERO_IBAN_1."' , '".$P_NUMERO_CUENTA_SWIFT_1."' , '".$FOTO_ESTADO_PROVEE."' , '".$ULTIMA_CARGA_DATOBANCA."' , '".$session."' );  ";			
	
		if($ENVIARRdatosbancario1p=='ENVIARRdatosbancario1p'){	

		mysqli_query($conn,$var1) or die('P156'.mysqli_error($conn));
		return "Actualizado";
		}else{
		mysqli_query($conn,$var2) or die('P160'.mysqli_error($conn));
		return "Ingresado";
		}
			
        }else{
		echo "NO HAY UN PROVEEDOR SELECCIONADO";	
		}
    }



	public function Listado_datos_bancariosPRO(){
		$conn = $this->db();

		$variablequery = "select * from 02DATOSBANCARIOS1 where idRelacion = '".$_SESSION['idCG']."' order by id desc ";
		return $arrayquery = mysqli_query($conn,$variablequery);
	}


        public function Listado_datos_bancariosPRO2($id){ $conn = $this->db(); $variablequery = "select * from 02DATOSBANCARIOS1 where id = '".$id."' "; return $arrayquery = mysqli_query($conn,$variablequery); }


         function borra_datos_bancario1($id){
		$conn = $this->db();
		$variablequery = "delete from 02DATOSBANCARIOS1 where id = '".$id."' ";
		$arrayquery = mysqli_query($conn,$variablequery);
		RETURN 
		
		"<P style='color:green; font-size:18px;'>ELEMENTO BORRADO</P>";
	}
	
          public function listado_colaboradorescel2CAMBIAR(){
		$conn = $this->db();

		$variablequery = "select * from 
		01informacionpersonal inner join 01adjuntoscolaboradores  on 01informacionpersonal.idRelacion = 01adjuntoscolaboradores.idRelacion
		where STATUS_CARGA_INFORMACION = 'COLABORADOR'  AND
		ESTATUS_CRM_ACTIVOBAJA = 'ACTIVO'

		order by 01informacionpersonal.id asc ";
		return $arrayquery = mysqli_query($conn,$variablequery);
	}
}


	?>