<?     
// PREPARE THE BODY OF THE MESSAGE
$message = '<html><body>';			
$message = '<link rel="stylesheet" href="http://violenciasexualcomercial.org.bo/css/style_envio.css" type="text/css" >';
$message .= '<div align="center" style="margin:auto;">';
$message .= '<div><img src="http://violenciasexualcomercial.org.bo/images/baner-correo.jpg" width="500" alt="MCVSC-EA" /></div>';
$message .= '<table id="correo" >';
$message .= '<tr><td colspan="2"></td></tr>';
$message .= '<tr><td><strong>Los siguientes datos son para ingresar al sistema.</strong></td></tr>';
//$message .= '<tr><td colspan="2" align="left" style="padding-left:60px;">' . strip_tags($_POST['nombre']) . '</td></tr>';
$message .= '<tr><td><strong>Usuario:</strong> </td>';
$message .= '<tr><td colspan="2" align="left" style="padding-left:60px;">'.$usario.'</td></tr>';
$message .= '<tr><td><strong>Password:</strong> </td>';
$message .= '<tr><td colspan="2" align="left" style="padding-left:60px;">'.$password.'</td></tr>';	
$message .= '<tr><td><strong>Pagina Web:</strong> </td>';
$message .= '<tr><td colspan="2" style="padding:20px; text-align:justify;">http://localhost/ElViejoRoble/admin.php</td></tr>';
$message .= '</table>';
$message .= '</div>';
$message .= "</body></html>";

$cleanedFrom = $email;

//   CHANGE THE BELOW VARIABLES TO YOUR NEEDS

$to = 'h.tapia@medicaltechbo.com';

$subject = strip_tags('Datos para el sistema');

$headers = "From: ".$cleanedFrom."\r\n";
$headers .= "Reply-To: ".$cleanedFrom."\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

if (mail($to, $subject, $message, $headers)) {
   echo true;     
} else {
	echo false;
}           
?>