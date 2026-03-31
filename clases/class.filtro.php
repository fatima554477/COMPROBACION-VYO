<?php
/**
 	--------------------------
	Autor: Sandor Matamoros
	Programer: Fatima Arellano
	Propietario: EPC
	fecha sandor: 
    fecha fatis : 05/04/2025
	----------------------------
 
*/

define("__ROOT1__", dirname(dirname(__FILE__)));
	include_once (__ROOT1__."/../includes/error_reporting.php");
	include_once (__ROOT1__."/../pagoproveedores/class.epcinnPP.php");

class orders extends accesoclase {
        public $mysqli;
        public $counter;//Propiedad para almacenar el numero de registro devueltos por la consulta

        function __construct(){
                $this->mysqli = $this->db();
    }

        /**
         * Obtiene la configuración de visibilidad de un campo usando caché en memoria
         * para evitar consultas repetidas a la base de datos durante la misma petición.
         */
        public function plantilla_filtro($nombreTabla, $campo, $altaeventos, $DEPARTAMENTO) {
                static $cache = [];
                $cacheKey = $nombreTabla.'|'.$campo.'|'.$altaeventos.'|'.$DEPARTAMENTO;

                if (array_key_exists($cacheKey, $cache)) {
                        return $cache[$cacheKey];
                }

                $parentClass = get_parent_class($this);
                if ($parentClass && is_callable([$parentClass, 'plantilla_filtro'])) {
                        $cache[$cacheKey] = parent::plantilla_filtro($nombreTabla, $campo, $altaeventos, $DEPARTAMENTO);
                } else {
                        $cache[$cacheKey] = '';
                }

                return $cache[$cacheKey];
        }

        private function isValidRfc($rfc){
                $cleanRfc = trim((string)$rfc);
                return $cleanRfc !== '' && preg_match('/^[A-Z&Ñ]{3,4}[0-9]{6}[A-Z0-9]{3}$/i', $cleanRfc);
        }

public function datos_bancarios_xml($rfc, $idRelacion = null, $nombreComercial = null){
    // ✅ CACHÉ
    static $cache = [];
    $cacheKey = $rfc . '|' . $idRelacion . '|' . $nombreComercial;
    if (array_key_exists($cacheKey, $cache)) {
        return $cache[$cacheKey];
    }

    $conn = $this->db();
    $filtros = [];

    if($this->isValidRfc($rfc)){
        $valueRfc = mysqli_real_escape_string($conn, strtoupper($rfc));
        $filtros[] = "P_RFC_MTDP = '".$valueRfc."'";
    }

    $nombreComercial = trim((string)$nombreComercial);
    if($nombreComercial !== ''){
        $valueNombre = mysqli_real_escape_string($conn, $nombreComercial);
        $filtros[] = "02direccionproveedor1.P_NOMBRE_COMERCIAL_EMPRESA = '".$valueNombre."'";
    }

    if(is_numeric($idRelacion)){
        $filtros[] = "02usuarios.id = '".intval($idRelacion)."'";
    }

    if(empty($filtros)){
        $cache[$cacheKey] = null;
        return null;
    }

    $variable = "SELECT 02DATOSBANCARIOS1.idRelacion AS idRelacion FROM 02usuarios "
        ."LEFT JOIN 02direccionproveedor1 ON 02usuarios.id = 02direccionproveedor1.idRelacion "
        ."LEFT JOIN 02DATOSBANCARIOS1 ON 02DATOSBANCARIOS1.idRelacion = 02usuarios.id "
        ."WHERE ".implode(' AND ', $filtros)." "
        ."ORDER BY 02DATOSBANCARIOS1.checkbox = 'si' DESC, 02DATOSBANCARIOS1.id DESC LIMIT 1";

    $query = mysqli_query($conn, $variable);
    $row   = mysqli_fetch_array($query, MYSQLI_ASSOC);

    // ✅ Guardar en caché antes de retornar
    $cache[$cacheKey] = $row ? $row['idRelacion'] : null;
    return $cache[$cacheKey];
}

public function datos_bancarios_todo($idRelacion, $nombreComercial = null){
    // ✅ CACHÉ
    static $cache = [];
    $cacheKey = $idRelacion . '|' . $nombreComercial;
    if (array_key_exists($cacheKey, $cache)) {
        return $cache[$cacheKey];
    }

    $conn = $this->db();
    $filtros = [];

    if(is_numeric($idRelacion)){
        $filtros[] = "02DATOSBANCARIOS1.idRelacion = '".intval($idRelacion)."'";
    }

    $nombreComercial = trim((string)$nombreComercial);
    if($nombreComercial !== ''){
        $valueNombre = mysqli_real_escape_string($conn, $nombreComercial);
        $filtros[] = "02direccionproveedor1.P_NOMBRE_COMERCIAL_EMPRESA = '".$valueNombre."'";
    }

    if(empty($filtros)){
        $cache[$cacheKey] = [];
        return [];
    }

    $variable2 = "SELECT 02DATOSBANCARIOS1.* FROM 02DATOSBANCARIOS1 "
        ."LEFT JOIN 02usuarios ON 02usuarios.id = 02DATOSBANCARIOS1.idRelacion "
        ."LEFT JOIN 02direccionproveedor1 ON 02usuarios.id = 02direccionproveedor1.idRelacion "
        ."WHERE (".implode(' AND ', $filtros).") "
        ."AND 02DATOSBANCARIOS1.checkbox = 'si' ORDER BY 02DATOSBANCARIOS1.id DESC LIMIT 1";

    $query2 = mysqli_query($conn, $variable2);
    $row2   = mysqli_fetch_array($query2, MYSQLI_ASSOC);

    // ✅ Guardar en caché antes de retornar
    $cache[$cacheKey] = $row2 ? $row2 : [];
    return $cache[$cacheKey];
}
   

public function DOCUMENTOSFISCALES_PAGOA($idRelacion, $documento, $documento2 = FALSE){
    // ✅ CACHÉ
    static $cache = [];
    $cacheKey = $idRelacion . '|' . $documento . '|' . $documento2;
    if (array_key_exists($cacheKey, $cache)) {
        return $cache[$cacheKey];
    }

    $conn = $this->db();
    $variable2 = "SELECT * FROM 02DOCUMENTOSFISCALES WHERE idRelacion = '".$idRelacion."' AND "
        ."(DOCUMENTO_LEGAL = '".$documento."' OR DOCUMENTO_LEGAL = '".$documento2."') LIMIT 1";
    $query2 = mysqli_query($conn, $variable2);

    $ADJUNTAR_DOCUMENTO_LEGAL = "";
    while($row2 = mysqli_fetch_array($query2, MYSQLI_ASSOC)){
        if($row2['ADJUNTAR_DOCUMENTO_LEGAL'] != 2 ||
           $row2['ADJUNTAR_DOCUMENTO_LEGAL'] != '' ||
           $row2['ADJUNTAR_DOCUMENTO_LEGAL'] != 1){
            $ADJUNTAR_DOCUMENTO_LEGAL .= "<a target='_blank' href='includes/archivos/".$row2['ADJUNTAR_DOCUMENTO_LEGAL']."'>ver</a><br>";
        } else {
            $ADJUNTAR_DOCUMENTO_LEGAL .= "<br>";
        }
    }

    // ✅ Guardar en caché antes de retornar
    $cache[$cacheKey] = $ADJUNTAR_DOCUMENTO_LEGAL;
    return $cache[$cacheKey];
}
	
	
	
