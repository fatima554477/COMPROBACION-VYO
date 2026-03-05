

<?php

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

<div id="dataModal14" class="modal fade">
 <div class="modal-dialog" style="width:80% !important; max-width:100% !important;">
  <div class="modal-content">
   <div class="modal-header">

    <h4 class="modal-title">Detalles</h4>
   </div>
   <div class="modal-body" id="personal_detalles14">
    
   </div>
   <div class="modal-footer">
   
   <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
   
   </div>
  </div>
 </div>
</div>
<div id="add_data_Modal" class="modal fade">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">

    <h4 class="modal-title">Detalles</h4>
   </div>
   <div class="modal-body" id="personal_detalles2">

   </div>
   <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
   </div>
  </div>
 </div>
</div>

<div id="add_data_Modal" class="modal fade">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">

    <h4 class="modal-title">Detalles</h4>
   </div>
   <div class="modal-body" id="personal">

   </div>
   <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
   </div>
  </div>
 </div>
</div>




<div id="dataModal" class="modal fade">
 <div class="modal-dialog modal-fullscreen">
  <div class="modal-content">
   <div class="modal-header">

    <h4 class="modal-title">Detalles</h4>
   </div>
   <div class="modal-body" id="personal_detalles">
    
   </div>
   <div class="modal-footer">
   
   <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
   
   </div>
  </div>
 </div>
</div>
	

<!--NUEVO CODIGO BORRAR-->
<div id="dataModal3" class="modal fade">
 <div class="modal-dialog modal-lg">
  <div class="modal-content">
   <div class="modal-header">

    <h4 class="modal-title">Detalles</h4>
   </div>
   <div class="modal-body" id="personal_detalles3">
    ¿ESTÁS SEGURO DE BORRAR ESTE REGISTRO?
   </div>
   <div class="modal-footer">
          <button id="btnYes" value="btnYes" class="btn confirm">SI BORRAR</button>	  
   <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
   
   </div>
  </div>
 </div>
</div>


<!--NUEVO CODIGO BORRAR-->
<div id="EFECTIVO" class="modal fade">
 <div class="modal-dialog modal-lg">
  <div class="modal-content">
   <div class="modal-header">

    <h4 class="modal-title">Detalles</h4>
   </div>
   <div class="modal-body" id="EFECTIVO">
    ¿ESTÁS SEGURO DE BORRAR ESTE REGISTRO?
   </div>
  
   <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
   
   </div>
  </div>
</div>

<div id="dataModal4" class="modal fade">
  <div class="modal-dialog" style="width:80% !important; max-width:100% !important;">
    <div class="modal-content">
      <div class="modal-header" style="cursor:move;">
        <h4 class="modal-title">Detalles</h4>
      </div>
      <div class="modal-body" id="personal_detalles4">
        Contenido...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>

      <!-- Flecha para arrastrar -->
      <div id="dragArrow" 
           style="cursor:move; text-align:center; font-size:22px; padding:5px; background:#f1f1f1;">
        ⬍
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
  const modal = document.querySelector("#dataModal4 .modal-dialog");
  const header = document.querySelector("#dataModal4 .modal-header");
  const arrow  = document.getElementById("dragArrow");

  let offsetX = 0, offsetY = 0, isDragging = false;

  function startDrag(e) {
    isDragging = true;
    offsetX = e.clientX - modal.offsetLeft;
    offsetY = e.clientY - modal.offsetTop;

    document.addEventListener("mousemove", moveModal);
    document.addEventListener("mouseup", stopDrag);
  }

  function moveModal(e) {
    if (isDragging) {
      modal.style.margin = "0"; 
      modal.style.position = "absolute";
      modal.style.left = (e.clientX - offsetX) + "px";
      modal.style.top  = (e.clientY - offsetY) + "px";
    }
  }

  function stopDrag() {
    isDragging = false;
    document.removeEventListener("mousemove", moveModal);
    document.removeEventListener("mouseup", stopDrag);
  }

  // Puedes arrastrar desde arriba o desde la flecha
  header.addEventListener("mousedown", startDrag);
  arrow.addEventListener("mousedown", startDrag);
});
</script>


<script>
function verificarFormaDePago() {
  var selectFP = document.getElementById('PFORMADE_PAGO');
  if (!selectFP) {
    return;
  }

  if (selectFP.value !== '04') {
    alert('LA FORMA DE PAGO DE TU FACTURA ES DIFERENTE A 04 (TARJETA DE CREDITO) PEDIR REFACTURACIÓN');
  }
}

document.addEventListener('DOMContentLoaded', verificarFormaDePago);
</script>





