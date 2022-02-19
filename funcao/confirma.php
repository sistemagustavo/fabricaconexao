<?php
function dialogo($tela,$mensagem,$criterio,$id){
		$dialogo = "
<script>
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
         $mensagem
      </div>
      <div class='modal-footer'>
		<form action='$tela' method='POST'>
			<input type='hidden' name='$criterio' value='$id'>
			
			<input type='hidden' name='pesquisa' value=''>
			<button type='submit' class='btn btn-success' id='confirma'> <span class='glyphicon glyphicon-ok'></span></button>
		</form>
       
      </div>
    </div>
  </div>
</div>";
		
	return $dialogo;	
}
function dialogo2($tela,$mensagem,$criterio,$id,$criterio2,$id2){
		$dialogo = "
<script>
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
         $mensagem
      </div>
      <div class='modal-footer'>
		<form action='$tela' method='POST'>
			<input type='hidden' name='$criterio' value='$id'>
			<input type='hidden' name='$criterio2' value='$id2'>
			
			<input type='hidden' name='pesquisa' value=''>
			<button type='submit' class='btn btn-success' id='confirma'> <span class='glyphicon glyphicon-ok'></span></button>
		</form>
       
      </div>
    </div>
  </div>
</div>";
		
	return $dialogo;	
}
function outrapagina($tela,$mensagem,$criterio,$id){
		$dialogo = "
<script>
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
         $mensagem
      </div>
      <div class='modal-footer'>
		<form action='$tela' method='POST' target='_blank'>
			<input type='hidden' name='$criterio' value='$id'>
			
			<input type='hidden' name='pesquisa' value=''>
			<button type='submit' class='btn btn-success' id='confirma'> <span class='glyphicon glyphicon-ok'></span></button>
		</form>
       
      </div>
    </div>
  </div>
</div>";
		
	return $dialogo;	
}
function outrapagina2($tela,$mensagem,$criterio,$id, $criterio2, $id2){
		$dialogo = "
<script>
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
         $mensagem
      </div>
      <div class='modal-footer'>
		<form action='$tela' method='POST' target='_blank'>
			<input type='hidden' name='$criterio' value='$id'>
			<input type='hidden' name='$criterio2' value='$id2'>
			
			<input type='hidden' name='pesquisa' value=''>
			<button type='submit' class='btn btn-success' id='confirma'> <span class='glyphicon glyphicon-ok'></span></button>
		</form>
       
      </div>
    </div>
  </div>
</div>";
		
	return $dialogo;	
}

function clicar(){
	$clicar = "<script type='text/javascript'>
					$('#confirma').trigger('click');
				</script>";
	return $clicar;			
}

function atualizar_pagina($mensagem){
	$dialogo = '
	<script>
		$("#myModal").modal("show");
	
	</script>
	<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      
      <div class="modal-body">
	  <p>'.$mensagem.'</p>
		<div class="row">
			
			<div class="col-md-3">
			  <label for="inputZip"></label>
					<button type="button" class="btn btn-danger" data-dismiss="modal" style="color:white; margin-top:5px; width: 100%; height: 50px;">Cancelar</button>
			</div>
		</div>
      </div><!-- end body-->   
    </div>
  </div>
</div>
<!-- end modal -->';

		
	return $dialogo;			
}
?>

