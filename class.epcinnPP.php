<?php



/*
clase EPC INNOVA
CREADO : 10/mayo/2023
TESTER: FATIMA ARELLANO
PROGRAMER: SANDOR ACTUALIZACION: 1 MAY 2023

*/
	define('__ROOT3__', dirname(dirname(__FILE__)));
	require __ROOT3__."/includes/class.epcinn.php";	
	
	
	class accesoclase extends colaboradores{

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
			//$resultado [] = $row['NUMERO_DE_EVENTO'];
			$resultado[] = ['id'=>$row['NUMERO_EVENTO'],'text'=>$row['NUMERO_EVENTO']];
		}
		return $resultado;
		
	}

	public function ultimopago($filtro){
		$conn = $this->db();
		$variable = "select * from 02SUBETUFACTURA where NUMERO_EVENTO = '".$filtro."' ";
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
	

	public function solocargartemp($archivo)/*new file*/
	{

		$nombre_carpeta=__ROOT3__.'/includes/archivos';
		$filehandle = opendir($nombre_carpeta);
		$nombretemp = $_FILES[$archivo]["tmp_name"];
		$nombrearchivo = $_FILES[$archivo]["name"];
		$tamanyoarchivo = $_FILES[$archivo]["size"];
		$tipoarchivo = getimagesize($nombretemp);
        $nombrearchivo = basename($nombrearchivo);
		$extension = explode('.',$nombrearchivo);
		$cuenta = count($extension) - 1;
		$nuevonombre = $nombrearchivo;
		//echo '1aaaaaaaaaaaaaaaa2'.$extension[$cuenta].'1aaaaaaaaaaaaaaaa2';
		
		if( 
		strtolower($extension[$cuenta]) == 'pdf' or 
		strtolower($extension[$cuenta]) == 'gif' or 
		strtolower($extension[$cuenta]) == 'jpeg' or 
		strtolower($extension[$cuenta]) == 'jpg' or 
		strtolower($extension[$cuenta]) == 'png' or 
		strtolower($extension[$cuenta]) == 'mp4' or 
		strtolower($extension[$cuenta]) == 'docx' or 
		strtolower($extension[$cuenta]) == 'doc' or 
		strtolower($extension[$cuenta]) == 'xml'
		){
		if(move_uploaded_file($nombretemp, $nombre_carpeta.'/'. $nuevonombre)){
		chmod ($nombre_carpeta.'/' . $nuevonombre, 0755);
		$tamanyo =fileSize($nombre_carpeta.'/'. $nuevonombre);
		$fp = fopen($nombre_carpeta.'/'.$nuevonombre, "rb"); 
		$contenido = fread($fp, $tamanyo);
		$contenido = addslashes($contenido);
		
		return trim($nuevonombre);
		
		}
		else{
			return "1";
		}
		
		}
		else{
			return "2";
		}
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
	


	public function busca_07XML($ultimo_id){
	$conn = $this->db();		
	$variablequery = "select * from 07XML where ultimo_id = '".$ultimo_id."' "; 
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

  	public function guardarxmlDB($ultimo_id,$conn){
	$conexion2 = new herramientas();
	$regreso = $this->variable_SUBETUFACTURA();
	$url = __ROOT3__.'/includes/archivos/'.$regreso['ADJUNTAR_FACTURA_XML'];
	$session = isset($_SESSION['idCG'])?$_SESSION['idCG']:'';    

	$conexion2->guardar_db_xml($url,$session,$ultimo_id,$conn);
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
}
		$session = isset($_SESSION['idCG'])?$_SESSION['idCG']:'';    

		$conn = $this->db();
	$var3 = "INSERT INTO `07XML` (
	`id`, `Version`, `fechaTimbrado`, `tipoDeComprobante`, 
	`metodoDePago`, `formaDePago`, `condicionesDePago`, `subTotal`, 
	`TipoCambio`, `Moneda`, `total`, `serie`, 
	`folio`, `LugarExpedicion`, `rfcE`, `nombreE`, 
	`regimenE`, `rfcR`, `nombreR`, `UsoCFDI`, 
	`DomicilioFiscalReceptor`, `RegimenFiscalReceptor`, `UUID`, `TImpuestosRetenidos`, 
	`TImpuestosTrasladados`,  `Descuento`, `DescripcionConcepto`,`Cantidad`,`ClaveUnidad`,`ClaveProdServ`,`idRelacion`, `ultimo_id`) VALUES (
	'', '".$Version."', '".$FechaTimbrado."', '".$tipoDeComprobante."', 
	'".$metodoDePago."', '".$formaDePago."', '".$condicionesDePago."', '".$subTotal."', 
	'".$TipoCambio."', '".$Moneda."', '".$total."', '".$serie."', 
	'".$folio."', '".$LugarExpedicion."', '".$rfcE."', '".$nombreE."', 
	'".$regimenE."', '".$rfcR."', '".$nombreR."', '".$UsoCFDI."', 
	'".$DomicilioFiscalReceptor."', '".$RegimenFiscalReceptor."', '".$UUID."', '".$TImpuestosRetenidos."', 
	'".$TImpuestosTrasladados."', 
	'".$Descuento."', 
	'".$DescripcionConcepto."', 
	'".$Cantidad."', 
	'".$ClaveUnidad."', 
	'".$ClaveProdServ."', '".$session."', '".$ultimo_id."'
	);  ";	
		mysqli_query($conn,$var3) or die('P156'.mysqli_error($conn));
		//return "1";	
		
		
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
		ECHO  $queryrfc = "SELECT * FROM 02direccionproveedor1 WHERE P_NOMBRE_FISCAL_RS_EMPRESA = '".$nommbrerazon."' ";
		$arrayquery = mysqli_query($conn,$queryrfc);
		$row = mysqli_fetch_array($arrayquery, MYSQLI_ASSOC);
		return $row['id'];
	}
		
	public function ingresar_usuario($conn,$nommbrerazon){
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

	/*public function ingresar_02direccionproveedor1($conn,$idwebc){
		$queryrfc = "insert into 02direccionproveedor1 (idRelacion)values('".$idwebc."')";
		$arrayquery = mysqli_query($conn,$queryrfc);
		return $row = mysqli_fetch_array($arrayquery, MYSQLI_ASSOC);
	}    */
//ingresar_02direccionproveedor1

	
	public function PAGOPRO ($NUMERO_CONSECUTIVO_PROVEE , $NOMBRE_COMERCIAL , $RAZON_SOCIAL , $RFC_PROVEEDOR , $NUMERO_EVENTO ,$NOMBRE_EVENTO, $MOTIVO_GASTO , $CONCEPTO_PROVEE , $MONTO_TOTAL_COTIZACION_ADEUDO , $MONTO_DEPOSITAR , $MONTO_PROPINA , $FECHA_AUTORIZACION_RESPONSABLE , $FECHA_AUTORIZACION_AUDITORIA , $FECHA_DE_LLENADO , $MONTO_FACTURA , $TIPO_DE_MONEDA , $PFORMADE_PAGO,$FECHA_DE_PAGO , $FECHA_A_DEPOSITAR , $STATUS_DE_PAGO ,$ACTIVO_FIJO, $GASTO_FIJO,$PAGAR_CADA,$FECHA_PPAGO,$FECHA_TPROGRAPAGO,$NUMERO_EVENTOFIJO,$CLASI_GENERAL,$SUB_GENERAL,$BANCO_ORIGEN , $MONTO_DEPOSITADO , $CLASIFICACION_GENERAL , $CLASIFICACION_ESPECIFICA , $PLACAS_VEHICULO , $MONTO_DE_COMISION , $POLIZA_NUMERO , $NOMBRE_DEL_EJECUTIVO , $NOMBRE_DEL_AYUDO,$OBSERVACIONES_1, $TIPO_CAMBIOP,  $TOTAL_ENPESOS,$IMPUESTO_HOSPEDAJE,$IVA,$EJECUTIVOTARJETA, $ENVIARPAGOprovee,$hiddenpagoproveedores,$TImpuestosRetenidosIVA,$TImpuestosRetenidosISR,$descuentos,$IPpagoprovee,
	$FechaTimbrado, $tipoDeComprobante, 
		$metodoDePago, $formaDePago, $condicionesDePago, $subTotal, 
		$TipoCambio, $Moneda, $total, $serie, 
		$folio, $LugarExpedicion, $rfcE, $nombreE, 
		$regimenE, $rfcR, $nombreR, $UsoCFDI, 
		$DomicilioFiscalReceptor, $RegimenFiscalReceptor, $UUID, $TImpuestosRetenidos, 
		$TImpuestosTrasladados, $TuaTotalCargos, $Descuento,$Propina, $TUA,$actualiza,$DescripcionConcepto,$Cantidad,$ClaveUnidad,$ClaveProdServ)
	{

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

		
		$conn = $this->db();

						
		/*if( $this->verificar_rfc($conn,$RFC_PROVEEDOR) ==''){
			$idwebc = $this->ingresar_usuario($conn,$RAZON_SOCIAL);
			$this->ingresar_rfc($conn,$RFC_PROVEEDOR,$idwebc);
			$this->ingresar_02direccionproveedor1($conn,$idwebc);
		}*/
		
		if( $this->verificar_rfc($conn,$RFC_PROVEEDOR)!=''){
			$session = $this->verificar_rfc($conn,$RFC_PROVEEDOR);
		}else{
			$session = verificar_usuario($conn,$nommbrerazon);		
		}
		
		$existe = $this->revisar_pagoproveedor2($IPpagoprovee);		
		//$session = isset($_SESSION['idCG'])?$_SESSION['idCG']:$existe;
		//$session = isset($_SESSION['idCG'])?$_SESSION['idCG']:$existe;
		
		if($session != ''){
			//ADJUNTAR_FACTURA_XML 
		$var1 = "update 07COMPROBACION set
		NUMERO_CONSECUTIVO_PROVEE = '".$NUMERO_CONSECUTIVO_PROVEE."' , NOMBRE_COMERCIAL = '".$NOMBRE_COMERCIAL."' , RAZON_SOCIAL = '".$RAZON_SOCIAL."' , RFC_PROVEEDOR = '".$RFC_PROVEEDOR."' , NUMERO_EVENTO = '".$NUMERO_EVENTO."' , NOMBRE_EVENTO = '".$NOMBRE_EVENTO."' , MOTIVO_GASTO = '".$MOTIVO_GASTO."' , CONCEPTO_PROVEE = '".$CONCEPTO_PROVEE."' , MONTO_TOTAL_COTIZACION_ADEUDO = '".$MONTO_TOTAL_COTIZACION_ADEUDO."' , MONTO_DEPOSITAR = '".$MONTO_DEPOSITAR."' , MONTO_PROPINA = '".$MONTO_PROPINA."' , FECHA_AUTORIZACION_RESPONSABLE = '".$FECHA_AUTORIZACION_RESPONSABLE."' , FECHA_AUTORIZACION_AUDITORIA = '".$FECHA_AUTORIZACION_AUDITORIA."' ,FECHA_DE_LLENADO = '".$FECHA_DE_LLENADO."' , MONTO_FACTURA = '".$MONTO_FACTURA."' , TIPO_DE_MONEDA = '".$TIPO_DE_MONEDA."' , PFORMADE_PAGO = '".$PFORMADE_PAGO."' , FECHA_DE_PAGO = '".$FECHA_DE_PAGO."' , FECHA_A_DEPOSITAR = '".$FECHA_A_DEPOSITAR."' , STATUS_DE_PAGO = '".$STATUS_DE_PAGO."' , ACTIVO_FIJO = '".$ACTIVO_FIJO."' , GASTO_FIJO = '".$GASTO_FIJO."' , PAGAR_CADA = '".$PAGAR_CADA."' , FECHA_PPAGO = '".$FECHA_PPAGO."' , FECHA_TPROGRAPAGO = '".$FECHA_TPROGRAPAGO."' , NUMERO_EVENTOFIJO = '".$NUMERO_EVENTOFIJO."' , CLASI_GENERAL = '".$CLASI_GENERAL."' , SUB_GENERAL = '".$SUB_GENERAL."' , BANCO_ORIGEN = '".$BANCO_ORIGEN."' , MONTO_DEPOSITADO = '".$MONTO_DEPOSITADO."' , CLASIFICACION_GENERAL = '".$CLASIFICACION_GENERAL."' , CLASIFICACION_ESPECIFICA = '".$CLASIFICACION_ESPECIFICA."' , PLACAS_VEHICULO = '".$PLACAS_VEHICULO."' , MONTO_DE_COMISION = '".$MONTO_DE_COMISION."' , POLIZA_NUMERO = '".$POLIZA_NUMERO."' , NOMBRE_DEL_EJECUTIVO = '".$NOMBRE_DEL_EJECUTIVO."' , NOMBRE_DEL_AYUDO = '".$NOMBRE_DEL_AYUDO."' , OBSERVACIONES_1 = '".$OBSERVACIONES_1."' , TIPO_CAMBIOP = '".$TIPO_CAMBIOP."' , TOTAL_ENPESOS = '".$TOTAL_ENPESOS."' , IMPUESTO_HOSPEDAJE = '".$IMPUESTO_HOSPEDAJE."' , TImpuestosRetenidosIVA = '".$TImpuestosRetenidosIVA."' , TImpuestosRetenidosISR = '".$TImpuestosRetenidosISR."' , descuentos = '".$descuentos."' , IVA = '".$IVA."' , EJECUTIVOTARJETA = '".$EJECUTIVOTARJETA."' where id = '".$existe."' ; ";
		
		
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
		IMPUESTO_HOSPEDAJE,		
		IVA,
		EJECUTIVOTARJETA,
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
		'".$IMPUESTO_HOSPEDAJE."',
		'".$IVA."',
		'".$EJECUTIVOTARJETA."',
		'".$TImpuestosRetenidosIVA."',
		'".$TImpuestosRetenidosISR."',
		'".$descuentos."',
		'".$hiddenpagoproveedores."' ,
		'".$session."' );  ";			


		if($ENVIARPAGOprovee=='ENVIARPAGOprovee'){
			
		$this->ActualizaxmlDB($FechaTimbrado, $tipoDeComprobante, 
		$metodoDePago, $formaDePago, $condicionesDePago, $subTotal, 
		$TipoCambio, $Moneda, $total, $serie, 
		$folio, $LugarExpedicion, $rfcE, $nombreE, 
		$regimenE, $rfcR, $nombreR, $UsoCFDI, 
		$DomicilioFiscalReceptor, $RegimenFiscalReceptor, $UUID, $TImpuestosRetenidos, 
		$TImpuestosTrasladados, $session, $existe, $TuaTotalCargos, $TUA, $Descuento, $Propina, $conn,  $actualiza);
		
		mysqli_query($conn,$var1) or die('P15622'.mysqli_error($conn));
		return "Actualizado";
		}else{
			//insert into
		mysqli_query($conn,$var2) or die('P16022'.mysqli_error($conn));
		$ultimo_id ='';		
		$ultimo_id = mysqli_insert_id($conn);
		$this->guardarxmlDB($ultimo_id,$conn);
		$var3 = "UPDATE 07COMPROBACIONDOCT SET idTemporal ='".$ultimo_id."' where idRelacion = '".$_SESSION['idCG']."' and idTemporal ='si' "; 			
		mysqli_query($conn,$var3);	
		return "Ingresado";
		}
			
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
		$var1 = "update 07COMPROBACION SET STATUS_DE_PAGO = '".$STATUS_DE_PAGO."' WHERE id = '".$pasarpagado_id."'  ";	
	
		//if($pasarpagado_text=='si'){
		mysqli_query($conn,$var1) or die('P156'.mysqli_error($conn));
		return "Actualizado";
		//}
			
        }else{
		echo "NO HAY UN PROVEEDOR SELECCIONADO";	
		}
    }

//Listado_subefacturaDOCTOS
	public function borrapagoaproveedores($id){ 
		$conn = $this->db();
		//papa
		$var1 = "DELETE FROM 07COMPROBACION where id = '".$id."' "; 
		mysqli_query($conn,$var1) or die('P44'.mysqli_error($conn));
		
		$var2 = "DELETE FROM `07XML` WHERE `ultimo_id` = '".$id."' ";
		mysqli_query($conn,$var2) or die('P44'.mysqli_error($conn));
		ECHO "ELEMENTO BORRADO";
		

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

    public function Listado_subefacturaDOCTOS($ID){
		$conn = $this->db();
		$variablequery = "select * from 07COMPROBACIONDOCT where idTemporal = '".$ID."'  order by id desc ";
		return $arrayquery = mysqli_query($conn,$variablequery);
		}

    public function Listado_subefacturadocto($ADJUNTAR_COTIZACION){ $conn = $this->db(); $variablequery = "select id,".$ADJUNTAR_COTIZACION.",fechaingreso from 07COMPROBACIONDOCT where idRelacion = '".$_SESSION['idCG']."' and idTemporal = 'si' and (".$ADJUNTAR_COTIZACION." is not null or ".$ADJUNTAR_COTIZACION." <> '') ORDER BY id DESC "; return $arrayquery = mysqli_query($conn,$variablequery); }
	
    public function delete_subefacturadocto2($id){ $conn = $this->db(); 
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