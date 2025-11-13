<?php 
$ROWdatoscolaborador3a = $pagoproveedores->variable_DIRECCIONP1();

$P_NOMBRE_COMERCIAL_EMPRESA = $ROWdatoscolaborador3a['P_NOMBRE_COMERCIAL_EMPRESA'];
$P_RFC_MTDP = $ROWdatoscolaborador3a['P_RFC_MTDP'];

$numero_evento_get = isset($_GET['num_evento'])?urldecode($_GET['num_evento']):'';
if($numero_evento_get!=''){
$NOMBRE_EVENTO_get = $pagoproveedores->buscarnombre($numero_evento_get);
}