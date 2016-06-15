<?PHP
	session_start();
    $cargo = $_SESSION['cargo'];
	//echo $_SESSION["idUser"];
?>
<div id="inicio">
    <?PHP
        if($cargo == 'adm'||$cargo == 'pre'||$cargo == 'alm'){
    ?>
	<div class="subMenu">    	
        <a onClick="despliega('modulo/pedido/pedido.php','contenido');" >
        	<img src="images/menu1.jpg" width="50">
            <p class="tMenu">Pedidos</p>
            <p>Mas informaci&oacute;n</p>
        </a>
    </div><!--End subMenu-->
    <?PHP
        }if($cargo == 'adm'){
    ?>
	<div class="subMenu">
    	<a onClick="despliega('modulo/empleado/empleado.php','contenido');" >
        	<img src="images/menu1.jpg" width="50">
            <p class="tMenu">Empleados</p>
            <p>Mas informaci&oacute;n</p>
        </a>          
    </div><!--End subMenu-->
    <?PHP
        }if($cargo == 'adm'||$cargo == 'pre'){
    ?>
    <div class="subMenu">    	
        <a onClick="despliega('modulo/cliente/cliente.php','contenido');">
        	<img src="images/menu1.jpg" width="50">
            <p class="tMenu">Clientes</p>
            <p>Mas informaci&oacute;n</p>
        </a>
    </div><!--End subMenu-->
    <?PHP
        }if($cargo == 'adm'||$cargo == 'alm'||$cargo == 'fab'){
    ?>
    <div class="subMenu">    	
        <a onClick="despliega('modulo/produccion/produccion.php','contenido');">
        	<img src="images/menu1.jpg" width="50">
            <p class="tMenu">Producci&oacute;n</p>
            <p>Mas informaci&oacute;n</p>
        </a>
    </div><!--End subMenu-->
    <?PHP
        }if($cargo == 'adm'||$cargo == 'con'||$cargo == 'alm'||$cargo == 'fab'){
    ?>
    <div class="subMenu">    	
        <a onClick="despliega('modulo/producto/producto.php','contenido');">
        	<img src="images/menu1.jpg" width="50">
            <p class="tMenu">Almacen</p>
            <p>Mas informaci&oacute;n</p>
        </a>
    </div><!--End subMenu-->
    <?PHP
        }
    ?>
    <div class="subMenu">    	
        <a onClick="">
        	<img src="images/menu1.jpg" width="50">
            <p class="tMenu">Reportes</p>
            <p>Mas informaci&oacute;n</p>
        </a>
    </div><!--End subMenu-->
    
    <div class="clearfix"></div>
</div><!--End inicio-->
<script type="text/javascript">
    $(document).ready(function(e) {
		var i=0;
        $('div#inicio').find('div.subMenu').each(function(index, element) {                          
        	i++;
            if(i%2==0){
			 $(this).addClass('der');
            }
        });
    });
</script>