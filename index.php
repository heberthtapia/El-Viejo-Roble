<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Ingreso Sistema</title>
</head>
<link rel="stylesheet" type="text/css" href="css/style_log.css">
<link rel="stylesheet" type="text/css" href="css/validationEngine.jquery.css">

<script type="text/javascript" src="js/jquery-1.7.2.js"></script>
<script type="text/javascript" src="js/jquery.json-2.3.js"></script>

<script type="text/javascript" src="js/jquery.validationEngine.js"></script>
<script type="text/javascript" src="js/jquery.validationEngine-es.js"></script>

<script src="js/login.js"></script>

<script>
	jQuery(document).ready(function(){
		$h = $(window).height();
		$h = $h/2;
		$h = Math.floor($h);
		$top = $h - 139;
		$('#logueo').css('margin-top',$top);

		$w = $(window).width();
		$w = $w/2;
		$w = Math.floor($w);
		$left = $w - 125;		//alert($left);
		$('#error').css('margin-left',$left);
		$('#error').css('margin-top',-$top);

		// binds form submission and fields to the validation engine

		jQuery("#login").validationEngine();
	});
</script>
<body>

	<div id="error"></div>
	<div id="logueo">
    	<div id="title">
        	<p>ACCEDER AL SISTEMA</p>
        </div><!--End title-->
    	<div class="linea"><img src="images/linea.png" width="341" height="2" alt="linea" /></div>
        <p><br /><br />Por favor ingrese su usuario y contrase&ntilde;a para continuar</p>
        <div id="log_form">
        	<form id="login" name="login" action="javascript:verifica('login','password.php');">
            	<input type="text" id="username" name="username" placeholder="Ingrese su usuario" value="" autofocus class="validate[required]" autocomplete="off" />
                <input type="password" id="password" name="password" placeholder="Ingrese su contrase&ntilde;a" class="validate[required]" />
                <input type="submit" id="submit" name="submit" value="INGRESAR" />
            </form>
        </div><!--End log_form-->
        <div id="lock"></div><!--End lock-->
        <div class="clearfix"></div>
        <div class="linea"><img src="images/linea.png" width="341" height="2" alt="linea" /></div>
    </div><!--End logueo-->

</body>
</html>
<style>
.formError .formErrorContent{
	font-size:11px;
	}
</style>