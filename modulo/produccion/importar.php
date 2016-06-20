<?PHP
	include '../../adodb5/adodb.inc.php';
	include '../../classes/function.php';

	$db = NewADOConnection('mysqli');
	//$db->debug = true;
	$db->Connect();

	$op = new cnFunction();

	$fecha 	= $op->ToDay();
	$hora	= $op->Time();
	$id 	= $_REQUEST['id'];

	$srtQ 	= "SELECT * FROM produccion WHERE id_produccion = $id ";
	$srtQr 	= $db->Execute($srtQ);
	$file 	= $srtQr->FetchRow();

	$srtSql = "SELECT * FROM empleado WHERE cargo = 'pre' ";
	$srtQuery = $db->Execute($srtSql);


?>
<script>

  $(document).ready(function(e) {

  /* idealForm */
	  $('#form').idealForms();
  /* Calendario */
	  $('#dateNac').datepicker({
		dateFormat: 'yy-mm-dd',
		changeMonth: true,
		changeYear: true,
		yearRange: 'c-40:c-0'
	  });
  /* Validación */
	  jQuery("#form").validationEngine({ 'custom_error_messages':{
	  	'max': {
            'message': "Se debe asignar toda la producci&oacute;n."
        },
	  	prettySelect	: true,
		useSuffix		: "_chosen",
		scroll		: false
	  }
	  });

  });
  /**
   * Funcion para restar actualizar cantidades
   */
  	function actuCant(id){
  		var valor = $('input#'+id).val();
  		alert(valor);
  		}
</script>
<style>
form div.WrapCOD {
    width: 100px;
}

form div.WrapDET {
    width: 460px;
}
input#cant[type="text"] {
    margin: 0px;
    width: 6em;
    cursor: not-allowed;
}
input#idInv[type="text"]{
	cursor: not-allowed;
}
input.pro[type="text"] {
	margin: 0;
	width: 6em;
}
form div.Wrap {
    width: 233px;
}
input#detalle[type="text"] {
    width: 30.5em;
    cursor: not-allowed;
}
form div.WrapDET {
    width: 349px;
}
</style>

  <form id="form" class="ideal-form" action="javascript:actuCant('form','savePro.php')" >
  	<fieldset>
      <legend>D E S I G N A R&nbsp;&nbsp;&nbsp;P R O D U C C I &Oacute; N</legend>
        <div class="idealWrap WrapDS">
        <label class="date">Fecha: </label>
        <input id="fecha" name="fecha" type="text" value="<?=$fecha;?> <?=$hora;?>" disabled="disabled" />
        <input id="date" name="date" type="hidden" value="<?=$fecha;?> <?=$hora;?>" />
        <input id="tabla" name="tabla" type="hidden" value="produccion" />
        </div><!--End idealWrap-->
        <div class="clearfix"></div>

        <div class="idealWrap WrapCOD">
        <input id="idInv" name="idInv" readonly="off" type="text" placeholder="Codigo" value="<?=$file['id_inventario']?>" />
        </div><!--End idealWrap-->

        <div class="idealWrap WrapDET">
        <input id="detalle" name="detalle" readonly="off" type="text" placeholder="Nombre producto" value="<?=$file['detalle'];?>"/>
        </div><!--End idealWrap-->
        <div class="clearfix"></div>

        <div class="idealWrap Wrap">
        <label>Cantidad a designar: </label>
        <input id="cant" name="cant" type="text" placeholder="Cantidad" value="<?=$file['cantidad'];?>" class="validate[max[0]]"/>
        </div><!--End idealWrap-->
        <div class="clearfix"></div>

        <p style="text-align: center; font-weight: bold; font-size: 14px; margin: 10px 0; color: #112863">ASIGNAR CANTIDADES</p>

        <?php
        	$c=0;
         while( $row = $srtQuery->FetchRow() ){
         	$c++;
        ?>

        <div class="idealWrap Wrap">
        <label><?=$row['nombre'].' '.$row['apP'];?>: </label>
        <input id="pro<?=$c;?>" name="pro" type="text" placeholder="Cantidad" onblur="actuCant()" value="" class="validate[custom[integer]] text-input pro" />
        </div><!--End idealWrap-->
        <div class="clearfix"></div>

        <?php
    	}
        ?>



	</fieldset>

		<div class="idealWrap" align="center">
			<input id="reset" type="reset" onclick="clearForm('form');" value="Limpiar...">
			<input id="save" type="submit" value="Guardar...">
		</div>

  </form>
<div class="clearfix"></div>
