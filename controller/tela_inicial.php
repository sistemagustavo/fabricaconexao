<?php
header("Content-type: text/html; charset=utf-8");
//session_cache_expire(30); // 30 min
//$cache_expire = session_cache_expire();
session_start(); 
$pdo = conectar();
$template = new template();
//pegando o nome do pc
$hostName = gethostbyaddr($_SERVER['REMOTE_ADDR']);
//daqui por diante sua imaginação é o limite ;)

$template->carrega_template('inicio'); // nome do arquivo html que vai ser concatenado no template.php
$template->carrega_sub_template('pagina','tela_inicial');// inicio.html 
$id = $_SESSION['usuario']['id_funcionario'];
$nome_usuario = $_SESSION['usuario']['nome'];
//$template->seta_variavel('',$sms);
if(isset($_POST['deslogar'])){
	 unset($_SESSION['usuario']);
	 unset($_SESSION['define_caixa']);
	 echo "<script type = 'text/javascript'> location.href = 'login'</script>";
      
}
// MODAL CRITERIO DE PESQUISA FECHAMENTO TEMPERA
// pesquisa as temperas
			$sql = $pdo->prepare( "SELECT * FROM pessoa WHERE fornecedor = 'S' AND fabrica = 'S'");
			$sql->execute();
			$tempera = '';
			while ( $ln = $sql->fetch(PDO::FETCH_OBJ)){	
				$tempera .= '<label><input type="radio" name="id_fabrica" class="optcaixa" value="'.$ln->id_pessoa.'">'.$ln->nome.'</label><br>';
			}
			
			//$sql = $pdo->prepare( "SELECT os_tempera, id_pessoa from pedido where id_pedido = $id_pedido");
			//$sql->execute();
			//$ln = $sql->fetch(PDO::FETCH_OBJ);
			echo '
			
			<!-- Modal novo pedido -->
				<div id="ModalRemessa" class="modal fade" role="dialog" >
				  <div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content">      
					  <div class="modal-body">
					  <p>Selecionar o período do Fechamento da Fábrica</p>
						<div class="row">
							<form action="r_fechamento_fabrica" method="POST" target="_blank">
							
							
								<div class="col-md-8">															
										<div class="controls">
											'.$tempera.'
										</div>
								  </div>
								
								<div class="col-md-6">
										<label class="control-label" for="selectbasic">Data Inicial Envio</label>					
										<div class="controls">
											<input type="text" class="form-control" id="data_inicial_envio" name="data_inicial_envio" autocomplete="off">
										</div>
								  </div>
								<div class="col-md-6">
										<label class="control-label" for="selectbasic">Data Final Envio</label>					
										<div class="controls">
											<input type="text" class="form-control" id="data_final_envio" name="data_final_envio" autocomplete="off">
										</div>
								  </div>
								
														
								<div class="col-md-3">
								   <label for="inputZip"></label>
								   <button type="submit" id="confirma_pedido" class="btn btn-success" style="color:white; margin-top:5px; width: 100%; height: 50px;">Confirmar</button>
								</div>
								<div class="col-md-3">
								  <label for="inputZip"></label>
									<button type="button" class="btn btn-warning" data-dismiss="modal" style="color:white; margin-top:5px; width: 100%; height: 50px;">Cancelar</button>
								</div>
								</div><!-- end div class row-->
							</form>	
						
					  </div><!-- end body-->     
					</div>
				  </div>
				</div><!-- end modal pedido-->';

$sql = $pdo->prepare("SELECT * from permissao where id_pessoa = $id");
$sql->execute();
//echo $sql->debugDumpParams();
$ln = $sql->fetch(PDO::FETCH_OBJ);



/*********************************************************************************************************************************************************************
**************************************************************** sms ********************************************************************************************
**********************************************************************************************************************************************************************/

if($ln->sms == 'S'){
	$sms = '<!--<a href="sms"><button type="submit" style="width: 100%; height: 50px; color:black; opacity: 0.7;">SMS</button></a>-->';
	$template->seta_variavel('sms',$sms);
	
}else{
	$template->seta_variavel('sms','');
}

