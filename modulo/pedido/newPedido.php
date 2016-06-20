<?PHP
	include '../../adodb5/adodb.inc.php';
	include '../../classes/function.php';

	$db = NewADOConnection('mysqli');
	//$db->debug = true;
	$db->Connect();

	$op = new cnFunction();

	$fecha = $op->ToDay();
	$hora = $op->Time();

	$strQuery = "SELECT max(id_pedido) FROM pedido ";
	$lastId = $db->Execute($strQuery)->FetchRow();
	if(!$lastId[0])
		$lastId[0] = 1;
	else
		$lastId[0]++;
?>
<style>
a.button{
	width:8.5em;
	}
.obs{
	padding:0 20px;
	width:120px;
	}
textarea#obs {     	/* Para el resumen */
	width: 107px;
	height: 12em;
	overflow: auto;
}
.ui-autocomplete-loading { background: white url('images/ui-anim_basic_16x16.gif') right center no-repeat; }
/*table#listaP{
	width:204px;
	}*/

table#listaP * {height: auto; min-height: none;} /* fixed ie9 & <*/
table#listaP {
  background: ##F7F7F7;
  table-layout: fixed;
  /*margin: 1rem auto;*/
  width: 100%;
  /*box-shadow: 0 0 4px 2px rgba(0,0,0,.4);*/
  border-collapse: collapse;
 /* border: 1px solid rgba(0,0,0,.5);*/
  border-top: 0 none;
}
table#listaP thead {
  background: #A3321D;
  text-align: center;
  z-index: 2;
}
table#listaP thead tr {
  padding-right: 17px;
  /*box-shadow: 0 4px 6px rgba(0,0,0,.6);*/
  z-index: 2;
}
table#listaP th {
  border-right: 1px solid rgba(0,0,0,.2);
  padding: 5px;
  font-size: 11px;
  font-weight: normal;
  font-variant: small-caps;
}
table#listaP tbody {
  display: block;
  height: 295px;/*calc(50vh - 1px);*/
  min-height: 295px; /*calc(200px + 1 px);*/
  /*use calc for fixed ie9 & <*/
  overflow-Y: scroll;
  color: #000;
}
table#listaP tr {
  display: block;
  overflow: hidden;
}
table#listaP tbody tr:nth-child(odd) {
  background: rgba(0,0,0,.2);
}
table#listaP td{
	width: 70px;
	}
table#listaP th {
  width: 70px;
  /*float:left;*/
  color: #ffffff;
  font-weight: bold;
}
table#listaP td {
  padding: 5px;
  border-right: 1px solid rgba(0,0,0,.2);
  vertical-align: middle;
}
table#listaP td:nth-child(2n) {color: #000;}

table#listaP th:last-child, table#listaP td:last-child {
  width: 90px;
  text-align: center;
  border-right: 0 none;
  padding-left: 0;
}


