<?php 

// LCC.14082002 Corregido error al eliminar archivos

define("TEMP_DIR", "tmp/");


// Borrar archivos adjuntados
function delete_files ($files) {
	if (is_array($files)) {
		foreach($files as $file) {
			
			if (!empty($file)) {
				if (file_exists($file)) {
					@unlink(TEMP_DIR.basename($GLOBALS[$file]));							
				}
			}

		}
	}	
}

require_once("global/class.template.php");
require_once("global/class.phpmailer.php");
 
// Validaciones Iniciales
ASSERT(!empty($_POST["redirect"]) && !empty($_POST["subject"]) && !empty($_POST["recipient"]));


$mail = new phpmailer();
$mail->PluginDir = "";
$mail->IsMail();

$destiny = explode (";", $_POST["recipient"]); 
foreach($destiny as $recipient) 
{
	$recipient = str_replace("_no_spam_", "@", $recipient);
	$mail->AddAddress($recipient, $recipient);
}
$mail->From     = empty($_POST["email"]) ? $recipient : $_POST["email"];
$mail->FromName = empty($_POST["nombre"]) ? $recipient : $_POST["nombre"]; 

// Texto HTML
if (!empty($_POST["html_file"])) {

	$mail->IsHTML(true);	

	$html_template=new template();
	$html_template->setfile($_POST["html_file"]);

	foreach($_POST as $key=>$value) {
		$key=strtoupper($key);
		$html_template->assign($key, $value);
	}
	$html_template->assign("SERVER", $_SERVER['SERVER_NAME']);
}                       

// Texto Plano
if (!empty($_POST["text_file"])) {

	$text_template=new template();
	$text_template->setfile($_POST["text_file"]);

	foreach($_POST as $key=>$value) {
		$key=strtoupper($key);
		$text_template->assign($key, $value);
	}
	$mail->AltBody  =  $text_template->get();	
}

$mail->Subject  =  $_POST["subject"];
$mail->Body     =  empty($_POST["html_file"]) ? $text_template->get() : $html_template->get();

// Adjuntar archivos
if (!empty($files)) {
	$files=split(",", $files);	
	foreach($files as $file) {
		if (is_uploaded_file($$file)) {
			if (move_uploaded_file($$file, TEMP_DIR.basename($$file))) {
				$mail->AddAttachment(TEMP_DIR.basename($$file), ${$file."_name"});					
			}
		}
	}
}

//$html_template->show();
// Envio del Correo (3 intentos)
if($mail->Send()) {
	delete_files($files);
	header("location: ".$_POST["redirect"]);	
	exit;
} else {
	// Intenta enviar via SMTP local
	$mail->IsSMTP();               // Enviar via SMTP
	$mail->Host     = "localhost"; // Servidores SMTP

	if ($mail->Send()) {
		delete_files($files);
		header("location: ".$_POST["redirect"]);
		exit;
	} else {
		// Intenta enviar via SMTP
		$mail->Host     = "http://www.dieseltuning.cl/"; // Servidores SMTP

		if ($mail->Send()) {
			delete_files($files);
			header("location: ".$_POST["redirect"]);
			exit;
		} else {
			echo "Message was not sent <p>";
			echo "Mailer Error: " . $mail->ErrorInfo;		
		}
	}
}

?>