<?php
$template = new template();

	
$template->carrega_template('inicio'); // nome do arquivo html que vai ser concatenado no template.php
$template->carrega_sub_template('pagina','login');// inicio.html 
    

$template->exibe_template();