	//STATUS_EVENTO,NOMBRE_CORTO_EVENTO,NOMBRE_EVENTO
	public function getData($tables3,$campos,$search){
		$offset=$search['offset'];
		$per_page=$search['per_page'];
	    $tables = '02SUBETUFACTURA';
		$tables2 = "(

			SELECT x.*

			FROM 02XML x

			INNER JOIN (

				SELECT ultimo_id, MAX(id) AS max_id

				FROM 02XML

				GROUP BY ultimo_id

			) xu ON x.id = xu.max_id

		) AS 02XML";

		$tables5 = '04altaeventos';	
        $tables4 = '02DATOSBANCARIOS1';			
		//$sWhereCC ="  02XML.`idRelacion` = 02SUBETUFACTURA.id AND ";
		$joinAltaEventos = " LEFT JOIN $tables5 ON $tables.NUMERO_EVENTO = $tables5.NUMERO_EVENTO AND $tables.NOMBRE_EVENTO = $tables5.NOMBRE_EVENTO ";
		$sWhereCC =" ON 02SUBETUFACTURA.id = 02XML.`ultimo_id` ".$joinAltaEventos;
		$sWhere2="";$sWhere3="";
		
		if($search['NUMERO_CONSECUTIVO_PROVEE']!=""){
			$sWhere2.="  $tables.NUMERO_CONSECUTIVO_PROVEE LIKE '%".$search['NUMERO_CONSECUTIVO_PROVEE']."%' and ";}
		if($search['NOMBRE_COMERCIAL']!=""){
			$sWhere2.="  $tables.NOMBRE_COMERCIAL LIKE '%".$search['NOMBRE_COMERCIAL']."%' and ";}
		if($search['RAZON_SOCIAL']!=""){
			$sWhere2.="  $tables.RAZON_SOCIAL LIKE '%".$search['RAZON_SOCIAL']."%' and ";}
		if($search['RFC_PROVEEDOR']!=""){
			$sWhere2.="  $tables.RFC_PROVEEDOR = '".$search['RFC_PROVEEDOR']."' and ";}
		if($search['VIATICOSOPRO']!=""){
			$sWhere2.="  $tables.VIATICOSOPRO = '".$search['VIATICOSOPRO']."' and ";}
		if($search['NUMERO_EVENTO']!=""){
			$sWhere2.="  $tables.NUMERO_EVENTO LIKE '%".$search['NUMERO_EVENTO']."%' and ";}
		if($search['NOMBRE_EVENTO']!=""){
			$sWhere2.="  $tables.NOMBRE_EVENTO LIKE '%".$search['NOMBRE_EVENTO']."%' and ";}
			
				if($search['FECHA_INICIO_EVENTO']!=""){
			$sWhere2.="  $tables5.FECHA_INICIO_EVENTO LIKE '%".$search['FECHA_INICIO_EVENTO']."%' and ";}
			
		if($search['FECHA_FINAL_EVENTO']!=""){
			$sWhere2.="  $tables5.FECHA_FINAL_EVENTO LIKE '%".$search['FECHA_FINAL_EVENTO']."%' and ";}			
			
			
		if($search['MOTIVO_GASTO']!=""){
			$sWhere2.="  $tables.MOTIVO_GASTO LIKE '%".$search['MOTIVO_GASTO']."%' and ";}
		if($search['CONCEPTO_PROVEE']!=""){
			$sWhere2.="  $tables.CONCEPTO_PROVEE LIKE '%".$search['CONCEPTO_PROVEE']."%' and ";}
		if($search['MONTO_TOTAL_COTIZACION_ADEUDO']!=""){
			$sWhere2.="  $tables.MONTO_TOTAL_COTIZACION_ADEUDO LIKE '%".$search['MONTO_TOTAL_COTIZACION_ADEUDO']."%' and ";}

		if($search['MONTO_FACTURA']!=""){
			$MONTO_FACTURA = str_replace(',','',str_replace('$','',$search['MONTO_FACTURA']));
			$sWhere2.="  $tables.MONTO_FACTURA LIKE '%".$MONTO_FACTURA."%' and ";}

		if($search['MONTO_PROPINA']!=""){
			$MONTO_PROPINA = str_replace(',','',str_replace('$','',$search['MONTO_PROPINA']));
			$sWhere2.="  $tables.MONTO_PROPINA LIKE '%".$MONTO_PROPINA ."%' and ";}

		if($search['MONTO_DEPOSITAR']!=""){
			$MONTO_DEPOSITAR = str_replace(',','',str_replace('$','',$search['MONTO_DEPOSITAR']));
			$sWhere2.="  $tables.MONTO_DEPOSITAR LIKE '%".$MONTO_DEPOSITAR."%' and ";
		}
		if($search['IVA']!=""){
			$sWhere2.="  $tables.IVA LIKE '%".$search['IVA']."%' and ";}

