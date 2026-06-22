<?php
/**
 	--------------------------
	Autor: Sandor Matamoros
	Programer: Fatima Arellano
	Propietario: EPC
	----------------------------
 
*/

define("__ROOT1__", dirname(dirname(__FILE__)));
	include_once (__ROOT1__."/../includes/error_reporting.php");
	include_once (__ROOT1__."/../comprobaciones/class.epcinnPP.php");

	
class orders extends accesoclase {
	public $mysqli;
	public $counter;//Propiedad para almacenar el numero de registro devueltos por la consulta
	private $matchCache = [];
	private $matchAnyCache = [];
	private $tarjetaCache = [];
	private $plantillaFiltroCache = [];


	function __construct(){
		$this->mysqli = $this->db();
    }
	public function plantilla_filtro($nombreTabla, $campo, $altaeventos, $departamento) {
		$cacheKey = $nombreTabla.'|'.$campo.'|'.$altaeventos.'|'.$departamento;
		if (isset($this->plantillaFiltroCache[$cacheKey])) {
			return $this->plantillaFiltroCache[$cacheKey];
		}

		$resultado = parent::plantilla_filtro($nombreTabla, $campo, $altaeventos, $departamento);
		$this->plantillaFiltroCache[$cacheKey] = $resultado;

		return $resultado;
	}
		/*se ocupa en MATCH_BBVA.php regresa checked*/
	public function validaexistematch2COMPROBACION($IpMATCHDOCUMENTOS2,$TARJETA){
		$conn = $this->db();		
			$pregunta = 'select * from 12matchDocumentos where 
			estatus = "si" and documentoConFactura="'.$IpMATCHDOCUMENTOS2.'" AND tarjeta="'.$TARJETA.'" ';
			$preguntaQ = mysqli_query($conn,$pregunta) or die('P1533'.mysqli_error($conn));
			$ROWP = MYSQLI_FETCH_ARRAY($preguntaQ, MYSQLI_ASSOC);

		
				
			if($ROWP['id']==0){
			return '';
			}else{
			return 'checked';				
			}
	}


	public function validaexistematch2COMPROBACIONtodos($IpMATCHDOCUMENTOS2,$TARJETA){
		$conn = $this->db();
	
			$pregunta1 = 'select * from 12matchDocumentos where 
			estatus = "si" and documentoConFactura="'.$IpMATCHDOCUMENTOS2.'" AND tarjeta="AMERICANE" ';
			$preguntaQ1 = mysqli_query($conn,$pregunta1) or die('P1533'.mysqli_error($conn));
			$ROWP1 = MYSQLI_FETCH_ARRAY($preguntaQ1, MYSQLI_ASSOC);

			$pregunta2 = 'select * from 12matchDocumentos where 
			estatus = "si" and documentoConFactura="'.$IpMATCHDOCUMENTOS2.'" AND tarjeta="INBURSA" ';
			$preguntaQ2 = mysqli_query($conn,$pregunta2) or die('P1533'.mysqli_error($conn));
			$ROWP2 = MYSQLI_FETCH_ARRAY($preguntaQ2, MYSQLI_ASSOC);

			$pregunta3 = 'select * from 12matchDocumentos where 
			estatus = "si" and documentoConFactura="'.$IpMATCHDOCUMENTOS2.'" AND tarjeta="TARJETABBVA" ';
			$preguntaQ3 = mysqli_query($conn,$pregunta3) or die('P1533'.mysqli_error($conn));
			$ROWP3 = MYSQLI_FETCH_ARRAY($preguntaQ3, MYSQLI_ASSOC);
			
			if($ROWP1['id']==0 and $ROWP2['id']==0 and $ROWP3['id']==0){
			return '';
			}else{
			return 'checked';				
			}
	}


	public function nombreCompletoPorID($id) {
    $conn = $this->db();

    $id = mysqli_real_escape_string($conn, trim($id));

    $sql = "
        SELECT NOMBRE_1, NOMBRE_2, APELLIDO_PATERNO, APELLIDO_MATERNO
        FROM 01informacionpersonal
        WHERE idRelacion = '$id'
        LIMIT 1
    ";

    $nombreCompleto = 'SIN INFORMACIÓN';
    
    if ($query = mysqli_query($conn, $sql)) {
        if ($row = mysqli_fetch_assoc($query)) {
            $nombreCompleto = trim(
                $row['NOMBRE_1'].' '.
                $row['NOMBRE_2'].' '.
                $row['APELLIDO_PATERNO'].' '.
                $row['APELLIDO_MATERNO']
            );

            if ($nombreCompleto == '') {
                $nombreCompleto = 'SIN INFORMACIÓN';
            }
        }
    }

    return $nombreCompleto;
}

