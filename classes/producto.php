<?PHP
	session_start();
	
	include '../adodb5/adodb.inc.php';
	include '../classes/function.php';
	
	$db = NewADOConnection('mysqli');
	//$db->debug = true;
	$db->Connect("localhost", "root", "mysql", "bd_elviejoroble");
	
	$op = new cnFunction();
	
	$fecha = $op->ToDay();    
	$hora = $op->Time();	

	$data = stripslashes($_POST['res']);	
	$data = json_decode($data);
	
	$strQuery = "SELECT * FROM inventario WHERE id_inventario = '".$data->producto."' ";
		
	$sql = $db->Execute($strQuery);
	$row = $sql->FetchRow();
	
	$data->detalle	= $row['detalle'];
	$data->volumen	= $row['volumen'];
	$data->cantI	= $row['cantidad'];
	$data->precio	= $row['precioCF'];	

	if($row)	
		echo json_encode($data);
	else
		echo 0;	
?>