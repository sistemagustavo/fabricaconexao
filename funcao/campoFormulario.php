<?php
class campoFormulario {
    public function campo_text($tamanho, $id){
        echo ' <div class="form-group col-md-'.$tamanho.' input-group-lg">
        <label for="inputEmail4">Campo</label>
        <input type="text" class="form-control" name="cst_icms" value="'.$id.'" autocomplete="off">
    </div>';
    }
}

?>