	public function countAll($sql){
		$query=$this->mysqli->query($sql);
		$count=$query->num_rows;
		return $count;
	}

	public function getData($tables,$campos,$search){
		$offset=$search['offset'];
		$per_page=$search['per_page'];
		
		$tables = '07COMPROBACION';
		$tables2 = '07XML';		
		
		$sWhereCC =" ON 07COMPROBACION.id = 07XML.`ultimo_id` ";
		$sWhere2="";$sWhere3="";
		
		
		if($search['NUMERO_CONSECUTIVO_PROVEE']!=""){
$sWhere2.="  $tables.NUMERO_CONSECUTIVO_PROVEE LIKE '%".$search['NUMERO_CONSECUTIVO_PROVEE']."%' AND ";}
if($search['RAZON_SOCIAL']!=""){
$sWhere2.="  $tables.RAZON_SOCIAL LIKE '%".$search['RAZON_SOCIAL']."%' AND ";}
if($search['RFC_PROVEEDOR']!=""){
$sWhere2.="  $tables.RFC_PROVEEDOR LIKE '%".$search['RFC_PROVEEDOR']."%' AND ";}

if($search['NUMERO_EVENTO']!=""){
$sWhere2.="  $tables.NUMERO_EVENTO LIKE '%".$search['NUMERO_EVENTO']."%' AND ";}

if($search['EJECUTIVOTARJETA']!=""){
$ejecutivoTarjeta = strtoupper($search['EJECUTIVOTARJETA']);
$ejecutivoTarjetaEscapado = $this->mysqli->real_escape_string($ejecutivoTarjeta);

$busquedaNombre = "SELECT idRelacion FROM 01informacionpersonal WHERE UPPER(CONCAT_WS(' ', NOMBRE_1, NOMBRE_2, APELLIDO_PATERNO, APELLIDO_MATERNO)) LIKE '%".$ejecutivoTarjetaEscapado."%'";

$sWhere2.="  (UPPER($tables.EJECUTIVOTARJETA) LIKE '%".$ejecutivoTarjetaEscapado."%' OR $tables.EJECUTIVOTARJETA IN (".$busquedaNombre.")) AND ";}


if($search['NOMBRE_EVENTO']!=""){
$sWhere2.="  $tables.NOMBRE_EVENTO LIKE '%".$search['NOMBRE_EVENTO']."%' AND ";}
if($search['MOTIVO_GASTO']!=""){
$sWhere2.="  $tables.MOTIVO_GASTO LIKE '%".$search['MOTIVO_GASTO']."%' AND ";}
if($search['CONCEPTO_PROVEE']!=""){
$sWhere2.="  $tables.CONCEPTO_PROVEE LIKE '%".$search['CONCEPTO_PROVEE']."%' AND ";}
if($search['MONTO_TOTAL_COTIZACION_ADEUDO']!=""){
$sWhere2.="  $tables.MONTO_TOTAL_COTIZACION_ADEUDO LIKE '%".$search['MONTO_TOTAL_COTIZACION_ADEUDO']."%' AND ";}
if($search['MONTO_FACTURA']!=""){
$sWhere2.="  $tables.MONTO_FACTURA LIKE '%".$search['MONTO_FACTURA']."%' AND ";}
if($search['MONTO_PROPINA']!=""){
$sWhere2.="  $tables.MONTO_PROPINA LIKE '%".$search['MONTO_PROPINA']."%' AND ";}
if($search['MONTO_DEPOSITAR']!=""){
$sWhere2.="  $tables.MONTO_DEPOSITAR LIKE '%".$search['MONTO_DEPOSITAR']."%' AND ";}
if($search['TIPO_DE_MONEDA']!=""){
$sWhere2.="  $tables.TIPO_DE_MONEDA LIKE '%".$search['TIPO_DE_MONEDA']."%' AND ";}
if($search['PFORMADE_PAGO']!=""){
$sWhere2.="  $tables.PFORMADE_PAGO LIKE '%".$search['PFORMADE_PAGO']."%' AND ";}
if($search['FECHA_A_DEPOSITAR']!=""){
$sWhere2.="  $tables.FECHA_A_DEPOSITAR LIKE '%".$search['FECHA_A_DEPOSITAR']."%' AND ";}
if($search['STATUS_DE_PAGO']!=""){
$sWhere2.="  $tables.STATUS_DE_PAGO LIKE '%".$search['STATUS_DE_PAGO']."%' AND ";}

if($search['BANCO_ORIGEN']!=""){
$sWhere2.="  $tables.BANCO_ORIGEN LIKE '%".$search['BANCO_ORIGEN']."%' AND ";}
if(isset($search['ADJUNTAR_FACTURA_XML_VACIO']) 
   && $search['ADJUNTAR_FACTURA_XML_VACIO'] == "si"){

    $sWhere2 .= " (
        07XML.UUID IS NULL
        OR TRIM(07XML.UUID) = ''
    ) and ";
}
if($search['ACTIVO_FIJO']!=""){
$sWhere2.="  $tables.ACTIVO_FIJO LIKE '%".$search['ACTIVO_FIJO']."%' AND ";}
if($search['GASTO_FIJO']!=""){
$sWhere2.="  $tables.GASTO_FIJO LIKE '%".$search['GASTO_FIJO']."%' AND ";}
if($search['PAGAR_CADA']!=""){
$sWhere2.="  $tables.PAGAR_CADA LIKE '%".$search['PAGAR_CADA']."%' AND ";}
if($search['FECHA_PPAGO']!=""){
$sWhere2.="  $tables.FECHA_PPAGO LIKE '%".$search['FECHA_PPAGO']."%' AND ";}
if($search['FECHA_TPROGRAPAGO']!=""){
$sWhere2.="  $tables.FECHA_TPROGRAPAGO LIKE '%".$search['FECHA_TPROGRAPAGO']."%' AND ";}
if($search['NUMERO_EVENTOFIJO']!=""){
$sWhere2.="  $tables.NUMERO_EVENTOFIJO LIKE '%".$search['NUMERO_EVENTOFIJO']."%' AND ";}
if($search['CLASI_GENERAL']!=""){
$sWhere2.="  $tables.CLASI_GENERAL LIKE '%".$search['CLASI_GENERAL']."%' AND ";}
if($search['SUB_GENERAL']!=""){
$sWhere2.="  $tables.SUB_GENERAL LIKE '%".$search['SUB_GENERAL']."%' AND ";}
if($search['MONTO_DE_COMISION']!=""){
$sWhere2.="  $tables.MONTO_DE_COMISION LIKE '%".$search['MONTO_DE_COMISION']."%' AND ";}
if($search['POLIZA_NUMERO']!=""){
$sWhere2.="  $tables.POLIZA_NUMERO LIKE '%".$search['POLIZA_NUMERO']."%' AND ";}
if($search['NOMBRE_DEL_EJECUTIVO']!=""){
$nombreEjecutivo = strtoupper($search['NOMBRE_DEL_EJECUTIVO']);
$nombreEjecutivoEscapado = $this->mysqli->real_escape_string($nombreEjecutivo);

$busquedaNombreEjecutivo = "SELECT idRelacion FROM 01informacionpersonal WHERE UPPER(CONCAT_WS(' ', NOMBRE_1, NOMBRE_2, APELLIDO_PATERNO, APELLIDO_MATERNO)) LIKE '%".$nombreEjecutivoEscapado."%'";

$sWhere2.="  (UPPER($tables.NOMBRE_DEL_EJECUTIVO) LIKE '%".$nombreEjecutivoEscapado."%' OR $tables.NOMBRE_DEL_EJECUTIVO IN (".$busquedaNombreEjecutivo.")) AND ";}
if($search['NOMBRE_DEL_AYUDO']!=""){
$nombreAyudo = strtoupper($search['NOMBRE_DEL_AYUDO']);

$nombreAyudoEscapado = $this->mysqli->real_escape_string($nombreAyudo);

$busquedaNombreAyudo = "SELECT idRelacion FROM 01informacionpersonal WHERE UPPER(CONCAT_WS(' ', NOMBRE_1, NOMBRE_2, APELLIDO_PATERNO, APELLIDO_MATERNO)) LIKE '%".$nombreAyudoEscapado."%'";

$sWhere2.="  (UPPER($tables.NOMBRE_DEL_AYUDO) LIKE '%".$nombreAyudoEscapado."%' OR $tables.NOMBRE_DEL_AYUDO IN (".$busquedaNombreAyudo.")) AND ";}

if($search['OBSERVACIONES_1']!=""){
$sWhere2.="  $tables.OBSERVACIONES_1 LIKE '%".$search['OBSERVACIONES_1']."%' AND ";}
if($search['FECHA_DE_LLENADO']!=""){
$sWhere2.="  $tables.FECHA_DE_LLENADO LIKE '%".$search['FECHA_DE_LLENADO']."%' AND ";}
if($search['hiddenpagoproveedores']!=""){
$sWhere2.="  $tables.hiddenpagoproveedores LIKE '%".$search['hiddenpagoproveedores']."%' AND ";}
if($search['ADJUNTAR_COTIZACION']!=""){
$sWhere2.="  $tables.ADJUNTAR_COTIZACION LIKE '%".$search['ADJUNTAR_COTIZACION']."%' AND ";}
if($search['TImpuestosRetenidosIVA']!=""){
$sWhere2.="  $tables.TImpuestosRetenidosIVA LIKE '%".$search['TImpuestosRetenidosIVA']."%' AND ";}

if($search['TImpuestosRetenidosISR']!=""){
$sWhere2.="  $tables.TImpuestosRetenidosISR LIKE '%".$search['TImpuestosRetenidosISR']."%' AND ";}

if($search['descuentos']!=""){
$sWhere2.="  $tables.descuentos LIKE '%".$search['descuentos']."%' AND ";}

if($search['EJECUTIVOTARJETA']!=""){
$sWhere2.="  $tables.EJECUTIVOTARJETA LIKE '%".$search['EJECUTIVOTARJETA']."%' AND ";}

if($search['TIPO_CAMBIOP']!=""){
$sWhere2.="  $tables.TIPO_CAMBIOP LIKE '%".$search['TIPO_CAMBIOP']."%' AND ";}
if($search['TOTAL_ENPESOS']!=""){
$sWhere2.="  $tables.TOTAL_ENPESOS LIKE '%".$search['TOTAL_ENPESOS']."%' AND ";}

if($search['NOMBRE_COMERCIAL']!=""){
$sWhere2.="  $tables.NOMBRE_COMERCIAL LIKE '%".$search['NOMBRE_COMERCIAL']."%' AND ";}

if($search['IVA']!=""){
$sWhere2.="  $tables.IVA LIKE '%".$search['IVA']."%' AND ";}

if($search['UUID']!=""){
$sWhere2.="  $tables2.UUID = '".$search['UUID']."' AND ";}

if($search['metodoDePago']!=""){
$sWhere2.="  $tables2.metodoDePago = '".$search['metodoDePago']."' AND ";}

if($search['total']!=""){
$totalf = str_replace(',','',str_replace('$','',$search['total']));
$sWhere2.="  $tables2.total = '".$totalf."' AND ";}

if($search['serie']!=""){
$sWhere2.="  $tables2.serie = '".$search['serie']."' AND ";}

if($search['folio']!=""){
$sWhere2.="  $tables2.folio = '".$search['folio']."' AND ";}

if($search['regimenE']!=""){
$sWhere2.="  $tables2.regimenE = '".$search['regimenE']."' AND ";}

if($search['UsoCFDI']!=""){
$sWhere2.="  $tables2.UsoCFDI = '".$search['UsoCFDI']."' AND ";}

if($search['TImpuestosTrasladados']!=""){
$TImpuestosTrasladados = str_replace(',','',str_replace('$','',$search['TImpuestosTrasladados']));
$sWhere2.="  $tables2.TImpuestosTrasladados = ".$TImpuestosTrasladados." AND ";}

if($search['TImpuestosRetenidos']!=""){
$TImpuestosRetenidos = str_replace(',','',str_replace('$','',$search['TImpuestosRetenidos']));
$sWhere2.="  $tables2.TImpuestosRetenidos = ".$TImpuestosRetenidos." AND ";}

if($search['Version']!=""){
$sWhere2.="  $tables2.Version = '".$search['Version']."' AND ";}

if($search['tipoDeComprobante']!=""){
$sWhere2.="  $tables2.tipoDeComprobante = '".$search['tipoDeComprobante']."' AND ";}

if($search['condicionesDePago']!=""){
$sWhere2.="  $tables2.condicionesDePago = '".$search['condicionesDePago']."' AND ";}

if($search['fechaTimbrado']!=""){
$sWhere2.="  $tables2.fechaTimbrado = '".$search['fechaTimbrado']."' AND ";}

if($search['nombreR']!=""){
$sWhere2.="  $tables2.nombreR = '".$search['nombreR']."' AND ";}

if($search['rfcR']!=""){
$sWhere2.="  $tables2.rfcR = '".$search['rfcR']."' AND ";}

if($search['Moneda']!=""){
$sWhere2.="  $tables2.Moneda = '".$search['Moneda']."' AND ";}

if($search['TipoCambio']!=""){
$sWhere2.="  $tables2.TipoCambio = '".$search['TipoCambio']."' AND ";}

if($search['ValorUnitarioConcepto']!=""){
$sWhere2.="  $tables2.ValorUnitarioConcepto = '".$search['ValorUnitarioConcepto']."' AND ";}

if($search['Cantidad']!=""){
$sWhere2.="  $tables2.Cantidad like '%".$search['Cantidad']."%' AND ";}

if($search['ClaveUnidad']!=""){
$sWhere2.="  $tables2.ClaveUnidad like '%".$search['ClaveUnidad']."%' AND ";}

if($search['ClaveProdServ']!=""){
$sWhere2.="  $tables2.ClaveProdServ = '".$search['ClaveProdServ']."' AND ";}

if($search['RFC_RECEPTOR']!=""){
$sWhere2.="  $tables2.RFC_RECEPTOR = '".$search['RFC_RECEPTOR']."' AND ";}

if($search['CantidadConcepto']!=""){
$sWhere2.="  $tables2.CantidadConcepto = '".$search['CantidadConcepto']."' AND ";}

if($search['ImporteConcepto']!=""){
$sWhere2.="  $tables2.ImporteConcepto = '".$search['ImporteConcepto']."' AND ";}

if($search['UnidadConcepto']!=""){
$sWhere2.="  $tables2.UnidadConcepto = '".$search['UnidadConcepto']."' AND ";}

if($search['TUA']!=""){
	$TUA = str_replace(',','',str_replace('$','',$search['TUA']));
$sWhere2.="  $tables2.TUA = '".$TUA."' AND ";}

if($search['TuaTotalCargos']!=""){
	$TuaTotalCargos = str_replace(',','',str_replace('$','',$search['TuaTotalCargos']));
$sWhere2.="  $tables2.TuaTotalCargos = '".$TuaTotalCargos."' AND ";}

if($search['Descuento']!=""){
	$Descuento = str_replace(',','',str_replace('$','',$search['Descuento']));
$sWhere2.="  $tables2.Descuento = '".$Descuento."' AND ";}

if($search['subTotal']!=""){
	$subTotal = str_replace(',','',str_replace('$','',$search['subTotal']));
$sWhere2.="  $tables2.subTotal = '".$subTotal."' AND ";}

if($search['IMPUESTO_HOSPEDAJE']!=""){
	$IMPUESTO_HOSPEDAJE = str_replace(',','',str_replace('$','',$search['IMPUESTO_HOSPEDAJE']));
$sWhere2.="  $tables2.IMPUESTO_HOSPEDAJE = '".$IMPUESTO_HOSPEDAJE."' AND ";}

if($search['propina']!=""){
	$propina = str_replace(',','',str_replace('$','',$search['propina']));
$sWhere2.="  $tables2.propina = '".$propina."' AND ";}