/*********************************************************************************************************************************************************************
**************************************************************** CADASTRO ********************************************************************************************
**********************************************************************************************************************************************************************/
if($ln->cadastro == 'S'){
	$cadastro = '<button type="submit" data-toggle="collapse" data-target="#demo" style="width: 100%; height: 50px; color:black; opacity: 0.7;">
  <span class="glyphicon glyphicon-collapse-down"></span> CADASTRO</button> 
  <div id="demo" class="collapse">';
		if($ln->cad_cli == 'S'){
			$cadastro .= '
					   <a href="lst_cliente"><button type="button" data-toggle="collapse" data-target="#demo" style="width: 100%; background-color:black; color:white; height: 50px; opacity: 0.6;">Cliente</button></a> 
					  <a href="lst_fornecedor"><button type="button" data-toggle="collapse" data-target="#demo" style="width: 100%; background-color:black; color:white; height: 50px; opacity: 0.6;">Fornecedor</button></a>';
		}
		if($ln->empresa == 'S'){
			$cadastro .= '
					   <a href="empresa"><button type="button" data-toggle="collapse" data-target="#demo" style="width: 100%; background-color:black; color:white; height: 50px; opacity: 0.6;">Empresa</button></a>';
		}
		if($ln->cad_fun == 'S'){
			$cadastro .= '<a href="lst_funcionario"><button type="button" data-toggle="collapse" data-target="#demo" style="width: 100%; background-color:black; color:white; height: 50px; opacity: 0.6;">Usuário</button></a> ';
		}
		if($ln->cad_produto == 'S'){
			$cadastro .= '<a href="lst_produto"><button type="button" style="width: 100%; background-color:black; color:white; height: 50px; opacity: 0.6;">Produto</button></a>
                          <a href="lst_grupo_produto"><button type="button" style="width: 100%; background-color:black; color:white; height: 50px; opacity: 0.6;">Grupo Produto</button></a>
						  <a href="lst_aplicacao"><button type="button"  style="width: 100%; background-color:black; color:white; height: 50px; opacity: 0.6;">Processo Aplicação</button></a>
						 <a href="lst_cor_vidro"><button type="button" style="width: 100%; background-color:black; color:white; height: 50px; opacity: 0.6;">Cor Vidro</button></a> ';
		}
		if($ln->cad_projeto == 'S'){
			$cadastro .= '<a href="lst_projeto"><button type="button" data-toggle="collapse" data-target="#demo" style="width: 100%; background-color:black; color:white; height: 50px; opacity: 0.6;">Projetos</button></a> ';
			$cadastro .= '<a href="lst_formula"><button type="button" data-toggle="collapse" data-target="#demo" style="width: 100%; background-color:black; color:white; height: 50px; opacity: 0.6;">Fórmula</button></a> 
						  <a href="lst_fase_pedido"><button type="button" data-toggle="collapse" data-target="#demo" style="width: 100%; background-color:black; color:white; height: 50px; opacity: 0.6;">Fase Pedido</button></a>';
		}        
        	
		 if($ln->financeiro == 'S'){
			$cadastro .= '
			<a href="lst_grupo_plano_financeiro"><button type="button" data-toggle="collapse" data-target="#demo" style="width: 100%; background-color:black; color:white; height: 50px; opacity: 0.6;">Grupo Plano Financeiro</button></a>
			<a href="lst_plano_financeiro"><button type="button" data-toggle="collapse" data-target="#demo" style="width: 100%; background-color:black; color:white; height: 50px; opacity: 0.6;">Plano Financeiro</button></a>';
		}		
		$cadastro .= '</div>';
	$template->seta_variavel('cadastro',$cadastro);
	
}else{
	$template->seta_variavel('cadastro','');
}

