<?php
require_once '../DAO/conexao.php';


function soma_pedido($pedido){
	$pdo = conectar();
	$sql = $pdo->prepare("update pedido set 
	custo_totalvenda = (select sum(custo_totalitem) from item_projeto_pedido where id_pedido = $pedido),
	vl_total_padrao = (select ROUND(sum(vl_total), 2) from item_projeto_pedido where id_pedido = $pedido),
	vl_pedido_desconto = (select sum(vl_total_desconto) from item_projeto_pedido where id_pedido = $pedido)
	where id_pedido = $pedido;");
	$sql->execute();
   
}

function altera_financeiro($pedido){
	$pdo = conectar();
   $sql = $pdo->prepare("update financeiro set `vl_original` = (select vl_pedido_desconto from pedido where id_pedido = $pedido),
					`vl_titulo` = (select vl_pedido_desconto from pedido where id_pedido = $pedido)
								where id_pedido = $pedido;");
    $sql->execute();
}
?>

