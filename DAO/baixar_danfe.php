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

require_once '../funcao/confirma.php';


# 2 - PASSO - CONSULTA DA NOTA (AQUI PODEMOS CHAMAR POR OUTRO BOTÃO OU CRIAR UM CRON QUE VERIFICA SE AS NOTAS JÁ FORAM TRANSMITIDAS)

# ABAIXO JÁ TEMOS O ID DA NOTA E AGORA VAMOS BUSCA-LO PARA SABER SE A 
# NOTA JÁ FOI TRANSMITIDAS E SE OS LINKS DE PDF E XML JÁ FORAM CRIADOS

$idNota = '618c0a27eada279e9f77f8f8';
//VERIFICA O ESTADO DA NOTA
$ch = curl_init("{$urlSandbox}/nfe/{$idNota}/pdf");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
	"Content-Type: application/pdf",
	"x-api-key: 2da392a6-79d2-4304-a8b7-959572c7e44d"  
]);

$response = curl_exec($ch);
//$pdf = file_get_contents($ch);
$encoded = base64_encode($response);
//echo $encoded;

$decoded = base64_decode($encoded);
$pdf2 = fopen("../danfe/$idNota.pdf", 'w');
fwrite($pdf2,$decoded);
fclose($pdf2);

echo "<script>
$(document).ready(function() {
    $('#exampleModal').modal('show');
})
</script>

<!-- Modal -->
<div class='modal fade' id='exampleModal' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
  <div class='modal-dialog' role='document'>
    <div class='modal-content'>
      <div class='modal-header'>
       
        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
          <span aria-hidden='true'>&times;</span>
        </button>
      </div>
      <div class='modal-body'>
        Visualizar o Danfe
      </div>
      <div class='modal-footer'>
		<form action='ver_danfe' method='POST' target='blank'>
			<input type='hidden' name='id' value='$idNota'>
			
			<input type='hidden' name='pesquisa' value=''>
			<button type='submit' class='btn btn-success' id='confirma'> <span class='glyphicon glyphicon-ok'></span></button>
		</form>
       
      </div>
    </div>
  </div>
</div>
";
//echo dialogo('ver_danfe','Visuaizar do Danfe','id',"$id");
		echo clicar();

//echo '<embed src="danfe/'.$idNota.'.pdf" type="application/pdf" width="100%" height="100%" target="_blank">';

//echo $response;

?>