if($ln->almoxarifado == 'S'){
	$almoxarifado = '<button type="submit" data-toggle="collapse" data-target="#almoxarifado" style="width: 100%; height: 50px; color:black; opacity: 0.7;">
  <span class="glyphicon glyphicon-collapse-down"></span>ALMOXARIFADO</button> 
  <div id="almoxarifado" class="collapse">
  <a href="lst_entrada_estoque"><button type="button" data-toggle="collapse" data-target="#demo" style="width: 100%; background-color:black; color:white; height: 50px; opacity: 0.6;">Lista Entrada</button></a> 
 </div>';
	
	$template->seta_variavel('almoxarifado',$almoxarifado);
	
}else{
	$template->seta_variavel('almoxarifado','');
}
if($ln->empresa == 'S'){
	$empresa = '	
	<a href="empresa"><button type="submit" style="width: 100%; height: 50px; color:black; opacity: 0.7;">
   EMPRESA</button></a>';
	$template->seta_variavel('empresa',$empresa);
	
}else{
	$template->seta_variavel('empresa','');
}
if($ln->cad_venda == 'S'){
	$pedido = '
	
	<a href="lst_pedido"><button type="submit" style="width: 100%; height: 50px; color:black; opacity: 0.7;">PEDIDO</button></a>
	<a href="pesquisa_visita"><button type="submit" style="width: 100%; height: 50px; color:black; opacity: 0.7;">PESQUISA VISITAS</button></a>
	<a href="lst_nota"><button type="submit" style="width: 100%; height: 50px; color:black; opacity: 0.7;">NOTA FISCAL</button></a>';
	$template->seta_variavel('pedido',$pedido);
	
}else{
	$template->seta_variavel('pedido','');
}
/*********************************************************************************************************************************************************************
**************************************************************** PRODUÇÃO ********************************************************************************************
**********************************************************************************************************************************************************************/
if($ln->producao == 'S'){
	$producao = '<button type="submit" data-toggle="collapse" data-target="#producao" style="width: 100%; height: 50px; color:black; opacity: 0.7;">
  <span class="glyphicon glyphicon-collapse-down"></span> PRODUÇÃO</button> 
  <div id="producao" class="collapse">';
	$producao .= ' 
	<a href="lst_producao"><button type="button" data-toggle="collapse" data-target="#producao" style="width: 100%; background-color:black; color:white; height: 50px; opacity: 0.6;">Lst Produção</button></a>
	
	<button type="button" data-toggle="collapse" data-target="#producao" id="fase_peca" style="width: 100%; background-color:black; color:white; height: 50px; opacity: 0.6;">Fase Peça</button>
	<button type="button" data-toggle="collapse" data-target="#medicao" id="btn-medicao" style="width: 100%; background-color:black; color:white; height: 50px; opacity: 0.6;">Medição</button>
	';
	
	//<button type="button" data-toggle="collapse" data-target="#producao" id="fase_pedido" style="width: 100%; background-color:black; color:white; height: 50px; opacity: 0.6;">Fase Pedido</button>
	if($ln->carregamento == 'S'){
	$producao .= '<a href="lst_carregamento"><button type="button" data-toggle="collapse" data-target="#producao" style="width: 100%; background-color:black; color:white; height: 50px; opacity: 0.6;">Carregamentos</button></a>';        
	
	}
	$producao .= '</div>';
	$template->seta_variavel('producao',$producao);
	
}else{
	$template->seta_variavel('producao','');
}
/*********************************************************************************************************************************************************************
**************************************************************** FINANCEIRO ********************************************************************************************
**********************************************************************************************************************************************************************/
if($ln->financeiro == 'S'){
	$financeiro = '
	<button type="submit" data-toggle="collapse" data-target="#financeiro" style="width: 100%; height: 50px; color:black; opacity: 0.7;">
  <span class="glyphicon glyphicon-collapse-down"></span> FINANCEIRO</button> 
  <div id="financeiro" class="collapse">';	
	if($ln->conta_receber == 'S'){
	$financeiro .= '<a href="lst_conta_receber"><button type="button" data-toggle="collapse" data-target="#demo" style="width: 100%; background-color:black; color:white; height: 50px; opacity: 0.6;">Contas à Receber</button></a>
					<a href="lst_conta_pagar"><button type="button" data-toggle="collapse" data-target="#demo" style="width: 100%; background-color:black; color:white; height: 50px; opacity: 0.6;">Contas à Pagar</button></a>
					<button type="button" data-toggle="collapse" data-target="#medicao" id="btn-fluxo" style="width: 100%; background-color:black; color:white; height: 50px; opacity: 0.6;">Fluxo de Caixa</button>
					<a href="lst_caixa"><button type="button" data-toggle="collapse" data-target="#demo" style="width: 100%; background-color:black; color:white; height: 50px; opacity: 0.6;">Caixa</button></a>
					<button type="button" data-toggle="collapse" id="btn-fechamento-fabrica" data-target="#demo" style="width: 100%; background-color:black; color:white; height: 50px; opacity: 0.6;">Fechamento Fábrica</button>';        
	
	}

	if($ln->conta_pagar == 'S'){
		$financeiro .= '
				';     
		
	}
			
            
	$financeiro .='
	</div>';
	$template->seta_variavel('financeiro',$financeiro);
	
	
}else{
	$template->seta_variavel('financeiro','');
}

/*********************************************************************************************************************************************************************
**************************************************************** GERENCIA ********************************************************************************************
**********************************************************************************************************************************************************************/
if($ln->gerencia == 'S'){
$gerencia = '
	<button type="submit" data-toggle="collapse" data-target="#gerencia" style="width: 100%; height: 50px; color:black; opacity: 0.7;">
  <span class="glyphicon glyphicon-collapse-down"></span>GERENCIA</button> 
  <div id="gerencia" class="collapse">';	
	
	$gerencia .= '<a href="g_produto"><button type="button" data-toggle="collapse" data-target="#gerencia" style="width: 100%; background-color:black; color:white; height: 50px; opacity: 0.6;">Produtos Vendidos</button></a>
				<a href="r_venda_cliente"><button type="button" data-toggle="collapse" data-target="#gerencia" style="width: 100%; background-color:black; color:white; height: 50px; opacity: 0.6;">Vendas Clientes</button></a>
				<a href="lst_log"><button type="button" data-toggle="collapse" data-target="#lst_log" style="width: 100%; background-color:black; color:white; height: 50px; opacity: 0.6;">Log</button></a>';        

$gerencia .='</div>';
$template->seta_variavel('gerencia',$gerencia);
	
}


	
	
	


  


    
  if(isset($_SESSION['usuario'])){
   date_default_timezone_set('America/Sao_Paulo');	
	$data = date("d-m-Y H:i:s");
			
	$template->seta_variavel('usuario',$_SESSION['usuario']['id_funcionario'].' - '.$_SESSION['usuario']['nome'].' - '.$data);
	$template->seta_variavel('usuario_sistema',''.$_SESSION['usuario']['nome'].'');	
	$template->seta_variavel('id_usuario',''.$_SESSION['usuario']['id_funcionario'].'');	
	
	$template->exibe_template();
}else{
	echo "<script type = 'text/javascript'> location.href =  'login'</script>";
}
