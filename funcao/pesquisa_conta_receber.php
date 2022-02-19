<?php
require_once '../DAO/conexao.php';


function apenasUmTituloAberto($pedido){
	$pdo = conectar();
	// verifica se tem titulos em quitados no financeiro 
	$sql = $pdo->prepare( "select status_titulo, data_pagamento, id_pessoa FROM 
	financeiro WHERE id_pedido = $pedido and status_titulo = 'Q'" );
	$sql->execute();
	$quitados = $sql->rowCount();
	// verifica se tem titulos em aberto no financeiro e apenas uma parcela
	$sql = $pdo->prepare( "select status_titulo, data_pagamento, id_pessoa FROM 
	financeiro WHERE id_pedido = $pedido and status_titulo = 'A'" );
	$sql->execute();
	$abertos = $sql->rowCount();
	if($abertos = 1 && $quitados = 0){
		$retorno = 'S';
	}
	if($abertos = 0 && $quitados > 0){
		$retorno = 'N';
	}
}

function TitulosQuitadosAberto($pedido){
	$pdo = conectar();
	// verifica se tem titulos em quitados no financeiro 
	$sql = $pdo->prepare( "select status_titulo, data_pagamento, id_pessoa FROM 
	financeiro WHERE id_pedido = $pedido and status_titulo = 'Q'" );
	$sql->execute();
	$quitados = $sql->rowCount();
	// verifica se tem titulos em aberto no financeiro e apenas uma parcela
	$sql = $pdo->prepare( "select status_titulo, data_pagamento, id_pessoa FROM 
	financeiro WHERE id_pedido = $pedido and status_titulo = 'A'" );
	$sql->execute();
	$abertos = $sql->rowCount();
	if($abertos = 1 && $quitados > 0){
		$retorno = 'S';
	}
}


?>

