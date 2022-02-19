
<?php
/*
{"documents":[{"idIntegracao":"TESTENota123","Emitente":"43054044000107","id":"61fc2dc72ae060cfb53bcfa2"}],
"message":"Nota(as) em processamento","protocol":"fc50c9cf-705e-42f0-a86d-cdb4823cb667"}
*/
require 'conexao.php';
//print_r($_POST);
//echo 'recebeu o ajax';
// recebe um post com o id da nota....
$nota = $_POST['nota'];
$array = [];

# INICIALMENTE DEVEMOS DECLARAR AS VARIAVES CONTENDO OS ENDPOINTS REFERENTES
# AO AMBIENTE DE HOMOLOGAÇÃO E PRODUÇÃO
$urlSandbox = "https://api.sandbox.plugnotas.com.br";
$urlProduction = "https://api.plugnotas.com.br";

# A SEGUINTE VARIAVEL FOI UTILIZADA PARA SIMULAR O ID_NOTA
$empresa = 'emissor';
$pdo = conectar();
$pdo2 = conectar();

try {
	/************************************************************************************************************************************************
	************************************************* PRODUTOS DA NOTA ******************************************************************************
	*************************************************************************************************************************************************/
    
	
	$sql = $pdo->prepare("SELECT p.id_produto, p.produto, p.ncm_prod,p.cest_prod, i.cfopnf,i.qtd,i.qtdm2, i.vl_unit_desconto, 
	i.vl_total_desconto, i.cst_origemnf, i.cstnf, i.cst_pisnf, i.cst_cofinsnf
	 FROM produto p, item_projeto_pedido i WHERE
	p.id_produto = i.id_produto AND
	i.id_pedido = $nota;");
	$sql->execute();
	//echo $sql->debugDumpParams();
	//$itens = '';
	while($ln = $sql->fetch(PDO::FETCH_OBJ)) {
		//$item +=;
		$itens[] = [
		//(double) number_format($ln->vl_unit_desconto, 2, ',', '.')
			'codigo'=>"$ln->id_produto",
			'descricao'=> $ln->produto,
			'ncm'=>$ln->ncm_prod,
			'cest'=>$ln->cest_prod,
			'cfop'=>$ln->cfopnf,
			'valorUnitario'=>[
				'comercial'=>round($ln->vl_unit_desconto,2),
				'tributavel'=>round($ln->vl_unit_desconto,2)
			],
			'valor'=>round($ln->vl_unit_desconto,2),
			'tributos'=>[
				'icms'=>[
					'origem'=>$ln->cst_origemnf,
					'cst'=>$ln->cstnf
					
				],
				'pis'=>[
					'cst'=>$ln->cst_pisnf,					
					'aliquota'=>0.00,
					'valor'=>0.00
				],
				'cofins'=>[
					'cst'=>$ln->cst_cofinsnf,
					'aliquota'=>0.00,
					'valor'=>0.00
				]
			
			]
		];	
	}// end while	
	
	# OS DADOS ABAIXO UTILIZA INFORMAÇÕES DO BANCO DE DADOS E ALGUNS ESTÁTICOS
    # OBS!!! O CAMPOS "Idintegracao" É UNICO PARA CADA NOTA. SEMPRE QUE PRECISAR GERAR UMA NOTA É INTERESSANTE UTILIZAR UM 
    # ID PRIMÁRIO DO BANCO DE DADOS OU UMA FUNÇÃO DE VALORES RANDOMICOS DO PHP, COMO POR EXEMPLO A SRAND			
	$sql = $pdo->prepare( 'select * from empresa');
    $sql->execute();
	$ln = $sql->fetch(PDO::FETCH_OBJ);
	
	$sql2 = $pdo2->prepare("SELECT p.vl_pedido_desconto, pe.cpf_cnpj, pe.nome,pe.email,e.endereco, e.numero, e.complemento, e.bairro,
	e.cidade, e.cod_municipio, e.uf, e.cep
	 FROM pedido p, pessoa pe, endereco e WHERE
	p.id_pessoa = pe.id_pessoa AND
	pe.id_pessoa = e.id_pessoa AND
	e.tipo_endereco = 'C' AND
	p.id_pedido = $nota;");
    $sql2->execute();
	$ln2 = $sql2->fetch(PDO::FETCH_OBJ);
	
        $array[] = [
					'idIntegracao' => 'TESTENota123',
					'presencial'=> true,
					'consumidorFinal'=> true,
					'natureza'=> 'OPERACAO INTERNA',
					'versaoManual'=> '4.0',
					'serie'=> '1',
					
					'emitente' => [
						'cpfCnpj' =>$ln->cpf_cnpj, //$'08187168000160'
					],
					[
						'nfe' => [
								'config '=>[
									'producao' => false,
								]
							]
					],
					
					'destinatario' => [
						'cpfCnpj'=> $ln2->cpf_cnpj,
					    'razaoSocial'=> $ln2->nome,
					    'email'=> $ln2->email,
						'endereco' =>[
							'logradouro'=> $ln2->endereco,
							'numero'=> $ln2->numero,
							'bairro'=> $ln2->bairro,
							'codigoCidade'=> $ln2->cod_municipio,
							'descricaoCidade'=> $ln2->cidade,
							'estado'=> $ln2->uf,
							'cep'=> $ln2->cep
						],//endereco
					],// destinatario
					
					'itens'=>  
							$itens
						,
					'transporte'=>[
							'modalidadeFrete'=>'1', 
							'transportador'=>[
								'cnpj'=>'',
								'cpf'=>'01332298888',
								'nome'=>'DESTINATARIO',
								'inscricaoEstadual'=>'ISENTO',	
								'endereco'=>[
									'logradouro'=>'VAZIO',
									'descricaoCidade'=>'VAZIO',									
									'uf'=>'XX'
								],// ENDERECO	

							],//TRANSPORTADOR
						],//TRANSPORTE
						
						
						'pagamentos'=>[
							[
							'aVista'=>true,
							'meio'=>'01',
							'valor'=>(float)number_format(1233.76, 2, '.', '') 
							]
						],
						'informacoesComplementares' => 'Observacoes da da nota fiscal',
						
						'responsavelTecnico'=>[
							'cpfCnpj'=>'08187168000160',
							'nome'=>'Tecnospeed',
							'email'=>'contato@tecnospeed.com.br',
							'telefone'=>[
								'ddd'=>44,
								'numero'=>30379500

							],
					],
				];
				echo '<br>';
				echo json_encode($array);
				echo '<br>';
   // }// end while
} catch(PDOException $e) {
    echo $e;
}

# 1 - PASSO - GERAÇÃO DA NOTA

# AQUI VAMOS ENVIAR TODAS AS INFORMAÇÕES PARA O PLUGNOTAS
# OBS!!! LEMBRE-SE DE TROCAR O VALOR DA VARIAVEL X-API-KEY PELA 
# CHAVE DE PRODUÇÃO QUANDO FOR MUDAR DO AMBIENTE DE TESTE PARA PRODUÇÃO. 
# MESMA COISA É VALIDA PARA A URLSANDOX E URLPRODUCTION
$ch = curl_init("{$urlProduction}/nfe");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($array));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
	"Content-Type: application/json",
	"x-api-key: 79ce208d-13fb-4a83-ade2-2cb5575c5092"  
]);

