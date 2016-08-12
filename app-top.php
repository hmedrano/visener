<?php 
require "vendors/lessphp/lessc.inc.php"; 

/* 
 * Listado de archivos less a preprocesar
 */
$lessfiles = array(
	"resources/css/stylesheet.less"
	) ;

/* 
 * Preprocesado de less a css
 */
$less = new lessc;
foreach ($lessfiles as $lf) {
  try {
    $less->compileFile($lf, str_replace(".less",".css",$lf));
  } catch (exception $e) {
   echo "Error al compilar archivo less: " . $lf . " : " . $e->getMessage(); 
  }	
}


?>