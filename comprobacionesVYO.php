<?php

function obtenerBancoOrigenPorEjecutivo($dbConn, $idRelacion) {
    static $cache = array();

    $idRelacion = trim((string) $idRelacion);

    if ($idRelacion === '') {
        return '';
    }

    if (isset($cache[$idRelacion])) {
        return $cache[$idRelacion];
    }

    if (!($dbConn instanceof mysqli)) {
        return '';
    }

    $banco = '';
    $idRelacionSafe = mysqli_real_escape_string($dbConn, $idRelacion);
    $sql = "SELECT TBANCO
            FROM 01Tempresarial
            WHERE idRelacion = '{$idRelacionSafe}'
              AND TBANCO IS NOT NULL
              AND TRIM(TBANCO) <> ''
            ORDER BY id DESC
            LIMIT 1";

    if ($resultado = mysqli_query($dbConn, $sql)) {
        if ($row = mysqli_fetch_assoc($resultado)) {
            $banco = trim($row['TBANCO']);
        }
        mysqli_free_result($resultado);
    }

    $cache[$idRelacion] = $banco;

    return $banco;
}

/**
        --------------------------
        Autor: Sandor Matamoros
        Programer: Fatima Arellano
	Propietario: EPC
	fecha sandor: 
    fecha fatis : 08/04/2024
	----------------------------
 
*/

?>



<?php
$connecDB = $conexion->db();


 $_SESSION['where'] = ISSET($_SESSION['where'])?$_SESSION['where']: " ";
 $_SESSION['where2'] = '';
 $_SESSION['where'] = " ";
 $results = mysqli_query($connecDB,"SELECT COUNT(*) FROM 07COMPROBACION ".$_SESSION['where']." order by id desc  ");

$get_total_rows = mysqli_fetch_array($results); //total records
$item_per_page = 7;
//break total records into pages
$pages = 1000;


?>

<script type="text/javascript">
$(document).ready(function() {
	
	$("#results").load("comprobacionesVYO/fetch_pagesPP.php");  //initial page number to load
	
	$(".pagination").bootpag({
	   total: <?php echo $pages; ?>,
	   page: 1,
	   maxVisible: 5 
	}).on("page", function(e, num){
		e.preventDefault();
		$("#results").prepend('<div class="loading-indication"><img src="inventario/ajax-loader.gif" /> Cargando datos...</div>');
		$("#results").load("comprobacionesVYO/fetch_pagesPP.php", {'page':num});
	});

});

/*
function calcular(){
    var arr = document.getElementsByClassName("total");
   
    var tot=0;
    for(var i=0;i<arr.length;i++){
        if(parseFloat(arr[i].value))
            tot += parseFloat(arr[i].value);
    }
    document.getElementById('MONTO_DEPOSITAR').value = tot;
}
*/