		if($search['IEPS']!=""){
			$sWhere2.="  $tables.IEPS LIKE '%".$search['IEPS']."%' and ";}

		if($search['MONTO_DEPOSITADO']!=""){
			$MONTO_DEPOSITADO = str_replace(',','',str_replace('$','',$search['MONTO_DEPOSITADO']));	
			$sWhere2.="  $tables.MONTO_DEPOSITADO LIKE '%".$search['MONTO_DEPOSITADO']."%' and ";}

		if($search['TIPO_DE_MONEDA']!=""){
			$sWhere2.="  $tables.TIPO_DE_MONEDA LIKE '%".$search['TIPO_DE_MONEDA']."%' and ";}
		if($search['PFORMADE_PAGO']!=""){
			$sWhere2.="  $tables.PFORMADE_PAGO LIKE '%".$search['PFORMADE_PAGO']."%' and ";}

   if(isset($search['FECHA_DE_PAGO_VACIO']) && $search['FECHA_DE_PAGO_VACIO']!==""){
   $sWhere2.=" ($tables.FECHA_DE_PAGO IS NULL OR $tables.FECHA_DE_PAGO = '' OR $tables.FECHA_DE_PAGO = '0000-00-00') and ";
   }elseif($search['FECHA_DE_PAGO']!="" and $search['FECHA_DE_PAGO2a']!=""){
   //BETWEEN '2022-01-12' AND '2022-01-22' DATE(`ribono_tabla`.fechaamazon) 	
   $sWhere2.=" $tables.FECHA_DE_PAGO BETWEEN 
   '".$search['FECHA_DE_PAGO']."' and '".$search['FECHA_DE_PAGO2a']."'  and ";
   }elseif($search['FECHA_DE_PAGO']!=""){

   $sWhere2.=" $tables.FECHA_DE_PAGO LIKE '%".$search['FECHA_DE_PAGO']."%' and ";

   }elseif($search['FECHA_DE_PAGO2a']!=""){

   $sWhere2.=" $tables.FECHA_DE_PAGO LIKE '%".$search['FECHA_DE_PAGO2a']."%' and ";

   }

		if($search['FECHA_A_DEPOSITAR']!=""){
			$sWhere2.="  $tables.FECHA_A_DEPOSITAR LIKE '%".$search['FECHA_A_DEPOSITAR']."%' and ";}
		if($search['STATUS_DE_PAGO']!=""){
			$sWhere2.="  $tables.STATUS_DE_PAGO LIKE '%".$search['STATUS_DE_PAGO']."%' and ";}
		if($search['ACTIVO_FIJO']!=""){
			$sWhere2.="  $tables.ACTIVO_FIJO LIKE '%".$search['ACTIVO_FIJO']."%' and ";}
		if($search['GASTO_FIJO']!=""){
			$sWhere2.="  $tables.GASTO_FIJO LIKE '%".$search['GASTO_FIJO']."%' and ";}
		if($search['PAGAR_CADA']!=""){
			$sWhere2.="  $tables.PAGAR_CADA LIKE '%".$search['PAGAR_CADA']."%' and ";}
		if($search['FECHA_PPAGO']!=""){
			$sWhere2.="  $tables.FECHA_PPAGO LIKE '%".$search['FECHA_PPAGO']."%' and ";}
		if($search['FECHA_TPROGRAPAGO']!=""){
			$sWhere2.="  $tables.FECHA_TPROGRAPAGO LIKE '%".$search['FECHA_TPROGRAPAGO']."%' and ";}
		if($search['NUMERO_EVENTOFIJO']!=""){
			$sWhere2.="  $tables.NUMERO_EVENTOFIJO LIKE '%".$search['NUMERO_EVENTOFIJO']."%' and ";}
		if($search['CLASI_GENERAL']!=""){
			$sWhere2.="  $tables.CLASI_GENERAL LIKE '%".$search['CLASI_GENERAL']."%' and ";}
		if($search['SUB_GENERAL']!=""){
			$sWhere2.="  $tables.SUB_GENERAL LIKE '%".$search['SUB_GENERAL']."%' and ";}
		if($search['NUMERO_EVENTO1']!=""){
			$sWhere2.="  $tables.NUMERO_EVENTO1 = '".$search['NUMERO_EVENTO1']."' and ";}
		if($search['CLASIFICACION_GENERAL']!=""){
			$sWhere2.="  $tables.CLASIFICACION_GENERAL LIKE '%".$search['CLASIFICACION_GENERAL']."%' and ";}
		if($search['CLASIFICACION_ESPECIFICA']!=""){
			$sWhere2.="  $tables.CLASIFICACION_ESPECIFICA LIKE '%".$search['CLASIFICACION_ESPECIFICA']."%' and ";}
		if($search['PLACAS_VEHICULO']!=""){
			$sWhere2.="  $tables.PLACAS_VEHICULO LIKE '%".$search['PLACAS_VEHICULO']."%' and ";}
		if($search['MONTO_DE_COMISION']!=""){
			$sWhere2.="  $tables.MONTO_DE_COMISION = '".$search['MONTO_DE_COMISION']."' and ";}
		if($search['POLIZA_NUMERO']!=""){
			$sWhere2.="  $tables.POLIZA_NUMERO LIKE '%".$search['POLIZA_NUMERO']."%' and ";}
		if($search['NOMBRE_DEL_EJECUTIVO']!=""){
			$sWhere2.="  $tables.NOMBRE_DEL_EJECUTIVO LIKE '%".$search['NOMBRE_DEL_EJECUTIVO']."%' and ";}
		if($search['NOMBRE_DEL_AYUDO']!=""){
			$sWhere2.="  $tables.NOMBRE_DEL_AYUDO LIKE '%".$search['NOMBRE_DEL_AYUDO']."%' and ";}
		if($search['OBSERVACIONES_1']!=""){
			$sWhere2.="  $tables.OBSERVACIONES_1 LIKE '%".$search['OBSERVACIONES_1']."%' and ";}
		if($search['FECHA_DE_LLENADO']!=""){
			$sWhere2.="  $tables.FECHA_DE_LLENADO LIKE '%".$search['FECHA_DE_LLENADO']."%' and ";}
		if($search['ID_RELACIONADO']!=""){
			$sWhere2.="  $tables.ID_RELACIONADO LIKE '%".$search['ID_RELACIONADO']."%' and ";}
		if($search['hiddenpagoproveedores']!=""){
			$sWhere2.="  $tables.hiddenpagoproveedores LIKE '%".$search['hiddenpagoproveedores']."%' and ";}
		if($search['TIPO_CAMBIOP']!=""){
			$sWhere2.="  $tables.TIPO_CAMBIOP LIKE '%".$search['TIPO_CAMBIOP']."%' and ";}
		if($search['TOTAL_ENPESOS']!=""){
			$sWhere2.="  $tables.TOTAL_ENPESOS LIKE '%".$search['TOTAL_ENPESOS']."%' and ";}
		if($search['TImpuestosRetenidosIVA']!=""){
			$sWhere2.="  $tables.TImpuestosRetenidosIVA LIKE '%".$search['TImpuestosRetenidosIVA']."%' and ";}
		if($search['TImpuestosRetenidosISR']!=""){
			$sWhere2.="  $tables.TImpuestosRetenidosISR LIKE '%".$search['TImpuestosRetenidosISR']."%' and ";}
		if($search['descuentos']!=""){
			$sWhere2.="  $tables.descuentos LIKE '%".$search['descuentos']."%' and ";}

