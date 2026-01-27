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

	function __construct(){
		$this->mysqli = $this->db();
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
    $conn = $this->db(); // tu conexión a la base de datos

    // Previene SQL injection
    $id = mysqli_real_escape_string($conn, trim($id));

    // Consulta
    $sql = "
        SELECT NOMBRE_1, NOMBRE_2, APELLIDO_PATERNO, APELLIDO_MATERNO
        FROM 01informacionpersonal
        WHERE idRelacion = '$id'
        LIMIT 1
    ";

    $nombreCompleto = 'SIN INFORMACIÓN'; // valor por defecto (gris clarito)
    
    if ($query = mysqli_query($conn, $sql)) {
        if ($row = mysqli_fetch_assoc($query)) {
            // Une los nombres con espacios y elimina dobles espacios
            $nombreCompleto = trim(
                $row['NOMBRE_1'].' '.
                $row['NOMBRE_2'].' '.
                $row['APELLIDO_PATERNO'].' '.
                $row['APELLIDO_MATERNO']
            );

            // Si por alguna razón está vacío, mantiene el texto “SIN INFORMACIÓN”
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
	//STATUS_EVENTO,NOMBRE_CORTO_EVENTO,NOMBRE_EVENTO
	public function getData($tables,$campos,$search){
		$offset=$search['offset'];
		$per_page=$search['per_page'];
		
		$tables = '07COMPROBACION';
		$tables2 = '07XML';		
		
		$sWhereCC =" ON 07COMPROBACION.id = 07XML.`ultimo_id` ";
		$sWhere2="";$sWhere3="";
		
		
		if($search['NUMERO_CONSECUTIVO_PROVEE']!=""){
$sWhere2.="  $tables.NUMERO_CONSECUTIVO_PROVEE LIKE '%".$search['NUMERO_CONSECUTIVO_PROVEE']."%' OR ";}
if($search['RAZON_SOCIAL']!=""){
$sWhere2.="  $tables.RAZON_SOCIAL LIKE '%".$search['RAZON_SOCIAL']."%' OR ";}
if($search['RFC_PROVEEDOR']!=""){
$sWhere2.="  $tables.RFC_PROVEEDOR LIKE '%".$search['RFC_PROVEEDOR']."%' OR ";}

if($search['NUMERO_EVENTO']!=""){
$sWhere2.="  $tables.NUMERO_EVENTO LIKE '%".$search['NUMERO_EVENTO']."%' OR ";}

if($search['EJECUTIVOTARJETA']!=""){
$ejecutivoTarjeta = strtoupper($search['EJECUTIVOTARJETA']);
$ejecutivoTarjetaEscapado = $this->mysqli->real_escape_string($ejecutivoTarjeta);

$busquedaNombre = "SELECT idRelacion FROM 01informacionpersonal WHERE UPPER(CONCAT_WS(' ', NOMBRE_1, NOMBRE_2, APELLIDO_PATERNO, APELLIDO_MATERNO)) LIKE '%".$ejecutivoTarjetaEscapado."%'";

$sWhere2.="  (UPPER($tables.EJECUTIVOTARJETA) LIKE '%".$ejecutivoTarjetaEscapado."%' OR $tables.EJECUTIVOTARJETA IN (".$busquedaNombre.")) OR ";}


if($search['NOMBRE_EVENTO']!=""){
$sWhere2.="  $tables.NOMBRE_EVENTO LIKE '%".$search['NOMBRE_EVENTO']."%' OR ";}
if($search['MOTIVO_GASTO']!=""){
$sWhere2.="  $tables.MOTIVO_GASTO LIKE '%".$search['MOTIVO_GASTO']."%' OR ";}
if($search['CONCEPTO_PROVEE']!=""){
$sWhere2.="  $tables.CONCEPTO_PROVEE LIKE '%".$search['CONCEPTO_PROVEE']."%' OR ";}
if($search['MONTO_TOTAL_COTIZACION_ADEUDO']!=""){
$sWhere2.="  $tables.MONTO_TOTAL_COTIZACION_ADEUDO LIKE '%".$search['MONTO_TOTAL_COTIZACION_ADEUDO']."%' OR ";}
if($search['MONTO_FACTURA']!=""){
$sWhere2.="  $tables.MONTO_FACTURA LIKE '%".$search['MONTO_FACTURA']."%' OR ";}
if($search['MONTO_PROPINA']!=""){
$sWhere2.="  $tables.MONTO_PROPINA LIKE '%".$search['MONTO_PROPINA']."%' OR ";}
if($search['MONTO_DEPOSITAR']!=""){
$sWhere2.="  $tables.MONTO_DEPOSITAR LIKE '%".$search['MONTO_DEPOSITAR']."%' OR ";}
if($search['TIPO_DE_MONEDA']!=""){
$sWhere2.="  $tables.TIPO_DE_MONEDA LIKE '%".$search['TIPO_DE_MONEDA']."%' OR ";}
if($search['PFORMADE_PAGO']!=""){
$sWhere2.="  $tables.PFORMADE_PAGO LIKE '%".$search['PFORMADE_PAGO']."%' OR ";}
if($search['FECHA_A_DEPOSITAR']!=""){
$sWhere2.="  $tables.FECHA_A_DEPOSITAR LIKE '%".$search['FECHA_A_DEPOSITAR']."%' OR ";}
if($search['STATUS_DE_PAGO']!=""){
$sWhere2.="  $tables.STATUS_DE_PAGO LIKE '%".$search['STATUS_DE_PAGO']."%' OR ";}

if($search['BANCO_ORIGEN']!=""){
$sWhere2.="  $tables.BANCO_ORIGEN LIKE '%".$search['BANCO_ORIGEN']."%' OR ";}

if($search['ACTIVO_FIJO']!=""){
$sWhere2.="  $tables.ACTIVO_FIJO LIKE '%".$search['ACTIVO_FIJO']."%' OR ";}
if($search['GASTO_FIJO']!=""){
$sWhere2.="  $tables.GASTO_FIJO LIKE '%".$search['GASTO_FIJO']."%' OR ";}
if($search['PAGAR_CADA']!=""){
$sWhere2.="  $tables.PAGAR_CADA LIKE '%".$search['PAGAR_CADA']."%' OR ";}
if($search['FECHA_PPAGO']!=""){
$sWhere2.="  $tables.FECHA_PPAGO LIKE '%".$search['FECHA_PPAGO']."%' OR ";}
if($search['FECHA_TPROGRAPAGO']!=""){
$sWhere2.="  $tables.FECHA_TPROGRAPAGO LIKE '%".$search['FECHA_TPROGRAPAGO']."%' OR ";}
if($search['NUMERO_EVENTOFIJO']!=""){
$sWhere2.="  $tables.NUMERO_EVENTOFIJO LIKE '%".$search['NUMERO_EVENTOFIJO']."%' OR ";}
if($search['CLASI_GENERAL']!=""){
$sWhere2.="  $tables.CLASI_GENERAL LIKE '%".$search['CLASI_GENERAL']."%' OR ";}
if($search['SUB_GENERAL']!=""){
$sWhere2.="  $tables.SUB_GENERAL LIKE '%".$search['SUB_GENERAL']."%' OR ";}
if($search['MONTO_DE_COMISION']!=""){
$sWhere2.="  $tables.MONTO_DE_COMISION LIKE '%".$search['MONTO_DE_COMISION']."%' OR ";}
if($search['POLIZA_NUMERO']!=""){
$sWhere2.="  $tables.POLIZA_NUMERO LIKE '%".$search['POLIZA_NUMERO']."%' OR ";}
if($search['NOMBRE_DEL_EJECUTIVO']!=""){
$sWhere2.="  $tables.NOMBRE_DEL_EJECUTIVO LIKE '%".$search['NOMBRE_DEL_EJECUTIVO']."%' OR ";}
if($search['NOMBRE_DEL_AYUDO']!=""){
$sWhere2.="  $tables.NOMBRE_DEL_AYUDO LIKE '%".$search['NOMBRE_DEL_AYUDO']."%' OR ";}
if($search['OBSERVACIONES_1']!=""){
$sWhere2.="  $tables.OBSERVACIONES_1 LIKE '%".$search['OBSERVACIONES_1']."%' OR ";}
if($search['FECHA_DE_LLENADO']!=""){
$sWhere2.="  $tables.FECHA_DE_LLENADO LIKE '%".$search['FECHA_DE_LLENADO']."%' OR ";}
if($search['hiddenpagoproveedores']!=""){
$sWhere2.="  $tables.hiddenpagoproveedores LIKE '%".$search['hiddenpagoproveedores']."%' OR ";}
if($search['ADJUNTAR_COTIZACION']!=""){
$sWhere2.="  $tables.ADJUNTAR_COTIZACION LIKE '%".$search['ADJUNTAR_COTIZACION']."%' OR ";}
if($search['TImpuestosRetenidosIVA']!=""){
$sWhere2.="  $tables.TImpuestosRetenidosIVA LIKE '%".$search['TImpuestosRetenidosIVA']."%' OR ";}

if($search['TImpuestosRetenidosISR']!=""){
$sWhere2.="  $tables.TImpuestosRetenidosISR LIKE '%".$search['TImpuestosRetenidosISR']."%' OR ";}

if($search['descuentos']!=""){
$sWhere2.="  $tables.descuentos LIKE '%".$search['descuentos']."%' OR ";}

if($search['EJECUTIVOTARJETA']!=""){
$sWhere2.="  $tables.EJECUTIVOTARJETA LIKE '%".$search['EJECUTIVOTARJETA']."%' OR ";}

if($search['TIPO_CAMBIOP']!=""){
$sWhere2.="  $tables.TIPO_CAMBIOP LIKE '%".$search['TIPO_CAMBIOP']."%' OR ";}
if($search['TOTAL_ENPESOS']!=""){
$sWhere2.="  $tables.TOTAL_ENPESOS LIKE '%".$search['TOTAL_ENPESOS']."%' OR ";}

if($search['NOMBRE_COMERCIAL']!=""){
$sWhere2.="  $tables.NOMBRE_COMERCIAL LIKE '%".$search['NOMBRE_COMERCIAL']."%' OR ";}

if($search['IVA']!=""){
$sWhere2.="  $tables.IVA LIKE '%".$search['IVA']."%' OR ";}

if($search['UUID']!=""){
$sWhere2.="  $tables2.UUID = '".$search['UUID']."' OR ";}

if($search['metodoDePago']!=""){
$sWhere2.="  $tables2.metodoDePago = '".$search['metodoDePago']."' OR ";}

if($search['total']!=""){
$totalf = str_replace(',','',str_replace('$','',$search['total']));
$sWhere2.="  $tables2.total = '".$totalf."' OR ";}

if($search['serie']!=""){
$sWhere2.="  $tables2.serie = '".$search['serie']."' OR ";}

if($search['folio']!=""){
$sWhere2.="  $tables2.folio = '".$search['folio']."' OR ";}

if($search['regimenE']!=""){
$sWhere2.="  $tables2.regimenE = '".$search['regimenE']."' OR ";}

if($search['UsoCFDI']!=""){
$sWhere2.="  $tables2.UsoCFDI = '".$search['UsoCFDI']."' OR ";}

if($search['TImpuestosTrasladados']!=""){
$TImpuestosTrasladados = str_replace(',','',str_replace('$','',$search['TImpuestosTrasladados']));
$sWhere2.="  $tables2.TImpuestosTrasladados = ".$TImpuestosTrasladados." OR ";}

if($search['TImpuestosRetenidos']!=""){
$TImpuestosRetenidos = str_replace(',','',str_replace('$','',$search['TImpuestosRetenidos']));
$sWhere2.="  $tables2.TImpuestosRetenidos = ".$TImpuestosRetenidos." OR ";}

if($search['Version']!=""){
$sWhere2.="  $tables2.Version = '".$search['Version']."' OR ";}

if($search['tipoDeComprobante']!=""){
$sWhere2.="  $tables2.tipoDeComprobante = '".$search['tipoDeComprobante']."' OR ";}

if($search['condicionesDePago']!=""){
$sWhere2.="  $tables2.condicionesDePago = '".$search['condicionesDePago']."' OR ";}

if($search['fechaTimbrado']!=""){
$sWhere2.="  $tables2.fechaTimbrado = '".$search['fechaTimbrado']."' OR ";}

if($search['nombreR']!=""){
$sWhere2.="  $tables2.nombreR = '".$search['nombreR']."' OR ";}

if($search['rfcR']!=""){
$sWhere2.="  $tables2.rfcR = '".$search['rfcR']."' OR ";}

if($search['Moneda']!=""){
$sWhere2.="  $tables2.Moneda = '".$search['Moneda']."' OR ";}

if($search['TipoCambio']!=""){
$sWhere2.="  $tables2.TipoCambio = '".$search['TipoCambio']."' OR ";}

if($search['ValorUnitarioConcepto']!=""){
$sWhere2.="  $tables2.ValorUnitarioConcepto = '".$search['ValorUnitarioConcepto']."' OR ";}

if($search['Cantidad']!=""){
$sWhere2.="  $tables2.Cantidad like '%".$search['Cantidad']."%' OR ";}

if($search['ClaveUnidad']!=""){
$sWhere2.="  $tables2.ClaveUnidad like '%".$search['ClaveUnidad']."%' OR ";}

if($search['ClaveProdServ']!=""){
$sWhere2.="  $tables2.ClaveProdServ = '".$search['ClaveProdServ']."' OR ";}

if($search['RFC_RECEPTOR']!=""){
$sWhere2.="  $tables2.RFC_RECEPTOR = '".$search['RFC_RECEPTOR']."' OR ";}

if($search['CantidadConcepto']!=""){
$sWhere2.="  $tables2.CantidadConcepto = '".$search['CantidadConcepto']."' OR ";}

if($search['ImporteConcepto']!=""){
$sWhere2.="  $tables2.ImporteConcepto = '".$search['ImporteConcepto']."' OR ";}

if($search['UnidadConcepto']!=""){
$sWhere2.="  $tables2.UnidadConcepto = '".$search['UnidadConcepto']."' OR ";}

if($search['TUA']!=""){
	$TUA = str_replace(',','',str_replace('$','',$search['TUA']));
$sWhere2.="  $tables2.TUA = '".$TUA."' OR ";}

if($search['TuaTotalCargos']!=""){
	$TuaTotalCargos = str_replace(',','',str_replace('$','',$search['TuaTotalCargos']));
$sWhere2.="  $tables2.TuaTotalCargos = '".$TuaTotalCargos."' OR ";}

if($search['Descuento']!=""){
	$Descuento = str_replace(',','',str_replace('$','',$search['Descuento']));
$sWhere2.="  $tables2.Descuento = '".$Descuento."' OR ";}

if($search['subTotal']!=""){
	$subTotal = str_replace(',','',str_replace('$','',$search['subTotal']));
$sWhere2.="  $tables2.subTotal = '".$subTotal."' OR ";}

if($search['IMPUESTO_HOSPEDAJE']!=""){
	$IMPUESTO_HOSPEDAJE = str_replace(',','',str_replace('$','',$search['IMPUESTO_HOSPEDAJE']));
$sWhere2.="  $tables2.IMPUESTO_HOSPEDAJE = '".$IMPUESTO_HOSPEDAJE."' OR ";}

if($search['propina']!=""){
	$propina = str_replace(',','',str_replace('$','',$search['propina']));
$sWhere2.="  $tables2.propina = '".$propina."' OR ";}







IF($sWhere2!=""){
			$sWhere22 = substr($sWhere2,0,-3);
			$sWhere3  = ' ('.$sWhere22.') ';
			$sWhere3  = ' '.$sWhereCC.' where ( ('.$sWhere3.') ) ';			
		}ELSE{
					
		$sWhere3  = ' '.$sWhereCC.' ';
		}
		$sWhere3campo.=" $tables.id desc ";		
        $sWhere3 .= " order by ".$sWhere3campo;


		$sql="SELECT $campos , 07COMPROBACION.id as 07COMPROBACIONid FROM $tables LEFT JOIN $tables2 $sWhere $sWhere3 LIMIT $offset,$per_page";
		
		 $query=$this->mysqli->query($sql);
	     $sql1="SELECT $campos , 07COMPROBACION.id as 07COMPROBACIONid FROM  $tables LEFT JOIN $tables2 $sWhere $sWhere3 ";
		$nums_row=$this->countAll($sql1);
		
		$this->setCounter($nums_row);
		return $query;
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
