<?php
require 'conexao.php';
//print_r($_POST);
//echo 'recebeu o ajax';
// recebe um post com o id da nota....
//$nota = $_POST['nota'];
//$array = [];

# INICIALMENTE DEVEMOS DECLARAR AS VARIAVES CONTENDO OS ENDPOINTS REFERENTES
# AO AMBIENTE DE HOMOLOGAÇÃO E PRODUÇÃO
$urlSandbox = "https://api.sandbox.plugnotas.com.br";
$urlProduction = "https://api.plugnotas.com.br";

# A SEGUINTE VARIAVEL FOI UTILIZADA PARA SIMULAR O ID_NOTA
$empresa = 'emissor';
$pdo = conectar();
$pdo2 = conectar();




# 2 - PASSO - CONSULTA DA NOTA (AQUI PODEMOS CHAMAR POR OUTRO BOTÃO OU CRIAR UM CRON QUE VERIFICA SE AS NOTAS JÁ FORAM TRANSMITIDAS)

# ABAIXO JÁ TEMOS O ID DA NOTA E AGORA VAMOS BUSCA-LO PARA SABER SE A 
# NOTA JÁ FOI TRANSMITIDAS E SE OS LINKS DE PDF E XML JÁ FORAM CRIADOS

$idNota = '618c0a27eada279e9f77f8f8';
//VERIFICA O ESTADO DA NOTA
$ch = curl_init("{$urlSandbox}/nfe/{$idNota}/xml");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
	"Content-Type: application/xml",
	"x-api-key: 2da392a6-79d2-4304-a8b7-959572c7e44d"  
]);

$response = curl_exec($ch);

$fp = fopen("../xml/$idNota.xml", "w");
$escreve = fwrite($fp, $response);		
fclose($fp);
//echo $response;
echo "<a href='xml/$idNota.xml' download id='baixar'>Baixar Arquivo</a>";

?>