		/////////////////////////////nuevo//////////////////////////
		if($search['P_TIPO_DE_MONEDA_1']!=""){
			$sWhere2.="  $tables4.P_TIPO_DE_MONEDA_1 LIKE '%".$search['P_TIPO_DE_MONEDA_1']."%' and ";}
		if($search['P_INSTITUCION_FINANCIERA_1']!=""){
			$sWhere2.="  $tables4.P_INSTITUCION_FINANCIERA_1 LIKE '%".$search['P_INSTITUCION_FINANCIERA_1']."%' and ";}
		if($search['P_NUMERO_DE_CUENTA_DB_1']!=""){
			$sWhere2.="  $tables4.P_NUMERO_DE_CUENTA_DB_1 LIKE '%".$search['P_NUMERO_DE_CUENTA_DB_1']."%' and ";}
		if($search['P_NUMERO_CLABE_1']!=""){
			$sWhere2.="  $tables4.P_NUMERO_CLABE_1 LIKE '%".$search['P_NUMERO_CLABE_1']."%' and ";}
		if($search['P_NUMERO_IBAN_1']!=""){
			$sWhere2.="  $tables4.P_NUMERO_IBAN_1 LIKE '%".$search['P_NUMERO_IBAN_1']."%' and ";}
		if($search['P_NUMERO_CUENTA_SWIFT_1']!=""){
			$sWhere2.="  $tables4.P_NUMERO_CUENTA_SWIFT_1 LIKE '%".$search['P_NUMERO_CUENTA_SWIFT_1']."%' and ";}
		if($search['FOTO_ESTADO_PROVEE']!=""){
			$sWhere2.="  $tables4.FOTO_ESTADO_PROVEE LIKE '%".$search['FOTO_ESTADO_PROVEE']."%' and ";}
		if($search['ULTIMA_CARGA_DATOBANCA']!=""){
			$sWhere2.="  $tables4.ULTIMA_CARGA_DATOBANCA LIKE '%".$search['ULTIMA_CARGA_DATOBANCA']."%' and ";}