<script type="text/javascript">
	
	var fileobj;
	function upload_file(e,name) {
	    e.preventDefault();
	    fileobj = e.dataTransfer.files[0];
	    ajax_file_upload1(fileobj,name);
	}
	 
	function file_explorer(name) {
	    document.getElementsByName(name)[0].click();
	    document.getElementsByName(name)[0].onchange = function() {
	        fileobj = document.getElementsByName(name)[0].files[0];
	        ajax_file_upload1(fileobj,name);
	    };
	}

	function ajax_file_upload1(file_obj,nombre) {
	    if(file_obj != undefined) {
	        var form_data = new FormData();                  
	        form_data.append(nombre, file_obj);
	        $.ajax({
	            type: 'POST',
	            url: 'comprobaciones/controladorPP.php',
	            contentType: false,
	            processData: false,
	            data: form_data,
 beforeSend: function() {
$('#1'+nombre).html('<p style="color:green;">Cargando archivo!</p>');
$('#mensajeADJUNTOCOL').html('<p style="color:green;">Actualizado!</p>');
    },				
success:function(response) {

    // 1) Caso: UUID ya cargado
    if ($.trim(response) == 3) {
        $('#1' + nombre).html('<p style="color:red;">UUID PREVIAMENTE CARGADO.</p>');
        $('#' + nombre).val("");
        /*nuevo inicio*/
    }

    // 2) Caso: archivo no es XML
    else if ($.trim(response) === 'El archivo debe estar en formato XML.') {
        $('#1' + nombre).html('<p style="color:red;">' + $.trim(response) + '</p>');
        $('#' + nombre).val("");
    }

    // 3) Caso: todo OK (respuesta correcta, subió archivo, etc.)
    else {
        // Si la respuesta es el nombre del archivo
        $('#' + nombre).val(response);
        $('#1' + nombre).html(
            '<a target="_blank" href="includes/archivos/' + $.trim(response) + '"></a>'
        );

        /*nuevo inicio*/
        $("#2ADJUNTAR_FACTURA_XML").load(location.href + " #2ADJUNTAR_FACTURA_XML");

        if (nombre == 'ADJUNTAR_FACTURA_XML') {
            // MONTO_FACTURA y demás campos
            $('#RAZON_SOCIAL2').load(location.href + ' #RAZON_SOCIAL2');
            $('#RFC_PROVEEDOR2').load(location.href + ' #RFC_PROVEEDOR2');
            $('#CONCEPTO_PROVEE2').load(location.href + ' #CONCEPTO_PROVEE2');
            $('#TIPO_DE_MONEDA2').load(location.href + ' #TIPO_DE_MONEDA2');
            $('#FECHA_DE_PAGO2').load(location.href + ' #FECHA_DE_PAGO2');
            $('#NUMERO_CONSECUTIVO_PROVEE2').load(location.href + ' #NUMERO_CONSECUTIVO_PROVEE2');
            $('#2MONTO_FACTURA').load(location.href + ' #2MONTO_FACTURA');
            $('#2MONTO_DEPOSITAR').load(location.href + ' #2MONTO_DEPOSITAR');
            $('#2PFORMADE_PAGO').load(location.href + ' #2PFORMADE_PAGO');
            $('#2IVA').load(location.href + ' #2IVA');
            $('#2TImpuestosRetenidosIVA').load(location.href + ' #2TImpuestosRetenidosIVA');
            $('#2TImpuestosRetenidosISR').load(location.href + ' #2TImpuestosRetenidosISR');
            $('#2descuentos').load(location.href + ' #2descuentos');
        }

        $('#2' + nombre).load(location.href + ' #2' + nombre);
        $("#resettabla").load(location.href + " #resettabla");
        /*nuevo final 2PFORMADE_PAGO*/
    }

}

	        });
	    }
	}
	
	
	
function myFunction(montoapagar_id) {
  var checkBox = document.getElementById("montoapagar"+montoapagar_id);
  var montoapagar_text = "";
  if (checkBox.checked == true){
    montoapagar_text = "enter";
  } else {
     montoapagar_text = "none";
  }
  
$.ajax({
url:'pagoproveedores/fetch_pagesPP.php',
method:'POST',
data:{montoapagar_id:montoapagar_id,montoapagar_text:montoapagar_text},
beforeSend:function(){
$('#mensajemontoapagar').html('cargando');
},
success:function(data){
//$('#resetmontoapagar').html(data);
$('#montoapagartotal').load(location.href + ' #montoapagartotal');
$('#montoapagartotal2').load(location.href + ' #montoapagartotal2');
//$('#personal_detalles').html(data);
//$('#dataModal').modal('toggle');
}
});
  
}




function pasarpagado(pasarpagado_id){
	//$('#personal_detalles4').html();
	//$('#dataModal4').modal('show');	

	var checkBox = document.getElementById("pasarpagado1a"+pasarpagado_id);
	var pasarpagado_text = "";
	if (checkBox.checked == true){
	pasarpagado_text = "si";
	}else{
	pasarpagado_text = "no";
	}
	  $.ajax({
		url:'comprobaciones/controladorPP.php',
		method:'POST',
		data:{pasarpagado_id:pasarpagado_id,pasarpagado_text:pasarpagado_text},
		beforeSend:function(){
		$('#pasarpagado').html('cargando');
	},
		success:function(data){
		$('#pasarpagado').html("<span id='ACTUALIZADO' >"+data+"</span>");
	}
	});

}

//////////////////////////////////////////////////////////////////////////////////////

function comasainput(name){
	
const numberNoCommas = (x) => {
  return x.toString().replace(/,/g, "");
}

    var total = document.getElementsByName(name)[0].value;
	 var total2 = numberNoCommas(total)
const numberWithCommas = (x) => {
  return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}	
    document.getElementsByName(name)[0].value = numberWithCommas(total2);	
}


////////////////////////////////////////////////////////////////////////////////





