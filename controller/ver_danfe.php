<?php
session_start(); 
$pdo = conectar();
//print_r($_POST);
$template = new template();
	
$template->carrega_template('inicio'); // nome do arquivo html que vai ser concatenado no template.php
$template->carrega_sub_template('pagina','ver_danfe');// inicio.html 

$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);       
   
$danfe = "<embed src='danfe/$id.pdf' type='application/pdf' width='1300px' height='1000px'>";
$template->seta_variavel('danfe', $danfe); 
//<embed src="danfe/'.$id.'.pdf" type="application/pdf" width="100%" height="100%">
 
          	
 $template->exibe_template();
      