@media only screen and (max-width:600px) {
 table#listaP {
    border-top: 1px solid ;
  }
 table#listaP thead {display: none;}
 table#listaP tbody {
    height: auto;
    max-height: 55vh;
  }
 table#listaP tr {
    border-bottom: 1px solid rgba(0,0,0,.35);
  }
 table#listaP tbody tr:nth-child(odd) {background: #15BFCC;}
 table#listaP tbody tr:nth-child(even) {background:#FF7361;}
 table#listaP td {
    display: block;
    width: 100%;
    min-width: 100%;
    padding: .4rem .5rem .4rem 40%;
    border-right: 0 none;
  }
 table#listaP td:before {
    content: attr(data-campo);
    background: rgba(0,0,0,.1);
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: auto;
    min-width: 37%;
    padding-left: .5rem;
    font-family: monospace;
    font-size: 150%;
    font-variant: small-caps;
    line-height: 1.8;
  }
 table#listaP tbody td:last-child {
    text-align: left;
    padding-left: 40%;
  }
 table#listaP td:nth-child(even) {
    background: rgba(0,0,0,.2);
  }
}
a {color: #FF7361}

</style>
<div class="titulo">
  <div class="subTit"><p class="text_titulo">PEDIDO</p></div>

  <form id="formPreVenta" class="ideal-form" action="javascript:savePedido('formPreVenta','savePedido.php')" >

  <div id="preventa">
  	<div id="preizq">
        <div class="idealWrap WrapPre">
        <label class="ped">FECHA: </label>
        <input id="fecha" name="fecha" type="text" value="<?=$fecha;?> <?=$hora;?>" disabled="disabled" />
        <input id="date" name="date" type="hidden" value="<?=$fecha;?> <?=$hora;?>" />
        </div><!--End idealWrap-->
        <div class="clearfix"></div>

        <div class="idealWrap">
        <label class="ped">Cliente: </label>
        <input id="cliente" name="cliente" type="text" class="validate[required] text-input"/>
        <input id="idCliente" name="idCliente" type="hidden" value="" />
        </div><!--End idealWrap-->
    </div><!--End preizq-->
    <div id="preder">
        <div class="idealWrap WrapPre">
        <label class="ped">N&deg; pedido: </label>
        <input id="pedido" name="pedido" type="text" disabled value="PD-<?=$op->ceros($lastId[0],7);?>"/>
        <input id="pedido" name="pedido" type="hidden" value="<?=$op->ceros($lastId[0],7);?>"/>
        </div><!--End idealWrap-->
    </div><!--End preder-->
    <div class="clearfix"></div>

    <div id="ventIzq">
      <div id="ventIq">
        <p>NUEVO PRODUCTO</p>
        <div class="idealWrap WrapPre">
        <label class="ped">Producto: </label>
        <input id="producto" name="producto" type="text"/>
        </div><!--End idealWrap-->

        <div class="idealWrap WrapPre">
        <label class="ped">Cantidad: </label>
        <input id="cant" name="cant" type="text" autocomplete="off"/>
        </div><!--End idealWrap-->
        <div class="clearfix"></div>

        <div class="idealWrap" align="center">
            <input type="button" id="confPedido" value="Añadir" onclick="adicFila('formPreVenta','producto.php');"/>
        </div>
      </div>

        <div class="idealWrap" align="center">
            <input type="submit" id="submit" value="Confirmar Pedido" class="formPedido"/>
        </div>

        <div class="idealWrap" align="center">
            <input type="button" id="cancelar" value="Cancelar" class="formPedido" onclick=""/>
        </div>

        <div class="idealWrap" align="center">
            <input type="button" id="imprimir" value="Imprimir" class="formPedido" onclick=""/>
        </div>

     </div><!--End ventIzq-->

     <div id="ventCent">

      <table id="tabla" align="center" width="450">
          <thead>
            <tr>
              <th width="270">PRODUCTO</th>
              <th>CANT.</th>
              <th width="60">P. UNIT (Bs)</th>
              <th>DESC.</th>
              <th>BONIF.</th>
              <th width="80">SUBTOTAL (Bs)</th>
              <th id="oculto"></th>
            </tr>
          </thead>
          <tbody>

          </tbody>
          <tfoot hidden="">
              <tr>
                  <th colspan="5">SUB-TOTAL:</th>
                  <th>
                      <input type="text" disabled="disabled" id="subTotal" name="subTotal" value="0" >Bs
                      <input type="hidden" id="subTotal" name="subTotal" value="0" >
                  </th>
                  <th></th>
              </tr>
              <tr>
                  <th colspan="5">DESCUENTO:</th>
                  <th>
                      <input type="text" id="descuento" name="descuento" autocomplete="off" onKeyUp="calculaDes();" > Bs
                  </th>
                  <th></th>
              </tr>
              <tr>
                  <th colspan="5">BONIFICACI&Oacute;N:</th>
                  <th>
                      <input type="text" id="bonificacion" name="bonificacion" autocomplete="off" onKeyUp="calculaDes();" > Bs
                  </th>
                  <th></th>
              </tr>
              <tr>
                  <th colspan="5">TOTAL:</th>
                  <th>
                      <input type="text" disabled="disabled" id="total" name="total" value="0" />Bs
                      <input type="hidden" id="total" name="total" value="0" />
                  </th>
              </tr>
          </tfoot>

      </table >

     </div><!--End ventCent-->

     <div id="ventDer">
      <div id="ventDe">
        <p>FORMA DE PAGO</p>
        <div class="idealWrap WrapV">
        <label class="rp"><input type="radio" value="con" name="tipo" id="tipo" class="validate[required]"><span>&nbsp;</span>AL CONTADO</label>
        <label class="rp"><input type="radio" value="cre" name="tipo" id="tipo" class="validate[required]"><span>&nbsp;</span>AL CREDITO</label>
        </div><!--End idealWrap-->
        <div class="clearfix"></div>
       </div>
        <div class="idealWrap obs">
        <label class="ped">Observaciones: </label>
        <textarea id="obs" name="obs"></textarea>
        </div><!--End idealWrap-->

     </div><!--End ventDer-->

     <div id="ventDer">
      <div id="listaProd">
        <p>LISTA DE PRODUCTOS</p>
        <?PHP
			$sqlProd = "SELECT id_inventario, detalle FROM inventario ORDER BY id_inventario DESC";
			$sql = $db->Execute($sqlProd);
		?>
        <table id="listaP">
        <thead>
        	<tr>
            	<th>CODIGO</th>
                <th>DETALLE</th>
            </tr>
        </thead>
        <tbody>
        	<?PHP
        	 while( $row = $sql->FetchRow()){
			?>
            <tr>
            	<td><?=$row[0]?></td>
                <td><?=$row[1]?></td>
            </tr>
            <?PHP
			 }
			?>
        </tbody>
        </table>
       </div>
     </div><!--End ventDer-->

  <div class="clearfix"></div>
  </div><!--End preventa-->

  </form>
  <div class="clearfix"></div>
</div><!--End titulo-->


<script type="text/javascript" charset="utf-8">
//========DataTables========
var oTable;
$(document).ready(function() {

	function log( message ) {
		$( "input#idCliente" ).val( message );
		//$( "#log" ).scrollTop( 0 );
	}
	$( "#cliente" ).autocomplete({
		source: "classes/search.php",
		minLength: 2,
		select: function( event, ui ) {
			log( ui.item.id

				/*"Selected: " + ui.item.value + " aka " + ui.item.id :
				"Nothing selected, input was " + this.value*/
				);
		}
	});
 	/* idealForm */
	$('#formPreVenta').idealForms();
	/* Validación */
	jQuery("#formPreVenta").validationEngine({
		prettySelect	: true,
		useSuffix		: "_chosen"
	   // scroll		: false,
	});

	deleteRow = function(p, idTr, table){

	var respuesta = confirm("SEGURO QUE DESEA ELIMINAR EL "+" ' "+table.toUpperCase()+" ' ");

	if(respuesta){
		var i = 1;
		$('#tb'+idTr).addClass('row_selected');
		var anSelected = fnGetSelected( oTable );

		if ( anSelected.length !== 0 ) {
			oTable.fnDeleteRow( anSelected[0] );
			deleteRowBD(p, idTr, table);
		}
	}
  };
});
</script>