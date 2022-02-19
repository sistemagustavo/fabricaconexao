<?php


function grava_log($tabela,$codigo,$acao,$descricao){
session_start();	
$host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
date_default_timezone_set('America/Sao_Paulo');	
$now = date("Y-m-d H:i:s");

$id_usuario = $_SESSION['usuario']['id_funcionario'];
$nome_usuario = $_SESSION['usuario']['nome'];
	
require_once '../DAO/conexao.php';
$pdo = conectar();	
$sql = $pdo->prepare("INSERT INTO `log` (`id_usuario`, `ip`, `data`, `acao`, `tabela`, `codigo`, `descricao`) VALUES 
								 ('$id_usuario', '$host', '$now', '$acao', '$tabela', '$codigo', ' $id_usuario - $nome_usuario - $descricao'); ");
$sql->execute();	
	
}

function grava_log_tela($tabela,$codigo,$acao,$descricao){
session_start();	
$host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
date_default_timezone_set('America/Sao_Paulo');	
$now = date("Y-m-d H:i:s");

$id_usuario = $_SESSION['usuario']['id_funcionario'];
$nome_usuario = $_SESSION['usuario']['nome'];
	

$pdo = conectar();	
$sql = $pdo->prepare("INSERT INTO `log` (`id_usuario`, `ip`, `data`, `acao`, `tabela`, `codigo`, `descricao`) VALUES 
								 ('$id_usuario', '$host', '$now', '$acao', '$tabela', '$codigo', ' $id_usuario - $nome_usuario - $descricao'); ");
$sql->execute();	
	
}




?>