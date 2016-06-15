<?php
include("../adodb5/adodb.inc.php");

$db = NewADOConnection('mysqli');
//$db->debug = true;
$db->Connect("localhost", "root", "mysql", "bd_elviejoroble");	

/* RECEIVE VALUE */

$validateValue=$_REQUEST['fieldValue'];
$validateId=$_REQUEST['fieldId'];

$validateError= "El usuario ya está en uso";
$validateSuccess= "El usuario es valido";

$sql = "SELECT user FROM usuario WHERE user = '".$validateValue."' ";	
$strQ = $db->Execute($sql);
$row = $strQ->FetchRow();

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