function calcular() {
    const numberNoCommas = (x) => {
        return x.toString().replace(/,/g, "");
    }

    const numberWithCommas = (x) => {
        //return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		const num = parseFloat(x);
		if (isNaN(num)) return '';
		return num.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function formatInputPreservingCursor(inputElement, value) {
        const originalValue = inputElement.value;
        const cursorPos = inputElement.selectionStart;

        const commasBefore = originalValue.slice(0, cursorPos).split(',').length - 1;

        const formattedValue = numberWithCommas(value);
        inputElement.value = formattedValue;

        let newCursorPos = cursorPos - commasBefore;
        let i = 0, charsPassed = 0;
        while (charsPassed < newCursorPos && i < formattedValue.length) {
            if (formattedValue[i] !== ',') {
                charsPassed++;
            }
            i++;
        }
        inputElement.setSelectionRange(i, i);
    }

    // Listener para inputs tipo .tol
    const inputs = document.querySelectorAll(".total");

    inputs.forEach(input => {
        input.addEventListener("keydown", function (e) {
            const keyCode = e.keyCode || e.which;

            // Teclas numéricas (teclado principal del 0 al 9 o numpad 0 al 9)
            const isNumberKey =
                (keyCode >= 48 && keyCode <= 57) || // Teclado principal
                (keyCode >= 96 && keyCode <= 105) ||
				(keyCode === 46) ||
				(keyCode === 8 );  // Numpad

            if (isNumberKey) {
                // Esperar a que el valor se actualice antes de formatear
                setTimeout(() => {
                    const arr = document.getElementsByClassName("total");

                    let MONTO_PROPINA_elem = document.getElementsByName("MONTO_PROPINA")[0];
                    let MONTO_FACTURA_elem = document.getElementsByName("MONTO_FACTURA")[0];
                    let IVA_elem = document.getElementsByName("IVA")[0];
                    let IMPUESTO_HOSPEDAJE_elem = document.getElementsByName("IMPUESTO_HOSPEDAJE")[0];
					
                    let MONTO_PROPINA2 = numberNoCommas(MONTO_PROPINA_elem.value);
                    let MONTO_FACTURA2 = numberNoCommas(MONTO_FACTURA_elem.value);
                    let IVA2 = numberNoCommas(IVA_elem.value);
                    let IMPUESTO_HOSPEDAJE2 = numberNoCommas(IMPUESTO_HOSPEDAJE_elem.value);
					
                    /*let tot = 0;
                    for (let i = 0; i < arr.length; i++) {
                        if (parseFloat(numberNoCommas(arr[i].value)))
							
                            tot += parseFloat(numberNoCommas(arr[i].value));
							
                    }*/
					let tot = 0;
					for (let i = 0; i < arr.length; i++) {
						const inputName = arr[i].getAttribute("name");
						const value = parseFloat(numberNoCommas(arr[i].value)) || 0;

						if (["TImpuestosRetenidosIVA", "TImpuestosRetenidosISR", "descuentos"].includes(inputName)) {
							tot -= value; // Se RESTA
						} else {
							tot += value; // Se SUMA
						}
					}
					
					
                    formatInputPreservingCursor(document.getElementById('MONTO_DEPOSITAR'), tot);
                    formatInputPreservingCursor(MONTO_PROPINA_elem, MONTO_PROPINA2);
                    formatInputPreservingCursor(MONTO_FACTURA_elem, MONTO_FACTURA2);
                    formatInputPreservingCursor(IVA_elem, IVA2);
                    formatInputPreservingCursor(IMPUESTO_HOSPEDAJE_elem, IMPUESTO_HOSPEDAJE2);					
                }, 0);
            }
        });
    });
}
		             function setCurrentFillingDate() {
                       const fechaInput = document.querySelector('input[name="FECHA_DE_LLENADO"]');
                       if(!fechaInput) {
                               return;
                       }
                       const now = new Date();
                       const pad = (value) => value.toString().padStart(2, '0');
                       const formatted = `${pad(now.getDate())}-${pad(now.getMonth() + 1)}-${now.getFullYear()} ${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
                       fechaInput.value = formatted;
               }
// Ejecutamos todo cuando cargue el DOM
document.addEventListener("DOMContentLoaded", calcular);


$(document).on('change','input[type="checkbox"]' ,function(e) {
    if(this.id=="MONTO_DEPOSITAR1") {
        if(this.checked) $('#FECHA_AUTORIZACION_RESPONSABLE').val(this.value);
        else $('#FECHA_AUTORIZACION_RESPONSABLE').val("");
    }
  
});
$(document).on('change','input[type="checkbox"]' ,function(e) {
    if(this.id=="MONTO_DEPOSITAR2") {
        if(this.checked) $('#FECHA_AUTORIZACION_AUDITORIA').val(this.value);
        else $('#FECHA_AUTORIZACION_AUDITORIA').val("");
    }
  
});
$(document).on('change','input[type="checkbox"]' ,function(e) {
    if(this.id=="MONTO_DEPOSITAR3") {
        if(this.checked) $('#FECHA_DE_LLENADO').val(this.value);
        else $('#FECHA_DE_LLENADO').val("");
    }
  
});


</script>

<!-- <link href="inventario/css/style2.css" rel="stylesheet" type="text/css"> -->





<div id="content">     
			<hr/>
	<strong> <P class="mb-0 text-uppercase">
<img src="includes/contraer31.png" id="mostrar1" style="cursor:pointer;"/>
<img src="includes/contraer41.png" id="ocultar1" style="cursor:pointer;"/>&nbsp;&nbsp;&nbsp;COMPROBACIÓN DE GASTOS-VYO</p></strong></div>

<div  id="mensajepagoproveedores2">
<div class="progress" style="width: 25%;">
									<div class="progress-bar" role="progressbar" style="width: <?php echo $pagoaproveedoress ; ?>%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><?php echo $pagoaproveedoress ; ?>%</div></div></div>


	        <div id="target1" style="display:block;"  class="content2">
      
        <div class="card">
          <div class="card-body">


					  
	<form class="row g-3 needs-validation was-validated" novalidate="" id="pagoaproveedoresform" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
	<?php if($conexion->variablespermisos('','comprobacionVYO','guardar')=='si'){ ?>
   <table  style="border-collapse: collapse;" border="1" class="table mb-0 table-striped">

				  
<tr>

<th scope="col">FACTURA </th>
<th scope="col">DATOS</th> 
</tr>

             
                 <tr  style="background: #d2faf1" > 
 
           
                 <th scope="row"> <label  style="width:300px"  for="formFileSm"  class="form-label">ADJUNTAR FACTURA FORMATO &nbsp;<a style="color:red;font:12px">(XML)</a></a><BR><a style="color:red;font:12px">SI NO TIENES POR EL MOMENTO EL ARCHIVO XML, <br>PRIMERO DEBES CAPTURAR EL NOMBRE COMERCIAL <br>Y DESPUÉS CARGAR EL PDF</a></label></th>
                 <td>
				 
	

		<div id="drop_file_zone" ondrop="upload_file(event,'ADJUNTAR_FACTURA_XML')" ondragover="return false" >
		<p>Suelta aquí o busca tu archivo</p>
		<p><input class="form-control form-control-sm" id="ADJUNTAR_FACTURA_XML" type="text" onkeydown="return false" onclick="file_explorer('ADJUNTAR_FACTURA_XML');"  VALUE="<?php echo $ADJUNTAR_FACTURA_XML; ?>" required /></p>
		<input type="file" name="ADJUNTAR_FACTURA_XML" id="nono"/>
		<div id="1ADJUNTAR_FACTURA_XML">
		<?php
		if($ADJUNTAR_FACTURA_XML!=""){echo "<a target='_blank' href='includes/archivos/".$ADJUNTAR_FACTURA_XML."'></a>"; 
		}?></div>
		</div>
		

				 
<div id="2ADJUNTAR_FACTURA_XML"><?php 

$listadosube = $pagoproveedores->Listado_subefacturadocto('ADJUNTAR_FACTURA_XML');

while($rowsube=mysqli_fetch_array($listadosube)){
	echo "<a target='_blank' href='includes/archivos/".$rowsube['ADJUNTAR_FACTURA_XML']."' id='A".$rowsube['id']."' >Visualizar!</a> "." <span id='".$rowsube['id']."' class='view_dataSBborrar2' style='cursor:pointer;color:blue;'>Borrar!</span> <span > ".$rowsube['fechaingreso']."</span>".'<br/>';		
	
}
	$NUMERO_CONSECUTIVO_PROVEE = '';	$FECHA_DE_PAGO = '';
	$regreso = $pagoproveedores->variable_SUBETUFACTURA();
	$url = __ROOT1__.'/includes/archivos/'.$regreso['ADJUNTAR_FACTURA_XML'];
if( file_exists($url) ){
	$regreso = $conexion2->lectorxml($url);
	
	$Version = $regreso['Version'];
	$Descuento = $regreso['Descuento'];
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
	$total = $regreso['total'];
	$serie = $regreso['serie'];
	$folio = $regreso['folio'];
	$LugarExpedicion = $regreso['LugarExpedicion'];
	
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
    $impueRdesglosado002 = $regreso['impueRdesglosado002'];/*IVA*/
	$impueRdesglosado001 = $regreso['impueRdesglosado001'];/*ISR*/
	/*nuevo*/

	$fechaInicio=strtotime(date('Y-m-d'));
	$FECHA_domingo = date('Y-m-d', strtotime('next monday', $fechaInicio));
	$FECHA_jueves = date('Y-m-d', strtotime('next Thursday', strtotime($FECHA_domingo)));
	$FECHA_DE_PAGO = $FECHA_jueves;//'2023-08-03';//. $conexion2->fechaEs($FECHA_jueves);

	/*nuevo*/
		
	$NUMERO_CONSECUTIVO_PROVEE = $pagoproveedores->select_02XML() + 1;
}
?></div>		

         
</td>
             </tr>
             <tr  style="background: #d2faf1" >  
            
             
                 <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">ADJUNTAR FACTURA FORMATO (PDF)</label></th>
				 
				 
             <td>
      


             <div id="drop_file_zone" ondrop="upload_file(event,'ADJUNTAR_FACTURA_PDF')" ondragover="return false" >
		<p>Suelta aquí o busca tu archivo</p>
		<p><input class="form-control form-control-sm" id="ADJUNTAR_FACTURA_PDF" type="text" onkeydown="return false" onclick="file_explorer('ADJUNTAR_FACTURA_PDF');"  VALUE="<?php echo $ADJUNTAR_FACTURA_PDF; ?>" required /></p>
		<input type="file" name="ADJUNTAR_FACTURA_PDF" id="nono"/>
		<div id="1ADJUNTAR_FACTURA_PDF">
		<?php
		if($ADJUNTAR_FACTURA_PDF!=""){echo "<a target='_blank' href='includes/archivos/".$ADJUNTAR_FACTURA_PDF."'></a>"; 
		}?></div>
		</div>
		

				 
				 <div id="2ADJUNTAR_FACTURA_PDF"><?php $listadosube = $pagoproveedores->Listado_subefacturadocto('ADJUNTAR_FACTURA_PDF');

while($rowsube=mysqli_fetch_array($listadosube)){
	echo "<a target='_blank' href='includes/archivos/".$rowsube['ADJUNTAR_FACTURA_PDF']."' id='A".$rowsube['id']."' >Visualizar!</a> "." <span id='".$rowsube['id']."' class='view_dataSBborrar2' style='cursor:pointer;color:blue;'>Borrar!</span><span > ".$rowsube['fechaingreso']."</span>".'<br/>';	
}


				 ?></div>				 
				 </td>
				 

				 
                 </tr>



                 <tr  style="background:#fcf3cf"> 
                 <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">NOMBRE COMERCIAL</label></th>
                 <td><input type="text" class="form-control"  required=""  value="<?php echo $NOMBRE_COMERCIAL; ?>" name="NOMBRE_COMERCIAL" placeholder="NOMBRE COMERCIAL"></td>
                 </tr>
				 
                 <tr style="background: #d2faf1"> 
      
            
                 <th scope="row"> <label  style="width:300px" for="RAZON_SOCIAL" class="form-label">RAZÓN SOCIAL</label></th>
                 <td>
				 
				 <div id="RAZON_SOCIAL2">
				 
				 <input type="text" class="form-control" id="RAZON_SOCIAL" required=""  value="<?php echo $nombreE; ?>" name="RAZON_SOCIAL" placeholder="RAZÓN SOCIAL">
				 </div>
				 </td>
                 </tr>
                 <tr  style="background:#fcf3cf"> 
              
                
                 <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">RFC DEL PROVEEDOR:</label></th>
                 <td>
				 
				 <div id="RFC_PROVEEDOR2">
				 
				 <input type="text" class="form-control" id="RFC_PROVEEDOR" required=""  value="<?php echo $rfcE; ?>" name="RFC_PROVEEDOR" placeholder="RFC DEL PROVEEDOR">
				 
				 </div>
				 </td>
                 </tr>
                 <tr style="background: #d2faf1">
                 
                 <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">No. DE EVENTO:</label></th>
                 <td>
				 
<!--<input type="text" class="form-control" id="NUMERO_EVENTO" required=""  value="<?php echo $NUMERO_EVENTO; ?>" name="NUMERO_EVENTO"  placeholder="No. DE EVENTO">
				 
				  <script type="text/javascript" >
			
	        $('#NUMERO_EVENTO').typeahead({
            source: function (busqueda, resultado) {
                $.ajax({
                    url: "pagoproveedores/controladorPP.php",
					data: 'busqueda=' + busqueda,            
                    dataType: "json",
                    type: "POST",
                    success: function (data) {
						resultado($.map(data, function (item) {
							return item;
                        }));
                    }
                });
            }
        });
		
</script>-->

<div style="">
  <select class="form-select mb-3" id="NUMERO_EVENTO" name="NUMERO_EVENTO" onchange="buscanombreevento(1);">
  <option value="<?php echo $numero_evento_get; ?>"><?php echo $numero_evento_get; ?></option>
  </select>
</div>
<script type="text/javascript">
      $('#NUMERO_EVENTO').select2({
        placeholder: 'ESCRIBE Y SELECCIONA UNA OPCIÓN',
        ajax: {
          url: 'comprobaciones/controladorPP.php',
          dataType: 'json',
          delay: 250,
		  type:'post',
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
      });
	  
function buscanombreevento(page){
			var NUMERO_EVENTO=$("#NUMERO_EVENTO").val();
var parametros = {
			"action":"ajax",
			"NUMERO_EVENTO":NUMERO_EVENTO
};
			$("#loader").fadeIn('slow');
			$.ajax({
				url:'comprobaciones/controladorPP.php',
				type: 'POST',				
				data: parametros,
				 beforeSend: function(objeto){
				$("#loader").html("Cargando...");
			  },
				success:function(data){
					/*$(".datos_ajax").html(data).fadeIn('slow');
					$("#loader").html("");*/
	document.getElementsByName('NOMBRE_EVENTO')[0].value = data;		
				}
			})
		}
	  
</script>

				 
				 </td>
                 </tr>
				 
                 <tr  style="background:#fcf3cf">
                
                 <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">NOMBRE DEL EVENTO:</label></th>
                 <td><input type="text" class="form-control" id="NOMBRE_EVENTO" required=""   value="<?php echo $NOMBRE_EVENTO_get ?>"  name="NOMBRE_EVENTO" placeholder="NOMBRE DEL EVENTO"></td>
                 </tr>
                 <tr  style="background: #d2faf1">
                 
                 <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">MOTIVO DEL GASTO:<br><a style="color:red;font-size:11px">OBLIGATORIO</a></label></th>
                 <td><input type="text" class="form-control" id="validationCustom03" required=""  value="<?php echo $MOTIVO_GASTO; ?>" name="MOTIVO_GASTO"placeholder="MOTIVO DEL GASTO "></td>
                 </tr>
                 <tr style="background:#fcf3cf"> 

                 <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">CONCEPTO DE LA FACTURA:</label></th>
                 <td><div id="CONCEPTO_PROVEE2"><input type="text" class="form-control" id="validationCustom03" required=""  value="<?php echo $Descripcion; ?>" name="CONCEPTO_PROVEE"placeholder="CONCEPTO DE LA FACTURA"></div></td>
                 </tr>
				 
            <tr style="background:#fcf3cf">
                 <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">SUB TOTAL:</label></th>
              
                 
				 
				 <td> 
				 
				<div id="2MONTO_FACTURA">			 
				 <div class="input-group mb-3"> <span class="input-group-text">$</span> <input type="text"  style="width:300px;height:40px;"  id="MONTO_FACTURA" required="" onkeyup="calcular()"   value="<?php echo $subTotal; ?>" name="MONTO_FACTURA" class="total"  placeholder="SUB TOTAL">
				
				</div></div>
				 </td>
                 </tr>
				 				 <tr style="background:#fcf3cf">
				 <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">IVA:</label></th>
              
                 
				 
				 <td> 
				 
				<div id="2IVA">			 
     <div class="input-group mb-3"> <span class="input-group-text">$</span> <input type="text"  style="width:300px;height:40px;"  id="IVA" required="" onkeyup="calcular()"   value="<?php echo $TImpuestosTrasladados; ?>"  name="IVA" class="total"  placeholder="IVA">
				
				</div></div>
				 </td>
                 </tr>
				 
				 				 <tr style="background:#fcf3cf">
				 <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">IMPUESTOS RETENIDOS &nbsp;<a style="color:red;font:12px">(IVA)</a></label></th>				 
				 <td> 
				 
				<div id="2TImpuestosRetenidosIVA">			 
     <div class="input-group mb-3"> <span class="input-group-text">$</span> <input type="text"  style="width:300px;height:40px;"  id="TImpuestosRetenidosIVA" required=""    value="<?php echo $impueRdesglosado002; ?>"  name="TImpuestosRetenidosIVA"  class="total" placeholder="IMPUESTOS RETENIDOS IVA">
				
				</div></div>
				 </td>
                 </tr>
				 
				 				 <tr style="background:#fcf3cf">
				 <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">IMPUESTOS RETENIDOS &nbsp;<a style="color:red;font:12px">(ISR)</label></th>				 
				 <td> 
				 
				<div id="2TImpuestosRetenidosISR">			 
     <div class="input-group mb-3"> <span class="input-group-text">$</span> <input type="text"  style="width:300px;height:40px;"  id="TImpuestosRetenidosISR" required=""   value="<?php echo $impueRdesglosado001; ?>"  name="TImpuestosRetenidosISR"  class="total" placeholder="IMPUESTOS RETENIDOS ISR">
				
				</div></div>
				 </td>
                 </tr>				 
                
                 <tr style="background: #d2faf1">

                 <th scope="row"> <label for="validationCustom03" class="form-label"><a style="color:red;font:12px">FAVOR DE PONER EL:&nbsp;</a>MONTO DE LA PROPINA O <br>SERVICIO INCLUIDO O NO EN LA FACTURA</label></th>
          
				 <td>
				 <div class="input-group mb-3"> <span class="input-group-text">$</span> <input type="text"class="total"  style="width:300px;height:40px;"  id="total" required=""  onkeyup="calcular()"   value="<?php echo $MONTO_PROPINA; ?>" name="MONTO_PROPINA"placeholder="MONTO DE LA PROPINA">
			
				 
				 </td>
                 </tr></div>
                 <tr style="background: #d2faf1">           
                 <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label"><a style="color:red;font:12px">FAVOR DE PONER EL:&nbsp;</a> IMPUESTO SOBRE <BR>HOSPEDAJE MÁS EL IMPUESTO DE SANEAMIENTO:</label></th>			
				 <td>
				 <div class="input-group mb-3"> <span class="input-group-text">$</span> <input type="text"class="total"  style="width:300px;height:40px;"  id="total" required=""  onkeyup="calcular()"   value="<?php echo $IMPUESTO_HOSPEDAJE; ?>" name="IMPUESTO_HOSPEDAJE"placeholder="IMPUESTO HOSPEDAJE">
			    
				 
				 </td>
                 </tr></div>


 				  <tr style="background:#fcf3cf">
				 <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">DESCUENTO:</label></th>				 
				 <td> 
				 
				<div id="2descuentos">			 
     <div class="input-group mb-3"> <span class="input-group-text">$</span> <input type="text"  style="width:300px;height:40px;"  id="descuentos" required=""    value="<?php echo $Descuento; ?>"  name="descuentos"  class="total" placeholder="DESCUENTO">
				
				</div></div>
				 </td>
                 </tr>
				 
                    <tr style="background:#fcf3cf">

                 <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">TOTAL:</label></th>
                 <td>
				 <div id="2MONTO_DEPOSITAR">
             <div class="input-group mb-3"> <span class="input-group-text">$</span><input type="text" class="form-control" id="MONTO_DEPOSITAR" required=""   value="<?php echo $total; ?>" name="MONTO_DEPOSITAR"placeholder="TOTAL" readonly="readonly">
				
				 </td>
                 </tr> </div> </div>
                 <tr style="background:#fcf3cf">

                 <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">TIPO DE MONEDA O DIVISA:</label></th>
                
                 <td>
				 
             <div id="TIPO_DE_MONEDA2">				 
         <select class="form-select mb-3" aria-label="Default select example" id="validationCustom02" required="" name="TIPO_DE_MONEDA">                
                  <option style="background: #c9e8e8" name="TIPO_DE_MONEDA" value="MXN" <?php if($Moneda=='MXN'){echo "selected";} ?>>MXN (Peso mexicano)</option>
                     <option style="background: #a3e4d7" name="TIPO_DE_MONEDA" value="USD" <?php if($Moneda=='USD'){echo "selected";} ?>>USD (Dolar)</option>
                     <option style="background: #e8f6f3" name="TIPO_DE_MONEDA" value="EUR" <?php if($Moneda=='EUR'){echo "selected";} ?>>EUR (Euro)</option>
                     <option style="background: #fdf2e9" name="TIPO_DE_MONEDA"value="GBP" <?php if($Moneda=='GBP'){echo "selected";} ?>>GBP (Libra esterlina)</option>
                     <option style="background: #eaeded" name="TIPO_DE_MONEDA" value="CHF" <?php if($Moneda=='CHF'){echo "selected";} ?>>CHF (Franco suizo)</option>
                     <option style="background: #fdebd0" name="TIPO_DE_MONEDA" value="CNY" <?php if($Moneda=='CNY'){echo "selected";} ?>>CNY (Yuan)</option>
                     <option style="background: #ebdef0" name="TIPO_DE_MONEDA" value="JPY" <?php if($Moneda=='JPY'){echo "selected";} ?>>JPY (Yen japonés)</option>
                     <option style="background: #d6eaf8" name="TIPO_DE_MONEDA" value="HKD" <?php if($Moneda=='HKD'){echo "selected";} ?>>HKD (Dólar hongkonés)</option>
                     <option style="background: #fef5e7" name="TIPO_DE_MONEDA" value="CAD" <?php if($Moneda=='CAD'){echo "selected";} ?>>CAD (Dólar canadiense)</option>
                     <option style="background: #ebedef" name="TIPO_DE_MONEDA" value="AUD" <?php if($Moneda=='AUD'){echo "selected";} ?>>AUD (Dólar australiano)</option>
                     <option style="background: #fbeee6" name="TIPO_DE_MONEDA" value="BRL" <?php if($Moneda=='BRL'){echo "selected";} ?>>BRL (Real brasileño)</option>
                     <option style="background: #e8f6f3" name="TIPO_DE_MONEDA" value="RUB" <?php if($Moneda=='RUB'){echo "selected";} ?>>RUB  (Rublo ruso)</option>

                     </select> 
                        </div>
                 
                 </td>                    

             </tr>
				     <tr style="background:#fcf3cf">				 
                 <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">TIPO DE CAMBIO:</label></th>
             			
				 <td>
             <div class="input-group mb-3"> <span class="input-group-text">$</span> <input type="text" style="width:300px;height:40px;"  value="<?php echo $TIPO_CAMBIOP; ?>" name="TIPO_CAMBIOP" onkeyup="comasainput2('TIPO_CAMBIOP')"  placeholder="TIPO DE CAMBIO" >
				 </div>
 </td>
				 </tr>
				     <tr style="background: #d2faf1">				 
				 <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">TOTAL DE LA CONVERSIÓN:</label></th>
             
                
				 
			
				 <td>
             <div class="input-group mb-3"> <span class="input-group-text">$</span> <input type="text" style="width:300px;height:40px;"  value="<?php echo $TOTAL_ENPESOS; ?>" name="TOTAL_ENPESOS" onkeyup="comasainput2('TOTAL_ENPESOS')"  placeholder="TOTAL DE LA CONVERSIÓN" >
				 </div>
 </td>
				 </tr>
				 
				 
				 
				 
				 
                 <tr style="background:#fcf3cf">

                 <th><label style="width: 300px"  class="form-label">FORMA DE PAGO:</label></th>
				 
				 
             <td style="width: 45%;">



<div id="2PFORMADE_PAGO">



<select name="PFORMADE_PAGO" class="form-select mb-3"  aria-label="Default select example">

    <option style="background:#dee6fc"  <?php if($formaDePago=='04'){echo "selected ";} ?> value="04">04 TARJETA DE CREDITO</option>
    <option style="background:#f2b4f5"  <?php if($formaDePago=='03'){echo "selected";} ?> value="03">03 TRANSFERENCIA ELECTRONICA DE FONDOS</option>
    <option style="background:#ddf5da" <?php if($formaDePago=='01'){echo "selected";} ?> value="01">01 EFECTIVO</option>
    <option style="background:#fceade" <?php if($formaDePago=='02'){echo "selected";} ?> value="02">02 CHEQUE NOMITATIVO</option>
    <option style="background:#f6fcde" <?php if($formaDePago=='05'){echo "selected";} ?> value="05">05 MONEDERO ELECTRONICO</option>
    <option style="background:#dee2fc" <?php if($formaDePago=='06'){echo "selected";} ?> value="06">06 DINERO ELECTRONICO</option>
    <option style="background:#f9e5fa" <?php if($formaDePago=='08'){echo "selected";} ?> value="08">08 VALES DE DESPENSA</option>
    <option style="background:#eefcde" <?php if($formaDePago=='28'){echo "selected";} ?> value="28">28 TARJETA DE DEBITO</option>
    <option style="background:#fcfbde" <?php if($formaDePago=='29'){echo "selected";} ?> value="29">29 TARJETA DE SERVICIO</option>
    <option style="background:#f9e5fa" <?php if($formaDePago=='99'){echo "selected";} ?> value="99">99 OTRO</option>

</select>


			  
        
    <div/>
        </td>

        </tr>

  
				 
             
				 
                 <tr style="background: #d2faf1"> 

                 <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">FECHA DE CARGO EN TDC<br><a style="color:red;font-size:11px">OBLIGATORIO</a></label></th>
                 <td><input type="date" class="form-control" id="validationCustom03" required=""  value="<?php echo $FECHA_A_DEPOSITAR; ?>" name="FECHA_A_DEPOSITAR" placeholder="FECHA A DEPOSITAR"></td>
                 </tr>
                               <tr  style="background:#fcf3cf" > 

                 <th scope="row">  <label  style="width:300px" for="validationCustom02" class="form-label">STATUS DE PAGO:</label></th>
                 <td>
				 
				 <select class="form-select mb-3" aria-label="Default select example" id="validationCustom02" value="<?php echo $STATUS_DE_PAGO; ?>" required="" name="STATUS_DE_PAGO"> 
                       
                         <option style="background:#f5deee " <?php if($STATUS_DE_PAGO=='PAGADO'){echo "selected";} ?> value="PAGADO">PAGADO</option>
                         <option style="background:#e1f5de " <?php if($STATUS_DE_PAGO=='APROBADO'){echo "selected";} ?> value="APROBADO">APROBADO</option>
                         <option style="background:#f5f4de " <?php if($STATUS_DE_PAGO=='RECHAZADO'){echo "selected";} ?> value="RECHAZADO">RECHAZADO</option>
						 </select>
						 
						 </td>

                 </tr>
				 
				 
                 <tr  style="background: #d2faf1" > 


                 <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">ADJUNTAR COTIZACIÓN O REPORTE: (CUAQUIER FORMATO)</label></th>
                 <td>



		<div id="drop_file_zone" ondrop="upload_file(event,'ADJUNTAR_COTIZACION')" ondragover="return false" >
		<p>Suelta aquí o busca tu archivo</p>
		<p><input class="form-control form-control-sm" id="ADJUNTAR_COTIZACION" type="text" onkeydown="return false" onclick="file_explorer('ADJUNTAR_COTIZACION');"  VALUE="<?php echo $ADJUNTAR_COTIZACION; ?>" required /></p>
		<input type="file" name="ADJUNTAR_COTIZACION" id="nono"/>
		<div id="1ADJUNTAR_COTIZACION">
		<?php
		if($ADJUNTAR_COTIZACION!=""){echo "<a target='_blank' href='includes/archivos/".$ADJUNTAR_COTIZACION."'></a>"; 
		}?></div>
		</div>
		

				 
				 <div id="2ADJUNTAR_COTIZACION"><?php $listadosube = $pagoproveedores->Listado_subefacturadocto('ADJUNTAR_COTIZACION');

while($rowsube=mysqli_fetch_array($listadosube)){
	echo "<a target='_blank' href='includes/archivos/".$rowsube['ADJUNTAR_COTIZACION']."' id='A".$rowsube['id']."' >Visualizar!</a> "." <span id='".$rowsube['id']."' class='view_dataSBborrar2' style='cursor:pointer;color:blue;'>Borrar!</span><span > ".$rowsube['fechaingreso']."</span>".'<br/>';	
}
?></div>				 
				 </td>
                 </tr>
				 
				 


<tr>
  <th style="background:#d2faf1;text-align:left" scope="col">
    NOMBRE DEL EJECUTIVO TITULAR DE LA TARJETA:<br><a style="color:red;font-size:11px">OBLIGATORIO</a>
  </th>
  <td style="background:#d2faf1">
    <?php
    $queryper = $conexion->colaborador_generico_bueno();

    $selectHTML = '<select class="form-select mb-3" aria-label="Default select example"
                    id="EJECUTIVOTARJETA" name="EJECUTIVOTARJETA" required>
                    <option value="">SELECCIONA UNA OPCIÓN</option>';

    $fondos = ["fff0df","f4ffdf","dfffed","dffeff","dfe8ff","efdfff","ffdffd","efdfff","ffdfe9"];
    $num = 0;

   while($row = mysqli_fetch_array($queryper)) {
        if($num==8){$num=0;}else{$num++;}
        $color = $fondos[$num];

        // Combina nombre completo
        $nombreCompleto = trim($row['NOMBRE_1'].' '.$row['NOMBRE_2'].' '.$row['APELLIDO_PATERNO'].' '.$row['APELLIDO_MATERNO']);

        $bancoOrigen = obtenerBancoOrigenPorEjecutivo($connecDB, $row['idRelacion']);
        $dataBanco = htmlspecialchars($bancoOrigen, ENT_QUOTES, 'UTF-8');

        // Usa el ID como value
        $selectHTML .= '<option style="background:#'.$color.'"
                            data-banco="'.$dataBanco.'"
                            value="'.$row['idRelacion'].'">'.$nombreCompleto.'</option>';
    }

    $selectHTML .= '</select>';
    echo $selectHTML;
    ?>
  </td>
</tr>				 
				 
				 
				 
<tr style="background:#fcf3cf">
    <th> 
        <strong><label for="validationCustom03" class="form-label">INSTITUCIÓN BANCARIA:<br></label></strong>  
    </th>
    <td>
        <span id="desplegadoreset">
            <?php
            $encabezado = '';
            $option = '';
            $queryper = $conexion->desplegables07('COMPROBACION','BANCO_ORIGEN');
            
            // Almacenar y ordenar opciones
            $opciones = array();
            while($row1 = mysqli_fetch_array($queryper)) {
                $opciones[] = $row1;
            }
            usort($opciones, function($a, $b) {
                return strcasecmp($a['nombre_campo'], $b['nombre_campo']);
            });
            
            // Generar HTML
            $encabezado = '<select class="form-select mb-3" aria-label="Default select example" id="BANCO_ORIGEN" required="" name="BANCO_ORIGEN">
                           <option value="">SELECCIONA UNA OPCIÓN</option>';
            $fondos = array("fff0df","f4ffdf","dfffed","dffeff","dfe8ff","efdfff","ffdffd","efdfff","ffdfe9");
            $num = 0;
            
            foreach($opciones as $row1) {
                $num = ($num == 8) ? 0 : $num + 1;
                $select = ($BANCO_ORIGEN == $row1['nombre_campo']) ? "selected" : "";
                $option .= '<option style="background: #'.$fondos[$num].'" '.$select.' value="'.$row1['nombre_campo'].'">'.strtoupper($row1['nombre_campo']).'</option>';
            }
            echo $encabezado.$option.'</select>';			
             ?>
        </span>
    </td>
</tr>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const ejecutivotarjetaSelect = document.getElementById('EJECUTIVOTARJETA');
    const bancoOrigenSelect = document.getElementById('BANCO_ORIGEN');

    if (!ejecutivotarjetaSelect || !bancoOrigenSelect) {
        return;
    }

    const ensureOptionExists = (value) => {
        if (!value) {
            return null;
        }

        const normalizedValue = value.trim();
        if (!normalizedValue) {
            return null;
        }

        for (let i = 0; i < bancoOrigenSelect.options.length; i++) {
            const option = bancoOrigenSelect.options[i];
            if (option.value.trim().toUpperCase() === normalizedValue.toUpperCase()) {
                return option;
            }
        }

        let dynamicOption = bancoOrigenSelect.querySelector('option[data-banco-dynamic="true"]');
        if (!dynamicOption) {
            dynamicOption = document.createElement('option');
            dynamicOption.dataset.bancoDynamic = 'true';
            bancoOrigenSelect.appendChild(dynamicOption);
        }

        dynamicOption.value = normalizedValue;
        dynamicOption.textContent = normalizedValue.toUpperCase();

        return dynamicOption;
    };

    const updateBancoOrigen = () => {
        const selectedOption = ejecutivotarjetaSelect.options[ejecutivotarjetaSelect.selectedIndex];
        if (!selectedOption) {
            bancoOrigenSelect.value = '';
            return;
        }

        const banco = (selectedOption.getAttribute('data-banco') || '').trim();

        if (!banco) {
            bancoOrigenSelect.value = '';
            return;
        }

        const matchingOption = ensureOptionExists(banco);
        if (matchingOption) {
            matchingOption.selected = true;
        }
    };

    ejecutivotarjetaSelect.addEventListener('change', updateBancoOrigen);
    updateBancoOrigen();
});
</script>



<tr  style="background:#fcf3cf" >				 
<th scope="row"> <label  for="validationCustom03" class="form-label">NOMBRE DEL EJECUTIVO QUE INGRESO ESTA FACTURA:</label></th>
<td><input type="text" class="form-control" id="validationCustom03" style="background:#D8E4F2"  value="<?php echo $_SESSION["NOMBREUSUARIO"]; ?>" name="NOMBRE_DEL_AYUDO"placeholder="NOMBRE DEL EJECUTIVO"></td>
</tr>

<tr>
    <th style="background: #d2faf1; text-align:left" scope="col">NOMBRE DEL EJECUTIVO QUE REALIZÓ LA COMPRA:</th>
       <td  style="background: #d2faf1"  >
<?php
$encabezadoA = '';
$queryper = $conexion->colaborador_generico_bueno();
$encabezadoA = '<select class="form-select mb-3" aria-label="Default select example" id="NOMBRE_DEL_EJECUTIVO" required="" name="NOMBRE_DEL_EJECUTIVO"  placeholder="SELECIONA UNA OPCIÓN">
<option> SELECIONA UNA OPCIÓN</option>';


$fondos = array("fff0df","f4ffdf","dfffed","dffeff","dfe8ff","efdfff","ffdffd","efdfff","ffdfe9");
$num = 0;

while($row = mysqli_fetch_array($queryper))
{

if($num==8){$num=0;}else{$num++;}

$select='';
if($_SESSION['idem']==$row['idRelacion']){
$select='selected';
}

$option2 .= '<option style="background: #'.$fondos[$num].'" '.$select.' 
value="'.$row['NOMBRE_1'].' '.$row['NOMBRE_2'].' '.$row['APELLIDO_PATERNO'].' '.$row['APELLIDO_MATERNO'].'">'.$row['NOMBRE_1'].' '.$row['NOMBRE_2'].' '.$row['APELLIDO_PATERNO'].' '.$row['APELLIDO_MATERNO'].
'</option>';
}
echo $encabezadoA.$option2.'</select>';		
?></td>

    </tr>






<!-- <tr  style="background: #d2faf1">  


<th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">MATCH CON EL ESTADO DE CUENTA:</label></th>
<td>

<div id="drop_file_zone" ondrop="upload_file(event,'FOTO_ESTADO_PROVEE11')" ondragover="return false" >
<p>Suelta aquí o busca tu archivo</p>
<p><input class="form-control form-control-sm" id="FOTO_ESTADO_PROVEE11" type="text" onkeydown="return false" onclick="file_explorer('FOTO_ESTADO_PROVEE11');"  VALUE="<?php echo $FOTO_ESTADO_PROVEE11; ?>" required /></p>
<input type="file" name="FOTO_ESTADO_PROVEE11" id="nono"/>
<div id="1FOTO_ESTADO_PROVEE11">
<?php
if($FOTO_ESTADO_PROVEE11!=""){echo "<a target='_blank' href='includes/archivos/".$FOTO_ESTADO_PROVEE11."'></a>"; 
}?></div>
</div>


<div id="2FOTO_ESTADO_PROVEE11"><?php 
$listadosube = $pagoproveedores->Listado_subefacturadocto('FOTO_ESTADO_PROVEE11');

while($rowsube=mysqli_fetch_array($listadosube)){
echo "<a target='_blank' href='includes/archivos/".$rowsube['FOTO_ESTADO_PROVEE11']."'  id='A".$rowsube['id']."' >Visualizar!</a> "." <span id='".$rowsube['id']."' class='view_dataSBborrar2' style='cursor:pointer;color:blue;'>Borrar!</span><span > ".$rowsube['fechaingreso']."</span>".'<br/>';
}


?></div>	

</td>
</tr>-->


<tr  style="background: #d2faf1"> 

<th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">OBSERVACIONES:</label></th>
<td><input type="text" class="form-control" id="validationCustom03" required=""  value="<?php echo $OBSERVACIONES_1; ?>" name="OBSERVACIONES_1"placeholder="OBSERVACIONES 1"></td>
</tr>
<tr style="background: #d2faf1"> 

<th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">ADJUNTAR ARCHIVO RELACIONADO A ESTE GASTO: (CUALQUIER FORMATO)</label></th>
<td>

<div id="drop_file_zone" ondrop="upload_file(event,'ADJUNTAR_ARCHIVO_1')" ondragover="return false" >
<p>Suelta aquí o busca tu archivo</p>
<p><input class="form-control form-control-sm" id="ADJUNTAR_ARCHIVO_1" type="text" onkeydown="return false" onclick="file_explorer('ADJUNTAR_ARCHIVO_1');"  VALUE="<?php echo $ADJUNTAR_ARCHIVO_1; ?>" required /></p>
<input type="file" name="ADJUNTAR_ARCHIVO_1" id="nono"/>
<div id="1ADJUNTAR_ARCHIVO_1">
<?php
if($ADJUNTAR_ARCHIVO_1!=""){echo "<a target='_blank' href='includes/archivos/".$ADJUNTAR_ARCHIVO_1."'></a>"; 
}?></div>
</div>


<div id="2ADJUNTAR_ARCHIVO_1"><?php 
$listadosube = $pagoproveedores->Listado_subefacturadocto('ADJUNTAR_ARCHIVO_1');

while($rowsube=mysqli_fetch_array($listadosube)){
echo "<a target='_blank' href='includes/archivos/".$rowsube['ADJUNTAR_ARCHIVO_1']."'  id='A".$rowsube['id']."' >Visualizar!</a> "." <span id='".$rowsube['id']."' class='view_dataSBborrar2' style='cursor:pointer;color:blue;'>Borrar!</span><span > ".$rowsube['fechaingreso']."</span>".'<br/>';
}


?></div>	

</td>





 <input type="hidden" style="width:200px;" class="form-control" id="validationCustom03" value="<?php echo date('d-m-Y H:i:s'); ?>" name="FECHA_DE_LLENADO">
     
            
				
 
            
                          

	<input type="hidden" name="hiddenpagoproveedores" value="hiddenpagoproveedores">

                         </table>
				       
                           <table  style="border-collapse:collapse;" border="1";  class="table mb-0 table-striped" id="resettabla">

                    <tr>
                    <th scope="col">FACTURA</th>
                    <th  scope="col">DATOS DE LA FACTURA</th>
                    </tr>

                   <tr>
                    <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">NOMBRE RECEPTOR:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $nombreR; ?>" name="DATOS_DE_EPC_INNOVACC_JUST" placeholder="DATOS DE EPC, INNOVACC O JUST"></td>
                 </tr>
                 

                   <tr>
                    <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">RFC RECEPTOR:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $rfcR; ?>" name="DATOS_DE_EPC_INNOVACC_JUST" placeholder="DATOS DE EPC, INNOVACC O JUST"></td>
                 </tr>
				 
             
                 
                    <tr>
                    <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">RAZÓN SOCIAL DEL PROVEEDOR:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $nombreE; ?>" name="RAZON_SOCIAL_FACTURA" placeholder="RAZON SOCIAL DEL PROVEEDOR"></td>
                 </tr>

                 <tr>
                    <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">RFC:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $rfcE; ?>" name="rfcE" placeholder="RFC"></td>
                 </tr>
                 
				 <tr>
                 <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">REGÍMEN FISCAL:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $regimenE; ?>" name="RegimenFiscalReceptor" placeholder="REGIMEN FISCAL"></td>
                 </tr>
				 
		                 <tr>		 
                 <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">UUID:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $UUID; ?>" name="UUID" placeholder="UUID"></td>
                 </tr>

                 <tr>
                    <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">FOLIO:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $folio; ?>" name="FOLIO_FACTURA" placeholder="FOLIO"></td>
                 </tr>
				 
                 <tr>
                    <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">SERIE:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $serie; ?>" name="SERIE_FACTURA" placeholder="SERIE"></td>
                 </tr>
                 <tr>
                 <th scope="row"><label  style="width:300px" for="validationCustom03" class="form-label">FECHA DE FACTURA:</label></th>
                 <td><input  type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $fecha; ?>" name="FECHA_DE_EMISION" placeholder="FECHA DE FACTURA"></td>
                 </tr>
                
                 <tr>
                    <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">CANTIDAD:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $Cantidad; ?>" name="CANTIDAD" placeholder="CANTIDAD"></td>
                 </tr>
                 <tr>
                    <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">CLAVE DE PRODUCTO O SERVICIO:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $ClaveProdServ; ?>" name="CLAVE-PRODUCTO_SERVICIO" placeholder="CLAVE DE PRODUCTO O SERVICIO"></td>
                 </tr>
                 <tr>
                 <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">CLAVE DE UNIDAD:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $ClaveUnidad; ?>" name="ClaveUnidad" placeholder="CLAVE DE UNIDAD"></td>
                 </tr>


				 
                 <tr>
                    <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">DESCRIPCIÓN:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $Descripcion; ?>" name="Descripcion" placeholder="DESCRIPCION"></td>
                 </tr>
                 <tr>
                    <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">DESCUENTO:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $Descuento; ?>" name="Descuento" placeholder="DESCUESTO"></td>
                 </tr>
                 
				 <tr> <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">IMPORTE:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $Importe; ?>" name="IMPORTE" placeholder="IMPORTE"></td>
                 </tr>

                 <tr>
                 <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">No IDENTIFICACION:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $NoIdentificacion; ?>" name="No_IDENTIFICACION" placeholder="No IDENTIFICACION"></td>
                 </tr>
                 <tr>
                    <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">OBJETO IMP:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $ObjetoImp; ?>" name="OBJETO_IMP" placeholder="OBJETO IMP"></td>
                 </tr>
                 <tr>
                    <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">UNIDAD:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $Unidad; ?>" name="IVA_FACTURA" placeholder="UNIDAD"></td>
                 </tr>
                 <tr>
                    <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">VALOR UNITARIO:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $ValorUnitario; ?>" name="IVA_FACTURA" placeholder="VALOR UNITARIO"></td>
                 </tr>
                  <tr>
                    <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">BASE:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $IVA_FACTURA; ?>" name="IVA_FACTURA" placeholder="BASE"></td>
                 </tr>
                 <tr>
                    <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">IMPUESTO:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $IVA_FACTURA; ?>" name="IVA_FACTURA" placeholder="IMPUESTO"></td>
                 </tr>
                 <tr>
                    <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">TASA O CUOTA:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $IVA_FACTURA; ?>" name="IVA_FACTURA" placeholder="TASA O CUOTA"></td>
                 </tr>
                 <tr>
                    <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">TIPO FACTOR:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $IVA_FACTURA; ?>" name="IVA_FACTURA" placeholder="TIPO FACTOR"></td>
                 </tr>
                 <tr>
                    <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">IVA:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $TImpuestosTrasladados; ?>" name="IVA_FACTURA" placeholder="IVA"></td>
                 </tr>
                 <tr>

               
                 <tr>
                    <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">I.V.A RETENIDO:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $IVA_RETENIDO; ?>" name="IVA_RETENIDO" placeholder="IVA RETENIDO"></td>
                 </tr>
                
                 <tr>
                    <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">I.S.R RETENIDO:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $ISR_RETENIDO; ?>" name="ISR_RETENIDO" placeholder="I.S.R RETENIDO"></td>
                 </tr>
                
                 <tr>
                    <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">TUA:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $TUA_FACTURA; ?>" name="TUA_FACTURA" placeholder="TUA"></td>
                 </tr>
                 <tr>
                    <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">I.S.H:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $ISH_FACTURA; ?>" name="ISH_FACTURA" placeholder="I.S.H"></td>
                 </tr>
                 <tr>
                    <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">IEPS:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $IEPS_FACTURA; ?>" name="IEPS_FACTURA" placeholder="IEPS"></td>
                 </tr>
                 <tr>
                    <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">PROPINA:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $PROPINA_FACTURA; ?>" name="PROPINA_FACTURA" placeholder="PROPINA"></td>
                 </tr>
                 <tr>
                    <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">OTRO:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $OTRO_FACTURA; ?>" name="OTRO_FACTURA" placeholder="OTRO"></td>
                 </tr>
                 <tr>
                    <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">TOTAL:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $total; ?>" name="TOTAL_FACTURA" placeholder="TOTAL"></td>
                 </tr>
                 <tr>
                    <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">MONEDA:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $Moneda; ?>" name="MONEDA_FACTURA" placeholder="MONEDA"></td>
                 </tr>
                 <tr>
                    <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">MONEDA EXTRANJERA:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $MONEDA_EXTRANGERA_FACTURA; ?>" name="MONEDA_EXTRNGERA_FACTURA" placeholder="MONEDA EXTRANJERA"></td>
                 </tr>
                 
				 <tr>
                    <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">TIPO DE CAMBIO:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $TipoCambio; ?>" name="TIPO_DE_CAMBIO" placeholder="TIPO DE CAMBIO"></td>
                 </tr>
                 
                  
                 <tr>
                    <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">USO DE CFDI:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $UsoCFDI; ?>" name="USO_CFDI_FACTURA" placeholder="USO DE CFDI"></td>
                 </tr>
                 <tr>
                    <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">FORMA DE PAGO:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $formaDePago; ?>" name="FORMA_DE_PAGO_FACTURA" placeholder="FORMA DE PAGO"></td>
                 </tr>
                 <tr>
                    <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">METODO DE PAGO:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $metodoDePago; ?>" name="METODO_DE_PAGO_FACTURA" placeholder="METODO DE PAGO"></td>
                 </tr>
                 <tr>
                    <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">CONDICIONES DE PAGO:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $CONDICIONES_DE_PAGO; ?>" name="CONDICIONES_DE_PAGO" placeholder="CONDICIONES DE PAGO"></td>
                 </tr>
                 <tr>
                    <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">TIPO DE COMPROBANTE:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $tipoDeComprobante; ?>" name="TIPO_DE_COMPROBANTE" placeholder="TIPO DE COMPROBANTE"></td>
                 </tr>
             
                 <tr>
                    <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">CLAVE DE UNIDAD:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $TIPO_DE_COMPROBANTE; ?>" name="TIPO_DE_COMPROBANTE" placeholder="CLAVE DE UNIDAD"></td>
                 </tr>
                 <tr>
                    <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">VERSIÓN:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $Version; ?>" name="TIPO_DE_COMPROBANTE" placeholder="VERSION"></td>
                 </tr>
                 <tr>
                    <th scope="row"> <label  style="width:300px" for="validationCustom03" class="form-label">FECHA DE TIMBRADO:</label></th>
                 <td><input type="text" class="form-control"  readonly="readonly" id="validationCustom03" required=""  value="<?php echo $FechaTimbrado; ?>" name="TIPO_DE_COMPROBANTE" placeholder="TIPO DE COMPROBANTE"></td>
                 </tr></table><table>
             
 	<tr>
				

						


 <td style="text-align: left;"><button  class="btn btn-primary" type="button" onclick="history.back();" >REGRESAR AL EVENTO</button></td>
 
   <td style="text-align: right;"><button  class="btn btn-primary" type="button" id="enviarPAGOPROVEEDORES">GUARDAR</button><div style="
 position: absolute;
    top: 97%; 
    right: 50%;
    transform: translate(50%,-50%);
    text-transform: uppercase;
    font-family: verdana;
    font-size: 2em;
    font-weight: 500;
    color: #f5f5f5;
    text-shadow: 1px 1px 1px #919191,
        1px 2px 1px #919191,
        1px 3px 1px #919191,
        1px 4px 1px #919191,
        1px 5px 1px #919191,
        1px 6px 1px #919191,
        1px 7px 1px #919191,
        1px 8px 1px #919191,
        1px 9px 1px #919191,
        1px 10px 1px #919191,
    1px 18px 6px rgba(16,16,16,0.4),
    1px 22px 10px rgba(16,16,16,0.2),
    1px 25px 35px rgba(16,16,16,0.2),
    1px 30px 60px rgba(16,16,16,0.4);"   id="mensajepagoproveedores">

<?php } ?>

	</td>
			
	
	</tr>                
			 
                 </table>
	
				
            </form>      

 
	


</div>
</div>

</div>
</div>					  
</div>