		if($search['UUID']!=""){
			$sWhere2.="  $tables2.UUID = '".$search['UUID']."' and ";}
		if($search['metodoDePago']!=""){
			$sWhere2.="  $tables2.metodoDePago = '".$search['metodoDePago']."' and ";}
		if($search['totalf']!=""){
			$totalf = str_replace(',','',str_replace('$','',$search['totalf']));
			$sWhere2.="  $tables2.totalf = '".$totalf."' and ";}
		if($search['serie']!=""){
			$sWhere2.="  $tables2.serie = '".$search['serie']."' and ";}
		if($search['folio']!=""){
			$sWhere2.="  $tables2.folio = '".$search['folio']."' and ";}
		if($search['regimenE']!=""){
			$sWhere2.="  $tables2.regimenE = '".$search['regimenE']."' and ";}
		if($search['UsoCFDI']!=""){
			$sWhere2.="  $tables2.UsoCFDI = '".$search['UsoCFDI']."' and ";}
		if($search['TImpuestosTrasladados']!=""){
			$TImpuestosTrasladados = str_replace(',','',str_replace('$','',$search['TImpuestosTrasladados']));
			$sWhere2.="  $tables2.TImpuestosTrasladados = ".$TImpuestosTrasladados." and ";}
		if($search['TImpuestosRetenidos']!=""){
			$TImpuestosRetenidos = str_replace(',','',str_replace('$','',$search['TImpuestosRetenidos']));
			$sWhere2.="  $tables2.TImpuestosRetenidos = ".$TImpuestosRetenidos." and ";}
		if($search['Version']!=""){
			$sWhere2.="  $tables2.Version = '".$search['Version']."' and ";}
		if($search['tipoDeComprobante']!=""){
			$sWhere2.="  $tables2.tipoDeComprobante = '".$search['tipoDeComprobante']."' and ";}
		if($search['condicionesDePago']!=""){
			$sWhere2.="  $tables2.condicionesDePago = '".$search['condicionesDePago']."' and ";}
		if($search['fechaTimbrado']!=""){
			$sWhere2.="  $tables2.fechaTimbrado = '".$search['fechaTimbrado']."' and ";}
		if($search['nombreR']!=""){
			$sWhere2.="  $tables2.nombreR = '".$search['nombreR']."' and ";}
		if($search['rfcR']!=""){
			$sWhere2.="  $tables2.rfcR = '".$search['rfcR']."' and ";}
		if($search['Moneda']!=""){
			$sWhere2.="  $tables2.Moneda = '".$search['Moneda']."' and ";}
		if($search['TipoCambio']!=""){
			$sWhere2.="  $tables2.TipoCambio = '".$search['TipoCambio']."' and ";}
		if($search['ValorUnitarioConcepto']!=""){
			$sWhere2.="  $tables2.ValorUnitarioConcepto = '".$search['ValorUnitarioConcepto']."' and ";}
		if($search['DescripcionConcepto']!=""){
			$sWhere2.="  $tables2.DescripcionConcepto like '%".$search['DescripcionConcepto']."%' and ";}
		if($search['ClaveUnidadConcepto']!=""){
			$sWhere2.="  $tables2.ClaveUnidadConcepto like '%".$search['ClaveUnidadConcepto']."%' and ";}
		if($search['ClaveProdServConcepto']!=""){
			$sWhere2.="  $tables2.ClaveProdServConcepto = '".$search['ClaveProdServConcepto']."' and ";}
		if($search['RFC_RECEPTOR']!=""){
			$sWhere2.="  $tables2.RFC_RECEPTOR = '".$search['RFC_RECEPTOR']."' and ";}
		if($search['CantidadConcepto']!=""){
			$sWhere2.="  $tables2.CantidadConcepto = '".$search['CantidadConcepto']."' and ";}
		if($search['ImporteConcepto']!=""){
			$sWhere2.="  $tables2.ImporteConcepto = '".$search['ImporteConcepto']."' and ";}
		if($search['UnidadConcepto']!=""){
			$sWhere2.="  $tables2.UnidadConcepto = '".$search['UnidadConcepto']."' and ";}
		if($search['TUA']!=""){
			$TUA = str_replace(',','',str_replace('$','',$search['TUA']));
			$sWhere2.="  $tables2.TUA = '".$TUA."' and ";}
		if($search['TuaTotalCargos']!=""){
			$TuaTotalCargos = str_replace(',','',str_replace('$','',$search['TuaTotalCargos']));
			$sWhere2.="  $tables2.TuaTotalCargos = '".$TuaTotalCargos."' and ";}
		if($search['IVAXML']!=""){
			$IVAXML = str_replace(',','',str_replace('$','',$search['IVAXML']));
			$sWhere2.="  $tables2.IVAXML = '".$IVAXML."' and ";}
		if($search['IEPSXML']!=""){
			$IEPSXML = str_replace(',','',str_replace('$','',$search['IEPSXML']));
			$sWhere2.="  $tables2.IEPSXML = '".$IEPSXML."' and ";}
		if($search['Descuento']!=""){
			$Descuento = str_replace(',','',str_replace('$','',$search['Descuento']));
			$sWhere2.="  $tables2.Descuento = '".$Descuento."' and ";}
		if($search['subTotal']!=""){
			$subTotal = str_replace(',','',str_replace('$','',$search['subTotal']));
			$sWhere2.="  $tables2.subTotal = '".$subTotal."' and ";}
		if($search['IMPUESTO_HOSPEDAJE']!=""){
			$IMPUESTO_HOSPEDAJE = str_replace(',','',str_replace('$','',$search['IMPUESTO_HOSPEDAJE']));
			$sWhere2.="  $tables2.IMPUESTO_HOSPEDAJE = '".$IMPUESTO_HOSPEDAJE."' and ";}
		if($search['propina']!=""){
			$propina = str_replace(',','',str_replace('$','',$search['propina']));
			$sWhere2.="  $tables2.propina = '".$propina."' and ";}

		if ($sWhere2 != "") {
			$sWhere22 = substr($sWhere2, 0, -4);
			$sWhere3 = ' ' . $sWhereCC . ' WHERE ( (' . $sWhere22 . ') 
				AND (02SUBETUFACTURA.ID_RELACIONADO IS NULL OR TRIM(02SUBETUFACTURA.ID_RELACIONADO) = "") ) '; 
		} else {
			$sWhere3 = ' ' . $sWhereCC . ' WHERE (02SUBETUFACTURA.ID_RELACIONADO IS NULL OR TRIM(02SUBETUFACTURA.ID_RELACIONADO) = "") '; 
		}

		$sWhere3campo ="";
		if($search['RAZON_SOCIAL_orden']=="asc"){
			$sWhere3campo .=" $tables.RAZON_SOCIAL asc, ";
		}
		if($search['RAZON_SOCIAL_orden']=="desc"){
			$sWhere3campo.=" $tables.RAZON_SOCIAL desc, ";
		}
		if($search['RFC_PROVEEDOR_orden']=="asc"){
			$sWhere3campo .=" $tables.RFC_PROVEEDOR asc, ";
		}
		if($search['RFC_PROVEEDOR_orden']=="desc"){
			$sWhere3campo.=" $tables.RFC_PROVEEDOR desc, ";
		}
		if($search['MONTO_FACTURA_orden']=="desc"){
			$sWhere3campo.=" $tables.MONTO_FACTURA desc, ";
		}
		if($search['MONTO_FACTURA_orden']=="asc"){
			$sWhere3campo.=" $tables.MONTO_FACTURA asc, ";
		}
		if($search['FECHA_DE_PAGO_orden']=="desc"){
			$sWhere3campo.=" $tables.FECHA_DE_PAGO desc, ";
		}
		if($search['FECHA_DE_PAGO_orden']=="asc"){
			$sWhere3campo.=" $tables.FECHA_DE_PAGO asc, ";
		}
		if($search['NUMERO_EVENTO_orden']=="desc"){
			$sWhere3campo.=" $tables.NUMERO_EVENTO desc, ";
		}
		if($search['NUMERO_EVENTO_orden']=="asc"){
			$sWhere3campo.=" $tables.NUMERO_EVENTO asc, ";
		}
		if($sWhere3campo == ""){
			$sWhere3campo.="  $tables.id desc ";		
	}else{
			$sWhere3campo = substr($sWhere3campo,0,-2);
		}

		$whereClause = $sWhere3;
		$orderClause = " order by ".$sWhere3campo;

                $sql="SELECT DISTINCT $campos , 02SUBETUFACTURA.id as 02SUBETUFACTURAid, RFC_PROVEEDOR as RFC_PROVEEDOR1trim FROM $tables LEFT JOIN $tables2 $sWhere $whereClause $orderClause LIMIT $offset,$per_page";
                $query=$this->mysqli->query($sql);

              
                $countSql = "SELECT COUNT(DISTINCT 02SUBETUFACTURA.id) AS total FROM $tables LEFT JOIN $tables2 $sWhere $whereClause";
                $totalResult = $this->mysqli->query($countSql);
                $totalRow = $totalResult ? $totalResult->fetch_assoc() : ['total' => 0];
                $nums_row = isset($totalRow['total']) ? (int)$totalRow['total'] : 0;

                //Set counter
                $this->setCounter($nums_row);
                return $query;
		

	}



