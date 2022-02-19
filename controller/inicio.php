<?php
ini_set('session.gc_maxlifetime', 3600); // 1 hora
ini_set('session.cookie_lifetime', 3600);
//header("Cache-Control: no-cache, must-revalidate");
header('Content-type: text/html; charset=utf-8');
setlocale(LC_ALL,'pt_BR.UTF8');
mb_internal_encoding('UTF8'); 
mb_regex_encoding('UTF8');

require_once './DAO/entidadeDAO.php';
require_once './DAO/entidadeDAO.php';
require_once './model/entidade.php';
require_once '/biblioteca/mpdf/mpdf.php';
//include("DAO/conexao.php");

$template = new template();

	
	
	$template->carrega_template('inicio'); // nome do arquivo html que vai ser concatenado no template.php
	$template->carrega_sub_template('pagina','inicio');// inicio.html      
        
        if(!isset($_SESSION['usuario'])){
            echo '<script>
                            $( "#cssmenu" ).hide();
                        </script>';
        }

        
        $template->exibe_template();