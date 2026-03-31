<?php
/*
clase EPC INNOVA
CREADO : 10/mayo/2023
fecha sandor: 21/ABRIL/2025
fecha fatis : 05/04/2025

*/
	define('__ROOT3__', dirname(dirname(__FILE__)));
	require __ROOT3__."/includes/class.epcinn.php";	
	
	
class accesoclase extends colaboradores{

	private function nombre_usuario_bitacora(){
		if(isset($_SESSION['NOMBREUSUARIO']) && $_SESSION['NOMBREUSUARIO'] != ''){
			return $_SESSION['NOMBREUSUARIO'];
		}
		if(isset($_SESSION['nombreusuario']) && $_SESSION['nombreusuario'] != ''){
			return $_SESSION['nombreusuario'];
		}
		if(isset($_SESSION['usuario']) && $_SESSION['usuario'] != ''){
			return $_SESSION['usuario'];
		}
		if(isset($_SESSION['idem']) && $_SESSION['idem'] != ''){
			return 'ID:'.$_SESSION['idem'];
		}
		return 'SIN_USUARIO';
	}

	private function registrar_bitacora($conn, $idSubetufactura, $tipoMovimiento, $detalle, $nombreQuienIngreso = '', $nombreQuienActualizo = ''){
		$crearTabla = "CREATE TABLE IF NOT EXISTS `02SUBETUFACTURA_BITACORA` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`id_subetufactura` int(11) NOT NULL DEFAULT 0,
			`tipo_movimiento` varchar(50) NOT NULL,
			`detalle` text,
			`fecha_hora` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			`nombre_quien_ingreso` varchar(255) DEFAULT NULL,
			`nombre_quien_actualizo` varchar(255) DEFAULT NULL,
			PRIMARY KEY (`id`),
			KEY `idx_id_subetufactura` (`id_subetufactura`),
			KEY `idx_fecha_hora` (`fecha_hora`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
		mysqli_query($conn, $crearTabla);

		$idSubetufactura = intval($idSubetufactura);
		$tipoMovimiento = mysqli_real_escape_string($conn, $tipoMovimiento);
		$detalle = mysqli_real_escape_string($conn, $detalle);
		$nombreQuienIngreso = mysqli_real_escape_string($conn, $nombreQuienIngreso);
		$nombreQuienActualizo = mysqli_real_escape_string($conn, $nombreQuienActualizo);

		$insertBitacora = "INSERT INTO 02SUBETUFACTURA_BITACORA
		(id_subetufactura, tipo_movimiento, detalle, fecha_hora, nombre_quien_ingreso, nombre_quien_actualizo)
		VALUES
		('".$idSubetufactura."', '".$tipoMovimiento."', '".$detalle."', NOW(), '".$nombreQuienIngreso."', '".$nombreQuienActualizo."')";

		mysqli_query($conn, $insertBitacora);
	}

	private function etiqueta_bitacora_campo($campo){
		$etiquetas = array(
			'STATUS_DE_PAGO'    => 'ESTATUS DE PAGO',
			'MONTO_DEPOSITAR'   => 'TOTAL A PAGAR',
			'FECHA_A_DEPOSITAR' => 'FECHA EFECTIVA DE PAGO',
			'FECHA_DE_PAGO'     => 'FECHA DE PROGRAMACIÓN DEL PAGO',
			'PFORMADE_PAGO'     => 'FORMA DE PAGO'
			
		);
		if(isset($etiquetas[$campo])){
			return $etiquetas[$campo];
		}
		return str_replace('_', ' ', $campo);
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

	public function buscarnumero($filtro){
		$conn = $this->db();
		$variable = "select * from 04altaeventos where NUMERO_EVENTO like '%".$filtro."%' ";
		$variablequery = mysqli_query($conn,$variable);
		while($row = mysqli_fetch_array($variablequery, MYSQLI_ASSOC)){
			$resultado[] = $row['NUMERO_EVENTO'];
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
		$variable = "select * from 02SUBETUFACTURA where NUMERO_EVENTO = '".$filtro."' ";
		$resultado = 0;
		$variablequery = mysqli_query($conn,$variable);
		while($row = mysqli_fetch_array($variablequery, MYSQLI_ASSOC)){
			$resultado += $row['MONTO_DEPOSITADO'];
		}
		return $resultado;
	}

	public function buscarnombre($filtro){
		$conn = $this->db();
		$variable = "select * from 04altaeventos where NUMERO_EVENTO = '".$filtro."' ";
		$variablequery = mysqli_query($conn,$variable);
		while($row = mysqli_fetch_array($variablequery, MYSQLI_ASSOC)){
			$resultado = $row['NOMBRE_EVENTO'];
		}
		return $resultado;
	}

	public function variable_DIRECCIONP1(){
		$conn = $this->db();
		$variablequery = "select * from 02direccionproveedor1 where idRelacion = '".$_SESSION['idPROV']."' ";
		$arrayquery = mysqli_query($conn,$variablequery);
		return $row = mysqli_fetch_array($arrayquery, MYSQLI_ASSOC);
	}

	public function variable_SUBETUFACTURA(){
		$conn = $this->db();
		$variablequery = "select * from 02SUBETUFACTURADOCTOS where idRelacion = '".$_SESSION['idPROV']."' and idTemporal = 'si' and (ADJUNTAR_FACTURA_XML is not null or ADJUNTAR_FACTURA_XML <> '') order by id desc ";
		$arrayquery = mysqli_query($conn,$variablequery);
		return $row = mysqli_fetch_array($arrayquery, MYSQLI_ASSOC);
	}

	public function revisar_ventasoperaciones(){
		$conn = $this->db();
		$var1 = 'select id from 02SUBETUFACTURA where idRelacion = "'.$_SESSION['idPROV'].'" ';
		$query = mysqli_query($conn,$var1) or die('P44'.mysqli_error($conn));
		$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
		return $row['id'];
	}

	public function guardarxmlDB($ultimo_id,$conn){
		$conexion2 = new herramientas();
		$regreso   = $this->variable_SUBETUFACTURA();
		$url       = __ROOT3__.'/includes/archivos/'.$regreso['ADJUNTAR_FACTURA_XML'];
		$session   = isset($_SESSION['idPROV']) ? $_SESSION['idPROV'] : '';
		$conexion2->guardar_db_xml($url,$session,$ultimo_id,$conn);
	}

	public function busca_02XML($ultimo_id){
		$conn = $this->db();
		$variablequery = "select * from 02XML where ultimo_id = '".$ultimo_id."' ";
		$arrayquery = mysqli_query($conn,$variablequery);
		return $row = mysqli_fetch_array($arrayquery, MYSQLI_ASSOC);
	}

	public function ActualizaxmlDB($FechaTimbrado, $tipoDeComprobante,
		$metodoDePago, $formaDePago, $condicionesDePago, $subTotal,
		$TipoCambio, $Moneda, $total, $serie,
		$folio, $LugarExpedicion, $rfcE, $nombreE,
		$regimenE, $rfcR, $nombreR, $UsoCFDI,
		$DomicilioFiscalReceptor, $RegimenFiscalReceptor, $UUID, $TImpuestosRetenidos,
		$TImpuestosTrasladados, $session, $ultimo_id, $TuaTotalCargos, $TUA, $Descuento, $Propina, $conn, $actualiza){

		$var3 = "update `02XML` set
		`Version` = 'no',
		`fechaTimbrado` = '".$FechaTimbrado."',
		`tipoDeComprobante` = '".$tipoDeComprobante."',
		`metodoDePago` = '".$metodoDePago."',
		`formaDePago` = '".$formaDePago."',
		`condicionesDePago` = '".$condicionesDePago."',
		`subTotal` = '".$subTotal."',
		`TipoCambio` = '".$TipoCambio."',
		`Moneda` = '".$Moneda."',
		`totalf` = '".$total."',
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
		where `ultimo_id` = '".$ultimo_id."';";

		$var4 = "INSERT INTO `02XML` (
		`id`, `Version`, `fechaTimbrado`, `tipoDeComprobante`,
		`metodoDePago`, `formaDePago`, `condicionesDePago`, `subTotal`,
		`TipoCambio`, `Moneda`, `totalf`, `serie`,
		`folio`, `LugarExpedicion`, `rfcE`, `nombreE`,
		`regimenE`, `rfcR`, `nombreR`, `UsoCFDI`,
		`DomicilioFiscalReceptor`, `RegimenFiscalReceptor`, `UUID`, `TImpuestosRetenidos`,
		`TImpuestosTrasladados`, `idRelacion`, `ultimo_id`, `TuaTotalCargos`, Descuento, `TUA`, `Propina`
		) VALUES (
		'', 'no', '".$FechaTimbrado."', '".$tipoDeComprobante."',
		'".$metodoDePago."', '".$formaDePago."', '".$condicionesDePago."', '".$subTotal."',
		'".$TipoCambio."', '".$Moneda."', '".$total."', '".$serie."',
		'".$folio."', '".$LugarExpedicion."', '".$rfcE."', '".$nombreE."',
		'".$regimenE."', '".$rfcR."', '".$nombreR."', '".$UsoCFDI."',
		'".$DomicilioFiscalReceptor."', '".$RegimenFiscalReceptor."', '".$UUID."', '".$TImpuestosRetenidos."',
		'".$TImpuestosTrasladados."', '".$session."', '".$ultimo_id."', '".$TuaTotalCargos."', '".$Descuento."', '".$TUA."', '".$Propina."'
		);";

		$row = $this->busca_02XML($ultimo_id);
		if($actualiza == 'true'){
			if($row['ultimo_id'] == 0 || $row['ultimo_id'] == ''){
				mysqli_query($conn,$var4) or die('P350'.mysqli_error($conn));
			}else{
				mysqli_query($conn,$var3) or die('P352'.mysqli_error($conn));
			}
		}
	}

	public function listado3(){
		$conn = $this->db();
		$var = 'select *,02usuarios.id AS IDDD from 02usuarios left join 02direccionproveedor1 on 02usuarios.id = 02direccionproveedor1.idRelacion order by nommbrerazon asc';
		return mysqli_query($conn,$var);
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

	public function ingresar_usuario($conn,$nommbrerazon){
		$queryrfc = "insert into 02direccionproveedor1 (P_NOMBRE_FISCAL_RS_EMPRESA) values ('".$nommbrerazon."'); ";
		mysqli_query($conn,$queryrfc) or die('P160'.mysqli_error($conn));
		return mysqli_insert_id($conn);
	}

	public function ingresar_rfc($conn,$RFC_PROVEEDOR,$idwebc){
		$queryrfc = "UPDATE 02direccionproveedor1 SET P_RFC_MTDP = '".$RFC_PROVEEDOR."', idRelacion = '".$idwebc."' WHERE id = '".$idwebc."' ";
		return mysqli_query($conn,$queryrfc);
	}

	public function variable_SUBETUFACTURA2($id12){
		$conn = $this->db();
		$variablequery = "select * from 02SUBETUFACTURADOCTOS where
		idRelacion = '".$id12."' and
		idTemporal = 'si' and (ADJUNTAR_FACTURA_XML is not null or ADJUNTAR_FACTURA_XML <> '') and
		idRelacionU = '".$_SESSION['idempermiso']."' and
		TIPOARCHIVO = 'xml'
		order by id desc ";
		$arrayquery = mysqli_query($conn,$variablequery);
		return $row = mysqli_fetch_array($arrayquery, MYSQLI_ASSOC);
	}

	public function borrar_xmls($ruta,$id,$nombrearchivo,$tabla1,$tabla2){
		$conn = $this->db();
		mysqli_query($conn, "delete FROM ".$tabla1." WHERE `ultimo_id` = '".$id."' ");
		$QUERYVAR2 = mysqli_query($conn,
			"SELECT * FROM ".$tabla2." WHERE `idTemporal` = '".$id."'
			and ADJUNTAR_FACTURA_XML <> '".$nombrearchivo."' and ADJUNTAR_FACTURA_XML <> ''")
			or die('P44'.mysqli_error($conn));
		while($row = mysqli_fetch_array($QUERYVAR2, MYSQLI_ASSOC)){
			if(file_exists($ruta.$row['ADJUNTAR_FACTURA_XML'])){ unlink($ruta.$row['ADJUNTAR_FACTURA_XML']); }
		}
		mysqli_query($conn,
			"DELETE FROM ".$tabla2." WHERE `idTemporal` = '".$id."'
			and ADJUNTAR_FACTURA_XML <> '".$nombrearchivo."' and ADJUNTAR_FACTURA_XML <>''")
			or die('P44'.mysqli_error($conn));
	}

	public function borrar_pdfs($ruta,$id,$nombrearchivo,$tabla1,$tabla2){
		$conn = $this->db();
		$QUERYVAR2 = mysqli_query($conn,
			"SELECT * FROM ".$tabla2." WHERE `idTemporal` = '".$id."'
			and ADJUNTAR_FACTURA_PDF <> '".$nombrearchivo."' and ADJUNTAR_FACTURA_PDF <> ''")
			or die('P44'.mysqli_error($conn));
		while($row = mysqli_fetch_array($QUERYVAR2, MYSQLI_ASSOC)){
			if(file_exists($ruta.$row['ADJUNTAR_FACTURA_PDF'])){ unlink($ruta.$row['ADJUNTAR_FACTURA_PDF']); }
		}
		mysqli_query($conn,
			"DELETE FROM ".$tabla2." WHERE `idTemporal` = '".$id."'
			and ADJUNTAR_FACTURA_PDF <> '".$nombrearchivo."' and ADJUNTAR_FACTURA_PDF <>''")
			or die('P44'.mysqli_error($conn));
	}

	public function busca_07XML2($ultimo_id,$tabla){
		$conn = $this->db();
		$variablequery = "select * from ".$tabla." where ultimo_id = '".$ultimo_id."' ";
		$arrayquery = mysqli_query($conn,$variablequery);
		return $row = mysqli_fetch_array($arrayquery, MYSQLI_ASSOC);
	}

	public function guardarxmlDB2($ultimo_id,$session,$tabla,$url){
		$conn     = $this->db();
		$conexion2 = new herramientas();
		if(!file_exists($url)){ return; }

		$regreso = $conexion2->lectorxml($url);

		$Version               = $regreso['Version'];
		$tipoDeComprobante     = $regreso['tipoDeComprobante'];
		$metodoDePago          = $regreso['metodoDePago'];
		$formaDePago           = $regreso['formaDePago'];
		$condicionesDePago     = $regreso['condicionesDePago'];
		$subTotal              = $regreso['subTotal'];
		$TipoCambio            = $regreso['TipoCambio'];
		$Moneda                = $regreso['Moneda'];
		$Descuento             = $regreso['Descuento'];
		$total                 = $regreso['total'];
		$serie                 = $regreso['serie'];
		$folio                 = $regreso['folio'];
		$LugarExpedicion       = $regreso['LugarExpedicion'];
		$DescripcionConcepto   = $regreso['DescripcionConcepto'];
		$rfcE                  = $regreso['rfcE'];
		$nombreE               = $regreso['nombreE'];
		$regimenE              = $regreso['regimenE'];
		$rfcR                  = $regreso['rfcR'];
		$nombreR               = $regreso['nombreR'];
		$UsoCFDI               = $regreso['UsoCFDI'];
		$DomicilioFiscalReceptor  = $regreso['DomicilioFiscalReceptor'];
		$RegimenFiscalReceptor    = $regreso['RegimenFiscalReceptor'];
		$UUID                  = $regreso['UUID'];
		$FechaTimbrado         = $regreso['FechaTimbrado'];
		$TImpuestosRetenidos   = $regreso['TImpuestosRetenidos'];
		$TImpuestosTrasladados = $regreso['TImpuestosTrasladados'];
		$Cantidad              = $regreso['Cantidad'];
		$ValorUnitario         = $regreso['ValorUnitario'];
		$Importe               = $regreso['Importe'];
		$ClaveProdServ         = $regreso['ClaveProdServ'];
		$Unidad                = $regreso['Unidad'];
		$Descripcion           = $regreso['Descripcion'];
		$ClaveUnidad           = $regreso['ClaveUnidad'];
		$NoIdentificacion      = $regreso['NoIdentificacion'];

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
		`totalf` = '".$total."',
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
		`Descuento` = '".$Descuento."',
		CantidadConcepto = '".$Cantidad."',
		ValorUnitarioConcepto = '".$ValorUnitario."',
		ImporteConcepto = '".$Importe."',
		ClaveProdServConcepto = '".$ClaveProdServ."',
		UnidadConcepto = '".$Unidad."',
		DescripcionConcepto = '".$Descripcion."',
		ClaveUnidadConcepto = '".$ClaveUnidad."',
		NoIdentificacionConcepto = '".$NoIdentificacion."',
		`TImpuestosRetenidos` = '".$TImpuestosRetenidos."',
		`TImpuestosTrasladados` = '".$TImpuestosTrasladados."'
		where `ultimo_id` = '".$ultimo_id."';";

		$var4 = "INSERT INTO ".$tabla." (
		`id`, `Version`, `fechaTimbrado`, `tipoDeComprobante`,
		`metodoDePago`, `formaDePago`, `condicionesDePago`, `subTotal`,
		`TipoCambio`, `Moneda`, `totalf`, `serie`,
		`folio`, `LugarExpedicion`, `rfcE`, `nombreE`,
		`regimenE`, `rfcR`, `nombreR`, `UsoCFDI`,
		`DomicilioFiscalReceptor`, `RegimenFiscalReceptor`, `UUID`, `TImpuestosRetenidos`,
		`TImpuestosTrasladados`, `idRelacion`, `ultimo_id`, `TuaTotalCargos`, Descuento, `TUA`, `Propina`,
		CantidadConcepto, ValorUnitarioConcepto, ImporteConcepto,
		ClaveProdServConcepto, UnidadConcepto, DescripcionConcepto,
		ClaveUnidadConcepto, NoIdentificacionConcepto
		) VALUES (
		'', '".$Version."', '".$FechaTimbrado."', '".$tipoDeComprobante."',
		'".$metodoDePago."', '".$formaDePago."', '".$condicionesDePago."', '".$subTotal."',
		'".$TipoCambio."', '".$Moneda."', '".$total."', '".$serie."',
		'".$folio."', '".$LugarExpedicion."', '".$rfcE."', '".$nombreE."',
		'".$regimenE."', '".$rfcR."', '".$nombreR."', '".$UsoCFDI."',
		'".$DomicilioFiscalReceptor."', '".$RegimenFiscalReceptor."', '".$UUID."', '".$TImpuestosRetenidos."',
		'".$TImpuestosTrasladados."', '".$session."', '".$ultimo_id."', '".$TuaTotalCargos."',
		'".$Descuento."', '".$TUA."', '".$Propina."',
		'".$Cantidad."', '".$ValorUnitario."', '".$Importe."',
		'".$ClaveProdServ."', '".$Unidad."', '".$Descripcion."',
		'".$ClaveUnidad."', '".$NoIdentificacion."'
		);";

		$row = $this->busca_07XML2($ultimo_id,$tabla);
		if($row['ultimo_id'] == 0 || $row['ultimo_id'] == ''){
			mysqli_query($conn,$var4) or die('P350'.mysqli_error($conn));
			echo "Ingresado";
		}else{
			mysqli_query($conn,$var3) or die('P352'.mysqli_error($conn));
			echo "Actualizado";
		}
	}

	public function verificar_usuario_comercial($conn,$nommbrerazon){
		$queryrfc = "SELECT * FROM 02direccionproveedor1 WHERE P_NOMBRE_COMERCIAL_EMPRESA = '".$nommbrerazon."' ";
		$arrayquery = mysqli_query($conn,$queryrfc);
		$row = mysqli_fetch_array($arrayquery, MYSQLI_ASSOC);
		return $row['id'];
	}

	public function revisar_pagoproveedor2($id){
		$conn = $this->db();
		$var1 = 'select id from 02SUBETUFACTURA where id = "'.$id.'" ';
		$query = mysqli_query($conn,$var1) or die('P44'.mysqli_error($conn));
		$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
		return $row['id'];
	}

	public function ventasyoperacionesP($NUMERO_CONSECUTIVO_PROVEE, $NOMBRE_COMERCIAL, $RAZON_SOCIAL, $VIATICOSOPRO,
		$RFC_PROVEEDOR, $NUMERO_EVENTO, $NOMBRE_EVENTO, $MOTIVO_GASTO, $CONCEPTO_PROVEE,
		$MONTO_TOTAL_COTIZACION_ADEUDO, $MONTO_DEPOSITAR, $MONTO_PROPINA,
		$FECHA_AUTORIZACION_RESPONSABLE, $FECHA_AUTORIZACION_AUDITORIA, $FECHA_DE_LLENADO,
		$MONTO_FACTURA, $TIPO_DE_MONEDA, $PFORMADE_PAGO, $FECHA_DE_PAGO, $FECHA_A_DEPOSITAR,
		$STATUS_DE_PAGO, $BANCO_ORIGEN, $MONTO_DEPOSITADO, $CLASIFICACION_GENERAL,
		$CLASIFICACION_ESPECIFICA, $PLACAS_VEHICULO, $MONTO_DE_COMISION, $POLIZA_NUMERO,
		$NOMBRE_DEL_EJECUTIVO, $NOMBRE_DEL_AYUDO, $OBSERVACIONES_1, $TIPO_CAMBIOP,
		$TOTAL_ENPESOS, $IMPUESTO_HOSPEDAJE, $TImpuestosRetenidosIVA, $TImpuestosRetenidosISR,
		$descuentos, $IVA, $ENVIARventasoper, $hiddenVENTASOPERACIONES, $IPventasoperar,
		$FechaTimbrado, $tipoDeComprobante,
		$metodoDePago, $formaDePago, $condicionesDePago, $subTotal,
		$TipoCambio, $Moneda, $total, $serie,
		$folio, $LugarExpedicion, $rfcE, $nombreE,
		$regimenE, $rfcR, $nombreR, $UsoCFDI,
		$DomicilioFiscalReceptor, $RegimenFiscalReceptor, $UUID, $TImpuestosRetenidos,
		$TImpuestosTrasladados, $TuaTotalCargos, $Descuento, $Propina, $TUA, $actualiza)
	{
		// Sanitizar montos
		foreach(array('MONTO_TOTAL_COTIZACION_ADEUDO','MONTO_DEPOSITAR','MONTO_FACTURA',
			'MONTO_PROPINA','MONTO_DEPOSITADO','MONTO_DE_COMISION','PENDIENTE_PAGO',
			'TOTAL_ENPESOS','TIPO_CAMBIOP','IMPUESTO_HOSPEDAJE','IVA') as $var){
			$$var = str_replace(',', '', $$var);
		}

		$conn = $this->db();

		$query_NOMBRE_COMERCIAL = mysqli_query($conn,
			"SELECT * FROM `02direccionproveedor1` where idRelacion = '".$NOMBRE_COMERCIAL."' ")
			or die('P160'.mysqli_error($conn));
		$row_NOMBRE_COMERCIAL = mysqli_fetch_array($query_NOMBRE_COMERCIAL, MYSQLI_ASSOC);
		$NOMBRE_COMERCIAL2    = $row_NOMBRE_COMERCIAL['P_NOMBRE_COMERCIAL_EMPRESA'];

		if($this->verificar_rfc($conn,$RFC_PROVEEDOR) != ''){
			$session = $this->verificar_rfc($conn,$RFC_PROVEEDOR);
		}elseif($this->verificar_usuario_comercial($conn,$NOMBRE_COMERCIAL2) != ''){
			$session = $this->verificar_usuario_comercial($conn,$NOMBRE_COMERCIAL2);
		}else{
			$session = 1;
		}

		$existe     = $this->revisar_pagoproveedor2($IPventasoperar);
		$registroId = $IPventasoperar;
		if($registroId == '' && $existe != ''){
			$registroId = $existe;
		}

		$idRelacionU    = isset($_SESSION['idempermiso']) ? $_SESSION['idempermiso'] : '';
		$idem           = isset($_SESSION['idem']) ? $_SESSION['idem'] : '';
		$usuarioBitacora = $this->nombre_usuario_bitacora();

		if($idem != ''){

			$var1 = "update 02SUBETUFACTURA set
			NUMERO_CONSECUTIVO_PROVEE = '".$NUMERO_CONSECUTIVO_PROVEE."',
			NOMBRE_COMERCIAL = '".$NOMBRE_COMERCIAL."',
			RAZON_SOCIAL = '".$RAZON_SOCIAL."',
			VIATICOSOPRO = '".$VIATICOSOPRO."',
			RFC_PROVEEDOR = '".$RFC_PROVEEDOR."',
			NUMERO_EVENTO = '".$NUMERO_EVENTO."',
			NOMBRE_EVENTO = '".$NOMBRE_EVENTO."',
			MOTIVO_GASTO = '".$MOTIVO_GASTO."',
			CONCEPTO_PROVEE = '".$CONCEPTO_PROVEE."',
			MONTO_TOTAL_COTIZACION_ADEUDO = '".$MONTO_TOTAL_COTIZACION_ADEUDO."',
			MONTO_DEPOSITAR = '".$MONTO_DEPOSITAR."',
			MONTO_PROPINA = '".$MONTO_PROPINA."',
			FECHA_AUTORIZACION_RESPONSABLE = '".$FECHA_AUTORIZACION_RESPONSABLE."',
			FECHA_AUTORIZACION_AUDITORIA = '".$FECHA_AUTORIZACION_AUDITORIA."',
			FECHA_DE_LLENADO = '".$FECHA_DE_LLENADO."',
			MONTO_FACTURA = '".$MONTO_FACTURA."',
			TIPO_DE_MONEDA = '".$TIPO_DE_MONEDA."',
			PFORMADE_PAGO = '".$PFORMADE_PAGO."',
			FECHA_DE_PAGO = '".$FECHA_DE_PAGO."',
			FECHA_A_DEPOSITAR = '".$FECHA_A_DEPOSITAR."',
			STATUS_DE_PAGO = '".$STATUS_DE_PAGO."',
			BANCO_ORIGEN = '".$BANCO_ORIGEN."',
			MONTO_DEPOSITADO = '".$MONTO_DEPOSITADO."',
			CLASIFICACION_GENERAL = '".$CLASIFICACION_GENERAL."',
			CLASIFICACION_ESPECIFICA = '".$CLASIFICACION_ESPECIFICA."',
			PLACAS_VEHICULO = '".$PLACAS_VEHICULO."',
			MONTO_DE_COMISION = '".$MONTO_DE_COMISION."',
			POLIZA_NUMERO = '".$POLIZA_NUMERO."',
			NOMBRE_DEL_EJECUTIVO = '".$NOMBRE_DEL_EJECUTIVO."',
			NOMBRE_DEL_AYUDO = '".$NOMBRE_DEL_AYUDO."',
			OBSERVACIONES_1 = '".$OBSERVACIONES_1."',
			TIPO_CAMBIOP = '".$TIPO_CAMBIOP."',
			TOTAL_ENPESOS = '".$TOTAL_ENPESOS."',
			IMPUESTO_HOSPEDAJE = '".$IMPUESTO_HOSPEDAJE."',
			TImpuestosRetenidosIVA = '".$TImpuestosRetenidosIVA."',
			TImpuestosRetenidosISR = '".$TImpuestosRetenidosISR."',
			descuentos = '".$descuentos."',
			IVA = '".$IVA."'
			where id = '".$registroId."';";

			$var2 = "insert into 02SUBETUFACTURA (
			NUMERO_CONSECUTIVO_PROVEE, NOMBRE_COMERCIAL, RAZON_SOCIAL, VIATICOSOPRO,
			RFC_PROVEEDOR, NUMERO_EVENTO, NOMBRE_EVENTO, MOTIVO_GASTO, CONCEPTO_PROVEE,
			MONTO_TOTAL_COTIZACION_ADEUDO, MONTO_DEPOSITAR, MONTO_PROPINA,
			FECHA_AUTORIZACION_RESPONSABLE, FECHA_AUTORIZACION_AUDITORIA, FECHA_DE_LLENADO,
			MONTO_FACTURA, TIPO_DE_MONEDA, PFORMADE_PAGO, FECHA_DE_PAGO, FECHA_A_DEPOSITAR,
			STATUS_DE_PAGO, BANCO_ORIGEN, MONTO_DEPOSITADO, CLASIFICACION_GENERAL,
			CLASIFICACION_ESPECIFICA, PLACAS_VEHICULO, MONTO_DE_COMISION, POLIZA_NUMERO,
			NOMBRE_DEL_EJECUTIVO, NOMBRE_DEL_AYUDO, OBSERVACIONES_1, TIPO_CAMBIOP,
			TOTAL_ENPESOS, IMPUESTO_HOSPEDAJE, TImpuestosRetenidosIVA, TImpuestosRetenidosISR,
			descuentos, IVA, idRelacion
			) values (
			'".$NUMERO_CONSECUTIVO_PROVEE."', '".$NOMBRE_COMERCIAL2."', '".$RAZON_SOCIAL."', '".$VIATICOSOPRO."',
			'".$RFC_PROVEEDOR."', '".$NUMERO_EVENTO."', '".$NOMBRE_EVENTO."', '".$MOTIVO_GASTO."', '".$CONCEPTO_PROVEE."',
			'".$MONTO_TOTAL_COTIZACION_ADEUDO."', '".$MONTO_DEPOSITAR."', '".$MONTO_PROPINA."',
			'".$FECHA_AUTORIZACION_RESPONSABLE."', '".$FECHA_AUTORIZACION_AUDITORIA."', '".$FECHA_DE_LLENADO."',
			'".$MONTO_FACTURA."', '".$TIPO_DE_MONEDA."', '".$PFORMADE_PAGO."', '".$FECHA_DE_PAGO."', '".$FECHA_A_DEPOSITAR."',
			'".$STATUS_DE_PAGO."', '".$BANCO_ORIGEN."', '".$MONTO_DEPOSITADO."', '".$CLASIFICACION_GENERAL."',
			'".$CLASIFICACION_ESPECIFICA."', '".$PLACAS_VEHICULO."', '".$MONTO_DE_COMISION."', '".$POLIZA_NUMERO."',
			'".$NOMBRE_DEL_EJECUTIVO."', '".$NOMBRE_DEL_AYUDO."', '".$OBSERVACIONES_1."', '".$TIPO_CAMBIOP."',
			'".$TOTAL_ENPESOS."', '".$IMPUESTO_HOSPEDAJE."', '".$TImpuestosRetenidosIVA."', '".$TImpuestosRetenidosISR."',
			'".$descuentos."', '".$IVA."', '".$session."'
			);";

			// ── ACTUALIZACIÓN ──────────────────────────────────────────────
			if($IPventasoperar != ''){

				$this->ActualizaxmlDB($FechaTimbrado, $tipoDeComprobante,
					$metodoDePago, $formaDePago, $condicionesDePago, $subTotal,
					$TipoCambio, $Moneda, $total, $serie,
					$folio, $LugarExpedicion, $rfcE, $nombreE,
					$regimenE, $rfcR, $nombreR, $UsoCFDI,
					$DomicilioFiscalReceptor, $RegimenFiscalReceptor, $UUID, $TImpuestosRetenidos,
					$TImpuestosTrasladados, $session, $registroId, $TuaTotalCargos, $TUA, $Descuento, $Propina, $conn, $actualiza);

				$consultaAnterior = mysqli_query($conn,
					"SELECT
						STATUS_DE_PAGO, MONTO_DEPOSITAR, FECHA_DE_PAGO, FECHA_A_DEPOSITAR, PFORMADE_PAGO,
						NUMERO_EVENTO, NOMBRE_EVENTO, NOMBRE_COMERCIAL, RAZON_SOCIAL, RFC_PROVEEDOR,
						MOTIVO_GASTO, CONCEPTO_PROVEE, MONTO_TOTAL_COTIZACION_ADEUDO, MONTO_FACTURA,
						MONTO_PROPINA, TIPO_DE_MONEDA, BANCO_ORIGEN, MONTO_DEPOSITADO,
						CLASIFICACION_GENERAL, CLASIFICACION_ESPECIFICA, MONTO_DE_COMISION,
						POLIZA_NUMERO, NOMBRE_DEL_EJECUTIVO, NOMBRE_DEL_AYUDO, OBSERVACIONES_1,
						TIPO_CAMBIOP, TOTAL_ENPESOS, IMPUESTO_HOSPEDAJE,
						TImpuestosRetenidosIVA, TImpuestosRetenidosISR, descuentos, IVA, VIATICOSOPRO
					FROM 02SUBETUFACTURA WHERE id = '".intval($registroId)."' LIMIT 1");
				$registroAnterior = $consultaAnterior ? mysqli_fetch_array($consultaAnterior, MYSQLI_ASSOC) : array();

				mysqli_query($conn,$var1) or die('P1561'.mysqli_error($conn));

				$mapaComparacion = array(
					'STATUS_DE_PAGO'                => $STATUS_DE_PAGO,
					'MONTO_DEPOSITAR'               => $MONTO_DEPOSITAR,
					'FECHA_DE_PAGO'                 => $FECHA_DE_PAGO,
					'FECHA_A_DEPOSITAR'             => $FECHA_A_DEPOSITAR,
					'PFORMADE_PAGO'                 => $PFORMADE_PAGO,
					'NUMERO_EVENTO'                 => $NUMERO_EVENTO,
					'NOMBRE_EVENTO'                 => $NOMBRE_EVENTO,
					'NOMBRE_COMERCIAL'              => $NOMBRE_COMERCIAL,
					'RAZON_SOCIAL'                  => $RAZON_SOCIAL,
					'RFC_PROVEEDOR'                 => $RFC_PROVEEDOR,
					'MOTIVO_GASTO'                  => $MOTIVO_GASTO,
					'CONCEPTO_PROVEE'               => $CONCEPTO_PROVEE,
					'MONTO_TOTAL_COTIZACION_ADEUDO' => $MONTO_TOTAL_COTIZACION_ADEUDO,
					'MONTO_FACTURA'                 => $MONTO_FACTURA,
					'MONTO_PROPINA'                 => $MONTO_PROPINA,
					'TIPO_DE_MONEDA'                => $TIPO_DE_MONEDA,
					'BANCO_ORIGEN'                  => $BANCO_ORIGEN,
					'MONTO_DEPOSITADO'              => $MONTO_DEPOSITADO,
					'CLASIFICACION_GENERAL'         => $CLASIFICACION_GENERAL,
					'CLASIFICACION_ESPECIFICA'      => $CLASIFICACION_ESPECIFICA,
					'MONTO_DE_COMISION'             => $MONTO_DE_COMISION,
					'POLIZA_NUMERO'                 => $POLIZA_NUMERO,
					'NOMBRE_DEL_EJECUTIVO'          => $NOMBRE_DEL_EJECUTIVO,
					'NOMBRE_DEL_AYUDO'              => $NOMBRE_DEL_AYUDO,
					'OBSERVACIONES_1'               => $OBSERVACIONES_1,
					'TIPO_CAMBIOP'                  => $TIPO_CAMBIOP,
					'TOTAL_ENPESOS'                 => $TOTAL_ENPESOS,
					'IMPUESTO_HOSPEDAJE'            => $IMPUESTO_HOSPEDAJE,
					'TImpuestosRetenidosIVA'        => $TImpuestosRetenidosIVA,
					'TImpuestosRetenidosISR'        => $TImpuestosRetenidosISR,
					'descuentos'                    => $descuentos,
					'IVA'                           => $IVA,
					'VIATICOSOPRO'                  => $VIATICOSOPRO,
				);

				$cambiosDetectados = array();
				foreach($mapaComparacion as $campo => $valorNuevo){
					$valorViejo = isset($registroAnterior[$campo]) ? $registroAnterior[$campo] : '';
					$viejoNorm  = trim((string)$valorViejo);
					$nuevoNorm  = trim((string)$valorNuevo);
					if($viejoNorm !== $nuevoNorm && !($viejoNorm === '' && $nuevoNorm === '0')){
						$cambiosDetectados[] = $this->etiqueta_bitacora_campo($campo).': "'.$viejoNorm.'" → "'.$nuevoNorm.'"';
					}
				}

				if(count($cambiosDetectados) > 0){
					$detalleActualizacion = 'Se actualizó VENTAS Y OPERACIONES. Cambios detectados: '.implode(' | ', $cambiosDetectados).'.';
				}else{
					$detalleActualizacion = 'Se guardó VENTAS Y OPERACIONES sin cambios detectados en los campos monitoreados.';
				}

				$this->registrar_bitacora($conn, $registroId, 'ACTUALIZACION', $detalleActualizacion, '', $usuarioBitacora);
				return "Actualizado";

			}else{

				mysqli_query($conn,$var2) or die('P160'.mysqli_error($conn));
				$ultimo_id = mysqli_insert_id($conn);

				$this->registrar_bitacora(
					$conn,
					$ultimo_id,
					'INGRESO',
					'Registro ingresado desde el módulo VENTAS Y OPERACIONES.',
					$usuarioBitacora,
					''
				);

				$regresourl = $this->variable_SUBETUFACTURA2($_SESSION['idPROV']);
				$url = __ROOT3__.'/includes/archivos/'.$regresourl['ADJUNTAR_FACTURA_XML'];
				ob_start();
				$this->guardarxmlDB2($ultimo_id,$_SESSION['idPROV'],'02XML',$url);
				ob_end_clean();
				mysqli_query($conn,
					"UPDATE 02SUBETUFACTURADOCTOS SET idTemporal ='".$ultimo_id."'
					where idRelacion = '".$_SESSION['idPROV']."' and idTemporal ='si' ");
				return "Ingresado";
			}

		}else{
			echo "NO HAY UN PROVEEDOR SELECCIONADO";
		}
	}

	public function borraventasoperaciones($id){
		$conn = $this->db();
		mysqli_query($conn, "DELETE FROM 02SUBETUFACTURA where id = '".$id."' ") or die('P44'.mysqli_error($conn));
		mysqli_query($conn, "DELETE FROM `02XML` WHERE `ultimo_id` = '".$id."' ") or die('P44'.mysqli_error($conn));
		mysqli_query($conn, "DELETE FROM `02SUBETUFACTURADOCTOS` WHERE `idTemporal` = '".$id."' ") or die('P44'.mysqli_error($conn));
		mysqli_query($conn, "DELETE FROM `02SUBETUFACTURA_BITACORA` WHERE `id_subetufactura` = '".$id."' ") or die('P44'.mysqli_error($conn));
		echo "ELEMENTO BORRADO";
	}

	public function getDoctos_subefactura($ID){
		$conn = $this->db();
		$sql = "SELECT COMPLEMENTOS_PAGO_PDF, COMPLEMENTOS_PAGO_XML
		FROM 02SUBETUFACTURADOCTOS
		WHERE idTemporal = '".mysqli_real_escape_string($conn,$ID)."'
		ORDER BY id DESC LIMIT 1";
		$query = mysqli_query($conn, $sql);
		return $query ? mysqli_fetch_array($query, MYSQLI_ASSOC) : null;
	}

	public function select_02XML(){
		$conn = $this->db();
		$variablequery = "select id from 02XML order by id desc ";
		$arrayquery = mysqli_query($conn,$variablequery);
		$row = mysqli_fetch_array($arrayquery, MYSQLI_ASSOC);
		return $row['id'];
	}

	// ── ÚNICA definición de VALIDA02XMLUUID ──────────────────────────────
	public function VALIDA02XMLUUID($uuid){
		$conn = $this->db();
		$variablequery = "select 02XML.id, 02XML.UUID, 02SUBETUFACTURA.NUMERO_CONSECUTIVO_PROVEE
		from 02XML
		left join 02SUBETUFACTURA on 02XML.ultimo_id = 02SUBETUFACTURA.id
		where 02XML.UUID = '".$uuid."' ";
		$arrayquery = mysqli_query($conn,$variablequery);
		$row = mysqli_fetch_array($arrayquery, MYSQLI_ASSOC);
		if($row['id'] == 0 || $row['id'] == ''){
			return 'S';
		}else{
			$numero = ($row['NUMERO_CONSECUTIVO_PROVEE'] != '') ? $row['NUMERO_CONSECUTIVO_PROVEE'] : $row['id'];
			return 'UUID_DUPLICADO:'.$numero;
		}
	}

	public function Listado_ventasoperaciones(){
		$conn = $this->db();
		return mysqli_query($conn, "select * from 02SUBETUFACTURA where idRelacion = '".$_SESSION['idPROV']."' order by id desc ");
	}



public function Listado_bitacora_pagoproveedor_array($idcomprobacion){
	$conn = $this->db();
	$idcomprobacion = intval($idcomprobacion);

	$crearTabla = "CREATE TABLE IF NOT EXISTS `02SUBETUFACTURA_BITACORA` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`id_subetufactura` int(11) NOT NULL DEFAULT 0,
		`tipo_movimiento` varchar(50) NOT NULL,
		`detalle` text,
		`fecha_hora` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		`nombre_quien_ingreso` varchar(255) DEFAULT NULL,
		`nombre_quien_actualizo` varchar(255) DEFAULT NULL,
		PRIMARY KEY (`id`),
		KEY `idx_id_subetufactura` (`id_subetufactura`),
		KEY `idx_fecha_hora` (`fecha_hora`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
	mysqli_query($conn, $crearTabla);

$variablequery = "SELECT 
		b.tipo_movimiento,
		b.detalle,
		b.fecha_hora,
		b.nombre_quien_ingreso,
		b.nombre_quien_actualizo,
		s.NUMERO_CONSECUTIVO_PROVEE,
		s.VIATICOSOPRO
	FROM 02SUBETUFACTURA_BITACORA b
	LEFT JOIN 02SUBETUFACTURA s ON s.id = b.id_subetufactura
	WHERE b.id_subetufactura = '".$idcomprobacion."'
	ORDER BY b.id DESC";

	$arrayquery = mysqli_query($conn, $variablequery);
	$resultado = array();

	if($arrayquery){
		while($row = mysqli_fetch_array($arrayquery, MYSQLI_ASSOC)){
			if(isset($row['fecha_hora']) && $row['fecha_hora'] != ''){
				$fechaBitacora = DateTime::createFromFormat('Y-m-d H:i:s', $row['fecha_hora'], new DateTimeZone('UTC'));
				if($fechaBitacora){
					$fechaBitacora->setTimezone(new DateTimeZone('America/Mexico_City'));
					$row['fecha_hora'] = $fechaBitacora->format('d/m/Y H:i:s');
				}
			}
			$resultado[] = $row;
		}
	}

	return $resultado;
}

	public function Listado_ventasoperaciones2($id){
		$conn = $this->db();
		return mysqli_query($conn, "select * from 02SUBETUFACTURA where id = '".$id."' ");
	}

	public function Listado_subefacturaDOCTOS($ID){
		$conn = $this->db();
		return mysqli_query($conn, "select * from 02SUBETUFACTURADOCTOS where idTemporal = '".$ID."' order by id desc ");
	}

	public function Listado_subefacturadocto($ADJUNTAR_COTIZACION){
		$conn = $this->db();

		$CIERRE_TOTAL11 = strtotime('-1 hours', strtotime(date("Y-m-d")));
		$nuevafecha2    = date('Y-m-d', $CIERRE_TOTAL11);

		mysqli_query($conn,
			"DELETE FROM 02SUBETUFACTURADOCTOS WHERE `fechaingreso` <= '".$nuevafecha2."'
			and idRelacion = '".$_SESSION['idPROV']."' and idTemporal = 'si'");

		return mysqli_query($conn,
			"select id,".$ADJUNTAR_COTIZACION.",fechaingreso from 02SUBETUFACTURADOCTOS
			where idRelacion = '".$_SESSION['idPROV']."' and idTemporal = 'si'
			and (".$ADJUNTAR_COTIZACION." is not null or ".$ADJUNTAR_COTIZACION." <> '')
			ORDER BY id DESC");
	}

	public function delete_subefacturadocto2($id){
		$conn = $this->db();
		$resultado = mysqli_query($conn, "SELECT idTemporal, ADJUNTAR_FACTURA_XML FROM 02SUBETUFACTURADOCTOS WHERE id = '".$id."' ");
		$row = mysqli_fetch_array($resultado, MYSQLI_ASSOC);
		if($row && $row['ADJUNTAR_FACTURA_XML'] != ''){
			mysqli_query($conn, "DELETE FROM 02XML WHERE ultimo_id = '".$row['idTemporal']."' ");
		}
		return mysqli_query($conn, "delete from 02SUBETUFACTURADOCTOS where id = '".$id."' ");
	}

	public function delete_subefactura2nombre($nombre){
		$conn = $this->db();
		mysqli_query($conn, "delete from 02SUBETUFACTURADOCTOS where ADJUNTAR_FACTURA_XML = '".$nombre."' ");
	}



	public function borrar_historico_xml($nombretabla,$idusuario){
		$conn = $this->db();
		$ruta = __ROOT3__;
		$QUERYVAR2 = mysqli_query($conn,
			"SELECT * FROM ".$nombretabla." WHERE `idRelacionU` = '".$idusuario."' and TIPOARCHIVO = 'xml' and idTemporal = 'si'")
			or die('P44'.mysqli_error($conn));
		while($row = mysqli_fetch_array($QUERYVAR2, MYSQLI_ASSOC)){
			if(file_exists($ruta.$row['ADJUNTAR_FACTURA_XML'])){ unlink($ruta.$row['ADJUNTAR_FACTURA_XML']); }
		}
		mysqli_query($conn,
			"DELETE FROM ".$nombretabla." WHERE `idRelacionU` = '".$idusuario."' and TIPOARCHIVO = 'xml' and idTemporal = 'si'")
			or die('P441'.mysqli_error($conn));
		mysqli_query($conn,
			"DELETE FROM ".$nombretabla." WHERE `idRelacionU` = '".$idusuario."' and idTemporal = 'si' and TIPOARCHIVO = 'OTR'")
			or die('P442'.mysqli_error($conn));
	}

	public function buscarNOMBRECOMERCIAL($filtro){
		$conn = $this->db();
		$variable = "select * from 02usuarios where nommbrerazon like '%".$filtro."%' limit 20 ";
		$variablequery = mysqli_query($conn,$variable);
		while($row2 = mysqli_fetch_array($variablequery, MYSQLI_ASSOC)){
			$resultado2[] = ['id'=>$row2['id'],'text'=>$row2['nommbrerazon']];
		}
		return $resultado2;
	}

	public function buscarrasonsocial($filtro){
		$conn = $this->db();
		$_SESSION['QUERYaaa'] = $variable = "select * from 02direccionproveedor1 where idRelacion = '".$filtro."' ";
		$variablequery = mysqli_query($conn,$variable);
		$row2 = mysqli_fetch_array($variablequery, MYSQLI_ASSOC);
		return $row2['P_NOMBRE_FISCAL_RS_EMPRESA'].'^^^'.$row2['P_RFC_MTDP'];
	}

	public function buscarNOMBRECOMERCIAL22($rfc){
		$conn = $this->db();
		$variable = "select *,02direccionproveedor1.idRelacion as idusuario from
		02direccionproveedor1 left join 02usuarios
		on 02direccionproveedor1.idRelacion = 02usuarios.id
		where P_RFC_MTDP ='".$rfc."' ";
		$variablequery = mysqli_query($conn,$variable);
		$row2 = mysqli_fetch_array($variablequery, MYSQLI_ASSOC);
		$_SESSION['idusuario12'] = $row2['idusuario'];
		$_SESSION['P_NOMBRE_COMERCIAL_EMPRESA12'] = $row2['P_NOMBRE_COMERCIAL_EMPRESA'];
		return $row2['idusuario'].'^^^^'.$row2['P_NOMBRE_COMERCIAL_EMPRESA'];
	}
	
	public function limpiarAdjuntoFacturaUnico($campo, $IPventasoperar, $idPROV){
    $conn  = $this->db();
    $ruta  = __ROOT3__.'/includes/archivos/';

    // Campos permitidos para evitar inyección SQL
    $camposPermitidos = array(
        'ADJUNTAR_FACTURA_XML',
        'ADJUNTAR_FACTURA_PDF',
        'ADJUNTAR_COTIZACION',
        'CONPROBANTE_TRANSFERENCIA',
    );
    if(!in_array($campo, $camposPermitidos)){ return; }

    // Buscar el registro actual de ese campo para ese registro
    $sql = "SELECT id, ".$campo." FROM 02SUBETUFACTURADOCTOS
            WHERE idTemporal = '".mysqli_real_escape_string($conn, $IPventasoperar)."'
            AND idRelacion   = '".mysqli_real_escape_string($conn, $idPROV)."'
            AND ".$campo."  <> ''
            AND ".$campo."  IS NOT NULL
            LIMIT 1";
    $q   = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($q, MYSQLI_ASSOC);

    if(!$row || $row['id'] == ''){ return; }

    // Borrar archivo físico
    $archivo = $ruta.$row[$campo];
    if(file_exists($archivo)){ unlink($archivo); }

    // Si es XML, borrar también el registro en 02XML
    if($campo === 'ADJUNTAR_FACTURA_XML'){
        mysqli_query($conn,
            "DELETE FROM 02XML WHERE ultimo_id = '".mysqli_real_escape_string($conn, $IPventasoperar)."' ");
    }

    // Borrar el registro de 02SUBETUFACTURADOCTOS
    mysqli_query($conn,
        "DELETE FROM 02SUBETUFACTURADOCTOS WHERE id = '".intval($row['id'])."' ");
}

public function ACTUALIZA_RECHAZADO($idcomprobacion, $estatusRechazado){

    $conn = $this->db();

    $session = isset($_SESSION['idem'])?$_SESSION['idem']:'';

    if($session != ''){

        $valorAnteriorRechazado  = $this->valor_actual_campo($conn, $idcomprobacion, 'STATUS_RECHAZADO');
        $valorAnteriorStatusPago = $this->valor_actual_campo($conn, $idcomprobacion, 'STATUS_DE_PAGO');

        $camposActualizar = "STATUS_RECHAZADO = '".$estatusRechazado."'";
        if($estatusRechazado === 'si'){
            $camposActualizar .= ", STATUS_DE_PAGO = 'RECHAZADO'";
            $nuevoStatusPago = 'RECHAZADO';
        } elseif($estatusRechazado === 'no'){
            $camposActualizar .= ", STATUS_DE_PAGO = 'SOLICITADO'";
            $nuevoStatusPago = 'SOLICITADO';
        }

        $var1 = "update 02SUBETUFACTURA SET ".$camposActualizar." WHERE id = '".$idcomprobacion."'";

        mysqli_query($conn,$var1) or die('P156'.mysqli_error($conn));

        // Bitácora: cambio de STATUS_RECHAZADO
        $detalleRechazado = 'Se actualizó '.$this->etiqueta_bitacora_campo('STATUS_RECHAZADO').' de "'.$valorAnteriorRechazado.'" a "'.$estatusRechazado.'".';
        $this->registrar_bitacora($conn, $idcomprobacion, 'ACTUALIZACION', $detalleRechazado, '', $this->nombre_usuario_bitacora());

        // Bitácora: cambio de STATUS_DE_PAGO (solo si realmente cambió)
        if(isset($nuevoStatusPago) && $valorAnteriorStatusPago !== $nuevoStatusPago){
            $detalleStatusPago = 'Se actualizó '.$this->etiqueta_bitacora_campo('STATUS_DE_PAGO').' de "'.$valorAnteriorStatusPago.'" a "'.$nuevoStatusPago.'".';
            $this->registrar_bitacora($conn, $idcomprobacion, 'ACTUALIZACION', $detalleStatusPago, '', $this->nombre_usuario_bitacora());
        }

        return "Actualizado^".$estatusRechazado;

    }else{

        echo "NO HAY UN PROVEEDOR SELECCIONADO";

    }

}

private function valor_actual_campo($conn, $idcomprobacion, $campo){
    $camposPermitidos = array('STATUS_RECHAZADO', 'STATUS_DE_PAGO');
    if(!in_array($campo, $camposPermitidos, true)){ return ''; }

    $id = intval($idcomprobacion);
    $query = mysqli_query($conn, "SELECT ".$campo." AS valor FROM 02SUBETUFACTURA WHERE id = '".$id."' LIMIT 1");
    if($query){
        $row = mysqli_fetch_assoc($query);
        if($row && isset($row['valor'])){ return $row['valor']; }
    }
    return '';
}

}
?>
