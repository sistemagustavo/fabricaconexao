<?php
session_start();
$id_usuario = $_SESSION['usuario']['id_funcionario'];
function temPermissao($permissao){
	require_once 'DAO/conexao.php';
	$pdo = conectar();
	$sql = $pdo->prepare("SELECT $permissao from permissao where id_pessoa = $id_usuario;");
	$sql->execute();
	$ln = $sql->fetch(PDO::FETCH_OBJ);	
	$permite = $ln->$permissao;
	return $permite;	
}

?>