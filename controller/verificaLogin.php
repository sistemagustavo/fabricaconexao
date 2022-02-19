<?php

session_cache_expire(30); // 30 min
$cache_expire = session_cache_expire();
session_start(); 
$template = new template();
$pdo = conectar();	


$template->carrega_template('inicio'); // nome do arquivo html que vai ser concatenado no template.php
$template->carrega_sub_template('pagina','verificaLogin');// inicio.html       

require_once 'funcao/log_sistema.php'; 
if(isset($_POST['txt_login'])){
    $login = filter_input(INPUT_POST, 'txt_login', FILTER_SANITIZE_STRING);
    $senha = filter_input(INPUT_POST, 'txt_senha', FILTER_SANITIZE_STRING);
    
   $sql = $pdo->prepare( "select * from pessoa where usuario='$login' and senha='$senha' and ativo = 'S' and funcionario='S';" );
   $sql->execute();   
 //  echo  $sql->debugDumpParams();
   
    $num_results = $sql->rowCount();
	

    if($num_results > 0){
		$ln = $sql->fetch(PDO::FETCH_OBJ);
		$id_usuario = $_SESSION['usuario']['id_funcionario'] = $ln->id_pessoa;
		$nome_usuario = $_SESSION['usuario']['nome'] = $ln->nome;	
		grava_log_tela('pessoa',$id_usuario,'Logou','Acessou o Sistema!');
		$template->seta_variavel('logar',"<script type = 'text/javascript'> location.href = 'tela_inicial'</script>"); 
    }else{
        unset($_SESSION['usuario']);
       $template->seta_variavel('logar',"<script type = 'text/javascript'> location.href = 'login'</script>"); 
    }//end else
}//end if isset

$template->exibe_template();

