<?PHP	
	session_start();
	
	include '../adodb5/adodb.inc.php';
	include '../classes/function.php';
	
	$db = NewADOConnection('mysqli');
	//$db->debug = true;
	$db->Connect();
	
	$op = new cnFunction();
	
	$id		= $_POST['id'];
	$table 	= $_POST['table'];
	
	/* verificar si esta activo */
	
	$strQuery = "SELECT status FROM $table WHERE id_$table = '$id'";
	
	$sql = $db->Execute($strQuery);
	$reg = $sql->FetchRow();
	
	if( $reg[0] == 'Activo' )
		$status = 2;
	else
		$status = 1;
	
	/***********************************/
	
	$strQuery = "UPDATE $table SET status = '".$status."' WHERE id_$table = '$id'";
	$str = $db->Execute($strQuery);
	
	if($str)
		echo 1;
	else
		echo 0;
?>