function actualizarFechaDeLlenado() {
	const fechaInput = document.querySelector('input[name="FECHA_DE_LLENADO"]');
	if (!fechaInput) {
		return;
	}
	const now = new Date();
	const pad = (value) => value.toString().padStart(2, '0');
	fechaInput.value = `${pad(now.getDate())}-${pad(now.getMonth() + 1)}-${now.getFullYear()} ${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
}


	
$(document).ready(function(){





$("#enviarPAGOPROVEEDORES").click(function(){
	/*nuevo script pbajar archivos y datos*/
	actualizarFechaDeLlenado();
const formData = new FormData($('#pagoaproveedoresform')[0]);


$.ajax({
    url: 'comprobaciones/controladorPP.php',
    type: 'POST',
    dataType: 'html',
    data: formData,
    cache: false,
    contentType: false,
    processData: false
}).done(function(data) {
		if($.trim(data)=='Ingresado' || $.trim(data)=='Actualizado'){
			/*nuveo inicio*/
			$("#pagoaproveedoresform")[0].reset(); //resetea formulario

			$("#RAZON_SOCIAL").val(''); //borra valores vienen de PHP
			$("#CONCEPTO_PROVEE").val(''); //borra valores vienen de PHP
			$("#RFC_PROVEEDOR").val(''); //borra valores vienen de PHP
			$("#TIPO_DE_MONEDA").val(''); //borra valores vienen de PHP
			$("#FECHA_DE_PAGO").val(''); //borra valores vienen de PHP
			$("#NUMERO_CONSECUTIVO_PROVEE").val(''); //borra valores vienen de PHP
			$("#ADJUNTAR_FACTURA_XML").val(''); //borra valores vienen de PHP
			$("#2MONTO_FACTURA").val(''); //borra valores vienen de PHP
			$("#2MONTO_DEPOSITAR").val(''); //borra valores vienen de PHP
			$("#PFORMADE_PAGO").val(''); //borra valores vienen de PHP
			$("#2ADJUNTAR_FACTURA_PDF").val(''); //borra valores vienen de PHP
			
			/*reset multi imagen*/
			$("#CONCEPTO_PROVEE2").load(location.href + " #CONCEPTO_PROVEE2");
			$("#2ADJUNTAR_FACTURA_XML").load(location.href + " #2ADJUNTAR_FACTURA_XML");
			$("#ADJUNTAR_FACTURA_XML").load(location.href + " #ADJUNTAR_FACTURA_XML");
			$("#1ADJUNTAR_FACTURA_XML").load(location.href + " #1ADJUNTAR_FACTURA_XML");
			$("#ADJUNTAR_FACTURA_PDF").load(location.href + " #ADJUNTAR_FACTURA_PDF");
			$("#1ADJUNTAR_FACTURA_PDF").load(location.href + " #1ADJUNTAR_FACTURA_PDF");
			$("#IMPUESTO_HOSPEDAJE").load(location.href + " #IMPUESTO_HOSPEDAJE");
			$("#MONTO_PROPINA").load(location.href + " #MONTO_PROPINA");
			$("#IVA").load(location.href + " #IVA");

			$("#2ADJUNTAR_FACTURA_PDF").load(location.href + " #2ADJUNTAR_FACTURA_PDF");
			$("#2ADJUNTAR_COTIZACION").load(location.href + " #2ADJUNTAR_COTIZACION");
			$("#2CONPROBANTE_TRANSFERENCIA").load(location.href + " #2CONPROBANTE_TRANSFERENCIA");
			$("#2ADJUNTAR_ARCHIVO_1").load(location.href + " #2ADJUNTAR_ARCHIVO_1");
			$('#NUMERO_CONSECUTIVO_PROVEE2').load(location.href + ' #NUMERO_CONSECUTIVO_PROVEE2');
			$('#2MONTO_FACTURA').load(location.href + ' #2MONTO_FACTURA');
			$('#2MONTO_DEPOSITAR').load(location.href + ' #2MONTO_DEPOSITAR');
			$('#2IVA').load(location.href + ' #2IVA');
			$('#2TImpuestosRetenidosIVA').load(location.href + ' #2TImpuestosRetenidosIVA');
			$('#TImpuestosRetenidosIVA').load(location.href + ' #TImpuestosRetenidosIVA');
			$('#2TImpuestosRetenidosISR').load(location.href + ' #2TImpuestosRetenidosISR');
			$('#TImpuestosRetenidosISR').load(location.href + ' #TImpuestosRetenidosISR');
			$('#2descuentos').load(location.href + ' #2descuentos');
			$('#descuentos').load(location.href + ' #descuentos');


			$("#mensajepagoproveedores").html("<span id='ACTUALIZADO' >"+data+"</span>").delay(2000).fadeOut();
            $('#resettabla').load(location.href + ' #resettabla');	
            $('#reset_totales').load(location.href + ' #reset_totales');

			$.getScript(load(1)).delay(2000).fadeOut();

			
			}else{
			$("#mensajepagoproveedores").html(data);
		}
})
.fail(function() {
    console.log("detect error");
});
});





//SCRIPT PARA BORRAR FOTOGRAFIA BORRAR
$(document).on('click', '.view_dataSBborrar2', function(){
var borra_id_sb = $(this).attr('id');
var borrasbdoc = 'borrasbdoc';
$('#personal_detalles3').html();
$('#dataModal3').modal('show');
$('#btnYes').click(function() {
$.ajax({
url:'comprobaciones/controladorPP.php',
method:'POST',
data:{borra_id_sb:borra_id_sb,borrasbdoc:borrasbdoc},
beforeSend:function(){
$('#mensajepagoproveedores').html('cargando');
},
success:function(data){
$('#dataModal3').modal('hide');
$('#mensajepagoproveedores').html("<span id='ACTUALIZADO' >"+data+"</span>");
$('#'+borra_id_sb).load(location.href + ' #'+borra_id_sb);
$('#A'+borra_id_sb).load(location.href + ' #A'+borra_id_sb);
}
});
});
});



//SCRIPT PARA BORRAR view_dataSBborrar
$(document).on('click', '.view_dataSBborrar', function(){
var borra_id_PAGOP = $(this).attr('id');
var borrapagoaproveedores = 'borrapagoaproveedores';
$('#personal_detalles3').html();
$('#dataModal3').modal('show');
$('#btnYes').click(function() {
$.ajax({
url:'comprobaciones/controladorPP.php',
method:'POST',
data:{borra_id_PAGOP:borra_id_PAGOP,borrapagoaproveedores:borrapagoaproveedores},
beforeSend:function(){
$('#mensajepagoproveedores').html('cargando');
},
success:function(data){
$('#dataModal3').modal('hide');
$('#mensajepagoproveedores').html("<span id='ACTUALIZADO' >"+data+"</span>");
            $('#reset_totales').load(location.href + ' #reset_totales');
			$.getScript(load(1));
}
});
});
});







//NOMBRE DEL BOTÓN
$(document).on('click', '.view_dataPAGOPROVEEmodifica', function(){
var personal_id = $(this).attr('id');
$.ajax({
url:'comprobaciones/VistaPreviapagoproveedor.php',
method:'POST',
data:{personal_id:personal_id},
beforeSend:function(){
$('#mensajepagoproveedores').html('cargando');
},
success:function(data){
$('#personal_detalles').html(data);
$('#dataModal').modal('toggle');
            $('#reset_totales').load(location.href + ' #reset_totales');
}
});
});



//NOMBRE DEL BOTÓN
$(document).on('click', '.view_dataSUBIRCOM', function(){
var personal_id = $(this).attr('id');
$.ajax({
url:'comprobaciones/VistaPreviapagoproveedor2.php',
method:'POST',
data:{personal_id:personal_id},
beforeSend:function(){
$('#mensajepagoproveedores').html('cargando');
},
success:function(data){
$('#personal_detalles').html(data);
$('#dataModal').modal('toggle');
            $('#reset_totales').load(location.href + ' #reset_totales');
}
});
});








//NOMBRE DEL BOTÓN
$(document).on('click', '.SOLICITADO', function(){
var SOLICITADO = 'SOLICITADO';
$.ajax({
url:'pagoproveedores/fetch_pagesPP.php',
method:'POST',
data:{SOLICITADO:SOLICITADO},
beforeSend:function(){
$('#mensajeSUBIRFACTURA').html('cargando');
},
success:function(data){
//$('#personal_detalles4').html(data);
//$('#dataModal4').modal('toggle');
//$("#results").load("pagoproveedores/fetch_pagesPP.php");
			$.getScript(load(1));
}
});
});



//NOMBRE DEL BOTÓN
$(document).on('click', '.APROBADO', function(){
var APROBADO = 'APROBADO';
$.ajax({
url:'pagoproveedores/fetch_pagesPP.php',
method:'POST',
data:{APROBADO:APROBADO},
beforeSend:function(){
$('#mensajeSUBIRFACTURA').html('cargando');
},
success:function(data){
//$('#personal_detalles4').html(data);
//$('#dataModal4').modal('toggle');
//$("#results").load("pagoproveedores/fetch_pagesPP.php");
			$.getScript(load(1));
}
});
});


//NOMBRE DEL BOTÓN
$(document).on('click', '.RECHAZADO', function(){
var RECHAZADO = 'RECHAZADO';
$.ajax({
url:'pagoproveedores/fetch_pagesPP.php',
method:'POST',
data:{RECHAZADO:RECHAZADO},
beforeSend:function(){
$('#mensajeSUBIRFACTURA').html('cargando');
},
success:function(data){
//$('#personal_detalles4').html(data);
//$('#dataModal4').modal('toggle');
//$("#results").load("pagoproveedores/fetch_pagesPP.php");
			$.getScript(load(1));
}
});
});

//NOMBRE DEL BOTÓN
$(document).on('click', '.PAGADO', function(){
var PAGADO = 'PAGADO';
$.ajax({
url:'pagoproveedores/fetch_pagesPP.php',
method:'POST',
data:{PAGADO:PAGADO},
beforeSend:function(){
$('#mensajeSUBIRFACTURA').html('cargando');
},
success:function(data){
//$('#personal_detalles4').html(data);
//$('#dataModal4').modal('toggle');
//$("#results").load("pagoproveedores/fetch_pagesPP.php");
			$.getScript(load(1));
}
});
});


//NOMBRE DEL BOTÓN
$(document).on('click', '.BORRAR', function(){
var BORRAR = 'BORRAR';
$.ajax({
url:'pagoproveedores/fetch_pagesPP.php',
method:'POST',
data:{BORRAR:BORRAR},
beforeSend:function(){
$('#mensajeSUBIRFACTURA').html('cargando');
},
success:function(data){
//$('#personal_detalles4').html(data);
//$('#dataModal4').modal('toggle');
//$("#results").load("pagoproveedores/fetch_pagesPP.php");
			$.getScript(load(1));
}
});
});


$("#clickbuscar").click(function(){
const formData = new FormData($('#buscaform')[0]);

$.ajax({
    url: 'pagoproveedores/fetch_pagesPP.php',
    type: 'POST',
    dataType: 'html',
    data: formData,
    cache: false,
    contentType: false,
    processData: false
})
.done(function(data) {
				
//$("#results").load("pagoproveedores/fetch_pagesPP.php");
			$.getScript(load(1));

})
.fail(function() {
    console.log("detect error");
});
});













     //DATOS resettodo //

$("#enviarDATOSBANCARIOS1").click(function(){
	/*nuevo script pbajar archivos y datos*/
const formData = new FormData($('#DATOSBANCARIOS1form')[0]);

$.ajax({
    url: 'comprobaciones/controladorPP.php',
    type: 'POST',
    dataType: 'html',
    data: formData,
    cache: false,
    contentType: false,
    processData: false
}).done(function(data) {
		if($.trim(data)=='Ingresado' || $.trim(data)=='Actualizado'){	
		
			$("#mensajeDATOSBANCARIOS1").html("<span id='ACTUALIZADO' >"+data+"</span>");
$('#resetBancario1p').load(location.href + ' #resetBancario1p');			
			}else{
			$("#mensajeDATOSBANCARIOS1").html(data);
		}
})
.fail(function() {
    console.log("detect error");
});
});





$(document).on('click', '.view_dataNUEVO', function(){
var personal_id = $(this).attr('id');
$.ajax({
url:'pagoproveedores/VistaPreviaDatosBancario1.php',
method:'POST',
data:{personal_id:personal_id},
beforeSend:function(){
$('#mensajepagoproveedores').html('cargando');
},
success:function(data){
$('#personal_detalles2').html(data);
$('#dataModal').modal('toggle');
}
});
});



















$(document).on('click', '.view_data_bancario1p_modifica', function(){
var personal_id = $(this).attr('id');
$.ajax({
url:'pagoproveedores/VistaPreviaDatosBancario1.php',
method:'POST',
data:{personal_id:personal_id},
beforeSend:function(){
$('#mensajeDATOSBANCARIOS1').html('cargando');
},
success:function(data){
$('#personal_detalles').html(data);
$('#dataModal').modal('toggle');
}
});
});


$(document).on('click', '.view_databancario1borrar', function(){
var borra_id_bancaP = $(this).attr('id');
var borra_datos_bancario1 = 'borra_datos_bancario1';
$('#personal_detalles3').html();
$('#dataModal3').modal('show');
$('#btnYes').click(function() {
$.ajax({
url:'comprobaciones/controladorPP.php',
method:'POST',
data:{borra_id_bancaP:borra_id_bancaP,borra_datos_bancario1:borra_datos_bancario1},
beforeSend:function(){
$('#mensajeREFERENCIAS').html('cargando');
},
success:function(data){
$('#dataModal3').modal('hide');
$('#mensajeDATOSBANCARIOS1').html("<span id='ACTUALIZADO' >"+data+"</span>");
//$('#resetBancario1p').load(location.href + ' #resetBancario1p');
$.getScript(load(1));
}
});
});
});


//SCRIPT enviar EMAIL
$(document).on('click', '#enviar_email_bancarios', function(){
var DAbancaPRO_ENVIAR_IMAIL = $('#DAbancaPRO_ENVIAR_IMAIL').val();


        var myCheckboxes = new Array();
        $("input:checked").each(function() {
           myCheckboxes.push($(this).val());
        });
var dataString = $("#form_emai_DATOSBpro").serialize();  



$.ajax({
url:'comprobaciones/controladorPP.php',
method:'POST',
dataType: 'html',

data: dataString+{DAbancaPRO_ENVIAR_IMAIL:DAbancaPRO_ENVIAR_IMAIL},


beforeSend:function(){
$('#mensajeDATOSBANCARIOS1').html('cargando');
},
success:function(data){
$('#mensajeDATOSBANCARIOS1').html("<span id='ACTUALIZADO' >"+data+"</span>");

}
});
});


$("#enviar_NUEVO").click(function(){

const formData = new FormData($('#pagoaproveedoresform')[0]);
$.ajax({
url:'pagoproveedores/VistaPreviaNUEVOproveedor.php',
method:'POST',
data:{personal_id:personal_id},
beforeSend:function(){
$('#mensajepagoproveedores').html('cargando');
},
success:function(data){
$('#personal_detalles').html(data);
$('#dataModal').modal('toggle');
}
});
});

/*match*//*match*//*match*//*match*//*match*//*match*//*match*/


$(document).on('click', '.view_MATCH2filtroinbursa', function(){
var personal_id = $(this).attr('id');
$.ajax({
url:'comprobacionesVYO/VistaPreviamatchinbursa.php',
method:'POST',
data:{personal_id:personal_id},
beforeSend:function(){
$('#mensajeDATOSBANCARIOS1').html('cargando');
},
success:function(data){
$('#personal_detalles14').html(data);
$('#dataModal14').modal('toggle');
}
});
});


$(document).on('click', '.view_MATCH2filtrobbva', function(){
var personal_id = $(this).attr('id');
$.ajax({
url:'comprobacionesVYO/VistaPreviamatchBBVA.php',
method:'POST',
data:{personal_id:personal_id},
beforeSend:function(){
$('#mensajeDATOSBANCARIOS1').html('cargando');
},
success:function(data){
$('#personal_detalles14').html(data);
$('#dataModal14').modal('toggle');
}
});
});


$(document).on('click', '.view_MATCH2filtroAMEX', function(){
var personal_id = $(this).attr('id');
$.ajax({
url:'comprobacionesVYO/VistaPreviamatchAMEX.php',
method:'POST',
data:{personal_id:personal_id},
beforeSend:function(){
$('#mensajeDATOSBANCARIOS1').html('cargando');
},
success:function(data){
$('#personal_detalles14').html(data);
$('#dataModal14').modal('toggle');
}
});
});

$(document).on('click', '.view_MATCH2filtroSIVALE', function(){
var personal_id = $(this).attr('id');
$.ajax({
url:'comprobacionesVYO/VistaPreviamatchSANTANDER.php',
method:'POST',
data:{personal_id:personal_id},
beforeSend:function(){
$('#mensajeDATOSBANCARIOS1').html('cargando');
},
success:function(data){
$('#personal_detalles14').html(data);
$('#dataModal14').modal('toggle');
}
});
});


		$('#target1').show("linear");
			$('#target2').hide("linear");
			$('#target3').hide("linear");
			$('#target4').hide("linear");
			$('#target5').hide("linear");
			$('#target6').hide("linear");
			$('#target7').hide("linear");
			$('#target8').hide("linear");
			$('#target9').hide("linear");
			$('#target10').hide("linear");
			$('#target11').hide("linear");
			$('#target12').hide("linear");
			$('#target13').hide("linear");
			$('#target14').hide("linear");
			$('#target15').hide("linear");
			$('#target16').hide("linear");
			$('#target17').hide("linear");
			$('#target18').hide("linear");
			$('#target19').hide("linear");
			$('#target20').hide("linear");
			$('#target21').hide("linear");
			$('#target22').hide("linear");
			$('#target23').hide("linear");
			$('#target24').hide("linear");
			$('#target25').hide("linear");
			$('#target26').hide("linear");
			$('#target27').hide("linear");
			$('#target28').hide("linear");
			$('#target29').hide("linear");
			$('#target30').hide("linear");
			$('#target31').hide("linear");
			$('#target32').hide("linear");
			$('#target33').hide("linear");
			$('#target34').hide("linear");
			$('#target35').hide("linear");
			$('#target35').hide("linear");
			$('#target37').hide("linear");
			$('#target38').hide("linear");
			$('#target39').hide("linear");
			$('#target40').hide("linear");
			$('#target41').hide("linear");
			$('#target42').hide("linear");
			$('#target43').hide("linear");
			$('#target44').hide("linear");
			$('#target45').hide("linear");
			$('#target46').hide("linear");
			$('#target47').hide("linear");
			$('#targetVIDEO').hide("linear");
			
			$("#mostrar1").click(function(){
				$('#target1').show("swing");
		 	});
			$("#ocultar1").click(function(){
				$('#target1').hide("linear");
			});
			$("#mostrar2").click(function(){
				$('#target2').show("swing");
		 	});
			$("#ocultar2").click(function(){
				$('#target2').hide("linear");
			});
			$("#mostrar3").click(function(){
				$('#target3').show("swing");
		 	});
			$("#ocultar3").click(function(){
				$('#target3').hide("linear");
			});
			$("#mostrar4").click(function(){
				$('#target4').show("swing");
		 	});
			$("#ocultar4").click(function(){
				$('#target4').hide("linear");
			});
			$("#mostrar5").click(function(){
				$('#target5').show("swing");
		 	});
			$("#ocultar5").click(function(){
				$('#target5').hide("linear");
			});
			$("#mostrar6").click(function(){
				$('#target6').show("swing");
		 	});
			$("#ocultar6").click(function(){
				$('#target6').hide("linear");
			});
			$("#mostrar7").click(function(){
				$('#target7').show("swing");
		 	});
			$("#ocultar7").click(function(){
				$('#target7').hide("linear");
			});
			$("#mostrar8").click(function(){
				$('#target8').show("swing");
		 	});
			$("#ocultar8").click(function(){
				$('#target8').hide("linear");
			});
			$("#mostrar9").click(function(){
				$('#target9').show("swing");
		 	});
			$("#ocultar9").click(function(){
				$('#target9').hide("linear");
			});
			$("#mostrar10").click(function(){
				$('#target10').show("swing");
		 	});
			$("#ocultar10").click(function(){
				$('#target10').hide("linear");
			});
			$("#mostrar11").click(function(){
				$('#target11').show("swing");
		 	});
			$("#ocultar11").click(function(){
				$('#target11').hide("linear");
			});
			$("#mostrar12").click(function(){
				$('#target12').show("swing");
		 	});
			$("#ocultar12").click(function(){
				$('#target12').hide("linear");
			});
			$("#mostrar13").click(function(){
				$('#target13').show("swing");
		 	});
			$("#ocultar13").click(function(){
				$('#target13').hide("linear");
			});

			$("#mostrar14").click(function(){
				$('#target14').show("swing");
		 	});
			$("#ocultar14").click(function(){
				$('#target14').hide("linear");
			});
			
			$("#mostrar15").click(function(){
				$('#target15').show("swing");
		 	});
			$("#ocultar15").click(function(){
				$('#target15').hide("linear");
			});
				$("#mostrar16").click(function(){
				$('#target16').show("swing");
		 	});
			$("#ocultar16").click(function(){
				$('#target16').hide("linear");
			});
				$("#mostrar17").click(function(){
				$('#target17').show("swing");
		 	});
			$("#ocultar17").click(function(){
				$('#target17').hide("linear");
			});
				$("#mostrar18").click(function(){
				$('#target18').show("swing");
		 	});
			$("#ocultar18").click(function(){
				$('#target18').hide("linear");
			});
				$("#mostrar19").click(function(){
				$('#target19').show("swing");
		 	});
			$("#ocultar19").click(function(){
				$('#target19').hide("linear");
			});
				$("#mostrar20").click(function(){
				$('#target20').show("swing");
		 	});
			$("#ocultar20").click(function(){
				$('#target20').hide("linear");
				
			});
					$("#mostrar21").click(function(){
				$('#target21').show("swing");
		 	});
			$("#ocultar21").click(function(){
				$('#target21').hide("linear");
				
			});
					$("#mostrar22").click(function(){
				$('#target22').show("swing");
		 	});
			$("#ocultar22").click(function(){
				$('#target22').hide("linear");
				
			});
					$("#mostrar23").click(function(){
				$('#target23').show("swing");
		 	});
			$("#ocultar23").click(function(){
				$('#target23').hide("linear");
				
			});
					$("#mostrar24").click(function(){
				$('#target24').show("swing");
		 	});
			$("#ocultar24").click(function(){
				$('#target24').hide("linear");
				
			});
					$("#mostrar25").click(function(){
				$('#target25').show("swing");
		 	});
			$("#ocultar25").click(function(){
				$('#target25').hide("linear");
				
			});
					$("#mostrar26").click(function(){
				$('#target26').show("swing");
		 	});
			$("#ocultar26").click(function(){
				$('#target26').hide("linear");
				
			});
					$("#mostrar27").click(function(){
				$('#target27').show("swing");
		 	});
			$("#ocultar27").click(function(){
				$('#target27').hide("linear");
				
			});
					$("#mostrar28").click(function(){
				$('#target28').show("swing");
		 	});
			$("#ocultar28").click(function(){
				$('#target28').hide("linear");
				
			});
					$("#mostrar29").click(function(){
				$('#target29').show("swing");
		 	});
			$("#ocultar29").click(function(){
				$('#target29').hide("linear");
				
			});
					$("#mostrar30").click(function(){
				$('#target30').show("swing");
		 	});
			$("#ocultar30").click(function(){
				$('#target30').hide("linear");
				
			});
					$("#mostrar31").click(function(){
				$('#target31').show("swing");
		 	});
			$("#ocultar31").click(function(){
				$('#target31').hide("linear");
				
			});
					$("#mostrar32").click(function(){
				$('#target32').show("swing");
		 	});
			$("#ocultar32").click(function(){
				$('#target32').hide("linear");
				
			});
					$("#mostrar303").click(function(){
				$('#target33').show("swing");
		 	});
			$("#ocultar33").click(function(){
				$('#target33').hide("linear");
				
			});
					$("#mostrar34").click(function(){
				$('#target34').show("swing");
		 	});
			$("#ocultar34").click(function(){
				$('#target34').hide("linear");
				
			});
					$("#mostrar35").click(function(){
				$('#target35').show("swing");
		 	});
			$("#ocultar35").click(function(){
				$('#target35').hide("linear");
				
			});
					$("#mostrar36").click(function(){
				$('#target36').show("swing");
		 	});
			$("#ocultar36").click(function(){
				$('#target36').hide("linear");
				
			});
					$("#mostrar37").click(function(){
				$('#target37').show("swing");
		 	});
			$("#ocultar37").click(function(){
				$('#target37').hide("linear");
				
			});
					$("#mostrar38").click(function(){
				$('#target38').show("swing");
		 	});
			$("#ocultar38").click(function(){
				$('#target38').hide("linear");
				
			});
					$("#mostrar39").click(function(){
				$('#target39').show("swing");
		 	});
			$("#ocultar39").click(function(){
				$('#target39').hide("linear");
				
			});
			$("#mostrar40").click(function(){
				$('#target40').show("swing");
		 	});
			$("#ocultar40").click(function(){
				$('#target40').hide("linear");
				
			});
            $("#mostrar41").click(function(){
				$('#target41').show("swing");
		 	});
			$("#ocultar41").click(function(){
				$('#target41').hide("linear");
				
			});
			$("#mostrar42").click(function(){
				$('#target42').show("swing");
		 	});
			$("#ocultar42").click(function(){
				$('#target42').hide("linear");
				
			});
			$("#mostrar43").click(function(){
				$('#target43').show("swing");
		 	});
			$("#ocultar43").click(function(){
				$('#target43').hide("linear");
				
			});
		    $("#mostrar44").click(function(){
				$('#target44').show("swing");
		 	});
			$("#ocultar44").click(function(){
				$('#target44').hide("linear");
				
			});
             $("#mostrar45").click(function(){
				$('#target45').show("swing");
		 	});
			$("#ocultar45").click(function(){
				$('#target45').hide("linear");
				
			});
			 $("#mostrar46").click(function(){
				$('#target46').show("swing");
		 	});
			$("#ocultar46").click(function(){
				$('#target46').hide("linear");
				
			});
			 $("#mostrar47").click(function(){
				$('#target47').show("swing");
		 	});
			$("#ocultar47").click(function(){
				$('#target47').hide("linear");
				
			});
			$("#mostrarVIDEO").click(function(){
				$('#targetVIDEO').show("swing");
		 	});
			$("#ocultarVIDEO").click(function(){
				$('#targetVIDEO').hide("linear");
			});

			$("#mostrartodos").click(function(){
		
				$('#target1').show("swing");
				$('#target2').show("swing");
				$('#target3').show("swing");
				$('#target4').show("swing");
				$('#target5').show("swing");
				$('#target6').show("swing");
				$('#target7').show("swing");
				$('#target8').show("swing");
				$('#target9').show("swing");
				$('#target10').show("swing");
				$('#target11').show("swing");
				$('#target12').show("swing");
				$('#target13').show("swing");
				$('#target14').show("swing");
				$('#target15').show("swing");
				$('#target16').show("swing");
				$('#target17').show("swing");
				$('#target18').show("swing");
				$('#target19').show("swing");
				$('#target20').show("swing");
				$('#target21').show("swing");
				$('#target22').show("swing");
				$('#target23').show("swing");
				$('#target24').show("swing");
				$('#target25').show("swing");
				$('#target26').show("swing");
				$('#target27').show("swing");
				$('#target28').show("swing");
				$('#target29').show("swing");
				$('#target30').show("swing");
				$('#target31').show("swing");
				$('#target32').show("swing");
				$('#target33').show("swing");
				$('#target34').show("swing");
				$('#target35').show("swing");
				$('#target36').show("swing");
				$('#target37').show("swing");
				$('#target38').show("swing");
				$('#target39').show("swing");
				$('#target40').show("swing");
				$('#target41').show("swing");
				$('#target42').show("swing");
				$('#target43').show("swing");
				$('#target44').show("swing");
				$('#target45').show("swing");
				$('#target46').show("swing");
				$('#target47').show("swing");
				$('#targetVIDEO').show("swing");
		 	});
			
			$("#ocultartodos").click(function(){
				
				$('#target1').hide("swing");
				$('#target2').hide("swing");
				$('#target3').hide("swing");
				$('#target4').hide("swing");
				$('#target5').hide("swing");
				$('#target6').hide("swing");
				$('#target7').hide("swing");
				$('#target8').hide("swing");
				$('#target9').hide("swing");
				$('#target10').hide("swing");
				$('#target11').hide("swing");
				$('#target12').hide("swing");
				$('#target13').hide("swing");
				$('#target14').hide("swing");
				$('#target15').hide("swing");
				$('#target16').hide("swing");
				$('#target17').hide("swing");
				$('#target18').hide("swing");
				$('#target19').hide("swing");
				$('#target20').hide("swing");
				$('#target21').hide("swing");
				$('#target22').hide("swing");
				$('#target23').hide("swing");
				$('#target24').hide("swing");
				$('#target25').hide("swing");
				$('#target26').hide("swing");
				$('#target27').hide("swing");
				$('#target28').hide("swing");
				$('#target29').hide("swing");
				$('#target30').hide("swing");
				$('#target31').hide("swing");
				$('#target32').hide("swing");
				$('#target33').hide("swing");
				$('#target34').hide("swing");
				$('#target35').hide("swing");
				$('#target36').hide("swing");
				$('#target37').hide("swing");
				$('#target38').hide("swing");
				$('#target39').hide("swing");
				$('#target40').hide("swing");
				$('#target41').hide("swing");
				$('#target42').hide("swing");
				$('#target43').hide("swing");
				$('#target44').hide("swing");
				$('#target45').hide("swing");
				$('#target46').hide("swing");
				$('#target47').hide("swing");
				$('#targetVIDEO').hide("linear");
			});

			$("#mostrartodos2").click(function(){
		
				$('#target1').show("swing");
				$('#target2').show("swing");
				$('#target3').show("swing");
				$('#target4').show("swing");
				$('#target5').show("swing");
				$('#target6').show("swing");
				$('#target7').show("swing");
				$('#target8').show("swing");
				$('#target9').show("swing");
				$('#target10').show("swing");
				$('#target11').show("swing");
				$('#target12').show("swing");
				$('#target13').show("swing");
				$('#target14').show("swing");
				$('#target15').show("swing");
				$('#target16').show("swing");
				$('#target17').show("swing");
				$('#target18').show("swing");
				$('#target19').show("swing");
				$('#target20').show("swing");
				$('#target21').show("swing");
				$('#target22').show("swing");
				$('#target23').show("swing");
				$('#target24').show("swing");
				$('#target25').show("swing");
				$('#target26').show("swing");
				$('#target27').show("swing");
				$('#target28').show("swing");
				$('#target29').show("swing");
				$('#target30').show("swing");
				$('#target31').show("swing");
				$('#target32').show("swing");
				$('#target33').show("swing");
				$('#target34').show("swing");
				$('#target35').show("swing");
				$('#target36').show("swing");
				$('#target37').show("swing");
				$('#target38').show("swing");
				$('#target39').show("swing");
				$('#target40').show("swing");
				$('#target41').show("swing");
				$('#target42').show("swing");
				$('#target43').show("swing");
				$('#target44').show("swing");
				$('#target45').show("swing");
				$('#target46').show("swing");
				$('#target47').show("swing");
				$('#targetVIDEO').show("swing");
		 	});
			
			$("#ocultartodos2").click(function(){
				
					$('#target1').hide("swing");
				$('#target2').hide("swing");
				$('#target3').hide("swing");
				$('#target4').hide("swing");
				$('#target5').hide("swing");
				$('#target6').hide("swing");
				$('#target7').hide("swing");
				$('#target8').hide("swing");
				$('#target9').hide("swing");
				$('#target10').hide("swing");
				$('#target11').hide("swing");
				$('#target12').hide("swing");
				$('#target13').hide("swing");
				$('#target14').hide("swing");
				$('#target15').hide("swing");
				$('#target16').hide("swing");
				$('#target17').hide("swing");
				$('#target18').hide("swing");
				$('#target19').hide("swing");
				$('#target20').hide("swing");
				$('#target21').hide("swing");
				$('#target22').hide("swing");
				$('#target23').hide("swing");
				$('#target24').hide("swing");
				$('#target25').hide("swing");
				$('#target26').hide("swing");
				$('#target27').hide("swing");
				$('#target28').hide("swing");
				$('#target29').hide("swing");
				$('#target30').hide("swing");
				$('#target31').hide("swing");
				$('#target32').hide("swing");
				$('#target33').hide("swing");
				$('#target34').hide("swing");
				$('#target35').hide("swing");
				$('#target36').hide("swing");
				$('#target37').hide("swing");
				$('#target38').hide("swing");
				$('#target39').hide("swing");
				$('#target40').hide("swing");
				$('#target41').hide("swing");
				$('#target42').hide("swing");
				$('#target43').hide("swing");
				$('#target44').hide("swing");
				$('#target45').hide("swing");
				$('#target46').hide("swing");
				$('#target47').hide("swing");
				$('#targetVIDEO').hide("linear");
			});

		});
		
	</script>