<?php
include("../adodb5/adodb.inc.php");

$db = NewADOConnection('mysqli');
//$db->debug = true;
$db->Connect("localhost", "root", "mysql", "bd_elviejoroble");	

/* RECEIVE VALUE */

$validateValue=$_REQUEST['fieldValue'];
$validateId=$_REQUEST['fieldId'];

$validateError= "This username is already taken";
$validateSuccess= "This username is available";

$strQuery = "SELECT * FROM empleado WHERE id_empleado = '".$validateValue."' ";
$sql = $db->Execute($strQuery);
$row = $sql->FetchRow();

	/* RETURN VALUE */
	$arrayToJs = array();
	$arrayToJs[0] = $validateId;

if($validateValue != $row[0]){		// validate??
	$arrayToJs[1] = true;			// RETURN TRUE
	echo json_encode($arrayToJs);			// RETURN ARRAY WITH success
}else{
	for($x=0;$x<1000000;$x++){
		if($x == 990000){
			$arrayToJs[1] = false;
			echo json_encode($arrayToJs);		// RETURN ARRAY WITH ERROR
		}
	}	
}
?>