public function obtener_rfc_a_id($valor, $nombreComercial = null) {
    $conn = $this->db();

    // Escapar el valor por seguridad
    $valor = mysqli_real_escape_string($conn, trim($valor));
    $nombreComercial = trim((string) $nombreComercial);

    $condiciones = [
        'dp.P_RFC_MTDP = "'.$valor.'"',
        'dp.P_NOMBRE_FISCAL_RS_EMPRESA LIKE "%'.$valor.'%"',
        'dp.P_NOMBRE_COMERCIAL_EMPRESA LIKE "%'.$valor.'%"',
    ];

    foreach ($condiciones as $condicion) {
        $filtros = [$condicion];

        if ($nombreComercial !== '') {
            $nombreComercialEscaped = mysqli_real_escape_string($conn, $nombreComercial);
            $filtros[] = 'dp.P_NOMBRE_COMERCIAL_EMPRESA = "'.$nombreComercialEscaped.'"';
        }

        $query = 'SELECT dp.idRelacion AS idRelacion FROM 02direccionproveedor1 dp '
            .'INNER JOIN 02usuarios u ON u.id = dp.idRelacion '
            .'WHERE '.implode(' AND ', $filtros).' ORDER BY dp.idRelacion DESC';

        $respuesta = mysqli_query($conn, $query);
        $fetch_array = mysqli_fetch_array($respuesta, MYSQLI_ASSOC);

        if (!empty($fetch_array['idRelacion'])) { 
            return $fetch_array['idRelacion'];
        }
    }

    // Si no encontró nada, regresar NULL o falso
    return null;
}

     public function iralevento($valor) {
    $conn = $this->db();

    // Escapar el valor por seguridad
    $valor = mysqli_real_escape_string($conn, trim($valor));

    // 1. Buscar por RFC exacto
    $query = 'SELECT id FROM 04altaeventos WHERE NUMERO_EVENTO = "'.$valor.'" LIMIT 1';
    $respuesta = mysqli_query($conn, $query);
    $fetch_array = mysqli_fetch_array($respuesta, MYSQLI_ASSOC);

    if (!empty($fetch_array['id'])) {
        return $fetch_array['id'];
    }
	}
	
	
	
	public function getTotalAmaunt($rfc,$idrelacioN=false){
		//PRINT_R($idrelacioN);
		$query_OR = "";
		foreach($idrelacioN as $etiqueta => $valor){
			foreach( $valor AS $etiqueta2 => $valor2){
				$query_OR .= ' id = '. $valor2.' OR ';
			}
		}
		//ECHO $query_OR;
		$query_OR2 = substr($query_OR,0,-3);
		$ROWevento = $this->var_altaeventos();
		$conn = $this->db();		
		$NUMERO_EVENTO = isset($ROWevento["NUMERO_EVENTO"])?$ROWevento["NUMERO_EVENTO"]:"";
                $identificadorProveedor = trim((string)$rfc);
                $sWhere3  = ' where ( 02SUBETUFACTURA.NUMERO_EVENTO = "'.$NUMERO_EVENTO.'") and (NOMBRE_COMERCIAL = trim("'.$identificadorProveedor.'") or RFC_PROVEEDOR = trim("'.$identificadorProveedor.'"))  ';
		$sql1="SELECT sum(MONTO_TOTAL_COTIZACION_ADEUDO - MONTO_DEPOSITADO) as MONTO_TOTAL_COTIZACION_ADEUDO1 FROM  02SUBETUFACTURA ".$sWhere3." and (".$query_OR2.") ";
		$query = mysqli_query($conn,$sql1);
		$fetch_arrary = mysqli_fetch_array($query);
		return $fetch_arrary['MONTO_TOTAL_COTIZACION_ADEUDO1'];
	}
	
	public function ingresarTemproal($RFC_PROVEEDOR,$MONTO_TOTAL_COTIZACION_ADEUDO,$MONTO_DEPOSITADO,$idRelacion,$balance){
		$connn=$this->db();
		$queryTemporal = 'insert into 02temporalEstadoCuenta (RFC_PROVEEDOR ,MONTO_TOTAL_COTIZACION_ADEUDO, MONTO_DEPOSITADO, idRelacion, BALANCE)values("'.$RFC_PROVEEDOR.'","'.$MONTO_TOTAL_COTIZACION_ADEUDO.'","'.$MONTO_DEPOSITADO.'","'.$idRelacion.'", "'.$balance.'");';
		mysqli_query($connn,$queryTemporal);	
	}		

	public function resultadoTemproal($idRelacion,$rfc){
		$connn=$this->db();
		$identificadorProveedor = trim((string)$rfc);
		$queryTemporal = 'select * from 02temporalEstadoCuenta where idRelacion = "'.$idRelacion.'" and RFC_PROVEEDOR = "'.$identificadorProveedor.'" ';
		$query = mysqli_query($connn,$queryTemporal);
		$fetch_arrary = mysqli_fetch_array($query);
		return $fetch_arrary['BALANCE'];
	}

	
	public function TruncateingresarTemproal(){
		$connn=$this->db();
		$queryTemporal = 'truncate table 02temporalEstadoCuenta;';
		mysqli_query($connn,$queryTemporal);	
	}





	
	
	public function ingresarTemproal2($RFC_PROVEEDOR,$MONTO_TOTAL_COTIZACION_ADEUDO,$MONTO_DEPOSITADO,$idRelacion,$balance){
		$connn=$this->db();
		$queryTemporal = 'insert into 02temporalEstadoCuenta2 (RFC_PROVEEDOR ,MONTO_TOTAL_COTIZACION_ADEUDO, MONTO_DEPOSITADO, idRelacion, BALANCE)values("'.$RFC_PROVEEDOR.'","'.$MONTO_TOTAL_COTIZACION_ADEUDO.'","'.$MONTO_DEPOSITADO.'","'.$idRelacion.'", "'.$balance.'");';
		mysqli_query($connn,$queryTemporal);	
	}		

	public function resultadoTemproal2(){
		$connn=$this->db();
		$queryTemporal = 'select * from 02temporalEstadoCuenta2 order by RFC_PROVEEDOR, id desc; ';
		return $query = mysqli_query($connn,$queryTemporal);

	}

	
	public function TruncateingresarTemproal2(){
		$connn=$this->db();
		$queryTemporal = 'truncate table 02temporalEstadoCuenta2;';
		mysqli_query($connn,$queryTemporal);	
	}










	public function getTotalAmaunt2($rfc){
		$conn = $this->db();		
		$identificadorProveedor = trim((string)$rfc);
		$sWhere3  = ' where RFC_PROVEEDOR = trim("'.$identificadorProveedor.'")  ';
		$sql1="SELECT sum(MONTO_TOTAL_COTIZACION_ADEUDO - MONTO_DEPOSITADO) as MONTO_TOTAL_COTIZACION_ADEUDO1 FROM  02temporalEstadoCuenta ".$sWhere3." ";
		$query = mysqli_query($conn,$sql1);
		$fetch_arrary = mysqli_fetch_array($query);
		return $fetch_arrary['MONTO_TOTAL_COTIZACION_ADEUDO1'];
	}

	public function getTotalAmaunt2id($rfc,$idrelacioN,$idactual){
		$query_OR = "";
		//EP2
		foreach($idrelacioN as $etiqueta => $valor){
			foreach( $valor AS $etiqueta2 => $valor2){
				if($idactual!=$valor2){
					$query_OR .= ' idRelacion = '. $valor2.' OR ';
				}
			}
		}
		if($query_OR != ''){
			$query_OR2 = substr($query_OR,0,-3);
			$query_OR3 = " and (".$query_OR2.") ";
		}else{
			return 0;
		}
		$conn = $this->db();		
		$identificadorProveedor = trim((string)$rfc);
		$sWhere3  = ' where RFC_PROVEEDOR = trim("'.$identificadorProveedor.'")  ';
		$sql12="SELECT sum(MONTO_DEPOSITADO) as MONTO_DEPOSITADO1 FROM  02temporalEstadoCuenta ".$sWhere3.$query_OR3." ";
		$query2 = mysqli_query($conn,$sql12);
		$fetch_arrary2 = mysqli_fetch_array($query2);
		return $fetch_arrary2['MONTO_DEPOSITADO1'];
	}
	
	
	
	
	public function diferenciaPorConsecutivo($NUMERO_CONSECUTIVO_PROVEE) {
    $NUMERO_CONSECUTIVO_PROVEE = $this->mysqli->real_escape_string($NUMERO_CONSECUTIVO_PROVEE);
    $NUMERO_CONSECUTIVO_PROVEE = (int)$NUMERO_CONSECUTIVO_PROVEE;
    $con = $this->db();

    // Inicializar variables
    $PorfaltaDeFactura = 0.0;
    $PorfaltaDeFacturaSUBERES = 0.0;

    $subTotalSUBETUFACTURA = 0.0;



    // 1) Con ID_RELACIONADO != '' (relacionadas)
    $VarSUBE = "SELECT subTotal, UUID, MONTO_DEPOSITAR, ID_RELACIONADO, STATUS_CHECKBOX,
                       MONTO_FACTURA, NUMERO_CONSECUTIVO_PROVEE
                FROM 02SUBETUFACTURA
                LEFT JOIN 02XML ON 02SUBETUFACTURA.id = 02XML.`ultimo_id`
                WHERE 02SUBETUFACTURA.NUMERO_CONSECUTIVO_PROVEE = '$NUMERO_CONSECUTIVO_PROVEE'
                  AND 02SUBETUFACTURA.VIATICOSOPRO IN ('REEMBOLSO','VIATICOS',
                      'PAGO A PROVEEDOR CON DOS O MAS FACTURAS','PAGOS CON UNA SOLA FACTURA')
                  AND (02SUBETUFACTURA.ID_RELACIONADO IS NOT NULL
                       AND TRIM(02SUBETUFACTURA.ID_RELACIONADO) <> '')";

    $QUERYSUBE = mysqli_query($con, $VarSUBE);
    while ($ROWe = mysqli_fetch_array($QUERYSUBE)) {

        if ($ROWe['STATUS_CHECKBOX'] == 'no' && strlen(trim($ROWe['UUID'])) < 1) {
            $PorfaltaDeFactura += (float)$ROWe['MONTO_DEPOSITAR'] * 1.46;
        } else {
            if (isset($ROWe['subTotal']) && is_numeric($ROWe['subTotal']) && $ROWe['subTotal'] > 0) {
                $subTotalSUBETUFACTURA += (float)$ROWe['subTotal'];
            } else {
                $subTotalSUBETUFACTURA += (float)$ROWe['MONTO_FACTURA'];
            }
        }
    }
	
	
$NUMERO_CONSECUTIVO_PROVEE = $this->mysqli->real_escape_string($NUMERO_CONSECUTIVO_PROVEE);
$NUMERO_CONSECUTIVO_PROVEE = (int)$NUMERO_CONSECUTIVO_PROVEE;
$con = $this->db();

$VarSUBERES = "
    SELECT  STATUS_CHECKBOX ,UUID,
        SUM(CASE WHEN ID_RELACIONADO IS NULL OR TRIM(ID_RELACIONADO) = ''
                 THEN MONTO_DEPOSITAR ELSE 0 END) AS sin_relacion,
        SUM(CASE WHEN ID_RELACIONADO IS NOT NULL AND TRIM(ID_RELACIONADO) <> ''
                 THEN MONTO_DEPOSITAR ELSE 0 END) AS con_relacion
    FROM 02SUBETUFACTURA LEFT JOIN 02XML ON 02SUBETUFACTURA.id = 02XML.`ultimo_id`
    WHERE NUMERO_CONSECUTIVO_PROVEE = '$NUMERO_CONSECUTIVO_PROVEE'
      AND VIATICOSOPRO IN ('VIATICOS','REEMBOLSO',
                           'PAGO A PROVEEDOR CON DOS O MAS FACTURAS',
                           'PAGOS CON UNA SOLA FACTURA')";

$QUERYSUBERES = mysqli_query($con, $VarSUBERES);

// Obtenemos el único registro de la consulta (suma de montos)
$ROWeR = $QUERYSUBERES ? mysqli_fetch_assoc($QUERYSUBERES)
                       : ['sin_relacion' => 0, 'con_relacion' => 0];

// Evitar undefined index para claves no incluidas en el SELECT
$ROWeR += ['UUID' => '', 'STATUS_CHECKBOX' => null];

// Inicializar acumuladores para evitar avisos
$con_relacion = 0.0;
$sin_relacion = 0.0;


    $sin_relacion = (float)$ROWeR['sin_relacion'];

    $con_relacion = (float)$ROWeR['con_relacion'];



if (
    strlen(trim($ROWeR['UUID'])) < 1 &&
    isset($ROWeR['STATUS_CHECKBOX']) && $ROWeR['STATUS_CHECKBOX'] === 'no'
) {
    $PorfaltaDeFacturaSUBERES = ($sin_relacion - $con_relacion) * 1.46;
} else {
    $PorfaltaDeFacturaSUBERES = $sin_relacion - $con_relacion;// o el valor que corresponda
}

return (float) $PorfaltaDeFacturaSUBERES2 = (float) $PorfaltaDeFactura + (float) $PorfaltaDeFacturaSUBERES;

}
	
	

        function setCounter($counter) {
                $this->counter = $counter;
        }
        function getCounter() {
                return $this->counter;
        }

        /**
         * Obtiene los números de evento para los que un colaborador puede
         * autorizar operaciones de ventas.
         *
         * La autorización se determina cuando el colaborador tiene
         * `autorizaAUT = 'si'` en la tabla 04personal y el evento asociado
         * pertenece a 04altaeventos.
         *
         * @param string|int $idPersonal Identificador del colaborador (idem en sesión).
         * @return string[] Lista de números de evento (normalizados en mayúsculas).
         */
        public function puedeAutorizarVentas($idPersonal) {
                if (empty($idPersonal)) {
                        return [];
                }

                $conn = $this->db();
                if (!$conn) {
                        return [];
                }

                $idPersonal = mysqli_real_escape_string($conn, trim((string) $idPersonal));

                $columnasIdentificador = $this->columnasIdentificadorPersonal($conn);
                if (empty($columnasIdentificador)) {
                        return [];
                }

                $condicionesIdentificador = [];
                foreach ($columnasIdentificador as $columna) {
                        $condicionesIdentificador[] = "`p`.`".$columna."` = '".$idPersonal."'";
                }

                $sql = "
                        SELECT DISTINCT ae.NUMERO_EVENTO
                        FROM 04personal AS p
                        INNER JOIN 04altaeventos AS ae ON ae.id = p.idRelacion
                        WHERE (".implode(' OR ', $condicionesIdentificador).")
                          AND LOWER(p.autorizaAUT) = 'si'
                          AND ae.NUMERO_EVENTO IS NOT NULL
                          AND ae.NUMERO_EVENTO <> ''";

                $resultado = mysqli_query($conn, $sql);
                if (!$resultado) {
                        return [];
                }

                $eventosAutorizados = [];
                while ($row = mysqli_fetch_assoc($resultado)) {
                        $eventoNormalizado = strtoupper(trim((string) $row['NUMERO_EVENTO']));
                        if ($eventoNormalizado !== '') {
                                $eventosAutorizados[$eventoNormalizado] = true;
                        }
                }
                mysqli_free_result($resultado);

                return array_keys($eventosAutorizados);
        }

        /**
         * Obtiene las columnas disponibles para identificar a un colaborador en 04personal.
         *
         * @param mysqli $conn Conexión activa a la base de datos.
         * @return string[]
         */
        private function columnasIdentificadorPersonal($conn) {
                static $columnasCache = null;

                if ($columnasCache !== null) {
                        return $columnasCache;
                }

                $columnasPosibles = ['idem', 'idPersonal', 'IDEM', 'ID_PERSONAL'];
                $columnasDisponibles = [];

                foreach ($columnasPosibles as $columna) {
                        if ($this->columnaExisteEnTabla($conn, '04personal', $columna)) {
                                $columnasDisponibles[] = $columna;
                        }
                }

                $columnasCache = $columnasDisponibles;
                return $columnasCache;
        }

        /**
         * Verifica si una columna existe en una tabla de la base de datos activa.
         *
         * @param mysqli $conn Conexión activa a la base de datos.
         * @param string $tabla Nombre de la tabla.
         * @param string $columna Nombre de la columna.
         * @return bool
         */
        private function columnaExisteEnTabla($conn, $tabla, $columna) {
                if (!$conn || $tabla === '' || $columna === '') {
                        return false;
                }

                $tablaLimpia = str_replace('`', '``', $tabla);
                $columnaLimpia = mysqli_real_escape_string($conn, $columna);
                $sql = "SHOW COLUMNS FROM `".$tablaLimpia."` LIKE '".$columnaLimpia."'";
                $resultado = mysqli_query($conn, $sql);
                if ($resultado) {
                        $existe = mysqli_num_rows($resultado) > 0;
                        mysqli_free_result($resultado);
                        return $existe;
                }

                return false;
        }

}
?>