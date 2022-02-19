<?php
session_start(); 
//print_r($_POST);


$template = new template();
$template->carrega_template('inicio'); // nome do arquivo html que vai ser concatenado no template.php
$template->carrega_sub_template('pagina','frm_transmissao');// inicio.html 

$pedido = 1233;//filter_input(INPUT_POST, 'id',  FILTER_SANITIZE_NUMBER_INT);
$emissor = 'DEMONSTRACAO';
//require 'conexao.php';
$template->seta_variavel('emissor',$emissor);
$pdo = conectar();


$template->seta_variavel('emissor',$emissor);
$template->seta_variavel('pedido',$pedido);

$sql = $pdo->prepare("SELECT MAX(nnf) + 1 as proxima FROM pedido;");
$sql->execute();
$ln = $sql->fetch(PDO::FETCH_OBJ);
$template->seta_variavel('proxima_nota', $ln->proxima);

$sql = $pdo->prepare( "SELECT * FROM empresa" );
$sql->execute();   
$ln = $sql->fetch(PDO::FETCH_OBJ); 

$template->seta_variavel('url_saas', $ln->url_saas);
$template->seta_variavel('grupo_saas', $ln->grupo_saas);
$template->seta_variavel('usuario_saas', $ln->usuario_saas);
$template->seta_variavel('senha_saas', $ln->senha_saas);
$template->seta_variavel('cnpj', $ln->cpf_cnpj);

/***********************************************************************************************************************************************************
******************************************** Cabeçalho da nota ******************************************************************************
************************************************************************************************************************************************************/ 
$sql = $pdo->prepare("SELECT * FROM empresa;");
$sql->execute();
$ln = $sql->fetch(PDO::FETCH_OBJ);
$timp = $ln->timp;
$template->seta_variavel('timp',$ln->timp);
$tamb = 2? '2 - HOMOLOGACAO' :' 1 - PRODUÇÃO';
$emissao_propria = $ln->emissao_propria;
$vsistema = $ln->vsistema;
$vnota = $ln->vnota;
$cMunFG = $ln->cod_municipio;
$cufemissor = $ln->cuf;

$emissao_propria = $ln->emissao_propria;


