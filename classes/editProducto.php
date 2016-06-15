<?PHP
	session_start();
	
	include '../adodb5/adodb.inc.php';
	include '../classes/function.php';
	
	$db = NewADOConnection('mysqli');
	//$db->debug = true;
	$db->Connect();
	
	$op = new cnFunction();
	
	$fecha = $op->ToDay();    
	$hora = $op->Time();	

	$data = stripslashes($_POST['res']);	
	$data = json_decode($data);
	
	if( count($data->cantidad) > 1 ){
	
	  foreach( $data->cantidad as $k => $valor ){
		
		$sqlCant = "SELECT cantidad FROM inventario WHERE id_inventario = '".$data->item[$i]."' ";
		
		$sqlReg = $db->Execute($sqlCant);
		$regCant = $sqlReg->FetchRow();
		
		$cantidad = $regCant[0] + $data->cantidad[$i];
		
		$strInv = "UPDATE inventario SET cantidad = '".$cantidad."' WHERE id_inventario = '".$data->item[$i]."' ";
		
		$sqlInv = $db->Execute($strInv);
		
		$i++;
		  
	  }
	  	
	}else{
		
		$sqlCant = "SELECT cantidad FROM inventario WHERE id_inventario = '".$data->item."' ";
		
		$sqlReg = $db->Execute($sqlCant);
		$regCant = $sqlReg->FetchRow();
		
		$cantidad = $regCant[0] + $data->cantidad;
		
		$strInv = "UPDATE inventario SET cantidad = '".$cantidad."' WHERE id_inventario = '".$data->item."' ";
		
		$sqlInv = $db->Execute($strInv);		
				
		$i++;		
	}
	
	//print_r($data);
	$strQuery = "SELECT * FROM inventario WHERE id_inventario = '".$data->producto."' ";
		
	$sql = $db->Execute($strQuery);
	$row = $sql->FetchRow();
	
	$data->detalle		= $row['detalle'];
	$data->volumen		= $row['volumen'];
	$data->cantidad		= $row['cantidad'];
	$data->precio		= $row['precio'];	

	if($row)	
		echo json_encode($data);
	else
		echo 0;	
?>