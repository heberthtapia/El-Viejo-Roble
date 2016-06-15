<?PHP
  ini_set("session.use_trans_sid","0");
  ini_set("session.use_only_cookies","1");

  session_start();

  date_default_timezone_set("America/La_Paz" ) ;
  session_set_cookie_params(0,"/",$_SERVER["HTTP_HOST"],0);

  include 'adodb5/adodb.inc.php';
  include 'classes/function.php';
  
  $op = new cnFunction();

  $db = NewADOConnection('mysqli');
  //$db->debug = true;
  $db->Connect();

  if(!isset($_SESSION['idUser'])){
	  header('location:index.php');
  }else{
	  $fechaGuardada = $_SESSION["ultimoAcceso"];
	  $ahora = date("Y-n-j H:i:s");
	  $tiempo_transcurrido = (strtotime($ahora)-strtotime($fechaGuardada));

	  if($tiempo_transcurrido >= 2160){
		  $user = $_SESSION["idUser"];
		  $strQuery = 'UPDATE usuario SET status = "Inactivo", dateReg = "0000-00-00 00:00:00" WHERE id_usuario = "'.$user.'"';
		  $str = $db->Execute($strQuery);
		  session_destroy();
		  header('location:index.php');
	  }else{
		  $_SESSION["ultimoAcceso"] = $ahora;
		  }
  }

  $sql = 'SELECT * ';
  $sql.= 'FROM empleado ';
  $sql.= 'WHERE id_empleado = '.$_SESSION['idEmp'].'';

  $reg = $db->Execute($sql);

  $row = $reg->FetchRow();

  $inc = strtoupper($row['nombre']);  
  $incp = strtoupper($row['apP']);
    
  $_SESSION['inc'] = $inc[0].''.$incp[0].'-';
  
  $cargo = $op->toSelect($row['cargo']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>El Viejo Roble</title>
</head>
<link rel="stylesheet" type="text/css" href="css/style.css">

<link rel="stylesheet" type="text/css" href="css/validationEngine.jquery.css"/>
<!--IdealForms-->
<link rel="stylesheet" type="text/css" href="css/idealForms/idealForms.css" media="screen"/>
<link rel="stylesheet" type="text/css" href="css/idealForms/idealForms-theme-sapphire.css" media="screen"/>
<!--Calendario-->
<link rel="stylesheet" type="text/css" href="css/jquery.ui.all.css" media="screen"/>
<!--ColorBox-->
<link rel="stylesheet" type="text/css" href="css/colorbox.css" media="screen"/>
<!--uploadIfy-->
<link rel="stylesheet" type="text/css" href="uploadify/uploadify.css"  media="screen"/>
<!--Autocomplete-->
<link rel="stylesheet" type="text/css" href="css/jquery.ui.autocomplete.css"  media="screen"/>

<link rel="stylesheet" type="text/css" href="css/tooltipster.css">
<link rel="stylesheet" type="text/css" href="css/tooltipster-shadow.css">
<!--DateTables-->
<style type="text/css" title="currentStyle">
	@import "css/demo_page.css";
	@import "css/ColVisAlt.css";
	/*@import "css/jquery.dataTables_themeroller.css";*/
	@import "css/demo_table_jui.css";
</style>
<!--<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>-->
<script type="text/javascript" src="js/jquery-1.7.2.js"></script>
<!--<script type="text/javascript" src="js/jquery-1.8.0.js"></script>-->
<!--<script type="text/javascript" src="js/jquery-1.11.3.js"></script>-->
<script type="text/javascript" src="js/jquery.validationEngine.js"></script>
<script type="text/javascript" src="js/jquery.validationEngine-es.js"></script>
<!--Calendario-->
<script type="text/javascript" src="js/jquery.ui.core.js"></script>
<script type="text/javascript" src="js/jquery.ui.widget.js"></script>
<script type="text/javascript" src="js/jquery.ui.datepicker.js"></script>
<script type="text/javascript" src="js/jquery.ui.datepicker-es.js"></script>
<!--Autocomplete-->
<script type="text/javascript" src="js/jquery.ui.position.js"></script>
<script type="text/javascript" src="js/jquery.ui.autocomplete.js"></script>
<!--ToolTip-->
<script src="js/jquery.ui.tooltip.js"></script>
<!--Data Tables-->
<script src="js/dataTables/jquery.dataTables.js"></script>
<script src="js/dataTables/ColVis.js"></script>
<!--Ideal Forms-->
<script src="js/idealForms/jquery-idealForms.js"></script>
<!--ColorBox-->
<script type="text/javascript" src="js/jquery.colorbox.js"></script>
<!--uploadIfy-->
<script type="text/javascript" src="uploadify/jquery.uploadify-3.1.js"></script>
<!--CKEDITOR-->
<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="ckeditor/adapters/jquery.js"></script>
<!--Json-->
<script type="text/javascript" src="js/jquery.json-2.3.js"></script>
<!--Jquery-Combo-->
<script type="text/javascript" src="js/jquery.jCombo.js"></script>
<!--Chozen-->
<link rel="stylesheet" type="text/css" href="css/chosen.css" media="screen"/>
<script src="js/jquery.tooltipster.js"></script>

<script type="text/javascript" src="js/miScript.js"></script>

<script>
	var txt="EL VIEJO ROBLE - SISTEMA DE GESTION -";
	var espera=200;
	var refresco=null;
	function rotulo_title() {
			document.title=txt;
			txt=txt.substring(1,txt.length)+txt.charAt(0);
			refresco=setTimeout("rotulo_title()",espera);}
	    rotulo_title();

	$(document).ready(function(e) {
		despliega('modulo/inicio/inicio.php','contenido');
	});

</script>

<body>
  <div id="container">
    <div id="head">
      <div id="title">
        <p>el viejo roble</p>
      </div><!--End title-->
      <div id="usuario">
        <p><?=$cargo;?></p>
        <p id="user">Bienvenido...........: <?=$inc[0];?>.&nbsp;&nbsp; <?=$row['apP'];?></p>
        <p id="session"><a href="Javascript:void(0);" onclick="outSession('<?=$_SESSION['idUser'];?>');">Cerrar Sesi&oacute;n</a></p>
      </div><!--End usuario-->
      <div class="clearfix"></div>
      <div id="menu">
        <ul>
          <li><a href="Javascript:void(0);" onclick="despliega('modulo/inicio/inicio.php','contenido');">Inicio</a></li>
          <?PHP
          	if($row['cargo'] == 'adm'||$row['cargo'] == 'pre'||$row['cargo'] == 'alm'){
		  ?>
          <li><a href="Javascript:void(0);" onclick="despliega('modulo/pedido/pedido.php','contenido');">Pedidos</a></li>
          <?PHP
			}if($row['cargo'] == 'adm'){
          ?>
          <li><a href="Javascript:void(0);" onclick="despliega('modulo/empleado/empleado.php','contenido');">Empleados</a></li>
          <?PHP
			}if($row['cargo'] == 'adm'||$row['cargo'] == 'pre'){
          ?>
          <li><a href="Javascript:void(0);" onclick="despliega('modulo/cliente/cliente.php','contenido');">Clientes</a></li>
          <?PHP
			}if($row['cargo'] == 'adm'||$row['cargo'] == 'alm'||$row['cargo'] == 'fab'){
          ?>
          <li><a href="Javascript:void(0);" onclick="despliega('modulo/produccion/produccion.php','contenido');">Producci&oacute;n</a></li>
          <?PHP
			}if($row['cargo'] == 'adm'||$row['cargo'] == 'con'||$row['cargo'] == 'alm'||$row['cargo'] == 'fab'){
          ?>
          <li><a href="Javascript:void(0);" onclick="despliega('modulo/producto/producto.php','contenido');">Productos</a></li>
          <?PHP
			}
		  ?>
          <li><a href="">Reportes</a></li>
        </ul>
      </div><!--End menu-->
    </div><!--End head-->
    <div class="clearfix"></div>
    <div id="contenido">
    </div><!--End contenido-->
    <div id="footer">
    </div><!--End foter-->
  </div><!--End container-->
</body>
</html>