		// -------------------------------------------------------
		// CORRECCIÓN: se cambiaron todos los OR por AND arriba.
		// Aquí se recorta " AND" (5 caracteres con el espacio).
		// -------------------------------------------------------
		IF($sWhere2!=""){
			$sWhere22 = substr($sWhere2, 0, -5); // recorta el último " AND"
			$sWhere3  = ' ('.$sWhere22.') ';
			$sWhere3  = ' '.$sWhereCC.' where ( ('.$sWhere3.') ) ';			
		}ELSE{
					
		$sWhere3  = ' '.$sWhereCC.' ';
		}
		$sWhere3campo.=" $tables.id desc ";		
        $sWhere3 .= " order by ".$sWhere3campo;


    $sql = "SELECT $campos, 07COMPROBACION.id as 07COMPROBACIONid 
            FROM $tables LEFT JOIN $tables2 $sWhere $sWhere3 
            LIMIT $offset,$per_page";
    $query = $this->mysqli->query($sql);

  
    $sqlCount = "SELECT COUNT(*) as total 
                 FROM $tables LEFT JOIN $tables2 $sWhere $sWhere3";
    $resultCount = $this->mysqli->query($sqlCount);
    $rowCount = $resultCount->fetch_assoc();
    $this->setCounter($rowCount['total']);

    return $query;
}

	    function setCounter($counter) {
		$this->counter = $counter;
	}
	    function getCounter() {
		return $this->counter;
	}
	
	
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