//  "x-api-key: 2da392a6-79d2-4304-a8b7-959572c7e44d" sandbox 
// "x-api-key: 79ce208d-13fb-4a83-ade2-2cb5575c5092"  Production
$response = curl_exec($ch);

if($response === false){
  $response = curl_error($ch);
}

# NESSE MOMENTO CONSEGUIMOS PEGAR OS DADOS QUE FORAM GERADOS NO PLUGNOTA
# INCLUSIVE O ID QUE SERÁ UTILIZADO NO SEGUNDO PASSO
echo $response;
return;


# 2 - PASSO - CONSULTA DA NOTA (AQUI PODEMOS CHAMAR POR OUTRO BOTÃO OU CRIAR UM CRON QUE VERIFICA SE AS NOTAS JÁ FORAM TRANSMITIDAS)

# ABAIXO JÁ TEMOS O ID DA NOTA E AGORA VAMOS BUSCA-LO PARA SABER SE A 
# NOTA JÁ FOI TRANSMITIDAS E SE OS LINKS DE PDF E XML JÁ FORAM CRIADOS

$idNota = '611e9d6a2784801320750c44';
//VERIFICA O ESTADO DA NOTA
$ch = curl_init("{$urlProduction}/nfe/{$idNota}/resumo");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
	"Content-Type: application/pdf",
	"x-api-key: 79ce208d-13fb-4a83-ade2-2cb5575c5092"  
]);
//"x-api-key: 2da392a6-79d2-4304-a8b7-959572c7e44d"  
$response = curl_exec($ch);
echo $response;

?>