$sql = $pdo->prepare("SELECT p.*, pe.consumidor_final, e.cuf, fe.finalidade_nfe FROM pedido p, pessoa pe, endereco e, finalidade_nfe fe WHERE 
p.id_pedido = $pedido and pe.id_pessoa = p.id_pessoa and e.id_pessoa = pe.id_pessoa and e.tipo_endereco='C' AND p.finnfe = fe.id_finalidade;");
$sql->execute();
//echo $sql->debugDumpParams();
$ln = $sql->fetch(PDO::FETCH_OBJ);
$cufdestino = $ln->cuf;
$template->seta_variavel('natop',$ln->natop);
$template->seta_variavel('mod',$ln->mod);
$template->seta_variavel('serie',$ln->serie);
$tpnf = 1? ' 1 - SAÍDA' : '0 - ENTRADA';
$template->seta_variavel('tpnf',$tpnf);
$template->seta_variavel('tamb',$tamb);
$template->seta_variavel('finnfe',''.$ln->finnfe.' - '.$ln->finalidade_nfe.'');
$cufemissor == $cufdestino ? $ind_dest = 1 : $ind_dest = 2;
$ind_dest = 1? ' 1 - INTERNO' : '2 - INTERESTADURAL';
$template->seta_variavel('ind_dest',$ind_dest);


/*********************************************************************************************************************************
*********************************************** DADOS DO CLIENTE ****************************************************************
***********************************************************************************************************************************/
$sql = $pdo->prepare("SELECT pe.*,e.* FROM pedido p, pessoa pe, endereco e WHERE 
e.id_pessoa = pe.id_pessoa and e.tipo_endereco = 'C' and p.id_pessoa = pe.id_pessoa and p.id_pedido = $pedido;");
$sql->execute();
$ln = $sql->fetch(PDO::FETCH_OBJ);
$cf = $ln->consumidor_final;
$cf == 1? $cf = ' 1 - Sim' : $cf = '0 - Não';
$template->seta_variavel('cf',$cf);
$template->seta_variavel('cliente',$ln->nome);

/***********************************************************************************************************************************************************
******************************************** itens da nota ******************************************************************************
************************************************************************************************************************************************************/ 

 $sql = $pdo->prepare( "select p.*,i.* FROM 
                        item_projeto_pedido i, produto p,grupo_produto g WHERE 
						i.id_pedido = $pedido and
						g.id_grupo_produto = p.id_grupo_produto AND	
						p.id_produto = i.id_produto AND 
						
						i.id_projeto_pedido = 0
						" );
$sql->execute();
//echo $sql->debugDumpParams();
$linha = 0;
$registro = '';
 while ( $ln = $sql->fetch(PDO::FETCH_OBJ)){ 
 $linha++;
	if($ln->medida == 1){
		$m2 = number_format($ln->qtdm2, 3, ',', '.');
		$qtd = '----';
		
		
		
	}
	if($ln->medida == 3 ){
		$qtd = $ln->qtd;
		$m2 = '----';
		
	}
	$ml  = '';
    $registro .= '	 

			  <tr>			 
					<td >'.$linha.'</td>		
                    <td style="width:200px;" >'.$ln->produto.'</td>							
                    <td title="qtd" class=""><center>'.$qtd.'</center></td>					
					<td title="ml" class="edita_item">'.$ml.'</td>
					<td title="ml" class="edita_item">'.$m2.'</td>									
					<td title="preco" class="edita_item"><center>'.number_format($ln->vl_unit_desconto, 2, ',', '.').'</center></td>					
					<td title="id_grupo" class="select_grupo"><center>'.number_format($ln->vl_total_desconto, 2, ',', '.').'</center></td>
					<td title="cfopnf" class="edita_item">'.$ln->cfopnf.'</td>	
					<td title="ml" class="edita_item">'.$ln->cst_origemnf.''.$ln->cstnf.'</td>
					<td title="ml" class="edita_item">'.$ln->cst_pisnf.'</td>
					<td title="ml" class="edita_item">'.$ln->cst_cofinsnf.'</td>		
					<td title="ambiente" class="edita_item">'.$ln->ambiente.'</td>
                    <td style="color:white;">item_projeto_pedido</td> 
					
                </tr>';
 }
 

 $registro .='';
 $template->seta_variavel('registro',$registro);
/***********************************************************************************************************************************************************
******************************************** parcelas ******************************************************************************
************************************************************************************************************************************************************/ 

$sql = $pdo->prepare("SELECT f.id_fatura_nota, f.data_vencimento, f.vl_fatura, m.tipo_pagamento_nota FROM 
fatura_nota f , tipo_pagamento_nota m WHERE id_pedido = $pedido and f.id_moeda = m.ind_pagamento;");
$sql->execute();
$parcela = '';
while ( $ln = $sql->fetch(PDO::FETCH_OBJ)){
	$parcela .= '	 

		<tr>
			<td>'.$ln->id_fatura_nota.'</td>
			<td ><button type="button" class="btn-danger"  value="'.$ln->id_fatura_nota.'"> <span class="glyphicon glyphicon-remove"></span></button></td>
			 <td title="data_vencimento" class="campodata">'.date('d/m/Y', strtotime($ln->data_vencimento)).'</td>
			<td title="vl_fatura" class="valor">'.number_format($ln->vl_fatura, 2, ',', '.').'</td>
			<td title="id_moeda" class="editavel">'.$ln->tipo_pagamento_nota.'</td>
			<td>fatura_nota</td>		
        </tr>';
				

}
$template->seta_variavel('parcela',$parcela);


$template->exibe_template();
